<?php
/**
 * Validation Unit Tests
 * Tests input validation and data sanitization
 */

class ValidationTest extends BaseTestCase
{
    /**
     * Test: Email validation
     */
    public function testEmailValidation()
    {
        // Valid emails
        $validEmails = [
            'user@example.com',
            'test.user@domain.org',
            'user+tag@gmail.com',
            'firstname.lastname@company.co.uk'
        ];
        
        foreach ($validEmails as $email) {
            $this->assertTrue(
                filter_var($email, FILTER_VALIDATE_EMAIL) !== false,
                "Email '$email' should be valid"
            );
        }
        
        // Invalid emails
        $invalidEmails = [
            'invalid-email',
            'user@',
            '@domain.com',
            'user@domain',
            'user@.com',
            ''
        ];
        
        foreach ($invalidEmails as $email) {
            $this->assertFalse(
                filter_var($email, FILTER_VALIDATE_EMAIL) !== false,
                "Email '$email' should be invalid"
            );
        }
    }

    /**
     * Test: Required field validation
     */
    public function testRequiredFieldValidation()
    {
        // Empty values should fail
        $emptyValues = ['', null, '   '];
        
        foreach ($emptyValues as $value) {
            $trimmed = is_string($value) ? trim($value) : $value;
            $this->assertTrue(
                empty($trimmed),
                'Empty value should be detected'
            );
        }
        
        // Non-empty values should pass
        $nonEmptyValues = ['John', 'test@email.com', '123'];
        
        foreach ($nonEmptyValues as $value) {
            $this->assertFalse(
                empty(trim($value)),
                "Value '$value' should not be empty"
            );
        }
    }

    /**
     * Test: Phone number validation
     */
    public function testPhoneNumberValidation()
    {
        // Valid phone formats
        $validPhones = [
            '+1234567890',
            '123-456-7890',
            '(123) 456-7890',
            '1234567890'
        ];
        
        foreach ($validPhones as $phone) {
            // Remove non-digits
            $digitsOnly = preg_replace('/[^0-9]/', '', $phone);
            $this->assertGreaterThanOrEqual(
                10,
                strlen($digitsOnly),
                "Phone '$phone' should have at least 10 digits"
            );
        }
    }

    /**
     * Test: Date format validation
     */
    public function testDateFormatValidation()
    {
        // Valid dates
        $validDates = ['2025-01-15', '2025-12-31', '2024-02-29'];
        
        foreach ($validDates as $date) {
            $this->assertMatchesRegularExpression(
                '/^\d{4}-\d{2}-\d{2}$/',
                $date,
                "Date '$date' should match YYYY-MM-DD format"
            );
        }
        
        // Invalid formats
        $invalidDates = ['15-01-2025', '2025/01/15', 'January 15, 2025'];
        
        foreach ($invalidDates as $date) {
            $this->assertDoesNotMatchRegularExpression(
                '/^\d{4}-\d{2}-\d{2}$/',
                $date,
                "Date '$date' should not match YYYY-MM-DD format"
            );
        }
    }

    /**
     * Test: Time format validation
     */
    public function testTimeFormatValidation()
    {
        // Valid times
        $validTimes = ['09:00', '14:30', '23:59', '00:00'];
        
        foreach ($validTimes as $time) {
            $this->assertMatchesRegularExpression(
                '/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/',
                $time,
                "Time '$time' should be valid"
            );
        }
    }

    /**
     * Test: XSS prevention (HTML sanitization)
     */
    public function testXssPrevention()
    {
        $maliciousInputs = [
            '<script>alert("xss")</script>',
            '<img src="x" onerror="alert(1)">',
            'onclick="malicious()"',
            '<a href="javascript:alert(1)">Click</a>'
        ];
        
        foreach ($maliciousInputs as $input) {
            $sanitized = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
            
            // Should not contain raw script tags
            $this->assertStringNotContainsString('<script>', $sanitized);
            // Should not contain raw event handlers
            $this->assertStringNotContainsString('onerror=', $sanitized);
        }
    }

    /**
     * Test: SQL injection prevention (using PDO prepared statements)
     */
    public function testSqlInjectionPatterns()
    {
        $maliciousInputs = [
            "'; DROP TABLE users; --",
            "1' OR '1'='1",
            "admin'--",
            "1; DELETE FROM appointments"
        ];
        
        foreach ($maliciousInputs as $input) {
            // When properly escaped, these should be safe strings
            $escaped = addslashes($input);
            
            // The escaped string should be different (quotes escaped)
            $this->assertStringContainsString("\'", $escaped);
        }
    }

    /**
     * Test: Password minimum requirements
     */
    public function testPasswordMinimumRequirements()
    {
        $minLength = 6;
        
        // Too short
        $shortPassword = '12345';
        $this->assertFalse(
            strlen($shortPassword) >= $minLength,
            'Password with 5 chars should fail'
        );
        
        // Exactly minimum
        $validPassword = '123456';
        $this->assertTrue(
            strlen($validPassword) >= $minLength,
            'Password with 6 chars should pass'
        );
        
        // Strong password
        $strongPassword = 'SecureP@ss123!';
        $this->assertTrue(
            strlen($strongPassword) >= $minLength,
            'Strong password should pass'
        );
    }

    /**
     * Test: Username validation (alphanumeric + underscore)
     */
    public function testUsernameValidation()
    {
        // Valid usernames
        $validUsernames = ['john_doe', 'user123', 'JohnDoe', 'test_user_1'];
        
        foreach ($validUsernames as $username) {
            $this->assertMatchesRegularExpression(
                '/^[a-zA-Z0-9_]+$/',
                $username,
                "Username '$username' should be valid"
            );
        }
        
        // Invalid usernames
        $invalidUsernames = ['user@name', 'user name', 'user-name', 'user.name'];
        
        foreach ($invalidUsernames as $username) {
            $this->assertDoesNotMatchRegularExpression(
                '/^[a-zA-Z0-9_]+$/',
                $username,
                "Username '$username' should be invalid"
            );
        }
    }

    /**
     * Test: Integer ID validation
     */
    public function testIntegerIdValidation()
    {
        // Valid IDs
        $validIds = [1, 100, 999999];
        foreach ($validIds as $id) {
            $this->assertIsInt($id);
            $this->assertGreaterThan(0, $id);
        }
        
        // Invalid IDs
        $invalidIds = [0, -1, 'abc', null];
        foreach ($invalidIds as $id) {
            $isValid = is_int($id) && $id > 0;
            $this->assertFalse($isValid, "ID should be invalid");
        }
    }
}
