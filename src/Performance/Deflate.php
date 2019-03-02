<?php

namespace JazzMan\Htaccess\Performance;

use JazzMan\Htaccess\Constant\AutoloadInterface;

class Deflate implements AutoloadInterface
{

    /**
     * @var array
     */
    private $data;
    /**
     * @return mixed
     */
    public function load()
    {
        // TODO: Implement load() method.
    }


    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

}
