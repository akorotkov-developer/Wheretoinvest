<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->SetTitle("Войти в личный кабинет");
$APPLICATION->AddChainItem("Войти в личный кабинет", $APPLICATION->GetCurPage());
?>

<? if (!empty($_REQUEST["Login"]) && !empty($arParams["~AUTH_RESULT"])): ?>
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
            Войти в личный кабинет
        </div>
        <form name="form_auth" method="post" target="_top"
              action="<?= $arResult["AUTH_URL"] ?>">
            <input type="hidden" name="AUTH_FORM" value="Y"/>
            <input type="hidden" name="TYPE" value="AUTH"/>
            <? if (strlen($arResult["BACKURL"]) > 0): ?>
                <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
            <? endif ?>
            <? foreach ($arResult["POST"] as $key => $value): ?>
                <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
            <? endforeach ?>

            <? ob_start(); ?>
            <noindex>
                <a href="<?= $arResult["AUTH_FORGOT_PASSWORD_URL"] ?>"
                   rel="nofollow" class="recover__link">Забыли пароль?</a>
            </noindex>
            <? $remember = ob_get_contents(); ?>
            <? ob_end_clean(); ?>
            <?
            $arResult["FORM_FIELDS"] = Array(
                "USER_LOGIN" => Array(
                    "BLOCK_TITLE" => "Ваш email",
                    "TYPE" => "EMAIL",
                    "VALUE" => $arResult["LAST_LOGIN"],
                    "REQUIRED" => "Y",
                    "NO_LABEL" => "Y"
                ),
                "USER_PASSWORD" => Array(
                    "BLOCK_TITLE" => "Пароль",
                    "TYPE" => "PASSWORD",
                    "VALUE" => "",
                    "REQUIRED" => "Y",
                    "NO_LABEL" => "Y"
                ),
                "REMEMBER" => Array(
                    "TYPE" => "STATIC",
                    "TEXT" => $remember,
                    "NO_LABEL" => "Y",
                    "ROW_CLASS" => "b-form__row_no-margin"
                )
            );
            ?>
            <?= getFormFields($arResult["FORM_FIELDS"], 12, "b-form__row_small-margin") ?>
            <div class="row">
                <div class="column small-12">
                    <button type="submit" class="b-form__btn" name="Login" value="Y">войти
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>