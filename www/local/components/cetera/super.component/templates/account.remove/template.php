<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="subscribe-edit">
    <? if (!empty($arResult["SUCCESS"]) || !empty($arResult["ERROR"])) { ?>
        <br><br><br><br><br><br><br><br>
        <div class="row">
            <div class="column small-12 small-centered medium-7 large-6 text-center">
                <? if (!empty($arResult["SUCCESS"])): ?>
                    <div class="mess b-sizeinfo__title i-sizeinfo__title_subscribe_edit">
                        <?= implode("<br>", $arResult["SUCCESS"]); ?>
                    </div>
                <? elseif (!empty($arResult["ERROR"])): ?>
                    <div class="mess b-sizeinfo__title i-sizeinfo__title_subscribe_edit error">
                        <?= implode("<br>", $arResult["ERROR"]); ?>
                    </div>
                <? endif; ?>
            </div>
        </div>
        <br><br><br><br><br><br><br><br>
    <? } else {
        LocalRedirect("/");
    } ?>
</div>
