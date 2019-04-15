<?php


use JazzMan\Htaccess\App;
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
     */
    function app_add_htaccess_comments($text)
    {
        $comment = new Comment();
        $comment->setText((string) $text);

        return $comment;
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

if (!function_exists('app_locate_root_dir')) {
    /**
     * @return bool|string
     */
    function app_locate_root_dir()
    {
        static $path;

        if (null === $path) {
            $path = false;

            if (file_exists(ABSPATH.'wp-config.php')) {
                $path = ABSPATH;
            } elseif (file_exists(dirname(ABSPATH).'/wp-config.php') && !file_exists(dirname(ABSPATH).'/wp-settings.php')) {
                $path = dirname(ABSPATH);
            }

            if ($path) {
                $path = realpath($path);
            }
        }

        return $path;
    }
}
