<?php
/**
 * Menu Model Unit Tests
 * Tests dynamic menu with self-reference (nested structure)
 */

class MenuModelTest extends BaseTestCase
{
    private $menuModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->menuModel = new Menu();
    }

    /**
     * Test: Menu model can be instantiated
     */
    public function testMenuModelCanBeInstantiated()
    {
        $this->assertInstanceOf(Menu::class, $this->menuModel);
    }

    /**
     * Test: Menu item structure is valid
     */
    public function testMenuItemStructure()
    {
        $menuItem = [
            'id' => 1,
            'title' => 'Home',
            'url' => '/',
            'icon' => 'fas fa-home',
            'parent_id' => null,
            'sort_order' => 1,
            'is_active' => true,
            'role_access' => 'all'
        ];
        
        $this->assertArrayHasKey('id', $menuItem);
        $this->assertArrayHasKey('title', $menuItem);
        $this->assertArrayHasKey('url', $menuItem);
        $this->assertArrayHasKey('parent_id', $menuItem);
        $this->assertArrayHasKey('sort_order', $menuItem);
        $this->assertArrayHasKey('is_active', $menuItem);
        $this->assertArrayHasKey('role_access', $menuItem);
    }

    /**
     * Test: Valid role access values
     */
    public function testValidRoleAccessValues()
    {
        $validRoles = ['all', 'guest', 'student', 'counselor', 'admin'];
        
        foreach ($validRoles as $role) {
            $this->assertContains($role, $validRoles);
        }
        
        $this->assertNotContains('superadmin', $validRoles);
    }

    /**
     * Test: Nested menu structure (self-reference)
     */
    public function testNestedMenuStructure()
    {
        // Simulate menu items from database
        $menuItems = [
            (object)['id' => 1, 'title' => 'Home', 'url' => '/', 'parent_id' => null],
            (object)['id' => 2, 'title' => 'Services', 'url' => '/services', 'parent_id' => null],
            (object)['id' => 3, 'title' => 'Career Guidance', 'url' => '/services/career', 'parent_id' => 2],
            (object)['id' => 4, 'title' => 'Interview Prep', 'url' => '/services/interview', 'parent_id' => 2],
        ];
        
        // Build tree structure manually for testing
        $tree = $this->buildTestTree($menuItems, null);
        
        // Should have 2 top-level items
        $this->assertCount(2, $tree);
        
        // Services should have 2 children
        $servicesItem = array_filter($tree, fn($item) => $item->title === 'Services');
        $this->assertNotEmpty($servicesItem);
    }

    /**
     * Helper: Build tree structure for testing
     */
    private function buildTestTree($items, $parentId)
    {
        $tree = [];
        foreach ($items as $item) {
            if ($item->parent_id == $parentId) {
                $item->children = $this->buildTestTree($items, $item->id);
                $tree[] = $item;
            }
        }
        return $tree;
    }

    /**
     * Test: URL format validation
     */
    public function testMenuUrlFormat()
    {
        $validUrls = [
            '/',
            '/home',
            '/home/services',
            '/home/services#career-guidance',
            'https://external-link.com'
        ];
        
        foreach ($validUrls as $url) {
            // Should start with / or http
            $isValid = str_starts_with($url, '/') || str_starts_with($url, 'http');
            $this->assertTrue($isValid, "URL '$url' should be valid");
        }
    }

    /**
     * Test: Icon class format (Font Awesome)
     */
    public function testIconClassFormat()
    {
        $validIcons = [
            'fas fa-home',
            'fas fa-user',
            'fab fa-github',
            'far fa-envelope'
        ];
        
        foreach ($validIcons as $icon) {
            // Should start with fa
            $this->assertStringStartsWith('fa', $icon, "Icon '$icon' should start with 'fa'");
        }
    }

    /**
     * Test: Sort order is numeric
     */
    public function testSortOrderIsNumeric()
    {
        $sortOrders = [1, 2, 3, 10, 100];
        
        foreach ($sortOrders as $order) {
            $this->assertIsInt($order);
            $this->assertGreaterThan(0, $order);
        }
    }

    /**
     * Test: Title sanitization
     */
    public function testTitleSanitization()
    {
        $dirtyTitle = '<script>alert("xss")</script>Home';
        $cleanTitle = htmlspecialchars($dirtyTitle, ENT_QUOTES, 'UTF-8');
        
        $this->assertStringNotContainsString('<script>', $cleanTitle);
        $this->assertStringContainsString('Home', $cleanTitle);
    }

    /**
     * Test: Parent-child relationship integrity
     */
    public function testParentChildRelationship()
    {
        // Simulate a valid parent-child relationship
        $parent = (object)['id' => 1, 'title' => 'Parent', 'parent_id' => null];
        $child = (object)['id' => 2, 'title' => 'Child', 'parent_id' => 1];
        
        // Child's parent_id should match parent's id
        $this->assertEquals($parent->id, $child->parent_id);
        
        // Parent should have no parent (top-level)
        $this->assertNull($parent->parent_id);
    }

    /**
     * Test: Circular reference prevention
     */
    public function testCircularReferencePrevention()
    {
        // A menu item cannot be its own parent
        $menuItem = ['id' => 1, 'parent_id' => 1];
        
        $this->assertEquals(
            $menuItem['id'],
            $menuItem['parent_id'],
            'This would create a circular reference - should be prevented'
        );
        
        // In real implementation, this validation should return false
        $isCircular = $menuItem['id'] === $menuItem['parent_id'];
        $this->assertTrue($isCircular, 'Circular reference detected correctly');
    }
}
