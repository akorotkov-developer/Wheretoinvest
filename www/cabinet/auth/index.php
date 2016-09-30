<? define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация");
if ((isset($_REQUEST["backurl"]))and(strlen($_REQUEST["backurl"])>0)) {
    LocalRedirect($_REQUEST["backurl"]);
}
else{
    LocalRedirect("/cabinet/");
}
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>