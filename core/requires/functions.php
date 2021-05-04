<?php

/**
 * Simple dump and die function.
 *
 * @param mixed ...$params Anything
 */
function dd(...$params)
{
    dump(...$params);
    die;
}

/**
 * Simple dump function.
 *
 * @param mixed ...$params Anything
 */
function dump(...$params)
{
    if (!CLI) {
        echo "<pre>";
    }
    var_dump(...$params);
}

/**
 * Get config from config.json file
 *
 * @param string|array $path Use "foo", "foo.bar" or ["foo", "bar"]
 * @param mixed [$default=null] Default value if attribute is not found
 * @return mixed Config value from config.json
 */
function config($path, $default = null)
{
    $configs = jsonLoad(PATH_BASE . "config.json");
    $path    = is_array($path) ? $path : explode(".", $path);

    foreach ($path as $attr) {
        if (!isset($configs->{$attr})) {
            return $default;
        }
        $configs = $configs->{$attr};
    }

    return $configs;
}

/**
 * Check if environment is in debug mode.
 *
 * @return bool Debug status
 */
function debug(): bool
{
    return config("debug", false);
}

/**
 * Load a json file and decode it.
 *
 * @param string $path File path
 * @return stdClass
 */
function jsonLoad(string $path): stdClass
{
    return json_decode(file_get_contents($path));
}

/**
 * Save object to json file.
 *
 * @param string $path Path to json file
 * @param stdClass $data Data to save
 * @return bool Success on save
 */
function jsonSave(string $path, stdClass $data): bool
{
    return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
}

/**
 * Merge two object into new one.
 *
 * @param stdClass $obj1 First object
 * @param stdClass $obj2 Second object
 * @return stdClass New merged object
 */
function jsonMerge(stdClass $obj1, stdClass $obj2): stdClass
{
    $obj1 = json_decode(json_encode($obj1), true);
    $obj2 = json_decode(json_encode($obj2), true);
    return (object) arrayMergeRecursiveDistinct($obj1, $obj2);
}

/**
 * Merge two arrays recursively on distinct indexes.
 *
 * @param array $array1 First array
 * @param array $array2 Second array
 * @return array New merged array
 */
function arrayMergeRecursiveDistinct(array &$array1, array &$array2): array
{
    $merged = $array1;

    foreach ($array2 as $key => &$value) {
        if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
            $merged[$key] = arrayMergeRecursiveDistinct($merged[$key], $value);
        } else {
            $merged[$key] = $merged[$key] ?? $value;
        }
    }

    return $merged;
}
