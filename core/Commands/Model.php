<?php

namespace core\Commands;

use core\Bases\Command as BaseCommand;
use core\Utils\Custom;
use core\Utils\Normalizer;
use core\Utils\Template;

/**
 * Handle custom models.
 */
class Model extends BaseCommand
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        Custom::mkdirModel();
        parent::__construct();
    }

    /**
     * Create a new custom model.
     * 
     * @param string $model Model name
     */
    public function create(string $model)
    {
        $name = Normalizer::className($model);
        $file = PATH_CUSTOM_MODELS . "{$name}.php";

        if (file_exists($file)) {
            self::error("Model already exists!", true);
        }

        $content = Template::parse("Model", [
            "name" => $name
        ]);

        if (!file_put_contents($file, $content)) {
            self::error("Failed to create model!");
            return;
        }

        self::success("Model {$name} created!");
        self::warn(preg_replace("/.*\.\.\//", "", $file));
    }

    /**
     * Delete a custom model.
     * 
     * @param string $model Model name
     */
    public function delete(string $model)
    {
        $name = Normalizer::className($model);
        $path = PATH_CUSTOM_MODELS . "{$name}.php";
        if (!file_exists($path)) {
            self::error("Model not found!", true);
        }

        if (!unlink($path)) {
            self::error("Failed to delete model!");
            return;
        }

        self::success("Model deleted!");
    }
}