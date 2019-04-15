<?php

/*
Plugin Name:  wp-htaccess
Plugin URI:   https://github.com/Jazz-Man/htaccess
Author:       Jazz-Man
Author URI:   https://github.com/Jazz-Man
*/

define('WP_HTACCESS_DIR', __DIR__);
define('WP_HTACCESS_CONFIG_DIR', WP_HTACCESS_DIR.'/config');

new \JazzMan\Htaccess\App();
