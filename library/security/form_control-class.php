<?php

namespace security;

class form_control
{
    private const min_char = 4;
    private const min_pass = 6;
    private const max_char = 40;

    private const alphabetic = "/[a-zA-ZçÇğĞıİöÖşŞüÜ]/u";
    private const non_alphabetic = "/[^a-zA-ZçÇğĞıİöÖşŞüÜ]/u";
    private const alphabetic_space = "/[a-zA-ZçÇğĞıİöÖşŞüÜ ]/u";
    private const non_alphabetic_space = "/[^a-zA-ZçÇğĞıİöÖşŞüÜ ]/u";

    private const long_text = "/[<>$#!'\"\*\/=]/u";

    private const non_username = "/[^A-Za-z0-9_]/u";
    private const non_password = "/[^A-Za-z0-9-_,\.!?+#%&]/u";

    private const numeric = "/[0-9]/";
    private const non_numeric = "/[^0-9]/";
    private const numeric_space = "/[0-9 ]/";
    private const non_numeric_space = "/[^0-9 ]/";

    private const alphanumeric = "/[a-zA-Z0-9çÇğĞıİöÖşŞüÜ_]/u";
    private const non_alphanumeric = "/[^a-zA-Z0-9]/u";
    private const alphanumeric_space = "/[a-zA-Z0-9çÇğĞıİöÖşŞüÜ_ ]/u";
    private const non_alphanumeric_space = "/[^a-zA-Z0-9 ]/u";

    private const url = "/[a-zA-ZçÇğĞıİöÖşŞüÜ-]/u";
    private const non_url = "/[^a-zA-ZçÇğĞıİöÖşŞüÜ-]/u";

    private const get = "/[^a-zA-Z0-9_-]/u";

    static function alphabetic($text, bool $space = true, int  $max = self::max_char, int $min = self::min_char)
    {
        $text = self::regular_control($text, $max, $min);
        if ($text[0]) {
            return $text;
        }

        if ($space) {
            $text = self::check_format($text[1], self::non_alphabetic_space);
        } else {
            $text = self::check_format($text[1], self::non_alphabetic);
        }

        if ($text[0]) {
            return $text;
        }
        return $text;
    }
    static function alphanumeric($text, bool $space = true, int  $max = self::max_char, int $min = self::min_char)
    {
        $text = self::regular_control($text, $max, $min);
        if ($text[0]) {
            return $text;
        }

        if ($space) {
            $text = self::check_format($text[1], self::non_alphanumeric_space);
        } else {
            $text = self::check_format($text[1], self::non_alphanumeric);
        }

        if ($text[0]) {
            return $text;
        }
        return $text;
    }
    static function request_get($text, int  $max = self::max_char, int $min = 1)
    {
        $text = self::regular_control($text, $max, $min);
        if ($text[0]) {
            return $text;
        }

        $text = self::check_format($text[1], self::get);
        if ($text[0]) {
            return $text;
        }
        return $text;
    }

    static function long_text($text, int $max, int $min = self::min_char)
    {
        $text = self::regular_control($text, $max, $min);
        if ($text[0]) {
            return $text;
        }

        $text = self::check_format($text[1], self::long_text);

        if ($text[0]) {
            return $text;
        }
        return $text;
    }
    static function numeric($num, $space = true, int $max = self::max_char, int $min = 1)
    {
        $num = self::regular_control($num, $max, $min);
        if ($num[0]) {
            return $num;
        }

        if ($space) {
            $num = self::check_format($num[1], self::non_numeric_space);
        } else {
            $num = self::check_format($num[1], self::non_numeric);
        }

        if ($num[0]) {
            return $num;
        }
        return $num;
    }

