<?php
/**
 * ValidationHelper Class
 * Centralized validation for all input data across the application
 * 
 * Usage:
 *   $validator = new ValidationHelper();
 *   $validator->validate('email', $email)->required()->email();
 *   $validator->validate('password', $password)->required()->minLength(6);
 *   if ($validator->hasErrors()) {
 *       $errors = $validator->getErrors();
 *   }
 */
class ValidationHelper
{
    private $errors = [];
    private $currentField = '';
    private $currentValue = null;
    private $currentLabel = '';

    /**
     * Start validating a field
     * @param string $field Field name
     * @param mixed $value Field value
     * @param string|null $label Human-readable label (optional)
     * @return $this
     */
    public function validate($field, $value, $label = null)
    {
        $this->currentField = $field;
        $this->currentValue = $value;
        $this->currentLabel = $label ?? ucfirst(str_replace('_', ' ', $field));
        return $this;
    }

    /**
     * Check if field is required (not empty)
     * @param string|null $message Custom error message
     * @return $this
     */
    public function required($message = null)
    {
        $value = is_string($this->currentValue) ? trim($this->currentValue) : $this->currentValue;
        
        if (empty($value) && $value !== '0') {
            $this->addError($message ?? "{$this->currentLabel} is required.");
        }
        return $this;
    }

    /**
     * Validate email format
     * @param string|null $message Custom error message
     * @return $this
     */
    public function email($message = null)
    {
        if (!empty($this->currentValue) && !filter_var($this->currentValue, FILTER_VALIDATE_EMAIL)) {
            $this->addError($message ?? "{$this->currentLabel} must be a valid email address.");
        }
        return $this;
    }

    /**
     * Validate minimum length
     * @param int $length Minimum length
     * @param string|null $message Custom error message
     * @return $this
     */
    public function minLength($length, $message = null)
    {
        if (!empty($this->currentValue) && strlen($this->currentValue) < $length) {
            $this->addError($message ?? "{$this->currentLabel} must be at least {$length} characters.");
        }
        return $this;
    }

    /**
     * Validate maximum length
     * @param int $length Maximum length
     * @param string|null $message Custom error message
     * @return $this
     */
    public function maxLength($length, $message = null)
    {
        if (!empty($this->currentValue) && strlen($this->currentValue) > $length) {
            $this->addError($message ?? "{$this->currentLabel} must not exceed {$length} characters.");
        }
        return $this;
    }

    /**
     * Validate exact length
     * @param int $length Exact length required
     * @param string|null $message Custom error message
     * @return $this
     */
    public function exactLength($length, $message = null)
    {
        if (!empty($this->currentValue) && strlen($this->currentValue) !== $length) {
            $this->addError($message ?? "{$this->currentLabel} must be exactly {$length} characters.");
        }
        return $this;
    }

    /**
     * Validate that value matches another value (e.g., password confirmation)
     * @param mixed $matchValue Value to match against
     * @param string $matchLabel Label of the field to match
     * @param string|null $message Custom error message
     * @return $this
     */
    public function matches($matchValue, $matchLabel = 'confirmation', $message = null)
    {
        if ($this->currentValue !== $matchValue) {
            $this->addError($message ?? "{$this->currentLabel} must match {$matchLabel}.");
        }
        return $this;
    }

    /**
     * Validate numeric value
     * @param string|null $message Custom error message
     * @return $this
     */
    public function numeric($message = null)
    {
        if (!empty($this->currentValue) && !is_numeric($this->currentValue)) {
            $this->addError($message ?? "{$this->currentLabel} must be a number.");
        }
        return $this;
    }

    /**
     * Validate integer value
     * @param string|null $message Custom error message
     * @return $this
     */
    public function integer($message = null)
    {
        if (!empty($this->currentValue) && !filter_var($this->currentValue, FILTER_VALIDATE_INT)) {
            $this->addError($message ?? "{$this->currentLabel} must be a whole number.");
        }
        return $this;
    }

    /**
     * Validate minimum value
     * @param int|float $min Minimum value
     * @param string|null $message Custom error message
     * @return $this
     */
    public function min($min, $message = null)
    {
        if (!empty($this->currentValue) && is_numeric($this->currentValue) && $this->currentValue < $min) {
            $this->addError($message ?? "{$this->currentLabel} must be at least {$min}.");
        }
        return $this;
    }

    /**
     * Validate maximum value
     * @param int|float $max Maximum value
     * @param string|null $message Custom error message
     * @return $this
     */
    public function max($max, $message = null)
    {
        if (!empty($this->currentValue) && is_numeric($this->currentValue) && $this->currentValue > $max) {
            $this->addError($message ?? "{$this->currentLabel} must not exceed {$max}.");
        }
        return $this;
    }

    /**
     * Validate value is between min and max
     * @param int|float $min Minimum value
     * @param int|float $max Maximum value
     * @param string|null $message Custom error message
     * @return $this
     */
    public function between($min, $max, $message = null)
    {
        if (!empty($this->currentValue) && is_numeric($this->currentValue)) {
            if ($this->currentValue < $min || $this->currentValue > $max) {
                $this->addError($message ?? "{$this->currentLabel} must be between {$min} and {$max}.");
            }
        }
        return $this;
    }

    /**
     * Validate value is in a list of allowed values
     * @param array $allowedValues List of allowed values
     * @param string|null $message Custom error message
     * @return $this
     */
    public function in(array $allowedValues, $message = null)
    {
        if (!empty($this->currentValue) && !in_array($this->currentValue, $allowedValues)) {
            $allowed = implode(', ', $allowedValues);
            $this->addError($message ?? "{$this->currentLabel} must be one of: {$allowed}.");
        }
        return $this;
    }

