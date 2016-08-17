<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Партнер");
?><?$APPLICATION->IncludeComponent(
	"cetera:dataorg.complex",
	"",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"TYPEPAGE" => "1"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>