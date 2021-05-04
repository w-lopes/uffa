<?php

/**
 * Starts its core.
 */
call_user_func(function () {
    $herePath = dirname(__FILE__);
    $ds       = DIRECTORY_SEPARATOR;
    $requires = "{$herePath}{$ds}requires{$ds}";

    // Load requires
    foreach (new DirectoryIterator($requires) as $require) {
        if (!$require->isFile()) {
            continue;
        }

        require_once "{$requires}{$require->getFilename()}";
    }
});
