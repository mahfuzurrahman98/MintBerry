<?php

namespace Scandiweb\Core;

class Router {
  protected $routes = [];

  public function add($path, $controller, $action, $method) {
    $this->routes[] = [
      'path' => $path,
      'controller' => $controller,
      'action' => $action,
      'method' => $method,
    ];
  }

  public function get($path, $controller, $action) {
    $this->add($path, $controller, $action, 'GET');
  }

  public function post($path, $controller, $action) {
    $this->add($path, $controller, $action, 'POST');
  }

  public function put($path, $controller, $action) {
    $this->add($path, $controller, $action, 'PUT');
  }

  public function delete($path, $controller, $action) {
    $this->add($path, $controller, $action, 'DELETE');
  }

  public function patch($path, $controller, $action) {
    $this->add($path, $controller, $action, 'PATCH');
  }

  public function route() {
    $uri = parse_url($_SERVER['REQUEST_URI']);
    $methodMatch = true;

    foreach ($this->routes as $route) {
      if ($uri['path'] === $route['path']) {

        if ($_SERVER['REQUEST_METHOD'] !== $route['method']) {
          $methodMatch = false;
          continue;
        }

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

    if (!$methodMatch) {
      http_response_code(405);
      echo json_encode([
        'success' => false,
        'status' => 405,
        'message' => 'The requested method is not allowed',
      ]);
      return;
    }

    http_response_code(404);
    echo json_encode([
      'success' => false,
      'status' => 404,
      'message' => 'The requested method is not found',
    ]);
    return;
  }
}
