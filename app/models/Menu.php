<?php

class Menu extends Model
{
    private $table = 'menus';

    /**
     * Get all active menu items ordered by sort_order
     */
    public function getAllMenuItems()
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY sort_order ASC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    /**
     * Get menu items by role access
     * @param string $role - 'all', 'guest', 'student', 'counselor', 'admin'
     */
    public function getMenuItemsByRole($role = 'all')
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE is_active = 1 
                AND (role_access = 'all' OR role_access = :role)
                ORDER BY sort_order ASC";
        $this->db->query($sql);
        $this->db->bind(':role', $role);
        return $this->db->resultSet();
    }

    /**
     * Get top-level menu items (no parent)
     */
    public function getTopLevelMenus($role = 'all')
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE is_active = 1 
                AND parent_id IS NULL
                AND (role_access = 'all' OR role_access = :role)
                ORDER BY sort_order ASC";
        $this->db->query($sql);
        $this->db->bind(':role', $role);
        return $this->db->resultSet();
    }

    /**
     * Get child menu items by parent_id
     */
    public function getChildMenus($parentId, $role = 'all')
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE is_active = 1 
                AND parent_id = :parent_id
                AND (role_access = 'all' OR role_access = :role)
                ORDER BY sort_order ASC";
        $this->db->query($sql);
        $this->db->bind(':parent_id', $parentId);
        $this->db->bind(':role', $role);
        return $this->db->resultSet();
    }

    /**
     * Build hierarchical menu tree (recursive structure)
     * Returns nested array with children
     */
    public function buildMenuTree($role = 'all')
    {
        $allMenus = $this->getMenuItemsByRole($role);
        return $this->buildTree($allMenus, null);
    }

    /**
     * Recursive function to build nested tree structure
     */
    private function buildTree($items, $parentId = null)
    {
        $tree = [];
        foreach ($items as $item) {
            $itemParentId = $item->parent_id ?? null;
            if ($itemParentId == $parentId) {
                $children = $this->buildTree($items, $item->id);
                if (!empty($children)) {
                    $item->children = $children;
                } else {
                    $item->children = [];
                }
                $tree[] = $item;
            }
        }
        return $tree;
    }

    /**
     * Create a new menu item
     */
    public function createMenuItem($data)
    {
        return $this->create($this->table, $data);
    }

    /**
     * Update a menu item
     */
    public function updateMenuItem($id, $data)
    {
        return $this->update($this->table, $data, $id);
    }

    /**
     * Delete a menu item (will cascade to children due to FK)
     */
    public function deleteMenuItem($id)
    {
        return $this->delete($this->table, $id);
    }
}
?>
