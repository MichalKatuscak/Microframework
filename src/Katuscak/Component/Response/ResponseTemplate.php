<?php

namespace Katuscak\Component\Response;

use Katuscak\Component\Configuration;
use Katuscak\Kernel\Factory;

final class ResponseTemplate implements ResponseInterface
{

    /**
     * @var string
     */
    private $template;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $dir;

    public function __construct(string $template, array $data)
    {
        $configuration = Factory::load(Configuration::class);
        if (empty($configuration->get("template"))) {
            new \Exception("Configuration 'template' not found.");
        }

        $this->template = $template;
        $this->data = $data;
        $this->dir = $configuration->get("template")["dir"];
    }

    public function render()
    {
        header("Content-type:text/html;charset=utf-8");

        foreach ($this->data as $key => $value) {
            $$key = $value;
        }

        include_once KATUSCAK_DIR . "/" . $this->dir . "/" . $this->template;
    }
}