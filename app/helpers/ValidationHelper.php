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
