<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Моя методика определения надежности");
?>

<? $APPLICATION->IncludeComponent("cetera:super.component", "user.method", Array()); ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>