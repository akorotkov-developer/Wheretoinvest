<?php
/**
 * Created by PhpStorm.
 * User: garin
 * Date: 19.12.2016
 * Time: 22:17
 */

namespace Parser;

use phpQuery;

abstract class Parser
{
    protected $timeout = 10;
    protected $redirectsCount = 0;
    protected $maxRedirects = 10;
    protected $configPath = "";
    protected $url;
    protected $data = Array();
    protected $id;
    protected $content;


    /**
     * Parser constructor.
     */
    protected function __construct()
    {
        // prevent from a PHP configuration problem when using mktime() and date()
        if (version_compare(PHP_VERSION, '5.1.0') >= 0) {
            if (ini_get('date.timezone') == '') {
                date_default_timezone_set('UTC');
            }
        }
    }

    public static function parse($id = "")
    {
        $hblock = new \Cetera\HBlock\SimpleHblockObject(3);
        if (!empty($id)) {
            $list = $hblock->getList(Array("filter" => Array("!UF_SITE" => false, "ID" => $id)));
            if ($el = $list->fetch()) {
                try {
                    $hblock->update($el["ID"], Array("UF_UPDATED" => date("d.m.Y H:i:s")));
                    $parser = self::getParser($el["ID"]);
                    if ($parser !== null) {
                        $parser->getData();
                        $parser->saveData();
                        $obCache = new \CPHPCache();
                        $obCache->CleanDir("/offers/");
                        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/parseMatrix.log", date("d.m.Y H:i:s") . " - [" . $el["ID"] . "] " . $el["UF_NAME"] . "\n", FILE_APPEND);
                    }
                } catch (\Exception $e) {
                }
            }
        } else {
            $filter = Array(
                "!UF_SITE" => false
            );

            $showToday = \Ceteralabs\UserVars::GetVar('PAID_ACCESS')["VALUE"];

            if ($showToday !== "N") {
                $filter["<=UF_ACTIVE_START"] = date("d.m.Y");
                $filter[">=UF_ACTIVE_END"] = date("d.m.Y");
            }

            $list = $hblock->getList(Array("filter" => $filter));
            $multi = curl_multi_init();
            $channels = array();
            $urls = Array();

            while ($el = $list->fetch()) {
                try {
                    $hblock->update($el["ID"], Array("UF_UPDATED" => date("d.m.Y H:i:s")));
                    $urls[] = $el["ID"];
                } catch (\Exception $e) {
                }
            }

            foreach ($urls as &$url) {
                $url = "http://" . $_SERVER["SERVER_NAME"] . "/local/ajax/parseMatrix.php?ID=" . $url;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);

                // если будет редирект - перейти по нему
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                // возвращать результат
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                // не возвращать http-заголовок
                curl_setopt($ch, CURLOPT_HEADER, 0);
                // таймаут соединения
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                // таймаут ожидания
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);

                curl_multi_add_handle($multi, $ch);

                $channels[$url] = $ch;
            }

