<?php

$root_dir = __DIR__;

define('ROOT_DIR',__DIR__);

require_once ROOT_DIR . '/vendor/autoload.php';

$htaccess_file = ROOT_DIR . '/.htaccess-old';
$new_htaccess_file = ROOT_DIR . '/.htaccess';
$is_htaccess = file_exists($htaccess_file);


new \JazzMan\Htaccess\App();
