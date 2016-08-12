<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

// component text here

$arParams["MODAL_ID"] = !empty($arParams["MODAL_ID"]) ? trim($arParams["MODAL_ID"]) : "saleLocation";
$arParams["COL_CNT"] = !empty($arParams["COL_CNT"]) ? intval($arParams["COL_CNT"]) : 3;
$arParams["EMPTY_NAME"] = !empty($arParams["EMPTY_NAME"]) ? trim($arParams["EMPTY_NAME"]) : "Выберите регион";

$arResult["REGIONS"] = Array();
$arResult["CURRENT_LOC_ID"] = $APPLICATION->get_cookie("CURRENT_LOC_ID");
$arResult["CURRENT_LOC_NAME"] = $APPLICATION->get_cookie("CURRENT_LOC_NAME");
$arResult["TOTAL_COUNT"] = 0;
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


// saving template name to cache array
$arResult["__TEMPLATE_FOLDER"] = $this->__folder;

// writing new $arResult to cache file
$this->__component->arResult = $arResult;