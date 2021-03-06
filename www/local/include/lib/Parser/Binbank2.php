<?php
/**
 * Created by PhpStorm.
 * User: garin
 * Date: 19.12.2016
 * Time: 22:22
 */

namespace Parser;

use phpQuery;

/**
 * Class Binbank
 * @package Parser
 */
class Binbank2
    extends Parser
{
    /**
     * @var
     */
    protected $tableUrl = "";

    /**
     * Sbrf constructor.
     *
     * @param $url
     */
    public function __construct($url, $id)
    {
        parent::__construct();
        //$this->url = $url;
        $this->url = "https://www.binbank.ru/local/templates/binbank2017common/js/json_deposit.js";
        $this->id = $id;
        $this->configPath = __DIR__ . "/config/binbank_config.php";
    }

    public function getData()
    {
        if (empty($this->data))
            self::parseData();

        return $this->data;
    }

    public function parseData()
    {
        self::getDataArray();

        if (!empty($this->content)) {

            foreach ($this->content as $k => $val) {

                switch (trim($k)) {
                    case "ru":
                        $type = "28";
                        break;
                    case "dol":
                        $type = "29";
                        break;
                    default:
                        $type = "";
                        break;
                }

                if (empty($type))
                    continue;

                $this->data[$type] = Array();

                foreach ($val as $k2 => $val2) {
                    foreach ($val2 as $k3 => $val3)
                        $this->data[$type][$k3][$k2] = $val3;
                }
            }
        }
    }

    public function getDataArray()
    {
        $sContent =  self::getUrl();

        preg_match("/\'1\'\:.*'valute'\:(.*)\'2'\:/is", $sContent, $matches);

        $sContent = trim(str_replace(array('//valut', ':.', '\''), array('', ':0.', '"'), $matches[1]));

        $sContent = trim(substr($sContent, 0, strlen($sContent) - 2));

        $this->content = json_decode($sContent);
    }

    public function getUrl()
    {
        return self::getWebPage($this->url);
    }
}