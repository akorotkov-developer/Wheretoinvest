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
    if("all" != $_REQUEST["method"]){
        $filter["UF_METHOD"] = explode(",", $_REQUEST["method"]);
    }
}else if(empty($_REQUEST["favorite"])){
    $filter["UF_METHOD"] = 3;
}

if (!empty($_REQUEST["favorite"])) {
    if (count($arFavoriteItems))
        $filter = Array("ID" => array_keys($arFavoriteItems));
    else return false;
}

$showToday = \Ceteralabs\UserVars::GetVar('PAID_ACCESS')["VALUE"];

if ($showToday !== "N") {
    $filter["<=UF_ACTIVE_START"] = date("d.m.Y");
    $filter[">=UF_ACTIVE_END"] = date("d.m.Y");
}


$query = Array();
if (!empty($filter))
    $query["filter"] = $filter;


$list = $hblock->getList($query);

$arResult["OFFER"] = Array();
$users = Array();
$offers = Array();

while ($el = $list->fetch()) {

    $today = new \DateTime();
    $today->setTime(0, 0, 0);


    foreach ($el["UF_ACTIVE_START"] as $key => $val) {
        if (!empty($val) && !empty($el["UF_ACTIVE_END"][$key])) {
            $start = new \DateTime($val->format("d.m.Y"));
            $end = new \DateTime($el["UF_ACTIVE_END"][$key]->format("d.m.Y"));

            $start->setTime(0, 0, 0);
            $end->setTime(0, 0, 0);

            if ($start <= $today && $end >= $today) {
                $interval = $start->diff($end);
                $interval = intval($interval->format("%R%a"));
                $el["UF_ACTIVE_DIFF"] = "с " . $start->format("d.m.Y") . " по " . $end->format("d.m.Y") . ", " . $interval . " " . \Cetera\Tools\Utils::pluralForm($interval, "сутки", "суток", "суток", "суток");
                break;
            }
        }
    }

    $arResult["OFFER"][$el["ID"]] = $el;
    $users[$el["UF_USER_ID"]] = $el["UF_USER_ID"];
    $offers[$el["ID"]] = $el["ID"];
}

$arResult["USERS"] = getUserSafety();

$arResult["USER_COUNT"] = count($arResult["USERS"]);

foreach ($arResult["OFFER"] as $key => $arItem) {
    if (!empty($arResult["USERS"][$arItem["UF_USER_ID"]])) {
        $arResult["OFFER"][$key]["USER"] = $arResult["USERS"][$arItem["UF_USER_ID"]];

    }
}

$filter = Array();

if (empty($_REQUEST["summ"]))
    $_REQUEST["summ"] = "1 500 000";

if (empty($_REQUEST["time"]))
    $_REQUEST["time"] = "369";

