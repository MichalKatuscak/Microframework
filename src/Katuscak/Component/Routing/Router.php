<?php

namespace Katuscak\Component\Routing;

use Katuscak\Component\Annotation;
use Katuscak\Component\Request;
use Katuscak\Component\Response\ResponseInterface;
use Katuscak\Kernel\Factory;

final class Router
{
    private $routes = [];
    private $actual_route = "/";

    /**
     * @var Annotation
     */
    private $annotation;

    public function __construct(Annotation $annotation, Request $request)
    {
        $route = $request->get("route");
        $this->actual_route = "/" . trim($route, "/");
        $this->annotation = $annotation;
    }

    public function addRoute(Route ...$routes)
    {
        $this->routes = array_merge($this->routes, $routes);
    }

    public function redirectToRoute($route_name, array ...$args)
    {
        foreach ($this->routes as $route) {
            if ($route->getName() == $route_name) {
                header("location: " . str_replace("/index.php", "", $_SERVER["PHP_SELF"]) . $route->getUrl(...$args));
            }
        }
    }

    public function __destruct()
    {
        /** @var Route $route */
        $run_route = false;
        foreach ($this->routes as $route) {
            if ($route->isRule($this->actual_route)) {
                $run_route = $route->getRoute();
            }
        }

        if (is_array($run_route) && count($run_route) == 2) {
            $this->render($run_route);
        }
    }

    private function render(array $run_route)
    {
        $controller_name = $run_route[0];
        $action_name = $run_route[1];

        $this->checkAnnotation($controller_name, $action_name);

        $args = $this->getArgsByReflection($controller_name, $action_name);

        $controller = Factory::load($controller_name);
        $response = $controller->{$action_name}(...$args);

        if (in_array(ResponseInterface::class, class_implements($response))) {
            $response->render();
        }
    }

    private function checkAnnotation(string $controller_name, $action_name)
    {
        $rc = new \ReflectionClass($controller_name);
        $doc = $rc->getDocComment() . $rc->getMethod($action_name)->getDocComment();
        preg_match_all('#@(.*?)\n#s', $doc, $annotations);
        $this->annotation->applyAnnotations($annotations[1]);
    }

    private function getArgsByReflection($controller_name, $action_name): array
    {
        $args = [];

        $rc = new \ReflectionClass($controller_name);
        $params = $rc->getMethod($action_name)->getParameters();
        foreach ($params as $param) {
            if ($param->getClass()) {
                $args[] = Factory::load($param->getClass()->name);
            } else {
                throw new \Exception("Router: Param of $controller_name::$action_name is not class");
            }
        }

        return $args;
    }
}