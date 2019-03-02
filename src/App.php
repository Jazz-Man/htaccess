<?php

namespace JazzMan\Htaccess;

use JazzMan\Htaccess\Constant\AutoloadInterface;

class App
{

    /**
     * @var array
     */
    private $class_autoload;

    /**
     * @var array
     */
    private $container;

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
}