if (count($offers)) {

    $filter["UF_OFFER"] = $offers;

    $filter["UF_CURRENCY"] = !empty($_REQUEST["currency"]) ? $_REQUEST["currency"] : 28;

    if (!empty($_REQUEST["summ"])) {
        $filter["<=UF_SUMM"] = preg_replace("#[^\d]#is", "", $_REQUEST["summ"]);
    }

    if (!empty($_REQUEST["time"])) {
        $date = preg_replace("#[^\d]#is", "", $_REQUEST["time"]);
        if (strpos($_REQUEST["time"], ">") !== false) {
            $filter[">=UF_DATE_START"] = $date;
        } else {
            $filter["<=UF_DATE_START"] = $date;
            $filter[] = Array(
                "LOGIC" => "OR",
                Array(
                    ">=UF_DATE_END" => $date
                ),
                Array(
                    "UF_DATE_END" => false
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

    $query["order"] = Array("UF_DATE_START" => "DESC");

    if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
        $vars = $obCache->GetVars();
        $arResult = $vars;
    } elseif ($obCache->StartDataCache($cacheLifetime, $cacheID, $cachePath)) {
        $hblock = new \Cetera\HBlock\SimpleHblockObject(9);
        $list = $hblock->getList($query);

        while ($el = $list->fetch()) {
            if (floatval($el["UF_PERCENT"]) <= 0)
                continue;

            $offer = $arResult["OFFER"][$el["UF_OFFER"]];
            $user = $offer["USER"];
            unset($offer["USER"]);

            if (
                empty($arResult["ITEMS"][$el["UF_OFFER"]]) ||
                (
                    floatval($el["UF_PERCENT"]) > floatval($arResult["ITEMS"][$el["UF_OFFER"]]["UF_PERCENT"])
                    && intval($arResult["ITEMS"][$el["UF_OFFER"]]["UF_DATE_START"]) <= intval($el["UF_DATE_START"])
                    && intval($arResult["ITEMS"][$el["UF_OFFER"]]["UF_SUMM"]) < intval($el["UF_SUMM"])
                )
            ) {
                $el["UF_PERCENT"] = floatval($el["UF_PERCENT"]);
                $el["UF_DATE_START"] = intval($el["UF_DATE_START"]);
                $el["UF_DATE_END"] = intval($el["UF_DATE_END"]);
                $el["UF_CURRENCY"] = intval($el["UF_CURRENCY"]);
                $el["UF_SUMM"] = intval($el["UF_SUMM"]);
                $el["UF_METHOD"] = $arResult["FIELDS"]["UF_METHOD"][$offer["UF_METHOD"]];
                $el["UF_NAME"] = $offer["UF_NAME"];
                $el["UF_ORG"] = !empty($user["UF_FULL_WORK_NAME"]) ? $user["UF_FULL_WORK_NAME"] : $user["WORK_COMPANY"];
                $el["UF_SAFETY"] = $user["UF_SAFETY"];
                $el["UF_ASSETS"] = $user["UF_ASSETS"];


                $arResult["ITEMS"][$el["UF_OFFER"]] = $el;
                $arResult["ITEMS"][$el["UF_OFFER"]]["OFFER"] = $offer;
                $arResult["ITEMS"][$el["UF_OFFER"]]["USER"] = $user;
            }
        }

        $methods = getUserMethods();
        $obCache->EndDataCache($arResult);
    }



    //Задаем правильное место для банков
    //Сначала отсортируем их по Надежности
    $RSORT = array("safety"=>"A");
    $by="UF_SAFETY";
    $order = SORT_ASC;

    $tempOrder = Array();
    $tempPercent = Array();
    foreach ($arResult["ITEMS"] as $key => $arItem) {
        $tempOrder[$key] = $arItem[$by];
        $tempPercent[$key] = $arItem["UF_PERCENT"];
    }

    array_multisort($tempOrder, $order, $tempPercent, SORT_DESC, $arResult["ITEMS"]);


    //todo Не работает с ?method пока исключим его, т.к. организаций очень мало с этим фильтром
    if (!$_REQUEST["method"]) {

        //Теперь переберем массив и поменяем у него места надежности
        $i = 1;
        foreach ($arResult["ITEMS"] as $key => $item) {
            if ($item["UF_ORG"] && ((!$item["USER"]["UF_NOTE"] || $item["USER"]["UF_NOTE"] == "норм.") and $item["USER"]["UF_BANK_PARTICIP"] == 25)) {
                if ($arResult["ITEMS"][$key]["USER"]["UF_LICENSE"] == $arResult["ITEMS"][$lastkey]["USER"]["UF_LICENSE"]) {
                    $arResult["ITEMS"][$key]["UF_SAFETY"] = $i - 1;
                } else {
                    $arResult["ITEMS"][$key]["UF_SAFETY"] = $i;
                    $i++;
                }
                $lastkey = $key;
            } else {
                unset($arResult["ITEMS"][$key]);
            }
        }

        //Дефолтная сортирвка
        if (!$_REQUEST["SORT"]) {
            $_REQUEST["SORT"] = array('yield' => 'A');
        }

        //Сортировка банков
        if (!empty($_REQUEST["SORT"])) {
            $by = reset(array_keys($_REQUEST["SORT"]));
            $order = $_REQUEST["SORT"][$by] == "D" ? SORT_DESC : SORT_ASC;
            $by = "UF_" . strtoupper($by);
        }

        if (!empty($by) && !empty($order)) {
            $tempOrder = Array();
            $tempPercent = Array();
            foreach ($arResult["ITEMS"] as $key => $arItem) {
                $tempOrder[$key] = $arItem[$by];
                $tempPercent[$key] = $arItem["UF_PERCENT"];
            }

            array_multisort($tempOrder, $order, $tempPercent, SORT_DESC, $arResult["ITEMS"]);
        }


        /*Сортировка по дохдности вторая сортировка по активам*/
        if ($_REQUEST["SORT"]["yield"] == "A") {
            $s = false;
            $i = 0;
            $tempOrderPercent = array();
            foreach ($arResult["ITEMS"] as $key => $item) {
                if ($item["UF_PERCENT"] != 0.1) {
                    $tempOrderPercent[] = $arResult["ITEMS"][$key];
                } else {
                    $s = true;
                }
                if ($s) continue;
                $i++;
            }


            $index = $i + 1;
            $tempOrderDohod = array();
            while ($index < count($arResult["ITEMS"]) - 1) {
                $tempOrderDohod[] = $arResult["ITEMS"][$index];
                $index++;
            }


            // По возрастанию:
            function cmp_function($a, $b)
            {
                return ($a['UF_ASSETS'] > $b['UF_ASSETS']);
            }

            uasort($tempOrderDohod, 'cmp_function');
            $tempOrderDohod = array_reverse($tempOrderDohod);

            $arResult["ITEMS"] = array_merge($tempOrderPercent, $tempOrderDohod);
        }

    } else {
        //Дефолтная сортирвка
        if (!$_REQUEST["SORT"]) {
            $_REQUEST["SORT"] = array('yield' => 'A');
        }

        //Сортировка банков
        if (!empty($_REQUEST["SORT"])) {
            $by = reset(array_keys($_REQUEST["SORT"]));
            $order = $_REQUEST["SORT"][$by] == "D" ? SORT_DESC : SORT_ASC;
            $by = "UF_" . strtoupper($by);
        }

        if (!empty($by) && !empty($order)) {
            $tempOrder = Array();
            $tempPercent = Array();
            foreach ($arResult["ITEMS"] as $key => $arItem) {
                $tempOrder[$key] = $arItem[$by];
                $tempPercent[$key] = $arItem["UF_PERCENT"];
            }

            array_multisort($tempOrder, $order, $tempPercent, SORT_DESC, $arResult["ITEMS"]);
        }
    }
    /**********************************/

    // Задаем количество элементов на странице
    $countOnPage = $arParams["PAGE_COUNT"];
    // Исходный массив данных для списка
    $elements = $arResult["ITEMS"];
    // Получаем номер текущей страницы из реквеста
    if ($arParams["PAGING"] == "Y") {
        if ($_GET["page"]) {
            $page = $_GET["page"];
        } else $page = 1;
    }  else {
        $page = intval($_REQUEST['page']);
        if (empty($page))
            $page = 1;
    }

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