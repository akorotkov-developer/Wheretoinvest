<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<? if ($_REQUEST["setClose"] == "Y") {
    $APPLICATION->set_cookie("SIZE_CLOSE", "Y");
} ?>
