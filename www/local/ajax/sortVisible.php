<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?
if (intval($_REQUEST["setVisible"]) === 1) {
    $APPLICATION->set_cookie("SORT_VISIBLE", "");
} else {
    $APPLICATION->set_cookie("SORT_VISIBLE", "N");
}
?>
