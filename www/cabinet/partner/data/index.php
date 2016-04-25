<?define("NEED_AUTH",true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Данные организации");
?><?$APPLICATION->IncludeComponent(
	"cetera:dataorg",
	"",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"HIGHLOAD_ID" => "1"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>