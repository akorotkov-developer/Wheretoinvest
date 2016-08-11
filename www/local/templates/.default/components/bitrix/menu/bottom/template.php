<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <ul class="b-footer__menu">
        <? foreach ($arResult as $arItem): ?>
            <? if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
                continue;
            ?>
            <? $class = !empty($arItem["PARAMS"]["class"]) ? $arItem["PARAMS"]["class"] : ""; ?>
            <li>
                <a class="b-footer__link <?= $class ?>"
                   href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a>
            </li>
        <? endforeach ?>
    </ul>
<? endif ?>