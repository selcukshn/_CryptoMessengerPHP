<?php

namespace user;

class login
{
    private const REGEX = "/[^a-zA-Z0-9_]/";
    private const MIN = 4;
    private const MAX = 20;

    static function login_control(string $u, string $p)
    {
        if (empty($u) or empty($p)) {
            return false;
        } else {
            $data = self::secure($u, $p);
            if (preg_match(self::REGEX, $data[0])) {
                return false;
            } elseif (strlen($data[0]) < self::MIN or strlen($data[0]) > self::MAX) {
                return false;
            } else {
                return true;
            }
        }
    }

    private function secure($u, $p)
    {
        $data = [];
        array_push($data, trim($u), trim($p));
        return $data;
    }
}
