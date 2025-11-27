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

    public function learnMore()
    {
        $data = [
            'title' => 'Learn More - ' . APP_NAME,
            'page_title' => 'Why Students Trust ' . APP_NAME,
            'pillars' => [
                [
                    'icon' => 'fa-users',
                    'title' => 'Human + AI Guidance',
                    'body' => 'Blended support that combines certified counselors with data-driven career insights so every plan is realistic and actionable.'
                ],
                [
                    'icon' => 'fa-diagram-project',
                    'title' => 'Structured Career Tracks',
                    'body' => 'Role-based roadmaps, curated content, and checkpoints that keep students focused from self-discovery to job offer.'
                ],
                [
                    'icon' => 'fa-lock',
                    'title' => 'Privacy-First Platform',
                    'body' => 'Bank-level encryption, role-based access, and audit trails ensure that sensitive coaching notes stay protected.'
                ]
            ],
            'journey_steps' => [
                [
                    'label' => 'Step 1',
                    'title' => 'Discover & Assess',
                    'text' => 'Students complete strengths, interests, and skills assessments. Counselors get an instant dashboard showing gaps to address.'
                ],
                [
                    'label' => 'Step 2',
                    'title' => 'Match & Plan',
                    'text' => 'Smart matching pairs each student with a counselor who specializes in their goals. Together they co-create a measurable action plan.'
                ],
                [
                    'label' => 'Step 3',
                    'title' => 'Practice & Iterate',
                    'text' => 'Mock interviews, resume reviews, and scheduled checkpoints keep momentum high. Students receive actionable feedback after every session.'
                ],
                [
                    'label' => 'Step 4',
                    'title' => 'Launch & Grow',
                    'text' => 'Job search tools, employer introductions, and alumni mentors help students convert preparation into offers and long-term success.'
                ]
            ],
            'faqs' => [
                [
                    'question' => 'Who can join the platform?',
                    'answer' => 'Our core users are students, recent graduates, and early-career professionals. Institutions can onboard entire cohorts, and counselors are vetted before gaining access.'
                ],
                [
                    'question' => 'How are counselors selected?',
                    'answer' => 'We verify certifications, conduct mock coaching sessions, and review satisfaction scores continuously. Only counselors who maintain a 4.5/5 average rating stay active.'
                ],
                [
                    'question' => 'Do you integrate with school systems?',
                    'answer' => 'Yes. We offer secure data exports, single sign-on, and optional LMS widgets so student progress can sync back to SIS or LMS tools.'
                ],
                [
                    'question' => 'What support exists for employers?',
                    'answer' => 'Employers can share project briefs, host virtual events, and access talent spotlights (with student opt-in) to accelerate recruiting.'
                ]
            ],
            'impact_metrics' => [
                ['value' => '92%', 'label' => 'Students land roles within 4 months'],
                ['value' => '4.8/5', 'label' => 'Average counselor rating'],
                ['value' => '38+', 'label' => 'Industries represented'],
                ['value' => '250+', 'label' => 'University & employer partners']
            ]
        ];

        $this->view('home/learn-more', $data);
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