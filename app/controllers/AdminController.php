<?php

class AdminController extends Controller
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
        $this->requireRole('admin');

        // Get statistics
        $userStats = $this->userModel->getUserStats();
        $totalUsers = $this->userModel->count('users');
        $totalStudents = $this->userModel->count('users', "role = 'student'");
        $totalCounselors = $this->userModel->count('users', "role = 'counselor'");
        $totalAppointments = $this->appointmentModel->count('appointments');
        $completedAppointments = $this->appointmentModel->count('appointments', "status = 'completed'");

        // Recent users
        $recentUsers = $this->userModel->findAll('users', 'created_at DESC');
        $recentUsers = array_slice($recentUsers, 0, 10);

        // Recent appointments
        $sql = "SELECT a.*, 
                       CONCAT(s.first_name, ' ', s.last_name) as student_name,
                       CONCAT(c.first_name, ' ', c.last_name) as counselor_name
                FROM appointments a
                JOIN users s ON a.student_id = s.id
                JOIN users c ON a.counselor_id = c.id
                ORDER BY a.created_at DESC
                LIMIT 10";
        
        // For now, we'll use a simple query since we don't have a generic method for complex joins
        $recentAppointments = [];

        $data = [
            'title' => 'Admin Dashboard - ' . APP_NAME,
            'page_title' => 'Administration Dashboard',
            'total_users' => $totalUsers,
            'total_students' => $totalStudents,
            'total_counselors' => $totalCounselors,
            'total_appointments' => $totalAppointments,
            'completed_appointments' => $completedAppointments,
            'user_stats' => $userStats,
            'recent_users' => $recentUsers,
            'recent_appointments' => $recentAppointments
        ];

        $this->view('admin/dashboard', $data);
    }

    public function users()
    {
        $this->requireRole('admin');

        $users = $this->userModel->findAll('users', 'created_at DESC');

        $data = [
            'title' => 'User Management - ' . APP_NAME,
            'page_title' => 'User Management',
            'users' => $users
        ];

        $this->view('admin/users', $data);
    }

    public function userDetails($userId = null)
    {
        $this->requireRole('admin');

        if (!$userId) {
            $this->setFlash('error', 'Invalid user ID.');
            $this->redirect('admin/users');
        }

        $user = $this->userModel->findById('users', $userId);
        if (!$user) {
            $this->setFlash('error', 'User not found.');
            $this->redirect('admin/users');
        }

        $data = [
            'title' => 'User Details - ' . APP_NAME,
            'page_title' => 'User Details',
            'user' => $user
        ];

        $this->view('admin/user-details', $data);
    }


    public function appointments()
    {
        $this->requireRole('admin');

        $appointments = $this->appointmentModel->findAll('appointments', 'created_at DESC');

        $data = [
            'title' => 'Appointment Management - ' . APP_NAME,
            'page_title' => 'Appointment Management',
            'appointments' => $appointments
        ];

        $this->view('admin/appointments', $data);
    }


}
?>