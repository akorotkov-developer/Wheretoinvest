<? define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
?>

<? $APPLICATION->IncludeComponent(
    "cetera:user.cabinet",
    ".default",
    array(
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "COMPONENT_TEMPLATE" => ".default",
        "FILTER_NAME" => "arrFilter",
        "NAME_TEMPLATE" => "#LAST_NAME# #NAME# #SECOND_NAME#",
        "PAGER_TEMPLATE" => "",
        "PAGE_COUNT" => "10",
        "PROFILE" => "",
        "SEF_FOLDER" => "/cabinet/",
        "SEF_MODE" => "Y",
        "SET_STATUS_404" => "Y",
        "SEF_URL_TEMPLATES" => array(
            "index" => "",
            "info" => "info/",
            "method" => "method/",
            "region" => "region/",
        )
    ),
    false
); ?>

<? if (strpos($APPLICATION->GetCurPage(), "/cabinet/offers/") !== false): ?>
    <script type="text/javascript">
        $(function () {
            var blockPos = 0;

            function scrMain() {
                var block = $('.js-header-main');

                setTimeout(function () {
                    if (block.length) {
                        var y = $(document).scrollTop();
                        var blockHeight = block.outerHeight();
                        if (parseInt(block.offset().top) !== 0 && !block.hasClass("fixed")) {
                            blockPos = block.offset().top;
                        }

                        if (blockPos > 0 && y > blockPos) {
                            block.addClass("fixed").css({
                                "margin": "0 auto",
                                "background": "#FFFFFF",
                                "position": "fixed",
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
                }, 10);
            }

            scrMain();

            window.onresize = function () {
                scrMain();
            };

            $(document).scroll(function () {
                scrMain();
            });
        });
    </script>
<? endif; ?>


<script type="text/javascript">
        $(document).ready(function()
        {
            var count =70 - $(".contentbox").val().length;
            $('#count').html("Символов осталось - " + count);
            $(".contentbox").keyup(function()
            {
                var box=$(this).val();
                var main = box.length *100;
                var value= (main / 70);
                var count= 70 - box.length;

                if(box.length <= 70)
                {
                    $('#count').html("Символов осталось - " + count);
                    $('#bar').animate(
                        {
                            "width": value+'%',
                        }, 1);
                }
                return false;
            });

        });
</script>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>