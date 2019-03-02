<?php

$root_dir = __DIR__;
require_once "{$root_dir}/vendor/autoload.php";

$htaccess_file = "{$root_dir}/.htaccess-old";
$new_htaccess_file = "{$root_dir}/.htaccess";
$is_htaccess = file_exists($htaccess_file);


new \JazzMan\Htaccess\App();
