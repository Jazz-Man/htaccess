<?php

namespace JazzMan\Htaccess\Security;

use JazzMan\Htaccess\Constant\AutoloadInterface;
use Tivie\HtaccessParser\Token\Block;

/**
 * Class ContentSecurityPolicy.
 */
class ContentSecurityPolicy implements AutoloadInterface
{

    /**
     * @var
     */
    private $headers;


    /**
     * @return mixed|void
     * @throws \Tivie\HtaccessParser\Exception\InvalidArgumentException
     */
    public function load()
    {
        $this->headers = new Block('IfModule', 'mod_headers.c');
    }

    /**
     * @return array
     */
    public function getData()
    {
        // TODO: Implement getData() method.
    }
}
