<?php

/**
 * MenuHelper - Renders dynamic navigation menu with support for nested items
 */
class MenuHelper
{
    private $menuModel;
    private $baseUrl;

    public function __construct()
    {
        $this->menuModel = new Menu();
        $this->baseUrl = APP_URL;
    }

    /**
     * Get the current user's role for menu filtering
     */
    private function getCurrentRole()
    {
        if (!isset($_SESSION['user_id'])) {
            return 'guest';
        }
        return $_SESSION['user_role'] ?? 'student';
    }

    /**
     * Build the full URL for a menu item
     */
    private function buildUrl($url)
    {
        // If URL starts with http/https, return as-is (external link)
        if (preg_match('/^https?:\/\//', $url)) {
            return $url;
        }
        // If URL is just '/', return base URL
        if ($url === '/') {
            return $this->baseUrl;
        }
        // Otherwise, append to base URL
        return $this->baseUrl . $url;
    }

    /**
     * Render the complete navigation menu HTML
     * @return string HTML for the navigation menu
     */
    public function renderMenu()
    {
        $role = $this->getCurrentRole();
        $menuTree = $this->menuModel->buildMenuTree($role);

        if (empty($menuTree)) {
            return $this->renderFallbackMenu();
        }

        return $this->renderMenuItems($menuTree);
    }

    /**
     * Recursively render menu items with nested dropdowns
     */
    private function renderMenuItems($items, $isSubmenu = false)
    {
        $html = '';

        foreach ($items as $item) {
            $hasChildren = !empty($item->children);
            $url = $this->buildUrl($item->url);
            $icon = !empty($item->icon) ? '<i class="' . htmlspecialchars($item->icon) . ' me-1"></i>' : '';
            $title = htmlspecialchars($item->title);

            if ($hasChildren) {
                // Dropdown menu item
                $html .= '<li class="nav-item dropdown">';
                $html .= '<a class="nav-link dropdown-toggle" href="#" id="menu-' . $item->id . '" role="button" data-bs-toggle="dropdown" aria-expanded="false">';
                $html .= $icon . $title;
                $html .= '</a>';
                $html .= '<ul class="dropdown-menu" aria-labelledby="menu-' . $item->id . '">';
                
                // Render children
                foreach ($item->children as $child) {
                    $childUrl = $this->buildUrl($child->url);
                    $childIcon = !empty($child->icon) ? '<i class="' . htmlspecialchars($child->icon) . ' me-2"></i>' : '';
                    $childTitle = htmlspecialchars($child->title);
                    
                    // Check if child has its own children (multi-level)
                    if (!empty($child->children)) {
                        $html .= '<li class="dropdown-submenu">';
                        $html .= '<a class="dropdown-item dropdown-toggle" href="' . $childUrl . '">';
                        $html .= $childIcon . $childTitle;
                        $html .= '</a>';
                        $html .= '<ul class="dropdown-menu">';
                        $html .= $this->renderSubmenuItems($child->children);
                        $html .= '</ul>';
                        $html .= '</li>';
                    } else {
                        $html .= '<li><a class="dropdown-item" href="' . $childUrl . '">' . $childIcon . $childTitle . '</a></li>';
                    }
                }
                
                $html .= '</ul>';
                $html .= '</li>';
            } else {
                // Regular menu item
                $html .= '<li class="nav-item">';
                $html .= '<a class="nav-link" href="' . $url . '">';
                $html .= $icon . $title;
                $html .= '</a>';
                $html .= '</li>';
            }
        }

        return $html;
    }

    /**
     * Render deeply nested submenu items
     */
    private function renderSubmenuItems($items)
    {
        $html = '';
        foreach ($items as $item) {
            $url = $this->buildUrl($item->url);
            $icon = !empty($item->icon) ? '<i class="' . htmlspecialchars($item->icon) . ' me-2"></i>' : '';
            $title = htmlspecialchars($item->title);

            if (!empty($item->children)) {
                $html .= '<li class="dropdown-submenu">';
                $html .= '<a class="dropdown-item dropdown-toggle" href="' . $url . '">';
                $html .= $icon . $title;
                $html .= '</a>';
                $html .= '<ul class="dropdown-menu">';
                $html .= $this->renderSubmenuItems($item->children);
                $html .= '</ul>';
                $html .= '</li>';
            } else {
                $html .= '<li><a class="dropdown-item" href="' . $url . '">' . $icon . $title . '</a></li>';
            }
        }
        return $html;
    }

    /**
     * Fallback menu if database is empty or unavailable
     */
    private function renderFallbackMenu()
    {
        $html = '';
        $fallbackItems = [
            ['title' => 'Home', 'url' => '/', 'icon' => 'fas fa-home'],
            ['title' => 'Learn More', 'url' => '/home/learn-more', 'icon' => 'fas fa-book-open'],
            ['title' => 'Services', 'url' => '/home/services', 'icon' => 'fas fa-briefcase'],
            ['title' => 'Contact', 'url' => '/home/contact', 'icon' => 'fas fa-envelope'],
        ];

        foreach ($fallbackItems as $item) {
            $url = $this->buildUrl($item['url']);
            $html .= '<li class="nav-item">';
            $html .= '<a class="nav-link" href="' . $url . '">';
            $html .= '<i class="' . $item['icon'] . ' me-1"></i>' . $item['title'];
            $html .= '</a>';
            $html .= '</li>';
        }

        return $html;
    }
}
?>
