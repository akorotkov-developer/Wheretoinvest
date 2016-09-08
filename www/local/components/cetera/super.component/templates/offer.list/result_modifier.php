<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

// component text here
$arParams["PAGE_COUNT"] = intval($arParams["PAGE_COUNT"]) > 0 ? intval($arParams["PAGE_COUNT"]) : 10;

global $USER_FIELD_MANAGER;
global $USER;
global $APPLICATION;

$arFields = $USER_FIELD_MANAGER->GetUserFields("HLBLOCK_3");
$obEnum = new CUserFieldEnum;
$arResult["FIELDS"] = Array();

foreach ($arFields as $key => $arField) {
    if ($arField["USER_TYPE_ID"] == "enumeration") {
        $rsEnum = $obEnum->GetList(array(), array("USER_FIELD_ID" => $arField["ID"]));
        while ($arEnum = $rsEnum->GetNext()) {
            $arResult["FIELDS"][$key][$arEnum["ID"]] = $arEnum["VALUE"];
            $arResult["XML_FIELDS"][$key][$arEnum["ID"]] = $arEnum["XML_ID"];
        }
    }
}

$arFields = $USER_FIELD_MANAGER->GetUserFields("HLBLOCK_9");
foreach ($arFields as $key => $arField) {
    if ($arField["USER_TYPE_ID"] == "enumeration") {
        $rsEnum = $obEnum->GetList(array(), array("USER_FIELD_ID" => $arField["ID"]));
        while ($arEnum = $rsEnum->GetNext()) {
            $arResult["FIELDS"][$key][$arEnum["ID"]] = $arEnum["VALUE"];
            $arResult["XML_FIELDS"][$key][$arEnum["ID"]] = $arEnum["XML_ID"];
        }
    }
}

$loc = $APPLICATION->get_cookie("CURRENT_LOC_ID");

$hblock = new \Cetera\HBlock\SimpleHblockObject(10);
if ($USER->IsAuthorized())
    $list = $hblock->getList(Array("filter" => Array("UF_USER" => $USER->GetID())));
else {
    $hash = $APPLICATION->get_cookie("USER_HASH");
    if (empty($hash))
        $hash = md5(rand(1111111, 99999999) . date("c"));

    $APPLICATION->set_cookie("USER_HASH", $hash);

    $list = $hblock->getList(Array("filter" => Array("UF_USER_HASH" => $hash)));
}
$arFavorite = Array();
$arFavoriteItems = Array();

while ($el = $list->fetch()) {
    $arFavorite[$el["UF_OFFER"]] = $el["UF_FAVORITE"];

    if (!empty($el["UF_FAVORITE"]))
        $arFavoriteItems[$el["UF_OFFER"]] = $el["UF_OFFER"];
}

$hblock = new \Cetera\HBlock\SimpleHblockObject(3);
$filter = Array();
if (!empty($loc)) {
    $filter["UF_REGIONS"] = $loc;
}

if (!empty($_REQUEST["method"])) {
    $filter["UF_METHOD"] = intval($_REQUEST["method"]);
}

if (!empty($_REQUEST["favorite"])) {
    if (count($arFavoriteItems))
        $filter = Array("ID" => array_keys($arFavoriteItems));
    else return false;
}

$query = Array();
if (!empty($filter))
    $query["filter"] = $filter;

$list = $hblock->getList($query);

$arResult["OFFER"] = Array();
$users = Array();
$offers = Array();
while ($el = $list->fetch()) {
    $arResult["OFFER"][$el["ID"]] = $el;
    $users[$el["UF_USER_ID"]] = $el["UF_USER_ID"];
    $offers[$el["ID"]] = $el["ID"];
}
$arResult["USERS"] = getUserSafety();

$arResult["USER_COUNT"] = count($arResult["USERS"]);

foreach ($arResult["OFFER"] as $key => $arItem) {
    if (!empty($arResult["USERS"][$arItem["UF_USER_ID"]]))
        $arResult["OFFER"][$key]["USER"] = $arResult["USERS"][$arItem["UF_USER_ID"]];
}

