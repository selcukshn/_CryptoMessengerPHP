<?php

namespace file;

class file_operation
{

    static function delete_image($dir, $imageName)
    {
        unlink($dir . "/" . $imageName);
    }
}
