<?php

class Appointment extends Model
{
    private $table = 'appointments';

    /**
     * Book new appointment
     */
    public function bookAppointment($data)
    {
        return $this->create($this->table, $data);
    }

    /**
     * Get appointments for student
     */
    public function getStudentAppointments($studentId)
    {
        $sql = "SELECT a.*, 
                       CONCAT(c.first_name, ' ', c.last_name) as counselor_name,
                       cp.specialization
                FROM {$this->table} a
                JOIN users c ON a.counselor_id = c.id
                LEFT JOIN counselor_profiles cp ON c.id = cp.user_id
                WHERE a.student_id = :student_id
                ORDER BY a.appointment_date DESC, a.appointment_time DESC";
        
        $this->db->query($sql);
        $this->db->bind(':student_id', $studentId);
        return $this->db->resultSet();
    }



    /**
     * Get upcoming appointments
     */
    public function getUpcomingAppointments($userId, $role)
    {
        $userField = ($role === 'student') ? 'student_id' : 'counselor_id';
        
        $sql = "SELECT a.*, 
                       CONCAT(u.first_name, ' ', u.last_name) as other_user_name
                FROM {$this->table} a
                JOIN users u ON ";
        
        if ($role === 'student') {
            $sql .= "a.counselor_id = u.id";
        } else {
            $sql .= "a.student_id = u.id";
        }
        
        $sql .= " WHERE a.{$userField} = :user_id 
                  AND CONCAT(a.appointment_date, ' ', a.appointment_time) > NOW()
                  AND a.status IN ('scheduled', 'confirmed')
                  ORDER BY a.appointment_date ASC, a.appointment_time ASC";
        
        $this->db->query($sql);
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    /**
     * Check counselor availability
     */
    public function checkAvailability($counselorId, $date, $time)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE counselor_id = :counselor_id 
                AND appointment_date = :date 
                AND appointment_time = :time 
                AND status NOT IN ('cancelled', 'no_show')";
        
        $this->db->query($sql);
        $this->db->bind(':counselor_id', $counselorId);
        $this->db->bind(':date', $date);
        $this->db->bind(':time', $time);
        
        $result = $this->db->single();
        return $result->count == 0;
    }

