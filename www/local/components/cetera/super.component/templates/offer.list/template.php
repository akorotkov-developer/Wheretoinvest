<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
    <form action="" class="x-filter">
        <?
        $request = http_build_query($_REQUEST);
        $list = explode("&", $request);
        ?>
        <? foreach ($list as $item): ?>
            <? list($key, $val) = explode("=", $item); ?>
            <? if (in_array($key, Array("summ", "currency", "time"))) continue; ?>
            <input type="hidden" name="<?= urldecode($key) ?>" value="<?= $val ?>">
        <? endforeach; ?>

        <div class="js-header">
            <section class="b-sort row">
                <div class="b-sort__arr"></div>
                <div class="columns b-sort__all">на 1 год</div>
                <div class="b-sort__main">
                    <div class="column medium-7">
                        <span class="b-sort__label">Сумма:</span>
                        <input type="text" class="b-sort__inp" value="<?= $_REQUEST["summ"] ?>" name="summ">
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
                        <?
                        $timeList = Array(
                            "31" => "1 месяц",
                            "93" => "3 месяца",
                            "182" => "6 месяцев",
                            "365" => "1 год",
                            ">365" => "1 год и более",
                            "1095" => "3 года",
                            ">1095" => "3 года и более",
                            "3650" => "10 лет",
                            ">3650" => "10 лет и более",
                            "7300" => "20 лет",
                            ">7300" => "20 лет и более",
                        );
                        ?>
                        <select name="time">
                            <option value="">Срок не выбран</option>
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
                                        <a href="<?= \Cetera\Tools\Uri::GetCurPageParam("SORT[org]=" . ($_REQUEST["SORT"]["org"] == "A" ? "D" : "A"), Array("SORT")) ?>"
                                           class="b-offers__title <? if (!empty($_REQUEST["SORT"]["org"])): ?><? if ($_REQUEST["SORT"]["org"] == "D"): ?>b-offers__title_sort b-offers__title_sort_desc<? else: ?> b-offers__title_sort<? endif; ?><? endif; ?>">
                                            Организация
                                        </a>
                                    </div>
                                </div>
                                <div class="column medium-2 hide-for-small-only">
                                    <div class="b-offers__th">
                                        <a href="<?= \Cetera\Tools\Uri::GetCurPageParam("SORT[method]=" . ($_REQUEST["SORT"]["method"] == "A" ? "D" : "A"), Array("SORT")) ?>"
                                           class="b-offers__title <? if (!empty($_REQUEST["SORT"]["method"])): ?><? if ($_REQUEST["SORT"]["method"] == "D"): ?>b-offers__title_sort b-offers__title_sort_desc<? else: ?> b-offers__title_sort<? endif; ?><? endif; ?>">
                                            Способ вложения
                                        </a>

                                    </div>
                                </div>
                                <div
                                    class="column medium-3 small-4 medium-text-right small-text-center b-offers__bility">
                                    <div class="b-offers__th">
                                        <a href="<?= \Cetera\Tools\Uri::GetCurPageParam("SORT[percent]=" . (empty($_REQUEST["SORT"]["percent"]) || $_REQUEST["SORT"]["percent"] == "A" ? "D" : "A"), Array("SORT")) ?>"
                                           class="b-offers__title  <? if (empty($_REQUEST["SORT"]) || !empty($_REQUEST["SORT"]["percent"])): ?><? if (empty($_REQUEST["SORT"]) || $_REQUEST["SORT"]["percent"] == "D"): ?>b-offers__title_sort b-offers__title_sort_desc<? else: ?> b-offers__title_sort<? endif; ?><? endif; ?>">
                                            Доходность
                                        </a>

                                    </div>
                                </div>
                                <div class="column medium-2 small-4 medium-text-right small-text-center">
                                    <div class="b-offers__th">
                                        <a href="<?= \Cetera\Tools\Uri::GetCurPageParam("SORT[safety]=" . ($_REQUEST["SORT"]["safety"] == "A" ? "D" : "A"), Array("SORT")) ?>"
                                           class="b-offers__title <? if (!empty($_REQUEST["SORT"]["safety"])): ?><? if ($_REQUEST["SORT"]["safety"] == "D"): ?>b-offers__title_sort b-offers__title_sort_desc<? else: ?> b-offers__title_sort<? endif; ?><? endif; ?>">
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
                $(".x-filter select").on("change", function () {
                    $(this).closest(".x-filter").submit();
                });

                $(".x-filter input").on("blur", function () {
                    $(this).closest(".x-filter").submit();
                });
            });
        </script>
    </form>
