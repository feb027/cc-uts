<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $action, callable $handler): void { $this->routes['GET'][$action] = $handler; }
    public function post(string $action, callable $handler): void { $this->routes['POST'][$action] = $handler; }

    public function dispatch(string $action): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $action = $action ?: 'index';
        if (isset($this->routes[$method][$action])) {
            ($this->routes[$method][$action])();
            return;
        }
        // Fallback: if GET and not found but index exists, call index
        if ($method === 'GET' && isset($this->routes['GET']['index']) && $action === 'index') {
            ($this->routes['GET']['index'])();
            return;
        }
        http_response_code(404);
        echo '404 Not Found';
    }
}
?>