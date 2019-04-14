<?php

namespace JazzMan\Htaccess;

use JazzMan\AutoloadInterface\AutoloadInterface;
use Tivie\HtaccessParser\Exception\Exception;

/**
 * Class CrossOrigin.
 */
class CrossOrigin implements AutoloadInterface
{
    public function load()
    {
        $files = app_files_in_path(APP_CONFIG_DIR.'/cross-origin', "/\.conf$/");

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
