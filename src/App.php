<?php

namespace JazzMan\Htaccess;

use JazzMan\Traits\SingletonTrait;
use Tivie\HtaccessParser\HtaccessContainer;

/**
 * Class App.
 */
class App
{
    use SingletonTrait;

    /**
     * @var string
     */
    private $marker = 'Server Config';

    private $text_domain = 'htaccess';
    /**
     * @var array
     */
    private $class_autoload;
    /**
     * @var array
     */
    private $container = [[]];

    /**
     * @var array
     */
    private $errors;

    /**
     * @var string
     */
    private $home_path;
    /**
     * @var \SplFileObject
     */
    private $htaccess;

    /**
     * App constructor.
     */
    public function __construct()
    {
        add_action('init', [$this, 'init']);
    }

    public function init()
    {
        $this->class_autoload = [
            Firewall::class,
            CrossOrigin::class,
            Errors::class,
            InternetExplorer::class,
            Media::class,
            Rewrites::class,
            Security::class,
            Performance::class,
            WordPress::class,
        ];

        $this->home_path = app_locate_root_dir();

        if (!$this->verifySetup()) {
            $this->alerts();
        } else {
            add_action('generate_rewrite_rules', [$this, 'write']);
        }
    }

    /**
     * @return bool
     */
    private function verifySetup()
    {
        if (!get_option('permalink_structure')) {
            $this->errors[] = sprintf(__('Please enable %s.', $this->text_domain),
                '<a href="'.admin_url('options-permalink.php').'">Permalinks</a>');
        }

        if (!is_writable($this->home_path)) {
            $this->errors[] = sprintf(__('Please make sure your %s file is writable.', $this->text_domain),
                '<a href="'.admin_url('options-permalink.php').'">.htaccess</a>');
        }

        return empty($this->errors);
    }

    private function alerts()
    {
        $alert = static function ($message) {
            echo '<div class="error"><p>'.$message.'</p></div>';
        };

        if (current_user_can('activate_plugins')) {
            add_action('admin_notices', function () use ($alert) {
                array_map($alert, $this->errors);
            });
        }
    }

    public function write()
    {
        $this->htaccess = new \SplFileObject("{$this->home_path}/.htaccess", 'w+');

        app_autoload_classes($this->class_autoload);

        $htaccess = '';

        $container = array_filter(app()->getContainer());

        if (!empty($container)) {
            $htaccess .= app_add_htaccess_comments('This file is generated automatically. Do not edit it!');
            $htaccess .= PHP_EOL;
            $htaccess .= app_add_htaccess_comments("BEGIN {$this->marker}");
            $htaccess .= PHP_EOL;

            $container = array_merge(...$container);

            foreach ($container as $item) {
                if ($item instanceof HtaccessContainer || \is_string($item)) {
                    $htaccess .= $item;
                } else {
                    $htaccess .= new HtaccessContainer([$item]);
                }
            }

            $htaccess .= PHP_EOL;
            $htaccess .= app_add_htaccess_comments("END {$this->marker}");
        }

        if (!empty($htaccess)) {
            $this->htaccess->fwrite($htaccess);
        }
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
}
