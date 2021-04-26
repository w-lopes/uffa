<?php

namespace core\Web;

use core\Utils\Normalizer;
use Exception;
use ReflectionClass;
use ReflectionNamedType;

class Index
{

    public $uri = [];

    public $model;

    public $method = "";

    public $reflection;

    public $attributes = [];

    public function __construct($uri)
    {
        $this->uri    = explode("/", trim($uri ?? "", "/"));
        $this->method = array_shift($this->uri);
        $this->model  = Normalizer::className(array_shift($this->uri) ?? "");

        try {
            $className        = "\\custom\\Models\\{$this->model}";
            $this->model      = new $className();
            $this->reflection = new ReflectionClass($className);
            $this->getAttributes();
        } catch (Exception $e) {
            Response::error();
        }
    }

    public function api()
    {
        dd("api", $this->uri);
    }

    public function doc()
    {
        dd("doc", $this->uri);
    }

    private function getAttributes()
    {
        dd($this->reflection->getMethods());
        // dd($this->reflection->getProperty("id")->getDocComment());
        // dd($this->reflection->getProperties());
        // $attributes = $this->reflection->getAttributes();

        // foreach ($attributes as $attribute) {
        //    dd($attribute);
        // }
    }
}
