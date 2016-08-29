<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arParams */
/** @var array $arResult */
/** @global CUser $USER */
global $USER;
/** @global CMain $APPLICATION */
global $APPLICATION;

$APPLICATION->SetTitle("Рейтинги организации");
$APPLICATION->AddChainItem("Рейтинги организации");

$ratingList = Array();
$hblock = new \Cetera\HBlock\SimpleHblockObject(8);
$list = $hblock->getList();
while ($el = $list->fetch()) {
    $ratingList[$el["ID"]] = $el;
}

$rating = Array();
$hblock = new \Cetera\HBlock\SimpleHblockObject(7);
$list = $hblock->getList(Array("filter" => Array("UF_USER" => $USER->GetID())));
while ($el = $list->fetch()) {
    $rating[$el["UF_AGENCY"]] = $el;
}

?>

<? if (!empty($_SESSION["SUCCESS"])): ?>
    <div data-alert class="alert-box success radius"><?= $_SESSION["SUCCESS"] ?><a href="#" class="close">&times;</a>
    </div>
    <? unset($_SESSION["SUCCESS"]); ?>
<? endif; ?>

<? if (count($ratingList)): ?>
    <div class="row">
        <? $i = 1; ?>
        <? foreach ($ratingList as $key => $item): ?>
            <div class="columns<? if ($i < count($ratingList)): ?> req<? endif; ?>">
                <div class="row">
                    <div class="req__name medium-4 small-5 columns"><?= $item["UF_NAME"] ?>:</div>
                    <div
                        class="req__value medium-8 small-7 columns"><?= !empty($rating[$key]["UF_RATING"]) ? $rating[$key]["UF_RATING"] : "<span class='req__name'>—</span>"; ?></div>
                </div>
            </div>
            <? ++$i; ?>
        <? endforeach; ?>
    </div>
<? else: ?>
    <? ShowNote("Рейтинги отсутствуют"); ?>
<? endif; ?>
    <div class="row">
        <div class="columns small-5 medium-4 req__name">&nbsp;</div>
        <div class="columns small-7 medium-8 req__value">
            <a class="content__change" href="edit/">Изменить</a>
        </div>
    </div>

<? if (!empty(reset($rating)["UF_UPDATED"])): ?>
    <div class="row">
        <div class="columns content__date">
            Обновлено: <?= CIBlockFormatProperties::DateFormat("d M Y в H:i", strtotime(reset($rating)["UF_UPDATED"])); ?></div>
    </div>
<? endif; ?>