    /**
     * Update appointment status
     */
    public function updateStatus($appointmentId, $status)
    {
        $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $appointmentId);
        return $this->db->execute();
    }

    /**
     * Add feedback and rating
     */
    public function addFeedback($appointmentId, $feedback, $rating)
    {
        $sql = "UPDATE {$this->table} SET feedback = :feedback, rating = :rating WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':feedback', $feedback);
        $this->db->bind(':rating', $rating);
        $this->db->bind(':id', $appointmentId);
        return $this->db->execute();
    }

    /**
     * Get appointment statistics
     */
    public function getAppointmentStats($userId = null, $role = null)
    {
        $sql = "SELECT 
                    status,
                    COUNT(*) as count,
                    session_type,
                    AVG(rating) as avg_rating
                FROM {$this->table}";
        
        if ($userId && $role) {
            $userField = ($role === 'student') ? 'student_id' : 'counselor_id';
            $sql .= " WHERE {$userField} = :user_id";
        }
        
        $sql .= " GROUP BY status, session_type";
        
        $this->db->query($sql);
        if ($userId && $role) {
            $this->db->bind(':user_id', $userId);
        }
        
        return $this->db->resultSet();
    }

    /**
     * Cancel appointment
     */
    public function cancelAppointment($appointmentId, $userId, $role)
    {
        $userField = ($role === 'student') ? 'student_id' : 'counselor_id';
        
        $sql = "UPDATE {$this->table} SET status = 'cancelled' 
                WHERE id = :id AND {$userField} = :user_id";
        
        $this->db->query($sql);
        $this->db->bind(':id', $appointmentId);
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }

    /**
     * Get today's appointments for counselor
     */
    public function getCounselorTodayAppointments($counselorId)
    {
        $sql = "SELECT a.*, 
                       CONCAT(s.first_name, ' ', s.last_name) as student_name,
                       s.email as student_email
                FROM {$this->table} a
                JOIN users s ON a.student_id = s.id
                WHERE a.counselor_id = :counselor_id 
                AND DATE(a.appointment_date) = CURDATE()
                ORDER BY a.appointment_time ASC";
        
        $this->db->query($sql);
        $this->db->bind(':counselor_id', $counselorId);
        return $this->db->resultSet();
    }

    /**
     * Get upcoming appointments for counselor
     */
    public function getCounselorUpcomingAppointments($counselorId, $days = 7)
    {
        $sql = "SELECT a.*, 
                       CONCAT(s.first_name, ' ', s.last_name) as student_name,
                       s.email as student_email
                FROM {$this->table} a
                JOIN users s ON a.student_id = s.id
                WHERE a.counselor_id = :counselor_id 
                AND a.appointment_date BETWEEN CURDATE() + INTERVAL 1 DAY AND CURDATE() + INTERVAL :days DAY
                ORDER BY a.appointment_date ASC, a.appointment_time ASC";
        
        $this->db->query($sql);
        $this->db->bind(':counselor_id', $counselorId);
        $this->db->bind(':days', $days);
        return $this->db->resultSet();
    }

    /**
     * Get pending appointments for counselor
     */
    public function getCounselorPendingAppointments($counselorId)
    {
        $sql = "SELECT a.*, 
                       CONCAT(s.first_name, ' ', s.last_name) as student_name,
                       s.email as student_email
                FROM {$this->table} a
                JOIN users s ON a.student_id = s.id
                WHERE a.counselor_id = :counselor_id 
                AND a.status = 'pending'
                ORDER BY a.appointment_date ASC, a.appointment_time ASC";
        
        $this->db->query($sql);
        $this->db->bind(':counselor_id', $counselorId);
        return $this->db->resultSet();
    }

    /**
     * Get total appointments count for counselor
     */
    public function getCounselorTotalAppointments($counselorId)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE counselor_id = :counselor_id";
        
        $this->db->query($sql);
        $this->db->bind(':counselor_id', $counselorId);
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    /**
     * Get completed appointments count for counselor
     */
    public function getCounselorCompletedAppointments($counselorId)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE counselor_id = :counselor_id AND status = 'completed'";
        
        $this->db->query($sql);
        $this->db->bind(':counselor_id', $counselorId);
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    /**
     * Get all appointments with optional filters for admin
     */
    public function getAllAppointments($status = null, $counselorId = null, $studentId = null)
    {
        $conditions = [];
        $params = [];

        if ($status !== null && $status !== '') {
            $conditions[] = "a.status = :status";
            $params[':status'] = $status;
        }

        if ($counselorId !== null && $counselorId !== '') {
            $conditions[] = "a.counselor_id = :counselor_id";
            $params[':counselor_id'] = (int)$counselorId;
        }

        if ($studentId !== null && $studentId !== '') {
            $conditions[] = "a.student_id = :student_id";
            $params[':student_id'] = (int)$studentId;
        }

        $sql = "SELECT a.*,
                       CONCAT(s.first_name, ' ', s.last_name) as student_name,
                       CONCAT(c.first_name, ' ', c.last_name) as counselor_name
                FROM {$this->table} a
                JOIN users s ON a.student_id = s.id
                JOIN users c ON a.counselor_id = c.id";

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY a.created_at DESC';

        $this->db->query($sql);
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }

        return $this->db->resultSet();
    }

    /**
     * Get counselor appointments with filter
     */
    public function getCounselorAppointments($counselorId, $status = 'all')
    {
        $sql = "SELECT a.*, 
                       CONCAT(s.first_name, ' ', s.last_name) as student_name,
                       s.email as student_email
                FROM {$this->table} a
                JOIN users s ON a.student_id = s.id
                WHERE a.counselor_id = :counselor_id";
        
        if ($status !== 'all') {
            $sql .= " AND a.status = :status";
        }
        
        $sql .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC";
        
        $this->db->query($sql);
        $this->db->bind(':counselor_id', $counselorId);
        if ($status !== 'all') {
            $this->db->bind(':status', $status);
        }
        
        return $this->db->resultSet();
    }

    /**
     * Get counselor schedule
     */
    public function getCounselorSchedule($counselorId)
    {
        // For now, return a simple structure
        // In a real application, this would come from a schedule table
        return [
            'monday' => ['start' => '09:00', 'end' => '17:00', 'available' => true],
            'tuesday' => ['start' => '09:00', 'end' => '17:00', 'available' => true],
            'wednesday' => ['start' => '09:00', 'end' => '17:00', 'available' => true],
            'thursday' => ['start' => '09:00', 'end' => '17:00', 'available' => true],
            'friday' => ['start' => '09:00', 'end' => '17:00', 'available' => true],
            'saturday' => ['start' => '10:00', 'end' => '14:00', 'available' => false],
            'sunday' => ['start' => '10:00', 'end' => '14:00', 'available' => false]
        ];
    }

    /**
     * Update counselor schedule
     */
    public function updateCounselorSchedule($counselorId, $scheduleData)
    {
        // For now, just return true
        // In a real application, this would update a schedule table
        return true;
    }

    /**
     * Update appointment
     */
    public function updateAppointment($appointmentId, $data)
    {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = :{$key}";
            $values[":{$key}"] = $value;
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        
        $this->db->query($sql);
        $this->db->bind(':id', $appointmentId);
        
        foreach ($values as $param => $value) {
            $this->db->bind($param, $value);
        }
        
        return $this->db->execute();
    }
}
?>