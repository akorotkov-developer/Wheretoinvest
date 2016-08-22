<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
global $USER;
if (!is_object($USER)) $USER = new \CUser();

switch ($arResult["MESSAGE_CODE"]) {
    case "E06":
        if (!empty($arResult["USER_ACTIVE"]["ID"])) {
            $USER->Authorize($arResult["USER_ACTIVE"]["ID"]);
            LocalRedirect("/cabinet/");
        }
        break;
}
$APPLICATION->SetTitle("Подтверждение регистрации");
$APPLICATION->AddChainItem("Подтверждение регистрации", $APPLICATION->GetCurPage());
?>

<? if (!empty($arResult["MESSAGE_TEXT"])): ?>
    <?
    switch ($arResult["MESSAGE_CODE"]) {
        case "E02":
            $type = "success";
            break;
        case "E06":
            $type = "success";
            if (!empty($arResult["USER_ACTIVE"]["ID"])) {
                $USER->Authorize($arResult["USER_ACTIVE"]["ID"]);
            }
            break;
        default:
            $type = "alert";
            break;
    }
    ?>
    <div class="row">
        <div class="column small-12 medium-6 large-5 small-centered">
            <div data-alert
                 class="alert-box alert radius">
                <?
                echo $arResult["MESSAGE_TEXT"];
                ?>
                <a href="#" class="close">&times;</a>
            </div>
        </div>
    </div>
<? endif; ?>

<? if ($arResult["SHOW_FORM"]): ?>
    <div class="row">
        <div class="column small-12 medium-6 large-5 small-centered">
            <div class="recover__title">
                Подтверждение регистрации
            </div>
            <form method="post" action="<? echo $arResult["FORM_ACTION"] ?>">
                <input type="hidden" name="<? echo $arParams["USER_ID"] ?>"
                       value="<? echo $arResult["USER_ID"] ?>"/>
                <? if (strlen($arResult["BACKURL"]) > 0): ?>
                    <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                <? endif ?>
                <? foreach ($arResult["POST"] as $key => $value): ?>
                    <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
                <? endforeach ?>
                <?
                $arResult["FORM_FIELDS"] = Array(
                    $arParams["LOGIN"] => Array(
                        "TITLE" => "Электронная почта",
                        "TYPE" => "EMAIL",
                        "VALUE" => strlen($arResult["LOGIN"]) > 0 ? $arResult["LOGIN"] : $arResult["USER"]["LOGIN"],
                        "REQUIRED" => "Y",
                    ),
                    $arParams["CONFIRM_CODE"] => Array(
                        "TITLE" => "Код подтверждения",
                        "TYPE" => "TEXT",
                        "VALUE" => $arResult["CONFIRM_CODE"],
                        "REQUIRED" => "Y",
                        "PARAMS" => Array(
                            "autocomplete" => "off"
                        )
                    ),
                );
                ?>
                <?= getFormFields($arResult["FORM_FIELDS"], 12, "b-form__row_small-margin") ?>

                <div class="row">
                    <div class="column small-12">
                        <button type="submit" class="b-form__btn" name="confirm_user" value="Y">Подтвердить
                        </button>
                        <a href="/cabinet/auth/" class="recover__return">Вернуться</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
<? elseif (!$USER->IsAuthorized()): ?>
    <? $APPLICATION->IncludeComponent("bitrix:system.auth.authorize", "", array()); ?>
<? endif ?>