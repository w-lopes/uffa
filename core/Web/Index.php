<?php

namespace core\Web;

use core\Utils\Normalizer;
use core\Bases\Resource;
use Exception;

/**
 * Handle requests received on index.php file.
 */
class Index
{
    /**
     * @var string[] URI splitted by slash.
     */
    public $uri = [];

    /**
     * @var Resource Resource loaded.
     */
    public $resource;

    /**
     * @var string Method to be called inside Index class.
     */
    public $method = "";

    /**
     * Constructor.
     *
     * @param string $uri Request URI
     */
    public function __construct($uri)
    {
        $this->uri      = explode("/", trim($uri ?? "", "/"));
        $this->method   = array_shift($this->uri);
        $this->resource = Normalizer::className(array_shift($this->uri) ?? "");

        if (!method_exists($this, $this->method)) {
            Response::notFound();
        }
    }

    /**
     * Display API documentation.
     */
    public function doc()
    {
        $fetched = Route::fetch();
        $fetched; // Used inside template only
        include PATH_TEMPLATES . "Doc.uffa";
        exit;
    }

    /**
     * Process API request.
     */
    public function api()
    {
        $resouceName = "\\custom\\Resources\\{$this->resource}";
        try {
            $resource = new $resouceName();
        } catch (Exception) {
            Response::error();
        }
        (new Api($resource, $this->uri))->execute();
    }
}
