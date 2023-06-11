<?php

namespace MintBerry\Core;

class Router {
  protected $routes;
  protected $prefixPath = '';
  protected $csrfProtection = true;

  public function __construct() {
    $this->routes = [];
  }

  public function add($path, $controller, $action, $requestMethod) {
    $path = $this->prefixPath . $path;
    $this->routes[] = [
      'path' => $path,
      'controller' => $controller,
      'action' => $action,
      'requestMethod' => $requestMethod,
      'middlewares' => []
    ];
    return $this;
  }

  public function disableCsrfProtection() {
    $this->csrfProtection = false;
  }

  public function prefix($prefixPath, $callback) {
    $previousPrefix = $this->prefixPath;
    $this->prefixPath = $previousPrefix . $prefixPath;
    $callback($this);
    $this->prefixPath = $previousPrefix;
  }

  public function get($path, $controller, $action) {
    return $this->add($path, $controller, $action, 'GET');
  }

  public function post($path, $controller, $action) {
    return $this->add($path, $controller, $action, 'POST');
  }

  public function put($path, $controller, $action) {
    return $this->add($path, $controller, $action, 'PUT');
  }

  public function delete($path, $controller, $action) {
    return $this->add($path, $controller, $action, 'DELETE');
  }

  public function patch($path, $controller, $action) {
    return $this->add($path, $controller, $action, 'PATCH');
  }

  public function middleware($names) {
    $names = is_array($names) ? $names : [$names];
    $this->routes[count($this->routes) - 1]['middlewares'] = $names;
    return $this;
  }

  public function route() {
    $uri = parse_url($_SERVER['REQUEST_URI']);

    if (isset($uri['path'])) {
      $path = $uri['path'];
    } else {
      http_response_code(404);
      echo json_encode(['message' => 'The requested resource is not found']);
      return;
    }

    if (env('APP_ROOT') != '') {
      $path = str_replace('/' . env('APP_ROOT'), '', $path);
    }

    $methodMatch = true;

    foreach ($this->routes as $route) {
      // Check if the route matches the current request
      if ($path === $route['path']) {
        // Check if the route method matches
        if (isset($_SERVER['_method']) && trim($_SERVER['_method']) != '') {
          $serverRequestMethod = trim($_SERVER['_method']);
        } else {
          $serverRequestMethod = $_SERVER['REQUEST_METHOD'];
        }
        if ($serverRequestMethod !== $route['requestMethod']) {
          $methodMatch = false;
          continue;
        }

        $controllerClass = $route['controller'];
        $action = $route['action'];

        $controller = new $controllerClass();

        // Call the action method on the controller instance
        if (!method_exists($controller, $action)) {
          http_response_code(500);
          echo json_encode(['message' => 'The controller has no such method']);
          return;
        }

        // check csrf token
        $csrfAllowedMethods = ['POST', 'PATCH', 'PUT', 'DELETE'];
        if ($this->csrfProtection && in_array(strtoupper($serverRequestMethod), $csrfAllowedMethods)) {
          if (
            isset($_POST['_csrf_token']) &&
            Session::has('_csrf_token') &&
            $_POST['_csrf_token'] === Session::get('_csrf_token')
          ) {
            // pass
          } else {
            http_response_code(419);
            echo json_encode(['message' => 'Page expired']);
            return;
          }
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

        // everything is fine, call the action method
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
    echo json_encode(['message' => 'The requested resource is not found']);
    return;
  }

  // create a destructor to run the route method
  public function __destruct() {
    $this->route();
  }
}
