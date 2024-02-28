<?php
class Session
{
    /**
     * Start Session
     *
     * @return void
     */
    public static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set Session
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public static function set($key, $value)
    {
        self::startSession();
        $_SESSION[$key] = $value;
    }

    /**
     * Unset Session
     *
     * @param string $key
     * @return void
     */
    public static function unset($key)
    {
        self::startSession();
        unset($_SESSION[$key]);
    }

    public static function get($key)
    {
        self::startSession();
        return $_SESSION[$key];
    }

    public static function isset($key)
    {
        self::startSession();
        return isset($_SESSION[$key]);
    }

}
