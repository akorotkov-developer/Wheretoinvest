<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Реквизиты");
?><?$APPLICATION->IncludeComponent(
	"cetera:dataorg.complex",
	"",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"TYPEPAGE" => "2"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>