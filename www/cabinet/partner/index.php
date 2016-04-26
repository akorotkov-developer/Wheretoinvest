<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Партнер");
?><?$APPLICATION->IncludeComponent(
	"cetera:dataorg.complex",
	"",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"HIGHLOAD_ID" => "1",
		"SEF_FOLDER" => "/cabinet/partner/",
		"SEF_MODE" => "Y",
		"SEF_URL_TEMPLATES" => Array("data"=>"data/","offers"=>"offers/#element_id#/","reliability"=>"reliability/","requisites"=>"requisites/"),
		"TYPEPAGE" => "1"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>