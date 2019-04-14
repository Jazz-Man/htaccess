<?php

namespace JazzMan\Htaccess;

use JazzMan\AutoloadInterface\AutoloadInterface;
use Tivie\HtaccessParser\Token\Block;
use Tivie\HtaccessParser\Token\Directive;

/**
 * Class Headers.
 */
class Headers implements AutoloadInterface
{
    /**
     * @var array
     */
    private $data;
    /**
     * @var Block
     */
    private $headers;

    /**
     * @var string
     */
    public static $setName = 'Header set';
    /**
     * @var string
     */
    public static $unsetName = 'Header unset';
    /**
     * @var string
     */
    public static $alwaysName = 'Header always set';

    /**
     * @var array
     */
    private $headers_set;

    /**
     * @var array
     */
    private $headers_unset;

    /**
     * @var
     */
    private $headers_always;

    /**
     * @throws \Tivie\HtaccessParser\Exception\DomainException
     * @throws \Tivie\HtaccessParser\Exception\InvalidArgumentException
     */
    public function load()
    {
        $this->headers = new Block('IfModule', 'mod_headers.c');

        $this->headers_set = [
            'Referrer-Policy "no-referrer-when-downgrade"',
            'Access-Control-Allow-Origin "*"',
            'Timing-Allow-Origin: "*"',
            'X-UA-Compatible "IE=edge"',
            'X-Frame-Options "DENY"',
            'Content-Security-Policy "script-src \'self\'; object-src \'self\'"',
            'X-Content-Type-Options "nosniff"',
            'X-XSS-Protection "1; mode=block"',
            'P3P "policyref=\"/w3c/p3p.xml\", CP=\"IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT\""',
        ];

        $this->headers_unset = [
            'X-Powered-By',
        ];

        $this->headers_always = [
            'Strict-Transport-Security "max-age=16070400; includeSubDomains"',
        ];

        $this->setHeaders();

        app()->setContainer($this->data);
    }


    /**
     * @throws \Tivie\HtaccessParser\Exception\DomainException
     * @throws \Tivie\HtaccessParser\Exception\InvalidArgumentException
     */
    private function setHeaders()
    {
        foreach ($this->headers_set as $item) {
            $directive = new Directive(self::$setName, (array) $item);

            $this->headers->addChild($directive);
        }

        foreach ($this->headers_unset as $item) {
            $directive = new Directive(self::$unsetName, (array) $item);
            $this->headers->addChild($directive);
        }

        foreach ($this->headers_always as $alway) {
            $directive = new Directive(self::$alwaysName, (array) $alway);

            $this->headers->addChild($directive);
        }


        $this->data = $this->headers;
    }

}
