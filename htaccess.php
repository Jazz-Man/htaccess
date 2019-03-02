<?php

use JazzMan\Htaccess\MimeType;
use Tivie\HtaccessParser\HtaccessContainer;
use Tivie\HtaccessParser\Parser;
use \Mimey\MimeTypes as M;
use Tivie\HtaccessParser\Token\Block;
use Tivie\HtaccessParser\Token\Directive;

$root_dir = __DIR__;
require_once "{$root_dir}/vendor/autoload.php";

$htaccess_file = "{$root_dir}/.htaccess-old";
$new_htaccess_file = "{$root_dir}/.htaccess";
$is_htaccess = file_exists($htaccess_file);

if ($is_htaccess){
    $parser = new Parser();
    $parser->setFile(new \SplFileObject($new_htaccess_file));
    $parser->ignoreWhitelines()
           ->ignoreComments();

    try {

        $mimes = new MimeType();

        $mime = new M();

        $container = [];

        $container[] = new Block('IfModule','mod_deflate.c');

        $ServerSignature = new Directive('RequestHeader');
        $ServerSignature->addArgument('off');

        $container[] = $ServerSignature;

        $htaccess = new HtaccessContainer($container);

        dump((string)$htaccess);


    } catch (Exception $e) {
        dump($e->getMessage());
    }
}
