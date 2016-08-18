<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>

<?
global $USER;
if (!is_object($USER)) $USER = new CUser;
if (check_bitrix_sessid() && isset($_REQUEST["ajax"]) && !empty($_REQUEST["action"]) && $USER->IsAuthorized()) {
    $arResult = [];
    switch (trim($_REQUEST["action"])) {
        case "changeProfile":
            $avaliableFields = Array(
                "LAST_NAME",
                "NAME",
                "SECOND_NAME",
                "PERSONAL_BIRTHDAY",
                "PERSONAL_GENDER",
                "WORK_COMPANY",
                "PERSONAL_PHONE",
            );

            $arFields = Array();
            foreach ($_REQUEST as $key => $val) {
                if (!in_array($key, $avaliableFields))
                    continue;

                switch ($key) {
                    case "PERSONAL_BIRTHDAY":
                        if (!empty($val)) {
                            $arFields[$key] = date("d.m.Y", strtotime($val));
                            $arResult["NEW"][$key] = $arFields[$key];
                        } else {
                            $arFields[$key] = $val;
                            $arResult["NEW"][$key] = "Не задан";
                        }
                        break;
                    case "PERSONAL_GENDER":
                        $arFields[$key] = $val;
                        $arResult["NEW"][$key] = empty($arFields[$key]) ? "Не задан" : ($arFields[$key] == "M" ? "Мужской" : "Женский");
                        break;
                    default:
                        $arFields[$key] = trim($val);
                        $arResult["NEW"][$key] = !empty($arFields[$key]) ? $arFields[$key] : "Не задан";
                        break;
                }
            }

            if (empty($arResult["ERRORS"])) {
                if (count($arFields)) {
                    $cUser = new \CUser();
                    if ($cUser->Update($USER->GetID(), $arFields)) {
                        $arResult["SUCCESS"] = "Данные успешно изменены.";
                    } else {
                        $arResult["ERROR"] = $cUser->LAST_ERROR;
                    }
                }
            }

            break;
        case "editDetails":
            $avaliableFields = Array(
                "WORK_COMPANY",
                "UF_FULL_WORK_NAME",
                "UF_SHORT_WORK_EN",
                "WORK_LOGO",
                "UF_SITE",
                "UF_LICENSE",
                "UF_OGRN",
                "UF_INN",
                "UF_KPP",
                "UF_ACCOUNT",
                "UF_BANK_INFO",
                "UF_BIK",
                "UF_C_ACCOUNT",
            );

            $arFields = Array();
            foreach ($_REQUEST as $key => $val) {
                if (!in_array($key, $avaliableFields))
                    continue;

                switch ($key) {
                    case "UF_SITE":
                        if (!empty($val) && !preg_match("@^(http\:\/\/|https\:\/\/)?([a-z0-9][a-z0-9\-]*\.)+[a-z0-9][a-z0-9\-]*$@i", $val)) {
                            $arResult["ERRORS"][$key] = "Неверная ссылка на сайт";
                            $arResult["ERROR"][] = "Неверная ссылка на сайт";
                        }
                        $arFields[$key] = trim($val);
                        break;
                    default:
                        $arFields[$key] = trim($val);
                        $arResult["NEW"][$key] = !empty($arFields[$key]) ? $arFields[$key] : "Не задан";
                        break;
                }
            }

            if (!empty($_FILES["WORK_LOGO"]) && empty($_FILES["WORK_LOGO"]["error"])) {
                $arIMAGE = $_FILES["WORK_LOGO"];
                $arIMAGE["del"] = "Y";
                $arIMAGE["MODULE_ID"] = "main";
                if (!empty($arIMAGE["name"])) {
                    $arFields["WORK_LOGO"] = $arIMAGE;
                }
            }

            if (empty($arResult["ERRORS"])) {
                if (count($arFields)) {
                    $cUser = new \CUser();
                    if ($cUser->Update($USER->GetID(), $arFields)) {
                        $arResult["SUCCESS"] = "Данные успешно изменены.";
                        $_SESSION["SUCCESS"] = $arResult["SUCCESS"];
                    } else {
                        $arResult["ERROR"] = $cUser->LAST_ERROR;
                    }
                }
            }

            break;
        case "setParticipate":
            $avaliableFields = Array(
                "UF_STATE_PARTICIP",
                "UF_BANK_PARTICIP",
            );

            $type = "UF_" . strtoupper(trim($_REQUEST["type"])) . "_PARTICIP";
            $val = $_REQUEST["val"];

            $arFields = Array();
            if (in_array($type, $avaliableFields)) {
                $arFields[$type] = $val;
            } else {
                $arResult["ERROR"] = "Ошибка сохранения статуса. Неверные входные данные.";
            }

            if (empty($arResult["ERRORS"]) && empty($arResult["ERROR"])) {
                if (count($arFields)) {
                    $cUser = new \CUser();
                    if ($cUser->Update($USER->GetID(), $arFields)) {
                        $arResult["SUCCESS"] = "Данные успешно изменены.";
                    } else {
                        $arResult["ERROR"] = $cUser->LAST_ERROR;
                    }
                }
            }

            break;
        default:
            die();
    }

    echo json_encode($arResult);
}

?>
