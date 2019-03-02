<?php

namespace JazzMan\Htaccess;

use JazzMan\Htaccess\Constant\AutoloadInterface;

/**
 * Class Directives.
 */
class Directives implements AutoloadInterface
{
    /**
     * @var array
     */
    private $data;

    /**
     * @return mixed|void
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
