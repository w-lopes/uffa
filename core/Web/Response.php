<?php

namespace core\Web;

class Response
{

    public static function notFound()
    {
        self::error("Not found", 404);
    }

    public static function send($data) {
        header('Content-Type: application/json');
        echo json_encode([
            "data"  => $data,
            "error" => null
        ]);
        exit;
    }

    public static function error(string $message = "Not found", int $code = 404)
    {
        header('Content-Type: application/json');
        http_response_code($code);
        echo json_encode([
            "data"  => null,
            "error" => $message
        ]);
        die;
    }
}
