<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?
ignore_user_abort(true);
ini_set('max_execution_time', '0');
set_time_limit(0);

if (!empty($_REQUEST["ID"]) && intval($_REQUEST["ID"]) > 0) {
    \Parser\Parser::parse(intval($_REQUEST["ID"]));
} ?>
