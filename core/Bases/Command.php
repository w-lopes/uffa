<?php

namespace core\Bases;

/**
 * Base class to CLI commands.
 */
abstract class Command
{

    /**
     * @var string Code to bash yellow color
     */
    public const YELLOW = "\e[93m";

    /**
     * @var string Code to bash red color
     */
    public const RED = "\e[91m";

    /**
     * @var string Code to bash green color
     */
    public const GREEN = "\e[92m";

    /**
     * @var string Code to bash default color
     */
    public const DEFAULT = "\e[0m";

    /**
     * Construtor.
     */
    public function __construct()
    {
        // Nothing for now
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        $this->removeEmptyPath(PATH_CUSTOM_COMMANDS);
        $this->removeEmptyPath(PATH_CUSTOM_RESOURCES);
        $this->removeEmptyPath(PATH_CUSTOM);
    }

    /**
     * Display a info message.
     *
     * @param string $text Text message
     */
    public static function info(string $text)
    {
        return self::showText($text);
    }

    /**
     * Display a success message.
     *
     * @param string $text Text message
     */
    public static function success(string $text)
    {
        return self::showText($text, Command::GREEN);
    }

    /**
     * Display a warning message.
     *
     * @param string $text Text message
     */
    public static function warn(string $text)
    {
        return self::showText($text, Command::YELLOW);
    }

    /**
     * Wait for user's input.
     *
     * @param string $text Text message
     * @return string User's input
     */
    public static function prompt(string $text): string
    {
        self::showText("{$text}:", Command::GREEN);
        echo "  > ";
        return trim(fgets(STDIN));
    }

    /**
     * Display a error message.
     *
     * @param string $text Text message
     */
    public static function error(string $text, bool $exitAfter = false)
    {
        $show = self::showText($text, Command::RED);
        if ($exitAfter) {
            exit;
        }
        return $show;
    }

    /**
     * Parse message before print it.
     *
     * @param string $text Text message
     * @param string $color Text color
     */
    private static function showText(string $text, string $color = Command::DEFAULT)
    {
        echo $color . $text . Command::DEFAULT . EOL;
    }

    /**
     * Check if directory is empty then remove it.
     *
     * @param string $path Path to check
     * @return bool If is was deleted
     */
    private function removeEmptyPath(string $path): bool
    {
        if (!file_exists($path)) {
            return false;
        }

        $iterator = new \FilesystemIterator($path);
        if ($iterator->valid()) {
            return false;
        }

        return rmdir($path);
    }
}
