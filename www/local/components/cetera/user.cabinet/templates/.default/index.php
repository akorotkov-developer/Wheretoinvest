<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arParams */
/** @var array $arResult */
/** @global CUser $USER */
global $USER;
/** @global CMain $APPLICATION */
global $APPLICATION;

$APPLICATION->SetTitle("Настройки аккаунта");
$APPLICATION->AddChainItem("Настройки аккаунта");

$arResult["EMAIL_FIELDS"] = Array(
    "HEADER" => Array(
        "TYPE" => "STATIC",
        "NO_LABEL" => "Y",
        "TEXT" => '
        <div class="b-form__title-desc">Email - это Ваш логин в личный кабинет. <br> После смены, используйте для входа новый email.</div>
        '
    ),
    "OLD_EMAIL" => Array(
        "TITLE" => "Текущий email",
        "TYPE" => "EMAIL",
        "VALUE" => "",
        "REQUIRED" => "Y",
        "PARAMS" => [
            "autocomplete" => "off"
        ]
    ),
    "NEW_EMAIL" => Array(
        "TITLE" => "Новый email",
        "TYPE" => "EMAIL",
        "VALUE" => "",
        "REQUIRED" => "Y",
        "PARAMS" => [
            "autocomplete" => "off"
        ]
    ),
    "NEW_EMAIL_CONFIRM" => Array(
        "TITLE" => "Повторите новый email",
        "TYPE" => "EMAIL",
        "VALUE" => "",
        "REQUIRED" => "Y",
        "PARAMS" => [
            "autocomplete" => "off"
        ]
    ),
);

$arResult["PASSWORD_FIELDS"] = Array(
    "OLD_PASSWORD" => Array(
        "TITLE" => "Старый пароль",
        "TYPE" => "PASSWORD",
        "VALUE" => "",
        "REQUIRED" => "Y",
        "PARAMS" => [
            "autocomplete" => "off"
        ]
    ),
    "NEW_PASSWORD" => Array(
        "TITLE" => "Новый пароль",
        "TYPE" => "PASSWORD",
        "VALUE" => "",
        "REQUIRED" => "Y",
        "PARAMS" => [
            "autocomplete" => "off"
        ]
    ),
    "NEW_PASSWORD_CONFIRM" => Array(
        "TITLE" => "Повторите новый пароль",
        "TYPE" => "PASSWORD",
        "VALUE" => "",
        "REQUIRED" => "Y",
        "PARAMS" => [
            "autocomplete" => "off"
        ]
    ),
);
?>
<div class=" content__key">
    <span class="b-form__title">Электронная почта:</span> <a href="mailto:<?= $USER->GetEmail() ?>"
                          class="js-email-link"><?= $USER->GetEmail() ?></a>
</div>
<a class="content__change" href="#" data-reveal-id="email">Изменить</a>
<br>
<div class="content__key">
    <span class="b-form__title">Пароль</span>: ********
</div>
<a class="content__change" href="#" data-reveal-id="password">Изменить</a>

<? if (!empty($arResult["EMAIL_FIELDS"])): ?>
    <div id="email" class="reveal-modal tiny modal" data-reveal aria-labelledby="modalTitle"
         aria-hidden="true"
         role="dialog">
        <div class="row">
            <div class="column small-12">
                <form action="" class="b-form x-save-form" enctype="multipart/form-data">
                    <input type="hidden" name="sessid" value="<?= bitrix_sessid(); ?>">
                    <input type="hidden" name="ajax" value="Y">
                    <input type="hidden" name="action" value="changeEmail">

                    <h2 class="content__title">Сменить email</h2>

                    <div class="b-main-block__body"></div>

                    <?= getFormFields($arResult["EMAIL_FIELDS"], 4, "b-form__row_small-margin"); ?>

                    <div class="row">
                        <div class="column small-12 medium-5 small-centered">
                            <button class="b-form__btn" type="submit">Сохранить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <a class="close-reveal-modal modal__close" aria-label="Close">×</a>
    </div>
<? endif; ?>
<? if (!empty($arResult["PASSWORD_FIELDS"])): ?>
    <div id="password" class="reveal-modal tiny modal" data-reveal aria-labelledby="modalTitle"
         aria-hidden="true"
         role="dialog">
        <div class="row">
            <div class="column small-12">
                <form action="" class="b-form x-save-form" enctype="multipart/form-data">
                    <input type="hidden" name="sessid" value="<?= bitrix_sessid(); ?>">
                    <input type="hidden" name="ajax" value="Y">
                    <input type="hidden" name="action" value="changePass">

                    <h2 class="content__title">Сменить пароль</h2>

                    <div class="b-main-block__body"></div>

                    <?= getFormFields($arResult["PASSWORD_FIELDS"], 4, "b-form__row_small-margin"); ?>

                    <div class="row">
                        <div class="column small-12 medium-5 small-centered">
                            <button class="b-form__btn" type="submit">Сохранить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <a class="close-reveal-modal modal__close" aria-label="Close">×</a>
    </div>
<? endif; ?>
<script type="text/javascript">
    $(function () {
        $(".x-save-form").on("submit", function () {
            var data = $(this).serialize(),
                _this = $(this);
            _this.find(".b-form__error").detach();
            _this.find(".alert-box").detach();

            $.ajax({
                url: "/local/ajax/editAccInfo.php",
                data: data,
                method: "POST",
                dataType: "json",
                success: function (response) {
                    if (response.ERRORS !== undefined) {
                        $.each(response.ERRORS, function (i, item) {
                            _this.find('input[name="' + i + '"]').after('<div class="b-form__error">' + item + '</div>');
                        });
                    }
                    else if (response.ERROR !== undefined) {
                        _this.find(".b-main-block__body").prepend('<div data-alert class="alert-box alert radius">' + response.ERROR + '<a href="#" class="close">&times;</a></div>');
                        $(document).foundation('alert', 'reflow');
                    }
                    else if (response.SUCCESS !== undefined) {
                        _this.find(".b-main-block__body").prepend('<div data-alert class="alert-box success radius">' + response.SUCCESS + '<a href="#" class="close">&times;</a></div>');
                        $(document).foundation('alert', 'reflow');
                        if (response.NEW_EMAIL !== undefined) {
                            $(".js-email-link").text(response.NEW_EMAIL).attr("href", "mailto:" + response.NEW_EMAIL);
                        }
                        $(':input', _this)
                            .not(':button, :submit, :reset, :hidden')
                            .val('')
                            .removeAttr('checked')
                            .removeAttr('selected');
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