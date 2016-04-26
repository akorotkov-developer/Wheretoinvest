<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Предложения");
?><?$APPLICATION->IncludeComponent(
	"cetera:dataorg.list",
	"",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"HIGHLOAD_ID" => "3"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>