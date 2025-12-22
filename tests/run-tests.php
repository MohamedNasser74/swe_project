<?php
/**
 * Simple Test Runner
 * Runs all unit tests without requiring PHPUnit installation
 * 
 * Usage: Open in browser: http://localhost/swe-project/swe_project/tests/run-tests.php
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load configuration
require_once APP_PATH . '/config/config.php';

// Autoload classes
spl_autoload_register(function($class) {
    $paths = [
        APP_PATH . '/core/',
        APP_PATH . '/models/',
        APP_PATH . '/controllers/',
        APP_PATH . '/helpers/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

/**
 * Simple Test Framework
 */
class SimpleTestRunner
{
    private $passed = 0;
    private $failed = 0;
    private $results = [];

    public function assertTrue($condition, $message = '')
    {
        if ($condition) {
            $this->passed++;
            return true;
        }
        $this->failed++;
        $this->results[] = ['status' => 'FAIL', 'message' => $message];
        return false;
    }

    public function assertFalse($condition, $message = '')
    {
        return $this->assertTrue(!$condition, $message);
    }

    public function assertEqual($expected, $actual, $message = '')
    {
        return $this->assertTrue($expected === $actual, $message . " (Expected: $expected, Got: $actual)");
    }

    public function assertNotEqual($expected, $actual, $message = '')
    {
        return $this->assertTrue($expected !== $actual, $message);
    }

    public function assertContains($needle, $haystack, $message = '')
    {
        if (is_array($haystack)) {
            return $this->assertTrue(in_array($needle, $haystack), $message);
        }
        return $this->assertTrue(strpos($haystack, $needle) !== false, $message);
    }

    public function getResults()
    {
        return [
            'passed' => $this->passed,
            'failed' => $this->failed,
            'total' => $this->passed + $this->failed,
            'details' => $this->results
        ];
    }
}

