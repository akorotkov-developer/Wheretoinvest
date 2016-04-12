<?php


namespace Cetera\Tools;


class Uri
{
  /**
   * Работает аналогично CMain::GetCurPageParam(), но умеет удалять параметры, являющиеся массивом
   * @see CMain::GetCurPageParam()
   * @param string $strParam
   * @param array $arParamKill
   * @param null $get_index_page
   * @param bool $uri
   * @return bool|mixed|string
   */
  public static function GetCurPageParam($strParam = '', $arParamKill = array(), $get_index_page = null, $uri = false)
  {

    if(null === $get_index_page)
    {
      if(defined('BX_DISABLE_INDEX_PAGE'))
        $get_index_page = !BX_DISABLE_INDEX_PAGE;
      else
        $get_index_page = true;
    }

    $sUrlPath = GetPagePath($uri, $get_index_page);
    $strNavQueryString = Uri::DeleteParam($arParamKill, $uri);

    if($strNavQueryString != '' && $strParam != '')
      $strNavQueryString = '&' . $strNavQueryString;

    if($strNavQueryString == '' && $strParam == '')
      return $sUrlPath;
    else
      return $sUrlPath . '?' . $strParam . $strNavQueryString;
  }

  /**
   * Работает аналогично битриксовой \DeleteParam(), но умеет удалять параметры, являющиеся массивом
   * @see DeleteParam()
   * @param $arParam
   * @param bool $uri
   * @return mixed|string
   */
  public static function DeleteParam($arParam, $uri = false)
  {
    $get = array();
    if($uri && ($qPos = strpos($uri, '?')) !== false)
    {

      $queryString = substr($uri, $qPos + 1);
      parse_str($queryString, $get);
      unset($queryString);
    }

    if(sizeof($get) < 1)
      $get = $_GET;

    if(sizeof($get) < 1)
      return '';

    if(sizeof($arParam) > 0)
    {
      foreach($arParam as $param)
      {
        $search = &$get;
        $param = (array)$param;
        $lastIndex = sizeof($param) - 1;

        foreach($param as $c => $key)
        {
          if(array_key_exists($key, $search))
          {
            if($c == $lastIndex)
              unset($search[$key]);
            else
              $search = &$search[$key];
          }
        }
      }
    }

    return str_replace(array(
      '%5B',
      '%5D'
    ), array(
      '[',
      ']'
    ), http_build_query($get));
  }

  /**
   * Добавление query параметров к url
   *
   * @example Utils::addParamToUrl('/warranty/warranty/?find=1', array('find' => array('search', 2), 'filter' => 'YES'))
   *
   * @param $url string url
   * @param array $addParams параметры
   * @return string
   */
  public static function addParamToUrl($url, array $addParams)
  {
    if(!is_array($addParams))
      return $url;

    $info = parse_url($url);

    $query = array();

    if($info['query'])
    {
      parse_str($info['query'], $query);
    }

    if(!is_array($query))
      $query = array();

    $params = array_merge($query, $addParams);

    $result = '';

    if($info['scheme'])
      $result .= $info['scheme'] . ':';

    if($info['host'])
      $result .= '//' . $info['host'];

    if($info['path'])
      $result .= $info['path'];

    if($params)
      $result .= '?' . http_build_query($params);

    return $result;
  }
}