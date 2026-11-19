<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Services\BankService;

/**
 * ValidPsid Rule
 *
 * Validates that a string is a structurally valid PSID:
 *  - Must be numeric
 *  - Must match the bank-prefixed length (e.g. 24 digits), legacy 12 digits, or legacy 20 digits
 *  - Must pass the Luhn check digit verification
 *
 * This rule is version-agnostic — it accepts active prefixed PSIDs, legacy 12-digit
 * V2, and legacy 20-digit V1 PSIDs simultaneously, so all valid records pass validation.
 */
class ValidPsid implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = (string) $value;

        if (!is_numeric($value)) {
            $fail('The :attribute must be a numeric PSID.');
            return;
        }

        $clean = BankService::normalizePsid($value);
        $len = strlen($clean);

        if ($len !== 12 && $len !== 20) {
            $prefixes = BankService::getAllBankPrefixes();
            $expectedLens = array_unique(array_map(fn($p) => strlen($p) + 12, $prefixes));
            $expectedStr = implode(', ', array_merge(['12', '20'], array_map(fn($l) => (string) $l, $expectedLens)));
            $fail("The :attribute must be a valid 1Bill PSID ({$expectedStr} digits).");
            return;
        }

        if (!BankService::validatePsid($clean)) {
            $fail('The :attribute has an invalid checksum. Please verify the PSID.');
        }
    }
}
