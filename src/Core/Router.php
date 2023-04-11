<?php

namespace Scandiweb\Core;

class Router {
  protected $routes = [];

  public function get($path, $controller, $action) {
    $this->routes[] = [
      'path' => $path,
      'controller' => $controller,
      'action' => $action,
      'method' => 'GET',
    ];
  }

  public function post($path, $controller, $action) {
    $this->routes[] = [
      'path' => $path,
      'controller' => $controller,
      'action' => $action,
      'method' => 'POST',
    ];
  }

  public function put($path, $controller, $action) {
    $this->routes[] = [
      'path' => $path,
      'controller' => $controller,
      'action' => $action,
      'method' => 'PUT',
    ];
  }

  public function delete($path, $controller, $action) {
    $this->routes[] = [
      'path' => $path,
      'controller' => $controller,
      'action' => $action,
      'method' => 'DELETE',
    ];
  }

  public function route() {
    $uri = parse_url($_SERVER['REQUEST_URI']);
    // die(realpath(__DIR__ . '/../'));
    foreach ($this->routes as $route) {
      if ($route['path'] === $uri['path'] && $_SERVER['REQUEST_METHOD'] == $route['method']) {
        $controllerName = $route['controller'];
        $action = $route['action'];

        // Require the controller file
        require_once realpath(__DIR__ . '/../') . '/App/Controllers/' . $controllerName . '.php';

        // Create an instance of the controller
        $className = 'Scandiweb\\App\\Controllers\\' . $controllerName;
        $controller = new $className();

        // Call the action method on the controller instance
        $controller->$action();
        return;
      }
    }

    // If no matching route is found, return a 404 error
    http_response_code(404);
    echo "Page not found!";
  }
}