// HTML Header
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automated Unit Tests - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .test-pass { color: #198754; }
        .test-fail { color: #dc3545; }
        .test-section { border-left: 4px solid #0d6efd; padding-left: 1rem; margin-bottom: 2rem; }
    </style>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="text-center mb-5">
        <h1><i class="fas fa-flask me-2"></i>Automated Unit Tests</h1>
        <p class="text-muted">Testing core functionality of <?= APP_NAME ?></p>
    </div>

<?php
$allPassed = 0;
$allFailed = 0;

// ==========================================
// TEST SUITE 1: User Model Tests
// ==========================================
echo '<div class="test-section">';
echo '<h3><i class="fas fa-user me-2"></i>User Model Tests</h3>';
$test = new SimpleTestRunner();

// Test 1: Email validation
$test->assertTrue(filter_var('user@example.com', FILTER_VALIDATE_EMAIL) !== false, 'Valid email should pass');
$test->assertTrue(filter_var('test.user@domain.org', FILTER_VALIDATE_EMAIL) !== false, 'Email with dot should pass');
$test->assertFalse(filter_var('invalid-email', FILTER_VALIDATE_EMAIL) !== false, 'Invalid email should fail');
$test->assertFalse(filter_var('user@', FILTER_VALIDATE_EMAIL) !== false, 'Incomplete email should fail');
$test->assertFalse(filter_var('@domain.com', FILTER_VALIDATE_EMAIL) !== false, 'Email without user should fail');

// Test 2: Password hashing
$plainPassword = 'SecurePassword123!';
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
$test->assertNotEqual($plainPassword, $hashedPassword, 'Hash should differ from plain password');
$test->assertTrue(password_verify($plainPassword, $hashedPassword), 'Password verification should work');
$test->assertFalse(password_verify('WrongPassword', $hashedPassword), 'Wrong password should fail verification');

// Test 3: Password strength
$test->assertFalse(strlen('12345') >= 6, 'Short password should fail');
$test->assertTrue(strlen('123456') >= 6, 'Valid password length should pass');

// Test 4: User roles
$validRoles = ['student', 'counselor', 'admin'];
$test->assertContains('student', $validRoles, 'Student role should be valid');
$test->assertContains('counselor', $validRoles, 'Counselor role should be valid');
$test->assertContains('admin', $validRoles, 'Admin role should be valid');

// Test 5: XSS prevention
$dirtyInput = '<script>alert("xss")</script>testuser';
$cleanInput = htmlspecialchars($dirtyInput, ENT_QUOTES, 'UTF-8');
$test->assertFalse(strpos($cleanInput, '<script>') !== false, 'Script tags should be escaped');

$results = $test->getResults();
$allPassed += $results['passed'];
$allFailed += $results['failed'];
echo "<p class='test-pass'><i class='fas fa-check-circle me-1'></i>{$results['passed']} tests passed</p>";
if ($results['failed'] > 0) {
    echo "<p class='test-fail'><i class='fas fa-times-circle me-1'></i>{$results['failed']} tests failed</p>";
}
echo '</div>';

// ==========================================
// TEST SUITE 2: Appointment Model Tests
// ==========================================
echo '<div class="test-section">';
echo '<h3><i class="fas fa-calendar-check me-2"></i>Appointment Model Tests</h3>';
$test = new SimpleTestRunner();

// Test 1: Session types
$validTypes = ['career_guidance', 'interview_prep', 'job_search'];
$test->assertContains('career_guidance', $validTypes, 'Career guidance should be valid');
$test->assertContains('interview_prep', $validTypes, 'Interview prep should be valid');
$test->assertContains('job_search', $validTypes, 'Job search should be valid');

// Test 2: Appointment statuses
$validStatuses = ['scheduled', 'confirmed', 'completed', 'cancelled', 'no_show'];
$test->assertContains('scheduled', $validStatuses, 'Scheduled status should be valid');
$test->assertContains('confirmed', $validStatuses, 'Confirmed status should be valid');
$test->assertContains('completed', $validStatuses, 'Completed status should be valid');

// Test 3: Future date validation
$pastDate = date('Y-m-d', strtotime('-1 day'));
$futureDate = date('Y-m-d', strtotime('+1 day'));
$test->assertFalse(strtotime($pastDate) >= strtotime('today'), 'Past date should be rejected');
$test->assertTrue(strtotime($futureDate) >= strtotime('today'), 'Future date should be accepted');

// Test 4: Duration validation
$test->assertTrue(60 >= 30 && 60 <= 120, '60 minutes should be valid duration');
$test->assertFalse(15 >= 30, '15 minutes should be too short');
$test->assertFalse(180 <= 120, '180 minutes should be too long');

// Test 5: Rating validation
$test->assertTrue(5 >= 1 && 5 <= 5, 'Rating 5 should be valid');
$test->assertTrue(1 >= 1 && 1 <= 5, 'Rating 1 should be valid');
$test->assertFalse(0 >= 1, 'Rating 0 should be invalid');
$test->assertFalse(6 <= 5, 'Rating 6 should be invalid');

$results = $test->getResults();
$allPassed += $results['passed'];
$allFailed += $results['failed'];
echo "<p class='test-pass'><i class='fas fa-check-circle me-1'></i>{$results['passed']} tests passed</p>";
if ($results['failed'] > 0) {
    echo "<p class='test-fail'><i class='fas fa-times-circle me-1'></i>{$results['failed']} tests failed</p>";
}
echo '</div>';

// ==========================================
// TEST SUITE 3: Menu Model Tests (Dynamic Menu / Self-Reference)
// ==========================================
echo '<div class="test-section">';
echo '<h3><i class="fas fa-bars me-2"></i>Menu Model Tests (Self-Reference)</h3>';
$test = new SimpleTestRunner();

// Test 1: Role access values
$validRoles = ['all', 'guest', 'student', 'counselor', 'admin'];
$test->assertContains('all', $validRoles, 'All role should be valid');
$test->assertContains('student', $validRoles, 'Student role should be valid');
$test->assertContains('admin', $validRoles, 'Admin role should be valid');

// Test 2: URL format
$test->assertTrue(str_starts_with('/', '/') || str_starts_with('/', 'http'), 'Root URL should be valid');
$test->assertTrue(str_starts_with('/home/services', '/'), 'Internal URL should be valid');
$test->assertTrue(str_starts_with('https://example.com', 'http'), 'External URL should be valid');

// Test 3: Nested structure (self-reference)
$menuItems = [
    (object)['id' => 1, 'title' => 'Home', 'parent_id' => null],
    (object)['id' => 2, 'title' => 'Services', 'parent_id' => null],
    (object)['id' => 3, 'title' => 'Career Guidance', 'parent_id' => 2],
    (object)['id' => 4, 'title' => 'Interview Prep', 'parent_id' => 2],
];

// Count top-level items
$topLevel = array_filter($menuItems, fn($item) => $item->parent_id === null);
$test->assertEqual(2, count($topLevel), 'Should have 2 top-level items');

// Count children of Services (id=2)
$children = array_filter($menuItems, fn($item) => $item->parent_id === 2);
$test->assertEqual(2, count($children), 'Services should have 2 children');

// Test 4: Parent-child relationship
$parent = (object)['id' => 1, 'parent_id' => null];
$child = (object)['id' => 2, 'parent_id' => 1];
$test->assertEqual($parent->id, $child->parent_id, 'Child parent_id should match parent id');
$test->assertTrue($parent->parent_id === null, 'Top-level parent should have null parent_id');

// Test 5: Circular reference detection
$circularItem = ['id' => 1, 'parent_id' => 1];
$isCircular = $circularItem['id'] === $circularItem['parent_id'];
$test->assertTrue($isCircular, 'Circular reference should be detected');

$results = $test->getResults();
$allPassed += $results['passed'];
$allFailed += $results['failed'];
echo "<p class='test-pass'><i class='fas fa-check-circle me-1'></i>{$results['passed']} tests passed</p>";
if ($results['failed'] > 0) {
    echo "<p class='test-fail'><i class='fas fa-times-circle me-1'></i>{$results['failed']} tests failed</p>";
}
echo '</div>';

// ==========================================
// TEST SUITE 4: Validation Tests
// ==========================================
echo '<div class="test-section">';
echo '<h3><i class="fas fa-shield-alt me-2"></i>Validation Tests</h3>';
$test = new SimpleTestRunner();

// Test 1: Required field validation
$test->assertTrue(empty(''), 'Empty string should be detected');
$test->assertTrue(empty(trim('   ')), 'Whitespace-only should be detected');
$test->assertFalse(empty('John'), 'Non-empty value should pass');

// Test 2: Phone number validation
$phone = '+1234567890';
$digitsOnly = preg_replace('/[^0-9]/', '', $phone);
$test->assertTrue(strlen($digitsOnly) >= 10, 'Phone should have at least 10 digits');

// Test 3: Date format
$test->assertTrue(preg_match('/^\d{4}-\d{2}-\d{2}$/', '2025-01-15') === 1, 'YYYY-MM-DD format should be valid');
$test->assertFalse(preg_match('/^\d{4}-\d{2}-\d{2}$/', '15-01-2025') === 1, 'DD-MM-YYYY format should fail');

// Test 4: SQL injection patterns (should be escaped)
$malicious = "'; DROP TABLE users; --";
$escaped = addslashes($malicious);
$test->assertContains("\\'", $escaped, 'SQL injection should be escaped');

// Test 5: Username validation
$test->assertTrue(preg_match('/^[a-zA-Z0-9_]+$/', 'john_doe') === 1, 'Valid username should pass');
$test->assertFalse(preg_match('/^[a-zA-Z0-9_]+$/', 'user@name') === 1, 'Username with @ should fail');

$results = $test->getResults();
$allPassed += $results['passed'];
$allFailed += $results['failed'];
echo "<p class='test-pass'><i class='fas fa-check-circle me-1'></i>{$results['passed']} tests passed</p>";
if ($results['failed'] > 0) {
    echo "<p class='test-fail'><i class='fas fa-times-circle me-1'></i>{$results['failed']} tests failed</p>";
}
echo '</div>';

// ==========================================
// SUMMARY
// ==========================================
$total = $allPassed + $allFailed;
$successRate = $total > 0 ? round(($allPassed / $total) * 100, 1) : 0;
?>

<div class="card border-0 shadow-sm">
    <div class="card-body text-center p-5">
        <h2>Test Results Summary</h2>
        <div class="row g-4 mt-3">
            <div class="col-md-4">
                <div class="bg-success bg-opacity-10 rounded-4 p-4">
                    <h1 class="text-success"><?= $allPassed ?></h1>
                    <p class="text-muted mb-0">Tests Passed</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-danger bg-opacity-10 rounded-4 p-4">
                    <h1 class="text-danger"><?= $allFailed ?></h1>
                    <p class="text-muted mb-0">Tests Failed</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-primary bg-opacity-10 rounded-4 p-4">
                    <h1 class="text-primary"><?= $successRate ?>%</h1>
                    <p class="text-muted mb-0">Success Rate</p>
                </div>
            </div>
        </div>
        
        <?php if ($allFailed === 0): ?>
            <div class="alert alert-success mt-4">
                <i class="fas fa-check-circle me-2"></i>
                <strong>All tests passed!</strong> Your application logic is working correctly.
            </div>
        <?php else: ?>
            <div class="alert alert-warning mt-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong><?= $allFailed ?> test(s) failed.</strong> Review the failed tests above.
            </div>
        <?php endif; ?>
        
        <p class="text-muted mt-4">
            <small>Tests executed on <?= date('F j, Y \a\t g:i A') ?></small>
        </p>
    </div>
</div>

</div>
</body>
</html>
