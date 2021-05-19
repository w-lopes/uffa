<?php

namespace core\Web;

use ReflectionClass;

/**
 * Handle routes to Doc or Api call.
 */
class Route
{

    /**
     * Fetch all routes from resources.
     *
     * @param string $method HTTP method to filter by
     * @return array Parsed routes
     */
    public static function fetch(string $method = null): array
    {
        return self::iterateResource($method);
    }

    /**
     * Iterate from all resources to fetch its route.
     *
     * @param string $method HTTP method to filter by
     * @return array Parsed routes
     */
    private static function iterateResource(string $method = null)
    {
        $result    = [];
        $namespace = "\\custom\\Resources\\";

        if (!is_dir(PATH_CUSTOM_RESOURCES)) {
            Response::error("Resources not found");
        }

        foreach (new \DirectoryIterator(PATH_CUSTOM_RESOURCES) as $class) {
            if (!$class->isFile()) {
                continue;
            }

            $current    = $namespace . str_replace(".php", "", $class->getFilename());
            $reflection = new ReflectionClass($current);
            $base       = self::getBaseRoute($reflection);
            $parsed     = self::getMethodsRoute($base, $reflection, $method)
                + ["attributes" => self::getApiProperties($reflection)];

            $result[$base] = $parsed;
        }

        return $result;
    }

    /**
     * Get base resource's base path.
     *
     * @param ReflectionClass $reflection Reflection of resource class
     * @return string Base path
     */
    private static function getBaseRoute(ReflectionClass $reflection): string
    {
        $attributes = $reflection->getAttributes();
        foreach ($attributes as $attribute) {
            if ($attribute->getName() === Route::class) {
                return $attribute->getArguments()["base"] ?? "/";
            }
        }
    }

    /**
     * Get route from each resource method.
     *
     * @param string $base Resource base path
     * @param ReflectionClass $reflection Reflection of resource
     * @param string $method HTTP method to filter by
     * @return array Fetched recource method routes
     */
    private static function getMethodsRoute(string $base, ReflectionClass $reflection, string $method = null): array
    {
        $routes = [];

        foreach ($reflection->getMethods() as $resouceMethod) {
            if (empty($resouceMethod->getAttributes())) {
                continue;
            }

            $attributes = $resouceMethod->getAttributes();
            foreach ($attributes as $attribute) {
                $arguments  = $attribute->getArguments();

                if ($method && !in_array($method, $arguments["method"])) {
                    continue;
                }

                $route          = "api{$base}{$arguments["uri"]}";
                $routes[$route] = [
                    "http"   => $arguments["method"],
                    "class"  => $reflection->getName(),
                    "method" => $resouceMethod->getName()
                ];
            }
        }

        return $routes;
    }

    /**
     * Fetch attrbiutes from resource API.
     *
     * @param ReflectionClass $reflection Resouce reflection
     * @return string[]
     */
    private static function getApiProperties(ReflectionClass $reflection): array
    {
        $ret   = [];
        $props = $reflection->getProperties();

        foreach ($props as $prop) {
            $attributes = $prop->getAttributes();
            foreach ($attributes as $attribute) {
                $arguments = $attribute->getArguments();
                $ret[$prop->getName()] = $arguments;
            }
        }
        return $ret;
    }
}
