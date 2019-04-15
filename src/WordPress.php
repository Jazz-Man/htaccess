<?php

namespace JazzMan\Htaccess;

use JazzMan\AutoloadInterface\AutoloadInterface;
use Tivie\HtaccessParser\Exception\Exception;

/**
 * Class WordPress.
 */
class WordPress implements AutoloadInterface
{
    public $config_dir = 'wp';

    public function load()
    {
        $dir = WP_HTACCESS_CONFIG_DIR.'/wp';

        $parser = app_htaccess_parser();

        $file = 'base.conf';

        if (is_multisite()) {
            if (\defined('SUBDOMAIN_INSTALL') && SUBDOMAIN_INSTALL) {
                $file = 'multisite-subdomain.conf';
            } else {
                $file = 'multisite-subfolder.conf';
            }
        }

        $file = new \SplFileObject("{$dir}/{$file}");

        try {
            app()->setContainer($parser->parse($file->openFile()));
        } catch (Exception $e) {
        }
    }
}
