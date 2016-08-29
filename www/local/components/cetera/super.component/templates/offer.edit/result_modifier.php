<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

// component text here


if (!CModule::IncludeModule("highloadblock"))
    return false;

if (empty($arParams["TYPE"]))
    return false;

$arParams["COL_CNT"] = intval($arParams["COL_CNT"]) > 0 ? $arParams["COL_CNT"] : 3;

global $USER_FIELD_MANAGER;

$arFields = $USER_FIELD_MANAGER->GetUserFields("HLBLOCK_3");
$obEnum = new CUserFieldEnum;
$arResult["FIELDS"] = Array();

foreach ($arFields as $key => $arField) {
    if ($arField["USER_TYPE_ID"] == "enumeration") {
        $rsEnum = $obEnum->GetList(array(), array("USER_FIELD_ID" => $arField["ID"]));
        while ($arEnum = $rsEnum->GetNext()) {
            $arResult["FIELDS"][$key][$arEnum["ID"]] = $arEnum["VALUE"];
            $arResult["XML_FIELDS"][$key][$arEnum["ID"]] = $arEnum["XML_ID"];
        }
    }
}

$arFields = $USER_FIELD_MANAGER->GetUserFields("HLBLOCK_9");
foreach ($arFields as $key => $arField) {
    if ($arField["USER_TYPE_ID"] == "enumeration") {
        $rsEnum = $obEnum->GetList(array(), array("USER_FIELD_ID" => $arField["ID"]));
        while ($arEnum = $rsEnum->GetNext()) {
            $arResult["FIELDS"][$key][$arEnum["ID"]] = $arEnum["VALUE"];
            $arResult["XML_FIELDS"][$key][$arEnum["ID"]] = $arEnum["XML_ID"];
        }
    }
}

if (CModule::IncludeModule("sale")) {
    $list = \CSaleLocation::GetRegionList();
    while ($el = $list->Fetch()) {
        $arResult["REGIONS"][mb_substr($el["NAME"], 0, 1)][] = $el;
        $arResult["TOTAL_COUNT"]++;
    }

    $list = \CSaleLocation::GetList(Array(), Array("CODE" => Array("0000073738", "0000103664", "0001092542"), "LID" => LANGUAGE_ID));
    while ($el = $list->Fetch()) {
        $el["NAME"] = $el["CITY_NAME"];
        $arResult["REGIONS"][mb_substr($el["NAME"], 0, 1)][] = $el;
        $arResult["TOTAL_COUNT"]++;
    }
}

foreach ($arResult["REGIONS"] as $keyRegion => $region) {
    $nameList = Array();
    foreach ($region as $key => $val) {
        $nameList[$key] = $val["NAME"];
    }
    array_multisort($nameList, SORT_STRING, $arResult["REGIONS"][$keyRegion]);
}

if (!empty($arParams["ID"])) {
    $hblock = new Cetera\HBlock\SimpleHblockObject(3);
    $list = $hblock->getList(Array("filter" => Array("ID" => intval($arParams["ID"]))));
    $find = false;
    if ($el = $list->fetch()) {
        if ($el["UF_USER_ID"] == $USER->GetID())
            $find = true;
        $arResult["ITEM"] = $el;
    }

    if (!$find) {
        $this->__component->AbortResultCache();
        ShowError("Элемент не найден.");
        @define("ERROR_404", "Y");
        CHTTP::SetStatus("404 Not Found");
    }
}

if (intval($arParams["ID"]) > 0) {
    $hblock = new \Cetera\HBlock\SimpleHblockObject(9);
    $list = $hblock->getList(Array("filter" => Array("UF_OFFER" => intval($arParams["ID"])), "order" => Array("UF_SUMM" => "ASC", "UF_DATE_START" => "ASC")));
    while ($el = $list->fetch()) {
        $row = Array($el["UF_DATE_START"]);
        if (!empty($el["UF_DATE_END"]))
            $row[] = $el["UF_DATE_END"];

        $row = implode(" - ", $row);
        $arResult["MATRIX"][$el["UF_CURRENCY"]][number_format($el["UF_SUMM"], 0, ".", " ")][$row] = number_format($el["UF_PERCENT"], 2);
        $arResult["MATRIX_COLS"][$el["UF_CURRENCY"]][$row] = $row;
    }
} else {
    foreach (array_keys($arResult["FIELDS"]["UF_CURRENCY"]) as $currency) {

        $arResult["MATRIX"][$currency] = Array(
            "10 000" => Array(
                "90" => "0.00",
                "180" => "0.00",
                "365" => "0.00",
            ),
            "100 000" => Array(
                "90" => "0.00",
                "180" => "0.00",
                "365" => "0.00",
            )
        );

        $arResult["MATRIX_COLS"][$currency] = Array(
            "90" => "90",
            "180" => "180",
            "365" => "365",
        );
    }
}

// saving template name to cache array
$arResult["__TEMPLATE_FOLDER"] = $this->__folder;

// writing new $arResult to cache file
$this->__component->arResult = $arResult;