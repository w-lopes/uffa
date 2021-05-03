<?php

namespace core\Utils;

use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use stdClass;

/**
 * Class to operate over classes (?).
 */
class Classes
{

    /**
     * Get first line of a class doc string.
     * 
     * @param string $class Class name with namespace
     * @return string First Line of doc string
     */
    public static function getClassDocString(string $class): string
    {
        $reflection = new ReflectionClass($class);
        $lines      = explode(PHP_EOL, $reflection->getDocComment());
        return str_replace("* ", "", trim($lines[1] ?? ""));
    }

    /**
     * Get an array of public non magic methods of a class.
     * 
     * @param $string $class Class name with namespace
     * @return string[] All public method
     */
    public static function getPublicMethods(string $class): array
    {
        $methods    = [];
        $reflection = new ReflectionClass($class);

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->class === $reflection->getName() && substr($method->name, 0, 2) !== "__") {
                $methods[] = $method->name;
            }
        }

        return $methods;
    }

    /**
     * Get first line of a method doc string.
     * 
     * @param string $class Class name with namespace
     * @param string $method Method name
     * @return string First line of doc string
     */
    public static function getMethodDocString(string $class, string $method): string
    {
        $reflection = new ReflectionClass($class);
        $lines      = explode(PHP_EOL, $reflection->getMethod($method)->getDocComment());
        return str_replace("* ", "", trim($lines[1] ?? ""));
    }

    /**
     * Get all parameters with its documentation and type.
     * 
     * @param string $class Class name with namespace
     * @param string $method Method name
     * @return stdClass[] Array of parameters object
     */
    public static function getMethodParameters(string $class, string $method): array
    {
        $reflection = new ReflectionMethod($class, $method);
        $parameters = $reflection->getParameters();

        foreach ($parameters as &$parameter) {
            $parameter = self::getParameterAttrs($parameter);
        }

        return $parameters;
    }

    /**
     * Parse parameter attributes.
     * 
     * @param ReflectionParameter $parameter Parameter to parse
     * @return stdClass Parameter's attributes
     */
    private static function getParameterAttrs(ReflectionParameter $parameter): stdClass
    {
        $type     = $parameter->getType();
        $optional = $parameter->isOptional();

        assert($type instanceof ReflectionNamedType);

        $attrs = (object) [
            "type"     => $type->getName(),
            "name"     => $parameter->getName(),
            "optional" => $optional,
            "default"  => $optional ? $parameter->getDefaultValue() : null
        ];

        return $attrs;
    }
}