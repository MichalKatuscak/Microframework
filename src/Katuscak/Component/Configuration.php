<?php

namespace Katuscak\Component;

final class Configuration
{
    private $dir;
    private $environment;
    private $config_by_environment;

    public function __construct(Environment $environment)
    {
        $this->dir = KATUSCAK_DIR_CONFIG;
        $this->environment = $environment;

        $this->load();
    }

    public function load()
    {
        $jsons = $this->loadJsonFiles();
        $config = $this->parseJsonFiles($jsons);
        $this->environment->setConfiguration($config);
        $environment = $this->environment->getEnvironment();
        $config_by_environment = $this->parseByEnvironment($config, $environment);
        $this->config_by_environment = $config_by_environment;
    }

    public function get(string $key)
    {
        if (empty($this->config_by_environment[$key])) {
            throw new \Exception("Configuration: '$key' missing in configuration.");
        }

        return $this->config_by_environment[$key];
    }

    private function loadJsonFiles(array $files = []): array
    {
        if ($handle = opendir($this->dir)) {
            while (false !== ($entry = readdir($handle))) {
                $path = explode(".", $entry);
                if (!empty($path[1]) && $path[1] == "json") {
                    $files[$path[0]] = file_get_contents($this->dir . "/" . $entry);
                }
            }
        }
        return $files;
    }

    private function parseJsonFiles(array $jsons = []): array
    {
        foreach ($jsons as $key => $json) {
            $jsons[$key] = json_decode($json, true);
        }

        return $jsons;
    }

    private function parseByEnvironment(array $configuration, string $environment): array
    {
        unset($configuration["env"]);

        foreach ($configuration as $key => $value) {
            if (empty($value["env"])) continue;

            $new_value = [];
            foreach ($value["env"] as $env_name => $env_value) {
                if ($env_name == $environment) {
                    $new_value = $env_value;
                }
            }

            $configuration[$key] = $new_value;
        }

        return $configuration;
    }

}