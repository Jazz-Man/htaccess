<?php

namespace JazzMan\Htaccess;

/**
 * Class MimeType.
 */
class MimeType
{
    /**
     * @var array
     */
    public static $javaScript = [
        'application/javascript',
    ];

    /**
     * @var array
     */
    public static $data_interchange = [
        'application/atom+xml',
        'application/json',
        'application/ld+json',
        'application/rss+xml',
        'application/vnd.geo+json',
        'application/xml',
    ];

    /**
     * @var array
     */
    public static $manifest = [
        'application/manifest+json',
        'application/x-web-app-manifest+json',
        'text/cache-manifest',
    ];

    /**
     * @var array
     */
    public static $media = [
        'audio/mp4',
        'audio/ogg',
        'image/bmp',
        'image/svg+xml',
        'image/webp',
        'video/mp4',
        'video/ogg',
        'video/webm',
        'video/x-flv',
        'image/x-icon',
    ];

    /**
     * @var array
     */
    public static $web_fonts = [
        'application/font-woff',
        'application/font-woff2',
        'application/vnd.ms-fontobject',
        'application/x-font-ttf',
        'font/opentype',
    ];
    /**
     * @var array
     */
    public static $other = [
        'application/octet-stream',
        'application/x-bb-appworld',
        'application/x-chrome-extension',
        'application/x-opera-extension',
        'application/x-xpinstall',
        'text/vcard',
        'text/vnd.rim.location.xloc',
        'text/x-component',
    ];

    public static $set_charset = [
        '.atom',
        '.bbaw',
        '.css',
        '.geojson',
        '.js',
        '.json',
        '.jsonld',
        '.manifest',
        '.rdf',
        '.rss',
        '.topojson',
        '.vtt',
        '.webapp',
        '.webmanifest',
        '.xloc',
        '.xml',
    ];
}
