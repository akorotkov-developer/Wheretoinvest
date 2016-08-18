<? define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php"); ?>

<? $APPLICATION->IncludeComponent(
	"cetera:user.cabinet", 
	".default", 
	array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => ".default",
		"FILTER_NAME" => "arrFilter",
		"NAME_TEMPLATE" => "#LAST_NAME# #NAME# #SECOND_NAME#",
		"PAGER_TEMPLATE" => "",
		"PAGE_COUNT" => "10",
		"PROFILE" => "",
		"SEF_FOLDER" => "/cabinet/",
		"SEF_MODE" => "Y",
		"SET_STATUS_404" => "Y",
		"SEF_URL_TEMPLATES" => array(
			"index" => "",
			"info" => "info/",
			"method" => "method/",
			"region" => "region/",
		)
	),
	false
); ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>