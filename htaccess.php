<?php

define('APP_ROOT_DIR', __DIR__);
define('APP_CONFIG_DIR', APP_ROOT_DIR.'/config');

require_once APP_ROOT_DIR.'/vendor/autoload.php';

app_init();

app_build_htaccess();
