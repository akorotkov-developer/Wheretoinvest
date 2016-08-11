<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->SetTitle("Изменение пароля");
?>

<div class="b-auth">
    <div class="row">
        <div class="column small-6 small-centered">
            <? if (!empty($_REQUEST["change_pwd"]) && !empty($arParams["~AUTH_RESULT"])): ?>
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
                    <div class="b-main-block__title">Изменение пароля</div>
                </div>

                <div class="b-main-block__body b-main-block__body_padding b-main-block__body_small-top-padding">
                    <div class="row">
                        <div class="column small-11 small-centered">
                            <form method="post" action="<?= $arResult["AUTH_FORM"] ?>" name="bform">
                                <input type="hidden" name="AUTH_FORM" value="Y">
                                <input type="hidden" name="TYPE" value="CHANGE_PWD">
                                <input type="hidden" name="USER_CHECKWORD" value="<?= $arResult["USER_CHECKWORD"] ?>"
                                       required/>
                                <? if (strlen($arResult["BACKURL"]) > 0): ?>
                                    <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                                <? endif ?>
                                <? foreach ($arResult["POST"] as $key => $value): ?>
                                    <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
                                <? endforeach ?>
                                <?
                                $arResult["FORM_FIELDS"] = Array(
                                    "USER_LOGIN" => Array(
                                        "TITLE" => "Электронная почта",
                                        "TYPE" => "EMAIL",
                                        "VALUE" => $arResult["LAST_LOGIN"],
                                        "REQUIRED" => "Y",
                                        "LABEL_CLASS" => "b-form__title_small b-form__title_no-padding"
                                    ),
                                    "USER_PASSWORD" => Array(
                                        "TITLE" => "Новый пароль",
                                        "TYPE" => "PASSWORD",
                                        "VALUE" => "",
                                        "REQUIRED" => "Y",
                                        "FULL_COL" => "Y",
                                        "LABEL_CLASS" => "b-form__title_small b-form__title_no-padding",
                                        "PARAMS" => Array(
                                            "autocomplete" => "off"
                                        )
                                    ),
                                    "USER_CONFIRM_PASSWORD" => Array(
                                        "TITLE" => "Подтверждение пароля",
                                        "TYPE" => "PASSWORD",
                                        "VALUE" => "",
                                        "REQUIRED" => "Y",
                                        "FULL_COL" => "Y",
                                        "LABEL_CLASS" => "b-form__title_small b-form__title_no-padding",
                                        "PARAMS" => Array(
                                            "autocomplete" => "off"
                                        )
                                    ),
                                );
                                ?>
                                <?= getFormFields($arResult["FORM_FIELDS"], 12, "b-form__row_small-margin") ?>
                                <div class="row">
                                    <div class="column small-11 small-centered">
                                        <div class="row">
                                            <div class="column small-4">
                                                <a href="<?= $arResult["AUTH_AUTH_URL"] ?>"
                                                   class="b-btn b-btn_grey">Назад</a>
                                            </div>
                                            <div class="column small-8">
                                                <button type="submit" class="b-btn" name="change_pwd" value="Y">
                                                    Изменить пароль
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