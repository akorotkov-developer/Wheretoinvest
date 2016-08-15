<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->SetTitle("Восстановить пароль");
$APPLICATION->AddChainItem("Восстановить пароль", $APPLICATION->GetCurPage());

$arResult["LAST_LOGIN"] = !empty($arResult["LAST_LOGIN"]) ? $arResult["LAST_LOGIN"] : $_REQUEST["email"];
?>
<? if (!empty($_REQUEST["send_account_info"]) && !empty($arParams["~AUTH_RESULT"])): ?>
    <div class="row">
        <div class="column small-12 medium-6 large-5 small-centered">
            <div data-alert
                 class="alert-box <? if ($arParams["~AUTH_RESULT"]["TYPE"] === "ERROR"): ?>alert<? else: ?>success<? endif; ?> radius">
                <?
                echo $arParams["~AUTH_RESULT"]["MESSAGE"];
                ?>
                <a href="#" class="close">&times;</a>
            </div>
        </div>
    </div>
<? endif; ?>


<div class="row">
    <div class="column small-12 medium-6 large-5 small-centered">
        <div class="recover__title">
            Восстановить пароль
        </div>
        <form name="bform" method="post" target="_top" action="<?= $arResult["AUTH_URL"] ?>">
            <input type="hidden" name="AUTH_FORM" value="Y">
            <input type="hidden" name="TYPE" value="SEND_PWD">
            <? if (strlen($arResult["BACKURL"]) > 0): ?>
                <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
            <? endif ?>
            <? foreach ($arResult["POST"] as $key => $value): ?>
                <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
            <? endforeach ?>
            <?
            $arResult["FORM_FIELDS"] = Array(
                "DESC" => Array(
                    "TYPE" => "STATIC",
                    "NO_LABEL" => "Y",
                    "TEXT" => '<div class="recover__text">Введите адрес электронной почты (email), указанный при регистрации. Вы получите
            ссылку для восстановления пароля.
        </div>',
                    "ROW_CLASS" => "b-form__row_no-margin"
                ),
                "USER_LOGIN" => Array(
                    "BLOCK_TITLE" => "Адрес электронной почты",
                    "TYPE" => "EMAIL",
                    "VALUE" => $arResult["LAST_LOGIN"],
                    "REQUIRED" => "Y",
                    "NO_LABEL" => "Y",
                ),
            );
            ?>
            <? if ($arResult["USE_CAPTCHA"] == "Y") {
                $arResult["FORM_FIELDS"]["captcha_word"] = Array(
                    "TITLE" => "Код безопасности",
                    "TYPE" => "CAPTCHA",
                    "VALUE" => $arResult["CAPTCHA_CODE"],
                    "REQUIRED" => "Y",
                    "LABEL_CLASS" => "b-form__title_small b-form__title_no-padding",
                    "INPUT_CLASS" => "text-center",
                    "FULL_COL" => "Y",
                    "PARAMS" => Array(
                        "maxlength" => 5
                    )
                );
            } ?>
            <?= getFormFields($arResult["FORM_FIELDS"], 12, "b-form__row_small-margin") ?>
            <div class="row">
                <div class="column small-12">
                    <button type="submit" class="b-form__btn" name="send_account_info" value="Y">восстановить пароль
                    </button>
                    <a href="<?= $arResult["AUTH_AUTH_URL"] ?>" class="recover__return">Вернуться</a>
                </div>
            </div>
        </form>
    </div>
</div>