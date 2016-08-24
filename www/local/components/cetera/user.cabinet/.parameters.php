<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!CModule::IncludeModule("iblock")) {
    return;
}

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arIBlock = array();
$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE" => "Y"));
while ($arr = $rsIBlock->Fetch()) {
    $arIBlock[$arr["ID"]] = "[" . $arr["ID"] . "] " . $arr["NAME"];
}

$arAscDesc = array(
    "asc" => GetMessage("IBLOCK_SORT_ASC"),
    "desc" => GetMessage("IBLOCK_SORT_DESC"),
);

$arComponentParameters = array(
    "GROUPS" => array(
        "REVIEW_SETTINGS" => array(
            "NAME" => GetMessage("T_IBLOCK_DESC_REVIEW_SETTINGS"),
        ),
        "PRICES" => array(
            "NAME" => GetMessage("IBLOCK_PRICES"),
        ),
        "TOP_SETTINGS" => array(
            "NAME" => GetMessage("T_IBLOCK_DESC_TOP_SETTINGS"),
        ),
        "LIST_SETTINGS" => array(
            "NAME" => GetMessage("T_IBLOCK_DESC_LIST_SETTINGS"),
        ),
        "DETAIL_SETTINGS" => array(
            "NAME" => GetMessage("T_IBLOCK_DESC_DETAIL_SETTINGS"),
        ),
        "LINK" => array(
            "NAME" => GetMessage("IBLOCK_LINK"),
        ),
    ),
    "PARAMETERS" => array(
        "VARIABLE_ALIASES" => Array(),
        "SEF_MODE" => Array(
            "index" => array(
                "NAME" => GetMessage("MAIN_PAGE"),
                "DEFAULT" => "",
                "VARIABLES" => array(),
            ),
            "method" => array(
                "NAME" => "Методика определения надежности",
                "DEFAULT" => "method/",
                "VARIABLES" => array(),
            ),
            "region" => array(
                "NAME" => "Регион",
                "DEFAULT" => "region/",
                "VARIABLES" => array(),
            ),
            "details" => array(
                "NAME" => "Реквизиты организации",
                "DEFAULT" => "details/",
                "VARIABLES" => array(),
            ),
            "details_edit" => array(
                "NAME" => "Редактирование реквизитов организации",
                "DEFAULT" => "details/edit/",
                "VARIABLES" => array(),
            ),
            "gov" => array(
                "NAME" => "Участие государства",
                "DEFAULT" => "gov/",
                "VARIABLES" => array(),
            ),
            "rating" => array(
                "NAME" => "Рейтинги организации",
                "DEFAULT" => "rating/",
                "VARIABLES" => array(),
            ),
            "rating_edit" => array(
                "NAME" => "Редактировать рейтинги",
                "DEFAULT" => "rating_edit/",
                "VARIABLES" => array(),
            ),
            "assets" => array(
                "NAME" => "Ключевые показатели отчётности",
                "DEFAULT" => "assets/",
                "VARIABLES" => array(),
            ),
            "offers" => array(
                "NAME" => "Список предложение",
                "DEFAULT" => "offers/#type#/",
                "VARIABLES" => array(
                    "type" => "type"
                ),
            ),
            "offers_edit" => array(
                "NAME" => "Редактирование предложения",
                "DEFAULT" => "offers/#type#/edit/#ID#/",
                "VARIABLES" => array(
                    "type" => "type",
                    "ID" => "ID"
                ),
            ),
            "offers_add" => array(
                "NAME" => "Создание предложения",
                "DEFAULT" => "offers/#type#/add/",
                "VARIABLES" => array(
                    "type" => "type"
                ),
            ),
            /*"profile" => array(
                "NAME" => GetMessage("PROFILE_PAGE"),
                "DEFAULT" => "profile/",
                "VARIABLES" => array(),
            ),*/
        ),
        "CACHE_TIME" => Array("DEFAULT" => 3600),
        "SET_STATUS_404" => Array(
            "NAME" => GetMessage("TITLE_SET_STATUS_404"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y"
        ),
        "PAGE_COUNT" => Array(
            "NAME" => GetMessage("TITLE_PAGE_COUNT"),
            "TYPE" => "STRING",
            "DEFAULT" => "10"
        ),
        "NAME_TEMPLATE" => Array(
            "NAME" => GetMessage("TITLE_NAME_TEMPLATE"),
            "TYPE" => "STRING",
            "DEFAULT" => "#LAST_NAME# #NAME# #SECOND_NAME#"
        ),
        "FILTER_NAME" => Array(
            "NAME" => GetMessage("TITLE_FILTER_NAME"),
            "TYPE" => "STRING",
            "DEFAULT" => "arrFilter"
        ),
        "PAGER_TEMPLATE" => Array(
            "NAME" => GetMessage("TITLE_PAGER_TEMPLATE"),
            "TYPE" => "STRING",
            "DEFAULT" => ""
        ),
    ),
);