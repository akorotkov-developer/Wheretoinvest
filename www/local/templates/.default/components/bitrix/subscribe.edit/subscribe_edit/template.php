<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="subscribe-edit">
    <?

    foreach ($arResult["MESSAGE"] as $itemID => $itemValue)
        echo getMess(preg_replace("#активизирована#is", "активирована", implode("<br>", $arResult["MESSAGE"])), "success");
    foreach ($arResult["ERROR"] as $itemID => $itemValue)
        echo getMess(preg_replace("#активизирована#is", "активирована", implode("<br>", $arResult["ERROR"])), "alert");

    //whether to show the forms
    if ($arResult["ID"] == 0 && empty($_REQUEST["action"]) || CSubscription::IsAuthorized($arResult["ID"])) {
        //show confirmation form
        if ($arResult["ID"] > 0 && $arResult["SUBSCRIPTION"]["CONFIRMED"] <> "Y") {
            include("confirmation.php");
        }
        //show current authorization section
        if ($USER->IsAuthorized() && ($arResult["ID"] == 0 || $arResult["SUBSCRIPTION"]["USER_ID"] == 0)) {
            include("authorization.php");
        }
        //show authorization section for new subscription
        if ($arResult["ID"] == 0 && !$USER->IsAuthorized()) {
            if ($arResult["ALLOW_ANONYMOUS"] == "N" || ($arResult["ALLOW_ANONYMOUS"] == "Y" && $arResult["SHOW_AUTH_LINKS"] == "Y")) {
                include("authorization_new.php");
            }
        }
        //setting section
        include("setting.php");
        //status and unsubscription/activation section
        if ($arResult["ID"] > 0) {
            include("status.php");
        }
        ?>
        <div class="row">
            <div class="column small-12 medium-12 large-12">
                <span class="b-form__required">*</span> <? echo GetMessage("subscr_req") ?>
            </div>
        </div>
        <?
    } else {
        //subscription authorization form
        include("authorization_full.php");
    }
    ?>
</div>