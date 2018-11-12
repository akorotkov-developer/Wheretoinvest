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

// при создании нового отзыва отсылать письмо
AddEventHandler('iblock', 'OnAfterIBlockElementAdd', 'IBElementCreateAfterHandler');
function IBElementCreateAfterHandler(&$arFields) {

    // при создании нового отзыва отошлем админу письмо
    if($arFields['IBLOCK_ID'] == 9) {
        $EVENT_TYPE = 'ADD_REVIEW'; // тип почтового шаблона

        $arMailFields['ID'] = $arFields['ID'];
        $arMailFields['NAME'] = $arFields['NAME'];
        $arMailFields['PREVIEW_TEXT'] = $arFields['PREVIEW_TEXT'];
        $arMailFields['AUTHOR'] = !empty($arFields['PROPERTY_VALUES']["1"]) ? $arFields['PROPERTY_VALUES']["1"] : "Аноним";
        $arMailFields['RATING'] = !empty($arFields['PROPERTY_VALUES']["2"]) ? $arFields['PROPERTY_VALUES']["2"] : "Без оценки";

        CEvent::Send($EVENT_TYPE, "s1", $arMailFields);
    }

}

$date = date('d.m.Y');
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/logs/".$date.".log");