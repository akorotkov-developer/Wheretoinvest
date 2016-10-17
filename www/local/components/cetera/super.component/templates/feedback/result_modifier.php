<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

global $USER;

// component text here
$arResult["FORM_FIELDS"] = Array(
    "MISTAKE" => Array(
        "TYPE" => "TEXTAREA",
        "VALUE" => "",
        "NO_LABEL" => "Y",
        "REQUIRED" => "Y",
        "INPUT_CLASS" => "i-form__textarea_large"
    ),
    "COMMENT" => Array(
        "TYPE" => "TEXTAREA",
        "TITLE" => "Опишите проблему",
        "REQUIRED" => "Y"
    ),
    "EMAIL" => Array(
        "TYPE" => "EMAIL",
        "TITLE" => "Email для связи",
        "REQUIRED" => "Y",
        "VALUE" => is_object($USER) && $USER->IsAuthorized() ? $USER->GetEmail() : ""
    )
);


// saving template name to cache array
$arResult["__TEMPLATE_FOLDER"] = $this->__folder;

// writing new $arResult to cache file
$this->__component->arResult = $arResult;