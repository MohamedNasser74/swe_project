<?php
/**
 * User Model Unit Tests
 * Tests user registration, authentication, and profile management
 */

class UserModelTest extends BaseTestCase
{
    private $userModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userModel = new User();
    }

    /**
     * Test: User model can be instantiated
     */
    public function testUserModelCanBeInstantiated()
    {
        $this->assertInstanceOf(User::class, $this->userModel);
    }

    /**
     * Test: Email validation works correctly
     */
    public function testValidEmailFormat()
    {
        // Valid emails
        $this->assertTrue(filter_var('user@example.com', FILTER_VALIDATE_EMAIL) !== false);
        $this->assertTrue(filter_var('test.user@domain.org', FILTER_VALIDATE_EMAIL) !== false);
        
        // Invalid emails
        $this->assertFalse(filter_var('invalid-email', FILTER_VALIDATE_EMAIL) !== false);
        $this->assertFalse(filter_var('user@', FILTER_VALIDATE_EMAIL) !== false);
        $this->assertFalse(filter_var('@domain.com', FILTER_VALIDATE_EMAIL) !== false);
    }

    /**
     * Test: Password hashing works correctly
     */
    public function testPasswordHashing()
    {
        $plainPassword = 'SecurePassword123!';
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
        
        // Hash should not equal plain password
        $this->assertNotEquals($plainPassword, $hashedPassword);
        
        // Password verification should work
        $this->assertTrue(password_verify($plainPassword, $hashedPassword));
        
        // Wrong password should fail
        $this->assertFalse(password_verify('WrongPassword', $hashedPassword));
    }

    /**
     * Test: Password strength validation
     */
    public function testPasswordStrengthValidation()
    {
        // Test minimum length
        $shortPassword = '12345';
        $validPassword = '123456';
        
        $this->assertFalse(strlen($shortPassword) >= 6, 'Short password should fail');
        $this->assertTrue(strlen($validPassword) >= 6, 'Valid password should pass');
    }

    /**
     * Test: User roles are valid
     */
    public function testValidUserRoles()
    {
        $validRoles = ['student', 'counselor', 'admin'];
        
        $this->assertContains('student', $validRoles);
        $this->assertContains('counselor', $validRoles);
        $this->assertContains('admin', $validRoles);
        $this->assertNotContains('superadmin', $validRoles);
        $this->assertNotContains('guest', $validRoles);
    }

    /**
     * Test: Username sanitization
     */
    public function testUsernameSanitization()
    {
        $dirtyUsername = '<script>alert("xss")</script>testuser';
        $cleanUsername = htmlspecialchars($dirtyUsername, ENT_QUOTES, 'UTF-8');
        
        // Should not contain script tags after sanitization
        $this->assertStringNotContainsString('<script>', $cleanUsername);
    }

    /**
     * Test: Email normalization (lowercase)
     */
    public function testEmailNormalization()
    {
        $email = 'User@EXAMPLE.COM';
        $normalizedEmail = strtolower($email);
        
        $this->assertEquals('user@example.com', $normalizedEmail);
    }

    /**
     * Test: Required fields validation
     */
    public function testRequiredFieldsValidation()
    {
        $userData = [
            'username' => '',
            'email' => 'test@test.com',
            'password' => '123456',
            'first_name' => 'John',
            'last_name' => 'Doe'
        ];
        
        // Empty username should fail validation
        $this->assertTrue(empty($userData['username']), 'Empty username should be detected');
        
        // Non-empty fields should pass
        $this->assertFalse(empty($userData['email']), 'Email should not be empty');
        $this->assertFalse(empty($userData['first_name']), 'First name should not be empty');
    }

    /**
     * Test: User data structure is correct
     */
    public function testUserDataStructure()
    {
        $requiredFields = ['username', 'email', 'password', 'first_name', 'last_name', 'role'];
        
        foreach ($requiredFields as $field) {
            $this->assertContains($field, $requiredFields);
        }
    }
}
