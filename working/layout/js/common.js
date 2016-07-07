(function ($) {
    $(function () {
        "use strict";


        $(document).foundation();

        // your code goes here

        (function () {
            $('.js-show-menu').on('click', function () {
                $('.js-menu').toggle(500);
                $('.b-header__place, .b-header__firstline').toggleClass('js-toggle');
            });
        })();


    });
})(jQuery);