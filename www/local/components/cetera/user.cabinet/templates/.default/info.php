<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$userInfo = getContainer("User");

$name = $userInfo->isPartner() ? "Контакты" : "Данные пользователя";

$APPLICATION->SetTitle($name);
$APPLICATION->AddChainItem($name);

?>
<?
$userName = \CUser::FormatName("#LAST_NAME# #NAME# #SECOND_NAME#", $userInfo, true);
$userName = $userName == $userInfo->GetEmail() ? "<span class='req__name'>—</span>" : $userName;
?>
<div class="row">
    <? if (!$userInfo->isPartner()): ?>
        <div class="columns req">
            <div class="row">
                <div class="req__name medium-4 small-5 columns">ФИО</div>
                <div
                    class="req__value medium-8 small-7 columns js-profile-name"><?= $userName; ?></div>
            </div>
        </div>
        <div class="columns req">
            <div class="row">
                <div class="req__name medium-4 small-5 columns">Пол</div>
                <div
                    class="req__value medium-8 small-7 columns js-profile-gender"><?= empty($userInfo["PERSONAL_GENDER"]) ? "<span class='req__name'>—</span>" : ($userInfo["PERSONAL_GENDER"] == "M" ? "Мужской" : "Женский") ?></div>
            </div>
        </div>
        <div class="columns req">
            <div class="row">
                <div class="req__name medium-4 small-5 columns">Дата рождения</div>
                <div
                    class="req__value medium-8 small-7 columns js-profile-birthday"><?= empty($userInfo["PERSONAL_BIRTHDAY"]) ? "<span class='req__name'>—</span>" : date("d.m.Y", strtotime($userInfo["PERSONAL_BIRTHDAY"])) ?></div>
            </div>
        </div>
        <? $arResult["FORM_FIELDS"] = Array(
            "PERSONAL_GENDER" => Array(
                "TYPE" => "RADIO",
                "VALUE" => $userInfo["PERSONAL_GENDER"],
                "BLOCK_TITLE" => "Пол",
                "LIST" => Array(
                    "M" => "Мужской",
                    "F" => "Женский",
                ),
                "REQUIRED" => "Y",
                "NO_LABEL" => "Y"
            ),
            "PERSONAL_BIRTHDAY" => Array(
                "BLOCK_TITLE" => "Дата рождения",
                "TYPE" => "DATE",
                "VALUE" => !empty($userInfo["PERSONAL_BIRTHDAY"]) ? date("Y-m-d", strtotime($userInfo["PERSONAL_BIRTHDAY"])) : "",
                "REQUIRED" => "Y",
                "NO_LABEL" => "Y",
                "PARAMS" => Array(
                    "autocomplete" => "off"
                )
            ),
            "LAST_NAME" => Array(
                "BLOCK_TITLE" => "Фамилия",
                "TYPE" => "TEXT",
                "VALUE" => $userInfo["LAST_NAME"],
                "REQUIRED" => "Y",
            ),
            "NAME" => Array(
                "BLOCK_TITLE" => "Имя",
                "TYPE" => "TEXT",
                "VALUE" => $userInfo["NAME"],
                "REQUIRED" => "Y",
            ),
            "SECOND_NAME" => Array(
                "BLOCK_TITLE" => "Отчество",
                "TYPE" => "TEXT",
                "VALUE" => $userInfo["SECOND_NAME"],
                "REQUIRED" => "Y",
            ),
        ); ?>
    <? else: ?>
        <div class="columns req">
            <div class="row">
                <div class="req__name medium-4 small-5 columns">Сокращенное наименование организации (согласно
                    уставу)
                </div>
                <div
                    class="req__value medium-8 small-7 columns js-profile-work"><?= empty($userInfo["WORK_COMPANY"]) ? "<span class='req__name'>—</span>" : $userInfo["WORK_COMPANY"] ?></div>
            </div>
        </div>
        <div class="columns req">
            <div class="row">
                <div class="req__name medium-4 small-5 columns">Контактное лицо</div>
                <div
                    class="req__value medium-8 small-7 columns js-profile-name"><?= $userName; ?></div>
            </div>
        </div>
        <div class="columns req">
            <div class="row">
                <div class="req__name medium-4 small-5 columns">Контактный телефон</div>
                <div
                    class="req__value medium-8 small-7 columns js-profile-phone"><?= empty($userInfo["PERSONAL_PHONE"]) ? "<span class='req__name'>—</span>" : $userInfo["PERSONAL_PHONE"] ?></div>
            </div>
        </div>
        <? $arResult["FORM_FIELDS"] = Array(
            "WORK_COMPANY" => Array(
                "BLOCK_TITLE" => "Сокращенное наименование организации (согласно уставу)",
                "TYPE" => "TEXT",
                "VALUE" => $userInfo["WORK_COMPANY"],
                "REQUIRED" => "Y",
                "NO_LABEL" => "Y",
            ),
            "FULL_NAME" => Array(
                "BLOCK_TITLE" => "Контактное лицо",
                "TYPE" => "TEXT",
                "VALUE" => trim(CUser::FormatName("#LAST_NAME# #NAME# #SECOND_NAME#", $userInfo, true)) == $userInfo["EMAIL"] ? "" : CUser::FormatName("#LAST_NAME# #NAME# #SECOND_NAME#", $userInfo, true),
                "REQUIRED" => "Y",
                "NO_LABEL" => "Y",
            ),
            "PERSONAL_PHONE" => Array(
                "BLOCK_TITLE" => "Контактный телефон",
                "TYPE" => "TEXT",
                "VALUE" => $userInfo["PERSONAL_PHONE"],
                "REQUIRED" => "Y",
                "NO_LABEL" => "Y",
                "INPUT_CLASS" => "js-phone"
            ),
        );
        ?>
    <? endif; ?>
