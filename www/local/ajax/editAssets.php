<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>

<?
global $USER;
if (!is_object($USER)) $USER = new CUser;
if (check_bitrix_sessid() && isset($_REQUEST["ajax"]) && !empty($_REQUEST["action"]) && $USER->IsAuthorized()) {
    $arResult = [];
    switch (trim($_REQUEST["action"])) {
        case "changeAssets":
            $avaliableFields = Array(
                "UF_CAPITAL_ASSETS",
                "UF_CAPITAL",
                "UF_ASSETS",
            );

            $arFields = Array();

            foreach ($_REQUEST as $key => $value) {
                if (in_array($key, $avaliableFields)) {
                    $arFields[$key] = $value;
                    $arResult["NEW"][$key] = $value;
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
        default:
            die();
    }

    echo json_encode($arResult);
}

?>
