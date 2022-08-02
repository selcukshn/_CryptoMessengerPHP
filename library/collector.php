<?php
//* aynı dizinde bulunan namespace olmayan classları toplar
/*
spl_autoload_register(function ($class) {
    $path = __DIR__ . "/" . $class . "-class.php";
    if (file_exists($path)) {
        require_once $path;
    }
});
*/

//* farklı dizinlerdeki classları toplar
spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . "/";
    $path = $baseDir . str_replace("\\", "/", $class . "-class.php");
    if (file_exists($path)) {
        require_once $path;
    }
});
