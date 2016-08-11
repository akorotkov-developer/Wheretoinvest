<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arTemplateParameters = array(
    "MODAL_ID" => array(
        "PARENT" => "OVERALL",
        "NAME" => "ID модального окна",
        "TYPE" => "STRING",
        "DEFAULT" => "saleLocation",
    ),
    "COL_CNT" => array(
        "PARENT" => "OVERALL",
        "NAME" => "Количество столбцов",
        "TYPE" => "STRING",
        "DEFAULT" => "3",
    ),
    "EMPTY_NAME" => array(
        "PARENT" => "OVERALL",
        "NAME" => "Название ссылка по-умолчанию",
        "TYPE" => "STRING",
        "DEFAULT" => "Выберите регион",
    ),
);
?>