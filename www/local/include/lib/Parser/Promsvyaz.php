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
class Promsvyaz extends Parser
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
        $this->configPath = __DIR__ . "/config/promsvyaz_config.php";
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
            $switchNum = "";
            foreach ($this->content->find(".v2-view-switcher li") as $li) {
                $li = pq($li);
                if (strpos($li->text(), "Процентные ставки") !== false) {
                    $switchNum = preg_replace("#[^\d]#is", "", $li->attr("ng-click"));
                    break;
                }
            }
            $table = pq($this->content->find(".v2-product-page-rules")->find("[ng-switch-when=\"" . $switchNum . "\"]")->find("table")->eq(0));
            if (!empty($table)) {
                $tmpCol = Array();

                $i = 0;
                if ($table->find("thead")->count()) {
                    foreach ($table->find("thead")->find("tr")->eq(2)->find("th") as $td) {
                        $td = pq($td);
                        $val = trim($td->text());
                        $val = str_replace("–", "-", $val);
                        $val = str_replace('360', '369', $val);
                        $tmpCol[$i] = $val;
                        $i++;
                    }
                } else {
                    $j = 0;
                    foreach ($table->find("tbody")->find("tr") as $tr) {
                        $tr = pq($tr);
                        if ($j === 3) {
                            foreach ($tr->find("th") as $td) {
                                $td = pq($td);
                                $val = trim($td->text());
                                $val = str_replace("–", "-", $val);
                                $val = str_replace('360', '369', $val);
                                $tmpCol[$i] = $val;
                                $i++;
                            }
                            $tr->remove();
                            break;
                        }
                        $tr->remove();
                        $j++;
                    }
                }

                $type = "";

                foreach ($table->find("tbody")->find("tr") as $tr) {
                    $tr = pq($tr);

                    $row = "";
                    $line = 0;

                    foreach ($tr->find("td") as $td) {

                        $td = pq($td);

                        if ($line == 0) {
                            $typeS = trim($td->text());
                            switch ($typeS) {
                                case "Рубли РФ":
                                    $type = "28";
                                    break;
                                case "Доллары США":
                                    $type = "29";
                                    break;
                                case "Евро":
                                    $type = "30";
                                    break;
                                default:
                                    $type = "";
                                    break;
                            }

                            if (!empty($type))
                                $this->data[$type] = Array();
                            else break;
                        }

                        if ($line == 1) {
                            $val = preg_replace("#[^\d]#is", "", $td->text());
                            $val = preg_replace("#\.#is", ",", $val);

                            if (!empty($val)) {
                                $row = $val;
                            }
                        }
                        elseif ($line > 1) {
                            $val = preg_replace("#[^\d,\.-]#is", "", $td->text());
                            $val = floatval(preg_replace("#,#is", ".", $val));

                            $this->data[$type][$tmpCol[$line - 2]][$row] = $val;
                        }

                        $line++;
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