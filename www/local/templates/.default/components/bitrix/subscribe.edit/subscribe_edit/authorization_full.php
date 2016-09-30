<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//******************************************
//subscription authorization form
//******************************************
?>

    <div class="row">
        <div class="column small-12 medium-12 large-12">
            <span class="subscribe__title"><?echo GetMessage("subscr_title_auth2")?></span>
            <div class="subscribe">
                <div class="row">
                    <div class="column small-12 medium-12 large-12">
                        <p><?echo GetMessage("adm_auth1")?> <a class="subscribe__link" href="/cabinet/auth/?backurl=/cabinet/subscribe/"><?echo GetMessage("adm_auth2")?></a>.
                            <span data-tooltip aria-haspopup="true" class="has-tip subscribe__hint" title="<?echo GetMessage("adm_reg_text")?>"></span></p>
                        <?if($arResult["ALLOW_REGISTER"]=="Y"):?>
                            <p><?echo GetMessage("adm_reg1")?> <a class="subscribe__link" href="/cabinet/auth/?register=yes"><?echo GetMessage("adm_reg2")?></a>.</p>
                        <?endif;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?/*
<form action="<?echo $arResult["FORM_ACTION"].($_SERVER["QUERY_STRING"]<>""? "?".htmlspecialcharsbx($_SERVER["QUERY_STRING"]):"")?>" method="post">
    <div class="row">
        <div class="column small-12 medium-12 large-12">
            <span class="subscribe__title"><?echo GetMessage("subscr_auth_sect_title")?></span>
            <div class="subscribe">
                <div class="row">
                    <div class="column small-12 medium-12 large-12">
                        <span>e-mail</span>
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

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="data-table">
<thead><tr><td colspan="2"><?echo GetMessage("subscr_auth_sect_title")?></td></tr></thead>
<tr valign="top">
	<td width="40%">
		<p>e-mail<br /><input type="text" name="sf_EMAIL" size="20" value="<?echo $arResult["REQUEST"]["EMAIL"];?>" title="<?echo GetMessage("subscr_auth_email")?>" /></p>
		<p><?echo GetMessage("subscr_auth_pass")?><br /><input type="password" name="AUTH_PASS" size="20" value="" title="<?echo GetMessage("subscr_auth_pass_title")?>" /></p>
	</td>
	<td width="60%">
		<?echo GetMessage("adm_auth_note")?>
	</td>
</tr>
<tfoot><tr><td colspan="2"><input type="submit" name="autorize" value="<?echo GetMessage("adm_auth_butt")?>" /></td></tr></tfoot>
</table>
<input type="hidden" name="action" value="authorize" />
<?echo bitrix_sessid_post();?>
</form>
<br />

<form action="<?=$arResult["FORM_ACTION"]?>">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="data-table">
<thead><tr><td colspan="2"><?echo GetMessage("subscr_pass_title")?></td></tr></thead>
<tr valign="top">
	<td width="40%">
		<p>e-mail<br /><input type="text" name="sf_EMAIL" size="20" value="<?echo $arResult["REQUEST"]["EMAIL"];?>" title="<?echo GetMessage("subscr_auth_email")?>" /></p>
	</td>
	<td width="60%">
		<?echo GetMessage("subscr_pass_note")?>
	</td>
</tr>
<tfoot><tr><td colspan="2"><input type="submit" name="sendpassword" value="<?echo GetMessage("subscr_pass_button")?>" /></td></tr></tfoot>
</table>
<input type="hidden" name="action" value="sendpassword" />
<?echo bitrix_sessid_post();?>
</form>
<br />
*/?>