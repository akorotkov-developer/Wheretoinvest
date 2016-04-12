<?php


namespace Cetera\Assets;


use Bitrix\Main\EventManager;

/**
 * Class StaticDomain
 * Изменение ссылок на статические файлы на страницах сайта - указание домена
 *
 * Было:
 * /path/to/js/script.js
 * Стало:
 * //static.site.ru/path/to/js/script.js
 *
 * @example
 * <?php
 *  $staticDomainUri = 'static.site.ru'
 *  new StaticDomain($staticDomainUri);
 *
 * @package Cetera\Assets
 */
class StaticDomain
{
  private static $isInited = false;

  private static $isAjax = false;

  private static $STATIC_DOMAIN_URI = '';

  public function __construct($staticDomain)
  {
    if(self::$isInited)
      return;

    self::$STATIC_DOMAIN_URI = $staticDomain;

    if(!defined('ADMIN_SECTION') && self::$STATIC_DOMAIN_URI)
      EventManager::getInstance()->addEventHandler("main", "OnEndBufferContent", Array('\Cetera\Assets\StaticDomain', "modifyStaticIncludes"));

    self::$isInited = true;
  }

  /**
   * изменение ссылок на css и js файлы
   * @param $content
   */
  public static function modifyStaticIncludes(&$content)
  {
    self::$isAjax = stripos(substr($content, 0, 1024), '<head>') === false;

    $extension_regex = "(?:" . implode("|", array('js', 'css', 'jpg', 'png', 'jpeg', 'JPG', 'PNG', 'JPEG', 'ico')) . ")";
    $regex = "/
				((?i:
					href=
					|src=
					|BX\\.loadCSS\\(
					|BX\\.loadScript\\(
					|BX\\.getCDNPath\\(
					|jsUtils\\.loadJSFile\\(
					|background\\s*:\\s*url\\(
					|image\\s*:\\s*url\\(
					|'SRC':
				))                                                   #attribute
				(\"|'|)                                               #open_quote
				([^?'\"]+\\.)                                        #href body
				(" . $extension_regex . ")                               #extension
				(|\\?\\d+|\\?v=\\d+)                                 #params
				(\\2)                                                #close_quote
			/x";
    $content = preg_replace_callback($regex, array('\Cetera\Assets\StaticDomain', 'filter'), $content);

  }

  private static function filter($match)
  {
    $attribute = $match[1];
    $open_quote = $match[2];
    $link = $match[3];
    $extension = $match[4];
    $params = $match[5];
    $close_quote = $match[6];

    if(self::$isAjax && $extension === "js")
      return $match[0];

    if(strpos($link, '//') === 0 ||
      strpos($link, 'http://') === 0 ||
      strpos($link, 'https://') === 0)
      return $match[0];

    $filePath = $link . $extension;
    if(!$params)
      $filePath = \CUtil::GetAdditionalFileURL($filePath);

    return $attribute . $open_quote . '//' . self::$STATIC_DOMAIN_URI . $filePath . $params . $close_quote;
  }
}