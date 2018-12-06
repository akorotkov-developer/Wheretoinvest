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
class Binbank
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

        if ($this->id == 104)
            preg_match("/\'8\'\:.*'valute'\:(.*)/is", $sContent, $matches);
        else
            preg_match("/\'7\'\:.*'valute'\:(.*)/is", $sContent, $matches);

        $sContent = trim(str_replace(array('//valut', ':.', '\''), array('', ':0.', '"'), $matches[1]));

        // Удаление js-комментариев
        $sContent = preg_replace('/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/', '', $sContent);

        if ($this->id == 104)
            $sContent = trim(substr($sContent, 0, strlen($sContent) - 2));
        else $sContent = trim(substr($sContent, 0, strlen($sContent) - 3));

        $sContent = substr($sContent, 0, strrpos($sContent, ',')) . '}}';

        $this->content = json_decode($sContent);
    }

    public function getUrl()
    {
        return self::getWebPage($this->url);
    }
}