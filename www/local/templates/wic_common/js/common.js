(function ($) {
    $(function () {
        "use strict";
        $(document).foundation({
            tooltip: {
                tip_template: function (selector, content) {
                    if (content == "")
                        return "";
                    else
                        return '<span data-selector="' + selector + '" class="'
                            + Foundation.libs.tooltip.settings.tooltip_class.substring(1)
                            + '">' + content + '</span>';
                }
            }
        });

        $(document).on('open.fndtn.reveal', '[data-reveal]', function () {
            $("body").css({"overflow-y": "scroll"});
        });

        $(document).on('close.fndtn.reveal', '[data-reveal]', function () {
            var _this = this;
            $(this).find(".alert-box .close").trigger("click", function () {
                $("[data-alert]").foundation("alert", "reflow");
            });
            $("body").css({"overflow-y": "auto"});
        });


        function scrollToAlert() {
            var alert = $("[data-alert]:visible");
            if (alert.length) {
                $('html, body').animate({
                    scrollTop: alert.eq(0).offset().top - 80
                }, 500);
            }
        }

        scrollToAlert();

        //begin of footer2bottom
        var $footer = $('.b-footer');
        var marT = +($footer.css('margin-top').slice(0, -2));

        function footer2bottom() {
            $footer.css('margin-top', 0 + 'px');
            if ($('body').height() < $(window).height()) { // если высота body меньше, чем высота окна
                var fmargin = $(document).height() - $('body').height() - 2; // вычисляем верхний оступ

                fmargin = fmargin > 60 ? fmargin : 60;
                $footer.css('margin-top', fmargin + 'px'); // применяем верхний отступ
            }
            else {
                $footer.css('margin-top', marT + 'px');
            }
        }

        // обработка события после загрузки дерева DOM
        (function () {
            if ($('.b-footer')) {
                $(document).ready(function () {
                    footer2bottom();
                });
                $(window).on('resize', function () {
                    footer2bottom();
                });
                $(document).on('close.fndtn.alert', function (event) {
                    footer2bottom();
                });
            }
        })();
        //end of footer2bottom

        (function () {
            $('.js-show-menu').on('click', function () {
                $('.js-menu').toggle(500);
                $('.b-header__place, .b-header__showMenu,.b-header__firstline').toggleClass('js-toggle');
            });

            //begin of select my region
            (function () {
                if ($('.modal_js-my-region')) {
                    $('.modal_js-my-region label').click(function () {
                        var txt = $(this).text();
                        $('.content__js-my-region').val(txt);
                        $(this).prev('input').prop('disabled', true);
                        $('#myModal').foundation('reveal', 'close');
                    });
                }

                var choose = $('.modal__js-choose-all');
                if (choose) {
                    choose.click(function () {
                        $(this).closest('.reveal-modal').find('input').prop('checked', true);
                    });
                }
                var disrobe = $('.modal__js-disrobe-all');
                if (disrobe) {
                    disrobe.click(function () {
                        $(this).closest('.reveal-modal').find('input').prop('checked', false);
                    });
                }

            })();
            //end of select my region

            //begin of hiding input in the account settings
            $('.content__change').click(function () {
                $(this).next('.content__input').slideToggle();
            });
            //end of hiding input in the account settings
            //begin of filter show/hide function
            $('.filter__arr').click(function () {
                $('.filter__main').slideToggle();
                $(this).toggleClass('js-toggle');
            });
            //end of filter show/hide function
        })();

        (function () {
            function number_format(str) {
                return str.replace(/(\s)+/g, '').replace(/(\d{1,3})(?=(?:\d{3})+$)/g, '$1 ');
            }

            $('.b-sort__inp').keyup(function (event) {
                $(this).val(number_format($(this).val()));
            });
            $('.region__js-sum').keyup(function (event) {
                $(this).val(number_format($(this).val()));
            });
        })();

        (function () {
            window.onresize = function () {
                mainMenuFix();
            };

            function mainMenuFix() {
                if ($(window).width() > 623) {
                    $('.b-header__menuWrap').attr('style', '');
                    $('.b-header__showMenu').removeClass('js-toggle');
                }
            }
        })();

        (function () {
            var blockPos = 0;

            function scr() {
                var block = $('.js-header');

                if (block.length) {
                    var y = $(document).scrollTop();
                    var blockHeight = block.outerHeight();
                    if (block.offset().top !== 0 && !block.hasClass("fixed")) {
                        blockPos = block.offset().top;
                    }

                    if (blockPos > 0 && y > blockPos) {
                        block.addClass("fixed").css({
                            "position": "fixed",
                            "top": 0,
                            "margin": "0 auto",
                            "width": "100%",
                            "background": "#FFFFFF",
                            "z-index": 100
                        });
                    }
                    else {
                        block.removeClass("fixed").css({
                            "position": "relative"
                        });
                    }
                    block.parent().css("height", blockHeight);
                }
            }

            window.onresize = function () {
                scr();
            };
            $('.b-sort').resize(function (e) {
                scr();
            });
            $(document).scroll(function () {
                scr();
            });

            //begin of sum menu show/hide
            function sortText() {
                var sum = $('.b-sort__main .b-sort__inp').val() || 0;
                var currency_val = $('.b-sort__main select[name="currency"]').val();
                var currency = "";
                var time_val = $('.b-sort__main select[name="time"]').val();
                var time = "";

                $(".b-sort__main select[name='currency']").find("option").each(function () {
                    if ($(this).attr("value") == currency_val)
                        currency = $(this).text();
                });

                $(".b-sort__main select[name='time']").find("option").each(function () {
                    if ($(this).attr("value") == time_val)
                        time = $(this).text();
                });

                var text = sum;
                switch (currency) {
                    case "В рублях":
                        currency = "руб.";
                        break;
                    case "В долларах":
                        currency = "долл. США";
                        break;
                    case "В евро":
                        currency = "евро";
                        break;
                    default:
                        break;
                }

                text += " " + currency;

                if (time_val !== "") {
                    text += " на " + time;
                }

                $('.b-sort__all').text(text);

                var visible = $('.b-sort__all').hasClass("js-toggle") ? 0 : 1;
                $.ajax({
                    url: "/local/ajax/sortVisible.php",
                    data: {
                        setVisible: visible
                    },
                    method: "post"
                });

                scr();
            }

            $('.b-sort__arr').click(function () {
                $('.b-sort__main').slideToggle();
                $('.b-sort__arr,.b-sort__all').toggleClass('js-toggle');

                sortText();
            });

            $(".b-sort__all").click(function () {
                $('.b-sort__main').slideDown();
                $(this).removeClass('js-toggle');
                $('.b-sort__arr').removeClass('js-toggle');
                sortText();
            });
            //end of sum menu show/hide
        })();

        if ($.fn.mask) {
            $(".js-phone").mask('+7 (999) 999-99-99');
            $(".js-date").mask('99.99.9999');
        }
    });
})
(jQuery);