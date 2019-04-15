<?php

namespace JazzMan\Htaccess;

use JazzMan\Htaccess\Security\Firewall;
use JazzMan\Traits\SingletonTrait;

/**
 * Class App.
 */
class App
{
    use SingletonTrait;
    /**
     * @var array
     */
    private $class_autoload;
    /**
     * @var array
     */
    private $container = [[]];
    /**
     * @var \Tivie\HtaccessParser\Parser
     */
    private $parser;

    /**
     * App constructor.
     *
     */
    public function __construct()
    {

        $this->class_autoload = [
            //            Directives::class,
            Firewall::class,
//            Headers::class,
            CrossOrigin::class,
            Errors::class,
            InternetExplorer::class
            //            ContentSecurityPolicy::class
        ];
    }

    /**
     * @return array
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param array|mixed $container
     */
    public function setContainer($container)
    {
        $this->container[] = \is_array($container) ? $container : [$container];
    }

    /**
     * @return array
     */
    public function getClassAutoload()
    {
        return $this->class_autoload;
    }

    /**
     * @return \Tivie\HtaccessParser\Parser
     */
    public function getParser()
    {
        return $this->parser;
    }

}
