<?php

namespace Katuscak\Component;

final class Environment
{
    private $enviroment = "dev";

    public function setConfiguration(array $configuration)
    {
        if (empty($configuration["env"])) {
            new \Exception("Enviroment: No loaded from configuration JSON.");
        }

        $hostname = $_SERVER["HTTP_HOST"];

        foreach ($configuration["env"] as $env_name => $hostnames) {
            if (in_array("*", $hostnames) || in_array($hostname, $hostnames)) {
                $this->enviroment = $env_name;
            }
        }

        $this->setEnvironment();
    }

    public function setEnvironment()
    {
        switch ($this->enviroment) {
            case "dev":
                error_reporting(E_ALL);
                ini_set("display_errors", true);
                break;
        }
    }

    public function getEnvironment():string
    {
        return $this->enviroment;
    }
}