<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use App\Models\Citizen;

class BankApiSecurityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Config::set('bank.sandbox.callback_token', 'test_secret_token_123');
        Config::set('bank.prefix', '100000000000');
        Config::set('bank.ip_whitelist.enabled', false);
        Config::set('bank.sandbox.enabled', true);
    }

    public function test_payment_inquiry_fails_without_authorization_header(): void
    {
        $response = $this->postJson('/api/payment/inquiry', [
            'consumer_number' => '126232813031',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status'  => '99',
                'message' => 'Unauthorized: Missing Authorization header',
            ]);
    }

    public function test_payment_inquiry_fails_with_invalid_token(): void
    {
        $response = $this->postJson('/api/payment/inquiry', [
            'consumer_number' => '126232813031',
        ], [
            'Authorization' => 'Bearer wrong_token',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status'  => '99',
                'message' => 'Unauthorized: Invalid token',
            ]);
    }

    public function test_payment_inquiry_fails_closed_when_token_is_unconfigured(): void
    {
        Config::set('bank.sandbox.callback_token', '');

        $response = $this->postJson('/api/payment/inquiry', [
            'consumer_number' => '126232813031',
        ], [
            'Authorization' => 'Bearer test_secret_token_123',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status'  => '99',
                'message' => 'Unauthorized: Authentication service misconfigured',
            ]);
    }

    public function test_1link_inquiry_requires_valid_bearer_token(): void
    {
        // Unauthenticated attempt should fail
        $unauthResponse = $this->postJson('/api/1link/inquiry', [
            'consumer_number' => '126232813031',
        ]);
        $unauthResponse->assertStatus(401);

        // Valid token attempt passes auth check (may 404 on non-existent PSID, which proves it passed auth)
        $authResponse = $this->postJson('/api/1link/inquiry', [
            'consumer_number' => '126232813031',
        ], [
            'Authorization' => 'Bearer test_secret_token_123',
        ]);
        $this->assertNotEquals(401, $authResponse->status());
    }

    public function test_ip_whitelist_allows_any_ip_when_disabled(): void
    {
        Config::set('bank.ip_whitelist.enabled', false);

        $response = $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.50'])
            ->postJson('/api/payment/inquiry', [
                'consumer_number' => '126232813031',
            ], [
                'Authorization' => 'Bearer test_secret_token_123',
            ]);

        // Auth passed, did not get 403 Forbidden
        $this->assertNotEquals(403, $response->status());
    }

    public function test_ip_whitelist_blocks_unauthorized_ip_when_enabled(): void
    {
        Config::set('bank.ip_whitelist.enabled', true);
        Config::set('bank.ip_whitelist.allowed_ips', ['192.168.1.100', '10.0.0.0/24']);

        $response = $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.50'])
            ->postJson('/api/payment/inquiry', [
                'consumer_number' => '126232813031',
            ], [
                'Authorization' => 'Bearer test_secret_token_123',
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'status'  => '99',
                'message' => 'Forbidden: IP address not authorized',
            ]);
    }

    public function test_ip_whitelist_permits_authorized_ip_when_enabled(): void
    {
        Config::set('bank.ip_whitelist.enabled', true);
        Config::set('bank.ip_whitelist.allowed_ips', ['192.168.1.100', '10.0.0.0/24']);

        // Testing direct IP match
        $response = $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.100'])
            ->postJson('/api/payment/inquiry', [
                'consumer_number' => '126232813031',
            ], [
                'Authorization' => 'Bearer test_secret_token_123',
            ]);

        $this->assertNotEquals(403, $response->status());

        // Testing CIDR subnet match
        $cidrResponse = $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.45'])
            ->postJson('/api/payment/inquiry', [
                'consumer_number' => '126232813031',
            ], [
                'Authorization' => 'Bearer test_secret_token_123',
            ]);

        $this->assertNotEquals(403, $cidrResponse->status());
    }

    public function test_mock_bank_routes_disabled_when_sandbox_disabled(): void
    {
        Config::set('bank.sandbox.enabled', false);

        $response = $this->postJson('/api/mock-bank/generate-psid', [
            'head'   => 'MEDICAL',
            'amount' => 200,
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'status'  => '99',
                'message' => 'Mock bank sandbox is disabled in this environment.',
            ]);
    }

    public function test_citizens_check_requires_web_session_authentication(): void
    {
        // Unauthenticated request is rejected or redirected to login
        $guestResponse = $this->getJson('/api/citizens/check/3310012345671');
        $this->assertTrue(in_array($guestResponse->status(), [401, 302]));
    }
}
