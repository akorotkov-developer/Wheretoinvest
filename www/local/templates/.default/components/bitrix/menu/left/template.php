<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)): ?>
    <ul class="accord__list">
        <? foreach ($arResult as $arItem): ?>
            <? if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
                continue;
            ?>
            <? $class = !empty($arItem["PARAMS"]["class"]) ? $arItem["PARAMS"]["class"] : ""; ?>
            <? if (!empty($arItem["LINK"])): ?>
                <li class="accord__li">
                    <a class="accord__link <?= $class ?><? if ($arItem["SELECTED"]): ?> accord__link_active<? endif; ?>"
                       href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a>
                </li>
            <? else: ?>
                <li class="accord__li <?= $class ?>">
                    <?= $arItem["TEXT"] ?>
                </li>
            <? endif; ?>
        <? endforeach ?>
    </ul>
<? endif ?>