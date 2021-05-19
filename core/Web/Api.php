<?php

namespace core\Web;

use core\Bases\Resource;

class Api
{
    private $resource;
    private $uri;
    private $method;
    private $resourceMethod;
    private $resourceParams = [];

    public function __construct(Resource $resource, array $uri)
    {
        $this->resource = $resource;
        $this->uri      = $uri;
        $this->method   = strtolower($_SERVER['REQUEST_METHOD']);

        $this->fetchRoute();
    }

    public function execute()
    {
        call_user_func_array(
            [$this->resource, $this->resourceMethod],
            $this->resourceParams
        );
    }

    private function fetchRoute()
    {
        foreach (Route::fetch($this->method) as $baseRoute => $routes) {
            unset($routes["attributes"]);
            $baseRoute = "api{$baseRoute}";

            foreach ($routes as $path => $attributes) {
                if (substr($path, 0, strlen($baseRoute)) === $baseRoute) {
                    $path = trim(substr($path, strlen($baseRoute)), "/");
                }

                $this->resourceParams = [];
                if ($this->matchPath($path)) {
                    $this->resourceMethod = $attributes["method"];
                    return;
                }
            }
        }

        if (!$this->resourceClass) {
            Response::error();
        }
    }

    private function matchPath(string $path)
    {
        $split = explode("/", $path);

        if (count($this->uri) > count($split)) {
            return false;
        }

        $index = 0;

        foreach ($split as $piece) {
            $optional = ($piece[-2] === "?");
            if ($piece[0] === "{" && $piece[-1] === "}") {
                $param = trim(substr($piece, 1, strlen($piece) - 2), "?");
                $value = $this->uri[$index] ?? null;

                if (!$optional && !$value) {
                    return false;
                }

                if ($value) {
                    $index++;
                }

                $this->resourceParams[$param] = $value;
            } elseif (!$optional && $this->uri[$index] !== $piece) {
                return false;
            } else {
                $index++;
            }
        }

        return true;
    }
}
