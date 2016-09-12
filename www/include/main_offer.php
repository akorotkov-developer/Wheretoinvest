<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<? $APPLICATION->IncludeComponent(
    "cetera:super.component",
    "offer.list",
    array(
        "COMPONENT_TEMPLATE" => "offer.list",
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "1",
        "PAGE_COUNT" => 10
    ),
    false
); ?>