<?php
/**
 * Base Controller Class
 */

class Controller
{
    /**
     * Load model
     */
    public function model($model)
    {
        require_once APP_PATH . '/models/' . $model . '.php';
        return new $model();
    }

    /**
     * Load view
     */
    public function view($view, $data = [])
    {
        // Extract data array to variables
        if (!empty($data)) {
            extract($data);
        }

        // Check if view file exists
        $viewFile = APP_PATH . '/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View {$view} not found.");
        }
    }

    /**
     * Redirect to another page
     */
    public function redirect($url)
    {
        header('Location: ' . APP_URL . '/' . $url);
        exit();
    }

    /**
     * Check if user is logged in
     */
    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Check if user has specific role
     */
    public function hasRole($role)
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }

    /**
     * Require authentication
     */
    public function requireAuth()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }
    }

    /**
     * Require specific role
     */
    public function requireRole($role)
    {
        $this->requireAuth();
        if (!$this->hasRole($role)) {
            $this->redirect('home/unauthorized');
        }
    }

    /**
     * Set flash message
     */
    public function setFlash($type, $message)
    {
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Get flash message
     */
    public function getFlash($type)
    {
        if (isset($_SESSION['flash'][$type])) {
            $message = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $message;
        }
        return null;
    }
}
?>