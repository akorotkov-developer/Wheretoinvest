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
		<h2>Новости</h2>
	</div>
	<div class="columns small-6 text-right">
		<a href="/news/">Посмотреть все</a>
	</div>
</div>
<div class="row">
	<div class="columns small-12">
		<ul class="small-block-grid-1 medium-block-grid-3" data-equalizer="news">




<?$i=0;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<?if($i == 6)
		break;
	?>
	<li>
		<div class="panel text-center" data-equalizer-watch="news">
                    <span class="date-square">
                        <span class="date-square__day"><?=substr($arItem["ACTIVE_FROM"],0,2);?></span><br><?=substr($arItem["ACTIVE_FROM"],3,7);?>
                    </span>
			<h2><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?echo $arItem["NAME"]?></a></h2>
			<?=$arItem["PREVIEW_TEXT"];?>

		</div>
	</li>

	<?$i++;?>

<?endforeach;?>

		</ul>
	</div>
</div>