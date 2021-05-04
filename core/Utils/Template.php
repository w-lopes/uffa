<?php

namespace core\Utils;

/**
 * Handle framework's templates.
 */
class Template
{

    /**
     * Load a template and replace its variables.
     *
     * @param string $tempate Template's fle name
     * @param string[] $data Associative array with its variables
     * @return string Parsed template
     */
    public static function parse(string $template, array $data = [])
    {
        $content = file_get_contents(PATH_TEMPLATES . $template);
        foreach ($data as $k => $v) {
            $content = str_replace("{\${$k}}", $v, $content);
        }
        return $content;
    }
}
