<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>

<?
global $USER;
if (!is_object($USER)) $USER = new CUser;
if (check_bitrix_sessid() && isset($_REQUEST["ajax"]) && !empty($_REQUEST["action"]) && $USER->IsAuthorized()) {
    $arResult = [];
    switch (trim($_REQUEST["action"])) {
        case "changeProfile":
            $avaliableFields = Array(
                "NAME",
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
                    case "NAME":
                        $arResult["NEW"][$key] = $val;
                        list($arFields["LAST_NAME"], $arFields["NAME"], $arFields["SECOND_NAME"]) = explode(" ", $val);
                        break;
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
        default:
            die();
    }

    echo json_encode($arResult);
}

?>
