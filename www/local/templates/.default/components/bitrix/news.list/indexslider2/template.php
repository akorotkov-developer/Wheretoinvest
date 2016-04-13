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

<div class="row">
	<div class="columns small-6">
		<h2>Каталог финансовых организаций</h2>
	</div>
	<div class="columns small-6 text-right">
		<a href="/banks/">Посмотреть все</a>
	</div>
</div>

<div class="row">
	<div class="columns small-12">
		<ul class="small-block-grid-1 medium-block-grid-2 large-block-grid-4">






<?$i=0;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<?if($i == 4)
		break;
	?>
	<li>
		<a class="project-square" href="<?=$arItem["DETAIL_PAGE_URL"]?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<div class="project-square__image" style="background-image: url(<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>)">
				<div class="project-square__text">
					<h2 class="project-square__title"><?echo $arItem["NAME"]?></h2>

					<?=$arItem["PREVIEW_TEXT"];?>

				</div>
			</div>
		</a>
	</li>
	<?$i++;?>

<?endforeach;?>

		</ul>
	</div>
</div>