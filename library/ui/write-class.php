<?php

namespace ui;

use DateTime;

class write
{
    static function calc_elapsed_time(int $now, int $past): string
    {
        $diff = $now - $past;
        $times = [
            "m" => (60),
            "h" => (60 * 60),
            "d" => (60 * 60 * 24),
            "M" => (60 * 60 * 24 * 30),
            "y" => (60 * 60 * 24) * 365
        ];

        if ($diff < 0) {
            return "Sonuç negatif olamaz!";
        } elseif ($diff < 60) {
            return $diff . " saniye önce";
        } elseif ($diff / $times["m"] < 60) {
            return floor($diff / $times["m"]) . " dakika önce";
        } else if ($diff / $times["h"] < 24) {
            return floor($diff / $times["h"]) . " saat önce";
        } else if ($diff / $times["d"] < 30) {
            return floor($diff / $times["d"]) . " gün önce";
        } else if ($diff / $times["M"] <= 12) {
            return round($diff / $times["M"]) . " ay önce";
        } else {
            return round($diff / $times["y"]) . " yıl önce";
        }
        exit();
    }

    static function str_to_turkish(string $str, string $case = "UPPER"): string
    {
        $str = trim($str);
        $tr_lc = ["ç", "ğ", "ı", "i", "ö", "ş", "ü"];
        $tr_uc = ["Ç", "Ğ", "I", "İ", "Ö", "Ş", "Ü"];

        $upper = function ($text)  use ($tr_lc, $tr_uc) {
            $text = str_replace($tr_lc, $tr_uc, $text);
            $text = strtoupper($text);
            return $text;
        };

        $lower = function ($text) use ($tr_lc, $tr_uc) {
            $text = str_replace($tr_uc, $tr_lc, $text);
            $text = strtolower($text);
            return $text;
        };

        $upper_first = function ($letter) use ($tr_lc, $tr_uc) {
            $first = mb_substr($letter, 0, 1, "utf-8");
            $outFirst = mb_substr($letter, 1, strlen($letter), "utf-8");
            if (in_array($first, $tr_lc)) {
                $first = str_replace($tr_lc, $tr_uc, $first);
            } else {
                $first = strtoupper($first);
            }
            return $first . $outFirst;
        };

        switch ($case) {
            case "UPPER":
                return $upper($str);
                break;
            case "LOWER":
                return $lower($str);
                break;
            case "UPPER_FIRST":
                $text = $lower($str);
                return $upper_first($text);
                break;
            case "UPPER_FIRSTS":
                $text = $lower($str);
                $letters = explode(" ", $text);
                $result = [];
                foreach ($letters as $l) {
                    array_push($result, $upper_first($l));
                }
                return implode(" ", $result);
                break;
            default:
                echo "2. parametre geçersiz!";
                break;
        }
    }
    static function date_to_turkish($date)
    {
        $engMonth = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        $trMonth = ["Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"];

        $date = new DateTime($date);
        $dateStr = date_format($date, 'd F Y');
        $dateArray = explode(" ", $dateStr);
        $find = array_search($dateArray[1], $engMonth);
        $dateArray[1] = $trMonth[$find];

        return  implode(" ", $dateArray);
    }
}
