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
$list = $hblock->getList(Array("filter" => Array("UF_USER_ID" => intval($USER->GetID()), "UF_TYPE" => $type), "order" => Array("UF_ACTIVE_START" => "ASC")));
while ($el = $list->fetch()) {
    $today = new \DateTime();
    $today->setTime(0, 0, 0);
    $lastStart = "";

    foreach ($el["UF_ACTIVE_START"] as $key => $val) {
        if (!empty($val) && !empty($el["UF_ACTIVE_END"][$key])) {
            $start = new \DateTime($val->format("d.m.Y"));
            $end = new \DateTime($el["UF_ACTIVE_END"][$key]->format("d.m.Y"));

            $start->setTime(0, 0, 0);
            $end->setTime(0, 0, 0);

            if ($start <= $today && $end >= $today) {
                $el["UF_ACTIVE_DIFF"] = $start->format("d.m.Y") . " - " . $end->format("d.m.Y");
                $arResult["FUTURE"] = false;
                break;
            } elseif ($start > $today) {
                $arResult["FUTURE"] = true;
                $el["UF_ACTIVE_DIFF"] = $start->format("d.m.Y") . " - " . $end->format("d.m.Y");
            } else {
                $arResult["PAST"] = true;
                $el["UF_ACTIVE_DIFF"] = $start->format("d.m.Y") . " - " . $end->format("d.m.Y");
            }
        }
    }
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
    <div class="columns">
        <? if (count($arResult["ITEMS"])): ?>
            <div class="invest__wrapper">
                <div class="invest">
                    <div class="invest__head">
                        <div class="invest__title">Наименование предложения</div>
                        <div class="invest__region">Период публикации</div>
                        <div class="invest__region invest_small-hide">Статус публикации</div>
                        <div class="invest__region"></div>
                    </div>
                    <div class="invest__body">
                        <? foreach ($arResult["ITEMS"] as $arItem): ?>
                            <a class="invest__row" href="edit/<?= $arItem["ID"] ?>/">
                                <div class="invest__deposite"><?= $arItem["UF_NAME"] ?></div>
                                <div class="invest__place"><?= $arItem["UF_ACTIVE_DIFF"] ?></div>
                                <div class="invest__place invest_small-hide">
                                    <? if (!empty($arItem["UF_ACTIVE_DIFF"]) && !$arResult["FUTURE"]): ?>
                                        <span class="i-public-status i-public-status_active">опубликована</span>
                                    <? elseif ($arResult["FUTURE"]): ?>
                                        <span class="i-public-status i-public-status_future">ожидается</span>
                                    <? elseif ($arResult["PAST"]): ?>
                                        <span class="i-public-status">завершена</span>
                                    <? endif; ?>
                                </div>
                                <div class="invest__place"></div>
                            </a>
                        <? endforeach; ?>
                    </div>
                </div>
            </div>
        <? endif; ?>
        <a href="add/" class="sentence">Создать предложение</a>
    </div>
</div>
