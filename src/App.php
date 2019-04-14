<?php

namespace JazzMan\Htaccess;

use JazzMan\Htaccess\Constant\AutoloadInterface;
use JazzMan\Htaccess\Security\ContentSecurityPolicy;
use JazzMan\Htaccess\Security\Firewall;
use Tivie\HtaccessParser\HtaccessContainer;
use Tivie\HtaccessParser\Token\Comment;

/**
 * Class App.
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
    private $container = [[]];

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->class_autoload = [
//            Directives::class,
            Firewall::class,
//            Headers::class,
//            ContentSecurityPolicy::class
        ];

        $this->loadDependencies();

        $this->buildHtaccess();
    }

    private function loadDependencies()
    {
        foreach ($this->class_autoload as $item) {
            try {
                $class = new \ReflectionClass($item);
                if ($class->implementsInterface(AutoloadInterface::class)) {
                    /** @var AutoloadInterface $instance */
                    $instance = $class->newInstance();
                    $instance->load();

                    $data = $instance->getData();

                    $this->container[] = \is_array($data) ? $data : [$data];
                }
            } catch (\ReflectionException $e) {
            }
        }
    }

    /**
     * @param string $text
     *
     * @return \Tivie\HtaccessParser\Token\Comment
     *
     * @throws \Tivie\HtaccessParser\Exception\InvalidArgumentException
     */
    public static function addComments($text)
    {
        $comment = new Comment();
        $comment->setText((string) $text);

        return $comment;
    }

    private function buildHtaccess()
    {
//        $this->container = array_filter($this->container);

        if (!empty($this->container)) {
            $this->container = array_merge(...$this->container);
            $htaccess = new HtaccessContainer($this->container);
            dump((string) $htaccess);
        }
    }
}
