<?php

namespace session;

class session
{
    public static function create_session($name, $value)
    {
        return $_SESSION[$name] = $value;
    }

    public static function have_session($name)
    {
        return isset($_SESSION[$name]) ? true : false;
    }

    public static function get_session($name)
    {
        if (self::have_session($name)) {
            return $_SESSION[$name];
        }
        return false;
    }

    public static function delete_session($name)
    {
        if (self::have_session($name)) {
            unset($_SESSION[$name]);
        }
    }

    public static function delete_all_session()
    {
        session_destroy();
    }
}
