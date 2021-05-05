<?php

namespace core\Web;

use core\Utils\Normalizer;
use Exception;
use ReflectionClass;

class Index
{

    public $uri = [];

    public $resource;

    public $method = "";

    public $reflection;

    public $attributes = [];

    public function __construct($uri)
    {
        $this->uri      = explode("/", trim($uri ?? "", "/"));
        $this->method   = array_shift($this->uri);
        $this->resource = Normalizer::className(array_shift($this->uri) ?? "");

        if (!method_exists($this, $this->method)) {
            Response::notFound();
        }

        $this->{$this->method}();
    }

    public function doc()
    {
        $fetched = Route::fetch();
        $fetched; // Used inside template only
        include PATH_TEMPLATES . "Doc.uffa";
        exit;
    }

    public function api()
    {
        dd("TODO", "api", $this->uri);
    }
}
