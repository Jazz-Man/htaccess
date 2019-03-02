<?php

namespace JazzMan\Htaccess;

use JazzMan\Htaccess\Constant\AutoloadInterface;
use Tivie\HtaccessParser\HtaccessContainer;

/**
 * Class App
 *
 * @package JazzMan\Htaccess
 */
class App
{

    /**
     * @var array
     */
    private $class_autoload;

    /**
     * @var array
     */
    private $container = [];

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->class_autoload = [
            Directives::class,
            Headers::class
        ];

        $this->loadDependencies();

        $this->buildHtaccess();

    }

    private function loadDependencies()
    {
        foreach ($this->class_autoload as $item){
            try {
                $class = new \ReflectionClass($item);
                if ($class->implementsInterface(AutoloadInterface::class)){
                    /** @var AutoloadInterface $instance */
                    $instance = $class->newInstance();
                    $instance->load();

                    $this->container[] = $instance->getData();
                }
            } catch (\ReflectionException $e) {
            }
        }
    }

    private function buildHtaccess()
    {
        $this->container = array_filter($this->container);

        if (!empty($this->container)){

            $htaccess = new HtaccessContainer($this->container);
            dump((string)$htaccess);
        }
    }
}
