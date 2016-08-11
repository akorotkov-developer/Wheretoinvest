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
?>
<? if ($arResult["SHOW_FORM"]): ?>
    <div class="b-auth">
        <div class="row">
            <div class="column small-6 small-centered">
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
                    <br><br>
                    <div data-alert
                         class="alert-box <?= $type; ?> radius">
                        <?
                        echo $arResult["MESSAGE_TEXT"];
                        ?>
                        <a href="#" class="close">&times;</a>
                    </div>
                <? else: ?>
                    <br><br>
                <? endif; ?>
                <br>

                <div class="b-main-block">
                    <div class="b-main-block__head clearfix">
                        <div class="b-main-block__title">Подтверждение регистрации</div>
                    </div>

                    <div class="b-main-block__body b-main-block__body_padding b-main-block__body_small-top-padding">
                        <div class="row">
                            <div class="column small-11 small-centered">
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
                                            "LABEL_CLASS" => "b-form__title_small b-form__title_no-padding"
                                        ),
                                        $arParams["CONFIRM_CODE"] => Array(
                                            "TITLE" => "Код подтверждения",
                                            "TYPE" => "TEXT",
                                            "VALUE" => $arResult["CONFIRM_CODE"],
                                            "REQUIRED" => "Y",
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
                                                    <a href="/auth/?login=yes"
                                                       class="b-btn b-btn_grey">Назад</a>
                                                </div>
                                                <div class="column small-8">
                                                    <button type="submit" class="b-btn" name="confirm_user" value="Y">
                                                        Подтвердить
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
<? elseif (!$USER->IsAuthorized()): ?>
    <? $APPLICATION->IncludeComponent("bitrix:system.auth.authorize", "", array()); ?>
<? endif ?>