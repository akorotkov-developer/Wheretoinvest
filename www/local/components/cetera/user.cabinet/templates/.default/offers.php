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

$APPLICATION->SetTitle("Предложения для " . $name . " лиц");
$APPLICATION->AddChainItem("Предложения для " . $name . " лиц");

if (defined("NO_LEGAL") && $type == "27"):?>
    <div class="content__title_small">Раздел временно не работает</div>
    <? return false; ?>
<? endif;

$hblock = new Cetera\HBlock\SimpleHblockObject(3);
$list = $hblock->getList(Array("filter" => Array("UF_USER_ID" => intval($USER->GetID()), "UF_TYPE" => $type)));
while ($el = $list->fetch()) {
    $arResult["ITEMS"][] = $el;
}
?>

<? if (!empty($_SESSION["SUCCESS"])): ?>
    <div data-alert class="alert-box success radius"><?= $_SESSION["SUCCESS"] ?><a href="#"
                                                                                   class="close">&times;</a>
    </div>
    <? unset($_SESSION["SUCCESS"]); ?>
<? endif; ?>

<div class="row">
    <div class="columns invest">
        <? if (count($arResult["ITEMS"])): ?>
            <div class="invest__head">
                <div class="invest__title">Наименование предложения</div>
                <div class="invest__region">Дата обновления</div>
            </div>
            <div class="invest__body">
                <? foreach ($arResult["ITEMS"] as $arItem): ?>
                    <a href="edit/<?= $arItem["ID"] ?>/">
                        <div class="invest__row">
                            <div class="invest__deposite"><?= $arItem["UF_NAME"] ?></div>
                            <div
                                class="invest__place"><?= !empty($arItem["UF_UPDATED"]) ? CIBlockFormatProperties::DateFormat("d M Y в H:i", strtotime($arItem["UF_UPDATED"])) : ""; ?></div>
                        </div>
                    </a>
                <? endforeach; ?>
            </div>
        <? endif; ?>
        <a href="add/" class="sentence">создать предложение</a>
    </div>
</div>
