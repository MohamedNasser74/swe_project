<?php

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Virtual Career Counseling Platform',
            'page_title' => 'Welcome to Your Career Journey'
        ];

        $this->view('home/index', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'About Us - ' . APP_NAME,
            'page_title' => 'About Our Platform'
        ];

        $this->view('home/about', $data);
    }

    public function services()
    {
        $data = [
            'title' => 'Our Services - ' . APP_NAME,
            'page_title' => 'Career Counseling Services'
        ];

        $this->view('home/services', $data);
    }

    public function contact()
    {
        $data = [
            'title' => 'Contact Us - ' . APP_NAME,
            'page_title' => 'Get in Touch'
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle contact form submission
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $subject = trim($_POST['subject'] ?? '');
            $message = trim($_POST['message'] ?? '');

            // Validate inputs
            $errors = [];
            if (empty($name)) $errors[] = 'Name is required';
            if (empty($email)) $errors[] = 'Email is required';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
            if (empty($subject)) $errors[] = 'Subject is required';
            if (empty($message)) $errors[] = 'Message is required';

            if (empty($errors)) {
                // In a real application, you would send an email or save to database
                $this->setFlash('success', 'Thank you for your message. We will get back to you soon!');
                $this->redirect('home/contact');
            } else {
                $data['errors'] = $errors;
                $data['form_data'] = $_POST;
            }
        }

        $this->view('home/contact', $data);
    }

    public function unauthorized()
    {
        $data = [
            'title' => 'Unauthorized - ' . APP_NAME,
            'page_title' => 'Access Denied'
        ];

        $this->view('home/unauthorized', $data);
    }

    public function notFound()
    {
        $data = [
            'title' => '404 Not Found - ' . APP_NAME,
            'page_title' => 'Page Not Found'
        ];

        $this->view('home/404', $data);
    }
}
?>