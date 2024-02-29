<?php

namespace App\Router;

class RouterFacade
{
  protected static $router;

  public static function setBasePath($basePath): void
  {
    self::$router = new Router($_SERVER['REQUEST_URI']);
    self::$router->setBasePath($basePath);
  }

  public static function get($path, $callback, $name = null): Route
  {
    return self::$router->get($path, $callback, $name);
  }

  public static function post($path, $callback, $name = null): Route
  {
    return self::$router->post($path, $callback, $name);
  }

  public static function run(): void
  {
    try {
      self::$router->run();
    } catch (\Exception $e) {
      echo "Error: " . $e->getMessage();
    }
  }
}
