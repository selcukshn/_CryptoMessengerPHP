<?php

namespace ui;

class json
{
    static function json_to_array($json, $isFile = false)
    {
        if ($isFile) {
            $decode = json_decode(file_get_contents($json), true);
        } else {
            $decode = json_decode($json, true);
        }
        return $decode;
    }

    static function json_to_list($json, bool $isFile = false)
    {
        function jsonarray_to_list($decode)
        {
            foreach ($decode as $k => $v) {
                if (is_array($v)) {
                    echo "<ul class='py-3'>";
                    echo "<h6>$k</h6>";
                    jsonarray_to_list($v);
                    echo "</ul>";
                } else {
                    echo "<li>$k - $v</li>";
                }
            }
        }

        $decode = self::json_to_array($json, $isFile);
        jsonarray_to_list($decode);
    }
}
