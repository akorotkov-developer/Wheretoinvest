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
                "FULL_NAME",
                "UF_BIRTHDAY",
                "PERSONAL_GENDER",
                "WORK_COMPANY",
                "PERSONAL_PHONE",
                "UF_EXTENSION_NUMBER",
            );

            $arFields = Array();
            foreach ($_REQUEST as $key => $val) {
                if (!in_array($key, $avaliableFields))
                    continue;

                switch ($key) {
                    case "FULL_NAME":
                        list($arFields["LAST_NAME"], $arFields["NAME"], $arFields["SECOND_NAME"]) = explode(" ", $val);
                        $arResult["NEW"]["LAST_NAME"] = $arFields["LAST_NAME"];
                        $arResult["NEW"]["NAME"] = $arFields["NAME"];
                        $arResult["NEW"]["SECOND_NAME"] = $arFields["SECOND_NAME"];
                        break;
                    case "UF_BIRTHDAY":
                        if (!empty($val)) {
                            if (!preg_match("#\d{4,4}#is", $val)) {
                                $arResult["ERROR"] = "Некорректно указан год рождения.";
                                break;
                            }
                            $arFields[$key] = $val;
                            $arResult["NEW"][$key] = $arFields[$key];
                        } else {
                            $arFields[$key] = $val;
                            $arResult["NEW"][$key] = "<span class='req__name'>—</span>";
                        }
                        break;
                    case "UF_EXTENSION_NUMBER":
                        $arFields[$key] = $val;
                        $arResult["NEW"][$key] = $arFields[$key];
                        break;
                    case "PERSONAL_GENDER":
                        $arFields[$key] = $val;
                        $arResult["NEW"][$key] = empty($arFields[$key]) ? "<span class='req__name'>—</span>" : ($arFields[$key] == "M" ? "Мужской" : "Женский");
                        break;
                    default:
                        $arFields[$key] = trim($val);
                        $arResult["NEW"][$key] = !empty($arFields[$key]) ? $arFields[$key] : "<span class='req__name'>—</span>";
                        break;
                }
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
        case "editDetails":
            $avaliableFields = Array(
                "WORK_COMPANY",
                "UF_FULL_WORK_NAME",
                "UF_SHORT_WORK_EN",
                "WORK_LOGO",
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
                    default:
                        $arFields[$key] = trim($val);
                        $arResult["NEW"][$key] = !empty($arFields[$key]) ? $arFields[$key] : "<span class='req__name'>—</span>";
                        break;
                }
            }

            if (!empty($_REQUEST["WORK_LOGO_DEL"])) {
                $arIMAGE = Array();
                $arIMAGE["del"] = "Y";
                $arIMAGE["MODULE_ID"] = "main";
                $arFields["WORK_LOGO"] = $arIMAGE;
            } elseif (!empty($_FILES["WORK_LOGO"]) && empty($_FILES["WORK_LOGO"]["error"])) {
                $arIMAGE = $_FILES["WORK_LOGO"];
                $arIMAGE["del"] = "Y";
                $arIMAGE["MODULE_ID"] = "main";

                $types = array('image/gif', 'image/png', 'image/jpeg');
                if (!in_array($arIMAGE["type"], $types)) {
                    $arResult["ERROR"] = "Неверный тип файла";
                } else {
                    if ($arIMAGE['type'] == 'image/jpeg')
                        $source = imagecreatefromjpeg($arIMAGE['tmp_name']);
                    elseif ($arIMAGE['type'] == 'image/png')
                        $source = imagecreatefrompng($arIMAGE['tmp_name']);
                    elseif ($arIMAGE['type'] == 'image/gif')
                        $source = imagecreatefromgif($arIMAGE['tmp_name']);

                    if (!empty($source)) {
                        $w_src = imagesx($source);
                        $h_src = imagesy($source);

                        if ($w_src > 100 || $h_src > 100) {
                            $arResult["ERROR"] = "Максимальная сторона логотипа 100px";
                        }
                    }
                }

                if (!empty($arIMAGE["name"])) {
                    $arFields["WORK_LOGO"] = $arIMAGE;
                }
            }

            if (empty($arResult["ERRORS"]) && empty($arResult["ERROR"])) {
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
