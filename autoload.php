<?php

spl_autoload_register(function ($class_name) {
    $file_name = __DIR__ . "/src/" . str_replace("\\","/",$class_name) . '.php';

    if (file_exists($file_name)) {
        include_once $file_name;
    }
});

