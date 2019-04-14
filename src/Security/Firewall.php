<?php

namespace JazzMan\Htaccess\Security;

use JazzMan\Htaccess\App;
use JazzMan\Htaccess\Constant\AutoloadInterface;
use Tivie\HtaccessParser\Token\Block;
use Tivie\HtaccessParser\Token\Comment;
use Tivie\HtaccessParser\Token\Directive;

/**
 * Class Firewall.
 */
class Firewall implements AutoloadInterface
{

    /**
     * @var array
     */
    private $data;

    /**
     * @var Block
     */
    private $query_strings;
    /**
     * @var Block
     */
    private $request_method;
    /**
     * @var Block
     */
    private $referrers;
    /**
     * @var Block
     */
    private $request_strings;
    /**
     * @var Block
     */
    private $user_agents;

    /**
     * @throws \Tivie\HtaccessParser\Exception\DomainException
     * @throws \Tivie\HtaccessParser\Exception\InvalidArgumentException
     */
    public function load()
    {
        $this->query_strings  = new Block('IfModule', 'mod_rewrite.c');
        $this->referrers      = new Block('IfModule', 'mod_rewrite.c');
        $this->request_method = new Block('IfModule', 'mod_rewrite.c');

        $this->request_strings = new Block('IfModule', 'mod_alias.c');
        $this->user_agents     = new Block('IfModule', 'mod_setenvif.c');

        $this->setCore();

        $this->setQueryStrings();
//        $this->setRequestMethod();
//        $this->setReferrers();
//        $this->setRequestStrings();
//        $this->setUserAgents();

    }

    /**
     * @throws \Tivie\HtaccessParser\Exception\DomainException
     * @throws \Tivie\HtaccessParser\Exception\InvalidArgumentException
     */
    private function setCore()
    {
        $this->data[] = App::addComments('7G:[QUERY STRING]');
        $this->data[] = new Directive('ServerSignature',(array)'Off');
        $this->data[] = new Directive('Options',(array)'-Indexes');
        $this->data[] = new Directive('RewriteEngine',(array)'On');
        $this->data[] = new Directive('RewriteBase',(array)'/');
    }


    /**
     * @throws \Tivie\HtaccessParser\Exception\DomainException
     * @throws \Tivie\HtaccessParser\Exception\InvalidArgumentException
     */
    private function setQueryStrings()
    {

        $query_strings = [
            '(eval\() [NC,OR]',
            '(127\.0\.0\.1) [NC,OR]',
            '([a-z0-9]{2000,}) [NC,OR]',
            '(javascript:)(.*)(;) [NC,OR]',
            '(base64_encode)(.*)(\() [NC,OR]',
            '(GLOBALS|REQUEST)(=|\[|%) [NC,OR]',
            '(<|%3C)(.*)script(.*)(>|%3) [NC,OR]',
            "(\\\|\.\.\.|\.\./|~|`|<|>|\|) [NC,OR]",
            '(boot\.ini|etc/passwd|self/environ) [NC,OR]',
            '(thumbs?(_editor|open)?|tim(thumb)?)\.php [NC,OR]',
            "(\'|\\\")(.*)(drop|insert|md5|select|union) [NC]",
        ];

        $this->query_strings->addChild(new Comment('# 7G:[QUERY STRING]'));
        $this->query_strings->addChild(new Directive('RewriteEngine', (array)'on'));

        foreach ($query_strings as $string) {
            $this->query_strings->addChild(new Directive('RewriteCond %{QUERY_STRING}', (array)$string));
        }

        $this->query_strings->addChild($this->getForbidden());

        $this->data[] = $this->query_strings;
    }


    private function setRequestMethod()
    {
        $this->request_method->addChild(new Comment('# 6G:[REQUEST METHOD]'));
        $this->request_method->addChild(new Directive('RewriteCond %{REQUEST_METHOD}', [
            '^(connect|debug|move|put|trace|track) [NC]',
        ]));
        $this->request_method->addChild($this->getForbidden());

        $this->data[] = $this->request_method;
    }


    private function setReferrers()
    {
        $referrers = [
            '([a-z0-9]{2000,}) [NC,OR]',
            '(semalt.com|todaperfeita) [NC]',
        ];

        $this->referrers->addChild(new Comment('# 6G:[REFERRERS]'));

        foreach ($referrers as $referrer) {
            $this->referrers->addChild(new Directive('RewriteCond %{HTTP_REFERER}', (array)$referrer));
        }
        $this->referrers->addChild($this->getForbidden());

        $this->data[] = $this->referrers;
    }


    public function setRequestStrings()
    {
        $request_strings = [
            '(?i)([a-z0-9]{2000,})',
            '(?i)(https?|ftp|php):/',
            '(?i)(base64_encode)(.*)(\()',
            "(?i)(=\\\\\'|=\\\\%27|/\\\\\'/?)\.",
            '(?i)/(\$(\&)?|\*|\"|\.|,|&|&amp;?)/?$',
            '(?i)(\{0\}|\(/\(|\.\.\.|\+\+\+|\\\\\"\\\\\")',
            '(?i)(~|`|<|>|:|;|,|%|\\\|\s|\{|\}|\[|\]|\|)',
            '(?i)/(=|\$&|_mm|cgi-|etc/passwd|muieblack)',
            '(?i)(&pws=0|_vti_|\(null\)|\{\$itemURL\}|echo(.*)kae|etc/passwd|eval\(|self/environ)',
            '(?i)\.(aspx?|bash|bak?|cfg|cgi|dll|exe|git|hg|ini|jsp|log|mdb|out|sql|svn|swp|tar|rar|rdf)$',
            '(?i)/(^$|(wp-)?config|mobiquo|phpinfo|shell|sqlpatch|thumb|thumb_editor|thumbopen|timthumb|webshell)\.php',
        ];
        $this->request_strings->addChild(new Comment('# 6G:[REQUEST STRINGS]'));

        foreach ($request_strings as $string) {
            $this->request_strings->addChild(new Directive('RedirectMatch 403', (array)$string));
        }

        $this->data[] = $this->request_strings;
    }


    public function setUserAgents()
    {
        $this->user_agents->addChild(new Comment('# 6G:[USER AGENTS]'));

        $this->data[] = $this->user_agents;
    }

    /**
     * @return \Tivie\HtaccessParser\Token\Directive
     * @throws \Tivie\HtaccessParser\Exception\DomainException
     * @throws \Tivie\HtaccessParser\Exception\InvalidArgumentException
     */
    private function getForbidden()
    {
        return new Directive('RewriteRule', (array)'.* - [F]');
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
