<?php

namespace Katuscak\Component\Routing;

final class Route
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $rule;

    /**
     * @var array
     */
    private $route;

    public function __construct(string $name, string $rule, array $route)
    {
        $this->name = $name;
        $this->rule = $rule;
        $this->route = $route;
    }

    public function isRule(string $rule): bool
    {
        return $this->rule == $rule;
    }

    public function getUrl(...$args) {
        return $this->rule;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRoute(): array
    {
        return $this->route;
    }
}