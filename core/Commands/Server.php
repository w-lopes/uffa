<?php

namespace core\Commands;

use core\Bases\Command;

/**
 * Handle a local server.
 */
class Server extends Command
{

    /**
     * Start a development server.
     *
     * @param string $host Listener host
     * @param int $port Port to start the server
     */
    public function run(string $host = "0.0.0.0", int $port = 8080)
    {
        system("php -S {$host}:{$port} -t " . PATH_PUBLIC);
    }
}
