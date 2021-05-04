<?php

namespace core\Commands;

use core\Bases\Command as BaseCommand;
use core\Utils\Template;

/**
 * Configuration commands.
 */
class Config extends BaseCommand
{

    const FILE    = PATH_BASE . "config.json";
    const EXAMPLE = PATH_BASE . "config.example.json";

    /**
     * Save new attributes from config.json to config.example.json using current values.
     */
    public function export()
    {
        if (!file_exists(Config::EXAMPLE)) {
            copy(Config::FILE, Config::EXAMPLE);
            return;
        }
        $current = jsonLoad(Config::FILE);
        $example = jsonLoad(Config::EXAMPLE);
        $new     = jsonMerge($example, $current);
        jsonSave(Config::EXAMPLE, $new);
    }

    /**
     * Save new attributes from config.example.json to config.json.
     */
    public function import()
    {
        if (!file_exists(Config::FILE)) {
            copy(Config::EXAMPLE, Config::FILE);
            return;
        }
        $current = jsonLoad(Config::EXAMPLE);
        $example = jsonLoad(Config::FILE);
        $new     = jsonMerge($example, $current);
        jsonSave(Config::FILE, $new);
    }
}
