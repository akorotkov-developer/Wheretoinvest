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
        $this->url = $url;
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
        self::getTableData();
        if (!empty($this->content) && $this->content instanceof \phpQueryObject) {
            $num = 0;
            foreach ($this->content->find(".procent_stavka_wrap .procent_stavka_table_wrap") as $p) {
                $p = pq($p);

                switch (trim($this->content->find(".procent_stavka_tabs span")->eq($num)->text())) {
                    case "Рубли":
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

                $num++;

                if (empty($type))
                    continue;

                $this->data[$type] = Array();

                $table = pq($p->find(".procent_stavka_table"));
                if (!empty($table)) {
                    $tmpCol = Array();

                    $tableHead = $table->find(".procent_stavka_table_gray.procent_stavka_table_gray_no_bord");
                    $tableRow = $table->find("tr")->not(".procent_stavka_table_gray");

                    if (empty($tableHead) || empty($tableRow))
                        continue;

                    $i = 1;
                    foreach ($tableHead->find("td") as $td) {
                        $td = pq($td);
                        $val = intval(trim($td->text()));
                        if (empty($val))
                            continue;

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
                        foreach ($tr->find("td") as $td) {
                            $td = pq($td);

                            if ($i === 0) {
                                $val = trim($td->text());
                                $val = preg_replace("#[^\d,\.-]#is", "", $val);
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