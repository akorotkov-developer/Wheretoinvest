<?php
/**
 * Common functions
 */

/**
 * Лог в файл + backtrace
 * Путь до файла: /local/php_interface/wic.log
 * @param mixed $variable1 ,$variable2,...
 */
function writeLog()
{
    $logFileName = '/local/php_interface/wic.log';

    $backtrace = debug_backtrace();
    $backtracePath = array();
    foreach ($backtrace as $k => $bt) {
        if ($k > 2)
            break;
        $backtracePath[] = substr($bt['file'], strlen($_SERVER['DOCUMENT_ROOT'])) . ':' . $bt['line'];
    }

    $data = func_get_args();
    if (count($data) == 0)
        return;
    elseif (count($data) == 1)
        $data = current($data);

    if (!is_string($data) && !is_numeric($data))
        $data = var_export($data, 1);

    file_put_contents($_SERVER['DOCUMENT_ROOT'] . $logFileName, "\n--------------------------" . date('Y-m-d H:i:s ') . microtime() . "-----------------------\n Backtrace: " . implode(' → ', $backtracePath) . "\n" . $data, FILE_APPEND);
}

/**
 * Удалить из GET параметр SHOWALL (отображает все элементы на 1 странице)
 * почти всегда это приводит к полному зависанию сервака
 */
function clearShowAll()
{
    if (!empty($_GET))
        foreach ($_GET as $key => $value) {
            if (strpos($key, 'SHOWALL') === 0)
                unset($_GET[$key]);
        }
}

function cl($text, $return = false)
{
    global $USER;
    if (($USER->IsAuthorized() || $return)) {
        if (function_exists("dump")) {
            dump($text);
        } else {
            echo "<pre style='background: #ffffff; display: block; clear: both;'>";
            var_dump($text);
            echo "</pre>";
        }
    }
}

function getUserMethods()
{
    global $USER;
    global $USER_FIELD_MANAGER;

    $obCache = new CPHPCache();
    $cacheLifetime = 86400 * 7;
    $cacheID = 'user_method';
    if ($USER->IsAuthorized())
        $cacheID .= "_" . $USER->GetID();

    $cachePath = '/user_methods/' . $cacheID;
    $arMethods = Array();

    if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
        $arMethods = $obCache->GetVars();
    } elseif ($obCache->StartDataCache()) {
        $arFields = $USER_FIELD_MANAGER->GetUserFields("HLBLOCK_6");
        $obEnum = new CUserFieldEnum;

        foreach ($arFields as $key => $arField) {
            if ($arField["USER_TYPE_ID"] == "enumeration") {
                $rsEnum = $obEnum->GetList(array(), array("USER_FIELD_ID" => $arField["ID"]));
                while ($arEnum = $rsEnum->GetNext()) {
                    if ($key == "UF_METHOD") {
                        $arMethods[$arEnum["ID"]] = Array(
                            "ID" => $arEnum["ID"],
                            "NAME" => $arEnum["VALUE"],
                            "ACTIVE" => true,
                            "SORT" => intval($arEnum["SORT"]),
                            "CODE" => $arEnum["XML_ID"]
                        );
                    }
                }
            }
        }

        if ($USER->IsAuthorized()) {
            $hblock = new \Cetera\HBlock\SimpleHblockObject(6);
            $list = $hblock->getList(Array("filter" => Array("UF_USER" => $USER->GetID())));
            while ($el = $list->fetch()) {
                $arMethods[$el["UF_METHOD"]]["ACTIVE"] = !empty($el["UF_ACTIVE"]) ? true : false;
                $arMethods[$el["UF_METHOD"]]["SORT"] = intval($el["UF_SORT"]);
            }
        }

        $sort = Array();
        foreach ($arMethods as $key => $val) {
            $sort[$val["SORT"]] = $val;
        }

        ksort($sort);
        $arMethods = $sort;

        $obCache->EndDataCache($arMethods);
    }

    return $arMethods;
}

