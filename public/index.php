<?php

require_once ".." . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR . "init.php";

use core\Web\Index;
use core\Web\Response;

call_user_func(function () {

    $index = new Index($_SERVER["REQUEST_URI"] ?? "");

    if (!method_exists($index, $index->method)) {
        Response::notFound();
    }

    try {
        call_user_func_array([$index, $index->method], []);
    } catch (\Exception $e) {
        if (config("debug")) {
            Response::error($e->getMessage());
        }
        Response::error();
    }
});
