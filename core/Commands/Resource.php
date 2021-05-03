<?php

namespace core\Commands;

use core\Bases\Command as BaseCommand;
use core\Utils\Custom;
use core\Utils\Normalizer;
use core\Utils\Template;

/**
 * Handle custom Resource.
 */
class Resource extends BaseCommand
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        Custom::mkdirResource();
        parent::__construct();
    }

    /**
     * Create a new custom resource.
     * 
     * @param string $resource Resource name
     */
    public function create(string $resource)
    {
        $name = Normalizer::className($resource);
        $file = PATH_CUSTOM_RESOURCES . "{$name}.php";

        if (file_exists($file)) {
            self::error("Resource already exists!", true);
        }

        $content = Template::parse("Resource", [
            "name"    => $name,
            "machine" => Normalizer::toMachineName($name)
        ]);

        if (!file_put_contents($file, $content)) {
            self::error("Failed to create resource!");
            return;
        }

        self::success("Resource {$name} created!");
        self::warn(preg_replace("/.*\.\.\//", "", $file));
    }

    /**
     * Delete a custom resource.
     * 
     * @param string $resource Resource name
     */
    public function delete(string $resource)
    {
        $name = Normalizer::className($resource);
        $path = PATH_CUSTOM_RESOURCES . "{$name}.php";
        if (!file_exists($path)) {
            self::error("Resource not found!", true);
        }

        if (!unlink($path)) {
            self::error("Failed to delete resource!");
            return;
        }

        self::success("Resource deleted!");
    }
}