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
                            "Y" => 'Я принимаю условия <a href="/uploads/docs/Пользовательское%20соглашение.pdf" target="_blank">Пользовательского соглашения</a> и <a href="/uploads/docs/Политика%20конфиденциальности.pdf" target="_blank">Политики конфиденциальности</a>'
                        ),
                        "PARAMS" => Array(
                            "data-confirm-input" => "Y"
                        )
                    ),
                );
                ?>
                <form method="post" action="<?= $arResult["AUTH_URL"] ?>" name="bform" data-confirm>
                    <br>
                    <input type="hidden" name="AUTH_FORM" value="Y"/>
                    <input type="hidden" name="TYPE" value="REGISTRATION"/>
                    <input type="hidden" name="FROM_PUBLIC" value="Y"/>
                    <? if (strlen($arResult["BACKURL"]) > 0): ?>
                        <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                    <? endif ?>

                    <?if (is_array($arResult["POST"]) ) {?>
                        <? foreach ($arResult["POST"] as $key => $value): ?>
                            <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
                        <? endforeach ?>
                    <?}?>

                    <?if (is_array($arResult["POST"]) ) {?>
                        <? foreach ($arResult["POST"] as $key => $value): ?>
                            <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
                        <? endforeach ?>
                    <?}?>

                    <?= getFormFields($arResult["FORM_FIELDS_PERSON"], 12, "b-form__row_small-margin") ?>

                    <?
                    if ($arResult["USE_CAPTCHA"] == "Y")
                    {
                        ?>
                        <tr>
                            <td colspan="2" class="sdfyhsdjfhsdkfjks"><b><?=GetMessage("CAPTCHA_REGF_TITLE")?></b></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
                                <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
                            </td>
                        </tr>
                        <tr>
                            <td><span class="starrequired"><br>*</span><?=GetMessage("CAPTCHA_REGF_PROMT")?>:</td>
                            <td><input type="text" name="captcha_word" maxlength="50" value="" /></td>
                        </tr>
                        <?
                    }
                    ?>
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
                            "Y" => 'Я принимаю условия <a href="/uploads/docs/Пользовательское%20соглашение.pdf" target="_blank">Пользовательского соглашения</a> и <a href="/uploads/docs/Политика%20конфиденциальности.pdf" target="_blank">Политики конфиденциальности</a>'
                        ),
                        "PARAMS" => Array(
                            "data-confirm-input" => "Y"
                        )
                    ),
                );
                ?>
                <form method="post" action="<?= $arResult["AUTH_URL"] ?>" name="bform" data-confirm>
                    <br>
                    <input type="hidden" name="AUTH_FORM" value="Y"/>
                    <input type="hidden" name="TYPE" value="REGISTRATION"/>
                    <input type="hidden" name="FROM_PUBLIC" value="Y"/>
                    <input type="hidden" name="partner" value="Y"/>
                    <? if (strlen($arResult["BACKURL"]) > 0): ?>
                        <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                    <? endif ?>
                    <?if (is_array($arResult["POST"]) ) {?>
                        <? foreach ($arResult["POST"] as $key => $value): ?>
                            <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
                        <? endforeach ?>
                    <?}?>
                    <?if (is_array($arResult["POST"]) ) {?>
                        <? foreach ($arResult["POST"] as $key => $value): ?>
                            <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
                        <? endforeach ?>
                    <?}?>
                    <?= getFormFields($arResult["FORM_FIELDS_PARTNER"], 12, "b-form__row_small-margin") ?>

                    <?
                    if ($arResult["USE_CAPTCHA"] == "Y")
                    {
                        ?>
                        <tr>
                            <td colspan="2" class="sdfyhsdjfhsdkfjks"><b><?=GetMessage("CAPTCHA_REGF_TITLE")?></b></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
                                <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
                            </td>
                        </tr>
                        <tr>
                            <td><span class="starrequired">*</span><?=GetMessage("CAPTCHA_REGF_PROMT")?>:</td>
                            <td><input type="text" name="captcha_word" maxlength="50" value="" /></td>
                        </tr>
                        <?
                    }
                    ?>
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