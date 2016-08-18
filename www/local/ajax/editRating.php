<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>

<?
global $USER;
if (!is_object($USER)) $USER = new CUser;
if (check_bitrix_sessid() && isset($_REQUEST["ajax"]) && !empty($_REQUEST["action"]) && $USER->IsAuthorized()) {
    $arResult = [];
    switch (trim($_REQUEST["action"])) {
        case "changeRating":
            $hblock = new \Cetera\HBlock\SimpleHblockObject(7);
            $list = $hblock->getList(Array("filter" => Array("UF_USER" => $USER->GetID())));
            while ($el = $list->fetch()) {
                $arResult["RATINGS"][$el["ID"]] = $el;
            }

            foreach ($_REQUEST["rating"] as $ratingID => $val) {
                $id = 0;
                $arFields = Array();
                if (!empty($arResult["RATINGS"][$ratingID])) {
                    $id = $arResult["RATINGS"][$ratingID]["ID"];
                    unset($arResult["RATINGS"][$ratingID]["ID"]);
                    $arFields = $arResult["RATINGS"][$ratingID];
                    unset($arResult["RATINGS"][$ratingID]);

                    $arFields["UF_RATING"] = $val["rating"];
                    $arFields["UF_AGENCY"] = $val["agency"];
                    $arFields["UF_DATE"] = !empty($val["date"]) ? date("d.m.Y", strtotime($val["date"])) : "";
                } else {
                    $arFields = Array(
                        "UF_USER" => $USER->GetID(),
                        "UF_RATING" => $val["rating"],
                        "UF_AGENCY" => $val["agency"],
                        "UF_DATE" => !empty($val["date"]) ? date("d.m.Y", strtotime($val["date"])) : ""
                    );
                }

                if (!empty($id)) {
                    if (!$hblock->update($id, $arFields)) {
                        $arResult["ERROR"] = "Ошибка сохранения данных.";
                    }
                } else {
                    if (!$hblock->add($arFields)) {
                        $arResult["ERROR"] = "Ошибка сохранения данных.";
                    }
                }
            }

            foreach ($arResult["RATINGS"] as $ratingID => $val) {
                $hblock->delete($ratingID);
            }

            $arResult["SUCCESS"] = "Данные успешно изменены.";
            $_SESSION["SUCCESS"] = "Данные успешно изменены.";

            break;
        default:
            die();
    }

    echo json_encode($arResult);
}

?>
