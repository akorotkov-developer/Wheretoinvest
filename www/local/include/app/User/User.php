<?php

namespace Wic\User;

use Wic\Exception\Exception;

/**
 * Class User
 * Получение специфичной информации о текущем пользователе
 * @package Wic\User
 */
class User extends \Cetera\User\User
{
    /**
     * Группа Admin
     */
    public function isAdmin()
    {
        return in_array(Config::GROUP_ADMIN, $this->getGroups());
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