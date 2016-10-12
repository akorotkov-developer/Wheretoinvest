<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <? $cnt = count($arResult); ?>
    <? global $TOTAL_METHOD; ?>
    <div class="row">
        <ul class="b-header__menu small-block-grid-2 medium-block-grid-<?= $cnt ?> large-block-grid-<?= $cnt ?>"
            data-equalizer="">
            <? foreach ($arResult as $arItem): ?>
                <? if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
                    continue;
                ?>
                <? $class = !empty($arItem["PARAMS"]["class"]) ? $arItem["PARAMS"]["class"] : ""; ?>
                <?
                if ($APPLICATION->GetCurPage() == "/") {
                    $arItem["LINK"] = $APPLICATION->GetCurPageParam(preg_replace("#^/[\?]?#is", "", $arItem["LINK"]), Array("method", "favorite"));
                }
                ?>
                <? if ($arItem["SELECTED"]): ?>
                    <? $TOTAL_METHOD = $arItem["TEXT"]; ?>
                    <li class="b-header__menu-item">
                        <a class="b-header__menu-link b-header__menu-link_active <?= $class ?>"
                           href="<?= $arItem["LINK"] ?>" data-equalizer-watch="">
                            <span class="b-header__menu-link-inner"><?= $arItem["TEXT"] ?></span>
                        </a>
                    </li>
                <? else: ?>
                    <li class="b-header__menu-item">
                        <a class="b-header__menu-link <?= $class ?>"
                           href="<?= $arItem["LINK"] ?>" data-equalizer-watch="">
                            <span class="b-header__menu-link-inner"><?= $arItem["TEXT"] ?></span>
                        </a>
                    </li>
                <? endif ?>
            <? endforeach ?>
        </ul>
    </div>
<? endif ?>