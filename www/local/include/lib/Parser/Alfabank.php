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
class Alfabank extends Parser
{
    /**
     * @var
     */
    protected $tableUrl = "";

    /**
     * Sbrf constructor.
     * @param $url
     */
    public function __construct($url, $id)
    {
        parent::__construct();
        $this->url = $url;
        $this->id = $id;
        $this->configPath = __DIR__ . "/config/alfabank_config.php";
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
            foreach ($this->content->find(".group-block") as $p) {
                $p = pq($p);
                switch (trim(preg_replace("#(group-block|hidden)#is", "", $p->attr("class")))) {
                    case "rub":
                        $type = "28";
                        break;
                    case "dollar":
                        $type = "29";
                        break;
                    case "euro":
                        $type = "30";
                        break;
                    default:
                        $type = "";
                        break;
                }

                if (empty($type))
                    continue;

                $this->data[$type] = Array();

                $table = pq($p->find(".group-block.none"));
                if (!empty($table)) {
                    $tmpCol = Array();

                    $tableHead = $table->find(".table-head .table-cell");
                    $tableRow = $table->find(".table-row");

                    if (empty($tableHead) || empty($tableRow))
                        continue;

                    $i = 1;
                    foreach ($tableHead as $td) {
                        $td = pq($td);
                        $val = trim($td->text());
                        if (empty($val))
                            continue;

                        $val = explode("год", $val);
                        foreach ($val as &$v) {
                            $v = preg_replace("#[^\d]#is", "", $v);
                        }

                        if (count($val) > 1) {
                            $val = intval($val[0]) * 365 + intval($val[1]);
                        } else {
                            $val = intval($val[0]);
                        }
                        if (!empty($val)) {
                            $tmpCol[$i] = $val;
                            $this->data[$type][$tmpCol[$i]] = Array();
                        }

                        $i++;
                    }

                    foreach ($tableRow as $tr) {
                        $tr = pq($tr);
                        $i = 0;
                        $row = "";
                        foreach ($tr->find(".table-cell") as $td) {
                            $td = pq($td);

                            if ($i === 0) {
                                $min = $td->find(".limit-min")->text();
                                $max = $td->find(".limit-max")->text();

                                $val = Array();
                                if (!empty($min))
                                    $val[] = $min;
                                if (!empty($max))
                                    $val[] = $max;

                                if (!count($val))
                                    break;

                                $val = implode("-", $val);
                                $val = preg_replace("#[^\d,\.-]#is", "", $val);
                                $val = preg_replace("#\.#is", ",", $val);
                                if (!empty($val)) {
                                    $row = $val;
                                }
                            } else {
                                if (!empty($row) && !empty($tmpCol[$i])) {
                                    $val = preg_replace("#[^\d,\.-]#is", "", $td->find(".group-block.prcnt")->eq(0)->text());
                                    $val = floatval(preg_replace("#,#is", ".", $val));

                                    $this->data[$type][$tmpCol[$i]][$row] = $val;
                                }
                            }
                            $i++;
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