<?php

namespace Bootstrap\Routes;

use \Bootstrap\App\Server;
use \Bootstrap\App\Request;

class Route
{
    private static array $routes = array('GET' => array(), 'POST' => array(), 'PUT' => array(), 'DELETE' => array());
    private static string $method = "";
    private static string $uri = "";

    public static function run()
    {
        require_once __DIR_APP__ . "routes/web.php";
        self::$method = Server::getMethod();
        self::$uri = Server::getUri();

        if (!isset(self::$method)) {
            return false;
        }

        if (!isset(self::$routes[self::$method][self::$uri])) {
            return false;
        }

        if (isset(self::$routes[self::$method][self::$uri])) {
            return self::execute(self::$routes[self::$method][self::$uri]);
        }
    }

    public static function execute(array|callable $action)
    {
        if (is_callable($action)) {
            return call_user_func($action);
        }

        if (is_array($action)) {
            $controller = $action[0];
            $method = $action[1];

            if (!class_exists($controller)) {
                return exit("Controller not found!");
            }
            if (!method_exists($controller, $method)) {
                return exit("Method not found!");
            }

            $request = new Request;
            $controller = new $controller;
            return $controller->$method($request);
        }
    }

    public static function get(string $route, array|callable $action)
    {
        return self::$routes['GET'][$route] = $action;
    }

    public static function post(string $route, array|callable $action)
    {
        return self::$routes['POST'][$route] = $action;
    }

    public static function delete() {}

    public static function put() {}

    public static function group() {}

    public static function name() {}
}
