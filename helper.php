<?php

use JazzMan\Htaccess\App;
use Tivie\HtaccessParser\HtaccessContainer;
use Tivie\HtaccessParser\Parser;
use Tivie\HtaccessParser\Token\Comment;

if (!function_exists('app')) {
    /**
     * @return \JazzMan\Htaccess\App
     */
    function app()
    {
        return App::getInstance();
    }
}

if (!function_exists('app_init')) {
    function app_init()
    {
        $autoload = app()->getClassAutoload();
        if (!empty($autoload)) {
            app_autoload_classes($autoload);
        }
    }
}

if (!function_exists('app_htaccess_parser')) {
    /**
     * @return \Tivie\HtaccessParser\Parser
     */
    function app_htaccess_parser()
    {
        $parser = new Parser();
        $parser->ignoreComments()->ignoreWhitelines();

        return $parser;
    }
}

if (!function_exists('app_add_htaccess_comments')) {
    /**
     * @param string $text
     *
     * @return \Tivie\HtaccessParser\Token\Comment
     *
     * @throws \Tivie\HtaccessParser\Exception\InvalidArgumentException
     */
    function app_add_htaccess_comments($text)
    {
        $comment = new Comment();
        $comment->setText((string) $text);

        return $comment;
    }
}

if (!function_exists('app_htaccess_file')) {
    /**
     * @return \SplFileObject
     */
    function app_htaccess_file()
    {
        $htaccess_file = APP_ROOT_DIR.'/.htaccess';

        return new \SplFileObject($htaccess_file, 'w+');
    }
}

if (!function_exists('app_build_htaccess')) {
    function app_build_htaccess()
    {
        $htaccess = '';

        $container = array_filter(app()->getContainer());

        if (!empty($container)) {
            $container = array_merge(...$container);

            foreach ($container as $item) {
                if ($item instanceof  HtaccessContainer) {
                    $htaccess .= $item;
                }else{

                    $htaccess .= new HtaccessContainer([$item]);
                }
            }

        }

        if (!empty($htaccess)){
           $htaccess_file = app_htaccess_file();

            $htaccess_file->fwrite($htaccess);
        }
    }
}

if (!function_exists('app_files_in_path')) {
    /**
     * @param string $folder
     * @param string $pattern
     * @param int    $max_depth
     *
     * @return \RegexIterator|\SplFileInfo[]
     */
    function app_files_in_path($folder, $pattern, $max_depth = 1)
    {
        $dir = new RecursiveDirectoryIterator($folder, FilesystemIterator::SKIP_DOTS);
        $ite = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::SELF_FIRST);
        $ite->setMaxDepth($max_depth);

        return new RegexIterator($ite, $pattern);
    }
}
