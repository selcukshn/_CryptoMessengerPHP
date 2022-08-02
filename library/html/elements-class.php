<?php

namespace html;

class elements
{
    private function combine_properties(array $params, array $values)
    {
        $array = array_combine($params, $values);
        $properties = "";
        foreach ($array as $key => $value) {
            $properties .= " $key='$value' ";
        }
        return $properties;
    }

    static function input(array $params, array $values)
    {
        $properties = self::combine_properties($params, $values);
        echo "<input $properties >";
    }

    static function button(array $params, array $values, string $text)
    {
        $properties = self::combine_properties($params, $values);
        echo "<button $properties >$text</button>";
    }

    static function label(array $params, array $values, string $text)
    {
        $properties = self::combine_properties($params, $values);
        echo " <label $properties>$text</label>";
    }
}
