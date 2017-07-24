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
 *
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->SetTitle("Регистрация");
$APPLICATION->AddChainItem("Регистрация", $APPLICATION->GetCurPage());

$emailConfirm = COption::GetOptionString("main", "new_user_registration_email_confirmation", "");
?>

<? if (!empty($_REQUEST["Register"]) && !empty($arParams["~AUTH_RESULT"])): ?>
    <? cl($arResult); ?>
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
            <li class="tab-title<? if (!isset($_REQUEST["WORK_COMPANY"])): ?> active<? endif; ?>"><a href="#person">клиент</a>
            </li>
            <li class="tab-title<? if (isset($_REQUEST["WORK_COMPANY"])): ?> active<? endif; ?>"><a href="#partner">партнер</a>
            </li>
        </ul>
        <div class="tabs-content">
            <div class="content<? if (!isset($_REQUEST["WORK_COMPANY"])): ?> active<? endif; ?>" id="person">
                <?
                $arResult["FORM_FIELDS_PERSON"] = Array(
                    "PERSONAL_GENDER" => Array(
                        "TYPE" => "RADIO",
                        "VALUE" => $arParams["~AUTH_RESULT"]["TYPE"] !== "OK" ? $_REQUEST["PERSONAL_GENDER"] : "",
                        "BLOCK_TITLE" => "Пол",
                        "LIST" => Array(
                            "M" => "Мужской",
                            "F" => "Женский",
                        ),
                        "REQUIRED" => "Y",
                    ),
                    "UF_BIRTHDAY" => Array(
                        "BLOCK_TITLE" => "Год рождения",
                        "TYPE" => "TEXT",
                        "VALUE" => $arParams["~AUTH_RESULT"]["TYPE"] !== "OK" ? $_REQUEST["UF_BIRTHDAY"] : "",
                        "REQUIRED" => "Y",
                        "PLACEHOLDER" => "гггг",
                        "PARAMS" => Array(
                            "autocomplete" => "off",
                            "maxlength" => "4"
                        )
                    ),
                    "NAME" => Array(
                        "BLOCK_TITLE" => "Ваше имя для сайта",
                        "TYPE" => "TEXT",
                        "VALUE" => $arParams["~AUTH_RESULT"]["TYPE"] !== "OK" ? $_REQUEST["NAME"] : "",
                        "REQUIRED" => "Y",
                    ),
                    "USER_EMAIL" => Array(
                        "BLOCK_TITLE" => "Электронная почта",
                        "TYPE" => "EMAIL",
                        "VALUE" => $arParams["~AUTH_RESULT"]["TYPE"] !== "OK" ? $arResult["USER_EMAIL"] : "",
                        "REQUIRED" => "Y",
                        "DESCRIPTION" => "Будет логином на сайт. Вы должны иметь к ней доступ, чтобы подтвердить регистрацию",
                        "PARAMS" => Array(
                            "autocomplete" => "off"
                        )
                    ),
                    "USER_PASSWORD" => Array(
                        "BLOCK_TITLE" => "Придумайте пароль",
                        "TYPE" => "PASSWORD",
                        "VALUE" => $arParams["~AUTH_RESULT"]["TYPE"] !== "OK" ? $arResult["USER_PASSWORD"] : "",
                        "REQUIRED" => "Y",
                        "PARAMS" => Array(
                            "autocomplete" => "off"
                        )
                    ),
                    "CONFIRM_C" => Array(
                        "NO_TITLE" => "Y",
                        "TYPE" => "CHECKBOX",
                        "VALUE" => "",
                        "REQUIRED" => "Y",
                        "SINGLE" => "Y",
                        "LIST" => Array(
                            "Y" => 'Я согласен с условиями <a href="/upload/uf/63d/%D0%9F%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D1%81%D0%BA%D0%BE%D0%B5%20%D1%81%D0%BE%D0%B3%D0%BB%D0%B0%D1%88%D0%B5%D0%BD%D0%B8%D0%B5%202017.07.01.pdf" target="_blank">Пользовательского соглашения</a> и <a href="/upload/uf/307/%D0%9F%D0%BE%D0%BB%D0%B8%D1%82%D0%B8%D0%BA%D0%B0%20%D0%BA%D0%BE%D0%BD%D1%84%D0%B8%D0%B4%D0%B5%D0%BD%D1%86%D0%B8%D0%B0%D0%BB%D1%8C%D0%BD%D0%BE%D1%81%D1%82%D0%B8%202017.07.01.pdf" target="_blank">Политики конфиденциальности</a>'
                        )
                    ),
                );
                ?>
                <form method="post" action="<?= $arResult["AUTH_URL"] ?>" name="bform">
                    <br>
                    <input type="hidden" name="AUTH_FORM" value="Y"/>
                    <input type="hidden" name="TYPE" value="REGISTRATION"/>
                    <input type="hidden" name="FROM_PUBLIC" value="Y"/>
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
            <div class="content<? if (!isset($_REQUEST["WORK_COMPANY"])): ?> active<? endif; ?>" id="partner">
                <?
                $arResult["FORM_FIELDS_PARTNER"] = Array(
                    "WORK_COMPANY" => Array(
                        "BLOCK_TITLE" => "Сокращенное наименование организации (согласно уставу)",
                        "TYPE" => "TEXT",
                        "VALUE" => $arParams["~AUTH_RESULT"]["TYPE"] !== "OK" ? $_REQUEST["WORK_COMPANY"] : "",
                        "REQUIRED" => "Y",
                    ),
                    "NAME" => Array(
                        "BLOCK_TITLE" => "Контактное лицо",
                        "TYPE" => "TEXT",
                        "VALUE" => $arParams["~AUTH_RESULT"]["TYPE"] !== "OK" ? $_REQUEST["NAME"] : "",
                        "REQUIRED" => "Y",
                    ),
                    "PERSONAL_PHONE" => Array(
                        "BLOCK_TITLE" => "Контактный телефон",
                        "TYPE" => "TEXT",
                        "VALUE" => $arParams["~AUTH_RESULT"]["TYPE"] !== "OK" ? $_REQUEST["PERSONAL_PHONE"] : "",
                        "REQUIRED" => "Y",
                        "INPUT_CLASS" => "js-phone"
                    ),
                    "USER_EMAIL" => Array(
                        "BLOCK_TITLE" => "Электронная почта",
                        "TYPE" => "EMAIL",
                        "VALUE" => $arParams["~AUTH_RESULT"]["TYPE"] !== "OK" ? $arResult["USER_EMAIL"] : "",
                        "REQUIRED" => "Y",
                        "DESCRIPTION" => "Будет логином на сайт. Вы должны иметь к ней доступ, чтобы подтвердить регистрацию",
                        "PARAMS" => Array(
                            "autocomplete" => "off"
                        )
                    ),
                    "USER_PASSWORD" => Array(
                        "BLOCK_TITLE" => "Придумайте пароль",
                        "TYPE" => "PASSWORD",
                        "VALUE" => $arParams["~AUTH_RESULT"]["TYPE"] !== "OK" ? $arResult["USER_PASSWORD"] : "",
                        "REQUIRED" => "Y",
                        "PARAMS" => Array(
                            "autocomplete" => "off"
                        )
                    ),
                    "CONFIRM_P" => Array(
                        "NO_TITLE" => "Y",
                        "TYPE" => "CHECKBOX",
                        "VALUE" => "",
                        "REQUIRED" => "Y",
                        "SINGLE" => "Y",
                        "LIST" => Array(
                            "Y" => 'Я согласен с условиями <a href="/upload/uf/63d/%D0%9F%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D1%81%D0%BA%D0%BE%D0%B5%20%D1%81%D0%BE%D0%B3%D0%BB%D0%B0%D1%88%D0%B5%D0%BD%D0%B8%D0%B5%202017.07.01.pdf" target="_blank">Пользовательского соглашения</a> и <a href="/upload/uf/307/%D0%9F%D0%BE%D0%BB%D0%B8%D1%82%D0%B8%D0%BA%D0%B0%20%D0%BA%D0%BE%D0%BD%D1%84%D0%B8%D0%B4%D0%B5%D0%BD%D1%86%D0%B8%D0%B0%D0%BB%D1%8C%D0%BD%D0%BE%D1%81%D1%82%D0%B8%202017.07.01.pdf" target="_blank">Политики конфиденциальности</a>'
                        )
                    ),
                );
                ?>
                <form method="post" action="<?= $arResult["AUTH_URL"] ?>" name="bform">
                    <br>
                    <input type="hidden" name="AUTH_FORM" value="Y"/>
                    <input type="hidden" name="TYPE" value="REGISTRATION"/>
                    <input type="hidden" name="FROM_PUBLIC" value="Y"/>
                    <input type="hidden" name="partner" value="Y"/>
                    <? if (strlen($arResult["BACKURL"]) > 0): ?>
                        <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                    <? endif ?>
                    <? foreach ($arResult["POST"] as $key => $value): ?>
                        <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
                    <? endforeach ?>
                    <? foreach ($arResult["POST"] as $key => $value): ?>
                        <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
                    <? endforeach ?>
                    <?= getFormFields($arResult["FORM_FIELDS_PARTNER"], 12, "b-form__row_small-margin") ?>
                    <button class="reg__submit" type="submit" name="Register" value="Y">зарегистрироваться</button>
                    <noindex>
                        <div class="reg__already">Уже зарегистрированы? <a href="<?= $arResult["AUTH_AUTH_URL"] ?>"
                                                                           class="reg__link">Войти</a></div>
                    </noindex>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $("[name='bform']").on("submit", function () {
            var id = "";
            if ($(this).find("#FIELD_CONFIRM_C").length)
                id = "#FIELD_CONFIRM_C";
            else
                id = "#FIELD_CONFIRM_P";

            if (!$(id).is(":checked")) {
                alert("Для продолжения Вам необходимо согласиться с условиями Пользовательского соглашения и Политики конфиденциальности.");
                return false;
            }
        });
    });
</script>