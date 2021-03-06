<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arParams */
/** @var array $arResult */
/** @global CUser $USER */
global $USER;
/** @global CMain $APPLICATION */
global $APPLICATION;

$APPLICATION->SetTitle("Капитал и активы");
$APPLICATION->AddChainItem("Капитал и активы");

$userInfo = getContainer("User");

?>

<div class="row">

    <div class="columns req">
        <div class="row">
            <div class="req__name medium-4 small-5 columns assets__first-column">Капитал / Активы&nbsp;<span
                        class="assets__tooltip_wrapper"><span
                            data-tooltip
                            aria-haspopup="true"
                            class="has-tip assets__tooltip show-for-large-up"
                            title="Для банков соответствует нормативу Н1.0 на последнюю отчетную дату.<br><br>Для остальных организаций соответствует отношению суммы собственных средств (капитала) к сумме активов по балансу, в процентах."></span></span>
            </div>
            <div class="req__value medium-8 small-7 columns">
                <span
                        class="assets__black js-capital-assets"><?= strlen($userInfo["UF_CAPITAL_ASSETS"]) ? "<span class='assets__num_right'>" . $userInfo["UF_CAPITAL_ASSETS"] . "</span><span class='assets__small'>%</span>" . (!empty($userInfo["UF_CAPITAL_A_DATE"]) ? "<span class='assets__small assets__date'> на " . date("d.m.Y", strtotime($userInfo["UF_CAPITAL_A_DATE"])) . "</span>" : "") : "<span class='req__name'>—</span>" ?></span>
            </div>
        </div>
    </div>
    <div class="columns req">
        <div class="row">
            <div class="req__name medium-4 small-5 columns assets__first-column">Собственные средства (капитал)&nbsp;<span
                        class="assets__tooltip_wrapper"><span
                            data-tooltip
                            aria-haspopup="true"
                            class="has-tip assets__tooltip show-for-large-up"
                            title="Соответствует сумме собственных средств (капитала) на последнюю отчетную дату"></span></span>
            </div>
            <div class="req__value medium-8 small-7 columns">
<span
        class="assets__red js-capital"><?= strlen($userInfo["UF_CAPITAL"]) ? "<span class='assets__num_right'>" . number_format(round(intval(preg_replace("#[^\d]#is", "", $userInfo["UF_CAPITAL"])) / 1000000, 1), 1, ",", " ") . "</span><span class='assets__small'>млрд. рублей</span>" . (!empty($userInfo["UF_CAPITAL_DATE"]) ? "<span class='assets__small assets__date'> на " . date("d.m.Y", strtotime($userInfo["UF_CAPITAL_DATE"])) . "</span>" : "") : "<span class='req__name'>—</span>" ?></span>
            </div>
        </div>
    </div>
    <div class="columns">
        <div class="row">
            <div class="req__name medium-4 small-5 columns assets__first-column">Активы&nbsp;<span
                        class="assets__tooltip_wrapper"><span data-tooltip
                                                              aria-haspopup="true"
                                                              class="has-tip assets__tooltip show-for-large-up"
                                                              title="Для банков соответствует сумме активов, взвешенных по уровню риска.<br><br>Для остальных организаций соответствует сумме активов по балансу."></span></span>
            </div>
            <div class="req__value medium-8 small-7 columns">
<span class="js-assets-parent">
                    <span
                            class="assets__red js-assets"><?= strlen($userInfo["UF_ASSETS"]) ? "<span class='assets__num_right'>" . number_format(round(intval(preg_replace("#[^\d]#is", "", $userInfo["UF_ASSETS"])) / 1000000, 1), 1, ",", " ") . "</span><span class='assets__small'>млрд. рублей</span>" . (!empty($userInfo["UF_ASSETS_DATE"]) ? "<span class='assets__small assets__date'> на " . date("d.m.Y", strtotime($userInfo["UF_ASSETS_DATE"])) . "</span>" : "") : "<span class='req__name'>—</span>" ?></span>
            </span>
            </div>
        </div>
    </div>

    <div class="columns">
        <div class="row">
            <div class="columns small-5 medium-4 req__name">&nbsp;</div>
            <div class="columns small-7 medium-8 req__value">
                <br>
                <span class='assets__num_right assets__num_right_margin'><a href="#" class="content__change"
                                                                            data-reveal-id="assets">Изменить</a></span>
            </div>
        </div>
    </div>
</div>

