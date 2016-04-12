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
<div class="x-slick">






<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="slick-slide" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<div class="content-line content-line_image"  style="background-image: url(<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>)">
			<div class="row">
				<div class="columns small-12 medium-8 large-5 large-offset-1 end">
					<h1><?echo $arItem["NAME"]?></h1>
					<?=$arItem["PREVIEW_TEXT"];?>
					<div><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="button small">Подробнее</a></div>
				</div>
			</div>
		</div>
	</div>

<?endforeach;?>

</div>
