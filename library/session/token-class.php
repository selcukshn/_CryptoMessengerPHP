<?php


namespace session;

class token extends session
{
    static function create_token($name)
    {
        return parent::create_session($name, hash("sha256", rand(100000, 999999)));
    }

    static function token_control($name, $token)
    {
        if (parent::have_session($name) and $token == parent::get_session($name)) {
            return true;
        }
        return false;
    }
}
