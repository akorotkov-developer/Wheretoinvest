<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);
?>
<div class="reviews">
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="reviews-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<div class="reviews-item_header">
			<span class="reviews-item_header-name"><?echo $arItem["DISPLAY_PROPERTIES"]["REVIEW_AUTHOR_NAME"]["VALUE"]?>,</span>
			<span class="reviews-item_header-date">
				<?echo date("d", strtotime($arItem['DATE_CREATE']))?>
				<?=GetMessage(date( 'F', strtotime($arItem['DATE_CREATE'])))?>
				<?echo date("Y", strtotime($arItem['DATE_CREATE']))?>
				<?echo("г.")?>
			</span>
		</div>
		<div class="reviews-item_rating">
			<?
			$stars = 0;
			$rating = intval($arItem["DISPLAY_PROPERTIES"]["REVIEW_RATING"]["VALUE"]);
			while ($stars < 5) {
				if ($rating > $stars) {
					echo('<span class="reviews-item_rating-star reviews-item_rating-star-active"></span>');
				} else {
					echo('<span class="reviews-item_rating-star"></span>');
				}
				$stars++;
			}?>
		</div>
		<div class="reviews-item_text"><?echo $arItem["PREVIEW_TEXT"];?></div>
	</div>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>
