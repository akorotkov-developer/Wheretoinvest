<?php
/**
 * Created by PhpStorm.
 * User: garin
 * Date: 19.12.2016
 * Time: 22:22
 */

namespace Parser;


/**
 * Class Sbrf
 * @package Parser
 */
class Sbrf extends Parser
{
    /**
     * @var
     */
    private $url;
    /**
     * @var
     */
    private $content;

    /**
     * Sbrf constructor.
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }


    /**
     *
     */
    public function getUrlContent()
    {
        $this->content = self::getWebPage($this->url);
        cl($this->content);
    }
}