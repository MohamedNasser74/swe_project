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

    /**
     * Get all menus for admin management (including inactive)
     */
    public function getAllMenus()
    {
        $sql = "SELECT m.*, p.title as parent_title 
                FROM {$this->table} m
                LEFT JOIN {$this->table} p ON m.parent_id = p.id
                ORDER BY m.sort_order ASC, m.id ASC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    /**
     * Get single menu by ID
     */
    public function getMenuById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Create menu (alias for createMenuItem)
     */
    public function createMenu($data)
    {
        $sql = "INSERT INTO {$this->table} (title, url, icon, parent_id, sort_order, role_access, is_active) 
                VALUES (:title, :url, :icon, :parent_id, :sort_order, :role_access, :is_active)";
        $this->db->query($sql);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':url', $data['url']);
        $this->db->bind(':icon', $data['icon']);
        $this->db->bind(':parent_id', $data['parent_id']);
        $this->db->bind(':sort_order', $data['sort_order']);
        $this->db->bind(':role_access', $data['role_access']);
        $this->db->bind(':is_active', $data['is_active']);
        return $this->db->execute();
    }

    /**
     * Update menu (alias for updateMenuItem)
     */
    public function updateMenu($id, $data)
    {
        $sql = "UPDATE {$this->table} 
                SET title = :title, url = :url, icon = :icon, parent_id = :parent_id, 
                    sort_order = :sort_order, role_access = :role_access, is_active = :is_active,
                    updated_at = NOW()
                WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':url', $data['url']);
        $this->db->bind(':icon', $data['icon']);
        $this->db->bind(':parent_id', $data['parent_id']);
        $this->db->bind(':sort_order', $data['sort_order']);
        $this->db->bind(':role_access', $data['role_access']);
        $this->db->bind(':is_active', $data['is_active']);
        return $this->db->execute();
    }

    /**
     * Delete menu (alias for deleteMenuItem)
     */
    public function deleteMenu($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
?>
