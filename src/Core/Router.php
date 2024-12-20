<?php

namespace Paw\Core;

use Paw\Core\Exceptions\RouteNotFoundException;
use Exception;
use Paw\Core\Request;
use Paw\Core\Traits\Loggeable;
use Twig\Environment;

class Router
{
    use Loggeable;

    public string $notFound = "/not_found";
    public string $internalError = "/internal_error";
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->get($this->notFound, 'ErrorController@notFound');
        $this->get($this->internalError, 'ErrorController@internalError');
    }

    public array $routes = [
        "GET"  => [],
        "POST" => [],
    ];

    public function loadRoutes($path, $action, $method = "GET")
    {
        $this->routes[$method][$path] = $action;
    }

    public function get($path, $action)
    {
        $this->loadRoutes($path, $action, "GET");
    }

    public function post($path, $action)
    {
        $this->loadRoutes($path, $action, "POST");
    }

    public function exists($path, $http_method)
    {
        return array_key_exists($path, $this->routes[$http_method]);
    }

    public function getController($path, $http_method)
    {
        if (!$this->exists($path, $http_method)) {
            throw new RouteNotFoundException("No existe ruta para ese Path");
        }
        return explode('@', $this->routes[$http_method][$path]);
    }

    public function call(string $controller, string $method, Request $request)
    {
        $controller_name = "Paw\\App\\Controllers\\" . $controller;
        $objController = new $controller_name($this->twig);
        $objController->setLogger($this->logger);
        $objController->$method($request);
    }

    public function direct(Request $request)
    {
        try {
            list($path, $http_method) = $request->route();
            list($controller, $method) = $this->getController($path, $http_method);
        } catch (RouteNotFoundException $e) {
            list($controller, $method) = $this->getController($this->notFound, "GET");
        } catch (Exception $e) {
            list($controller, $method) = $this->getController($this->internalError, "GET");
            $this->logger->error("Status Code:500 - Internal Server Error", ["Error" => $e]);
        } finally {
            $this->call($controller, $method, $request);
        }
    }
}