<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//echo "<pre>fparams=";print_r($arParams); echo "</pre>"; // параметры компонента

$iblock=CIBlock::GetFields($arParams["IBLOCK_ID"]); // параметры инфоблока
//echo "<pre>iblock=";print_r($iblock); echo "</pre>"; 
?>
<div class="news-list">
<?if($arParams["DISPLAY_TOP_PAGER"]):?> <?=$arResult["NAV_STRING"]?><br /><?endif;?>

<?foreach($arResult["ITEMS"] as $arItem):?>
	<? 
	if(strlen($arItem['PROPERTIES']['file']['VALUE'])<1)$arItem['NAME']=$arItem['NAME']."<span style='color:red'>файл не загружен</span>";
	$rsFile = CFile::GetByID($arItem['PROPERTIES']['file']['VALUE']);
	$arFile = $rsFile->Fetch();
	//echo "<pre>file=";print_r($arFile); echo "</pre>";
	$arItem['PROPERTIES']['counter']['VALUE']=(int)$arItem['PROPERTIES']['counter']['VALUE'];
	?>
	<div>
	<a style="vertical-align:middle;" href="/bitrix/components/ruc/downloads/download.php?id=<?=$arItem['IBLOCK_ID'].":".$arItem['ID']?>">
	<?=($arItem["PREVIEW_PICTURE"]["SRC"]>"" ?
		"<img src='".$arItem["PREVIEW_PICTURE"]["SRC"]."'>":
		"<img src='/bitrix/components/ruc/downloads/images/space.gif' width='".$iblock['PREVIEW_PICTURE']['DEFAULT_VALUE']['WIDTH']."' height='1'>")?>
	<?=$arItem['NAME']?>  <? // IBLOCK_ID
	
	$x=$arFile['FILE_SIZE'];
	if($arFile['FILE_SIZE']>1024*2)			$x=number_format(($arFile['FILE_SIZE']/1024),2,'.',' ')."K";
	if($arFile['FILE_SIZE']>1024*1024*2)	$x=number_format(($arFile['FILE_SIZE']/1024/1024),2,'.',' ')."M";
	
	echo " - ".$x;
	?>
	(скачиваний: <?=$arItem['PROPERTIES']['counter']['VALUE']?>)
	</a></div>
<?endforeach;?>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>	<br /><br /><?=$arResult["NAV_STRING"]?><?endif;?>
</div>