            $active = null;
            do {
                $mrc = curl_multi_exec($multi, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);

            while ($active && $mrc == CURLM_OK) {
                if (curl_multi_select($multi) == -1) {
                    continue;
                }

                do {
                    $mrc = curl_multi_exec($multi, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }

            foreach ($channels as $channel) {
                curl_multi_remove_handle($multi, $channel);
            }

            curl_multi_close($multi);

            return "\\Parser\\Parser::parse();";
        }
    }

    public static function getParser($itemID)
    {
        if (!empty($itemID)) {
            $hblock = new \Cetera\HBlock\SimpleHblockObject(3);
            $list = $hblock->getList(Array("filter" => Array("ID" => $itemID)));
            if ($el = $list->fetch()) {
                $site = $el["UF_SITE"];
                if (!empty($site)) {
                    if (preg_match("#gazprombank\.ru#is", $site)) {
                        return new Gazprombank($site, $itemID);
                    }
                }
            }
        }

        return null;
    }

    public function saveData()
    {
        if (!empty($this->id)) {

            $itemHblock = new \Cetera\HBlock\SimpleHblockObject(3);
            $itemList = $itemHblock->getList(Array("filter" => Array("ID" => $this->id)));
            if ($itemEl = $itemList->fetch()) {
                $message = Array(
                    "NAME" => $itemEl["UF_NAME"],
                    "TIME" => date("d.m.Y H:i:s"),
                    "MESSAGE" => ""
                );

                if (!empty($this->data) && is_array($this->data) && count($this->data)) {
                    $matrix = $this->data;

                    $newMatrix = Array();
                    foreach ($matrix as $currency => $cols) {
                        $cur = preg_replace("#[^\d]#is", "", $currency);
                        foreach ($cols as $col => $rows) {
                            list($start, $end) = explode("-", $col);

                            foreach ($rows as $row => $percent) {
                                list($summStart, $summEnd) = explode("-", $row);

                                $item = Array(
                                    "UF_OFFER" => $this->id,
                                    "UF_CURRENCY" => $cur,
                                    "UF_DATE_START" => $start,
                                    "UF_DATE_END" => $end,
                                    "UF_SUMM" => floatval(preg_replace("#[^\d,]#is", "", $summStart)),
                                    "UF_SUMM_END" => floatval(preg_replace("#[^\d,]#is", "", $summEnd)),
                                    "UF_PERCENT" => floatval($percent)
                                );

                                $newMatrix[] = $item;
                            }
                        }
                    }

                    $hblock = new \Cetera\HBlock\SimpleHblockObject(9);
                    $list = $hblock->getList(Array("filter" => Array("UF_OFFER" => $this->id)));
                    $matrixList = Array();
                    while ($el = $list->fetch()) {
                        $matrixList[] = $el["ID"];
                    }

                    if (count($newMatrix)) {
                        foreach ($matrixList as $key => $listId) {
                            if (count($newMatrix)) {
                                $item = array_shift($newMatrix);
                                $hblock->update($listId, $item);
                                unset($matrixList[$key]);
                            }
                        }
                    }

                    foreach ($newMatrix as $item) {
                        $hblock->add($item);
                    }

                    foreach ($matrixList as $listId) {
                        $hblock->delete($listId);
                    }

                    $message["STATUS"] = "Успешно обновлено";
                } else {
                    $message["STATUS"] = "Ошибка обновления";
                    $message["MESSAGE"] = "Не найдена матрица для предложения. \nПроверьте правильность указанной ссылки для предложения. \nЕсли по указанной ссылке матрица выводится, значит изменилась структура данных на странице. \nВ таком случае сообщите об ошибке разработчику.";
                }

                \CEvent::SendImmediate("PARSER_UPDATE", SITE_ID, $message);
            }
        }
    }

    abstract function getData();

    public function unparse_url($parsed_url)
    {
        $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    /**
     * Загрузка файла/html-страницы
     * @param $url
     * @param bool|false $headers
     * @return bool|mixed|string
     */
    protected function getWebPage($url, $headers = false, $post = false, $returnArray = false)
    {
        $this->redirectsCount = 0;
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            $uagent = "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:41.0) Gecko/20100101 Firefox/41.0";

            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);   // переходит по редиректам
            curl_setopt($ch, CURLOPT_ENCODING, "");        // обрабатывает все кодировки
            curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout); // таймаут соединения
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);        // таймаут ответа
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);       // останавливаться после 10-ого редиректа
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            if (!empty($this->currentUrl))
                curl_setopt($ch, CURLOPT_REFERER, $this->currentUrl);

            if (!empty($headers)) {
                if (!is_array($headers))
                    $headers = explode("\n", $headers);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_HEADER, 1);
                curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
            }

            if (!empty($post)) {
                curl_setopt($ch, CURLOPT_POST, true);
                $postData = [];
                if (is_array($post)) {
                    foreach ($post as $key => $val) {
                        $postData[] = $key . "=" . $val;
                    }
                } else {
                    $postData[] = $post;
                }
                curl_setopt($ch, CURLOPT_POSTFIELDS, implode("&", $postData));
            }

            $redirects = [];
            $content = $this->curl_redirect_exec($ch, $redirects, $returnArray);
            $err = curl_errno($ch);
            $errmsg = curl_error($ch);
            $header = curl_getinfo($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);
        } else {
            $content = file_get_contents($url);
            $err = "";
            $errmsg = "";
            $header = "";
            $http_code = "";
        }
        if (!$returnArray)
            return $content;
        else
            return Array("content" => $content, "err" => $err, "errmsg" => $errmsg, "header" => $header, "code" => $http_code);
    }

    /**
     * @param $ch
     * @param $redirects
     * @param bool|false $curlopt_header
     * @return mixed
     */
    protected function curl_redirect_exec(&$ch, &$redirects, $curlopt_header = false)
    {
        $cookieFile = __DIR__ . "/tmp/cookie_";
        $cookieFile .= trim(strtolower(preg_replace("/\\\\+/si", "_", get_class($this)))) . ".txt";

        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

        $data = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code == 301 || $http_code == 302) {
            list($header) = explode("\r\n\r\n", $data, 2);

            $header .= "\n";
            $matches = array();
            preg_match('/(Location:|URI:)(.*?)\n/', $header, $matches);
            $mainUrlParse = parse_url(curl_getinfo($ch)["url"]);

            $url = trim(array_pop($matches));
            if (preg_match("#^/#is", $url) && !empty($mainUrlParse["host"])) {
                $url = $mainUrlParse["host"] . $url;
            }

            $url_parsed = parse_url($url);

            if (isset($url_parsed) && $this->redirectsCount < $this->maxRedirects) {
                $this->redirectsCount++;
                curl_setopt($ch, CURLOPT_URL, $url);
                $redirects++;
                sleep(1);
                return $this->curl_redirect_exec($ch, $redirects);
            }
        } elseif ($http_code == 403) {
            return $this->curl_redirect_exec($ch, $redirects);
        }
        if ($curlopt_header)
            return $data;
        else {
            list(, $body) = explode("\r\n\r\n", $data, 2);
            return $body;
        }
    }
}