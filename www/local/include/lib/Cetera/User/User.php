<?php
/**
 * Пользователь
 * Класс- надстройка
 */
namespace Cetera\User;

use Cetera\Exception\Exception;

/**
 * Class User
 * @example
 * <code>
 *  $usr = new Cetera\User($USER);
 *  // OR
 *  $usr = Cetera\User::fromId(1)
 *
 *  echo $usr['PERSONAL_PHOTO'];
 *  echo $user->GetFullName();
 * </code>
 * @package Cetera
 */
class User extends \CUser implements \ArrayAccess
{

  private $id = 0,
    $isCurrentUser = false,
    $USER,
    $userData = null,
    $userGroups = array();

  public function __construct(\CUser $USER = null, $id = null)
  {
    if (!is_null($USER))
    {
      $this->isCurrentUser = true;
      $this->USER = $USER;
      $this->id = parent::GetId();
    }
    elseif (intval($id) > 0)
    {
      $this->id = $id;

      $userId = $this->getUserData('ID');
      if ($userId != $id)
        $this->throwException("Invalid userId");
    } else
      $this->throwException("CUser object or user ID required");

    $this->userData = $this->getUserData();
  }

  public function __get($var)
  {
    if (strpos($var, 'printable_') === 0)
    {
      $varName = substr($var, strlen('printable_'));
      switch ($varName)
      {
        default:
          return $this->getUserData($varName);
      }
    }

    return $this->getUserData($var);
  }

  public function getID()
  {
    return $this->id;
  }

  /**
   * Получить данные пользователя
   * @param null $field возвращает значение указанного поля
   * @return array
   */
  public function getUserData($field = null)
  {
    if (is_null($this->userData))
    {
      $this->userData = \CUser::GetByID($this->GetID())->Fetch();
    }

    if (!is_null($field))
      return $this->userData[$field];
    else
      return $this->userData;
  }

  /**
   * создать пользователя по id
   * @param $userId
   * @return User
   */
  public static function fromId($userId)
  {
    return new self(null, $userId);
  }

  function GetFirstName()
  {
    if ($this->isCurrentUser)
      return parent::GetFirstName();

    return $this->getUserData("FIRST_NAME");
  }

  function GetLastName()
  {
    if ($this->isCurrentUser)
      return parent::GetLastName();

    return $this->getUserData("LAST_NAME");
  }

  function GetSecondName()
  {
    if ($this->isCurrentUser)
      return parent::GetSecondName();

    return $this->getUserData("SECOND_NAME");
  }

  public function GetFullName()
  {
    if ($this->isCurrentUser)
      return parent::GetFullName();

    return $this->getUserData("NAME") . (strlen($this->getUserData("NAME")) <= 0 || strlen($this->getUserData("LAST_NAME")) <= 0 ? "" : " ") . $this->getUserData("LAST_NAME");
  }

  public function GetEmail()
  {
    if ($this->isCurrentUser)
      return parent::GetEmail();

    return $this->getUserData("EMAIL");
  }

  function GetLogin()
  {
    if ($this->isCurrentUser)
      return parent::GetLogin();

    return $this->getUserData("LOGIN");
  }

  /**
   * Обновить данные о пользователе
   * @param $params
   * @return bool
   */
  public function Update($params)
  {
    $user = new \CUser;

    $result = $user->Update($this->getID(), $params);

    if ($result)
    {
      $this->userData = null;
      return $result;
    }

    $this->throwException($user->LAST_ERROR);

    return false;
  }

  /**
   * Whether a offset exists
   * @param mixed $offset
   * @return boolean true on success or false on failure.
   * The return value will be casted to boolean if non-boolean was returned.
   */
  public function offsetExists($offset)
  {
    try
    {
      $this->getUserData($offset);
    }
    catch (Exception $e)
    {
      return false;
    }

    return true;
  }

  /**
   * Offset to retrieve
   * @param mixed $offset
   * @return mixed Can return all value types.
   */
  public function offsetGet($offset)
  {
    return $this->getUserData($offset);
  }

  /**
   * @param mixed $offset
   * @param mixed $value
   * @return void
   */
  public function offsetSet($offset, $value)
  {
    $this->userData[$offset] = $value;
  }

  /**
   * Offset to unset
   * @link http://php.net/manual/en/arrayaccess.offsetunset.php
   * @param mixed $offset
   * @return void
   */
  public function offsetUnset($offset)
  {
    unset($this->userData[$offset]);
  }

  public function getGroups()
  {
    if (empty($this->userGroups))
      $this->userGroups = \CUser::GetUserGroup($this->id);

    return $this->userGroups;
  }

  /**
   * @param $args
   * @throws Exception
   */
  protected function throwException($args)
  {
    throw new Exception($args);
  }


}