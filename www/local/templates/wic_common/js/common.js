(function ($) {
    $(function () {
        "use strict";
        $(document).foundation();

        $(document).on('close.fndtn.reveal', '[data-reveal]', function () {
            $(this).find(".alert-box .close").trigger("click");
            $(this).find(".alert-box").foundation("alert", "reflow");
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
            //begin of b-offers__item show/hide
            $('.b-offers__arrows').click(function () {
                $(this).closest('.b-offers__item').toggleClass('active');
            });
            //end of b-offers__item show/hide

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

            var block = $('.b-sort');
            var header = $('.b-offers__header');
            var blockHei = block.height();
            var compensation = blockHei + header.height();
            if (block.is('section')) {
                var blockPos = block.offset().top;
            }


            function scr() {
                block.removeClass('fixed');
                if (block.is('section')) {
                    blockPos = block.offset().top;
                }
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
            };
            $('.b-sort').resize(function (e) {
                scr();
            });
            $(document).scroll(function () {
                scr();
            });


        })();
        //begin of number of checked regions
        (function () {
            $(document).on('close.fndtn.reveal', '[data-reveal]', function () {
                var num = $('.reveal-modal').find('input:checked').length;
                $('.course__title .course__red').text(num);
            });
        })();
        //end of number of checked regions

        //begin of graph tqble edit
        (function () {
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


            //begin of add of rows and columns
            function panelActive() {
                return '#' + $('.content.active').attr('id');
            }

            function inputCreate() {

                var tx = $(this).val().replace(",", ".");
                var txt = (+tx).toFixed(2);
                if (txt && !(isNaN(txt))) {
                    $(this).parent().removeClass('graph__td_js').html(txt + '%');
                }
            }

            $('.graph__cr:not(.graph__cr_td)').on('click', deleteCol);

            $('.graph__cr_td').click(function () {

                $(this).parent().parent().detach();
            });

            var graphTd = '<div class="graph__td graph__td_js"><input type="text" class="region__js-percent"></div>';

            $('.region__js-col').click(function () {
                var firD = $('.region__js-fir').val();
                var secD = $('.region__js-sec').val();
                if (firD) {
                    var idA = panelActive();
                    var numCol = $(idA + ' .graph__th').length;
                    if (numCol <= 7) {
                        var numRow = $(idA + ' .graph__row').length;
                        if (secD) {
                            var headText = '<div class="graph__th graph__th_reg"><a class="graph__cr"></a>' + firD + ' –  ' + secD + '<br> дней</div>';
                        } else {
                            var headText = '<div class="graph__th graph__th_reg"><a class="graph__cr"></a>' + firD + '<br>дней</div>';
                        }

                        $(idA + " .graph__head").append(headText);
                        $('.graph__cr:not(.graph__cr_td)').off('click', deleteCol);
                        $('.graph__cr:not(.graph__cr_td)').on('click', deleteCol);

                        $(idA + ' .graph__row').each(function () {
                            $(this).append(graphTd);

                        });
                        $('.region__js-percent').off('blur', inputCreate);
                        $('.region__js-percent').on('blur', inputCreate);
                    }
                }
                respoTd();
            });


            $('.region__js-row').click(function () {
                var firD = $('.region__js-sum').val();
                if (firD) {
                    var idA = panelActive();
                    var numCol = $(idA + ' .graph__th').length;
                    var rowText = '<div class="graph__row"><div class="graph__td graph__td_reg">от ' + firD + '<a class="graph__cr graph__cr_td"></a></div>';
                    for (var i = 1; i < numCol; i++) {
                        rowText += graphTd;
                    }
                    $(idA + " .graph__body").append(rowText);
                    $('.graph__cr_td').off('click', deleteRow);
                    $('.graph__cr_td').on('click', deleteRow);
                    $('.region__js-percent').off('blur', inputCreate);
                    $('.region__js-percent').on('blur', inputCreate);

                }
                respoTd();
            });

            //end of add of rows and columns

            //begin of graph table hide/show
            function respoTd() {
                var beginNum = 4;
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
                }
            }

            window.onresize = function () {
                respoTd();
            };

            //end of graph table hide/show

        })();
        //end of graph tqble edit

        if ($.fn.mask) {
            $(".js-phone").mask('+7(999)999-99-99');
            $(".js-date").mask('99.99.9999');
        }
    });
})(jQuery);