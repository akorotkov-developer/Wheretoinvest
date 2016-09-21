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

    function OnAfterUserLogin($arFields)
    {
        if ($arFields['USER_ID'] > 0) {
            global $APPLICATION;
            $hash = $APPLICATION->get_cookie("USER_HASH");
            if (!empty($hash)) {
                $hblock = new \Cetera\HBlock\SimpleHblockObject(10);
                $list = $hblock->getList(Array("filter" => Array("UF_USER_HASH" => $hash)));
                while ($el = $list->fetch()) {
                    $hblock->update($el["ID"], Array("UF_USER" => $arFields['USER_ID']));
                }

                //Удаляем дубли
                $listID = Array();
                $removeArray = Array();
                $list = $hblock->getList(Array("filter" => Array("UF_USER" => $arFields['USER_ID']), "order" => Array("ID" => "DESC")));
                while ($el = $list->fetch()) {
                    if (!in_array($el["UF_OFFER"], $listID)) {
                        $listID[$el["UF_OFFER"]] = $el["UF_OFFER"];
                        if ($el["UF_USER_HASH"] !== $hash)
                            $hblock->update($el["ID"], Array("UF_USER_HASH" => $hash));
                    } else {
                        $removeArray[$el["ID"]] = $el["ID"];
                    }
                }
                foreach ($removeArray as $id) {
                    $hblock->delete($id);
                }

                if (count($removeArray)) {
                    $obCache = new CPHPCache();
                    $obCache->CleanDir("/offers/" . $arFields['USER_ID'] . "/");
                }
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

    function OnBeforeUserUpdate(&$arFields)
    {
        if (!empty($arFields["UF_ADD_CASH"])) {
            $rsUser = CUser::GetByID($arFields["ID"]);

            if ($arUser = $rsUser->Fetch()) {
                $cash = floatval($arUser["UF_CASH"]);
                $cash += floatval($arFields["UF_ADD_CASH"]);
                $arFields["UF_CASH"] = $cash;

                global $USER;
                $hblock = new \Cetera\HBlock\SimpleHblockObject(11);
                $arSave = Array(
                    "UF_USER" => $USER->GetID(),
                    "UF_PARTNER" => $arUser["ID"],
                    "UF_SUMM" => floatval($arFields["UF_ADD_CASH"]),
                    "UF_BALANCE" => $cash,
                    "UF_DATE" => date("d.m.Y H:i:s")
                );

                $hblock->add($arSave);
                unset($hblock);
            }

            $arFields["UF_ADD_CASH"] = "";
        }
    }
}

$eventManager->addEventHandler("main", "OnAfterUserLogin", array("UserEx", "OnAfterUserLogin"), false, 100);
$eventManager->addEventHandler("main", "OnBeforeUserLogin", array("UserEx", "OnBeforeUserLogin"), false, 100);
$eventManager->addEventHandler("main", "OnBeforeUserRegister", array("UserEx", "OnBeforeUserRegister"), false, 100);
$eventManager->addEventHandler("main", "OnAfterUserRegister", array("UserEx", "OnAfterUserRegister"), false, 100);
$eventManager->addEventHandler("main", "OnAfterUserUpdate", array("UserEx", "OnAfterUserUpdate"), false, 100);
$eventManager->addEventHandler("main", "OnBeforeUserUpdate", array("UserEx", "OnBeforeUserUpdate"), false, 100);
