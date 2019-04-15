<?php

namespace JazzMan\Htaccess;

use JazzMan\AutoloadInterface\AutoloadInterface;
use Tivie\HtaccessParser\Exception\Exception;

/**
 * Class HtaccessBase.
 */
abstract class HtaccessBase implements AutoloadInterface
{
    public $config_dir;

    public function load()
    {
        if (null !== $this->config_dir && \is_string($this->config_dir)) {
            $dir = trim($this->config_dir, '/');

            $files = app_files_in_path(WP_HTACCESS_CONFIG_DIR."/{$dir}", "/\.conf$/");

            $parser = app_htaccess_parser();

            foreach ($files as $file) {
                if ($file->isFile()) {
                    try {
                        app()->setContainer($parser->parse($file->openFile()));
                    } catch (Exception $e) {
                    }
                }
            }
        }
    }
}
