<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?
global $USER;
global $APPLICATION;
if (intval($_REQUEST["id"]) > 0 && check_bitrix_sessid() && $_REQUEST["ajax"] == "Y") {
    $arResult = Array();
    $hash = $APPLICATION->get_cookie("USER_HASH");
    if (empty($hash))
        $hash = md5(rand(1111111, 99999999) . date("c"));

    $APPLICATION->set_cookie("USER_HASH", $hash);

    $hblock = new \Cetera\HBlock\SimpleHblockObject(10);
    if ($USER->IsAuthorized())
        $list = $hblock->getList(Array("filter" => Array("UF_USER" => $USER->GetID(), "UF_OFFER" => intval($_REQUEST["id"]))));
    else
        $list = $hblock->getList(Array("filter" => Array("UF_USER_HASH" => $hash, "UF_OFFER" => intval($_REQUEST["id"]))));

    $id = 0;
    $val = 1;
    if ($el = $list->fetch()) {
        $id = $el["ID"];
        $val = !empty($el["UF_FAVORITE"]) ? 0 : 1;
    }

    $arFields = Array(
        "UF_USER" => $USER->IsAuthorized() ? $USER->GetID() : "",
        "UF_OFFER" => intval($_REQUEST["id"]),
        "UF_FAVORITE" => $val,
        "UF_USER_HASH" => $hash,
    );

    if (!empty($id)) {
        $hblock->update($id, $arFields);
    } else {
        $hblock->add($arFields);
    }

    if ($USER->IsAuthorized()) {
        //Удаляем дубли
        $listID = Array();
        $removeArray = Array();
        $list = $hblock->getList(Array("filter" => Array("UF_USER" => $USER->GetID()), "order" => Array("ID" => "DESC")));
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
    }

    $arResult["SUCCESS"] = "Y";
    echo json_encode($arResult);
} elseif (check_bitrix_sessid() && $_REQUEST["ajax"] == "Y" && $_REQUEST["action"] == "getList") {
    $arResult = Array();
    $hash = $APPLICATION->get_cookie("USER_HASH");
    if (empty($hash))
        $hash = md5(rand(1111111, 99999999) . date("c"));

    $APPLICATION->set_cookie("USER_HASH", $hash);

    $hblock = new \Cetera\HBlock\SimpleHblockObject(10);
    if ($USER->IsAuthorized())
        $list = $hblock->getList(Array("filter" => Array("UF_USER" => $USER->GetID())));
    else
        $list = $hblock->getList(Array("filter" => Array("UF_USER_HASH" => $hash)));

    while ($el = $list->fetch()) {
        $arResult["LIST"][$el["UF_OFFER"]] = $el["UF_FAVORITE"];
    }
    
    $arResult["SUCCESS"] = "Y";
    echo json_encode($arResult);
}

?>