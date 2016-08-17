<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Данные пользователя");

$userInfo = getContainer("User");
?>
    <div class="row">
        <? if ($userInfo->isPartner()): ?>
            <div class="columns req_p">
                <div class="row">
                    <div class="req__name medium-4 small-5 columns">ФИО</div>
                    <div
                        class="req__value medium-8 small-7 columns js-profile-name"><?= \CUser::FormatName("#LAST_NAME# #NAME# #SECOND_NAME#", $userInfo, false); ?></div>
                </div>
            </div>
            <div class="columns req">
                <div class="row">
                    <div class="req__name medium-4 small-5 columns">Пол</div>
                    <div
                        class="req__value medium-8 small-7 columns js-profile-gender"><?= empty($userInfo["PERSONAL_GENDER"]) ? "Не задан" : ($userInfo["PERSONAL_GENDER"] == "M" ? "Мужской" : "Женский") ?></div>
                </div>
            </div>
            <div class="columns req req_last">
                <div class="row">
                    <div class="req__name medium-4 small-5 columns">Дата рождения</div>
                    <div
                        class="req__value medium-8 small-7 columns js-profile-birthday"><?= empty($userInfo["PERSONAL_BIRTHDAY"]) ? "Не задана" : date("d.m.Y", strtotime($userInfo["PERSONAL_BIRTHDAY"])) ?></div>
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
                "NAME" => Array(
                    "BLOCK_TITLE" => "ФИО",
                    "TYPE" => "TEXT",
                    "VALUE" => trim(CUser::FormatName("#LAST_NAME# #NAME# #SECOND_NAME#", $userInfo, true)) == $userInfo["EMAIL"] ? "" : CUser::FormatName("#LAST_NAME# #NAME# #SECOND_NAME#", $userInfo, true),
                    "REQUIRED" => "Y",
                    "NO_LABEL" => "Y",
                ),
            ); ?>
        <? else: ?>
            <div class="columns req_p">
                <div class="row">
                    <div class="req__name medium-4 small-5 columns">Сокращенное наименование организации (согласно
                        уставу)
                    </div>
                    <div
                        class="req__value medium-8 small-7 columns js-profile-work"><?= empty($userInfo["WORK_COMPANY"]) ? "Не задано" : $userInfo["WORK_COMPANY"] ?></div>
                </div>
            </div>
            <div class="columns req">
                <div class="row">
                    <div class="req__name medium-4 small-5 columns">Контактное лицо</div>
                    <div
                        class="req__value medium-8 small-7 columns js-profile-name"><?= \CUser::FormatName("#LAST_NAME# #NAME# #SECOND_NAME#", $userInfo, false); ?></div>
                </div>
            </div>
            <div class="columns req req_last">
                <div class="row">
                    <div class="req__name medium-4 small-5 columns">Контактный телефон</div>
                    <div
                        class="req__value medium-8 small-7 columns js-profile-phone"><?= empty($userInfo["PERSONAL_PHONE"]) ? "Не задан" : $userInfo["PERSONAL_PHONE"] ?></div>
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
                "NAME" => Array(
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
                                if (response.NEW.NAME !== undefined) {
                                    $(".js-profile-name").text(response.NEW.NAME);
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
                    }
                });
                return false;
            });
        });
    </script>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>