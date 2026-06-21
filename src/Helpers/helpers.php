<?php

use Src\Session;
use Src\Util\Timing;


if (!function_exists('router')) {
    function router()
    {
        return app()->getContainer()['router'];
    }
}

if(!function_exists('generateCsrfToken'))
{
    function generateCsrfToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if(!function_exists('timing'))
{
    function timing($time = 'now')
    {
        return new Timing($time);
    }
}

function timezoneToOffset(string $timezoneName): string
{
    $timezone = new \DateTimeZone($timezoneName);
    $datetime = new \DateTime('now', $timezone);
    $offsetInSeconds = $timezone->getOffset($datetime);

    return sprintf('%+03d:%02d', $offsetInSeconds / 3600, abs($offsetInSeconds % 3600) / 60);
}

if (!function_exists('old')) {
    function old($key = null, $default = null)
    {
        $oldValues = Session::getOldValues();

        if ($key) {
            return $oldValues[$key] ?? $default;
        }

        return $oldValues;
    }
}

if (!function_exists('flash')) {
    function flash(string $key, string $message)
    {
        Session::flash($key, $message);
        Session::clearOldValues();
    }
}


if (!function_exists('app')) {
    /**
     * Get the global application instance.
     *
     * @return \Src\Application
     */
    function app()
    {
        global $app;

        if (!$app) {
            $app = new \Src\Application();
        }

        return $app;
    }
}


function auth(string $guard = 'user'): \Src\Auth
{
    return new \Src\Auth($guard);
}

function user(string $guard = 'user')
{
    return auth($guard)->user();
}

function systemInfo()
{
    return 'system';
}

function baseUrl()
{
    $config = require __DIR__ . '/../../config/app.php';

    return $config['base_url'];
}

function url($path)
{
    $url = baseUrl() . '/' . $path;

    return str_replace('//', '/', $url);
}

function asset($path)
{
    $url = baseUrl() . '/';

    $url = str_replace('//', '/', $url) . '/public';

    return $url . $path;
}

function arrayToObject(array $array): stdClass
{
    $object = new stdClass();

    foreach ($array as $key => $value) {
        $key = is_numeric($key) ? (string) $key : $key;

        if (is_array($value)) {
            $object->$key = arrayToObject($value);
        } else {
            $object->$key = $value;
        }
    }

    return $object;
}

function redirect($path)
{
    header('Location: ' . url($path));
    exit;
}
