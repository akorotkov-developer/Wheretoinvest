<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Реквизиты");
?><?$APPLICATION->IncludeComponent(
	"cetera:dataorg",
	"",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"HIGHLOAD_ID" => "2"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>