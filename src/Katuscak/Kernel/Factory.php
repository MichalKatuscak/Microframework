<?php

namespace Katuscak\Kernel;

final class Factory
{
    private static $objects = [];

    public static function load($class)
    {
        if (!array_key_exists($class, self::$objects)) {
            $args = self::getArgsByReflection($class);
            self::$objects[$class] = new $class(...$args);
        }
        return self::$objects[$class];
    }

    private static function getArgsByReflection(string $controller_name, string $method_name = "__construct"): array
    {
        $args = [];

        $rc = new \ReflectionClass($controller_name);

        if (!$rc->hasMethod($method_name)) {
            return $args;
        }

        $params = $rc->getMethod($method_name)->getParameters();
        foreach ($params as $param) {
            if ($param->getClass()) {
                $args[] = Factory::load($param->getClass()->name);
            } else {
                throw new \Exception("Factory: Param of $controller_name::$method_name is not a class");
            }
        }

        return $args;
    }
}