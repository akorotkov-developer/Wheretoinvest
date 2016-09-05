<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arParams */
/** @var array $arResult */
/** @global CUser $USER */
global $USER;
/** @global CMain $APPLICATION */
global $APPLICATION;

$APPLICATION->SetTitle("Участие государства");
$APPLICATION->AddChainItem("Участие государства");

$userInfo = getContainer("User");
?>

<div class="b-main-block__body"></div>

<div class="row">
    <div class="columns state">
        <input type="checkbox" name="address" id="state_particip"
               class="state__checkbox js-participate"<? if (!empty($userInfo["UF_STATE_PARTICIP"])): ?> checked<? endif; ?>
               data-type="state" value="24">
        <label for="state_particip" class="state__chck">Участие государства в капитале организации напрямую или через
            другие организации</label>
    </div>
    <div class="columns state">
        <input type="checkbox" name="address" id="bank_particip"
               class="state__checkbox js-participate"<? if (!empty($userInfo["UF_BANK_PARTICIP"])): ?> checked<? endif; ?>
               data-type="bank" value="25">
        <label for="bank_particip" class="state__chck">Участие банка в системе страхования вкладов </label>
    </div>
    <div class="columns">
        <div class="content__i"><img src="<?= WIC_TEMPLATE_PATH ?>/images/asb.jpg" alt=""></div>
    </div>
</div>

<? if (!empty($userInfo["TIMESTAMP_X"])): ?>
    <div class="row">
        <div class="columns content__date">
            Обновлено: <?= strtolower(CIBlockFormatProperties::DateFormat("d M Y в H:i", strtotime($userInfo["TIMESTAMP_X"]))); ?></div>
    </div>
<? endif; ?>

<script type="text/javascript">
    $(function () {
        $(".js-participate").on("change", function () {
            var type = $(this).data("type"),
                val = $(this).is(":checked") ? $(this).val() : "";

            $(".b-form__error").detach();
            $(".alert-box").detach();

            $.ajax({
                url: "/local/ajax/editProfile.php",
                data: {
                    action: "setParticipate",
                    type: type,
                    val: val,
                    ajax: "Y",
                    sessid: "<?= bitrix_sessid(); ?>"
                },
                method: "POST",
                dataType: "json",
                success: function (response) {
                    if (response.ERROR !== undefined) {
                        $(".b-main-block__body").prepend('<div data-alert class="alert-box alert radius">' + response.ERROR + '<a href="#" class="close">&times;</a></div>');
                        $(document).foundation('alert', 'reflow');
                    }
                    else if (response.SUCCESS !== undefined) {
                        $(".b-main-block__body").prepend('<div data-alert class="alert-box success radius">' + response.SUCCESS + '<a href="#" class="close">&times;</a></div>');
                        $(document).foundation('alert', 'reflow');
                    }

                    var alert = $("[data-alert]:visible");
                    if (alert.length) {
                        $('html, body').animate({
                            scrollTop: alert.eq(0).offset().top - 80
                        }, 500);

                        setTimeout(function () {
                            alert.find(".close").trigger("click", function () {
                                alert.foundation("alert", "reflow");
                            });
                        }, 2000);
                    }
                }
            });
            return false;
        });
    });
</script>