<?php
/**
 * Все обработчики событий на сайте
 */
$eventManager = \Bitrix\Main\EventManager::getInstance();

/**
 * Удалить из GET параметр SHOWALL (отображает все элементы на 1 странице)
 * почти всегда это приводит к полному зависанию сервака
 */
$eventManager->addEventHandler("main", "OnBeforeProlog", "clearShowAll", false, 100);

/**
 * Пользовательское свойство типа "Логическое"
 *
 */
$eventManager->addEventHandler("iblock", "OnIBlockPropertyBuildList", array("Cetera\\UserType\\CUserTypeBool", "GetIBlockPropertyDescription"), false, 100);
// добавляем тип для главного модуля
//$eventManager->AddEventHandler("main", "OnUserTypeBuildList", array("Cetera\\UserType\\CUserTypeBool", "GetUserTypeDescription"), false, 100);