    static function username($username, int $max = self::max_char, int $min = self::min_char)
    {
        $username = self::regular_control($username, $max, $min);
        if ($username[0]) {
            return $username;
        }

        $username = self::check_format($username[1], self::non_username);
        if ($username[0]) {
            return $username;
        }

        return $username;
    }
    static function password($pass, $passre = null)
    {
        if (!is_null($passre)) {
            if ($pass != $passre) {
                return [true, "birbiriyle uyuşmuyor"];
            }
        }

        $pass = self::regular_control($pass, 40, self::min_pass);
        if ($pass[0]) {
            return $pass;
        }
        $pass = self::check_format($pass[1], self::non_password);
        if ($pass[0]) {
            return $pass;
        }

        $pass[1] = hash::sha256($pass[1]);
        return $pass;
    }
    static function email($email)
    {
        $email = self::regular_control($email, self::max_char, self::min_char);
        if ($email[0]) {
            return $email;
        }

        if (filter_var($email[1], FILTER_VALIDATE_EMAIL) == false) {
            return [true, "geçersiz"];
        }

        return $email;
    }

    static function url($url, int $max = self::max_char, int $min = self::min_char)
    {
        $url = self::regular_control($url, $max, $min);
        if ($url[0]) {
            return $url;
        }

        $url = self::check_format($url[1], self::non_url);

        if ($url[0]) {
            return $url;
        }
        return $url;
    }

    static function editor($content, int $max, int $min = self::min_char)
    {
        $content = self::regular_control($content, $max, $min);
        if ($content[0]) {
            return $content;
        }
    }

    static function check_box($value)
    {
        if ($value == "on") {
            return [false, true];
        } elseif (is_null($value)) {
            return [false, false];
        } else {
            return [true, "değeri geçersiz"];
        }
    }

    static function add_image($file, $dir, $name)
    {
        if (empty($file["name"])) {
            return [true, "boş olamaz"];
        }

        $tempPath = $file["tmp_name"];
        $fileExtension = pathinfo($file["name"], PATHINFO_EXTENSION);
        $fileName = $name . "-" . rand(100, 999) . "." . $fileExtension;
        $savePath = "$dir/" . $fileName;

        function checkExtension($extension)
        {
            $allowed = ["jpg", "jpeg", "png"];
            if (in_array(strtolower($extension), $allowed)) {
                return false;
            } else {
                return true;
            }
        }


        if ($file["size"] > 1000000) {
            return [true, "dosya boyutu 1 Mb 'tan küçük olmak zorunda"];
        } elseif (file_exists(false)) {
            return [true, "Bu dosya zaten mevcut"];
        } else if (checkExtension($fileExtension)) {
            return [true, "sadece jpg, jpeg ve png uzantılı olabilir"];
        } else if (move_uploaded_file($tempPath, $savePath)) {
            return [false, $fileName];
        } else {
            return [true, "dosya yüklenirken bilinmeyen bir hata oluştu"];
        }
    }

    private static function regular_control($var, int $max, int $min)
    {
        $var = trim($var);
        if (empty($var) or is_null($var)) {
            return [true, "boş olamaz"];
        }
        if (mb_strlen($var, "utf-8") < $min or mb_strlen($var, "utf-8") > $max) {
            return [true, "izin verilen karakter uzunluğunun altında veya aşıyor"];
        }
        return [false, $var];
    }

    private static function check_format(string $var, $pattern)
    {
        $chars = [];
        for ($i = 0; $i < mb_strlen($var, "utf-8"); $i++) {
            $first = mb_substr($var, $i, 1, "utf-8");
            array_push($chars, $first);
        }
        foreach ($chars as $char) {
            if (preg_match($pattern, $char)) {
                return [true, "geçersiz karakter içeriyor"];
            }
        }
        return [false, $var];
    }

    private function delete_special(string $var, $pattern)
    {
        $chars = [];
        for ($i = 0; $i < mb_strlen($var, "utf-8"); $i++) {
            $first = mb_substr($var, $i, 1, "utf-8");
            array_push($chars, $first);
        }

        foreach ($chars as $key => $char) {
            if (preg_match($pattern, $char)) {
                $chars[$key] = "";
            }
        }
        return implode($chars);
    }
}
