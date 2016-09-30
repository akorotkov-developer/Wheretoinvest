<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//*************************************
//show current authorization section
//*************************************
?>
    <div class="row">
        <div class="column small-12 medium-12 large-12">
            <span class="subscribe__title"><?echo GetMessage("subscr_title_auth")?></span>
            <div class="subscribe">
                <div class="row">
                    <div class="column small-12 medium-12 large-12">
                        <?echo bitrix_sessid_post();?>
                    </div>
                </div>
                <div class="row">
                    <div class="column small-12 medium-12 large-12">
                        <span><?echo GetMessage("adm_auth_user")?></span>
                        <span><?echo htmlspecialcharsbx($USER->GetFormattedName(false));?> [<?echo htmlspecialcharsbx($USER->GetLogin())?>].</span>
                    </div>
                    <div class="column small-12 medium-12 large-12">
                        <span>
                        <?if($arResult["ID"]==0):?>
                            <?echo GetMessage("subscr_auth_logout1")?> <a class="subscribe__link" href="<?echo $arResult["FORM_ACTION"]?>?logout=YES&amp;sf_EMAIL=<?echo $arResult["REQUEST"]["EMAIL"]?><?echo $arResult["REQUEST"]["RUBRICS_PARAM"]?>"><?echo GetMessage("adm_auth_logout")?></a><?echo GetMessage("subscr_auth_logout2")?><br />
                        <?else:?>
                            <?echo GetMessage("subscr_auth_logout3")?> <a  class="subscribe__link" href="<?echo $arResult["FORM_ACTION"]?>?logout=YES&amp;sf_EMAIL=<?echo $arResult["REQUEST"]["EMAIL"]?><?echo $arResult["REQUEST"]["RUBRICS_PARAM"]?>"><?echo GetMessage("adm_auth_logout")?></a><?echo GetMessage("subscr_auth_logout4")?><br />
                        <?endif;?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
