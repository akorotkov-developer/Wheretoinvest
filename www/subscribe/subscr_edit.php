<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Редактирование подписки");
?><? $APPLICATION->IncludeComponent("bitrix:subscribe.edit", "confirm", Array(
	"SHOW_HIDDEN" => "N",	// Показать скрытые рубрики подписки
		"ALLOW_ANONYMOUS" => "Y",	// Разрешить анонимную подписку
		"SHOW_AUTH_LINKS" => "Y",	// Показывать ссылки на авторизацию при анонимной подписке
		"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"SET_TITLE" => "Y",	// Устанавливать заголовок страницы
	),
	false
); ?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>