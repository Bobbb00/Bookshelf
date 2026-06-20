<?php

namespace App\Validation;

use Myth\Auth\Authentication\Passwords\ValidationRules;

class PasswordRules extends ValidationRules
{
    /**
     * Custom strong_password validation rule that checks:
     * 1. Minimum 8 characters.
     * 2. Combination of uppercase, lowercase, numbers, and symbols.
     * 3. Myth\Auth default password validation logic.
     */
    public function strong_password(string $value, ?string &$error = null, array $data = [], ?string &$error2 = null): bool
    {
        // Check minimum 8 characters
        if (strlen($value) < 8) {
            $errText = "Password minimal harus 8 karakter.";
            $error = $errText;
            if ($error2 !== null) {
                $error2 = $errText;
            }
            return false;
        }

        // Check combination of uppercase, lowercase, numbers, and symbols
        $hasUppercase = preg_match('/[A-Z]/', $value);
        $hasLowercase = preg_match('/[a-z]/', $value);
        $hasNumber    = preg_match('/[0-9]/', $value);
        $hasSpecial   = preg_match('/[^a-zA-Z0-9]/', $value);

        if (!$hasUppercase || !$hasLowercase || !$hasNumber || !$hasSpecial) {
            $errText = "Password harus mengandung kombinasi huruf besar, huruf kecil, angka, dan simbol.";
            $error = $errText;
            if ($error2 !== null) {
                $error2 = $errText;
            }
            return false;
        }

        // Run the base Myth\Auth password checks (common check, dictionary, personal info check)
        $parentPass = parent::strong_password($value, $parentError, $data, $parentError2);
        if (!$parentPass) {
            // Translate common English errors to Indonesian for better UX if needed
            $errText = $parentError ?? $parentError2 ?? "Password terlalu lemah.";
            if (strpos($errText, 'common password') !== false) {
                $errText = "Password ini terlalu umum digunakan.";
            } elseif (strpos($errText, 'personal information') !== false || strpos($errText, 'similar to the username') !== false) {
                $errText = "Password tidak boleh mirip dengan username atau mengandung informasi pribadi.";
            }
            
            $error = $errText;
            if ($error2 !== null) {
                $error2 = $errText;
            }
            return false;
        }

        return true;
    }
}
