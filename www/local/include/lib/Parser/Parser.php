<?php
/**
 * Created by PhpStorm.
 * User: garin
 * Date: 19.12.2016
 * Time: 22:17
 */

namespace Parser;


use Cetera\Exception\Exception;

class Parser
{
    protected $binPath;
    protected $timeout = 10;
    protected $redirectsCount = 0;
    protected $maxRedirects = 10;
    protected $configPath = "";
    protected $url;
    protected $data = Array();
    protected $includedJsScripts = Array(
        "https://yastatic.net/jquery/2.2.3/jquery.min.js"
    );
    protected $userAgentString;
    protected $includedJsSnippets;
    protected $options = array();

    /**
     * Parser constructor.
     */
    public function __construct()
    {
        $this->binPath = realpath(implode(DIRECTORY_SEPARATOR, array(dirname(__FILE__), '..', '..', 'vendor', 'microweber', 'screen', 'bin'))) . DIRECTORY_SEPARATOR;
    }

    public function getData()
    {
        $this->data["url"] = $this->url;

        if ($this->userAgentString) {
            $this->data['userAgent'] = $this->userAgentString;
        }

        if ($this->timeout) {
            $this->data['timeout'] = $this->timeout;
        }

        if ($this->includedJsScripts) {
            $this->data['includedJsScripts'] = $this->includedJsScripts;
        }

        if ($this->includedJsSnippets) {
            $this->data['includedJsSnippets'] = $this->includedJsSnippets;
        }

        $jobName = md5(json_encode($this->data));
        $jobPath = __DIR__ . "/tmp/" . $jobName . ".js";

        if (!is_file($jobPath)) {
            // Now we write the code to a js file
            $resultString = $this->getTemplateResult($this->configPath, $this->data);
            file_put_contents($jobPath, $resultString);
        }

        $command = sprintf("%sphantomjs %s %s", $this->binPath, $this->getOptionsString(), $jobPath);

        // Run the command and ensure it executes successfully
        $returnCode = null;
        $output = [];
        exec(sprintf("%s 2>&1", escapeshellcmd($command)), $output, $returnCode);

        if ($returnCode !== 0) {
            throw new Exception($output, $returnCode);
        }

        cl($output);
    }

    /**
     * @param string $templateName
     * @param array $args
     *
     * @return string
     * @throws TemplateNotFoundException
     */
    protected function getTemplateResult($template, array $args)
    {
        if (!file_exists($template)) {
            throw new Exception(sprintf("The template '%s' does not exist!", $template));
        }
        ob_start();
        extract($args);
        include $template;

        return ob_get_clean();
    }

    /**
     * @return string
     */
    private function getOptionsString()
    {
        if (empty($this->options)) {
            return '';
        }

        $mappedOptions = array_map(function ($value, $key) {
            if (substr($key, 0, 2) === '--') {
                $key = substr($key, 2);
            }

            return sprintf("--%s=%s", $key, $value);
        }, $this->options, array_keys($this->options));

        return implode(' ', $mappedOptions);
    }

    public function getShot()
    {
        try {
            cl("Start");
            $screen = new \Screen\Capture($this->url);
            /*$screen->output->setLocation(__DIR__ . "/tmp/");
            $screen->jobs->setLocation(__DIR__ . "/tmp/");
            $fileLocation = md5($this->url);
            $screen->save($fileLocation);
            $tmpFile = $screen->getImageLocation();
            $timeout = 0;
            $maxTimeout = 15;
            $timeoutStep = 1;

            while (!file_exists($tmpFile) && $timeout <= $maxTimeout) {
                sleep($timeoutStep);
                $timeout += $timeoutStep;
            }*/
            cl("End");
        } catch (Exception $e) {
            cl($e);
        }
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