<?php

namespace Src;

use Pimple\Container;
use Src\Router;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Src\Exceptions\ErrorHandler;

class Application
{
    private $container;

    public function __construct()
    {
        $this->container = new Container();
        $this->registerServices();
        $this->registerProviders();
        set_exception_handler([ErrorHandler::class, 'handleException']); // Register global exception handler
    }

    private function registerProviders()
    {
        foreach (glob(__DIR__ . '/Providers/*.php') as $file) {
            $class = 'Src\\Providers\\' . basename($file, '.php');

            if (class_exists($class) && $class !== 'Src\Providers\ServiceProvider') {
                $provider = new $class();
                $provider->register($this->container);
            }
        }
    }

    private function registerServices()
    {
        $this->container['db'] = function () {
            return Database::getInstance();
        };

        $this->container['request'] = function () {
            return new Request();
        };

        $this->container['response'] = function () {
            return new Response();
        };

        $this->container['logger'] = function () {
            $log = new Logger('request_logger');
            $log->pushHandler(new StreamHandler(__DIR__ . '/../logs/request_time.log', Logger::INFO));
            return $log;
        };

        $this->container['router'] = function () {
            $router = new Router($this->container);
            $router->registerControllersAutomatically();
            return $router;
        };

        $this->container['config'] = function () {
            $configPath = __DIR__ . '/../config/';
            $config = [];

            foreach (glob($configPath . '*.php') as $file) {
                $key = basename($file, '.php');
                $config[$key] = require $file;
            }

            return $config;
        };
    }

    public function getConfig($key = null)
    {
        $config = $this->container['config'];

        return $key ? ($config[$key] ?? []) : $config;
    }

    public function run()
    {
        $startTime = microtime(true);

        $router = $this->container['router'];
        $config = $this->container['config'];
        $log    = $this->container['logger'];

        $baseUrl = $config['app']['base_url'] ?? '/';
        $method  = $_SERVER['REQUEST_METHOD'];
        $uri     = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $router->dispatch($method, $uri);

        $endTime     = microtime(true);
        $requestTime = ($endTime - $startTime) * 1000;

        $awesome = str_replace($baseUrl, '', $uri);
        $log->info(strtoupper($method) . ": {$awesome} took " . round($requestTime, 2) . " ms");
    }

    public function getContainer()
    {
        return $this->container;
    }
}