<? if (count($arResult["ITEMS"])): ?>
    <? $inflation = floatval(\Ceteralabs\UserVars::GetVar('USER_VAR_INFLATION')["VALUE"]); ?>
    <section class="b-offers">
        <? if (!empty($inflation) && !empty($_REQUEST["SORT"]) && empty($_REQUEST["SORT"]["percent"])): ?>
            <div class="row b-offers__infl">
                <div class="columns medium-2 medium-offset-4 small-4 small-text-center medium-text-left">
                    <div class="b-offers__type b-offers__type_infl">Инфляция</div>
                </div>
                <div class="columns medium-3 small-3 text-right end b-offers__percent b-offers__bility">
                    <div class="b-offers__prof b-offers__prof_infl"><?= $inflation ?> <span>%</span></div>
                </div>
            </div>
        <? endif; ?>

        <div class="b-offers__list">
            <? if (!empty($_REQUEST["ajax"])) {
                $APPLICATION->RestartBuffer();
            } ?>
            <? $showInflation = true; ?>
            <? foreach ($arResult["ITEMS"] as $arItem): ?>
                <?
                $user = $arItem["USER"];
                $offer = $arItem["OFFER"];
                ?>
                <? if (!empty($inflation) && (empty($_REQUEST["SORT"]) || !empty($_REQUEST["SORT"]["percent"])) && $showInflation): ?>
                    <? if (((empty($_REQUEST["SORT"]) || $_REQUEST["SORT"]["percent"] == "D") && $inflation > floatval($arItem["UF_PERCENT"])) || ($_REQUEST["SORT"]["percent"] == "A" && $inflation < floatval($arItem["UF_PERCENT"]))): ?>
                        <div class="row b-offers__infl">
                            <div class="columns medium-2 medium-offset-4 small-4 small-text-center medium-text-left">
                                <div class="b-offers__type b-offers__type_infl">Инфляция</div>
                            </div>
                            <div class="columns medium-3 small-3 text-right end b-offers__percent b-offers__bility">
                                <div class="b-offers__prof b-offers__prof_infl"><?= $inflation ?> <span>%</span></div>
                            </div>
                        </div>
                        <? $showInflation = false; ?>
                    <? endif; ?>
                <? endif; ?>
                <div class="b-offers__item row">
                    <div class="column medium-4  small-4 b-offers__firsttd">
                        <div class="b-offers__logo">
                            <? if (!empty($user["WORK_LOGO"])): ?>
                                <? $file = CFile::ResizeImageGet($user["WORK_LOGO"], array('width' => 40, 'height' => 40), BX_RESIZE_IMAGE_PROPORTIONAL); ?>
                                <img src="<?= $file["src"]; ?>">
                            <? endif; ?>
                        </div>
                        <div class="b-offers__name">
                            <?= $arItem["UF_ORG"] ?>
                        </div>
                        <div class="b-offers__arrows"></div>
                    </div>

                    <div class="column medium-2 hide-for-small-only">
                        <div class="b-offers__type">
                            <?= $arItem["UF_METHOD"]; ?>
                        </div>
                    </div>
                    <div class="column small-3 medium-3  text-right b-offers__profit b-offers__bility">
                        <div class="b-offers__prof"><?= floatval($arItem["UF_PERCENT"]); ?> <span>%</span></div>
                    </div>
                    <div class="column small-5 medium-2  text-right">
                        <div class="b-offers__prof"><?= $arItem["UF_SAFETY"] ?>
                            <span>из <?= $arResult["USER_COUNT"] ?></span></div>
                    </div>
                    <div class="column hide-for-small-only medium-1 text-left  ">
                        <a href="#"
                           class="b-offers__stars js-favorite-add<? if ($offer["UF_FAVORITE"]): ?> b-offers__stars_active<? endif; ?>"
                           data-id="<?= $offer["ID"] ?>"></a>
                    </div>

                    <div class="column small-12 b-offers__hidden">
                        <div class="row b-offers__more">
                            <div class="column medium-1 show-for-medium-up">
                                &nbsp;
                            </div>
                            <div class="column medium-11">
                                <div class="row b-offers__more-item">
                                    <div class="column medium-3 small-6">
                                        <div class="b-offers__label">Наименование</div>
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
                                            <div class="b-offers__rest">
                                                <?= !empty($user["RATING"]) ? $user["RATING"] : "-" ?>
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
                                                <?= !empty($user["UF_STATE_PARTICIP"]) ? "Да" : "-" ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row b-offers__more-item">
                                    <div class="column medium-3 small-6">
                                        <div class="b-offers__label b-offers__label_bot">Участие в системе страхования
                                            вкладов
                                        </div>

                                    </div>
                                    <div class="column medium-9 small-6 b-offers__nopadding">
                                        <div class="b-offers__res2">
                                            <div class="b-offers__img">
                                                <?= !empty($user["UF_BANK_PARTICIP"]) ? '<img src="' . WIC_TEMPLATE_PATH . '/images/asb.jpg" alt="">' : '-' ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row b-offers__more-item">
                                    <div class="column medium-3 small-6">
                                        <div class="b-offers__label">Капитал / Активы</div>

                                    </div>
                                    <div class="column medium-9 small-6 b-offers__nopadding">
                                        <div class="b-offers__res2">
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
                                <div class="row b-offers__more-item">
                                    <div class="column medium-3 small-6">
                                        <div class="b-offers__label">Капитал</div>

                                    </div>
                                    <div class="column medium-9 small-6 b-offers__nopadding">
                                        <div class="b-offers__res2">
                                            <? if (!empty($user["UF_CAPITAL"])): ?>
                                                <div class="b-offers__prof">
                                                    <?= $user["UF_CAPITAL"] ?>
                                                    <span>тыс. рублей</span>
                                                </div>
                                            <? else: ?>
                                                -
                                            <? endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row b-offers__more-item">
                                    <div class="column medium-3 small-6">
                                        <div class="b-offers__label">Активы</div>
                                    </div>
                                    <div class="column medium-9 small-6 b-offers__nopadding">
                                        <div class="b-offers__res2">
                                            <? if (!empty($user["UF_ASSETS"])): ?>
                                                <div class="b-offers__prof">
                                                    <?= $user["UF_ASSETS"] ?>
                                                    <span>тыс. рублей</span>
                                                </div>
                                            <? else: ?>
                                                -
                                            <? endif; ?>
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
                                                <?= $user["FULL_WORK_COMPANY"] ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row b-offers__more-item">
                                    <div class="column medium-3 small-6">
                                        <div class="b-offers__label">Обновлено</div>

                                    </div>
                                    <div class="column medium-9 small-6 b-offers__nopadding">
                                        <div class="b-offers__res2">
                                            <div class="b-offers__rest">
                                                <?= CIBlockFormatProperties::DateFormat("d M Y в H:i", strtotime($user["TIMESTAMP_X"])); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <? if ($USER->IsAuthorized()): ?>
                                <div class="columns show-for-small-only b-offers__best">
                                    <div class="b-offers__liked">Избранное</div>
                                    <a href="#"
                                       class="b-offers__stars js-favorite-add<? if ($offer["UF_FAVORITE"]): ?> b-offers__stars_active<? endif; ?>"
                                       data-id="<?= $offer["ID"] ?>"></a>
                                </div>
                            <? endif; ?>
                            <? if (!empty($user["UF_SITE"])): ?>
                                <? if (!preg_match("#^(http|//)#is", $user["UF_SITE"])) $user["UF_SITE"] = "//" . $user["UF_SITE"] ?>
                                <div class="column medium-6 medium-offset-4 b-offers__go">
                                    <a href="<?= $user["UF_SITE"] ?>" class="b-offers__link" target="_blank">Перейти на
                                        сайт</a>
                                </div>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            <? endforeach; ?>

            <script>
                window.paging = {
                    pageNum: parseInt("<?=$arResult["NAV_PAGE_NUM"]?>"),
                    pageCount: parseInt("<?=$arResult["NAV_PAGE_COUNT"]?>")
                };

                $(function () {
                    $('.b-offers__item').unbind().click(function () {
                        $(this).toggleClass('active');
                    });

                    $(".b-offers__link").unbind().on("click", function (event) {
                        window.open(this.href, '_blank');
                        event.preventDefault();
                        return false;
                    });

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
                                            $(".b-offers").html('<h2><p><font class="errortext">Предложения отсутствуют.</font></p></h2>');
                                        }
                                    }
                                }
                            }
                        });

                        event.preventDefault();
                        return false;
                    });
                });
            </script>

            <? if (!empty($_REQUEST["ajax"])) {
                die();
            } ?>
        </div>

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
                                $(".b-offers__list").append(response)

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
    </section>
<? else: ?>
    <div class="row">
        <div class="column small-12">
            <h2><?= ShowError("Предложения отсутствуют.") ?></h2>
        </div>
    </div>
<? endif; ?>