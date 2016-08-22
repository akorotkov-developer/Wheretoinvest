<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->SetTitle("Изменить пароль");
$APPLICATION->AddChainItem("Изменить пароль", $APPLICATION->GetCurPage());
?>

<? if (!empty($_REQUEST["change_pwd"]) && !empty($arParams["~AUTH_RESULT"])): ?>
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
            Изменить пароль
        </div>
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
                    "BLOCK_TITLE" => "Логин (ваш e-mail)",
                    "TYPE" => "EMAIL",
                    "VALUE" => $arResult["LAST_LOGIN"],
                    "REQUIRED" => "Y",
                    "NO_LABEL" => "Y",
                ),
                "USER_PASSWORD" => Array(
                    "BLOCK_TITLE" => "Новый пароль",
                    "TYPE" => "PASSWORD",
                    "VALUE" => "",
                    "REQUIRED" => "Y",
                    "NO_LABEL" => "Y",
                    "PARAMS" => Array(
                        "autocomplete" => "off"
                    )
                ),
                "USER_CONFIRM_PASSWORD" => Array(
                    "BLOCK_TITLE" => "Подтверждение пароля",
                    "TYPE" => "PASSWORD",
                    "VALUE" => "",
                    "REQUIRED" => "Y",
                    "NO_LABEL" => "Y",
                    "PARAMS" => Array(
                        "autocomplete" => "off"
                    )
                ),
            );
            ?>
            <?= getFormFields($arResult["FORM_FIELDS"], 12, "b-form__row_small-margin") ?>
            <div class="row">
                <div class="column small-12">
                    <button type="submit" class="b-form__btn" name="change_pwd" value="Y">изменить пароль
                    </button>
                    <a href="<?= $arResult["AUTH_AUTH_URL"] ?>" class="recover__return">Вернуться</a>
                </div>
            </div>
        </form>
    </div>
</div>