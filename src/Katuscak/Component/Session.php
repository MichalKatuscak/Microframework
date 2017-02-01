<?php

namespace Katuscak\Component;

final class Session
{

    public function __construct()
    {
        session_start();
    }

    public function set(string $name, $value)
    {
        $_SESSION["katuscak-" . $name] = $value;
    }

    public function get(string $name)
    {
        if (!empty($_SESSION["katuscak-" . $name])) {
            return $_SESSION["katuscak-" . $name];
        }
        return false;
    }

}