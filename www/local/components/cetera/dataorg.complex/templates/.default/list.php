<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


$APPLICATION->IncludeComponent(
    "cetera:dataorg.list",
    "",
    Array(

        "HIGHLOAD_ID" => $arParams["HIGHLOAD_ID"],
        "METHOD"=>$arParams["METHOD"]
    ),
    $component
);?>