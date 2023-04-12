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

    foreach ($this->routes as $route) {
      // Check if the request path matches the route path
      if ($uri['path'] !== $route['path']) {
        // If no matching route is found, return a 404 error
        http_response_code(404);
        echo json_encode([
          'success' => false,
          'status' => 404,
          'message' => 'The requested resource could not be found',
        ]);
        return;
      }

      // Check if the request method matches the route method
      if ($_SERVER['REQUEST_METHOD'] !== $route['method']) {
        // If no matching route is found, return a 404 error
        http_response_code(405);
        echo json_encode([
          'success' => false,
          'status' => 405,
          'message' => 'The requested method is not allowed',
        ]);
        return;
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
}
