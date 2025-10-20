<?php
/**
 * Validation Helper
 * Common validation functions
 */

class ValidationHelper
{
    private $errors = [];

    public function required($value, $fieldName)
    {
        if (empty(trim($value))) {
            $this->errors[] = "{$fieldName} is required";
            return false;
        }
        return true;
    }

    public function email($value, $fieldName = 'Email')
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Valid {$fieldName} is required";
            return false;
        }
        return true;
    }

    public function minLength($value, $length, $fieldName)
    {
        if (strlen($value) < $length) {
            $this->errors[] = "{$fieldName} must be at least {$length} characters";
            return false;
        }
        return true;
    }

    public function match($value1, $value2, $fieldName)
    {
        if ($value1 !== $value2) {
            $this->errors[] = "{$fieldName} do not match";
            return false;
        }
        return true;
    }

    /**
     * Validate username: at least 3 chars, only letters and numbers (no spaces/symbols)
     */
    public function validateUsernameStrict($value, $fieldName = 'Username')
    {
        $ok = true;
        // If the strict pattern fails, diagnose specific issues for clearer messages
        if (!preg_match('/^[A-Za-z0-9]{3,}$/', $value)) {
            if (strlen($value) < 3) {
                $this->errors[] = "{$fieldName} must be at least 3 characters long";
                $ok = false;
            }
            if (preg_match('/\s/', $value)) {
                $this->errors[] = "{$fieldName} must not contain spaces";
                $ok = false;
            }
            if (preg_match('/[^A-Za-z0-9]/', $value)) {
                $this->errors[] = "{$fieldName} can only contain letters and numbers (no special symbols)";
                $ok = false;
            }
        }
        return $ok;
    }

    /**
     * Validate email contains '@' and ends with allowed domains only
     */
    public function validateEmailAllowedDomains($value, $fieldName = 'Email')
    {
        $ok = true;
        if (strpos($value, '@') === false) {
            $this->errors[] = "{$fieldName} must contain '@'";
            $ok = false;
        }
        // Only check domain format if '@' present
        if ($ok || strpos($value, '@') !== false) {
            // Allowed providers
            $allowed = '(?:gmail\.com|icloud\.com|yahoo\.com|outlook\.com|hotmail\.com)';
            if (!preg_match('/^[^@\s]+@' . $allowed . '$/i', $value)) {
                $this->errors[] = "{$fieldName} must end with one of: gmail.com, icloud.com, yahoo.com, outlook.com, hotmail.com";
                $ok = false;
            }
        }
        return $ok;
    }

    /**
     * Validate strong password per policy: 8+ chars, upper, lower, number, special
     */
    public function validatePasswordStrong($value, $fieldName = 'Password')
    {
        $ok = true;
        if (strlen($value) < 8) {
            $this->errors[] = "{$fieldName} must be at least 8 characters long";
            $ok = false;
        }
        if (!preg_match('/[A-Z]/', $value)) {
            $this->errors[] = "{$fieldName} must contain at least one uppercase letter";
            $ok = false;
        }
        if (!preg_match('/[a-z]/', $value)) {
            $this->errors[] = "{$fieldName} must contain at least one lowercase letter";
            $ok = false;
        }
        if (!preg_match('/[0-9]/', $value)) {
            $this->errors[] = "{$fieldName} must contain at least one number";
            $ok = false;
        }
        // Require at least one non-alphanumeric (special) character
        if (!preg_match('/[^A-Za-z0-9]/', $value)) {
            $this->errors[] = "{$fieldName} must contain at least one special character";
            $ok = false;
        }
        return $ok;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasErrors()
    {
        return !empty($this->errors);
    }

    public function clearErrors()
    {
        $this->errors = [];
    }
}
