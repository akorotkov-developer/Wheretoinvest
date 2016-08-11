<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->SetTitle("Регистрация");

$emailConfirm = COption::GetOptionString("main", "new_user_registration_email_confirmation", "");
?>

<div class="b-auth">
    <div class="row">
        <div class="column small-6 small-centered">
            <? if (!empty($_REQUEST["Register"]) && !empty($arParams["~AUTH_RESULT"])): ?>
                <br><br>
                <div data-alert
                     class="alert-box <? if ($arParams["~AUTH_RESULT"]["TYPE"] === "ERROR"): ?>alert<? else: ?>success<? endif; ?> radius">
                    <?
                    echo $arParams["~AUTH_RESULT"]["MESSAGE"];
                    if ($arParams["~AUTH_RESULT"]["TYPE"] === "OK" && $emailConfirm === "Y") {
                        echo "<br/>На указанный email было выслано письмо с кодом подтверждения регистрации.";
                    }
                    ?>
                    <a href="#" class="close">&times;</a>
                </div>
            <? else: ?>
                <br><br>
            <? endif; ?>
            <br>

            <div class="b-main-block">
                <div class="b-main-block__head clearfix">
                    <div class="b-main-block__title">Регистрация</div>
                </div>

                <div class="b-main-block__body b-main-block__body_padding">
                    <div class="row">
                        <div class="column small-11 small-centered">
                            <form method="post" action="<?= $arResult["AUTH_URL"] ?>" name="bform">
                                <input type="hidden" name="AUTH_FORM" value="Y"/>
                                <input type="hidden" name="TYPE" value="REGISTRATION"/>
                                <? if (strlen($arResult["BACKURL"]) > 0): ?>
                                    <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                                <? endif ?>
                                <? foreach ($arResult["POST"] as $key => $value): ?>
                                    <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
                                <? endforeach ?>
                                <?
                                $arResult["FORM_FIELDS"] = Array(
                                    "UF_USER_TYPE" => Array(
                                        "TYPE" => "RADIO",
                                        "VALUE" => $_REQUEST["UF_USER_TYPE"],
                                        "LIST" => Array(
                                            "4" => GetMessage("TITLE_UF_USER_TYPE_F"),
                                            "5" => GetMessage("TITLE_UF_USER_TYPE_U"),
                                        ),
                                        "REQUIRED" => "Y",
                                        "NO_LABEL" => "Y"
                                    ),
                                    "USER_EMAIL" => Array(
                                        "TITLE" => "Электронная почта",
                                        "TYPE" => "EMAIL",
                                        "VALUE" => $arResult["USER_EMAIL"],
                                        "REQUIRED" => "Y",
                                        "LABEL_CLASS" => "b-form__title_small b-form__title_no-padding",
                                        "PARAMS" => Array(
                                            "autocomplete" => "off"
                                        )
                                    ),
                                    "USER_PASSWORD" => Array(
                                        "TITLE" => "Пароль",
                                        "TYPE" => "PASSWORD",
                                        "VALUE" => $arResult["USER_PASSWORD"],
                                        "REQUIRED" => "Y",
                                        "FULL_COL" => "Y",
                                        "LABEL_CLASS" => "b-form__title_small b-form__title_no-padding",
                                        "PARAMS" => Array(
                                            "autocomplete" => "off"
                                        )
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

                                <br>

                                <div class="row">
                                    <div class="column small-12">
                                        <button type="submit" class="b-btn" name="Register" value="Y">Зарегистрироваться
                                        </button>
                                    </div>
                                    <div class="column small-12 text-center">
                                        <br>

                                        Уже зарегистрированы?
                                        <noindex>
                                            <a href="<?= $arResult["AUTH_AUTH_URL"] ?>"
                                               rel="nofollow">Войти</a>
                                        </noindex>
                                        <br>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>