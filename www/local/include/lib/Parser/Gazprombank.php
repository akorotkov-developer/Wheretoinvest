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
     *
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

            foreach ($this->content->find(".interest-rate-table") as $p) {
                $p = pq($p);

                switch (trim($p->find('.interest-rate-table__title')->text())) {
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

                $tmpCol = Array();
                $i = 0;
                foreach ($p->find('.interest-rate-header__text') as $row) {
                    $row = pq($row);

                    $val = $row->text();

                    $val = preg_replace("#[^\d -]#is", "", $val);
                    $val = intval($val);
                    if (!empty($val)) {
                        $tmpCol[$i] = $val;
                        $this->data[$type][$tmpCol[$i]] = Array();
                    }

                    $i++;
                }

                foreach ($p->find('.row--interest-rate-table') as $row) {
                    $row = pq($row);

                    $k = 0;
                    $sum = '';
                    foreach ($row->find('.interest-rate-table__digit') as $column) {
                        $column = pq($column);

                        $val = trim($column->text());
                        if ($k == 0) {
                            $sum = str_replace(array(' — ', ',99'), array('-', ''), $val);
                            $sum = htmlentities($sum, null, 'utf-8');
                            $sum = str_replace('от&nbsp;', '', $sum);
                        }
                        else {
                            $val = preg_replace("#[^\d,\.-]#is", "", $val);
                            $val = floatval(preg_replace("#,#is", ".", $val));
                            $this->data[$type][$tmpCol[$k - 1]][$sum] = $val;
                        }

                        $k++;
                    }
                }
            }
        }
    }

    public function getTableData()
    {
        $this->content = phpQuery::newDocument($this->getUrl());
    }

    public function getUrl()
    {
        return self::getWebPage($this->url);
    }
}