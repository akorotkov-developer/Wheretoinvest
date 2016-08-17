<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Банковские вклады");
?><?$APPLICATION->IncludeComponent(
	"cetera:dataorg.complex",
	"",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"METHOD" => "3",
		"TYPEPAGE" => "3",
		"TYPE_CONT" => "banks",
		"VARIABLE_ALIASES_element_id" => "offer_id"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>