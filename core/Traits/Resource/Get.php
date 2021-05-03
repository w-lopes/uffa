<?php

namespace core\Traits\Resource;

use core\Web\Route;
use core\Utils\Uuid;

/**
 * Common trait to fetch an record.
 */
trait Get
{

    /**
     * Get an record by its ID.
     * 
     * @param Uuid|null $id Record's ID
     */
    #[Route(uri: "/get/{id?}", method: ["get"])]
    public function get(Uuid|null $id = null)
    {
        dd("Get method '{$id}'");
    }
}