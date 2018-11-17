<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
    <form action="" class="x-filter">
        <?
        $request = http_build_query($_REQUEST);
        $list = explode("&", $request);
        ?>
        <? foreach ($list as $item): ?>
            <? list($key, $val) = explode("=", $item); ?>
            <? if (in_array($key, Array("summ", "currency", "time"))) continue; ?>
            <input type="hidden" name="<?= urldecode($key) ?>" value="<?= urldecode($val) ?>">
        <? endforeach; ?>
        <?
        $timeList = Array(
            "92" => "3 мес.",
            "185" => "6 мес.",
            "369" => "1 год",
            "545" => "1,5 года",
        );

        if (!empty($_REQUEST["time"]) && !array_key_exists($_REQUEST["time"], $timeList))
            $timeList[$_REQUEST["time"]] = $_REQUEST["time"] . " " . \Cetera\Tools\Utils::pluralForm($_REQUEST["time"], "день", "дня", "дней", "дней");

        ksort($timeList);

        $timeList["other"] = "Указать в днях";

        $sortVisible = $APPLICATION->get_cookie("SORT_VISIBLE");
        if ($sortVisible === "Y")
            $sortVisible = "";
        else
            $sortVisible = "N";
        global $TOTAL_METHOD;
        ?>

        <div class="js-header<? if (!empty($sortVisible)): ?> js-toggle<? endif; ?>">
            <section class="b-sort row">
                <div class="b-sort__arr<? if (!empty($sortVisible)): ?> js-toggle<? endif; ?>"></div>
                <div
                    class="columns b-sort__all<? if (!empty($sortVisible)): ?> js-toggle<? endif; ?>">
                    <span
                        class="b-sort__all_border js-sort-all"><?= !empty($_REQUEST["summ"]) ? $_REQUEST["summ"] : 0; ?> <?= empty($_REQUEST["currency"]) ? reset($arResult["FIELDS"]["UF_CURRENCY"]) : $arResult["FIELDS"]["UF_CURRENCY"][$_REQUEST["currency"]] ?> <?= !empty($_REQUEST["time"]) ? "на " . $timeList[$_REQUEST["time"]] : "" ?></span>
                </div>
                <div class="b-sort__main"<? if (!empty($sortVisible)): ?> style="display: none;"<? endif; ?>>
                    <div class="column medium-7">
                        <span class="b-sort__label">Сумма:</span>
                        <input type="text" class="b-sort__inp" value="<?= htmlspecialcharsbx($_REQUEST["summ"]) ?>"
                               name="summ">
                    </div>
                    <div class="column small-5 medium-2 b-sort__select">
                        <span class="b-sort__label">Валюта:</span>
                        <select name="currency">
                            <? foreach ($arResult["FIELDS"]["UF_CURRENCY"] as $key => $name): ?>
                                <option
                                    value="<?= $key ?>"<? if ($_REQUEST["currency"] == $key): ?> selected<? endif; ?>><?= $name ?></option>
                            <? endforeach; ?>
                        </select>
                    </div>
                    <div class="column small-7 medium-3">
                        <span class="b-sort__label">Срок:</span>
                        <select name="time">
                            <? foreach ($timeList as $key => $name): ?>
                                <option
                                    value="<?= $key ?>"<? if ($_REQUEST["time"] == $key): ?> selected<? endif; ?>><?= $name ?></option>
                            <? endforeach; ?>
                        </select>
                    </div>
                </div>
            </section>
            <button type="submit" class="hide"></button>

            <? if (count($arResult["ITEMS"])): ?>
                <div class="row small-collapse medium-uncollapse">
                    <div class="columns">
                        <div class="b-offers__header small-only-text-center">
                            <div class="row">
                                <div class="column medium-4 small-4 ">
                                    <div class="b-offers__th first">
                                        <span class="b-offers__title">
                                            Организация
                                        </span>
                                    </div>
                                </div>
                                <div class="column medium-3 hide-for-small-only">
                                    <div class="b-offers__th">
                                        <span class="b-offers__title">
                                            Наименование вложения
                                        </span>
                                    </div>
                                </div>
                                <div
                                    class="column medium-2 small-4 text-right b-offers__bility">
                                    <div class="b-offers__th">
                                        <a href="<?= \Cetera\Tools\Uri::GetCurPageParam("", Array("SORT")) ?>"
                                           class="b-offers__title<? if (empty($_REQUEST["SORT"])): ?> b-offers__title_sort<? endif; ?> has-tooltip"
                                           data-tooltip aria-haspopup="true"
                                           title="с учетом капитализации процентов,<br> % годовых">
                                            Доходность
                                        </a>
                                    </div>
                                </div>
                                <div class="column medium-2 small-4 text-right">
                                    <div class="b-offers__th">
                                        <? $arResult["METHODS"] = getContainer("userMethod"); ?>
                                        <? $i = 1; ?>
                                        <? $methodList = Array(
                                            "Определяется на основании показателей: <br>",
                                        ); ?>
                                        <? foreach ($arResult["METHODS"] as $arItem): ?>
                                            <? if ($arItem["ACTIVE"]): ?>
                                                <? $methodList[$i] = $i . ". " . $arItem["NAME"] ?>
                                                <? $i++; ?>
                                            <? endif; ?>
                                        <? endforeach; ?>
                                        <a href="<?= \Cetera\Tools\Uri::GetCurPageParam("SORT[safety]=A", Array("SORT")) ?>"
                                           class="b-offers__title<? if (!empty($_REQUEST["SORT"]["safety"])): ?> b-offers__title_sort<? endif; ?> has-tooltip"
                                           data-tooltip aria-haspopup="true"
                                           title="<?= implode("<br>", $methodList) ?>"
                                        >
                                            Надежность
                                        </a>
                                    </div>
                                </div>
                                <div class="column medium-1 show-for-medium-up">
                                    <div class="b-offers__th">&nbsp;</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <? endif; ?>
        </div>

        <script type="text/javascript">
            $(function () {
                $(".x-filter select[name='currency']").on("change", function () {
                    $(this).closest(".x-filter").submit();
                });

                var summInput = $(".x-filter input[name='summ']");
                var lastInput = summInput.val();

                $(".x-filter input[name='summ']").wrapAll('<span class="js-summ-wrapper"></span>');
                $(".x-filter input[name='summ']").css({"padding-right": "30px"});
                var clearBtn = $("<a>")
                    .appendTo($(".js-summ-wrapper"))
                    .css({
                        "display": lastInput !== "" ? "inline-block" : "none",
                        "position": "absolute",
                        "top": "50%",
                        "right": "10px",
                        "width": "16px",
                        "height": "16px",
                        "margin-top": "-8px",
                        "background": "url(<?=WIC_TEMPLATE_PATH?>/images/cross.png) no-repeat center center"
                    })
                    .attr("href", "#")
                    .on("click", function () {
                        summInput.val("").focus();
                        $(this).hide();
                        return false;
                    });
                $(".js-summ-wrapper").css({"position": "relative"});

                summInput
                    .on("blur", function () {
                        if ($(this).val() == "") {
                            $(this).val(lastInput);
                            clearBtn.show();
                        }

                        if ($(this).val() !== lastInput)
                            $(this).closest(".x-filter").submit();
                    })
                    .on("keyup", function () {
                        if ($(this).val() !== "") {
                            clearBtn.show();
                        }
                    });

                function timeField() {

                    var time = $("select[name='time']"),
                        lastSelectVal = time.val();
                    time.wrapAll("<span class='x-time-wrapper'></span>");
                    var wrapper = $(".x-time-wrapper");
                    wrapper.css({
                        "position": "relative",
                        "display": "block"
                    });

                    var input = $("<input>")
                        .attr("type", "text")
                        .addClass("b-form__autocomplete")
                        .css({
                            "position": "absolute",
                            "z-index": "10",
                            "top": 0,
                            "left": 0,
                            "pointer-events": "none"
                        });

                    var val = "";
                    $("option", time).each(function () {
                        if ($(this).attr("value") == time.val())
                            val = $(this).text();

                        if ($(this).text().match(/\d+ (дней|день|дня)/g))
                            $(this).hide();
                    });

                    input.val(val);
                    wrapper.append(input);

                    time.on("change", function () {
                        var val = $(this).val();
                        if (val == "other") {
                            $(this).val(lastSelectVal);
                            var lastVal = input.val();

                            function updateVal(_this) {
                                var newVal = $(_this).val();
                                if (newVal == "") {
                                    $(_this).val(lastVal);
                                }
                                else {
                                    if (!newVal.match(/[^\d]/g)) {
                                        var hasOption = false;
                                        $("option", time).each(function () {
                                            if ($(this).attr("value") == newVal)
                                                hasOption = true;
                                        });

                                        if (!hasOption) {
                                            time.append('<option value="' + newVal + '">' + newVal + '</option>');
                                            time.val(newVal);
                                        }

                                        time.closest("form").submit();
                                    }
                                    else {
                                        $(_this).val(lastVal);
                                    }
                                }

                                $(_this).css({"pointer-events": "none"});
                            }

                            input.on("touchstart", function () {
                                function blured() {
                                    input.unbind("blur");
                                    updateVal(this);
                                }

                                input.on("blur", blured);
                            });

                            input.on("blur", function () {
                                updateVal(this);
                            });

                            input.on("keyup", function (e) {
                                if (e.keyCode == 13) {
                                    updateVal(this);
                                }
                            });

                            setTimeout(function () {
                                input
                                    .css({"pointer-events": "all"})
                                    .val("")
                                    .focus();
                            }, 0);
                        }
                        else {
                            $("option", time).each(function () {
                                if ($(this).attr("value") == time.val())
                                    val = $(this).text();
                            });

                            input.val(val);
                            $(this).closest("form").submit();
                        }
                    })
                }

                timeField();
            });
        </script>
    </form>
