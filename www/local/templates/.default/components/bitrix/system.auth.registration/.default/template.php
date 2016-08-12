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

<? if (!empty($_REQUEST["Register"]) && !empty($arParams["~AUTH_RESULT"])): ?>
    <div class="row">
        <div class="column small-12 medium-6 large-5 small-centered">
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
        </div>
    </div>
<? endif; ?>

<div class="row">
    <div class="recover">
        <div class="recover__title">
            Регистрация
        </div>
        <ul class="tabs reg" data-tab="" data-options="deep_linking:true;scroll_to_content:false;">
            <li class="tab-title active"><a href="#person">обычный
                    пользователь</a></li>
            <li class="tab-title"><a href="#partner">партнер</a></li>
        </ul>
        <div class="tabs-content">
            <div class="content active" id="person">
                <?
                $arResult["FORM_FIELDS_PERSON"] = Array(
                    "PERSONAL_GENDER" => Array(
                        "TYPE" => "RADIO",
                        "VALUE" => $_REQUEST["PERSONAL_GENDER"],
                        "BLOCK_TITLE" => "Пол",
                        "LIST" => Array(
                            "M" => "Мужской",
                            "F" => "Женский",
                        ),
                        "REQUIRED" => "Y",
                        "NO_LABEL" => "Y"
                    ),
                    "PERSONAL_BIRTHDAY" => Array(
                        "BLOCK_TITLE" => "Дата рождения",
                        "TYPE" => "DATE",
                        "VALUE" => $_REQUEST["PERSONAL_BIRTHDAY"],
                        "REQUIRED" => "Y",
                        "NO_LABEL" => "Y",
                        "PARAMS" => Array(
                            "autocomplete" => "off"
                        )
                    ),
                    "NAME" => Array(
                        "BLOCK_TITLE" => "Ваше имя для сайта",
                        "TYPE" => "TEXT",
                        "VALUE" => $arResult["NAME"],
                        "REQUIRED" => "Y",
                        "NO_LABEL" => "Y",
                    ),
                    "USER_EMAIL" => Array(
                        "BLOCK_TITLE" => "Электронная почта",
                        "TYPE" => "EMAIL",
                        "VALUE" => $arResult["USER_EMAIL"],
                        "REQUIRED" => "Y",
                        "NO_LABEL" => "Y",
                        "DESCRIPTION" => "Будет логином на сайт. Вы должны иметь к ней доступ, чтобы подтвердить регистрацию",
                        "PARAMS" => Array(
                            "autocomplete" => "off"
                        )
                    ),
                    "USER_PASSWORD" => Array(
                        "BLOCK_TITLE" => "Придумайте пароль",
                        "TYPE" => "PASSWORD",
                        "VALUE" => $arResult["USER_PASSWORD"],
                        "REQUIRED" => "Y",
                        "NO_LABEL" => "Y",
                        "PARAMS" => Array(
                            "autocomplete" => "off"
                        )
                    ),
                );
                ?>
                <form method="post" action="<?= $arResult["AUTH_URL"] ?>" name="bform">
                    <input type="hidden" name="AUTH_FORM" value="Y"/>
                    <input type="hidden" name="TYPE" value="REGISTRATION"/>
                    <? if (strlen($arResult["BACKURL"]) > 0): ?>
                        <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                    <? endif ?>
                    <? foreach ($arResult["POST"] as $key => $value): ?>
                        <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
                    <? endforeach ?>
                    <? foreach ($arResult["POST"] as $key => $value): ?>
                        <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
                    <? endforeach ?>
                    <?= getFormFields($arResult["FORM_FIELDS_PERSON"], 12, "b-form__row_small-margin") ?>
                    <button class="reg__submit" type="submit" name="Register" value="Y">зарегистрироваться</button>
                    <noindex>
                        <div class="reg__already">Уже зарегистрированы? <a href="<?= $arResult["AUTH_AUTH_URL"] ?>"
                                                                           class="reg__link">Войти</a></div>
                    </noindex>
                </form>
            </div>
            <div class="content" id="partner">
                <form action="">
                    <input type="hidden" name="partner" value="Y">
                    <label for="31" class="reg__label">Сокращенное наименование организации
                        (согласно уставу)
                        <input type="text" id="31">
                    </label>
                    <label for="41" class="reg__label">Контактное лицо

                        <input type="text" id="41">
                    </label>
                    <label for="51" class="reg__label">Контактный телефон

                        <input type="text" id="51">
                    </label>
                    <label for="52" class="reg__label">Электронная почта

                        <input type="email" id="52">
                    </label>

                    <div class="reg__info">Будет логином на сайт. Вы должны иметь к ней доступ, чтобы подтвердить
                        регистрацию
                    </div>
                    <label for="22" class="reg__label">Придумайте пароль

                        <input type="password" id="22">
                    </label>
                    <button class="reg__submit" type="submit">зарегистрироваться</button>
                    <div class="reg__already">Уже зарегистрированы? <a href="#" class="reg__link">Войти</a></div>

                </form>
            </div>
        </div>
    </div>
</div>