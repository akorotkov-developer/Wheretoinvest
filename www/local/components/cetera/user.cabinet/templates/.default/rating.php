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

$rating = Array();
$hblock = new \Cetera\HBlock\SimpleHblockObject(7);
$list = $hblock->getList(Array("filter" => Array("UF_USER" => $USER->GetID())));
while ($el = $list->fetch()) {
    $rating[] = $el;
}
?>

<? if (!empty($_SESSION["SUCCESS"])): ?>
    <div data-alert class="alert-box success radius"><?= $_SESSION["SUCCESS"] ?><a href="#" class="close">&times;</a>
    </div>
    <? unset($_SESSION["SUCCESS"]); ?>
<? endif; ?>

<? if (count($rating)): ?>
    <div class="row">
        <div class="columns agency">
            <div class="agency__main">
                <div class="agency__head">
                    <div class="agency__th agency__th_first">Рейтинговое агентство</div>
                    <div class="agency__th">Способ вложения</div>
                </div>
                <div class="agency__body">
                    <? foreach ($rating as $item): ?>
                        <div class="row agency__row">
                            <div class="agency__td agency__td_first">
                                <?= $item["UF_AGENCY"] ?>
                            </div>
                            <div class="agency__td">
                                <?= $item["UF_RATING"] ?>
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

<a class="content__change" href="edit/"><? if (!count($rating)): ?>Добавить<? else: ?>Изменить<? endif; ?></a>