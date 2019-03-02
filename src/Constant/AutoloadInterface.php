<?php

namespace JazzMan\Htaccess\Constant;

/**
 * Interface AutoloadInterface.
 */
interface AutoloadInterface
{

    /**
     * @return mixed
     */
    public function load();

    /**
     * @return array
     */
    public function getData();
}
