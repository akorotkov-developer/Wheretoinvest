(function ($) {
    $(function () {
        "use strict";


        $(document).foundation();

        // your code goes here

        (function () {
            $('.js-show-menu').on('click', function () {
                $('.js-menu').toggle(500);
                $('.b-header__place, .b-header__showMenu,.b-header__firstline').toggleClass('js-toggle');
            });
            //begin of b-offers__item show/hide
            $('.b-offers__arrows').click(function () {
                $(this).closest('.b-offers__item').toggleClass('active');
            });
            //end of b-offers__item show/hide
            //begin of sum menu show/hide
            function sortText() {
                var sum = $('.b-sort__main .b-sort__inp').val() || 0;
                var currency = $('.b-sort__main select').eq(0).val();
                var time = $('.b-sort__main select').eq(1).val();

                $('.b-sort__all').text(sum + " " + currency + " на " + time);
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
            var block = $('.b-sort');
            var header = $('.b-offers__header');
            var blockHei = block.height();
            var compensation = blockHei + header.height();
            if (block.is('section') && header.is('div')) {

                var blockPos = block.offset().top;

                function scr() {
                    block.removeClass('fixed');
                    blockPos = block.offset().top;
                    blockHei = block.height();
                    var y = $(document).scrollTop();
                    if (y > blockPos) {
                        block.addClass('fixed');
                        $('.b-offers').attr('style', "margin-top:" + compensation + 'px;');
                        header.addClass('fixed').attr('style', 'top:' + (blockHei + 12) + 'px;');
                    }
                    else {
                        $('.b-offers').attr('style', '');
                        block.removeClass('fixed');
                        header.removeClass('fixed');
                    }
                }

                window.onresize = function () {
                    scr();
                }
                $('.b-sort').resize(function (e) {
                    scr();
                });
                $(document).scroll(function () {
                    scr();
                })
            }

        })();

    });
})(jQuery);