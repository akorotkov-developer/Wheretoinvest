<?php
/**
 * Exception
 */
namespace Cetera\Exception;

class Exception extends \Exception
{

  protected $arMessage = array();

  public function __construct($message = '', $code = 0, \Exception $previous = null)
  {

    if(is_array($message))
    {
      $this->addArMessage($message);
      $message = '';
    }

    parent::__construct($message, $code, $previous);
  }

  private function addArMessage($message)
  {
    $this->arMessage = $message;
  }

  /**
   * 
   * @return string
   */
  public function getMsg()
  {
    if(!empty($this->arMessage))
    {
      $mess = '';
      foreach($this->arMessage as $message)
      {
        $mess .= $message;
      }

      return $mess;
    }

    return $this->message;
  }
}
