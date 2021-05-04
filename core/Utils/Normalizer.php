<?php

namespace core\Utils;

/**
 * Class to normalize some common data.
 */
class Normalizer
{

    /**
     * @var string Pattern to split class/method name
     */
    const PREG_SPLIT = "/[^a-zA-Z0-9]/";

    /**
     * Normalize class name.
     *
     * @param string $name Raw class name
     * @return string Normalized class name
     */
    public static function className(string $name): string
    {
        $pieces = preg_split(Normalizer::PREG_SPLIT, $name);
        array_walk($pieces, function (&$item) {
            $item = ucfirst($item);
        });
        return implode("", $pieces);
    }

    /**
     * Normalize method name.
     *
     * @param string $name Raw method name
     * @return string Normalized method name
     */
    public static function methodName(string $name): string
    {
        $pieces = preg_split(Normalizer::PREG_SPLIT, $name);
        $first  = strtolower(array_shift($pieces));
        array_walk($pieces, function (&$item) {
            $item = ucfirst(strtolower($item));
        });
        return $first . implode("", $pieces);
    }

    /**
     * Get machine name from class/method/...
     *
     * @param string $raw Raw string
     * @return string Machine name
     */
    public static function toMachineName(string $raw): string
    {
        return trim(strtolower(preg_replace("/([A-Z])/", "-$1", $raw)), "-");
    }
}
