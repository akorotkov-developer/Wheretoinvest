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
class Rosbank extends Parser
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
        $this->configPath = __DIR__ . "/config/rosbank_config.php";
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
            foreach ($this->content->find(".page-deposit__tabs.js-tabs-container .js-tab-content") as $p) {
                $p = pq($p);
                switch (trim($p->attr("id"))) {
                    case "rub":
                        $type = "28";
                        break;
                    case "usd":
                        $type = "29";
                        break;
                    case "eur":
                        $type = "30";
                        break;
                    default:
                        $type = "";
                        break;
                }

                if (empty($type))
                    continue;

                $this->data[$type] = Array();

                $table = pq($p->find(".page-deposit__table"));
                if (!empty($table)) {
                    $tmpCol = Array();
                    $rowNum = 1;
                    foreach ($table->find(".page-deposit__table_row") as $row) {
                        $row = pq($row);
                        $colNum = 0;
                        $title = "";
                        foreach ($row->find(".table-item") as $col) {
                            $col = pq($col);
                            if ($rowNum === 1) {
                                $val = intval(preg_replace("#[^\d]#is", "", $col->text()));
                                if (!empty($val)) {
                                    $tmpCol[$colNum] = $val;
                                }
                            } else {
                                if ($colNum === 0) {
                                    $title = intval(preg_replace("#[^\d]#is", "", $col->text()));
                                    $title = intval(365 / 12 * $title);
                                } else {
                                    $val = floatval(preg_replace("#,#is", ".", $col->text()));
                                    if (!empty($title) && !empty($tmpCol[$colNum]) && !empty($val)) {
                                        $this->data[$type][$title][$tmpCol[$colNum]] = $val;
                                    }
                                }
                            }
                            $colNum++;
                        }
                        $rowNum++;
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