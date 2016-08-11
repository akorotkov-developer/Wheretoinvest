<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->SetTitle("Восстановление пароля");

$arResult["LAST_LOGIN"] = !empty($arResult["LAST_LOGIN"]) ? $arResult["LAST_LOGIN"] : $_REQUEST["email"];
?>

<div class="b-auth">
    <div class="row">
        <div class="column small-6 small-centered">
            <? if (!empty($_REQUEST["send_account_info"]) && !empty($arParams["~AUTH_RESULT"])): ?>
                <br><br>
                <div data-alert
                     class="alert-box <? if ($arParams["~AUTH_RESULT"]["TYPE"] === "ERROR"): ?>alert<? else: ?>success<? endif; ?> radius">
                    <?
                    echo $arParams["~AUTH_RESULT"]["MESSAGE"];
                    ?>
                    <a href="#" class="close">&times;</a>
                </div>
            <? else: ?>
                <br><br>
            <? endif; ?>
            <br>

            <div class="b-main-block">
                <div class="b-main-block__head clearfix">
                    <div class="b-main-block__title">Восстановление пароля</div>
                </div>

                <div class="b-main-block__body b-main-block__body_padding b-main-block__body_small-top-padding">
                    <div class="row">
                        <div class="column small-11 small-centered">
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
                                        "TEXT" => '<div class="b-form__title-desc text-center">Введите email, указанный при регистрации, и <br>мы вышлем инструкцию по восстановлению</div>'
                                    ),
                                    "USER_LOGIN" => Array(
                                        "TITLE" => "Электронная почта, указанная при регистрации",
                                        "TYPE" => "EMAIL",
                                        "VALUE" => $arResult["LAST_LOGIN"],
                                        "REQUIRED" => "Y",
                                        "LABEL_CLASS" => "b-form__title_small b-form__title_no-padding"
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
                                    <div class="column small-9 small-centered">
                                        <div class="row">
                                            <div class="column small-5">
                                                <a href="<?= $arResult["AUTH_AUTH_URL"] ?>"
                                                   class="b-btn b-btn_grey">Назад</a>
                                            </div>
                                            <div class="column small-7">
                                                <button type="submit" class="b-btn" name="send_account_info" value="Y">
                                                    Отправить
                                                </button>
                                            </div>
                                        </div>
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