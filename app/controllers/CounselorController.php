<?php

class CounselorController extends Controller
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
        $this->requireRole('counselor');

        $counselorId = $_SESSION['user_id'];
        
        // Get today's appointments
        $todaysAppointments = $this->appointmentModel->getCounselorTodayAppointments($counselorId);
        
        // Get upcoming appointments (next 7 days)
        $upcomingAppointments = $this->appointmentModel->getCounselorUpcomingAppointments($counselorId, 7);
        
        // Get pending appointments
        $pendingAppointments = $this->appointmentModel->getCounselorPendingAppointments($counselorId);
        
        // Get statistics
        $stats = [
            'total_appointments' => $this->appointmentModel->getCounselorTotalAppointments($counselorId),
            'completed_appointments' => $this->appointmentModel->getCounselorCompletedAppointments($counselorId),
            'pending_appointments' => count($pendingAppointments),
            'todays_appointments' => count($todaysAppointments)
        ];

        $data = [
            'title' => 'Counselor Dashboard - ' . APP_NAME,
            'page_title' => 'Dashboard',
            'stats' => $stats,
            'todays_appointments' => $todaysAppointments,
            'upcoming_appointments' => $upcomingAppointments,
            'pending_appointments' => $pendingAppointments
        ];

        $this->view('counselor/dashboard', $data);
    }

    public function appointments()
    {
        $this->requireRole('counselor');

        $counselorId = $_SESSION['user_id'];
        $status = $_GET['status'] ?? 'all';
        
        $appointments = $this->appointmentModel->getCounselorAppointments($counselorId, $status);

        $data = [
            'title' => 'My Appointments - ' . APP_NAME,
            'page_title' => 'My Appointments',
            'appointments' => $appointments,
            'current_status' => $status
        ];

        $this->view('counselor/appointments', $data);
    }

    public function schedule()
    {
        $this->requireRole('counselor');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateSchedule();
            return;
        }

        $counselorId = $_SESSION['user_id'];
        $schedule = $this->appointmentModel->getCounselorSchedule($counselorId);

        $data = [
            'title' => 'My Schedule - ' . APP_NAME,
            'page_title' => 'Manage Schedule',
            'schedule' => $schedule
        ];

        $this->view('counselor/schedule', $data);
    }

    private function updateSchedule()
    {
        $counselorId = $_SESSION['user_id'];
        $scheduleData = $_POST;

        if ($this->appointmentModel->updateCounselorSchedule($counselorId, $scheduleData)) {
            $this->setFlash('success', 'Schedule updated successfully');
        } else {
            $this->setFlash('error', 'Failed to update schedule');
        }

        $this->redirect('counselor/schedule');
    }

    public function updateAppointmentStatus()
    {
        $this->requireRole('counselor');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $appointmentId = $_POST['appointment_id'] ?? '';
            $status = $_POST['status'] ?? '';
            $notes = $_POST['notes'] ?? '';

            if (empty($appointmentId) || empty($status)) {
                $this->setFlash('error', 'Missing required fields');
                $this->redirect('counselor/appointments');
            }

            $updateData = [
                'status' => $status,
                'counselor_notes' => $notes
            ];

            if ($this->appointmentModel->updateAppointment($appointmentId, $updateData)) {
                $this->setFlash('success', 'Appointment updated successfully');
            } else {
                $this->setFlash('error', 'Failed to update appointment');
            }
        }

        $this->redirect('counselor/appointments');
    }

    public function students()
    {
        $this->requireRole('counselor');

        $counselorId = $_SESSION['user_id'];
        $students = $this->userModel->getCounselorStudents($counselorId);

        $data = [
            'title' => 'My Students - ' . APP_NAME,
            'page_title' => 'My Students',
            'students' => $students
        ];

        $this->view('counselor/students', $data);
    }

    public function profile()
    {
        $this->requireRole('counselor');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateProfile();
            return;
        }

        $data = [
            'title' => 'My Profile - ' . APP_NAME,
            'page_title' => 'My Profile',
            'user' => [
                'name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'],
                'phone' => '',
                'specialization' => '',
                'bio' => ''
            ]
        ];

        $this->view('counselor/profile', $data);
    }

    private function updateProfile()
    {
        $userId = $_SESSION['user_id'];
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $specialization = trim($_POST['specialization'] ?? '');
        $bio = trim($_POST['bio'] ?? '');

        $errors = [];

        if (empty($name)) {
            $errors[] = 'Name is required';
        }

        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }

        if (empty($errors)) {
            $userData = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'specialization' => $specialization,
                'bio' => $bio
            ];

            if ($this->userModel->updateUser($userId, $userData)) {
                // Update session data
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $this->setFlash('success', 'Profile updated successfully');
            } else {
                $this->setFlash('error', 'Failed to update profile');
            }
        } else {
            $this->setFlash('error', implode('<br>', $errors));
        }

        $this->redirect('counselor/profile');
    }

    public function resources()
    {
        $this->requireRole('counselor');

        $data = [
            'title' => 'Resources - ' . APP_NAME,
            'page_title' => 'Counselor Resources'
        ];

        $this->view('counselor/resources', $data);
    }
}
?>