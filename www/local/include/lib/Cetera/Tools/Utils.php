<?php
/**
 * Utils
 */
namespace Cetera\Tools;

class Utils
{
  /**
   * Аналог json_encode
   * Отличие в том, что русские символы не транслируются в \u000, а остаются читаемыми
   *
   * @see CUtil::PhpToJSObject()
   * @param mixed $data
   * @return string
   * @throws \Exception
   */
  public static function toJSON($data)
  {
    if(defined('JSON_UNESCAPED_UNICODE'))
      return json_encode($data, JSON_UNESCAPED_UNICODE);

    if(!is_array($data))
      return json_encode($data);

    $isArray = true;
    $keys = array_keys($data);
    $prevKey = -1;

    // Необходимо понять — перед нами список или ассоциативный массив.
    foreach($keys as $key)
      if(!is_numeric($key) || $prevKey + 1 != $key)
      {
        $isArray = false;
        break;
      }
      else
        $prevKey++;

    unset($keys);
    $items = array();

    foreach($data as $key => $value)
    {
      $item = (!$isArray ? "\"$key\":" : '');

      if(is_array($value))
        $item .= self::toJSON($value);
      elseif(is_null($value))
        $item .= 'null';
      elseif(is_bool($value))
        $item .= $value ? 'true' : 'false';
      elseif(is_string($value))
        $item .= '"' . preg_replace('%([\\x00-\\x1f\\x22\\x5c])%e', 'sprintf("\\\\u%04X", ord("$1"))', $value) . '"';
      elseif(is_numeric($value))
        $item .= $value;
      else
        throw new \Exception('Wrong argument.');

      $items[] = $item;
    }

    return ($isArray ? '[' : '{') . implode(',', $items) . ($isArray ? ']' : '}');
  }

  /**
   * Склоняет существительное в зависимости от числительного идущего перед ним.
   * Пример использования:
   * 1. Cetera\Utils::pluralForm($n, "письмо", "письма", "писем", 'письма отсутствуют')
   * 2. Cetera\Utils::pluralForm($n, array("письмо", "письма", "писем", 'письма отсутствуют'));
   *
   * @param $n int число
   * @param $normative string Именительный падеж слова ИЛИ массив
   * @param $singular string Родительный падеж ед. число
   * @param $plural string Множественное число
   * @param $zero string форма слова, если $n - ноль, optional
   * @param string $format
   * @return string
   */
  function pluralForm($n, $normative, $singular = null, $plural = null, $zero = null, $format = null)
  {
    // массив существительных
    if(is_array($normative))
    {
      if($n == 0 && count($normative) == 4)
        return end($normative);

      $cases = array(
        2,
        0,
        1,
        1,
        1,
        2
      );

      $form = $normative[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]];

      if(!is_null($format))
      {
        return sprintf($format, $n, $form);
      }

      return $form;
    }

    if($n == 0 && !is_null($zero))
      return $zero;

    $number = abs($n) % 100;
    $n1 = $number % 10;
    if($number > 10 && $number < 20)
      $form = $plural;
    elseif($n1 > 1 && $n1 < 5)
      $form = $singular;
    elseif($n1 == 1)
      $form = $normative;
    else
      $form = $plural;

    if(!is_null($format))
    {
      return sprintf($format, $n, $form);
    }

    return $form;
  }

  /**
   * @param $var
   * @return mixed
   */
  public static function htmlspecialchars($var)
  {
    if(is_numeric($var))
      return $var;
    elseif(is_array($var))
      return filter_var_array($var, FILTER_SANITIZE_SPECIAL_CHARS);

    return filter_var($var, FILTER_SANITIZE_SPECIAL_CHARS);
  }

  /**
   * Получить уникальный код 36 символов.
   *
   * @link http://ru.wikipedia.org/wiki/GUID
   * @example BBE69C73-6105-4144-9AAE-9DDC8B4F1E39
   * @return string
   */
  public static function getUniqueId()
  {
    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
  }


}
