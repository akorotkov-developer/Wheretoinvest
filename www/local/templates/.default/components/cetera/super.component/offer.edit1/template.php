<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$redirectUrl = preg_replace("#" . (!empty($arParams["ID"]) ? "edit/.*?/" : "add/") . "#is", "", $APPLICATION->GetCurPage());

if (defined("ERROR_404"))
    return;

$APPLICATION->AddHeadScript($templateFolder."/js/block-sim.js");
?>

<form action="" method="post" class="x-save-form">
    <input type="hidden" name="sessid" value="<?= bitrix_sessid() ?>">
    <input type="hidden" name="ID" value="<?= $arParams["ID"] ?>">
    <input type="hidden"  name="UF_TYPE" value="<?= $arParams["TYPE"] ?>">

    <div class="b-main-block__body"></div>

    <?
    $arFields = Array(
        "UF_METHOD" => Array(
            "TYPE" => "SELECT",
            "VALUE" => $arResult["ITEM"]["UF_METHOD"],
            "LIST" => $arResult["FIELDS"]["UF_METHOD"],
            "TITLE" => "Способ вложения",
            "REQUIRED" => "Y",
            "COL_SIZE" => "6",
            "PLACEHOLDER" => "Выберите один из вариантов"
        ),
        "UF_NAME" => Array(
            "TYPE" => "TEXT",
            "TITLE" => "Наименование предложения (без кавычек)",
            "VALUE" => htmlentities($arResult["ITEM"]["UF_NAME"]),
            "REQUIRED" => "Y",
        ),
        "UF_SITE" => Array(
            "TYPE" => "TEXT",
            "TITLE" => "Ссылка на предложение",
            "VALUE" => $arResult["ITEM"]["UF_SITE"],
        )
    );

    echo getFormFields($arFields);

    ?>
    <div class="row">
        <div class="columns region">
            <div class="region__main">
                <div class="row course">
                    <div class="columns">
                        <label for="#" class="content__label">Регион действия </label>
                        <a href="#" data-reveal-id="selectedRegions" class="course__title">Для <span
                                class="course__red"><?= count($arResult["ITEM"]["UF_REGIONS"]) ?></span> регионов РФ</a>

                        <div id="selectedRegions" class="reveal-modal modal" data-reveal aria-labelledby="modalTitle"
                             aria-hidden="true" role="dialog">
                            <div class="modal__col">
                                <div class="modal__title"></div>
                                <div class="modal__wrp">
                                    <label for="dd2109p" class="modal__js-choose-all modal__service">Выбрать все</label>
                                </div>
                                <div class="modal__wrp">
                                    <label for="dd3109p" class="modal__js-disrobe-all modal__service">Снять все</label>
                                </div>
                            </div>
                            <?
                            $i = 0;
                            $nextCol = false;
                            $rowCount = 1;
                            ?>
                            <ul class="small-block-grid-1 medium-block-grid-<?= $arParams["COL_CNT"] ?>">
                                <li>
                                    <? foreach ($arResult["REGIONS"] as $char => $list): ?>
                                    <? if ($nextCol): ?>
                                    <?
                                    $nextCol = false;
                                    $rowCount++;
                                    ?>
                                </li>
                                <li>
                                    <? endif; ?>
                                    <div class="modal__title"><?= $char ?></div>
                                    <? foreach ($list as $value): ?>
                                        <div class="modal__wrp">
                                            <input type="checkbox" name="UF_REGIONS[]" id="regions_<?= $value["ID"] ?>"
                                                   class="modal__checkbox"
                                                   value="<?= $value["ID"] ?>" <? if (in_array($value["ID"], $arResult["ITEM"]["UF_REGIONS"])): ?> checked<? endif; ?>>
                                            <label for="regions_<?= $value["ID"] ?>"
                                                   class="modal__chck"><?= $value["NAME"] ?></label>
                                        </div>
                                        <? if ($i !== 0 && $i % (ceil($arResult["TOTAL_COUNT"] / $arParams["COL_CNT"]) - 2) == 0 && $rowCount < $arParams["COL_CNT"]) $nextCol = true; ?>
                                        <? ++$i; ?>
                                    <? endforeach; ?>
                                    <? endforeach; ?>
                                </li>
                            </ul>
                            <a class="close-reveal-modal modal__close" aria-label="Close">&#215;</a>
                        </div>
                    </div>
                    <div class="columns">
                        <ul class="tabs" data-tab>
                            <? $first = true; ?>
                            <? foreach ($arResult["FIELDS"]["UF_CURRENCY"] as $key => $currency): ?>
                                <li class="tab-title<? if ($first): ?> active<? endif; ?>"><a href="#panel<?= $key ?>"
                                                                                              class="course__<?= $arResult["XML_FIELDS"]["UF_CURRENCY"][$key]; ?>"><?= $currency ?></a>
                                </li>
                                <? $first = false; ?>
                            <? endforeach; ?>
                        </ul>
                        <div class="tabs-content">
                            <? $first = true; ?>
                            <? foreach ($arResult["FIELDS"]["UF_CURRENCY"] as $key => $currency): ?>
                                <div
                                    class="content<? if ($first): ?> active<? endif; ?> content_<?= $arResult["XML_FIELDS"]["UF_CURRENCY"][$key]; ?>"
                                    id="panel<?= $key ?>">
                                    <div class="graph__wrapper">
                                        <div class="graph graph_reg">
                                            <div class="graph__head">
                                                <div class="graph__th graph__th_reg">&nbsp;</div>
                                                <? foreach ($arResult["MATRIX_COLS"][$key] as $col): ?>
                                                    <? list($start, $end) = explode(" - ", $col); ?>
                                                    <div class="graph__th graph__th_reg" data-id="<?= $col ?>"><a
                                                            class="graph__cr"></a>
                                                        <? if (empty($end)): ?>
                                                            от <?= $start . "<br>" . \Cetera\Tools\Utils::pluralForm($start, "дня", "дней", "дней", "дней") ?>
                                                            <br>
                                                        <? elseif ($start == $end): ?>
                                                            <?= $start . "<br>" . \Cetera\Tools\Utils::pluralForm($start, "день", "дня", "дней", "дней") ?>
                                                        <? else: ?>
                                                            <?= $start . " - " . $end . "<br>" . \Cetera\Tools\Utils::pluralForm($end, "день", "дня", "дней", "дней"); ?>
                                                        <? endif; ?>
                                                    </div>
                                                <? endforeach; ?>
                                            </div>
                                            <div class="graph__body">
                                                <? foreach ($arResult["MATRIX"][$key] as $row => $cols): ?>
                                                    <div class="graph__row" data-id="<?= $row ?>">
                                                        <? list($start, $end) = explode(" - ", $row); ?>
                                                        <div class="graph__td graph__td_reg">
                                                            <? if (empty($end)): ?>
                                                                от <?= $start ?>
                                                            <? elseif ($start == $end): ?>
                                                                <?= $start ?>
                                                            <? else: ?>
                                                                от <?= $start . "<br>до " . $end; ?>
                                                            <? endif; ?>
                                                            <a class="graph__cr graph__cr_td"></a>
                                                        </div>
                                                        <? foreach ($cols as $col => $percent): ?>
                                                            <div
                                                                class="graph__td graph__td_js<? if (!empty($percent)): ?> i-show-text<? endif; ?>">
                                                                <span class="js-percent-text"><?= $percent; ?>%</span>
                                                                <input type="text"
                                                                       class="region__js-percent text-center"
                                                                       name="UF_MATRIX[<?= $key ?>][<?= $row ?>][<?= $col ?>]"
                                                                       value="<?= $percent; ?>">
                                                            </div>
                                                        <? endforeach; ?>
                                                    </div>
                                                <? endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <? $first = false; ?>
                            <? endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="b-form__row">
                    <div class="row">
                        <div class="columns medium-6 region__net region__snood">
                            <label for="ggs" class="region__label">Добавить сумму</label>

                            <div class="region__from">от</div>
                            <input type="text" class="region__inp region__inp_medium region__js-sum" value="">

                            <div class="region__from">до</div>
                            <input type="text" class="region__inp region__inp_medium region__js-sum_sec" value="">
                            <span class="region__add region__js-row">+</span>
                        </div>
                        <div class="columns medium-6 region__net">
                            <label for="ggs" class="region__label">Добавить срок</label>

                            <div class="region__from">от</div>
                            <input type="text" class="region__inp region__inp_mini region__js-fir" value="">

                            <div class="region__from">до</div>
                            <input type="text" class="region__inp region__inp_mini region__js-sec" value="">

                            <div class="region__from">дней</div>
                            <span class="region__add region__js-col">+</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="column small-12">
            <?
            $publicationCost = floatval(\Ceteralabs\UserVars::GetVar('PUBLICATION_COST')["VALUE"]);
            $canActivate = floatval(getContainer("User")["UF_CASH"]) < $publicationCost ? false : true;

            if ($publicationCost <= 0) {
                $canActivate = true;
            }

            $hasActivate = count($arResult["ITEM"]["UF_ACTIVE_START"]) > 0 ? true : false;
            ?>
            <br>
            <label class="region__label">Период публикации на главной странице сайта</label>
            <? if ($hasActivate || $canActivate): ?>
                <div class="i-date-table_wrapper">
                    <div class="i-date-table">
                        <div class="i-date-table__head">
                            <div class="i-date-table__row">
                                <div class="i-date-table__col">Дата начала</div>
                                <div class="i-date-table__col">Дата окончания</div>
                                <div class="i-date-table__col">Срок</div>
                                <div class="i-date-table__col i-date-table__col_tariff">Тариф,<br> рублей в сутки</div>
                                <div class="i-date-table__col">Итого</div>
                            </div>
                        </div>
                        <div class="i-date-table__body">
                            <? foreach ($arResult["ITEM"]["UF_ACTIVE_START"] as $key => $val): ?>
                                <?
                                $start = $val;
                                $end = $arResult["ITEM"]["UF_ACTIVE_END"][$key];

                                if (empty($start) || empty($end))
                                    continue;

                                $start = new \DateTime($start);
                                $end = new \DateTime($end);
                                $interval = $start->diff($end);
                                $interval = intval($interval->format("%R%a")) + 1;

                                $publicationCostItem = floatval($arResult["ITEM"]["UF_ACTIVE_COST"][$key]);
                                ?>
                                <div class="i-date-table__row">
                                    <div
                                        class="i-date-table__col"><?= $start->format("d.m.Y") ?></div>
                                    <div
                                        class="i-date-table__col"><?= $end->format("d.m.Y") ?></div>
                                    <div
                                        class="i-date-table__col"><?= $interval . " " . \Cetera\Tools\Utils::pluralForm($interval, "сутки", "суток", "суток", "суток") ?></div>
                                    <div
                                        class="i-date-table__col"><?= number_format($publicationCostItem, 0, ".", " ") ?>
                                        руб.
                                    </div>
                                    <div class="i-date-table__col"
                                         style="white-space: nowrap;"><?= number_format($publicationCostItem * $interval, 0, ".", " ") ?> руб.
                                    </div>
                                </div>
                            <? endforeach; ?>
                            <? if ($canActivate): ?>
                                <?
                                $arFields["DATE_START"] = Array(
                                    "UF_ACTIVE_START" => Array(
                                        "TYPE" => "DATETIME",
                                        "VALUE" => "",
                                        "INPUT_CLASS" => "js-active-start",
                                        "PLACEHOLDER" => "дд.мм.гггг",
                                        "ONLY_FUTURE" => "Y"
                                    )
                                );
                                $arFields["DATE_END"] = Array(
                                    "UF_ACTIVE_END" => Array(
                                        "TYPE" => "DATETIME",
                                        "VALUE" => "",
                                        "INPUT_CLASS" => "js-active-end",
                                        "PLACEHOLDER" => "дд.мм.гггг",
                                        "ONLY_FUTURE" => "Y"
                                    )
                                );


                                ?>
                                <div class="i-date-table__row">
                                    <div class="i-date-table__col" style="min-width: 145px;">
                                        <?= getFormFields($arFields["DATE_START"], 12, "b-form__row_no-margin"); ?>
                                    </div>
                                    <div class="i-date-table__col" style="min-width: 145px;">
                                        <?= getFormFields($arFields["DATE_END"], 12, "b-form__row_no-margin"); ?>
                                    </div>
                                    <div class="i-date-table__col js-active-diff"></div>
                                    <div class="i-date-table__col">
                                        <?= number_format($publicationCost, 0, ".", " ") ?> руб.
                                    </div>
                                    <div class="i-date-table__col js-active-summ" style="white-space: nowrap;"></div>
                                </div>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
                <br>
            <? endif; ?>

            <? if (!$hasActivate && !$canActivate): ?>
                <?= getMess("У Вас недостаточно средств на счете для активации данного предложения.<br>Стоимость публикации - " . $publicationCost . " руб. в сутки", "alert", false, false); ?>
            <? endif; ?>

            <div class="js-alert hide">
                <?= getMess("", "alert", false); ?>
            </div>
            <br>
        </div>
    </div>
    <div class="row">
        <div class="columns">
            <button type="submit" id="submit" class="content__submit">Сохранить</button>
            <button class="content__delete js-delete" type="button">Удалить предложение</button>
        </div>
    </div>
