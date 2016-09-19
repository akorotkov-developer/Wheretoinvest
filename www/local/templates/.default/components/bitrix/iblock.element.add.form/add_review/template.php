<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(false);

if (!empty($arResult["ERRORS"])):?>
	<?ShowError(implode("<br />", $arResult["ERRORS"]))?>
<?endif;
if (strlen($arResult["MESSAGE"]) > 0):?>
	<?ShowNote($arResult["MESSAGE"])?>
<?endif?>

<?
$fields = array("2","PREVIEW_TEXT","1");
?>

<form name="iblock_add" action="<?=POST_FORM_ACTION_URI?>" method="post" enctype="multipart/form-data">
	<?=bitrix_sessid_post()?>
	<div class="reviews-form">
		<?if (is_array($arResult["PROPERTY_LIST"]) && !empty($arResult["PROPERTY_LIST"])):?>
		<div class="reviews-form_content">
			<input type="hidden" name="PROPERTY[NAME][0]" size="25" value="<?echo(date("dmYHis"));?>"/>
			<?foreach ($fields as $field):?>
				<?if (isset($arResult["PROPERTY_LIST_FULL"][$field])) :?>
					<div class="reviews-form_content-item">
						<div class="reviews-form_content-item-name">
							<?if($field === "PREVIEW_TEXT"):?>
								<?echo("Комментарий")?>
							<?else:?>
								<?if (intval($field) > 0):?>
									<?=$arResult["PROPERTY_LIST_FULL"][$field]["NAME"]?>
								<?else:?>
									<?=!empty($arParams["CUSTOM_TITLE_".$field]) ? $arParams["CUSTOM_TITLE_".$field] : GetMessage("IBLOCK_FIELD_".$field)?>
								<?endif?>
							<?endif?>
						</div>
						<div class="reviews-form_content-item-value">
							<?
							switch ($field):
								case "1":
									?>
									<input type="text" name="PROPERTY[<?=$field?>][0]" size="25" />
									<?
								break;

								case "PREVIEW_TEXT":
									?>
									<textarea cols="<?=$arResult["PROPERTY_LIST_FULL"][$field]["COL_COUNT"]?>" rows="<?=$arResult["PROPERTY_LIST_FULL"][$field]["ROW_COUNT"]?>" name="PROPERTY[<?=$field?>][0]"></textarea>
									<?
								break;

								case "2":
									?>
									<div id="reviews-rating">
										<?foreach (array_reverse($arResult["PROPERTY_LIST_FULL"][$field]["ENUM"], true) as $key => $arEnum) {?>
											<input type="radio" class="rating-item" id="rating-<?=$key?>" name="PROPERTY[<?=$field?>]" value="<?=$key?>">
											<label id="star-<?=$key?>" for="rating-<?=$key?>"></label>
										<?}?>
									</div>
									<?
								break;
							endswitch;?>
						</div>
					</div>
				<?endif;?>
			<?endforeach;?>

			<?if($arParams["USE_CAPTCHA"] == "Y" && $arParams["ID"] <= 0):?>
				<div class="reviews-form_content-item">
					<div class="reviews-form_content-item-name">
						<?=GetMessage("IBLOCK_FORM_CAPTCHA_TITLE")?>
					</div>
					<div class="reviews-form_content-item-value">
						<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
						<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
					</div>
				</div>
				<div class="reviews-form_content-item">
					<div class="reviews-form_content-item-name">
						<?=GetMessage("IBLOCK_FORM_CAPTCHA_PROMPT")?>
					</div>
					<div class="reviews-form_content-item-value">
						<input type="text" name="captcha_word" maxlength="50" value="">
					</div>
				</div>
			<?endif?>
		</div>
		<?endif?>
		<div class="reviews-form_footer">
			<div class="reviews-form_footer-button">
				<input type="submit" name="iblock_submit" value="<?=GetMessage("IBLOCK_FORM_SUBMIT")?>" />
				<?if (strlen($arParams["LIST_URL"]) > 0):?>
					<input type="submit" name="iblock_apply" value="<?=GetMessage("IBLOCK_FORM_APPLY")?>" />
					<input
						type="button"
						name="iblock_cancel"
						value="<? echo GetMessage('IBLOCK_FORM_CANCEL'); ?>"
						onclick="location.href='<? echo CUtil::JSEscape($arParams["LIST_URL"])?>';"
					>
				<?endif?>
			</div>
		</div>
	</div>
</form>