<? if (count($arResult["ITEMS"])): ?>
    <? $inflation = floatval(\Ceteralabs\UserVars::GetVar('USER_VAR_INFLATION')["VALUE"]); ?>
    <? $inflationName = \Ceteralabs\UserVars::GetVar('USER_VAR_INFLATION')["DESCRIPTION"]; ?>
    <section class="b-offers">
        <? $showInflation = empty($_REQUEST["ajax"]) ? true : false; ?>
        <? if (!empty($inflation) && !empty($_REQUEST["SORT"]) && empty($_REQUEST["SORT"]["percent"]) && $showInflation): ?>
            <div class="row b-offers__infl" data-percent="<?= floatval($inflation) ?>">
                <div class="columns medium-3 medium-offset-4 small-4 small-text-center medium-text-left">
                    <div class="b-offers__type b-offers__type_infl"><?= $inflationName ?></div>
                </div>
                <div class="columns medium-2 small-4 text-right end b-offers__percent b-offers__bility">
                    <div class="b-offers__prof b-offers__prof_infl b-offers__prof_main"><?= $inflation ?> <span>%</span>
                    </div>
                </div>
            </div>
            <? $showInflation = false; ?>
        <? endif; ?>

        <div class="b-offers__list">
            <? if (!empty($_REQUEST["ajax"])) {
                $APPLICATION->RestartBuffer();
            } ?>
            <? foreach ($arResult["ITEMS"] as $arItem): ?>
                <?if (empty($arItem["UF_ORG"])) continue;?>
                <?
                $user = $arItem["USER"];
                $offer = $arItem["OFFER"];
                ?>
                <?
                if ($user["UF_NOTE"] != "норм." && $user["UF_NOTE"]) {
                    continue;
                }
                ?>
                <?
                /*if ($user["UF_NOTE"] == "в процессе оформления (лицензии нет)" || $user["UF_NOTE"] == "лицензия отозвана"
                    || $user["UF_NOTE"] == "лицензия аннулирована" || $user["UF_NOTE"] == "ликвидирована") continue;*/
                ?>
                <? if (!empty($inflation) && (empty($_REQUEST["SORT"]) || !empty($_REQUEST["SORT"]["percent"])) && $showInflation): ?>
                    <? if (((empty($_REQUEST["SORT"]) || $_REQUEST["SORT"]["percent"] == "D") && $inflation > floatval($arItem["UF_PERCENT"])) || ($_REQUEST["SORT"]["percent"] == "A" && $inflation < floatval($arItem["UF_PERCENT"]))): ?>
                        <div class="row b-offers__infl" data-percent="<?= floatval($inflation) ?>">
                            <div class="columns medium-3 medium-offset-4 small-4 small-text-center medium-text-left">
                                <div class="b-offers__type b-offers__type_infl"><?= $inflationName ?></div>
                            </div>
                            <div class="columns medium-2 small-4 text-right end b-offers__percent b-offers__bility">
                                <div class="b-offers__prof b-offers__prof_infl b-offers__prof_main"><?= $inflation ?>
                                    <span>%</span></div>
                            </div>
                        </div>
                        <? $showInflation = false; ?>
                    <? endif; ?>
                <? endif; ?>
                <div class="b-offers__item row" data-equalizer data-equalizer-mq="medium-up"
                     data-percent="<?= floatval($arItem["UF_PERCENT"]) ?>">
                    <div class="column medium-4 small-4 b-offers__firsttd" data-equalizer-watch>
                        <div class="b-offers__logo">
                            <? if (!empty($user["WORK_LOGO"])): ?>
                                <? $file = CFile::ResizeImageGet($user["WORK_LOGO"], array('width' => 30, 'height' => 30), BX_RESIZE_IMAGE_PROPORTIONAL); ?>
                                <img src="<?= $file["src"]; ?>">
                            <? endif; ?>
                        </div>
                        <div class="b-offers__name">
                            <?= $arItem["UF_ORG"] ?>
                        </div>
                        <div class="b-offers__arrows"></div>
                    </div>

                    <div class="column medium-3 hide-for-small-only" data-equalizer-watch>
                        <?
                        $class = "";
                        if ($offer["UF_NAME"] == "-") {$class="b-offers__prof";}?>
                        <div class="b-offers__type <?=$class?>">
                            <?= $offer["UF_NAME"] ?>
                        </div>
                    </div>
                    <div class="column small-4 medium-2 text-right b-offers__profit b-offers__bility"
                         data-equalizer-watch>
                        <div class="b-offers__prof b-offers__prof_main">
                            <div class="has-tooltip" data-tooltip aria-haspopup="true"
                                 title="<?= !empty($offer["UF_UPDATED"]) && is_object($offer["UF_UPDATED"]) ? "Обновлено<br>" . strtolower(CIBlockFormatProperties::DateFormat("d M Y в H:i", $offer["UF_UPDATED"]->getTimestamp())) : "" ?>">
                                <? if (floatval($arItem["UF_PERCENT"]) > 0.1) {?>
                                    <?= floatval($arItem["UF_PERCENT"]); ?> <span>%</span>
                                <?} else {echo "-";}?>
                            </div>
                        </div>
                    </div>
                    <div class="column small-4 medium-2 text-right" data-equalizer-watch>
                        <div class="b-offers__prof has-tooltip b-offers__prof_main" data-tooltip aria-haspopup="true"
                             title="<?=$arItem["UF_SAFETY"] ?> место из <?=$arResult["USER_COUNT"] ?>"><?= $arItem["UF_SAFETY"] ?>
                            <span>место</span>
                        </div>
                    </div>
                    <div class="column hide-for-small-only medium-1 text-left" data-equalizer-watch>
                        <a href="#"
                           class="b-offers__stars js-favorite-add"
                           data-id="<?= $offer["ID"] ?>"></a>
                    </div>

                    <div class="column small-12 b-offers__hidden">
                        <div class="row b-offers__more">
                            <div class="column medium-1 show-for-medium-up">
                                &nbsp;
                            </div>
                            <div class="column medium-11">
                                <div class="row b-offers__more-item show-for-small-only">
                                    <div class="column medium-3 small-6">
                                        <div class="b-offers__label">Наименование предложения</div>
                                    </div>
                                    <div class="column medium-9 small-6 b-offers__nopadding">
                                        <div class="b-offers__res2">
                                            <div class="b-offers__rest">
                                                <?= $offer["UF_NAME"] ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row b-offers__more-item">
                                    <div class="column medium-3 small-6">
                                        <div class="b-offers__label">Рейтинг организации</div>
                                    </div>
                                    <div class="column medium-9 small-6 b-offers__nopadding">
                                        <div class="b-offers__res2">
                                            <div class="b-offers__rest b-offers__rest_rating">
                                                <div class="has-tooltip" data-tooltip aria-haspopup="true"
                                                     title="<?= !empty($user["RATING_UPDATED"]) && is_object($user["RATING_UPDATED"]) ? "Обновлено<br>" . strtolower(CIBlockFormatProperties::DateFormat("d M Y в H:i", $user["RATING_UPDATED"]->getTimestamp())) : "" ?>">
                                                    <? if (!empty($user["RATING"])): ?>
                                                        <span class="x-rating-list">
                                                            <? foreach ($user["RATING"] as $agency => $rating): ?>
                                                                <span class="x-rating-agency"><?= $agency ?></span>
                                                                <span
                                                                    class="b-offers__prof"><?= $rating ?></span>
                                                                <br>
                                                            <? endforeach; ?>
                                                        </span>
                                                    <? else: ?>
                                                        -
                                                    <? endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row b-offers__more-item">
                                    <div class="column medium-3 small-6">
                                        <div class="b-offers__label b-offers__label_bot">Участие государства в капитале
                                            организации
                                        </div>
                                    </div>
                                    <div class="column medium-9 small-6 b-offers__nopadding">
                                        <div class="b-offers__res2">
                                            <div class="b-offers__rest">
                                                <div class="has-tooltip" data-tooltip aria-haspopup="true"
                                                     title="<?= !empty($user["TIMESTAMP_X"]) ? "Обновлено<br>" . strtolower(CIBlockFormatProperties::DateFormat("d M Y в H:i", strtotime($user["TIMESTAMP_X"]))) : "" ?>">
                                                    <?= !empty($user["UF_STATE_PARTICIP"]) ? "Да" : "-" ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <? if (intval($offer["UF_METHOD"]) === 3): ?>
                                    <div class="row b-offers__more-item">
                                        <div class="column medium-3 small-6">
                                            <div class="b-offers__label b-offers__label_bot">Участие в системе
                                                страхования
                                                вкладов
                                            </div>

                                        </div>
                                        <div class="column medium-9 small-6 b-offers__nopadding">
                                            <div class="b-offers__res2">
                                                <div
                                                    class="b-offers__img<?= !empty($user["UF_BANK_PARTICIP"]) ? " b-offers__prof" : "" ?>">
                                                    <div class="has-tooltip" data-tooltip aria-haspopup="true"
                                                         title="<?= !empty($user["TIMESTAMP_X"]) ? "Обновлено<br>" . strtolower(CIBlockFormatProperties::DateFormat("d M Y в H:i", strtotime($user["TIMESTAMP_X"]))) : "" ?>">
                                                        <?= !empty($user["UF_BANK_PARTICIP"]) ? '<img src="' . WIC_TEMPLATE_PATH . '/images/asb.jpg" alt="">' : '-' ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <? endif; ?>

                                <div class="row b-offers__more-item">
                                    <div class="column medium-3 small-6">
                                        <div class="b-offers__label b-offers__label_capital"><span
                                                data-tooltip
                                                aria-haspopup="true"
                                                class="has-tip assets__tooltip"
                                                title="Для банков соответствует нормативу Н1.0 на последнюю отчетную дату.<br><br>Для остальных организаций соответствует отношению суммы собственных средств (капитала) к сумме активов по балансу, в процентах."></span>
                                            Капитал / Активы
                                        </div>

                                    </div>
                                    <div class="column medium-9 small-6 b-offers__nopadding">
                                        <div class="b-offers__res2">
                                            <div class="has-tooltip" data-tooltip aria-haspopup="true"
                                                 title="<?= !empty($user["UF_CAPITAL_A_DATE"]) ? "По состоянию на<br>" . strtolower(CIBlockFormatProperties::DateFormat("d.m.Y", strtotime($user["UF_CAPITAL_A_DATE"]))) : "" ?>">
                                                <? if (!empty($user["UF_CAPITAL_ASSETS"])): ?>
                                                    <div class="b-offers__prof">
                                                        <?= $user["UF_CAPITAL_ASSETS"]; ?>
                                                        <span>%</span>
                                                    </div>
                                                <? else: ?>
                                                    -
                                                <? endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row b-offers__more-item">
                                    <div class="column medium-3 small-6">
                                        <div class="b-offers__label b-offers__label_capital"><span
                                                data-tooltip
                                                aria-haspopup="true"
                                                class="has-tip assets__tooltip"
                                                title="Соответствует сумме собственных средств (капитала) на последнюю отчетную дату"></span>
                                            Собственные средства (капитал)
                                        </div>

                                    </div>
                                    <div class="column medium-9 small-6 b-offers__nopadding">
                                        <div class="b-offers__res2">
                                            <div class="has-tooltip" data-tooltip aria-haspopup="true"
                                                 title="<?= !empty($user["UF_CAPITAL_DATE"]) ? "По состоянию на<br>" . strtolower(CIBlockFormatProperties::DateFormat("d.m.Y", strtotime($user["UF_CAPITAL_DATE"]))) : "" ?>">
                                                <? if (!empty($user["UF_CAPITAL"])): ?>
                                                    <div class="b-offers__prof">
                                                        <?= number_format(round(intval(preg_replace("#[^\d]#is", "", $user["UF_CAPITAL"])) / 1000000, 1), 1, ",", " ") ?>
                                                        <span>млрд. рублей</span>
                                                    </div>
                                                <? else: ?>
                                                    -
                                                <? endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row b-offers__more-item">
                                    <div class="column medium-3 small-6">
                                        <div class="b-offers__label b-offers__label_capital"><span data-tooltip
                                                                                                   aria-haspopup="true"
                                                                                                   class="has-tip assets__tooltip"
                                                                                                   title="Для банков соответствует сумме активов, взвешенных по уровню риска.<br><br>Для остальных организаций соответствует сумме активов по балансу."></span>
                                            Активы
                                        </div>
                                    </div>
                                    <div class="column medium-9 small-6 b-offers__nopadding">
                                        <div class="b-offers__res2">
                                            <div class="has-tooltip" data-tooltip aria-haspopup="true"
                                                 title="<?= !empty($user["UF_ASSETS_DATE"]) ? "По состоянию на<br>" . strtolower(CIBlockFormatProperties::DateFormat("d.m.Y", strtotime($user["UF_ASSETS_DATE"]))) : "" ?>">
                                                <? if (!empty($user["UF_ASSETS"])): ?>
                                                    <div class="b-offers__prof">
                                                        <?= number_format(round(intval(preg_replace("#[^\d]#is", "", $user["UF_ASSETS"])) / 1000000, 1), 1, ",", " ") ?>
                                                        <span>млрд. рублей</span>
                                                    </div>
                                                <? else: ?>
                                                    -
                                                <? endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row b-offers__more-item">
                                    <div class="column medium-3 small-6">
                                        <div class="b-offers__label">Организация</div>
                                    </div>
                                    <div class="column medium-9 small-6 b-offers__nopadding">
                                        <div class="b-offers__res2">
                                            <div class="b-offers__rest">
                                                <div class="has-tooltip" data-tooltip aria-haspopup="true"
                                                     title="<?= !empty($user["TIMESTAMP_X"]) ? "Обновлено<br>" . strtolower(CIBlockFormatProperties::DateFormat("d M Y в H:i", strtotime($user["TIMESTAMP_X"]))) : "" ?>">
                                                    <?= $user["FULL_WORK_COMPANY"] ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row b-offers__more-item">
                                    <div class="column medium-3 small-6">
                                        <div class="b-offers__label">Лицензия ЦБ РФ:</div>
                                    </div>
                                    <div class="column medium-9 small-6 b-offers__nopadding">
                                        <div class="b-offers__res2">
                                            <div class="b-offers__rest">
                                                <div class="has-tooltip" data-tooltip aria-haspopup="true"
                                                     title="<?= !empty($user["TIMESTAMP_X"]) ? "Обновлено<br>" . strtolower(CIBlockFormatProperties::DateFormat("d M Y в H:i", strtotime($user["TIMESTAMP_X"]))) : "" ?>">
                                                    <?if ($user["UF_LICENSE"]) {?>
                                                        <?= $user["UF_LICENSE"] ?>
                                                    <?} else {echo "-";}?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row b-offers__more-item">
                                    <div class="column medium-3 small-6">
                                        <div class="b-offers__label">Лицензия</div>
                                    </div>
                                    <div class="column medium-9 small-6 b-offers__nopadding">
                                        <div class="b-offers__res2">
                                            <div class="b-offers__rest">
                                                <div class="has-tooltip" data-tooltip aria-haspopup="true"
                                                     title="<?= !empty($user["TIMESTAMP_X"]) ? "Обновлено<br>" . strtolower(CIBlockFormatProperties::DateFormat("d M Y в H:i", strtotime($user["TIMESTAMP_X"]))) : "" ?>">
                                                    <?if ($user["UF_NOTE"]) {?>
                                                        <?= $user["UF_NOTE"] ?>
                                                    <?} else {echo "-";}?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <? if ($USER->IsAuthorized()): ?>
                                        <div class="columns show-for-small-only b-offers__best b-offers__nopadding">
                                            <div class="b-offers__liked">Избранное</div>
                                            <a href="#"
                                               class="b-offers__stars js-favorite-add"
                                               data-id="<?= $offer["ID"] ?>"></a>
                                        </div>
                                    <? endif; ?>
                                    <? if (!empty($offer["UF_SITE"])): ?>
                                        <? if (!preg_match("#^(http|//)#is", $offer["UF_SITE"])) $offer["UF_SITE"] = "//" . $offer["UF_SITE"] ?>
                                        <div
                                            class="column medium-6 medium-offset-3 end b-offers__go b-offers__nopadding">
                                            <a href="<?= $offer["UF_SITE"] ?>" class="b-offers__link" target="_blank">
                                                Перейти на сайт
                                            </a>
                                        </div>
                                    <? endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <? endforeach; ?>
            <? if (!empty($inflation) && (empty($_REQUEST["SORT"]) || !empty($_REQUEST["SORT"]["percent"])) && $showInflation): ?>
                <div class="row b-offers__infl" data-percent="<?= floatval($inflation) ?>">
                    <div class="columns medium-3 medium-offset-4 small-4 small-text-center medium-text-left">
                        <div class="b-offers__type b-offers__type_infl"><?= $inflationName ?></div>
                    </div>
                    <div class="columns medium-2 small-4 text-right end b-offers__percent b-offers__bility">
                        <div class="b-offers__prof b-offers__prof_infl b-offers__prof_main"><?= $inflation ?>
                            <span>%</span></div>
                    </div>
                </div>
                <? $showInflation = false; ?>
            <? endif; ?>

            <script>
                window.paging = {
                    pageNum: parseInt("<?=$arResult["NAV_PAGE_NUM"]?>"),
                    pageCount: parseInt("<?=$arResult["NAV_PAGE_COUNT"]?>")
                };

                $(function () {
                    $('.b-offers__item').unbind().on("click", function (e) {
                        var clickItem = $(e.target);
                        if (clickItem.closest(".b-offers__hidden").length) {
                            return false;
                        }

                        $(this).toggleClass('active');
                        $(".x-rating-list:not(.ratingChecked)", $(this)).each(function () {
                            var maxWidth = 0;
                            $(".x-rating-agency", $(this)).each(function () {
                                if ($(this).outerWidth(true) > maxWidth)
                                    maxWidth = $(this).outerWidth(true);
                            });

                            maxWidth += 35;
                            $(".x-rating-agency", $(this)).css({"min-width": maxWidth});
                            $(this).addClass("ratingChecked");
                        });
                    });

                    $(".b-offers__link").unbind().on("click", function (event) {
                        window.open(this.href, '_blank');
                        event.preventDefault();
                        return false;
                    });

                    $(".x-rating-agency").css({"display": "inline-block"});

                    $(".js-favorite-add").unbind().on("click", function (event) {
                        var id = $(this).data("id"),
                            _this = $(this);
                        $.ajax({
                            url: "/local/ajax/add2favorite.php",
                            data: {
                                id: id,
                                ajax: "Y",
                                sessid: "<?=bitrix_sessid()?>"
                            },
                            method: "post",
                            dataType: "json",
                            success: function (response) {
                                $(".js-favorite-add[data-id='" + id + "']").toggleClass("b-offers__stars_active");
                                var search = window.location.search;
                                if (search.match("favorite=")) {
                                    if (!_this.hasClass("b-offers__stars_active")) {
                                        _this.closest(".b-offers__item").detach();

                                        if (!$(".b-offers__item").length) {
                                            $(".b-offers__header").closest(".row").detach();
                                            $(".b-offers").html('<h2 class="i-offers__no-items">Предложения отсутствуют</h2>');
                                        }
                                    }
                                }
                            }
                        });

                        event.preventDefault();
                        return false;
                    });

                    (function () {
                        $.ajax({
                            url: "/local/ajax/add2favorite.php",
                            data: {
                                ajax: "Y",
                                sessid: "<?=bitrix_sessid()?>",
                                action: "getList"
                            },
                            method: "post",
                            dataType: "json",
                            success: function (response) {
                                if (response.LIST !== undefined) {
                                    $.each(response.LIST, function (id, val) {
                                        if (val == "1")
                                            $(".js-favorite-add[data-id='" + id + "']").addClass("b-offers__stars_active");
                                        else
                                            $(".js-favorite-add[data-id='" + id + "']").removeClass("b-offers__stars_active");
                                    });
                                }
                            }
                        });
                    })();
                });
            </script>

            <? if (!empty($_REQUEST["ajax"])) {
                die();
            } ?>
        </div>

        <?if ($arParams["PAGING"] == "Y") {?>
            <?
            $iCurr = intval($_GET["page"]) > 0 ? $_GET["page"] : 1;
            $iLastPage = $arResult["NAV_PAGE_COUNT"];
            $iLeftLimit = $iCurr > 1 ? $iCurr - 1 : 0;
            $iRightLimit = $iCurr == $arResult["NAV_PAGE_COUNT"] ? 0 : $iCurr + 1;

            $limit = 3;
            $total_pages = $iLastPage;
            $stages = 1;
            $page = $_GET['page'];

            if ($page == 0){$page = 1;}
            $prev = $page - 1;
            $next = $page + 1;
            $lastpage = $iLastPage;
            $LastPagem1 = $lastpage - 1;

            $paginate = '';
            if($lastpage > 1)
            {

                $paginate .= "<div class='paginate'>";
                if ($page > 1){
                    $paginate.= "<a href='".$APPLICATION->GetCurPageParam("page=".$prev, array("page"))."'><</a>";

                }else{
                    $paginate.= "<span class='disabled'><</span>"; }

                if ($lastpage < 5 + ($stages * 2))
                {
                    for ($counter = 1; $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page){
                            $paginate.= "<span class='current'>$counter</span>";
                        }else{
                            $paginate.= "<a href='".$APPLICATION->GetCurPageParam("page=".$counter, array("page"))."'>$counter</a>";}
                    }
                }
                elseif($lastpage > 3 + ($stages * 2))
                {
                    if($page < 1 + ($stages * 2))
                    {
                        for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
                        {
                            if ($counter == $page){
                                $paginate.= "<span class='current'>$counter</span>";
                            }else{
                                $paginate.= "<a href='".$APPLICATION->GetCurPageParam("page=".$counter, array("page"))."'>$counter</a>";
                            }
                        }
                        $paginate.= "...";
                        $paginate.= "<a href='".$APPLICATION->GetCurPageParam("page=".$LastPagem1, array("page"))."'>$LastPagem1</a>";
                        $paginate.= "<a href='".$APPLICATION->GetCurPageParam("page=".$lastpage, array("page"))."'>$lastpage</a>";
                    }
                    elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
                    {
                        $paginate.= "<a href='".$APPLICATION->GetCurPageParam("page=1", array("page"))."'>1</a>";
                        $paginate.= "<a href='".$APPLICATION->GetCurPageParam("page=2", array("page"))."'>2</a>";
                        $paginate.= "...";
                        for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
                        {
                            if ($counter == $page){
                                $paginate.= "<span class='current'>$counter</span>";
                            }else{
                                $paginate.= "<a href='".$APPLICATION->GetCurPageParam("page=".$counter, array("page"))."'>$counter</a>";}
                        }
                        $paginate.= "...";
                        $paginate.= "<a href='".$APPLICATION->GetCurPageParam("page=".$LastPagem1, array("page"))."'>$LastPagem1</a>";
                        $paginate.= "<a href='".$APPLICATION->GetCurPageParam("page=".$lastpage, array("page"))."'>$lastpage</a>";
                    }
                    else
                    {
                        $paginate.= "<a href='".$APPLICATION->GetCurPageParam("page=1", array("page"))."'>1</a>";
                        $paginate.= "<a href='".$APPLICATION->GetCurPageParam("page=2", array("page"))."'>2</a>";
                        $paginate.= "...";
                        for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
                        {
                            if ($counter == $page){
                                $paginate.= "<span class='current'>$counter</span>";
                            }else{
                                $paginate.= "<a href='".$APPLICATION->GetCurPageParam("page=".$couinter, array("page"))."'>$counter</a>";
                            }
                        }
                    }
                }

                if ($page < $counter - 1){
                    $paginate.= "<a href='".$APPLICATION->GetCurPageParam("page=".$next, array("page"))."'> > </a>";
                }else{
                    $paginate.= "<span class='disabled'> > </span>";
                }

                $paginate.= "</div>";

            }

            echo $paginate;
            ?>
        <?} else {?>

            <? if ($arResult["NAV_PAGE_NUM"] < $arResult["NAV_PAGE_COUNT"]): ?>
                <div class="b-offers__bottom">
                    <a href="<?= \Cetera\Tools\Uri::GetCurPageParam("", Array("page")); ?>"
                       class="b-offers__showmore js-show-more"><span>+</span>Показать ещё</a>
                </div>

                <script type="text/javascript">
                    $(function () {
                        $(".js-show-more").on("click", function () {
                            var data = $(this).attr("href");
                            if (data.indexOf("?") > -1) {
                                data = data.split("?");
                                data = data[1];
                            }
                            else {
                                data = "";
                            }
                            data += "&ajax=Y&page=" + (window.paging.pageNum + 1);

                            $.ajax({
                                url: "/include/main_offer.php",
                                data: data,
                                method: "get",
                                success: function (response) {
                                    $(".b-offers__list").append(response);
                                    var inflation = $(".row.b-offers__infl"),
                                        val = parseFloat(inflation.data("percent")),
                                        emptySort = "<?=empty($_REQUEST["SORT"]) ? 1 : 0;?>";

                                    if (emptySort == "1") {
                                        $(".b-offers__list .b-offers__item").each(function () {
                                            var itemVal = parseFloat($(this).data("percent"));
                                            if (itemVal >= val)
                                                inflation.insertAfter($(this));
                                        });
                                    }

                                    $(document).foundation("tooltip", "reflow");

                                    if (window.paging.pageNum == window.paging.pageCount) {
                                        $(".js-show-more").detach();
                                    }
                                }
                            });
                            return false;
                        });
                    });
                </script>
            <? endif; ?>

        <?}?>

    </section>
<? else: ?>
    <div class="row">
        <div class="column small-12">
            <h2 class="i-offers__no-items">Предложения отсутствуют</h2>
        </div>
    </div>
<? endif; ?>
