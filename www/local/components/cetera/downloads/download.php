<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/iblock.php");
CModule::IncludeModule("iblock");

if(!isset($_GET['id']))exit("ОШИБКА: не задан ID элемента!");
list($iblock_id,$id)=explode(":",$_GET['id']);
global $arFilter;
$arSelect = Array("IBLOCK_ID","*"); //ID,DETAIL_PICTURE,DETAIL_TEXT,
$arFilter = Array(
	"IBLOCK_ID"=>$iblock_id, "ID"=>$id, //"ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y",
	);
	
$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);   

for($cnt=0;$ob = $res->GetNextElement();){ 
	//echo "<pre>file=";print_r($ob); echo "</pre>";
	$prop = $ob->GetProperties();
	// "<pre>prop=";print_r($prop); echo "</pre>";
	}

	$rsFile = CFile::GetByID($prop['file']['VALUE']);
	$arFile = $rsFile->Fetch();
	echo "<pre>file=";print_r($arFile); echo "</pre>";
	$fil="../../../../upload/".$arFile['SUBDIR']."/".$arFile["FILE_NAME"]; echo "<br>".$fil;  

if (file_exists($fil)) { //echo "====";
    header("200 OK");
	header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header("Content-Disposition: attachment; filename=".str_replace(" ","_",$arFile['ORIGINAL_NAME'])); //
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($fil));
    ob_clean();
    flush();
    readfile($fil);
	
	CIBlockElement::SetPropertyValues($id, $iblock_id, ($prop['counter']['VALUE']+1), "counter");  
    exit;
}
else header("404 Not Found");
?>