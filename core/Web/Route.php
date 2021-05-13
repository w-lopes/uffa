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
     * @return array Parsed routes
     */
    public static function fetch(): array
    {
        return self::iterateResource();
    }

    /**
     * Iterate from all resources to fetch its route.
     *
     * @return array Parsed routes
     */
    private static function iterateResource()
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
            $parsed     = self::getMethodsRoute($base, $reflection)
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
     * @return array Fetched recource method routes
     */
    private static function getMethodsRoute(string $base, ReflectionClass $reflection): array
    {
        $routes = [];

        foreach ($reflection->getMethods() as $method) {
            if (empty($method->getAttributes())) {
                continue;
            }

            $attributes = $method->getAttributes();
            foreach ($attributes as $attribute) {
                $arguments      = $attribute->getArguments();
                $route          = "api{$base}{$arguments["uri"]}";
                $routes[$route] = [
                    "http"   => $arguments["method"],
                    "class"  => $reflection->getName(),
                    "method" => $method->getName()
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
