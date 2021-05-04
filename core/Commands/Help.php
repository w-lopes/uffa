<?php

namespace core\Commands;

use core\Bases\Command;
use core\Utils\Classes;
use core\Utils\Normalizer;

/**
 * Help.
 */
class Help extends Command
{

    /**
     * @var int Max size that command will be displayed.
     */
    const LINE_PAD = 60;

    /**
     * Display all available commands.
     */
    public function run()
    {
        $classes = array_merge($this->iterateCore(), $this->iterateCustom());

        foreach ($classes as $class) {
            $rawClass = Normalizer::toMachineName(array_reverse(explode("\\", $class))[0]);
            $classDoc = Classes::getClassDocString($class);

            self::warn($classDoc);

            $methods = Classes::getPublicMethods($class);

            foreach ($methods as $method) {
                $machineMethod = Normalizer::toMachineName($method);
                $commandMethod = $method === "run" ? "" : ":{$machineMethod}";
                $commandDoc    = Classes::getMethodDocString($class, $method);
                $parameters    = Classes::getMethodParameters($class, $method);
                $paramDoc      = [];

                array_walk($parameters, function ($item) use (&$paramDoc) {
                    $default = $item->optional ? "={$item->default}" : "";
                    $paramDoc[] = "[{$item->name}{$default}]";
                });

                $paramDoc = implode(" ", $paramDoc);

                $command = str_pad("  {$rawClass}{$commandMethod} {$paramDoc}", Help::LINE_PAD);

                self::info("{$command}  {$commandDoc}");
            }
        }
    }

    /**
     * Get command classes of core.
     *
     * @return string[] Array of classes
     */
    private function iterateCore(): array
    {
        return $this->getClassesFrom("core\\Commands\\", PATH_COMMANDS);
    }

    /**
     * Get command classes of custom.
     *
     * @return string[] Array of classes
     */
    private function iterateCustom(): array
    {
        return $this->getClassesFrom("custom\\Commands\\", PATH_CUSTOM_COMMANDS);
    }

    /**
     * Get classes name from path.
     *
     * @param string $path Prefix of class namespace
     * @param string $path Path to iterate
     * @return string[] Array of classes
     */
    private function getClassesFrom(string $prefix, string $path): array
    {
        $classes = [];
        if (!file_exists($path)) {
            return $classes;
        }
        foreach (new \DirectoryIterator($path) as $class) {
            if (!$class->isFile()) {
                continue;
            }
    
            $classes[] = $prefix . str_replace(".php", "", $class->getFilename());
        }

        return $classes;
    }
}
