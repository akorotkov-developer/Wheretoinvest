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
class Gazprombank extends Parser
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
        $this->configPath = __DIR__ . "/config/gazprom_config.php";
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
            foreach ($this->content->find("p") as $p) {
                $p = pq($p);
                switch (trim($p->text())) {
                    case "Российские рубли":
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

                if (empty($type))
                    continue;

                $this->data[$type] = Array();

                $table = pq($p->next("table"));
                if (!empty($table)) {
                    $tmpCol = Array();
                    $line = 1;
                    foreach ($table->find("tr") as $tr) {
                        $tr = pq($tr);

                        switch ($line) {
                            case 1:
                                continue;
                                break;
                            case 2:
                                $i = 1;
                                foreach ($tr->find("td") as $td) {
                                    $td = pq($td);
                                    $val = $td->text();
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

    public function getTableData()
    {
        self::getTableUrl();
        if (!empty($this->tableUrl)) {
            $this->content = self::getWebPage($this->tableUrl);
            $this->content = phpQuery::newDocument($this->content);
        }
    }

    public function getTableUrl()
    {
        $document = phpQuery::newDocument($this->getUrl());
        $tableUrl = $document->find(".tabs-content iframe[name='chapter']")->attr("src");

        if (!empty($tableUrl)) {
            $uri = parse_url($this->url);
            $uri["path"] = $tableUrl;
            if (!empty($uri["host"])) {
                $this->tableUrl = self::unparse_url($uri);
            }
        }
    }

    public function getUrl()
    {
        return self::getWebPage($this->url);
    }
}