<div id="assets" class="reveal-modal tiny modal" data-reveal aria-labelledby="modalTitle"
     aria-hidden="true"
     role="dialog">
    <form action="" class="b-form x-save-form-asset" enctype="multipart/form-data" method="post">
        <div class="row">
            <div class="column small-12">
                <input type="hidden" name="sessid" value="<?= bitrix_sessid(); ?>">
                <input type="hidden" name="ajax" value="Y">
                <input type="hidden" name="action" value="changeAssets">

                <h2 class="content__title">Редактировать капитал и активы</h2>

                <div class="b-main-block__body"></div>

                <div class="row">
                    <div class="assets columns small-12 medium-12 large-8">
                        <div class="assets__head">
                            <div class="assets__title">Капитал / Активы</div>
                            <span class="assets__tooltip_wrapper"><span data-tooltip aria-haspopup="true"
                                                                        class="has-tip assets__tooltip show-for-large-up"
                                                                        title="Для банков соответствует нормативу Н1.1 на последнюю отчетную дату.<br><br>Для остальных организаций соответствует отношению суммы собственных средств (капитала) к сумме активов по балансу, в процентах."></span></span>

                            <div class="level">
                                <input type="text" name="UF_CAPITAL_ASSETS" class="level__inp js-number-only"
                                       value="<?= $userInfo["UF_CAPITAL_ASSETS"] ?>" required maxlength="6">
                                <span class="level__help">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="assets columns small-12 medium-12 large-4">
                        <div class="assets__head">
                            <div class="assets__title">по состоянию на</div>
                            <div class="level">
                                <?
                                $capitalDate = Array(
                                    "UF_CAPITAL_A_DATE" => Array(
                                        "TYPE" => "DATETIME",
                                        "NO_LABEL" => "Y",
                                        "VALUE" => !empty($userInfo["UF_CAPITAL_A_DATE"]) ? date("d.m.Y", strtotime($userInfo["UF_CAPITAL_A_DATE"])) : "",
                                        "REQUIRED" => "Y"
                                    )
                                );
                                echo getFormFields($capitalDate, 12, "b-form__row_no-margin");
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="assets columns small-12 medium-12 large-8">
                        <div class="assets__head">
                            <div class="assets__title">Собственный капитал</div>
                            <span class="assets__tooltip_wrapper"><span data-tooltip aria-haspopup="true"
                                                                        class="has-tip assets__tooltip show-for-large-up"
                                                                        title="Для банков соответствует показателю «Базовый капитал» (строка 102 формы
            0409123 «Расчёт собственных средств (капитала) («Базель III»)) на последнюю отчетную дату.<br><br>Для остальных организаций соответствует сумме собственных средств (капитала) на последнюю отчетную дату."></span></span>

                            <div class="level">
                                <input type="text" class="level__inp level__inp_big js-assets-mask" name="UF_CAPITAL"
                                       value="<?= $userInfo["UF_CAPITAL"] ?>" required>
                                <span class="level__help">тыс. рублей</span>
                            </div>
                        </div>
                    </div>
                    <div class="assets columns small-12 medium-12 large-4">
                        <div class="assets__head">
                            <div class="assets__title">по состоянию на</div>
                            <div class="level">
                                <?
                                $capitalDate = Array(
                                    "UF_CAPITAL_DATE" => Array(
                                        "TYPE" => "DATETIME",
                                        "NO_LABEL" => "Y",
                                        "VALUE" => !empty($userInfo["UF_CAPITAL_DATE"]) ? date("d.m.Y", strtotime($userInfo["UF_CAPITAL_DATE"])) : "",
                                        "REQUIRED" => "Y"
                                    )
                                );
                                echo getFormFields($capitalDate, 12, "b-form__row_no-margin");
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="assets columns small-12 medium-12 large-8">
                        <div class="assets__head">
                            <div class="assets__title">Активы</div>
                            <span class="assets__tooltip_wrapper"><span data-tooltip aria-haspopup="true"
                                                                        class="has-tip assets__tooltip show-for-large-up"
                                                                        title="Для банков соответствует сумме активов, взвешенных по уровню риска.<br><br>Для остальных организаций соответствует сумме активов по балансу."></span></span>

                            <div class="level">
                                <input type="text" class="level__inp level__inp_big js-assets-mask" name="UF_ASSETS"
                                       value="<?= $userInfo["UF_ASSETS"] ?>" required>
                                <span class="level__help">тыс. рублей</span>
                            </div>
                        </div>
                    </div>
                    <div class="assets columns small-12 medium-12 large-4">
                        <div class="assets__head">
                            <div class="assets__title">по состоянию на</div>
                            <div class="level">
                                <?
                                $capitalDate = Array(
                                    "UF_ASSETS_DATE" => Array(
                                        "TYPE" => "DATETIME",
                                        "NO_LABEL" => "Y",
                                        "VALUE" => !empty($userInfo["UF_ASSETS_DATE"]) ? date("d.m.Y", strtotime($userInfo["UF_ASSETS_DATE"])) : "",
                                        "REQUIRED" => "Y"
                                    )
                                );
                                echo getFormFields($capitalDate, 12, "b-form__row_no-margin");
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="column small-12">
                <div class="row">
                    <div class="column small-12 medium-5 small-centered">
                        <button class="b-form__btn" type="submit">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <a class="close-reveal-modal modal__close" aria-label="Close">×</a>
