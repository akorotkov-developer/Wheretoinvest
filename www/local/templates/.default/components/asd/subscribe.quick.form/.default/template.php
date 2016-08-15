<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (method_exists($this, 'setFrameMode')) {
    $this->setFrameMode(true);
}
?>
<div id="asd_subscribe_res" class="reveal-modal modal modal_js-top" data-reveal aria-labelledby="modalTitle"
     aria-hidden="true" role="dialog">
    <div class="mess b-sizeinfo__title i-sizeinfo__title_subscribe">
        <?
        if ($arResult['ACTION']['status'] == 'error') {
            ShowError($arResult['ACTION']['message']);
        } elseif ($arResult['ACTION']['status'] == 'ok') {
            ShowNote($arResult['ACTION']['message']);
        }
        ?>
    </div>
    <a class="close-reveal-modal modal__close" aria-label="Close">&#215;</a>
</div>

<form action="<?= POST_FORM_ACTION_URI ?>" method="post" id="asd_subscribe_form" class="b-footer__form">
    <div class="b-footer__smalltext">Подписаться на рассылку</div>
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="asd_subscribe" value="Y"/>
    <input type="hidden" name="charset" value="<?= SITE_CHARSET ?>"/>
    <input type="hidden" name="site_id" value="<?= SITE_ID ?>"/>
    <input type="hidden" name="asd_rubrics" value="<?= $arParams['RUBRICS_STR'] ?>"/>
    <input type="hidden" name="asd_format" value="<?= $arParams['FORMAT'] ?>"/>
    <input type="hidden" name="asd_show_rubrics" value="<?= $arParams['SHOW_RUBRICS'] ?>"/>
    <input type="hidden" name="asd_not_confirm" value="<?= $arParams['NOT_CONFIRM'] ?>"/>
    <input type="hidden" name="asd_key"
           value="<?= md5($arParams['JS_KEY'] . $arParams['RUBRICS_STR'] . $arParams['SHOW_RUBRICS'] . $arParams['NOT_CONFIRM']) ?>"/>

    <div class="row collapse ">
        <div class="small-10 columns">
            <input type="text" placeholder="youremail@yandex.ru" name="asd_email" value="" required/>
        </div>
        <div class="small-2 columns b-footer__bug end">
            <input type="submit" class="b-footer__postfix" name="asd_submit" id="asd_subscribe_submit">
        </div>
    </div>
</form>
