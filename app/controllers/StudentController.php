<?php

class StudentController extends Controller
{
    private $userModel;
    private $appointmentModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
        $this->appointmentModel = $this->model('Appointment');
    }

    public function dashboard()
    {
        $this->requireRole('student');

        $userId = $_SESSION['user_id'];
        
        // Get upcoming appointments
        $upcomingAppointments = $this->appointmentModel->getUpcomingAppointments($userId, 'student');
        
        // Get recent appointments
        $recentAppointments = $this->appointmentModel->getStudentAppointments($userId);
        $recentAppointments = array_slice($recentAppointments, 0, 5);

        $data = [
            'title' => 'Student Dashboard - ' . APP_NAME,
            'page_title' => 'Your Dashboard',
            'upcoming_appointments' => $upcomingAppointments,
            'recent_appointments' => $recentAppointments,
            'user_name' => $_SESSION['user_name']
        ];

        $this->view('student/dashboard', $data);
    }

    public function appointments()
    {
        $this->requireRole('student');

        $userId = $_SESSION['user_id'];
        $appointments = $this->appointmentModel->getStudentAppointments($userId);

        $data = [
            'title' => 'My Appointments - ' . APP_NAME,
            'page_title' => 'My Appointments',
            'appointments' => $appointments
        ];

        $this->view('student/appointments', $data);
    }

    public function bookAppointment()
    {
        $this->requireRole('student');

        // Get available counselors
        $counselors = $this->userModel->getUsersByRole('counselor');

        $data = [
            'title' => 'Book Appointment - ' . APP_NAME,
            'page_title' => 'Schedule a Session',
            'counselors' => $counselors
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $counselorId = $_POST['counselor_id'] ?? '';
            $appointmentDate = $_POST['appointment_date'] ?? '';
            $appointmentTime = $_POST['appointment_time'] ?? '';
            $sessionType = $_POST['session_type'] ?? '';
            $notes = trim($_POST['notes'] ?? '');

            $errors = [];
            if (empty($counselorId)) $errors[] = 'Please select a counselor';
            if (empty($appointmentDate)) $errors[] = 'Please select a date';
            if (empty($appointmentTime)) $errors[] = 'Please select a time';
            if (empty($sessionType)) $errors[] = 'Please select session type';

            // Check if date is in the future
            if (!empty($appointmentDate) && strtotime($appointmentDate) < strtotime('today')) {
                $errors[] = 'Please select a future date';
            }

            // Check availability
            if (empty($errors)) {
                if (!$this->appointmentModel->checkAvailability($counselorId, $appointmentDate, $appointmentTime)) {
                    $errors[] = 'Selected time slot is not available';
                }
            }

            if (empty($errors)) {
                $appointmentData = [
                    'student_id' => $_SESSION['user_id'],
                    'counselor_id' => $counselorId,
                    'appointment_date' => $appointmentDate,
                    'appointment_time' => $appointmentTime,
                    'session_type' => $sessionType,
                    'notes' => $notes,
                    'status' => 'scheduled'
                ];

                if ($this->appointmentModel->bookAppointment($appointmentData)) {
                    $this->setFlash('success', 'Appointment booked successfully!');
                    $this->redirect('student/appointments');
                } else {
                    $errors[] = 'Failed to book appointment. Please try again.';
                }
            }

            $data['errors'] = $errors;
            $data['form_data'] = $_POST;
        }

        $this->view('student/book-appointment', $data);
    }

    public function cancelAppointment($appointmentId = null)
    {
        $this->requireRole('student');

        if (!$appointmentId) {
            $this->setFlash('error', 'Invalid appointment.');
            $this->redirect('student/appointments');
        }

        if ($this->appointmentModel->cancelAppointment($appointmentId, $_SESSION['user_id'], 'student')) {
            $this->setFlash('success', 'Appointment cancelled successfully.');
        } else {
            $this->setFlash('error', 'Failed to cancel appointment.');
        }

        $this->redirect('student/appointments');
    }

    public function profile()
    {
        $this->requireRole('student');

        $user = $this->userModel->findById('users', $_SESSION['user_id']);

        $data = [
            'title' => 'My Profile - ' . APP_NAME,
            'page_title' => 'My Profile',
            'user' => $user
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');

            $errors = [];
            if (empty($firstName)) $errors[] = 'First name is required';
            if (empty($lastName)) $errors[] = 'Last name is required';

            if (empty($errors)) {
                $updateData = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone' => $phone
                ];

                if ($this->userModel->updateProfile($_SESSION['user_id'], $updateData)) {
                    $_SESSION['user_name'] = $firstName . ' ' . $lastName;
                    $this->setFlash('success', 'Profile updated successfully!');
                    $this->redirect('student/profile');
                } else {
                    $errors[] = 'Failed to update profile. Please try again.';
                }
            }

            $data['errors'] = $errors;
            $data['form_data'] = $_POST;
        }

        $this->view('student/profile', $data);
    }


    public function jobSearch()
    {
        $this->requireRole('student');

        $data = [
            'title' => 'Job Search - ' . APP_NAME,
            'page_title' => 'Find Your Dream Job'
        ];

        $this->view('student/jobs', $data);
    }
    
    public function jobs()
    {
        $this->requireRole('student');

        $data = [
            'title' => 'Job Search - ' . APP_NAME,
            'page_title' => 'Find Your Dream Job'
        ];

        $this->view('student/jobs', $data);
    }
}
?>