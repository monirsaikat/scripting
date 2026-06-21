<?php

namespace Src;

use ErrorException;
use ReflectionClass;
use ReflectionMethod;
use Src\Attributes\Auth;
use Src\Attributes\Guest;
use Src\Attributes\Route;
use Pimple\Container;
use Src\Exceptions\AppException;
use Src\Middleware\CsrfMiddleware;

class Router
{
    private $routes = [];
    private $namedRoutes = [];
    private $baseUrl;
    private $middleware = [];
    private $routeGroups = [];

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->baseUrl = $this->container['config']['app']['base_url'];
    }

    public function registerControllersAutomatically()
    {
        $controllerNamespace = 'Src\\Controllers\\';
        $controllerPath = __DIR__ . '/Controllers/';

        foreach (glob($controllerPath . '*.php') as $file) {
            $className = basename($file, '.php');
            $fullClassName = $controllerNamespace . $className;

            if (class_exists($fullClassName)) {
                $controllerInstance = new $fullClassName($this->container);
                $this->registerRoutesFromAttributes($controllerInstance);
            }
        }
    }

    private function registerRoutesFromAttributes($controller)
    {
        $reflectionClass = new ReflectionClass($controller);

        foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            foreach ($method->getAttributes(Route::class) as $attribute) {
                $routeInstance = $attribute->newInstance();
                $this->add($routeInstance->method, $routeInstance->path, [$controller, $method->getName()], $routeInstance->name);
            }
        }
    }

    private function applyAuthAttribute($callback)
    {
        $reflection = new ReflectionMethod($callback[0], $callback[1]);

        foreach ($reflection->getAttributes(Auth::class) as $attribute) {
            $auth = $attribute->newInstance();
            $auth->handle();
        }
    }

    private function applyGuestAttribute($callback)
    {
        $reflection = new ReflectionMethod($callback[0], $callback[1]);

        foreach ($reflection->getAttributes(Guest::class) as $attribute) {
            $auth = $attribute->newInstance();
            $auth->handle();
        }
    }
    /**
     * Add a route.
     *
     * @param string $method
     * @param string $route
     * @param callable|array $callback
     * @param array $middleware
     */
    public function add($method, $route, $callback, $name = null, $middleware = [])
    {
        if (strtolower($method) == 'post') {
            $middleware[] = [new CsrfMiddleware(), 'handle'];
        }

        if (!empty($this->routeGroups)) {
            $lastGroup = end($this->routeGroups);
            $route = $lastGroup['prefix'] . $route;
            $middleware = array_merge($lastGroup['middleware'], $middleware);
        }

        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'callback' => $callback,
            'middleware' => $middleware,
        ];

        if ($name) {
            $this->namedRoutes[$name] = $route;
        }
    }

    public function route(string $name, array $params = []): string
    {
        if (!isset($this->namedRoutes[$name])) {
            throw new ErrorException("Route name '$name' not found.");
        }

        $route = $this->namedRoutes[$name];

        foreach ($params as $key => $value) {
            $route = str_replace('{' . $key . '}', $value, $route);
        }

        return $this->baseUrl . $route;
    }

    /**
     * Group routes with a common prefix or middleware.
     *
     * @param string $prefix
     * @param array $middleware
     * @param callable $callback
     */
    public function group($prefix, $middleware, $callback)
    {
        $this->routeGroups[] = [
            'prefix' => $prefix,
            'middleware' => $middleware,
        ];

        call_user_func($callback, $this);

        array_pop($this->routeGroups);
    }

    /**
     * Match a route based on method and URI.
     *
     * @param string $method
     * @param string $uri
     * @return array|null
     */
    public function match($method, $uri)
    {
        foreach ($this->routes as $route) {
            $pattern = $this->convertRouteToPattern($route['route']);

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY); // Extract named parameters
                return [
                    'callback' => $route['callback'],
                    'middleware' => $route['middleware'],
                    'params' => $params,
                ];
            }
        }

        return null;
    }

    /**
     * Dispatch the request to the appropriate route.
     *
     * @param string $method
     * @param string $uri
     */
    public function dispatch($method, $uri)
    {
        $uri = str_replace($this->baseUrl, '', $uri);
        $uri = ($uri !== '/' && str_ends_with($uri, '/')) ? rtrim($uri, '/') : $uri;

        $matchedRoute = $this->match($method, $uri);

        if ($matchedRoute) {
            $callback   = $matchedRoute['callback'];
            $middleware = $matchedRoute['middleware'];
            $params     = $matchedRoute['params'];

            // Apply middleware
            $this->applyMiddleware($middleware);

            $this->applyAuthAttribute($callback);

            $this->applyGuestAttribute($callback);

            // Pass params to the callback
            if (is_array($callback) && is_callable($callback)) {
                call_user_func_array($callback, $params);
            } elseif (is_callable($callback)) {
                call_user_func($callback, $params);
            } else {
                throw new ErrorException("Invalid callback for route: $uri");
            }
        } else {
            throw new AppException("Page Not Found", 404);
        }
    }

    /**
     * Convert a route with dynamic parameters to a regex pattern.
     *
     * @param string $route
     * @return string
     */
    private function convertRouteToPattern($route)
    {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route);
        return '#^' . $pattern . '$#';
    }

    /**
     * Apply middleware to the request.
     *
     * @param array $middleware
     */
    private function applyMiddleware($middleware)
    {
        foreach ($middleware as $mw) {
            if (is_callable($mw)) {
                call_user_func($mw);
            } else {
                throw new ErrorException("Invalid middleware provided.");
            }
        }
    }
}
