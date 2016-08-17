<?php
/**
 * Конфигурация
 */

namespace Wic;

use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Cetera\Tools;
use Wic\Exception\Exception;
use Wic\User\User;

/**
 * Class Application
 *
 * @package Wic
 */
class Application
{
    /**
     * Инициализация приложения
     *
     * @throws \Bitrix\Main\LoaderException
     * @throws Exception
     */
    public static function init()
    {
        /**
         * Загрузка модулей
         */
        $bxModulesToLoad = array(
            "iblock",
            "highloadblock"
        );
        foreach ($bxModulesToLoad as $module)
            if (!Loader::includeModule($module))
                throw new Exception("Модуль {$module} не был загружен");

        Tools\DIContainer::init();

        /**
         * Application
         */
        global $APPLICATION;
        Tools\DIContainer::$DIC['Application'] = Tools\DIContainer::$DIC->factory(function () use ($APPLICATION) {
            return $APPLICATION;
        });

        Tools\DIContainer::$DIC['userMethod'] = Tools\DIContainer::$DIC->factory(function () {
            return getUserMethods();
        });

        self::initHandlers();
    }

    /**
     * Иницилизация отложенных функций
     */
    public static function initHandlers()
    {
        /**
         * отложенная инициализация некоторых переменных
         */
        EventManager::getInstance()->addEventHandler("main", "OnBeforeProlog", Array(
            "Wic\\Application",
            "deferredInit"
        ), false, 100);
    }

    /**
     * отложенная инициализация
     * на OnBeforeProlog
     */
    public static function deferredInit()
    {
        global $USER;

        /**
         * Пользователь
         */
        Tools\DIContainer::$DIC['User'] = Tools\DIContainer::$DIC->factory(function () use ($USER) {
            return new User($USER);
        });

        /**
         * Include javascript
         */
        Tools\JsIncludes::includeFiles(array(
            'common',
        ));
    }

}