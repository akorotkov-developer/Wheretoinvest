<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//***********************************
//status and unsubscription/activation section
//***********************************
?>
<form action="<?=$arResult["FORM_ACTION"]?>" method="get">
    <div class="row">
        <div class="column small-12 medium-12 large-12">
            <span class="subscribe__title"><?echo GetMessage("subscr_title_status")?></span>
            <div class="subscribe subscribe__no-padding">
                <div class="subscribe__border-b">
                    <div class="column small-9 medium-9 large-5">
                        <?echo GetMessage("subscr_conf")?>
                    </div>
                    <div class="column small-3 medium-3 large-7">
                        <span class="<?echo ($arResult["SUBSCRIPTION"]["CONFIRMED"] == "Y"? "notetext":"errortext")?>"><?echo ($arResult["SUBSCRIPTION"]["CONFIRMED"] == "Y"? GetMessage("subscr_yes"):GetMessage("subscr_no"));?></span>
                    </div>
                </div>
                <div class="subscribe__border-b">
                    <div class="column small-9 medium-9 large-5">
                        <?echo GetMessage("subscr_act")?>
                    </div>
                    <div class="column small-3 medium-3 large-7">
                        <span class="<?echo ($arResult["SUBSCRIPTION"]["ACTIVE"] == "Y"? "notetext":"errortext")?>"><?echo ($arResult["SUBSCRIPTION"]["ACTIVE"] == "Y"? GetMessage("subscr_yes"):GetMessage("subscr_no"));?></span>
                    </div>
                </div>
                <div class="subscribe__border-b">
                    <div class="column small-9 medium-9 large-5">
                        <span><?echo GetMessage("adm_id")?></span>
                    </div>
                    <div class="column small-3 medium-3 large-7">
                        <span><?echo $arResult["SUBSCRIPTION"]["ID"];?></span>
                    </div>
                </div>
                <div class="subscribe__border-b">
                    <div class="column small-9 medium-9 large-5">
                        <span><?echo GetMessage("subscr_date_add")?></span>
                    </div>
                    <div class="column small-3 medium-3 large-7">
                        <span><?echo $arResult["SUBSCRIPTION"]["DATE_INSERT"];?></span>
                    </div>
                </div>
                <div class="subscribe__border-b">
                    <div class="column small-9 medium-9 large-5">
                        <span><?echo GetMessage("subscr_date_upd")?></span>
                    </div>
                    <div class="column small-3 medium-3 large-7">
                        <span><?echo $arResult["SUBSCRIPTION"]["DATE_UPDATE"];?></span>
                    </div>
                </div>
                <div class="subscribe__border-b subscribe__border-b_no-border">
                    <div class="column small-12 medium-12 large-12">
                        <?if($arResult["SUBSCRIPTION"]["CONFIRMED"] <> "Y"):?>
                            <div class="subscribe__small-gray"><?echo GetMessage("subscr_title_status_note1")?></div>
                        <?elseif($arResult["SUBSCRIPTION"]["ACTIVE"] == "Y"):?>
                            <div class="subscribe__small-gray"><?echo GetMessage("subscr_title_status_note2")?></div>
                            <div class="subscribe__small-gray"><?echo GetMessage("subscr_status_note3")?></div>
                        <?else:?>
                            <div class="subscribe__small-gray"><?echo GetMessage("subscr_status_note4")?></div>
                            <div class="subscribe__small-gray"><?echo GetMessage("subscr_status_note5")?></div>
                        <?endif;?>
                    </div>
                </div>
                <input type="hidden" name="ID" value="<?echo $arResult["SUBSCRIPTION"]["ID"];?>" />
                <?echo bitrix_sessid_post();?>
            </div>
        </div>
    </div>

    <?if($arResult["SUBSCRIPTION"]["CONFIRMED"] == "Y"):?>
        <div class="row">
            <div class="column small-12 medium-12 large-12">
                <?if($arResult["SUBSCRIPTION"]["ACTIVE"] == "Y"):?>
                    <button type="submit" name="unsubscribe" class="content__submit"><?echo GetMessage("subscr_unsubscr")?></button>
                    <input type="hidden" name="action" value="unsubscribe" />
                <?else:?>
                    <button type="submit" name="activate" class="content__submit"><?echo GetMessage("subscr_activate")?></button>
                    <input type="hidden" name="action" value="activate" />
                <?endif;?>
            </div>
        </div>
    <?endif;?>
</form>