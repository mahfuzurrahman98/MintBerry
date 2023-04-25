<?php

namespace MintBerry\Core;

class Router {
  protected $routes;

  public function __construct() {
    $this->routes = [];
  }

  public function add($path, $controller, $action, $requestMethod) {
    $this->routes[] = [
      'path' => $path,
      'controller' => $controller,
      'action' => $action,
      'requestMethod' => $requestMethod,
      'middlewares' => []
    ];
    return $this;
  }

  public function get($path, $action) {
    $arr = explode('@', trim($action));
    $controller = $arr[0];
    $method = $arr[1];
    return $this->add($path, $controller, $method, 'GET');
  }

  public function post($path, $action) {
    $arr = explode('@', trim($action));
    $controller = $arr[0];
    $method = $arr[1];
    return $this->add($path, $controller, $method, 'POST');
  }

  public function put($path, $action) {
    $arr = explode('@', trim($action));
    $controller = $arr[0];
    $method = $arr[1];
    return $this->add($path, $controller, $method, 'PUT');
  }

  public function delete($path, $action) {
    $arr = explode('@', trim($action));
    $controller = $arr[0];
    $method = $arr[1];
    return $this->add($path, $controller, $method, 'DELETE');
  }

  public function patch($path, $action) {
    $arr = explode('@', trim($action));
    $controller = $arr[0];
    $method = $arr[1];
    return $this->add($path, $controller, $method, 'PATCH');
  }

  public function middleware($names) {
    $names = is_array($names) ? $names : [$names];
    $this->routes[count($this->routes) - 1]['middlewares'] = $names;
    return $this;
  }

  public function route() {
    $uri = parse_url($_SERVER['REQUEST_URI']);
    $methodMatch = true;

    foreach ($this->routes as $route) {
      if ($uri['path'] === $route['path']) {

        if ($_SERVER['REQUEST_METHOD'] !== $route['requestMethod']) {
          $methodMatch = false;
          continue;
        }

        // Check if the route has any middleware
        if (count($route['middlewares']) > 0) {
          foreach ($route['middlewares'] as $middleware) {
            $middlewareName = $middleware;
            $middlewarePath = BASE_PATH . '/src/App/Middlewares/' . $middlewareName . '.php';

            if (!file_exists($middlewarePath)) {
              http_response_code(404);
              echo json_encode(['message' => 'The requested middleware is not found']);
              return;
            }

            require_once $middlewarePath;

            $middlewareClass = 'MintBerry\\App\\Middlewares\\' . $middlewareName;
            $middleware = new $middlewareClass();

            $middleware->handle();
          }
        }

        $controllerName = $route['controller'];
        $action = $route['action'];

        // Require the controller file
        if (!file_exists(BASE_PATH . '/src/App/Controllers/' . $controllerName . '.php')) {
          http_response_code(404);
          echo json_encode(['message' => 'The requested controller is not found']);
          return;
        }
        require_once BASE_PATH . '/src/App/Controllers/' . $controllerName . '.php';

        // Create an instance of the controller
        $className = 'MintBerry\\App\\Controllers\\' . $controllerName;
        $controller = new $className();

        // Call the action method on the controller instance
        if (!method_exists($controller, $action)) {
          http_response_code(404);
          echo json_encode(['message' => 'The controller has no such method']);
          return;
        }
        $controller->$action();
        return;
      }
    }

    if (!$methodMatch) {
      http_response_code(405);
      echo json_encode(['message' => 'The requested method is not allowed']);
      return;
    }

    http_response_code(404);
    echo json_encode(['message' => 'The requested method is not found']);
    return;
  }

  // create a destructor to run the route method
  public function __destruct() {
    $this->route();
  }
}
