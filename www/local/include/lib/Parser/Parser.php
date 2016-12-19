<?php
/**
 * Created by PhpStorm.
 * User: garin
 * Date: 19.12.2016
 * Time: 22:17
 */

namespace Parser;


class Parser
{
    protected $timeout = 10;
    protected $redirectsCount = 0;
    protected $maxRedirects = 10;

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