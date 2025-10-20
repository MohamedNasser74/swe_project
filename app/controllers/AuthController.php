<?php

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    // Route default: show login when /auth is requested
    public function index()
    {
        // Delegate to login action
        $this->login();
    }

    public function login()
    {
        // Redirect if already logged in
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }

        $data = [
            'title' => 'Login - ' . APP_NAME,
            'page_title' => 'Sign In to Your Account'
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verify CSRF token
            if (!FormHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
                $this->setFlash('error', 'Invalid request. Please try again.');
                $this->redirect('auth/login');
                return;
            }

            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Validate inputs
            $errors = [];
            if (empty($email)) $errors[] = 'Email is required';
            if (empty($password)) $errors[] = 'Password is required';

            if (empty($errors)) {
                $user = $this->userModel->verifyLogin($email, $password);
                
                if ($user) {
                    // Set session data
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['user_role'] = $user->role;
                    $_SESSION['user_name'] = $user->first_name . ' ' . $user->last_name;
                    $_SESSION['user_email'] = $user->email;

                    $this->setFlash('success', 'Welcome back, ' . $user->first_name . '!');
                    
                    // Redirect based on role
                    switch ($user->role) {
                        case 'admin':
                            $this->redirect('admin/dashboard');
                            break;
                        case 'counselor':
                            $this->redirect('counselor/dashboard');
                            break;
                        default:
                            $this->redirect('student/dashboard');
                    }
                } else {
                    $errors[] = 'Invalid email or password';
                }
            }

            $data['errors'] = $errors;
            $data['form_data'] = $_POST;
        }

        $this->view('auth/login', $data);
    }

    public function register()
    {
        // Redirect if already logged in
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }

        $data = [
            'title' => 'Register - ' . APP_NAME,
            'page_title' => 'Create Your Account'
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verify CSRF token
            if (!FormHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
                $this->setFlash('error', 'Invalid request. Please try again.');
                $this->redirect('auth/register');
                return;
            }

            // Get raw inputs (don't trim username yet - validate first to catch spaces)
            $username = $_POST['username'] ?? '';
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $role = $_POST['role'] ?? 'student';
            $phone = trim($_POST['phone'] ?? '');

            // Validate inputs using helper (strict regex policies)
            $errors = [];
            $validator = new ValidationHelper();
            if ($validator->required($username, 'Username')) {
                $validator->validateUsernameStrict($username, 'Username');
            }
            // After validation, trim username for storage (only if valid)
            $username = trim($username);
            if ($validator->required($email, 'Email')) {
                $validator->validateEmailAllowedDomains($email, 'Email');
            }
            if ($validator->required($password, 'Password')) {
                $validator->validatePasswordStrong($password, 'Password');
            }
            $validator->match($password, $confirmPassword, 'Passwords');
            $validator->required($firstName, 'First name');
            $validator->required($lastName, 'Last name');
            if (!in_array($role, ['student', 'counselor'])) $errors[] = 'Invalid role selected';
            $errors = array_merge($errors, $validator->getErrors());

            // Check if username/email already exists
            if (empty($errors)) {
                if ($this->userModel->findByUsername($username)) {
                    $errors[] = 'Username already exists';
                }
                if ($this->userModel->findByEmail($email)) {
                    $errors[] = 'Email already exists';
                }
            }

            if (empty($errors)) {
                $userData = [
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'role' => $role,
                    'phone' => $phone
                ];

                if ($this->userModel->createUser($userData)) {
                    $this->setFlash('success', 'Registration successful! You can now log in.');
                    $this->redirect('auth/login');
                } else {
                    $errors[] = 'Registration failed. Please try again.';
                }
            }

            $data['errors'] = $errors;
            $data['form_data'] = $_POST;
        }

        $this->view('auth/register', $data);
    }

    public function logout()
    {
        // Destroy session
        session_destroy();
        
        $this->setFlash('info', 'You have been logged out successfully.');
        $this->redirect('home');
    }

}
?>