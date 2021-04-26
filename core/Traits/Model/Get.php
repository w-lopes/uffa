<?php

namespace core\Traits\Model;

trait Get
{

    /**
     * Collect 
     * 
     * @get
     */
    public function get(int $id, array $customCondition = [])
    {
        dd("Get method");
    }
}