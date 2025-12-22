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

            // Sanitize inputs
            $username = ValidationHelper::sanitize($_POST['username'] ?? '');
            $email = ValidationHelper::sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $firstName = ValidationHelper::sanitize($_POST['first_name'] ?? '');
            $lastName = ValidationHelper::sanitize($_POST['last_name'] ?? '');
            $role = $_POST['role'] ?? 'student';
            $phone = ValidationHelper::sanitize($_POST['phone'] ?? '');

            // Use ValidationHelper for centralized validation
            $validator = new ValidationHelper();
            
            $validator->validate('username', $username, 'Username')
                ->required()
                ->minLength(3)
                ->maxLength(50)
                ->username();
            
            $validator->validate('email', $email, 'Email')
                ->required()
                ->email()
                ->maxLength(100);
            
            $validator->validate('password', $password, 'Password')
                ->required()
                ->minLength(8)
                ->regex('/[A-Z]/', 'Password must contain at least one uppercase letter')
                ->regex('/[a-z]/', 'Password must contain at least one lowercase letter')
                ->regex('/[^A-Za-z0-9]/', 'Password must contain at least one special character');
            
            $validator->validate('confirm_password', $confirmPassword, 'Confirm Password')
                ->required()
                ->matches($password, 'Password');
            
            $validator->validate('first_name', $firstName, 'First name')
                ->required()
                ->maxLength(50);
            
            $validator->validate('last_name', $lastName, 'Last name')
                ->required()
                ->maxLength(50);
            
            $validator->validate('role', $role, 'Role')
                ->required()
                ->in(['student', 'counselor']);
            
            // Optional: Validate phone if provided
            if (!empty($phone)) {
                $validator->validate('phone', $phone, 'Phone')
                    ->phone();
            }

            // Get validation errors
            $errors = $validator->getAllErrorMessages();

            // Check if username/email already exists (only if no validation errors)
            if ($validator->isValid()) {
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