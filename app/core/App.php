<?php
/**
 * Main Application Class - Front Controller
 */

class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // Check if controller exists
        if (isset($url[0]) && file_exists(APP_PATH . '/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }

        // Instantiate controller
        require_once APP_PATH . '/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Check if method exists (convert hyphenated URLs to camelCase)
        if (isset($url[1])) {
            // Convert hyphenated method names to camelCase
            $methodName = $this->convertToCamelCase($url[1]);
            
            if (method_exists($this->controller, $methodName)) {
                $this->method = $methodName;
                unset($url[1]);
            }
        }

        // Get parameters
        $this->params = $url ? array_values($url) : [];

        // Call controller method with parameters
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }

    /**
     * Convert hyphenated URL segments to camelCase method names
     * Example: book-appointment -> bookAppointment
     */
    private function convertToCamelCase($string)
    {
        // Replace hyphens with spaces, capitalize each word, then remove spaces
        $string = str_replace('-', ' ', $string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);
        // Make first character lowercase
        return lcfirst($string);
    }
}
?>