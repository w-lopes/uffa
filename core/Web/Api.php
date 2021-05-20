<?php

namespace core\Web;

use core\Bases\Resource;

/**
 * Parse an API request.
 */
class Api
{
    /**
     * @var Resource Instance of resource class.
     */
    private $resource;

    /**
     * @var string[] Splitted URI params.
     */
    private $uri;

    /**
     * @var string HTTP Method used.
     */
    private $method;

    /**
     * @var string Resource's method name.
     */
    private $resourceMethod;

    /**
     * @var mixed[] Resource's method parameters.
     */
    private $resourceParams = [];

    /**
     * @var string Default parameter type.
     */
    const DEFAULT_TYPE = "string";

    /**
     * @var string[] Types to do a simple cast.
     */
    const CASTS = [
        "int",
        "array",
        "object",
        "string",
        "bool",
        "float"
    ];

    /**
     * Constructor.
     *
     * @param Resource $resource Instace of resource class
     * @param stirng[] $uri Splitted URI params.
     */
    public function __construct(Resource $resource, array $uri)
    {
        $this->resource = $resource;
        $this->uri      = $uri;
        $this->method   = strtolower($_SERVER['REQUEST_METHOD']);

        $this->fetchRoute();
        $this->castParams();
    }

    /**
     * Execute resource API call.
     */
    public function execute()
    {
        call_user_func_array(
            [$this->resource, $this->resourceMethod],
            $this->resourceParams
        );
    }

    /**
     * Cast URI parameters to resource's method.
     */
    public function castParams()
    {
        $reflection   = new \ReflectionMethod($this->resource, $this->resourceMethod);
        $methodParams = $reflection->getParameters();

        $index = 0;
        foreach ($this->resourceParams as &$value) {
            $type      = "string";
            $paramType = $methodParams[$index]->getType();
            $type      = $paramType ? $paramType->getName() : $type;

            if (in_array($type, self::CASTS)) {
                settype($value, $type);
            } elseif (class_exists($type)) {
                $new = new $type();
                if (!method_exists($new, "find")) {
                    Response::error("Class '{$type}' must have 'find' method to be used as argument!");
                }
                $new->find($value);
                $value = $new;
            }

            $index++;
        }
    }

    /**
     * Check route called if availables on resources.
     */
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

    /**
     * Verify if passed URI match with a available path.
     * Load route params to array.
     *
     * @param string $path Available system path
     * @return bool If is compatible
     */
    private function matchPath(string $path): bool
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
