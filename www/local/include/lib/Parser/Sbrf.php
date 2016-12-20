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
    private $content;

    /**
     * Sbrf constructor.
     * @param $url
     */
    public function __construct($url)
    {
        parent::__construct();

        $this->url = $url;
        $this->configPath = __DIR__ . "/config/sbrf_config.php";
        $this->includedJsSnippets[] = "console.log($('.tabs-area').html())";
    }

    public function getUrl()
    {
        cl(self::getWebPage($this->url));
    }
}