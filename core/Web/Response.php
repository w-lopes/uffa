<?php

namespace core\Web;

/**
 * Class to handle responses.
 */
class Response
{

    /**
     * Default method to throw a not found error.
     */
    public static function notFound(): void
    {
        self::error("Not found", 404);
    }

    /**
     * Send a response to browser.
     * 
     * @param mixed $data Any data
     */
    public static function send($data): void
    {
        header('Content-Type: application/json');
        echo json_encode([
            "data"  => $data,
            "error" => null
        ]);
        exit;
    }

    /**
     * Send a response with an error message.
     * 
     * @param string $message Error message
     * @param int $code Http response code
     */
    public static function error(string $message = "Not found", int $code = 404): void
    {
        header('Content-Type: application/json');
        http_response_code($code);
        $response = [
            "data"  => null,
            "error" => $message
        ];
        if (config("debug")) {
            $response = $response + ["debug" => true];
        }
        echo json_encode($response);
        die;
    }
}
