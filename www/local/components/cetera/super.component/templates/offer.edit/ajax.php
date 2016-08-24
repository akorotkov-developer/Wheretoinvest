<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?
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
        } else {
            $arResult["ERRORS"] = "Предложение не найдено, либо у Вас нет прав на удаление.";
        }
    } else {
        $avaliableFields = Array(
            "UF_NAME",
            "UF_METHOD",
            "UF_TYPE",
            "UF_REGIONS",
        );

        $arFields = Array();
        foreach ($_REQUEST as $key => $value) {
            if (!in_array($key, $avaliableFields))
                continue;

            $arFields[$key] = $value;
            $arResult["NEW"][$key] = $value;
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

        if (empty($arResult["ERRORS"]) && count($arFields)) {
            $arFields["UF_USER_ID"] = $USER->GetID();
            $hblock = new \Cetera\HBlock\SimpleHblockObject(3);
            if (intval($_REQUEST["ID"]) > 0) {
                $list = $hblock->getList(Array("filter" => Array("ID" => intval($_REQUEST["ID"]))));
                if ($el = $list->fetch()) {
                    $hblock->update($el["ID"], $arFields);
                    $arResult["ID"] = $el["ID"];
                } else {
                    $arResult["ERRORS"][] = "Ошибка сохранения. Предложение не найдено.";
                }
            } else {
                if ($res = $hblock->add($arFields)) {
                    $arResult["ID"] = $res->getId();
                }
            }
        }

        if (!empty($arResult["ID"])) {
            $matrix = is_array($_REQUEST["UF_MATRIX"]) ? $_REQUEST["UF_MATRIX"] : Array();

            $newMatrix = Array();
            foreach ($matrix as $currency => $cols) {
                $cur = preg_replace("#[^\d]#is", "", $currency);
                foreach ($cols as $col => $rows) {
                    foreach ($rows as $row => $percent) {
                        list($start, $end) = explode("-", $row);

                        $item = Array(
                            "UF_OFFER" => $arResult["ID"],
                            "UF_CURRENCY" => $cur,
                            "UF_DATE_START" => $start,
                            "UF_DATE_END" => $end,
                            "UF_SUMM" => preg_replace("#[^\d]#is", "", $col),
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
            unset($arResult["ERRORS"]);
        }

    }

    echo json_encode($arResult);
}
?>
