<?php

namespace security;

class hash
{
    private const CIPHER = "aes-128-cbc";
    private const KEY = "dU%0SuuTFQP#JrVa";

    static function sha256($var)
    {
        return hash("sha256", hash("sha256", $var));
    }
    //! encrypt ile şifrelenen metinlerdeki '+' ifadesi url'de ' ' (boşluk) olarak görüntülenir str_replace() ile ' '(boşluk) geçen yerleri '+' ifadesi ile değiştirmek gerekir
    /*
    Örnek
    encrypt ile oluşturulmuş ifade : YM4R0YSfys+VWZraM+153A
    urlde görüntülenme şekli       : YM4R0YSfys VWZraM 153A
    */
    static function encrypt($value)
    {
        return @openssl_encrypt($value, self::CIPHER, self::KEY);
    }
    static function decrypt($value)
    {
        return openssl_decrypt($value, self::CIPHER, self::KEY);
    }
}
