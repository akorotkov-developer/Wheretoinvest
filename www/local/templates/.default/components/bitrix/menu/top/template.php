<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <div class="b-header__menuWrap js-menu">
        <div class="row">
            <div class="column small-12">
                <ul class="b-header__menu">
                    <? foreach ($arResult as $arItem): ?>
                        <? if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
                            continue;
                        ?>
                        <? $class = !empty($arItem["PARAMS"]["class"]) ? $arItem["PARAMS"]["class"] : ""; ?>
                        <? if ($arItem["SELECTED"]): ?>
                            <li class="b-header__menu-item">
                                <a class="b-header__menu-link b-header__menu-link_active <?= $class ?>"
                                   href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a>
                            </li>
                        <? else: ?>
                            <li class="b-header__menu-item">
                                <a class="b-header__menu-link <?= $class ?>"
                                   href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a>
                            </li>
                        <? endif ?>
                    <? endforeach ?>
                </ul>
            </div>
        </div>
    </div>
<? endif ?>