</form>
<script type="text/javascript">
    $(function () {
        (function () {
            $(document).on('close.fndtn.reveal', '#selectedRegions', function () {
                var num = $('#selectedRegions').find('input:checked').length;
                $('.course__title .course__red').text(num);
            });
        })();

        (function () {
            $(".x-save-form input").on("keypress", function (event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $(this).blur();
                    event.preventDefault();
                    return false;
                }
            });

            function deleteCol() {
                var num = $(this).parent().index();

                $(this).closest('.graph').find('.graph__row').each(function () {
                    $(this).find('.graph__td').eq(num).remove();
                });
                $(this).parent().detach();
                respoTd();
            }

            function deleteRow() {
                $(this).parent().parent().remove();
                respoTd();
            }

            function panelActive() {
                return '#' + $('.content.active').attr('id');
            }

            function showPercentEvent() {
                $(".js-percent-text").unbind().on("click", function () {
                    $(this).parent().removeClass("i-show-text");
                    $(this).parent().find(".region__js-percent").focus();
                });
            }

            showPercentEvent();
            $('.region__js-percent').unbind().on('blur', inputCreate);

            function inputCreate() {
                var tx = $(this).val().replace(",", ".");
                var txt = (+tx).toFixed(2);
                if (txt && !(isNaN(txt))) {
                    $(this).val(txt);
                    if (!$(this).parent().find(".js-percent-text").length)
                        $(this).parent().prepend("<span class='js-percent-text'></span>");

                    $(this).parent().find(".js-percent-text").text(txt + '%');
                    $(this).parent().addClass("i-show-text");
                    showPercentEvent();
                }
            }

            $('.graph__cr:not(.graph__cr_td)').on('click', deleteCol);

            $('.graph__cr_td').click(function () {
                $(this).parent().parent().detach();
            });

            var graphTd = '<div class="graph__td graph__td_js"><input type="text" class="region__js-percent text-center"></div>';

            function pluralForm(n, normative, singular, plural, zero, format) {
                if (n == 0 && zero !== null)
                    return zero;
                var number = Math.abs(n) % 100;
                var n1 = number % 10;
                var form;
                if (number > 10 && number < 20)
                    form = plural;
                else if (n1 > 1 && n1 < 5)
                    form = singular;
                else if (n1 == 1)
                    form = normative;
                else
                    form = plural;
                return form;
            }

            $('.region__js-col').click(function () {
                var firD = $('.region__js-fir').val();
                var secD = $('.region__js-sec').val();
                if (firD) {
                    $(".region__main .tabs-content .content").each(function () {
                        var idA = "#" + $(this).attr("id");
                        var canAdd = true;
                        var dataId = firD;
                        if (secD) {
                            if (parseFloat(secD.replace(/[^\d,.]/g, "")) < parseFloat(firD.replace(/[^\d,.]/g, ""))) {
                                alert("Конечная дата не может быть больше начальной");
                                $('.region__js-sec').val("");
                                return false;
                            }
                            dataId += "-" + secD;
                        }

                        if ($(".graph__th[data-id='" + dataId + "']", idA).length) {
                            canAdd = false;
                        }

                        var numCol = $('.graph__th', idA).length;
                        if (/*numCol <= 15 && */canAdd) {
                            var headText = '<div class="graph__th graph__th_reg" data-id="' + dataId + '"><a class="graph__cr"></a>';
                            if (!secD) {
                                headText += "от " + firD + "<br>" + pluralForm(firD, "дня", "дней", "дней", "дней");
                            }
                            else if (firD == secD) {
                                headText += firD + "<br>" + pluralForm(firD, "день", "дня", "дней", "дней");
                            }
                            else {
                                headText += firD + " - " + secD + "<br>" + pluralForm(secD, "день", "дня", "дней", "дней");
                            }
                            headText += '</div>';

                            $(".graph__head", idA).append(headText);
                            $('.graph__cr:not(.graph__cr_td)').unbind().on('click', deleteCol);

                            $('.graph__row', idA).each(function () {
                                var item = $(graphTd);
                                var colID = $(this).data("id");
                                item.find("input").attr("name", "UF_MATRIX[" + idA + "][" + colID + "][" + dataId + "]");
                                $(this).append($('<div>').append(item.clone()).html());
                            });
                            $('.region__js-percent').unbind().on('blur', inputCreate);
                        }
                    });
                }
                $('.region__js-fir').val("");
                $('.region__js-sec').val("");
                respoTd();
            });

            $('.region__js-row').click(function () {
                var firD = $('.region__js-sum').val();
                var secD = $('.region__js-sum_sec').val();
                if (firD) {
                    var canAdd = true;
                    var idA = panelActive();
                    var dataId = firD;
                    if (secD) {
                        if (parseFloat(secD.replace(/[^\d,.]/g, "")) < parseFloat(firD.replace(/[^\d,.]/g, ""))) {
                            alert("Конечная сумма не может быть больше начальной");
                            $('.region__js-sum_sec').val("");
                            return false;
                        }
                        dataId += "-" + secD;
                    }

                    if ($(".graph__row[data-id='" + dataId + "']", idA).length) {
                        canAdd = false;
                    }

                    if (canAdd) {
                        var numCol = $(idA + ' .graph__th').length;
                        var rowText = '<div class="graph__row" data-id="' + dataId + '"><div class="graph__td graph__td_reg">';
                        if (!secD) {
                            rowText += "от " + firD;
                        }
                        else if (firD == secD) {
                            rowText += firD;
                        }
                        else {
                            rowText += "от " + firD + "<br>до " + secD;
                        }
                        rowText += '<a class="graph__cr graph__cr_td"></a></div>';
                        for (var i = 1; i < numCol; i++) {
                            var item = $(graphTd);
                            var colID = $(".graph__th.graph__th_reg").eq(i).data("id");
                            item.find("input").attr("name", "UF_MATRIX[" + idA + "][" + dataId + "][" + colID + "]");
                            rowText += $('<div>').append(item.clone()).html();
                        }
                        $(idA + " .graph__body").append(rowText);
                        $('.graph__cr_td').unbind().on('click', deleteRow);
                        $('.region__js-percent').unbind().on('blur', inputCreate);
                    }
                }
                $('.region__js-sum').val("");
                $('.region__js-sum_sec').val("");
                respoTd();
            });

            function respoTd() {
                /*var beginNum = 4;
                 var endNum = 8;
                 if (window.innerWidth < 640) {
                 $('.graph__row').each(function () {
                 for (var i = 0; i < beginNum; i++) {
                 $(this).find('.graph__td').eq(i).show()
                 }
                 for (var i = beginNum; i < endNum; i++) {
                 $(this).find('.graph__td').eq(i).hide()
                 }
                 });
                 $('.graph__head').each(function () {
                 for (var i = 0; i < beginNum; i++) {
                 $(this).find('.graph__th').eq(i).show()
                 }
                 for (var i = beginNum; i < endNum; i++) {
                 $(this).find('.graph__th').eq(i).hide()
                 }
                 });
                 }
                 else {
                 $('.graph__row').each(function () {
                 for (var i = beginNum; i < endNum; i++) {
                 $(this).find('.graph__td').eq(i).show()
                 }
                 });
                 $('.graph__head').each(function () {
                 for (var i = beginNum; i < endNum; i++) {
                 $(this).find('.graph__th').eq(i).show()
                 }
                 });
                 }*/
            }

            window.onresize = function () {
                respoTd();
            };
        })();

        (function () {
            $(".js-delete").on("click", function () {
                if (confirm("Вы действительно хотите удалить данное предложение?")) {
                    var url = "<?=$templateFolder?>/ajax.php",
                        _this = $(this).closest("form"),
                        data = "";

                    _this.find("input[name='del']").detach();
                    _this.prepend("<input name='del' value='Y' type='hidden'/>");
                    data = _this.serialize();
                    _this.find(".b-main-block__body").html("");

                    $.ajax({
                        url: url,
                        method: "post",
                        data: data,
                        dataType: "json",
                        success: function (response) {
                            _this.find("input[name='del']").detach();
                            if (response.ERRORS !== undefined) {
                                _this.find(".b-main-block__body").prepend('<div data-alert class="alert-box alert radius">' + response.ERRORS + '<a href="#" class="close">&times;</a></div>');
                                $(document).foundation('alert', 'reflow');
                            }
                            else if (response.SUCCESS !== undefined) {
                                var redirect = "<?=$redirectUrl?>";
                                if (redirect !== "") {
                                    window.location.href = "<?=$redirectUrl?>";
                                }
                                else {
                                    _this.find(".b-main-block__body").prepend('<div data-alert class="alert-box success radius">' + response.SUCCESS + '<a href="#" class="close">&times;</a></div>');
                                    $(document).foundation('alert', 'reflow');
                                    _this.find("[name='ID']").val(response.ID);
                                }
                            }
                        }
                    });

                    return false;
                }
            });

            var startField = $("input[name='UF_ACTIVE_START']"),
                endField = $("input[name='UF_ACTIVE_END']"),
                lastStart = startField.val(),
                lastEnd = endField.val(),
                canSend = true;

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

            function changeDate(item, isStart) {
                var pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
                var start = startField.val() !== "" ? new Date(startField.val().replace(pattern, '$3-$2-$1')) : "",
                    end = endField.val() !== "" ? new Date(endField.val().replace(pattern, '$3-$2-$1')) : "",
                    current = item.val() !== "" ? new Date(item.val().replace(pattern, '$3-$2-$1')) : "",
                    today = new Date(),
                    price = parseFloat("<?=$publicationCost?>"),
                    cash = parseFloat("<?=floatval(getContainer("User")["UF_CASH"]);?>"),
                    formError = $(".js-alert"),
                    diffCol = $(".js-active-diff"),
                    summCol = $(".js-active-summ"),
                    hasAlert = false;

                if (current !== "") {
                    current.setHours(0);
                    current.setMinutes(0);
                    current.setSeconds(0);
                    current.setMilliseconds(0);
                }
                if (start !== "") {
                    start.setHours(0);
                    start.setMinutes(0);
                    start.setSeconds(0);
                    start.setMilliseconds(0);
                }
                if (end !== "") {
                    end.setHours(0);
                    end.setMinutes(0);
                    end.setSeconds(0);
                    end.setMilliseconds(0);
                }

                today.setHours(0);
                today.setMinutes(0);
                today.setSeconds(0);
                today.setMilliseconds(0);

                if (current !== "" && current < today) {
                    if (!hasAlert)
                        alert("Укажите будущую дату.");
                    if (isStart) {
                        startField.val(lastStart);
                    }
                    else {
                        endField.val(lastEnd);
                    }
                    diffCol.html("");
                    summCol.html("");

                    changeDate(item, isStart);
                    return false;
                }

                if (end !== "" && start !== "" && end < start) {
                    if (!hasAlert)
                        alert("Дата окончания должна быть не раньше даты начала.");
                    if (isStart)
                        startField.val(lastStart);
                    else
                        endField.val(lastEnd);

                    diffCol.html("");
                    summCol.html("");

                    changeDate(item, isStart);
                    return false;
                }

                lastStart = startField.val();
                lastEnd = endField.val();

                if (start !== "" && end !== "" && price > 0) {
                    var millisecondsPerDay = 1000 * 60 * 60 * 24;
                    var millisBetween = end.getTime() - start.getTime();
                    var days = (millisBetween / millisecondsPerDay) + 1;

                    diffCol.html(days + (days == 1 ? " сутки" : " суток"));
                    summCol.html(number_format(days * price, 0, ".", " ") + " руб.");

                    if (days * price > cash) {
                        formError.find("[data-alert-text]").html("У Вас недостаточно средств на счете для активации данного предложения.<br>Доступно для списания " + cash + " руб.");
                        formError.removeClass("hide");
                        canSend = false;
                    }
                    else {
                        formError.addClass("hide");
                        canSend = true;
                    }
                }
            }

            startField.on("change", function () {
                changeDate($(this), true);
            });

            endField.on("change", function () {
                changeDate($(this), false);
            });

            $(".x-save-form").on("submit", function () {
                var url = "<?=$templateFolder?>/ajax.php",
                    _this = $(this),
                    data = _this.serialize();

                _this.find(".b-main-block__body").html("");

                if (startField.val() !== "" && endField.val() == "") {
                    alert("Не указана дата окончания действия предложения");
                    return false;
                }
                if (startField == "" && endField.val() !== "") {
                    alert("Не указана дата начала действия предложения");
                    return false;
                }
                if (!canSend) {
                    alert("У Вас недостаточно средств на счете для активации данного предложения.");
                    return false;
                }

                $.ajax({
                    url: url,
                    method: "post",
                    data: data,
                    dataType: "json",
                    success: function (response) {
                        if (response.ERRORS !== undefined) {
                            _this.find(".b-main-block__body").prepend('<div data-alert class="alert-box alert radius">' + response.ERRORS + '<a href="#" class="close">&times;</a></div>');
                            $(document).foundation('alert', 'reflow')
                            $("html, body").stop().animate({scrollTop: $(".b-main-block__body").offset().top - 20}, '500', 'swing');
                        }
                        else if (response.SUCCESS !== undefined && response.ID !== undefined) {
                            var redirect = "<?=$redirectUrl?>";
                            if (redirect !== "") {
                                window.location.href = "<?=$redirectUrl?>";
                            }
                            else {
                                _this.find(".b-main-block__body").prepend('<div data-alert class="alert-box success radius">' + response.SUCCESS + '<a href="#" class="close">&times;</a></div>');
                                $(document).foundation('alert', 'reflow');
                                _this.find("[name='ID']").val(response.ID);
                                $("html, body").stop().animate({scrollTop: $(".b-main-block__body").offset().top - 20}, '500', 'swing');
                            }
                        }
                    }
                });

                return false;
            });
        })();
    })
    ;
</script>