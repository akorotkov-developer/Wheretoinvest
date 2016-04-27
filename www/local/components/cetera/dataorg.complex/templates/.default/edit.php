<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


$APPLICATION->IncludeComponent(
    "cetera:dataorg.edit",
    "",
    Array(

        "HIGHLOAD_ID" => $arParams["HIGHLOAD_ID"],
        "VARIABLE_ALIASES"=>$arParams["VARIABLE_ALIASES"]
    ),
    $component
);?>