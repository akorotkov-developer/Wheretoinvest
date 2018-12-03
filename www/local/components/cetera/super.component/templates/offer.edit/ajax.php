<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?
use Wic\BanksInfo\Config;
global $USER;
$USER = getContainer("User");

if ($USER->IsAuthorized() && $USER->isPartner() && check_bitrix_sessid() && !empty($_REQUEST)) {
    $arResult = Array();
    if (!empty($_REQUEST["del"]) && !empty($_REQUEST["ID"])) {
        $hblock = new \Cetera\HBlock\SimpleHblockObject(3);
        $list = $hblock->getList(Array("filter" => Array("ID" => intval($_REQUEST["ID"]), "UF_USER_ID" => $USER->GetID())));
        if ($el = $list->fetch()) {
            $matrix = new \Cetera\HBlock\SimpleHblockObject(9);
            $matrixList = $matrix->getList(Array("filter" => Array("UF_OFFER" => $el["ID"])));
            while ($e = $matrixList->fetch()) {
                $matrix->delete($e["ID"]);
            }
            $hblock->delete($el["ID"]);

            $arResult["SUCCESS"] = "Предложение успешно удалено.";
            $_SESSION["SUCCESS"] = $arResult["SUCCESS"];

            //Добавляем пустое предложение, если у банка больше не осталось предложений, чтобы он выводился на главной странице
            //Проверяем есть ли предлжение с таким ID
            $itemHblockOffer = new \Cetera\HBlock\SimpleHblockObject(Config::HOFFRES);
            $itemHblockMatrix = new \Cetera\HBlock\SimpleHblockObject(Config::HMATRIX);
            $idElem = array();
            $filter["UF_USER_ID"] = $USER->GetID();
            $query["filter"] = $filter;
            $list = $itemHblockOffer->getList($query);
            while ($el = $list->fetch()) {
                $idElem[] = $el;
            }

            $offerID = $idElem[0]["ID"];
            mail("89065267799@mail.ru", "Тема письма", print_r($offerID, true));
            $findMatrix = false;
            //Если нет предложения, то создадим его
            if (!$offerID) {
                //Если нет создаем предложение
                $date = Config::ENDDATEOFFER;
                $date = strtotime($date); // переводит из строки в дату
                $dateEnd = date("d.m.Y", $date);

                $offeritems = array(
                    "UF_USER_ID"=>$USER->GetID(),
                    "UF_METHOD"=>Config::OFFER_UF_METHOD,
                    "UF_NAME"=>"-",
                    "UF_TYPE"=>Config::OFFER_UF_TYPE,
                    "UF_REGIONS"=>Config::ALL_REGIONS,
                    "UF_UPDATED"=>date("d.m.Y H:i:s"),
                    "UF_SITE"=>"",
                    "UF_ACTIVE_START"=>array(date("d.m.Y")),
                    "UF_ACTIVE_END"=>array($dateEnd)
                );
                $offerID = $itemHblockOffer->add($offeritems)->getId();
                //Добавляем матрицу
                $item = array(
                    "UF_OFFER" => $offerID,
                    "UF_SUMM" => Config::MATRIX_UF_SUMM,
                    "UF_DATE_START" => Config::MATRIX_UF_DATE_START,
                    "UF_CURRENCY" => Config::MATRIX_UF_CURRENCY,
                    "UF_PERCENT" => Config::MATRIX_UF_PERCENT
                );
                $itemHblockMatrix->add($item);
            }


        } else {
            $arResult["ERRORS"] = "Предложение не найдено, либо у Вас нет прав на удаление.";
        }
    } else {
        $avaliableFields = Array(
            "UF_NAME",
            "UF_METHOD",
            "UF_TYPE",
            "UF_REGIONS",
            "UF_SITE",
            "UF_ACTIVE_START",
            "UF_ACTIVE_END",
            "UF_ACTIVE_COST",
        );

        $arFields = Array();
        foreach ($_REQUEST as $key => $value) {
            if (!in_array($key, $avaliableFields))
                continue;

            $arFields[$key] = is_array($value) ? $value : trim($value);
            $arResult["NEW"][$key] = is_array($value) ? $value : trim($value);
        }

        if (empty($arFields["UF_NAME"])) {
            $arResult["ERRORS"][] = "Название обязательно к заполнению.";
        }
        if (empty($arFields["UF_METHOD"])) {
            $arResult["ERRORS"][] = "Не указан способ вложения.";
        }
        if (empty($arFields["UF_REGIONS"]) || (is_array($arFields["UF_REGION"]) && !count($arFields["UF_REGION"]))) {
            $arResult["ERRORS"][] = "Не выбран регион действия предложения.";
        }
        if (empty($arFields["UF_TYPE"])) {
            $arResult["ERRORS"][] = "Не выбран тип лица. Обратитесь к администратору.";
        }
        if (!empty($arFields["UF_SITE"]) && !preg_match("@^http(s)?://[a-zа-я0-9-]+(.[a-zа-я0-9-]+)*(:[0-9]+)?(/.*)?$@i", $arFields["UF_SITE"])) {
            $arResult["ERRORS"][] = "Неверная ссылка на сайт.";
        }

        if (empty($arResult["ERRORS"]) && count($arFields)) {
            $arFields["UF_UPDATED"] = date("d.m.Y H:i:s");

            $hblock = new \Cetera\HBlock\SimpleHblockObject(3);
            $arFields["UF_USER_ID"] = $USER->GetID();
            $price = floatval(\Ceteralabs\UserVars::GetVar('PUBLICATION_COST')["VALUE"]);

            if (intval($_REQUEST["ID"]) > 0) {
                $list = $hblock->getList(Array("filter" => Array("ID" => intval($_REQUEST["ID"]))));
                if ($el = $list->fetch()) {
                    if (!empty($arFields["UF_ACTIVE_START"]) && !empty($arFields["UF_ACTIVE_END"])) {
                        $startDate = date("d.m.Y", strtotime($arFields["UF_ACTIVE_START"]));
                        $endDate = date("d.m.Y", strtotime($arFields["UF_ACTIVE_END"]));

                        $startDateVal = new DateTime($startDate);
                        $endDateVal = new DateTime($endDate);

                        $arFields["UF_ACTIVE_START"] = is_array($el["UF_ACTIVE_START"]) ? $el["UF_ACTIVE_START"] : Array();
                        $arFields["UF_ACTIVE_END"] = is_array($el["UF_ACTIVE_END"]) ? $el["UF_ACTIVE_END"] : Array();
                        $arFields["UF_ACTIVE_COST"] = is_array($el["UF_ACTIVE_COST"]) ? $el["UF_ACTIVE_COST"] : Array();

                        foreach ($arFields["UF_ACTIVE_START"] as $key => $val) {
                            $startVal = $val;
                            $endVal = $arFields["UF_ACTIVE_END"][$key];

                            if (!empty($startVal) && !empty($endVal)) {
                                $startVal = new DateTime($startVal);
                                $endVal = new DateTime($endVal);

                                if (($startVal <= $startDateVal && $endVal >= $startDateVal) || ($startDateVal <= $startVal && $endDateVal >= $startVal)) {
                                    $arResult["ERRORS"][] = "Имеются пересечения по срокам активации.";
                                    break;
                                }
                            }
                        }

                        $arFields["UF_ACTIVE_START"][] = $startDate;
                        $arFields["UF_ACTIVE_END"][] = $endDate;
                        $arFields["UF_ACTIVE_COST"][] = $price;
                    }
                    else {
                        unset($arFields["UF_ACTIVE_START"]);
                        unset($arFields["UF_ACTIVE_END"]);
                        unset($arFields["UF_ACTIVE_COST"]);
                    }

                    if (empty($arResult["ERRORS"])) {
                        $hblock->update($el["ID"], $arFields);
                        $arResult["ID"] = $el["ID"];
                    }
                } else {
                    $arResult["ERRORS"][] = "Ошибка сохранения. Предложение не найдено.";
                }
            } else {
                if (!empty($arFields["UF_ACTIVE_START"]) && !empty($arFields["UF_ACTIVE_END"])) {
                    $startDate = date("d.m.Y", strtotime($arFields["UF_ACTIVE_START"]));
                    $endDate = date("d.m.Y", strtotime($arFields["UF_ACTIVE_END"]));
                    $price = floatval(\Ceteralabs\UserVars::GetVar('PUBLICATION_COST')["VALUE"]);

                    $arFields["UF_ACTIVE_START"] = Array();
                    $arFields["UF_ACTIVE_END"] = Array();
                    $arFields["UF_ACTIVE_COST"] = Array();

                    $arFields["UF_ACTIVE_START"][] = $startDate;
                    $arFields["UF_ACTIVE_END"][] = $endDate;
                    $arFields["UF_ACTIVE_COST"][] = $price;
                }
                else {
                    unset($arFields["UF_ACTIVE_START"]);
                    unset($arFields["UF_ACTIVE_END"]);
                    unset($arFields["UF_ACTIVE_COST"]);
                }

                if ($res = $hblock->add($arFields)) {
                    $arResult["ID"] = $res->getId();

                    //После добавления предложения проверяем есть ли пустое предложения для этого банка
                    $itemHblockOffer = new \Cetera\HBlock\SimpleHblockObject(Config::HOFFRES);
                    $itemHblockMatrix = new \Cetera\HBlock\SimpleHblockObject(Config::HMATRIX);

                    //Проверяем есть ли предлжение с таким ID
                    $idElem = array();
                    $filter["UF_USER_ID"] = $USER->GetID();
                    $query["filter"] = $filter;
                    $list = $itemHblockOffer->getList($query);
                    while ($el = $list->fetch()) {
                        $idElem[] = $el;
                    }

                    $offerID = $idElem[0]["ID"];
                    if ($offerID) {
                        //Если предложение есть, то ищем Матрицу в HBlock Matrix создаем Матрицу в HBlock Matrix
                        $filter = array();
                        $filter["UF_OFFER"] = $offerID;
                        $query["filter"] = $filter;
                        $list = $itemHblockMatrix->getList($query);
                        $Elem = false;
                        while ($el = $list->fetch()) {
                            $Elem[] = $el;
                        }

                        if ($Elem) {
                            $matrixID = $Elem[0]["ID"];
                        }
                    }

                    //Удаляем предложение и матрицу
                    $itemHblockOffer->delete($offerID);
                    $itemHblockMatrix->delete($matrixID);
                }
            }

            if (empty($arResult["ERRORS"])) {
                if (!empty($_REQUEST["UF_ACTIVE_START"]) && !empty($_REQUEST["UF_ACTIVE_END"])) {
                    $startDate = new DateTime($_REQUEST["UF_ACTIVE_START"]);
                    $endDate = new DateTime($_REQUEST["UF_ACTIVE_END"]);

                    $interval = $startDate->diff($endDate);
                    $interval = intval($interval->format("%R%a")) + 1;

                    $summ = $interval * $price;
                    $cash = floatval(getContainer("User")["UF_CASH"]);

                    if ($cash >= $summ) {
                        $cUser = new \CUser();
                        $cUser->Update($USER->GetID(), Array("UF_CASH" => ($cash - $summ)));
                    } else {
                        $arResult["ERRORS"][] = "У Вас недостаточно средств на счете для активации данного предложения.";
                    }
                }
            }
        }

        if (!empty($arResult["ID"])) {
            $matrix = is_array($_REQUEST["UF_MATRIX"]) ? $_REQUEST["UF_MATRIX"] : Array();

            $newMatrix = Array();
            foreach ($matrix as $currency => $cols) {
                $cur = preg_replace("#[^\d]#is", "", $currency);
                foreach ($cols as $col => $rows) {
                    list($summStart, $summEnd) = explode("-", $col);

                    foreach ($rows as $row => $percent) {
                        list($start, $end) = explode("-", $row);

                        $item = Array(
                            "UF_OFFER" => $arResult["ID"],
                            "UF_CURRENCY" => $cur,
                            "UF_DATE_START" => $start,
                            "UF_DATE_END" => $end,
                            "UF_SUMM" => preg_replace("#[^\d]#is", "", $summStart),
                            "UF_SUMM_END" => preg_replace("#[^\d]#is", "", $summEnd),
                            "UF_PERCENT" => floatval($percent)
                        );

                        $newMatrix[] = $item;
                    }
                }
            }

            $arResult["MATRIX"] = $newMatrix;

            $hblock = new \Cetera\HBlock\SimpleHblockObject(9);
            $list = $hblock->getList(Array("filter" => Array("UF_OFFER" => $arResult["ID"])));
            $matrixList = Array();
            while ($el = $list->fetch()) {
                $matrixList[] = $el["ID"];
            }

            if (count($newMatrix)) {
                foreach ($matrixList as $key => $listId) {
                    if (count($newMatrix)) {
                        $item = array_shift($newMatrix);
                        $hblock->update($listId, $item);
                        unset($matrixList[$key]);
                    }
                }
            }

            foreach ($newMatrix as $item) {
                $hblock->add($item);
            }

            foreach ($matrixList as $listId) {
                $hblock->delete($listId);
            }
        }

        $arResult["ERRORS"] = is_array($arResult["ERRORS"]) ? implode("<br>", $arResult["ERRORS"]) : "";

        if (empty($arResult["ERRORS"])) {
            $arResult["SUCCESS"] = "Данные успешно сохранены.";
            $_SESSION["SUCCESS"] = $arResult["SUCCESS"];

            $obCache = new CPHPCache();
            $obCache->CleanDir("/offers/");
            unset($arResult["ERRORS"]);
        }

    }

    echo json_encode($arResult);
}
?>