$filter = Array();
if (count($offers)) {
    $filter["UF_OFFER"] = $offers;

    $filter["UF_CURRENCY"] = !empty($_REQUEST["currency"]) ? $_REQUEST["currency"] : 28;

    if (!empty($_REQUEST["summ"])) {
        $filter["<=UF_SUMM"] = preg_replace("#[^\d]#is", "", $_REQUEST["summ"]);
    }

    if (!empty($_REQUEST["time"])) {
        if (strpos($_REQUEST["time"], ">") !== false) {
            $filter[">=UF_DATE_START"] = preg_replace("#[^\d]#is", "", $_REQUEST["time"]);
        } else {
            $filter[">=UF_DATE_START"] = preg_replace("#[^\d]#is", "", $_REQUEST["time"]);
            $filter[] = Array(
                "LOGIC" => "OR",
                Array(
                    "<=UF_DATE_END" => preg_replace("#[^\d]#is", "", $_REQUEST["time"])
                ),
                Array(
                    "UF_DATE_END" => ""
                )
            );
        }
    }

    $query = Array();
    if (!empty($filter))
        $query["filter"] = $filter;

    $obCache = new CPHPCache();
    $cacheLifetime = 86400;
    $cacheString = "";
    if ($USER->IsAuthorized())
        $cacheString .= "userId=" . $USER->GetID();
    $cacheString .= http_build_query($query, '', '&');
    $cacheID = hash("md5", $cacheString);
    $cachePath = '/offers/';
    if ($USER->IsAuthorized())
        $cachePath .= $USER->GetID() . "/";
    else
        $cachePath .= "main/";

    $cachePath .= $cacheID;

    if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
        $vars = $obCache->GetVars();
        $arResult = $vars;
    } elseif ($obCache->StartDataCache()) {
        $hblock = new \Cetera\HBlock\SimpleHblockObject(9);
        $list = $hblock->getList($query);

        while ($el = $list->fetch()) {
            if (floatval($el["UF_PERCENT"]) <= 0)
                continue;

            $offer = $arResult["OFFER"][$el["UF_OFFER"]];
            $user = $offer["USER"];
            $user["RATING"] = is_array($rating[$user["ID"]]) ? implode("<br>", $rating[$user["ID"]]) : "";
            unset($offer["USER"]);

            if (empty($arResult["ITEMS"][$el["UF_OFFER"]]) || $el["UF_PERCENT"] > $arResult["ITEMS"][$el["UF_OFFER"]]["UF_PERCENT"]) {
                $el["UF_PERCENT"] = floatval($el["UF_PERCENT"]);
                $el["UF_DATE_START"] = intval($el["UF_DATE_START"]);
                $el["UF_DATE_END"] = intval($el["UF_DATE_END"]);
                $el["UF_CURRENCY"] = intval($el["UF_CURRENCY"]);
                $el["UF_SUMM"] = intval($el["UF_SUMM"]);
                $el["UF_METHOD"] = $arResult["FIELDS"]["UF_METHOD"][$offer["UF_METHOD"]];
                $el["UF_NAME"] = $offer["UF_NAME"];
                $el["UF_ORG"] = !empty($user["UF_FULL_WORK_NAME"]) ? $user["UF_FULL_WORK_NAME"] : $user["WORK_COMPANY"];
                $el["UF_SAFETY"] = $user["UF_SAFETY"];

                $arResult["ITEMS"][$el["UF_OFFER"]] = $el;
                $arResult["ITEMS"][$el["UF_OFFER"]]["OFFER"] = $offer;
                $arResult["ITEMS"][$el["UF_OFFER"]]["USER"] = $user;
            }
        }

        $methods = getUserMethods();


        $obCache->EndDataCache($arResult);
    }

    $by = "UF_PERCENT";
    $order = SORT_DESC;

    if (!empty($_REQUEST["SORT"])) {
        $by = reset(array_keys($_REQUEST["SORT"]));
        $order = $_REQUEST["SORT"][$by] == "D" ? SORT_DESC : SORT_ASC;
        $by = "UF_" . strtoupper($by);
    }

    if (!empty($by) && !empty($order)) {
        $tempOrder = Array();
        foreach ($arResult["ITEMS"] as $key => $arItem) {
            $tempOrder[$key] = $arItem[$by];
        }

        array_multisort($tempOrder, $order, $arResult["ITEMS"]);
    }


    // Задаем количество элементов на странице
    $countOnPage = $arParams["PAGE_COUNT"];
    // Исходный массив данных для списка
    $elements = $arResult["ITEMS"];
    // Получаем номер текущей страницы из реквеста
    $page = intval($_REQUEST['page']);
    if (empty($page))
        $page = 1;

    // Отбираем элементы текущей страницы
    $elementsPage = array_slice($elements, ($page - 1) * $countOnPage, $countOnPage);
    $arResult["ITEMS"] = $elementsPage;

    // Подготовка параметров для пагинатора
    $arResult["NAV_PAGE_NUM"] = $page;
    $arResult["NAV_PAGE_COUNT"] = ceil(count($elements) / $countOnPage);
}

// saving template name to cache array
$arResult["__TEMPLATE_FOLDER"] = $this->__folder;

// writing new $arResult to cache file
$this->__component->arResult = $arResult;