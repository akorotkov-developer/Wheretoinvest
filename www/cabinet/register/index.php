<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
?><?$_REQUEST["REGISTER"]["LOGIN"]=$_REQUEST["REGISTER"]["EMAIL"];?>
<?$_REQUEST["REGISTER"]["CONFIRM_PASSWORD"]=$_REQUEST["REGISTER"]["PASSWORD"];?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.register",
	"register",
	Array(
		"AUTH" => "Y",
		"COMPONENT_TEMPLATE" => "register",
		"REQUIRED_FIELDS" => array(),
		"SET_TITLE" => "Y",
		"SHOW_FIELDS" => array("EMAIL","NAME","PERSONAL_GENDER","PERSONAL_BIRTHDAY","PERSONAL_MOBILE","WORK_COMPANY","WORK_PHONE"),
		"SUCCESS_PAGE" => "/cabinet/",
		"USER_PROPERTY" => array("UF_TYPE"),
		"USER_PROPERTY_NAME" => "",
		"USE_BACKURL" => "Y"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>