<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Сравниваем доходность и надежность вкладов в банках, займов микрофинансовым организациям.");
$APPLICATION->SetTitle("Куда вложить – простой удобный поиск и сравнение инвестиций");
?>
    <br class="show-for-medium">
<? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array(
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "",
        "PATH" => "/include/main_offer.php",
        "AREA_FILE_RECURSIVE" => "Y",
        "EDIT_TEMPLATE" => "standard.php"
    )
); ?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>