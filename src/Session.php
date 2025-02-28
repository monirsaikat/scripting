<?php

namespace Src;

class Session
{
    /**
     * Start the session if not already started
     */
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set the old form values into the session
     *
     * @param array $values
     */
    public static function setOldValues($values)
    {
        self::start();
        $_SESSION['_old_values'] = $values;
    }

    /**
     * Get the old values from the session
     *
     * @return array
     */
    public static function getOldValues()
    {
        self::start();
        return $_SESSION['_old_values'] ?? [];
    }

    public static function clearOldValues()
    {
        self::start();
        unset($_SESSION['_old_values']);
    }

    /**
     * Set a flash message
     *
     * @param string $key
     * @param string $message
     */
    public static function flash($key, $message)
    {
        self::start();
        $_SESSION['flash'][$key] = $message;
    }

    /**
     * Get a flash message (only available for the next request)
     *
     * @param string $key
     * @return string|null
     */
    public static function getFlash($key)
    {
        self::start();
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
}
