<?php
/**
 * Все обработчики событий на сайте
 */
/**
 * Авторизация по email
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
class UserEx
{
    public function OnBeforeUserRegister(&$arFields)
    {

        if (empty($arFields["ID"]) || (!empty($arFields["ID"]) && $arFields["ID"] != "1")) {
            $arFields["CONFIRM_PASSWORD"] = $arFields["PASSWORD"];
            $arFields["LOGIN"] = $arFields["EMAIL"];
            $arFields["GROUP_ID"][] = $_POST["group_id"]; // где id - номер необходимой группы
        }

    }
    public function OnAfterUserRegister(&$arFields)
    {
        print_r($arFields);

    }
    function OnBeforeUserLogin($arFields)
    {
        $filter = Array("EMAIL" => $arFields["LOGIN"]);
        $rsUsers = \CUser::GetList(($by = "LAST_NAME"), ($order = "asc"), $filter);
        if ($user = $rsUsers->GetNext())
            $arFields["LOGIN"] = $user["LOGIN"];
    }
}

$eventManager->addEventHandler("main", "OnBeforeUserLogin", array("UserEx", "OnBeforeUserLogin"), false, 100);
$eventManager->addEventHandler("main", "OnBeforeUserRegister", array("UserEx", "OnBeforeUserRegister"), false, 100);
$eventManager->addEventHandler("main", "OnBeforeUserUpdate", array("UserEx", "OnBeforeUserRegister"), false, 100);
$eventManager->addEventHandler("main", "OnAfterUserRegister", array("UserEx", "OnAfterUserRegister"), false, 100);
