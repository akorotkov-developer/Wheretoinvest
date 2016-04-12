<?php

namespace Cetera\Tools;

class Http
{
  const METHOD_POST = 'POST';
  const METHOD_GET = 'GET';
  const METHOD_PUT = 'PUT';
  const METHOD_DELETE = 'DELETE';

  /**
   * Ajax?
   *
   * @return bool
   */
  public static function isXHR()
  {
    $headers = apache_request_headers();
    $xhrHeader = $headers['X-Requested-With'];
    if(!$xhrHeader)
      $xhrHeader = $headers['x-requested-with'];
    if(!$xhrHeader)
      $xhrHeader = $_SERVER['HTTP_XHR'];
    if(!$xhrHeader)
      $xhrHeader = $_REQUEST['HTTP_XHR'];

    return isset($xhrHeader) && $xhrHeader == 'XMLHttpRequest';
  }

  public static function getXHRData()
  {
    if(stripos($_SERVER["HTTP_CONTENT_TYPE"], "application/json") === 0 || stripos($_SERVER["CONTENT_TYPE"], "application/json") === 0)
    {
      $xhrData = file_get_contents("php://input");
      $xhrDataDecoded = json_decode($xhrData, true);
      if($xhrDataDecoded)
        return $xhrDataDecoded;

      return $xhrData;
    }

    switch(self::getMethod())
    {
      case self::METHOD_POST:
        return $_POST;
      default:
        return $_GET;
    }
  }

  public static function getMethod()
  {
    return $_SERVER["REQUEST_METHOD"];
  }

  public static function methodIs($method)
  {
    return toUpper(self::getMethod()) == toUpper($method);
  }
}