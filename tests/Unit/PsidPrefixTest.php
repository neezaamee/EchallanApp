<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\BankService;
use App\Rules\ValidPsid;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class PsidPrefixTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('bank.prefix', '111787477100');
        Config::set('bank.prefixes', [
            'MEDICAL'       => '111787477101',
            'TRAFFIC_CAR'   => '111787477102',
            'TRAFFIC_BIKE'  => '111787477103',
            'TRAFFIC_OTHER' => '111787477104',
        ]);
        Config::set('bank.psid_version', 12);
    }

    public function test_category_prefixes_resolution(): void
    {
        $this->assertEquals('111787477101', BankService::getBankPrefix('MEDICAL'));
        $this->assertEquals('111787477102', BankService::getBankPrefix('TRAFFIC_CAR'));
        $this->assertEquals('111787477102', BankService::getBankPrefix('CAR'));
        $this->assertEquals('111787477103', BankService::getBankPrefix('TRAFFIC_BIKE'));
        $this->assertEquals('111787477103', BankService::getBankPrefix('BIKE'));
        $this->assertEquals('111787477104', BankService::getBankPrefix('TRAFFIC_OTHER'));
        $this->assertEquals('111787477100', BankService::getBankPrefix('UNKNOWN_HEAD'));
    }

    public function test_all_bank_prefixes_list(): void
    {
        $all = BankService::getAllBankPrefixes();
        $this->assertContains('111787477100', $all);
        $this->assertContains('111787477101', $all);
        $this->assertContains('111787477102', $all);
        $this->assertContains('111787477103', $all);
        $this->assertContains('111787477104', $all);
    }

    public function test_generate_psid_uses_category_specific_prefix(): void
    {
        $medicalPsid = BankService::generatePsid('MEDICAL', 500);
        $carPsid     = BankService::generatePsid('TRAFFIC_CAR', 2000);
        $bikePsid    = BankService::generatePsid('TRAFFIC_BIKE', 200);

        $this->assertSame(24, strlen($medicalPsid));
        $this->assertSame(24, strlen($carPsid));
        $this->assertSame(24, strlen($bikePsid));

        $this->assertStringStartsWith('111787477101', $medicalPsid);
        $this->assertStringStartsWith('111787477102', $carPsid);
        $this->assertStringStartsWith('111787477103', $bikePsid);

        // Core 12 digits: category code is first digit after prefix
        $normMedical = BankService::normalizePsid($medicalPsid);
        $normCar     = BankService::normalizePsid($carPsid);
        $normBike    = BankService::normalizePsid($bikePsid);

        $this->assertSame(12, strlen($normMedical));
        $this->assertSame('1', $normMedical[0]);

        $this->assertSame(12, strlen($normCar));
        $this->assertSame('2', $normCar[0]);

        $this->assertSame(12, strlen($normBike));
        $this->assertSame('3', $normBike[0]);
    }

    public function test_psid_validation_and_normalization(): void
    {
        $medicalPsid = BankService::generatePsid('MEDICAL', 500);
        $this->assertTrue(BankService::validatePsid($medicalPsid));

        $cleanMedical = BankService::normalizePsid($medicalPsid);
        $this->assertTrue(BankService::validatePsid($cleanMedical));

        $validator = Validator::make(['psid' => $medicalPsid], ['psid' => [new ValidPsid()]]);
        $this->assertTrue($validator->passes());

        // Tampered checksum should fail
        $tampered = substr($medicalPsid, 0, -1) . (($medicalPsid[23] === '9') ? '0' : '9');
        $this->assertFalse(BankService::validatePsid($tampered));
    }
}
