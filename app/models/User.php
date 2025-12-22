<?php

class User extends Model
{
    private $table = 'users';

    /**
     * Create new user
     */
    public function createUser($data)
    {
        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Set email as verified by default
        $data['email_verified'] = 1;
        
        return $this->create($this->table, $data);
    }

    /**
     * Find user by email
     */
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $this->db->query($sql);
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    /**
     * Find user by username
     */
    public function findByUsername($username)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username";
        $this->db->query($sql);
        $this->db->bind(':username', $username);
        return $this->db->single();
    }

    /**
     * Verify user login
     */
    public function verifyLogin($email, $password)
    {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        
        return false;
    }

    /**
     * Update user profile
     */
    public function updateProfile($userId, $data)
    {
        return $this->update($this->table, $data, $userId);
    }


    /**
     * Get users by role
     */
    public function getUsersByRole($role)
    {
        $sql = "SELECT * FROM {$this->table} WHERE role = :role ORDER BY created_at DESC";
        $this->db->query($sql);
        $this->db->bind(':role', $role);
        return $this->db->resultSet();
    }

    /**
     * Get user statistics
     */
    public function getUserStats()
    {
        $sql = "SELECT 
                    role,
                    COUNT(*) as count
                FROM {$this->table} 
                GROUP BY role";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    /**
     * Get all users with optional role/status filters
     */
    public function getAllUsers($role = null, $status = null)
    {
        $conditions = [];
        $params = [];

        if ($role !== null && $role !== '') {
            $conditions[] = "role = :role";
            $params[':role'] = $role;
        }

        if ($status !== null && $status !== '') {
            $conditions[] = "status = :status";
            $params[':status'] = $status;
        }

        $sql = "SELECT * FROM {$this->table}";
        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        $sql .= ' ORDER BY created_at DESC';

        $this->db->query($sql);
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }

        return $this->db->resultSet();
    }

    /**
     * Get counselor's students (students who have had appointments)
     */
    public function getCounselorStudents($counselorId)
    {
        $sql = "SELECT DISTINCT u.*, 
                       COUNT(a.id) as total_appointments,
                       MAX(a.appointment_date) as last_appointment
                FROM {$this->table} u
                JOIN appointments a ON u.id = a.student_id
                WHERE a.counselor_id = :counselor_id AND u.role = 'student'
                GROUP BY u.id
                ORDER BY last_appointment DESC";
        
        $this->db->query($sql);
        $this->db->bind(':counselor_id', $counselorId);
        return $this->db->resultSet();
    }

    /**
     * Update user profile
     */
    public function updateUser($userId, $data)
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
        
        // Add updated_at timestamp
        $fields[] = "updated_at = :updated_at";
        $values[':updated_at'] = date('Y-m-d H:i:s');
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        
        $this->db->query($sql);
        $this->db->bind(':id', $userId);
        
        foreach ($values as $param => $value) {
            $this->db->bind($param, $value);
        }
        
        return $this->db->execute();
    }

    /**
     * Find user by ID
     */
    public function getUserById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}
?>