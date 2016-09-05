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
 */
$eventManager->addEventHandler("iblock", "OnIBlockPropertyBuildList", array("Cetera\\UserType\\CUserTypeBool", "GetIBlockPropertyDescription"), false, 100);
// добавляем тип для главного модуля
$eventManager->AddEventHandler("main", "OnUserTypeBuildList", array("Cetera\\UserType\\CUserTypeBool", "GetUserTypeDescription"), false, 100);

/**
 * Пользовательское свойство типа "Строка с описанием"
 */
$eventManager->AddEventHandler("main", "OnUserTypeBuildList", array("Cetera\\UserType\\CUserTypeStrDesc", "GetUserTypeDescription"), false, 100);

class UserEx
{
    public function OnBeforeUserRegister(&$arFields)
    {
        global $USER;
        if (empty($arFields["ID"]) || (!empty($arFields["ID"]) && $arFields["ID"] != "1")) {
            $arFields["CONFIRM_PASSWORD"] = $arFields["PASSWORD"];
            $arFields["LOGIN"] = $arFields["EMAIL"];

            if (!$USER->IsAuthorized()) {
                $filter = Array("EMAIL" => $arFields["LOGIN"]);
                $rsUsers = \CUser::GetList(($by = "ID"), ($order = "asc"), $filter);
                if ($user = $rsUsers->GetNext()) {
                    $GLOBALS['APPLICATION']->ThrowException('Пользователь с таким e-mail (' . $arFields["EMAIL"] . ') уже существует.');
                    return false;
                }
            }

            if (!empty($_REQUEST["FROM_PUBLIC"])) {
                if (empty($_REQUEST["partner"])) {
                    $arFields["PERSONAL_GENDER"] = $_REQUEST["PERSONAL_GENDER"];
                    if (!empty($_REQUEST["UF_BIRTHDAY"]) && !preg_match("#\d{4,4}#is", $_REQUEST["UF_BIRTHDAY"])) {
                        $GLOBALS['APPLICATION']->ThrowException('Некорректно указан год рождения.');
                        return false;
                    }
                    $arFields["UF_BIRTHDAY"] = $_REQUEST["UF_BIRTHDAY"];
                    $arFields["NAME"] = $_REQUEST["NAME"];
                    $arFields["UF_TYPE"] = 1;
                } else {
                    $arFields["GROUP_ID"] = Array(6);
                    $arFields["UF_TYPE"] = 2;
                    $arFields["NAME"] = $_REQUEST["NAME"];
                    $arFields["PERSONAL_PHONE"] = $_REQUEST["PERSONAL_PHONE"];
                    $arFields["WORK_COMPANY"] = $_REQUEST["WORK_COMPANY"];
                }
            }
        }
    }

    public function OnAfterUserRegister(&$arFields)
    {
    }

    function OnBeforeUserLogin($arFields)
    {
        $filter = Array("EMAIL" => $arFields["LOGIN"]);
        $rsUsers = \CUser::GetList(($by = "LAST_NAME"), ($order = "asc"), $filter);
        if ($user = $rsUsers->GetNext()) {
            $arFields["LOGIN"] = $user["LOGIN"];

            if ($user["ACTIVE"] !== "Y") {
                $GLOBALS['APPLICATION']->ThrowException('Ваш аккаунт заблокирован или удален. Для восстановления доступа обратитесь к администрации сайта.');
                return false;
            }
        }
    }

    function OnAfterUserUpdate(&$arFields)
    {
        if ($arFields["RESULT"]) {
            if (in_array(PARTNER_GROUP, CUser::GetUserGroup($arFields["ID"]))) {
                $obCache = new CPHPCache();
                $obCache->CleanDir("/offers/");
            }
        }
    }
}

$eventManager->addEventHandler("main", "OnBeforeUserLogin", array("UserEx", "OnBeforeUserLogin"), false, 100);
$eventManager->addEventHandler("main", "OnBeforeUserRegister", array("UserEx", "OnBeforeUserRegister"), false, 100);
$eventManager->addEventHandler("main", "OnAfterUserRegister", array("UserEx", "OnAfterUserRegister"), false, 100);
$eventManager->addEventHandler("main", "OnAfterUserUpdate", array("UserEx", "OnAfterUserUpdate"), false, 100);
