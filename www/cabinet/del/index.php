<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Удаление аккаунта");
?>
<?
$APPLICATION->IncludeComponent("cetera:super.component", "account.remove", Array());
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>