<?php

namespace Katuscak\Bridge;

use Katuscak\Component\Configuration;

final class AuthorizationConfiguration
{

    private $authorization_configuration;

    public function __construct(Configuration $configuration)
    {
        $authorization_configuration = $configuration->get("security");

        if (empty($authorization_configuration)) {
            throw new \Exception("Bridge configuration: 'security' is missing in configuration.");
        }

        foreach (["login_route", "roles", "database", "password"] as $must_have) {
            if (!isset($authorization_configuration[$must_have])) {
                throw new \Exception("Bridge authorizatio configuration: '$must_have' is missing in configuration.");
            }
        }

        $this->authorization_configuration = $authorization_configuration;
    }

    public function get($key)
    {
        return $this->authorization_configuration[$key];
    }
}