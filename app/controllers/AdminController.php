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

        $role = $_GET['role'] ?? '';
        $status = $_GET['status'] ?? '';

        $users = $this->userModel->getAllUsers($role, $status);
        $totalUsers = $this->userModel->count('users', 
            trim(
                implode(' AND ', array_filter([
                    $role !== '' ? "role = '" . addslashes($role) . "'" : '',
                    $status !== '' ? "status = '" . addslashes($status) . "'" : '',
                ]))
        ));

        $data = [
            'title' => 'User Management - ' . APP_NAME,
            'page_title' => 'User Management',
            'users' => $users,
            'total_users' => $totalUsers,
            'filter_role' => $role,
            'filter_status' => $status
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

        // If this is a counselor, gather basic appointment stats for admin overview
        $counselorStats = null;
        if ($user->role === 'counselor') {
            $counselorStats = [
                'total_appointments' => $this->appointmentModel->getCounselorTotalAppointments($userId),
                'completed_appointments' => $this->appointmentModel->getCounselorCompletedAppointments($userId),
                'pending_appointments' => count($this->appointmentModel->getCounselorPendingAppointments($userId)),
                'todays_appointments' => count($this->appointmentModel->getCounselorTodayAppointments($userId)),
            ];
        }

        $data = [
            'title' => 'User Details - ' . APP_NAME,
            'page_title' => 'User Details',
            'user' => $user,
            'counselor_stats' => $counselorStats
        ];

        $this->view('admin/user-details', $data);
    }


    public function appointments()
    {
        $this->requireRole('admin');

        $status = $_GET['status'] ?? '';
        $counselorId = $_GET['counselor_id'] ?? '';
        $studentId = $_GET['student_id'] ?? '';

        $appointments = $this->appointmentModel->getAllAppointments($status, $counselorId, $studentId);
        $counselors = $this->userModel->getUsersByRole('counselor');
        $students = $this->userModel->getUsersByRole('student');

        $data = [
            'title' => 'Appointment Management - ' . APP_NAME,
            'page_title' => 'Appointment Management',
            'appointments' => $appointments,
            'filter_status' => $status,
            'filter_counselor_id' => $counselorId,
            'filter_student_id' => $studentId,
            'counselors' => $counselors,
            'students' => $students
        ];

        $this->view('admin/appointments', $data);
    }

    public function changeUserRole($userId = null)
    {
        $this->requireRole('admin');

        if (!$userId || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/users');
        }

        $newRole = $_POST['role'] ?? '';
        if (!in_array($newRole, ['student', 'counselor', 'admin'], true)) {
            $this->setFlash('error', 'Invalid role selected.');
            $this->redirect('admin/users');
        }

        if ($this->userModel->update('users', ['role' => $newRole], $userId)) {
            $this->setFlash('success', 'User role updated successfully.');
        } else {
            $this->setFlash('error', 'Failed to update user role.');
        }

        $this->redirect('admin/users');
    }

    public function toggleUserStatus($userId = null)
    {
        $this->requireRole('admin');

        if (!$userId) {
            $this->redirect('admin/users');
        }

        $user = $this->userModel->findById('users', $userId);
        if (!$user) {
            $this->setFlash('error', 'User not found.');
            $this->redirect('admin/users');
        }

        $currentStatus = $user->status ?? 'active';
        $newStatus = $currentStatus === 'active' ? 'inactive' : 'active';

        if ($this->userModel->update('users', ['status' => $newStatus], $userId)) {
            $this->setFlash('success', 'User status updated to ' . $newStatus . '.');
        } else {
            $this->setFlash('error', 'Failed to update user status.');
        }

        $this->redirect('admin/users');
    }

    public function updateAppointment($appointmentId = null)
    {
        $this->requireRole('admin');

        if (!$appointmentId || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/appointments');
        }

        $status = $_POST['status'] ?? '';
        $counselorId = $_POST['counselor_id'] ?? '';

        $updateData = [];
        if ($status !== '') {
            $updateData['status'] = $status;
        }
        if ($counselorId !== '') {
            $updateData['counselor_id'] = (int)$counselorId;
        }

        if (empty($updateData)) {
            $this->setFlash('error', 'Nothing to update.');
            $this->redirect('admin/appointments');
        }

        if ($this->appointmentModel->updateAppointment($appointmentId, $updateData)) {
            $this->setFlash('success', 'Appointment updated successfully.');
        } else {
            $this->setFlash('error', 'Failed to update appointment.');
        }

        $this->redirect('admin/appointments');
    }
}
?>