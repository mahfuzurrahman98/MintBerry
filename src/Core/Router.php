<?php

namespace Scandiweb\Core;

class Router {
  protected $routes = [];

  public function get($path, $controller, $action) {
    $this->routes[] = [
      'path' => $path,
      'controller' => $controller,
      'action' => $action,
      'methods' => ['GET'],
    ];
  }

  public function post($path, $controller, $action) {
    $this->routes[] = [
      'path' => $path,
      'controller' => $controller,
      'action' => $action,
      'methods' => ['POST'],
    ];
  }

  public function put($path, $controller, $action) {
    $this->routes[] = [
      'path' => $path,
      'controller' => $controller,
      'action' => $action,
      'methods' => ['PUT'],
    ];
  }

  public function delete($path, $controller, $action) {
    $this->routes[] = [
      'path' => $path,
      'controller' => $controller,
      'action' => $action,
      'methods' => ['DELETE'],
    ];
  }



  public function route() {
    $uri = parse_url($_SERVER['REQUEST_URI']);

    foreach ($this->routes as $route) {
      if ($route['path'] === $uri['path'] && in_array($_SERVER['REQUEST_METHOD'], $route['methods'])) {
        $controller = new $route['controller'];
        $controller->{$route['action']}();
      }
    }
  }
}
