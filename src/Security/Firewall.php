<?php

namespace JazzMan\Htaccess\Security;

use JazzMan\AutoloadInterface\AutoloadInterface;
use Tivie\HtaccessParser\Exception\Exception;

/**
 * Class Firewall.
 */
class Firewall implements AutoloadInterface
{
    /**
     * @var string
     */
    public static $log_file = '7g_log.php';

    public function load()
    {
        $files = app_files_in_path(APP_CONFIG_DIR.'/firewall', "/\.conf$/");

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
