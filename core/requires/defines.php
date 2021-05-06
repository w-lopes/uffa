<?php

// Shortened
define("CLI", php_sapi_name() === "cli");
define("EOL", CLI ? PHP_EOL : "<br/>");
define("DS", DIRECTORY_SEPARATOR);

// Base path defines
define("PATH_BASE", dirname(__FILE__) . DS . ".." . DS . ".." . DS);
define("PATH_PUBLIC", PATH_BASE . "public" . DS);

// Path
define("PATH_CORE", PATH_BASE . "core" . DS);
define("PATH_COMMANDS", PATH_CORE . "Commands" . DS);
define("PATH_REQUIRES", PATH_CORE . "requires" . DS);
define("PATH_TEMPLATES", PATH_CORE . "templates" . DS);

// Custom path
define("PATH_CUSTOM", PATH_BASE . "custom" . DS);
define("PATH_CUSTOM_COMMANDS", PATH_CUSTOM . "Commands" . DS);
define("PATH_CUSTOM_RESOURCES", PATH_CUSTOM . "Resources" . DS);
