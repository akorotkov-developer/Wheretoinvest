<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>

<?
global $USER;
if (!is_object($USER)) $USER = new CUser;
if (check_bitrix_sessid() && isset($_REQUEST["ajax"]) && !empty($_REQUEST["action"]) && $USER->IsAuthorized() && is_array($_REQUEST["method"]) && count($_REQUEST["method"])) {
    $arResult = [];
    switch (trim($_REQUEST["action"])) {
        case "changeMethods":
            $hblock = new \Cetera\HBlock\SimpleHblockObject(6);
            $list = $hblock->getList(Array("filter" => Array("UF_USER" => $USER->GetID())));
            while ($el = $list->fetch()) {
                $arResult["METHODS"][$el["UF_METHOD"]] = $el;
            }

            foreach ($_REQUEST["method"] as $methodID => $val) {
                $id = 0;
                $arFields = Array();
                if (!empty($arResult["METHODS"][$methodID])) {
                    $id = $arResult["METHODS"][$methodID]["ID"];
                    unset($arResult["METHODS"][$methodID]["ID"]);
                    $arFields = $arResult["METHODS"][$methodID];
                    $arFields["UF_SORT"] = $val["sort"];
                    $arFields["UF_ACTIVE"] = !empty($val["active"]) ? 23 : "";

                } else {
                    $arFields = Array(
                        "UF_USER" => $USER->GetID(),
                        "UF_METHOD" => $methodID,
                        "UF_ACTIVE" => !empty($val["active"]) ? 23 : "",
                        "UF_SORT" => $val["sort"]
                    );
                }

                if (!empty($id)) {
                    if ($hblock->update($id, $arFields)) {
                        $arResult["SUCCESS"] = "Данные успешно изменены.";
                    } else {
                        $arResult["ERROR"] = "Ошибка сохранения данных.";
                    }
                } else {
                    if ($hblock->add($arFields)) {
                        $arResult["SUCCESS"] = "Данные успешно изменены.";
                    } else {
                        $arResult["ERROR"] = "Ошибка сохранения данных.";
                    }
                }
            }
            $obCache = new CPHPCache();
            $obCache->CleanDir("/user_methods/user_method_" . $USER->GetID());
            $obCache->CleanDir("/offers/users/" . $USER->GetID());
            $obCache->CleanDir("/offers/" . $USER->GetID());

            break;
        default:
            die();
    }

    echo json_encode($arResult);
}

?>
