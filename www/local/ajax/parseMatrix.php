<?
$_SERVER["DOCUMENT_ROOT"] = __DIR__ . "/../../";
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?
\Parser\Parser::parse();
?>
