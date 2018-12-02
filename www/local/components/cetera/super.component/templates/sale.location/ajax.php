<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?
global $APPLICATION;
if (!empty($_REQUEST["id"]) && !empty($_REQUEST["name"]) && CModule::IncludeModule("sale")) {
    $list = \CSaleLocation::GetList(Array(), Array("ID" => intval($_REQUEST["id"])));
    if ($el = $list->Fetch()) {
        $APPLICATION->set_cookie("CURRENT_LOC_ID", intval($_REQUEST["id"]));
        $APPLICATION->set_cookie("CURRENT_LOC_NAME", trim($_REQUEST["name"]));
    }
}
?>
<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>
