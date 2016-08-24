<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CUser $USER */
global $USER;
/** @global CMain $APPLICATION */
global $APPLICATION;

switch ($arResult["VARIABLES"]["type"]) {
    case "private":
        $name = "физических";
        $type = "26";
        break;
    case "legal":
        $name = "юридических";
        $type = "27";
        break;
    default:
        LocalRedirect("/cabinet/offers/private/");
}

$APPLICATION->SetTitle("Изменить предложение");
$APPLICATION->AddChainItem("Предложения для " . $name . " лиц", preg_replace("#add/#is", "", $APPLICATION->GetCurPage()));
$APPLICATION->AddChainItem("Изменить предложение");

$APPLICATION->IncludeComponent("cetera:super.component", "offer.edit", Array(
    "TYPE" => $type,
    "ID" => $arResult["VARIABLES"]["ID"]
));
?>