</div>

<script type="text/javascript">
    $(function () {
        function number_format(number, decimals, dec_point, thousands_sep) {
            var i, j, kw, kd, km;

            // input sanitation & defaults
            if (isNaN(decimals = Math.abs(decimals))) {
                decimals = 2;
            }
            if (dec_point == undefined) {
                dec_point = ",";
            }
            if (thousands_sep == undefined) {
                thousands_sep = ".";
            }

            i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

            if ((j = i.length) > 3) {
                j = j % 3;
            } else {
                j = 0;
            }

            km = (j ? i.substr(0, j) + thousands_sep : "");
            kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
            //kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : "");
            kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");

            return km + kw + kd;
        }

        $(".js-assets-mask").on("keyup", function (event) {
            if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||
                // Разрешаем: Ctrl+A
                (event.keyCode == 65 && event.ctrlKey === true) ||
                // Разрешаем: home, end, влево, вправо
                (event.keyCode >= 35 && event.keyCode <= 39)) {
                return;
            }
            else {
                var val = $(this).val();
                val = val.replace(/[^\d,]/g, '').trim();
                val = number_format(parseInt(val), 0, ".", " ");
                if (val < 0)
                    val = 0;
                $(this).val(val);
            }
        });

        $(".js-number-only").on("keyup", function (event) {
            if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||
                // Разрешаем: Ctrl+A
                (event.keyCode == 65 && event.ctrlKey === true) ||
                // Разрешаем: home, end, влево, вправо
                (event.keyCode >= 35 && event.keyCode <= 39)) {
                return;
            }
            else {
                var val = $(this).val();
                val = val.replace(/[^\d\.,]/g, '').replace(/\./g, ',').trim();
                if (val < 0)
                    val = 0;
                $(this).val(val);
            }
        });

        $(".x-save-form-asset").unbind().on("submit", function () {
            var data = $(this).serialize(),
                _this = $(this),
                url = _this.attr("action") !== "" ? _this.attr("action") : "/local/ajax/editAssets.php";
            _this.find(".b-form__error").detach();
            _this.find(".alert-box").detach();

            $.ajax({
                url: url,
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

                        if (response.NEW.UF_ASSETS !== undefined) {
                            $(".js-assets").html("<span class='assets__num_right'>" + response.NEW.UF_ASSETS + "</span><span class='assets__small'>млрд. рублей</span><span class='assets__small assets__date'> на " + response.NEW.UF_ASSETS_DATE + "</span>");
                        }
                        if (response.NEW.UF_CAPITAL_ASSETS !== undefined) {
                            $(".js-capital-assets").html("<span class='assets__num_right'>" + response.NEW.UF_CAPITAL_ASSETS + "</span><span class='assets__small'>%</span><span class='assets__small assets__date'> на " + response.NEW.UF_CAPITAL_A_DATE + "</span>");
                        }
                        if (response.NEW.UF_CAPITAL !== undefined) {
                            $(".js-capital").html("<span class='assets__num_right'>" + response.NEW.UF_CAPITAL + "</span><span class='assets__small'>млрд. рублей</span><span class='assets__small assets__date'> на " + response.NEW.UF_CAPITAL_DATE + "</span>");
                        }
                    }

                    var alert = $("[data-alert]:visible");
                    if (alert.length) {
                        $('html, body').animate({
                            scrollTop: alert.eq(0).offset().top - 80
                        }, 500);
                    }
                }
            });
            return false;
        });
    });
</script>