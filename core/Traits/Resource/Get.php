<?php

namespace core\Traits\Resource;

use core\Web\Route;

/**
 * Common trait to fetch an record.
 */
trait Get
{

    /**
     * Get an record by its ID.
     *
     * @param int $id Record's ID
     */
    #[Route(uri: "/get/{id?}", method: ["get"])]
    public function get(int $id = null)
    {
        dd("Get method", $id);
    }
}
