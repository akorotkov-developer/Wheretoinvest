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
        <div class="columns agency">
            <div class="agency__main">
                <div class="agency__head">
                    <div class="agency__th agency__th_first">Рейтинговое агентство</div>
                    <div class="agency__th">Способ вложения</div>
                </div>
                <div class="agency__body">
                    <? foreach ($ratingList as $key => $item): ?>
                        <div class="row agency__row">
                            <div class="agency__td agency__td_first">
                                <span class='req__name'><?= $item["UF_NAME"] ?></span>
                            </div>
                            <div class="agency__td">
                                <?= !empty($rating[$key]["UF_RATING"]) ? $rating[$key]["UF_RATING"] : "<span class='req__name'>—</span>"; ?>
                            </div>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<? else: ?>
    <? ShowNote("Рейтинги отсутствуют"); ?>
<? endif; ?>

<a class="content__change" href="edit/">Изменить</a>