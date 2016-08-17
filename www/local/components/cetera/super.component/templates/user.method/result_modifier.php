<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

// component text here

if (!CModule::IncludeModule("highloadblock"))
    return false;

if (!$USER->IsAuthorized())
    return false;

$arResult["METHODS"] = getContainer("userMethod");

// saving template name to cache array
$arResult["__TEMPLATE_FOLDER"] = $this->__folder;

// writing new $arResult to cache file
$this->__component->arResult = $arResult;