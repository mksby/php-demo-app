<?php

spl_autoload_register(function ($class_name) {
    $class = implode('\\', array_slice(explode('\\', $class_name), 1));
    $replace = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    include $replace . '.php';
});