<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arParams */
/** @var array $arResult */
/** @global CUser $USER */
global $USER;
/** @global CMain $APPLICATION */
global $APPLICATION;

$APPLICATION->SetTitle("Редактирование подписки");
$APPLICATION->AddChainItem("Редактирование подписки" );
?>
<? $APPLICATION->IncludeComponent(
    "bitrix:subscribe.edit",
    "subscribe_edit",
    Array(
        "SHOW_HIDDEN" => "N",	// Показать скрытые рубрики подписки
        "ALLOW_ANONYMOUS" => "Y",	// Разрешить анонимную подписку
        "SHOW_AUTH_LINKS" => "Y",	// Показывать ссылки на авторизацию при анонимной подписке
        "CACHE_TIME" => "36000000",	// Время кеширования (сек.)
        "SET_TITLE" => "Y",	// Устанавливать заголовок страницы
    ),
    false
); ?>
