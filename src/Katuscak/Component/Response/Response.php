<?php

namespace Katuscak\Component\Response;

final class Response implements ResponseInterface
{
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function render()
    {
        header("Content-type:text/html;charset=utf-8");
        echo $this->text;
    }
}