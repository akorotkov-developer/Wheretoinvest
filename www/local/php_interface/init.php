<?php

/**
 * composer autoloader
 * конфигурация - ./include/composer.json
 * после внесения изменений в конфигурацию выполнить php composer.phar install
 * @link https://getcomposer.org/
 */
require(__DIR__ . "/../include/vendor/autoload.php");

/**
 * инициализация приложения
 */
Wic\Application::init();