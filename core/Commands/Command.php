<?php

namespace core\Commands;

use core\Bases\Command as BaseCommand;
use core\Utils\Custom;
use core\Utils\Normalizer;
use core\Utils\Template;

/**
 * Handle custom commands.
 */
class Command extends BaseCommand
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        Custom::mkdirCommand();
        parent::__construct();
    }

    /**
     * Create a new custom command.
     * 
     * @param string $command Command name
     */
    public function create(string $command)
    {
        $name = Normalizer::className($command);
        $file = PATH_CUSTOM_COMMANDS . "{$name}.php";

        if (file_exists($file)) {
            self::error("Command already exists!", true);
        }

        $content = Template::parse("Command", [
            "name" => $name
        ]);

        if (!file_put_contents($file, $content)) {
            self::error("Failed to create command!");
            return;
        }

        self::success("Command {$name} created!");
        self::warn(preg_replace("/.*\.\.\//", "", $file));
    }

    /**
     * Delete a custom command.
     * 
     * @param string $command Command name
     */
    public function delete(string $command)
    {
        $name = Normalizer::className($command);
        $path = PATH_CUSTOM_COMMANDS . "{$name}.php";
        if (!file_exists($path)) {
            self::error("Command not found!", true);
        }

        if (!unlink($path)) {
            self::error("Failed to delete command!");
            return;
        }

        self::success("Command deleted!");
    }
}