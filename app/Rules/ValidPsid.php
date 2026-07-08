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
 *  - Must be exactly 20 digits (V1) OR exactly 12 digits (V2)
 *  - Must pass the Luhn check digit verification
 *
 * This rule is version-agnostic — it accepts BOTH 12 and 20 digit PSIDs
 * simultaneously, so old and new records always pass validation regardless
 * of which PSID_VERSION is currently active.
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

        $len = strlen($value);

        if ($len !== 20 && $len !== 12) {
            $fail('The :attribute must be a valid 12-digit or 20-digit PSID.');
            return;
        }

        if (!BankService::validatePsid($value)) {
            $fail('The :attribute has an invalid checksum. Please verify the PSID.');
        }
    }
}