function getUserSafety()
{
    global $USER;

    $obCache = new CPHPCache();
    $cacheLifetime = 86400;
    $cacheString = "userList";
    if ($USER->IsAuthorized())
        $cacheString .= "&userId=" . $USER->GetID();
    $cacheID = hash("md5", $cacheString);
    $cachePath = '/offers/users/';
    if ($USER->IsAuthorized())
        $cachePath .= $USER->GetID() . "/";
    $cachePath .= $cacheID;
    $arResult["USERS"] = Array();

    if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
        $vars = $obCache->GetVars();
        $arResult["USERS"] = $vars["USERS"];
    } elseif ($obCache->StartDataCache()) {
        $ratingList = Array();
        $hblock = new \Cetera\HBlock\SimpleHblockObject(8);
        $list = $hblock->getList();
        while ($el = $list->fetch()) {
            $ratingList[$el["ID"]] = $el;
        }

        $rating = Array();
        $ratingDetail = Array();
        $hblock = new \Cetera\HBlock\SimpleHblockObject(7);
        $list = $hblock->getList();
        while ($el = $list->fetch()) {
            if (!empty($el["UF_RATING"])) {
                if (!empty($el["UF_UPDATED"]))
                    $arResult["RATING_UPDATED"][$el["UF_USER"]] = $el["UF_UPDATED"];

                $rating[$el["UF_USER"]][$ratingList[$el["UF_AGENCY"]]["UF_NAME"]] = $el["UF_RATING"];
                if (empty($ratingDetail[$el["UF_USER"]]))
                    $ratingDetail[$el["UF_USER"]] = 0;

                foreach ($ratingList[$el["UF_AGENCY"]]["UF_SCALE"] as $key => $val) {
                    if ($val["VALUE"] == $el["UF_RATING"]) {
                        $i = intval($val["DESCRIPTION"]);
                        if (empty($ratingDetail[$el["UF_USER"]]) || $i < $ratingDetail[$el["UF_USER"]]) {
                            $ratingDetail[$el["UF_USER"]] = $i;
                            break;
                        }
                    }
                }
            }
        }

        $arResult["USER_SORT"] = Array();
        $rsUsers = \CUser::GetList(($by = "ID"), ($order = "ASC"), Array("GROUPS_ID" => Array(PARTNER_GROUP)), Array("SELECT" => Array("UF_*")));
        while ($arUser = $rsUsers->GetNext()) {
            $name = Array();
            $name[] = $arUser["WORK_COMPANY"];
            if (!empty($arUser["UF_OGRN"]))
                $name[] = "ОГРН " . $arUser["UF_OGRN"];
            if (!empty($arUser["UF_LICENSE"]))
                $name[] = "Лицензия ЦБ № " . $arUser["UF_LICENSE"];
            $arUser["FULL_WORK_COMPANY"] = implode(", ", $name);

            $arResult["USERS"][$arUser["ID"]] = $arUser;
            $arResult["USER_SORT"][$arUser["ID"]] = Array(
                "ID" => $arUser["ID"],
                "RATING" => intval($ratingDetail[$arUser["ID"]]) > 0 ? intval($ratingDetail[$arUser["ID"]]) : 1000,
                "GOV" => !empty($arUser["UF_STATE_PARTICIP"]) ? 1 : 100,
                "CAPITAL" => floatval(preg_replace("#[^\d\.]#is", "", preg_replace("#,#is", ".", $arUser["UF_CAPITAL"]))),
                "CAPITAL_TO_ASSETS" => floatval(preg_replace("#,#is", ".", $arUser["UF_CAPITAL_ASSETS"])),
                "ASSETS" => floatval(preg_replace("#[^\d\.]#is", "", preg_replace("#,#is", ".", $arUser["UF_ASSETS"]))),
                "UPDATED" => strtotime($arUser["TIMESTAMP_X"]),
            );
        }

        $userMethods = getUserMethods();
        $arr = Array();
        foreach ($userMethods as $key => $method) {
            if (!$method["ACTIVE"])
                continue;

            foreach ($arResult["USER_SORT"] as $userId => $fields) {
                $arr[$method["CODE"]][$userId] = $fields[$method["CODE"]];
            }

            $arr[] = SORT_NUMERIC;
            switch ($method["CODE"]) {
                case "GOV":
                case "RATING":
                    $arr[] = SORT_ASC;
                    break;
                default:
                    $arr[] = SORT_DESC;
            }
        }

        $arr[] = &$arResult["USER_SORT"];
        call_user_func_array("array_multisort", $arr);

        $i = 1;
        foreach ($arResult["USER_SORT"] as $sort => $item) {
            $arResult["USERS"][$item["ID"]]["UF_SAFETY"] = $i;
            ++$i;
        }

        foreach ($arResult["USERS"] as $key => $arUser) {
            $arResult["USERS"][$key]["RATING_UPDATED"] = $arResult["RATING_UPDATED"][$key];
            $arResult["USERS"][$key]["RATING"] = $rating[$key];
        }

        $obCache->EndDataCache(Array("USERS" => $arResult["USERS"]));
    }

    return $arResult["USERS"];
}

function getContainer($varName)
{
    return \Cetera\Tools\DIContainer::$DIC[$varName];
}