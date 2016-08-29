<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?
global $USER;
if ($USER->IsAuthorized() && intval($_REQUEST["id"]) > 0 && check_bitrix_sessid() && $_REQUEST["ajax"] == "Y") {
    $arResult = Array();

    $hblock = new \Cetera\HBlock\SimpleHblockObject(10);
    $list = $hblock->getList(Array("filter" => Array("UF_USER" => $USER->GetID(), "UF_OFFER" => intval($_REQUEST["id"]))));

    $id = 0;
    $val = 1;
    if ($el = $list->fetch()) {
        $id = $el["ID"];
        $val = !empty($el["UF_FAVORITE"]) ? 0 : 1;
    }

    $arFields = Array(
        "UF_USER" => $USER->GetID(),
        "UF_OFFER" => intval($_REQUEST["id"]),
        "UF_FAVORITE" => $val
    );

    if (!empty($id)) {
        $hblock->update($id, $arFields);
    } else {
        $hblock->add($arFields);
    }

    $obCache = new CPHPCache();
    $obCache->CleanDir("/offers/" . $USER->GetID() . "/");

    $arResult["SUCCESS"] = "Y";
    echo json_encode($arResult);
}

?>