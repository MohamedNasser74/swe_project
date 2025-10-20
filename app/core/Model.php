<?php
/**
 * Base Model Class with Database Connection
 */

class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Find record by ID
     */
    public function findById($table, $id)
    {
        $sql = "SELECT * FROM {$table} WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Find all records
     */
    public function findAll($table, $orderBy = 'id DESC')
    {
        $sql = "SELECT * FROM {$table} ORDER BY {$orderBy}";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    /**
     * Create new record
     */
    public function create($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $this->db->query($sql);
        
        foreach ($data as $key => $value) {
            $this->db->bind(':' . $key, $value);
        }
        
        return $this->db->execute();
    }

    /**
     * Update record
     */
    public function update($table, $data, $id)
    {
        $set = '';
        foreach (array_keys($data) as $key) {
            $set .= $key . ' = :' . $key . ', ';
        }
        $set = rtrim($set, ', ');
        
        $sql = "UPDATE {$table} SET {$set} WHERE id = :id";
        $this->db->query($sql);
        
        foreach ($data as $key => $value) {
            $this->db->bind(':' . $key, $value);
        }
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

    /**
     * Delete record
     */
    public function delete($table, $id)
    {
        $sql = "DELETE FROM {$table} WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Count records
     */
    public function count($table, $condition = '')
    {
        $sql = "SELECT COUNT(*) as count FROM {$table}";
        if (!empty($condition)) {
            $sql .= " WHERE {$condition}";
        }
        $this->db->query($sql);
        $result = $this->db->single();
        return $result->count;
    }
}
?>