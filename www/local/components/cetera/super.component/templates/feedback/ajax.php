<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?
global $APPLICATION;
$arResult = Array();

if (check_bitrix_sessid() && isset($_REQUEST["ajax"])) {
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

    if (!$APPLICATION->CaptchaCheckCode($_POST["captcha_word"], $_POST["captcha_code"])) {
        $arResult["ERROR"] = "Неверно указан код с картинки.";
    }

    if (count($arFields) && empty($arResult["ERRORS"]) && empty($arResult["ERROR"])) {
        CEvent::SendImmediate("SEND_MISTAKE", SITE_ID, $arFields, "N");
        $arResult["SUCCESS"] = "Спасибо! Сообщение об ошибке успешно отправлено.";
    }
} elseif ($_REQUEST["action"] === "getCaptcha") {
    include_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/classes/general/captcha.php");

    $cpt = new CCaptcha();
    $captchaPass = COption::GetOptionString("main", "captcha_password", "");

    If (strlen($captchaPass) <= 0) {
        $captchaPass = randString(10);
        COption::SetOptionString("main", "captcha_password", $captchaPass);
    }

    $cpt->SetCodeCrypt($captchaPass);

    $arResult["captcha"] = htmlspecialchars($cpt->GetCodeCrypt());
}

echo json_encode($arResult);
?>
