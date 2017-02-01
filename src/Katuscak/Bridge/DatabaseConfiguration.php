<?php

namespace Katuscak\Bridge;

use Katuscak\Component\Configuration;

final class DatabaseConfiguration
{

    private $database_configuration;

    public function __construct(Configuration $configuration)
    {
        $database_configuration = $configuration->get("db");

        if (empty($database_configuration)) {
            throw new \Exception("Bridge configuration: 'db' is missing in configuration.");
        }

        foreach (["server", "user", "password", "database"] as $must_have) {
            if (!isset($database_configuration[$must_have])) {
                throw new \Exception("Bridge database configuration: '$must_have' is missing in configuration.");
            }
        }

        $this->database_configuration = $database_configuration;
    }

    public function get($key)
    {
        return $this->database_configuration[$key];
    }
}