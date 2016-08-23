<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>

<?
global $USER;
if (!is_object($USER)) $USER = new CUser;
if (check_bitrix_sessid() && isset($_REQUEST["ajax"]) && !empty($_REQUEST["action"]) && $USER->IsAuthorized()) {
    $arResult = [];
    switch (trim($_REQUEST["action"])) {
        case "changeEmail":
            $oldEmail = trim($_REQUEST["OLD_EMAIL"]);
            $newEmail = trim($_REQUEST["NEW_EMAIL"]);
            $confirmEmail = trim($_REQUEST["NEW_EMAIL_CONFIRM"]);
            $currentEmail = trim($USER->GetEmail());

            if (empty($oldEmail))
                $arResult["ERRORS"]["OLD_EMAIL"] = "Поле обязательно к заполнению";
            else {
                if ($oldEmail !== $currentEmail) {
                    $arResult["ERRORS"]["OLD_EMAIL"] = "Email указан неверно";
                }
            }

            if (empty($newEmail)) {
                $arResult["ERRORS"]["NEW_EMAIL"] = "Поле обязательно к заполнению";
            } elseif ($newEmail === $currentEmail) {
                $arResult["ERRORS"]["NEW_EMAIL"] = "Указан текущий email";
            } else {
                $rsUser = \CUser::GetList(($by = "ID"), ($order = "ASC"), Array("EMAIL" => $newEmail));
                if ($aruser = $rsUser->Fetch()) {
                    $arResult["ERRORS"]["NEW_EMAIL"] = "Email уже занят";
                }
            }

            if (empty($confirmEmail)) {
                $arResult["ERRORS"]["NEW_EMAIL_CONFIRM"] = "Поле обязательно к заполнению";
            }
            if (!empty($newEmail) && !empty($confirmEmail) && $newEmail !== $confirmEmail) {
                $arResult["ERRORS"]["NEW_EMAIL_CONFIRM"] = "Новый email повторен неверно";
            }

            if (empty($arResult["ERRORS"])) {
                $userId = $USER->GetID();
                $cUser = new CUser();
                $arFields = [
                    "LOGIN" => $newEmail,
                    "EMAIL" => $newEmail,
                ];
                if ($cUser->Update($userId, $arFields)) {
                    $USER->Authorize($userId);
                    $arResult["SUCCESS"] = "Email успешно изменен.";
                    $arResult["NEW_EMAIL"] = $newEmail;
                } else {
                    $arResult["ERROR"] = $cUser->LAST_ERROR;
                }
            }

            break;
        case "changePass":
            $oldPass = trim($_REQUEST["OLD_PASSWORD"]);
            $newPass = trim($_REQUEST["NEW_PASSWORD"]);
            $confirmPass = trim($_REQUEST["NEW_PASSWORD_CONFIRM"]);
            $currentPass = $USER->Login($USER->GetLogin(), $oldPass);

            if (empty($oldPass))
                $arResult["ERRORS"]["OLD_PASSWORD"] = "Поле обязательно к заполнению";
            else {
                if ($currentPass !== true) {
                    $arResult["ERRORS"]["OLD_PASSWORD"] = "Пароль указан неверно";
                }
            }

            if (empty($newPass)) {
                $arResult["ERRORS"]["NEW_PASSWORD"] = "Поле обязательно к заполнению";
            } else {
                if (strlen($newPass) < 6) {
                    $arResult["ERRORS"]["NEW_PASSWORD"] = "Пароль должен содержать не менее 6 символов";
                } elseif (preg_match("#[^a-zA-Z0-9]#is", $newPass)) {
                    $arResult["ERRORS"]["NEW_PASSWORD"] = "Пароль содержит недопустимые символы. <br/>  Для ввода доступны [a-zA-Z0-9].";
                } elseif (!preg_match("#[a-z]#s", $newPass) || !preg_match("#[A-Z]#s", $newPass) || !preg_match("#[0-9]#s", $newPass)) {
                    $arResult["ERRORS"]["NEW_PASSWORD"] = "Сложность пароля: Слабый";
                }
            }

            if (empty($confirmPass)) {
                $arResult["ERRORS"]["NEW_PASSWORD_CONFIRM"] = "Поле обязательно к заполнению";
            }
            if (!empty($newPass) && !empty($confirmPass) && $newPass !== $confirmPass) {
                $arResult["ERRORS"]["NEW_PASSWORD_CONFIRM"] = "Новый пароль повторен неверно";
            }

            if (empty($arResult["ERRORS"])) {
                $userId = $USER->GetID();
                $cUser = new CUser();
                $arFields = [
                    "PASSWORD" => $newPass,
                    "CONFIRM_PASSWORD" => $confirmPass,
                ];
                if ($cUser->Update($userId, $arFields)) {
                    $USER->Authorize($userId);
                    $arResult["SUCCESS"] = "Пароль успешно изменен.";
                } else {
                    $arResult["ERROR"] = $cUser->LAST_ERROR;
                }
            }
            break;
        case "removeAcc":
            $cUser = new \CUser();
            $checkword = randString(16);
            $cUser->Update($USER->GetID(), Array("UF_CHECKWORD" => $checkword));

            $userInfo = getContainer("User");

            $arFields = Array(
                "USER_ID" => $USER->GetID(),
                "STATUS" => $userInfo["ACTIVE"] == "Y" ? "активен" : "не активен",
                "MESSAGE" => "Вы сделали запрос на удаление аккаунта.",
                "LOGIN" => $userInfo["LOGIN"],
                "URL_LOGIN" => urlencode($userInfo["LOGIN"]),
                "NAME" => $userInfo["NAME"],
                "LAST_NAME" => $userInfo["LAST_NAME"],
                "EMAIL" => $userInfo["EMAIL"],
                "CHECKWORD" => $checkword,
            );

            \CEvent::SendImmediate("USER_ACC_REMOVE", SITE_ID, $arFields, "N");
            $arResult["SUCCESS"] = "На email было выслано письмо с кодом подтверждения удаления аккаунта.<br>Пожалуйста, дождитесь письма, т.к. при повторном запросе код подтверждения будет изменен.";

            break;
        default:
            die();
    }

    echo json_encode($arResult);
}

?>
