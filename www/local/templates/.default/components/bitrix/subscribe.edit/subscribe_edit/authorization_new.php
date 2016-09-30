<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if($arResult["ALLOW_ANONYMOUS"]=="Y"){?>
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
<?} /*elseif($arResult["ALLOW_ANONYMOUS"]=="N" || $_REQUEST["authorize"]=="YES" || $_REQUEST["register"]=="YES"):?>
	<form action="<?=$arResult["FORM_ACTION"]?>" method="post">
	<?echo bitrix_sessid_post();?>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="data-table">
	<thead><tr><td colspan="2"><?echo GetMessage("adm_auth_exist")?></td></tr></thead>
	<tr valign="top">
		<td width="40%">
			<p><?echo GetMessage("adm_auth_login")?><span class="starrequired">*</span><br />
			<input type="text" name="LOGIN" value="<?echo $arResult["REQUEST"]["LOGIN"]?>" size="20" /></p>
			<p><?echo GetMessage("adm_auth_pass")?><span class="starrequired">*</span><br />
			<input type="password" name="PASSWORD" size="20" value="<?echo $arResult["REQUEST"]["PASSWORD"]?>" /></p>
		</td>
		<td width="60%">
			<?if($arResult["ALLOW_ANONYMOUS"]=="Y"):?>
				<?echo GetMessage("subscr_auth_note")?>
			<?else:?>
				<?echo GetMessage("adm_must_auth")?>
			<?endif;?>
		</td>
	</tr>
	<tfoot><tr><td colspan="2"><input type="submit" name="Save" value="<?echo GetMessage("adm_auth_butt")?>" /></td></tr></tfoot>
	</table>
	<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
		<input type="hidden" name="RUB_ID[]" value="<?=$itemValue["ID"]?>">
	<?endforeach;?>
	<input type="hidden" name="PostAction" value="<?echo ($arResult["ID"]>0? "Update":"Add")?>" />
	<input type="hidden" name="ID" value="<?echo $arResult["SUBSCRIPTION"]["ID"];?>" />
	<?if($_REQUEST["register"] == "YES"):?>
		<input type="hidden" name="register" value="YES" />
	<?endif;?>
	<?if($_REQUEST["authorize"]=="YES"):?>
		<input type="hidden" name="authorize" value="YES" />
	<?endif;?>
	</form>
	<br />
	<?if($arResult["ALLOW_REGISTER"]=="Y"):
		?>
		<form action="<?=$arResult["FORM_ACTION"]?>" method="post">
		<?echo bitrix_sessid_post();?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="data-table">
		<thead><tr><td colspan="2"><?echo GetMessage("adm_reg_new")?></td></tr></thead>
		<tr valign="top">
			<td width="40%">
			<p><?echo GetMessage("adm_reg_login")?><span class="starrequired">*</span><br />
			<input type="text" name="NEW_LOGIN" value="<?echo $arResult["REQUEST"]["NEW_LOGIN"]?>" size="20" /></p>
			<p><?echo GetMessage("adm_reg_pass")?><span class="starrequired">*</span><br />
			<input type="password" name="NEW_PASSWORD" size="20" value="<?echo $arResult["REQUEST"]["NEW_PASSWORD"]?>" /></p>
			<p><?echo GetMessage("adm_reg_pass_conf")?><span class="starrequired">*</span><br />
			<input type="password" name="CONFIRM_PASSWORD" size="20" value="<?echo $arResult["REQUEST"]["CONFIRM_PASSWORD"]?>" /></p>
			<p><?echo GetMessage("subscr_email")?><span class="starrequired">*</span><br />
			<input type="text" name="EMAIL" value="<?=$arResult["SUBSCRIPTION"]["EMAIL"]!=""?$arResult["SUBSCRIPTION"]["EMAIL"]:$arResult["REQUEST"]["EMAIL"];?>" size="30" maxlength="255" /></p>
		<?
			// CAPTCHA
			if (COption::GetOptionString("main", "captcha_registration", "N") == "Y"):
				$capCode = $GLOBALS["APPLICATION"]->CaptchaGetCode();
			?>
				<p><?=GetMessage("subscr_CAPTCHA_REGF_TITLE")?><br />
				<input type="hidden" name="captcha_sid" value="<?= htmlspecialcharsbx($capCode) ?>" />
				<img src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($capCode) ?>" width="180" height="40" alt="CAPTCHA" /></p>
				<p><?=GetMessage("subscr_CAPTCHA_REGF_PROMT")?><span class="starrequired">*</span><br />
				<input type="text" name="captcha_word" size="30" maxlength="50" value="" /></p>
			<?endif;?>
			</td>
			<td width="60%">
				<?if($arResult["ALLOW_ANONYMOUS"]=="Y"):?>
					<?echo GetMessage("subscr_auth_note")?>
				<?else:?>
					<?echo GetMessage("adm_must_auth")?>
				<?endif;?>
			</td>
		</tr>
		<tfoot><tr><td colspan="2"><input type="submit" name="Save" value="<?echo GetMessage("adm_reg_butt")?>" /></td></tr></tfoot>
		</table>
		<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
			<input type="hidden" name="RUB_ID[]" value="<?=$itemValue["ID"]?>">
		<?endforeach;?>
		<input type="hidden" name="PostAction" value="<?echo ($arResult["ID"]>0? "Update":"Add")?>" />
		<input type="hidden" name="ID" value="<?echo $arResult["SUBSCRIPTION"]["ID"];?>" />
		<?if($_REQUEST["register"] == "YES"):?>
			<input type="hidden" name="register" value="YES" />
		<?endif;?>
		<?if($_REQUEST["authorize"]=="YES"):?>
			<input type="hidden" name="authorize" value="YES" />
		<?endif;?>
		</form>
		<br />
	<?endif;?>
<?endif; //$arResult["ALLOW_ANONYMOUS"]=="Y" && $authorize<>"YES"?>
 */?>