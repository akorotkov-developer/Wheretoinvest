<?php
/**
 * Права доступа
 */

namespace Cetera\User;

use Cetera\Tools\DIContainer;

abstract class Rights
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @param User $user пользователь, относительно которого рассматриваются все права
     */
    function __construct(User $user = null)
    {
        if(is_null($user))
            $user = DIContainer::$DIC['User'];

        $this->user = $user;
    }


} 