    /**
     * Validate alphanumeric value (letters and numbers only)
     * @param string|null $message Custom error message
     * @return $this
     */
    public function alphanumeric($message = null)
    {
        if (!empty($this->currentValue) && !ctype_alnum($this->currentValue)) {
            $this->addError($message ?? "{$this->currentLabel} must contain only letters and numbers.");
        }
        return $this;
    }

    /**
     * Validate username format (letters, numbers, underscore)
     * @param string|null $message Custom error message
     * @return $this
     */
    public function username($message = null)
    {
        if (!empty($this->currentValue) && !preg_match('/^[a-zA-Z0-9_]+$/', $this->currentValue)) {
            $this->addError($message ?? "{$this->currentLabel} can only contain letters, numbers, and underscores.");
        }
        return $this;
    }

    /**
     * Validate phone number format
     * @param string|null $message Custom error message
     * @return $this
     */
    public function phone($message = null)
    {
        if (!empty($this->currentValue)) {
            $digitsOnly = preg_replace('/[^0-9]/', '', $this->currentValue);
            if (strlen($digitsOnly) < 10) {
                $this->addError($message ?? "{$this->currentLabel} must be a valid phone number with at least 10 digits.");
            }
        }
        return $this;
    }

    /**
     * Validate date format (YYYY-MM-DD)
     * @param string|null $message Custom error message
     * @return $this
     */
    public function date($message = null)
    {
        if (!empty($this->currentValue)) {
            $d = DateTime::createFromFormat('Y-m-d', $this->currentValue);
            if (!$d || $d->format('Y-m-d') !== $this->currentValue) {
                $this->addError($message ?? "{$this->currentLabel} must be a valid date (YYYY-MM-DD).");
            }
        }
        return $this;
    }

    /**
     * Validate date is in the future
     * @param string|null $message Custom error message
     * @return $this
     */
    public function futureDate($message = null)
    {
        if (!empty($this->currentValue)) {
            $inputDate = strtotime($this->currentValue);
            $today = strtotime('today');
            if ($inputDate < $today) {
                $this->addError($message ?? "{$this->currentLabel} must be a future date.");
            }
        }
        return $this;
    }

    /**
     * Validate date is today or in the future
     * @param string|null $message Custom error message
     * @return $this
     */
    public function futureDateOrToday($message = null)
    {
        if (!empty($this->currentValue)) {
            $inputDate = strtotime($this->currentValue);
            $today = strtotime('today');
            if ($inputDate < $today) {
                $this->addError($message ?? "{$this->currentLabel} must be today or a future date.");
            }
        }
        return $this;
    }

    /**
     * Validate time format (HH:MM or HH:MM:SS)
     * @param string|null $message Custom error message
     * @return $this
     */
    public function time($message = null)
    {
        if (!empty($this->currentValue)) {
            if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $this->currentValue)) {
                $this->addError($message ?? "{$this->currentLabel} must be a valid time format.");
            }
        }
        return $this;
    }

    /**
     * Validate URL format
     * @param string|null $message Custom error message
     * @return $this
     */
    public function url($message = null)
    {
        if (!empty($this->currentValue) && !filter_var($this->currentValue, FILTER_VALIDATE_URL)) {
            $this->addError($message ?? "{$this->currentLabel} must be a valid URL.");
        }
        return $this;
    }

    /**
     * Validate using a custom regex pattern
     * @param string $pattern Regex pattern
     * @param string|null $message Custom error message
     * @return $this
     */
    public function regex($pattern, $message = null)
    {
        if (!empty($this->currentValue) && !preg_match($pattern, $this->currentValue)) {
            $this->addError($message ?? "{$this->currentLabel} format is invalid.");
        }
        return $this;
    }

    /**
     * Validate using a custom callback function
     * @param callable $callback Function that returns true if valid
     * @param string|null $message Custom error message
     * @return $this
     */
    public function custom(callable $callback, $message = null)
    {
        if (!empty($this->currentValue) && !$callback($this->currentValue)) {
            $this->addError($message ?? "{$this->currentLabel} is invalid.");
        }
        return $this;
    }

    /**
     * Add error message for current field
     * @param string $message Error message
     */
    private function addError($message)
    {
        if (!isset($this->errors[$this->currentField])) {
            $this->errors[$this->currentField] = [];
        }
        $this->errors[$this->currentField][] = $message;
    }

    /**
     * Check if there are any validation errors
     * @return bool
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * Check if validation passed
     * @return bool
     */
    public function isValid()
    {
        return empty($this->errors);
    }

    /**
     * Get all validation errors
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get errors for a specific field
     * @param string $field Field name
     * @return array
     */
    public function getFieldErrors($field)
    {
        return $this->errors[$field] ?? [];
    }

    /**
     * Get first error for a specific field
     * @param string $field Field name
     * @return string|null
     */
    public function getFirstError($field)
    {
        return $this->errors[$field][0] ?? null;
    }

    /**
     * Get all errors as a flat array of messages
     * @return array
     */
    public function getAllErrorMessages()
    {
        $messages = [];
        foreach ($this->errors as $fieldErrors) {
            $messages = array_merge($messages, $fieldErrors);
        }
        return $messages;
    }

    /**
     * Clear all errors
     * @return $this
     */
    public function clearErrors()
    {
        $this->errors = [];
        return $this;
    }

    /**
     * Sanitize input - remove HTML tags and trim
     * @param string $value
     * @return string
     */
    public static function sanitize($value)
    {
        if (!is_string($value)) {
            return $value;
        }
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Sanitize an array of inputs
     * @param array $data
     * @return array
     */
    public static function sanitizeArray(array $data)
    {
        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = self::sanitizeArray($value);
            } else {
                $sanitized[$key] = self::sanitize($value);
            }
        }
        return $sanitized;
    }
}
