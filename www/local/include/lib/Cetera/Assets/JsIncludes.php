<?php


namespace Cetera\Assets;


use Bitrix\Main\Page\Asset;
use CJSCore;

/**
 * Class JsIncludes
 *
 * @package Cetera\Assets
 */
class JsIncludes
{

  /**
   * @var JsIncludes
   */
  protected static $instance;

  /**
   * @see Config::$jsIncludes
   * @var array
   */
  private $files = array();

  private $pathVars = array('#SITE_TEMPLATE_PATH#' => SITE_TEMPLATE_PATH);

  /**
   * @var array подключенные файлы
   */
  private $includedFiles = array();

  private function __construct()
  {
  }

  private function __clone()
  {
  }

  /**
   * @param array $coreToFooter
   * @return JsIncludes
   */
  public static function getInstance(array $coreToFooter = array())
  {
    if(null === self::$instance)
    {
      self::$instance = new self();

      self::$instance
        ->registerModule()
        ->bxJsToBody($coreToFooter);
    }

    return self::$instance;
  }

  /**
   * @return $this
   */
  private function registerModule()
  {
    $this->files = Config::$jsIncludes;

    return $this;
  }

  /**
   * @todo сейчас не работает moveJs()
   * @see bitrix/modules/main/jscore.php
   * @param $coreToFooter
   * @return $this
   */
  private function bxJsToBody($coreToFooter)
  {
    /*if(!empty($coreToFooter))
    {
      foreach($coreToFooter as $module)
      {
        Asset::getInstance()->moveJs($module);
      }
    }*/

    return $this;
  }

  /**
   * Подключение файлов
   *
   * @param $files array|string "moduleName, moduleName2.submodule.file, file5, moduleName2.file2" OR array('moduleName', 'moduleName2.submodule.file', 'file5', 'moduleName2.file2')
   * @return $this
   */
  public function includeFiles($files)
  {
    if(is_string($files) && strlen($files))
    {
      $files = explode(',', $files);
    }

    if(is_array($files) && !empty($files))
    {
      foreach($files as $file)
      {
        $file = trim($file);
        if(!strlen($file))
          continue;

        if($this->isFileAliasExists($file))
          $this->includedFiles[$file] = $file;
      }

      return $this;
    }

    return $this;
  }

  private function isFileAliasExists($fileAlias)
  {
    static $filesKeys = null;

    if(is_null($filesKeys))
      $filesKeys = array_keys($this->files);

    foreach($filesKeys as $fa)
    {
      // ищем целое название article.edit не подходит для article.edits.file1
      if(strpos($fa, $fileAlias) === 0 && ($fa == $fileAlias || substr($fa, strlen($fileAlias), 1) == '.'))
        return true;
    }

    return false;
  }

  public function getIncludes()
  {
    $filesToShow = array();
    $toShowStr = '';

    if(empty($this->includedFiles))
      return $toShowStr;

    foreach($this->includedFiles as $incAlias => $incFile)
    {
      foreach($this->files as $fileAlias => $file)
      {
        if(strpos($fileAlias, $incFile) === 0 && ($fileAlias == $incFile || substr($fileAlias, strlen($incFile), 1) == '.'))
        {
          // перезаписать данный файл, тогда он будет ниже в массиве.
          if(isset($filesToShow[$file]))
            unset($filesToShow[$file]);

          $filesToShow[$file] = $this->replaceTpl($file);
        }
      }
    }

    if(!empty($filesToShow))
      foreach($filesToShow as $fileToShow)
        $toShowStr .= "<script src=\"" . $fileToShow . "\" type=\"text/javascript\"></script>\n";

    return $toShowStr;
  }

  private function replaceTpl($str)
  {
    return str_replace(array_keys($this->pathVars), $this->pathVars, $str);
  }

  /**
   * Добавляет переменную пути до файлов
   *
   * @param $var '#PATH_TO_FILES#'
   * @param $value '/path/to/files'
   * @return $this
   */
  public function addPathVar($var, $value)
  {
    $this->pathVars[$var] = $value;

    return $this;
  }
}