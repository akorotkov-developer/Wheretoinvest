<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (count($arResult["METHODS"])): ?>
    <div class="row">
        <div class="column small-12">
            <div class="b-form__title-desc b-form__title-desc_method">
                Выбирайте подходящие Вам показатели надежности путем проставления галочки.<br>
                Изменяйте порядок применения выбранных показателей путем перетаскивания строк.
            </div>
            <br><br><br>

            <form action="" class="b-form x-save-form" enctype="multipart/form-data" method="post">
                <input type="hidden" name="sessid" value="<?= bitrix_sessid(); ?>">
                <input type="hidden" name="ajax" value="Y">
                <input type="hidden" name="action" value="changeMethods">

                <div class="b-main-block__body"></div>
                <ul class="sortable" id="sortable">
                    <? foreach ($arResult["METHODS"] as $arItem): ?>
                        <li class="content__wrp content__wrp_sortable<? if (!$arItem["ACTIVE"]): ?> ui-state-disabled<? endif; ?>">
                            <input type="hidden" class="js-sortable-sort" name="method[<?= $arItem["ID"]; ?>][sort]"
                                   value="<?= $arItem["SORT"]; ?>">
                            <input type="checkbox" name="method[<?= $arItem["ID"]; ?>][active]"
                                   id="active_<?= $arItem["ID"]; ?>"
                                   class="state__checkbox js-sortable-active"<? if ($arItem["ACTIVE"]): ?> checked<? endif; ?>>
                            <label for="active_<?= $arItem["ID"]; ?>" class="state__chck"><span
                                    class="met_rating-sort"><?= $arItem["SORT"] ?></span>-й <span
                                    class="js-sortable-name"><?= $arItem["NAME"] ?></span>
                            </label>
                        </li>
                    <? endforeach; ?>
                </ul>
                <div class="row">
                    <div class="columns">
                        <button type="submit" class="content__submit">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        $(function () {
            var sortContainer = $("#sortable");

            if ($.fn.sortable) {
                sortContainer.sortable({
                    placeholder: "sortable-highlight",
                    items: "li:not(.ui-state-disabled)",
                    cancel: ".ui-state-disabled",
                    stop: function (event, ui) {
                        setSort();
                    }
                });

                function setSort() {
                    var i = 1;
                    sortContainer.find(".content__wrp").each(function () {
                        $(this).find(".met_rating-sort").text(i);
                        $(this).find(".js-sortable-sort").val(i);
                        i++;
                    });
                }

                sortContainer.find(".js-sortable-active").change(function () {
                    var checked = $(this).is(":checked"),
                        parent = $(this).closest(".content__wrp"),
                        itemBefore = sortContainer.find(".ui-state-disabled").eq(0);

                    if (!checked) {
                        parent.addClass("ui-state-disabled");
                        if (itemBefore.length)
                            parent.insertBefore(itemBefore);
                        else
                            sortContainer.append(parent);
                    }
                    else {
                        parent.removeClass("ui-state-disabled");
                        if (itemBefore.length)
                            parent.insertBefore(itemBefore);
                    }

                    sortContainer.sortable("refreshPositions");
                    setSort();
                });
            }

            $(".x-save-form").on("submit", function () {
                var hasActive = false,
                    data = $(this).serialize(),
                    _this = $(this);
                _this.find(".b-form__error").detach();
                _this.find(".alert-box").detach();

                sortContainer.find(".js-sortable-active").each(function () {
                    if ($(this).is(":checked"))
                        hasActive = true;
                });

                if (!hasActive) {
                    _this.find(".b-main-block__body").html('<div data-alert class="alert-box alert radius">Должен быть выбран хотя бы один параметр.<a href="#" class="close">&times;</a></div>');
                    $(document).foundation('alert', 'reflow');
                    return false;
                }

                $.ajax({
                    url: "/local/ajax/editUserMethods.php",
                    data: data,
                    method: "POST",
                    dataType: "json",
                    success: function (response) {
                        if (response.ERROR !== undefined) {
                            _this.find(".b-main-block__body").html('<div data-alert class="alert-box alert radius">' + response.ERROR + '<a href="#" class="close">&times;</a></div>');
                            $(document).foundation('alert', 'reflow');
                        }
                        else if (response.SUCCESS !== undefined) {
                            _this.find(".b-main-block__body").html('<div data-alert class="alert-box success radius">' + response.SUCCESS + '<a href="#" class="close">&times;</a></div>');
                            $(document).foundation('alert', 'reflow');

                            $(".js-method-list").html("");
                            sortContainer.find(".content__wrp").each(function () {
                                var active = $(this).find(".js-sortable-active").is(":checked"),
                                    pos = $(this).find(".js-sortable-sort").val(),
                                    name = $(this).find(".js-sortable-name").text();

                                var item = '<div class="met__rating' + (!active ? ' met__rating_no' : '') + '"><span class="met_rating-sort">' + pos + '</span>-й ' + name + '</div>';
                                $(".js-method-list").append(item);
                            });
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
<? endif; ?>


