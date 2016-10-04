<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arParams */
/** @var array $arResult */
/** @global CUser $USER */
global $USER;
/** @global CMain $APPLICATION */
global $APPLICATION;

$APPLICATION->SetTitle("Моя подписка");
$APPLICATION->AddChainItem("Моя подписка" );
?>
<? $APPLICATION->IncludeComponent(
    "bitrix:subscribe.edit",
    "subscribe_edit",
    Array(
        "SHOW_HIDDEN" => "N",	// Показать скрытые рубрики подписки
        "ALLOW_ANONYMOUS" => "Y",	// Разрешить анонимную подписку
        "SHOW_AUTH_LINKS" => "Y",	// Показывать ссылки на авторизацию при анонимной подписке
        "CACHE_TIME" => "36000000",	// Время кеширования (сек.)
        "SET_TITLE" => "N",	// Устанавливать заголовок страницы
    ),
    false
); ?>