</div>
<div class="row">
    <div class="columns">
        <a href="#" class="content__change" data-reveal-id="profile">Изменить</a></div>
</div>

<div id="profile" class="reveal-modal tiny modal" data-reveal aria-labelledby="modalTitle"
     aria-hidden="true"
     role="dialog">
    <div class="row">
        <div class="column small-12">
            <form action="" class="b-form x-save-form" enctype="multipart/form-data" method="post">
                <input type="hidden" name="sessid" value="<?= bitrix_sessid(); ?>">
                <input type="hidden" name="ajax" value="Y">
                <input type="hidden" name="action" value="changeProfile">

                <h2 class="content__title">Данные пользователя</h2>

                <div class="b-main-block__body"></div>

                <?= getFormFields($arResult["FORM_FIELDS"], 4, "b-form__row_small-margin"); ?>
                <br>

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

<script type="text/javascript">
    $(function () {
        $(".x-save-form").on("submit", function () {
            var data = $(this).serialize(),
                _this = $(this);
            _this.find(".b-form__error").detach();
            _this.find(".alert-box").detach();

            $.ajax({
                url: "/local/ajax/editProfile.php",
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

                        if (response.NEW !== undefined) {
                            if (response.NEW.NAME !== undefined || response.NEW.LAST_NAME !== undefined || response.NEW.SECOND_NAME !== undefined) {
                                var name = "";
                                name += response.NEW.LAST_NAME !== undefined ? response.NEW.LAST_NAME + " " : "";
                                name += response.NEW.NAME !== undefined ? response.NEW.NAME + " " : "";
                                name += response.NEW.SECOND_NAME !== undefined ? response.NEW.SECOND_NAME : "";
                                $(".js-profile-name").text(name.trim());
                            }
                            if (response.NEW.PERSONAL_GENDER !== undefined) {
                                $(".js-profile-gender").text(response.NEW.PERSONAL_GENDER);
                            }
                            if (response.NEW.PERSONAL_BIRTHDAY !== undefined) {
                                $(".js-profile-birthday").text(response.NEW.PERSONAL_BIRTHDAY);
                            }
                            if (response.NEW.WORK_COMPANY !== undefined) {
                                $(".js-profile-work").text(response.NEW.WORK_COMPANY);
                            }
                            if (response.NEW.PERSONAL_PHONE !== undefined) {
                                $(".js-profile-phone").text(response.NEW.PERSONAL_PHONE);
                            }
                        }
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