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

function getContainer($varName)
{
    return \Cetera\Tools\DIContainer::$DIC[$varName];
}