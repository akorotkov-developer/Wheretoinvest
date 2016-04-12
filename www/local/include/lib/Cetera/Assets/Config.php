<?php


namespace Cetera\Assets;


class Config 
{
  /**
   * Список js для подключения через JsIncludes
   * @see JsIncludes
   * @var array
   */
  public static $jsIncludes = array(
    'common.jq' => 'http://yandex.st/jquery/1.11.1/jquery.min.js',
    'common.common' => '#SITE_TEMPLATE_PATH#/js/common.js?v=1',
  );
}