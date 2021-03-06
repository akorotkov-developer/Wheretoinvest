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

$APPLICATION->SetTitle("Создать предложение");
$APPLICATION->AddChainItem("Предложения для " . $name . " лиц", preg_replace("#add/#is", "", $APPLICATION->GetCurPage()));
$APPLICATION->AddChainItem("Создать предложение");

if (defined("NO_LEGAL") && $type == "27"):?>
    <div class="content__title_small">Раздел временно не работает</div>
    <? return false; ?>
<? endif;

$APPLICATION->IncludeComponent("cetera:super.component", "offer.edit", Array(
    "TYPE" => $type,
));
?>