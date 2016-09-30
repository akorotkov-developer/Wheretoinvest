<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//*************************************
//show confirmation form
//*************************************
?>
<form action="<?=$arResult["FORM_ACTION"]?>" method="get">
    <div class="row">
        <div class="column small-12 medium-12 large-12">
            <span class="subscribe__title"><?echo GetMessage("subscr_title_confirm")?></span>
            <div class="subscribe">
                <div class="row">
                    <div class="column small-9 medium-9 large-7">
                        <div class="row">
                            <div class="column small-12">
                                <div class="b-form__title b-form__title_margin-bottom subscribe__title-fields">
                                    <span class="b-form__title-text"><?echo GetMessage("subscr_conf_code")?></span>
                                    <sup class="b-form__required">*</sup>
                                </div>
                            </div>
                            <div class="column small-12 medium-12 end">
                                <input class="b-form__input" type="text"  name="CONFIRM_CODE" required=""
                                       value="<?echo $arResult["REQUEST"]["CONFIRM_CODE"];?>" />
                            </div>
                        </div>
                        <div class="b-form__row"></div>
                        <div class="row">
                            <div class="column small-12">
                                <p><?echo GetMessage("subscr_conf_date")?></p>
                                <p><?echo $arResult["SUBSCRIPTION"]["DATE_CONFIRM"];?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="column small-12 medium-12 large-12">
                        <?echo GetMessage("subscr_conf_note1")?> <a class="subscribe__link" title="<?echo GetMessage("adm_send_code")?>" href="<?echo $arResult["FORM_ACTION"]?>?ID=<?echo $arResult["ID"]?>&amp;action=sendcode&amp;<?echo bitrix_sessid_get()?>"><?echo GetMessage("subscr_conf_note2")?></a>.
                    </div>
                </div>
                <div class="b-form__row"></div>
                <div class="row">
                    <div class="column small-12 medium-12 large-12">
                        <button type="submit" name="confirm" class="content__submit"><?echo GetMessage("subscr_conf_button")?></button>
                    </div>
                </div>
                <input type="hidden" name="ID" value="<?echo $arResult["ID"];?>" />
                <?echo bitrix_sessid_post();?>
            </div>
        </div>
    </div>
</form>