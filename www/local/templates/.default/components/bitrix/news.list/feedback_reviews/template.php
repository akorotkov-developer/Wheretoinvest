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
	<div class="reviews-stats">
		<div class="reviews-stats_item">
			<div class="reviews-stats_item-left">
				<?
				$stars = 0;
				while ($stars < 5) {
					if ($arResult["RATES_AVERAGE"] > $stars) {
						if ($arResult["RATES_AVERAGE"] - $stars >= 0.75) {
							echo('<span class="reviews-stats_item-left-star reviews-stats_item-left-star-full"></span>');
						} elseif ($arResult["RATES_AVERAGE"] - $stars >= 0.25 && $arResult["RATES_AVERAGE"] - $stars < 0.75) {
							echo('<span class="reviews-stats_item-left-star reviews-stats_item-left-star-half"></span>');
						} else {
							echo('<span class="reviews-stats_item-left-star"></span>');
						}
					} else {
						echo('<span class="reviews-stats_item-left-star"></span>');
					}
					$stars++;
				}?>
			</div>
			<div class="reviews-stats_item-right">
				<span class="reviews-stats_item-right-text">Всего отзывов <?=$arResult["RATES_COUNT"]?></span>
			</div>
		</div>
		<?foreach(array_reverse($arResult["RATES"], true) as $rate => $info) :?>
			<div class="reviews-stats_item">
				<div class="reviews-stats_item-left">
					<?
					$stars = 5;
					while ($stars > 0) {
						if (intval($rate) >= $stars) {
							echo('<span class="reviews-stats_item-left-star reviews-stats_item-left-star-black"></span>');
						} else {
							echo('<span class="reviews-stats_item-left-star reviews-stats_item-left-star-hide"></span>');
						}
						$stars--;
					}?>
				</div>
				<div class="reviews-stats_item-right">
					<div class="reviews-stats_item-right-line">
						<div class="reviews-stats_item-right-line-active" style="width:<?=$info["PERCENT"]?>%"></div>
					</div>
				</div>
			</div>
		<?endforeach;?>
	</div>

	<?foreach($arResult["ITEMS"] as $arItem):?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
		<div class="reviews-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<div class="reviews-item_header">
				<span class="reviews-item_header-name">
					<?if (isset($arItem["DISPLAY_PROPERTIES"]["REVIEW_AUTHOR_NAME"]["VALUE"]) && !empty($arItem["DISPLAY_PROPERTIES"]["REVIEW_AUTHOR_NAME"]["VALUE"])) :
						echo ($arItem["DISPLAY_PROPERTIES"]["REVIEW_AUTHOR_NAME"]["VALUE"].",")?>
					<?else:
						echo ("Анонимно,")?>
					<?endif;?>
				</span>
				<span class="reviews-item_header-date">
					<?echo date("d", strtotime($arItem['DATE_CREATE']))?>
					<?=GetMessage(date( 'F', strtotime($arItem['DATE_CREATE'])))?>
					<?echo date("Y", strtotime($arItem['DATE_CREATE']))?>
					<?echo("г.")?>
				</span>
			</div>
			<div class="reviews-item_rating">
				<?
				$rating = intval($arItem["DISPLAY_PROPERTIES"]["REVIEW_RATING"]["VALUE"]);
				$stars = 0;
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


