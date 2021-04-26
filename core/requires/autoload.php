<?php

spl_autoload_register(function ($class) {
    $file = PATH_BASE . str_replace("\\", DS, $class) . ".php";
    if (!file_exists($file)) {
        throw new \Exception("Class {$class} not found!");
    }
    require_once $file;
});
