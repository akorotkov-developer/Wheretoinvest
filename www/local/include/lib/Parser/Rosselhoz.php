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
 * Class Sbrf
 * @package Parser
 */
class Rosselhoz extends Parser
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
        $this->url = $url;
        $this->id = $id;
        $this->configPath = __DIR__ . "/config/rosselhoz_config.php";
    }

    public function getData()
    {
        if (empty($this->data))
            self::parseData();

        return $this->data;
    }

    public function parseData()
    {
        self::getTableData();
        if (!empty($this->content) && $this->content instanceof \phpQueryObject) {
            foreach ($this->content->find(".b-text-block > p") as $p) {
                $p = pq($p);

                if (preg_match("#Открываемый через дистанционные каналы обслуживания \(выплата процентов в конце срока\)#is", $p->text())) {
                    $table = pq($p->next("table"));
                    if (!empty($table)) {
                        $tmpCol = Array();
                        $line = 1;
                        $typeS = trim($table->find("tr")->eq(1)->find("td")->eq(0)->text());
                        switch ($typeS) {
                            case "Рубли РФ":
                                $type = "28";
                                break;
                            case "Доллары":
                                $type = "29";
                                break;
                            case "Евро":
                                $type = "30";
                                break;
                            default:
                                $type = "";
                                break;
                        }

                        if (empty($type))
                            continue;

                        $this->data[$type] = Array();

                        foreach ($table->find("tr") as $tr) {
                            $tr = pq($tr);

                            switch ($line) {
                                case 1:
                                    $i = 0;
                                    foreach ($tr->find("th") as $td) {
                                        if ($i === 0) {
                                            $i++;
                                            continue;
                                        }

                                        $td = pq($td);
                                        $val = $td->text();
                                        $val = preg_replace("#[^\d]#is", "", $val);
                                        $val = intval($val);
                                        if (!empty($val)) {
                                            $tmpCol[$i] = $val;
                                            $this->data[$type][$tmpCol[$i]] = Array();
                                        }

                                        $i++;
                                    }
                                    break;
                                case 2:
                                    continue;
                                    break;
                                default:
                                    $i = 0;
                                    $row = "";
                                    foreach ($tr->find("td") as $td) {
                                        $td = pq($td);

                                        if ($i === 0) {
                                            $val = preg_replace("#[^\d,\.-]#is", "", $td->text());
                                            $val = preg_replace("#\.#is", ",", $val);
                                            if (!empty($val)) {
                                                $row = $val;
                                            }
                                        } else {
                                            if (!empty($row) && !empty($tmpCol[$i])) {
                                                $val = preg_replace("#[^\d,\.-]#is", "", $td->text());
                                                $val = floatval(preg_replace("#,#is", ".", $val));

                                                $this->data[$type][$tmpCol[$i]][$row] = $val;
                                            }
                                        }
                                        $i++;
                                    }
                                    break;
                            }

                            $line++;
                        }
                    }
                }
            }
        }
    }

    public function getTableData()
    {
        $this->content = phpQuery::newDocument(self::getUrl());
    }

    public function getUrl()
    {
        return self::getWebPage($this->url);
    }
}