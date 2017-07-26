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
    <div class="b-footer__smalltext">
        Подписаться на рассылку <br>
        самых выгодных предложений!
    </div>
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
        <div class="columns small-12">
            <input type="checkbox" name="CONFIRM_S" value="Y" data-confirm-input="Y" id="FIELD_CONFIRM_S"
                   class="modal__checkbox">
            <label class="modal__chck " for="FIELD_CONFIRM_S">
                Я принимаю условия <a
                        href="/upload/uf/63d/%D0%9F%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D1%81%D0%BA%D0%BE%D0%B5%20%D1%81%D0%BE%D0%B3%D0%BB%D0%B0%D1%88%D0%B5%D0%BD%D0%B8%D0%B5%202017.07.01.pdf"
                        target="_blank">Пользовательского соглашения</a> и <a
                        href="/upload/uf/307/%D0%9F%D0%BE%D0%BB%D0%B8%D1%82%D0%B8%D0%BA%D0%B0%20%D0%BA%D0%BE%D0%BD%D1%84%D0%B8%D0%B4%D0%B5%D0%BD%D1%86%D0%B8%D0%B0%D0%BB%D1%8C%D0%BD%D0%BE%D1%81%D1%82%D0%B8%202017.07.01.pdf"
                        target="_blank">Политики конфиденциальности</a>
            </label>
        </div>
    </div>
</form>
