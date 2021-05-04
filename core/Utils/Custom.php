<?php

namespace core\Utils;

/**
 * Handle custom files and infos.
 */
class Custom
{

    /**
     * Check if resource path exists before create it.
     *
     * @return bool If exists or created
     */
    public static function mkdirResource()
    {
        return self::mkdir(PATH_CUSTOM_RESOURCES);
    }

    /**
     * Check if commands path exists before create it.
     *
     * @return bool If exists or created
     */
    public static function mkdirCommand()
    {
        return self::mkdir(PATH_CUSTOM_COMMANDS);
    }

    /**
     * Check if a path exists before create it.
     *
     * @param string $path Path to check or create.
     * @return bool If exists or created
     */
    private static function mkdir(string $path)
    {
        if (file_exists($path)) {
            return true;
        }
        return mkdir($path, 0777, true);
    }
}
