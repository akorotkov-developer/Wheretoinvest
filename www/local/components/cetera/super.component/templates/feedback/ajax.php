<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?
if (check_bitrix_sessid() && isset($_REQUEST["ajax"])) {
    $arResult = Array();

    $Fields = Array(
        "MISTAKE",
        "COMMENT",
        "EMAIL"
    );

    $requiredFields = Array(
        "MISTAKE",
        "EMAIL"
    );

    $arFields = Array();
    foreach ($_REQUEST as $key => $val) {
        if (in_array($key, $Fields)) {
            if (in_array($key, $requiredFields) && empty($val)) {
                $arResult["ERRORS"][$key] = "Поля обязательно к заполнению.";
                continue;
            }
            $arFields[$key] = $val;
        }
    }

    if (count($arFields)) {
        CEvent::SendImmediate("SEND_MISTAKE", SITE_ID, $arFields, "N");
        $arResult["SUCCESS"] = "Спасибо! Сообщение об ошибке успешно отправлено.";
    }

    echo json_encode($arResult);
}
?>
