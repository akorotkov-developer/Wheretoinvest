<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$size = $APPLICATION->get_cookie("SIZE_CLOSE");
?>
<? if (empty($size)): ?>
    <section class="row b-sizeinfo ">
        <div class="column medium-8">
            <div class="b-sizeinfo__title">
                Доходность или надежность?
                <div class="b-sizeinfo__close js-sizeclose"></div>
            </div>
            <div class="b-sizeinfo__text">
                Выбирайте приемлемое соотношение доходности и риска, <br class="show-for-large-up">применяя сортировку по
                доходности, либо по
                надежности. <br class="show-for-large-up">Сравнивайте с инфляцией.
            </div>
        </div>
        <div class="column medium-3 b-sizeinfo__scales small-only-text-center">
            <img src="<?= WIC_TEMPLATE_PATH ?>/images/scales.png">
        </div>
        <div class="column medium-1 medium-text-right show-for-medium-up">
            <div class="b-sizeinfo__close js-sizeclose">Закрыть</div>
        </div>
    </section>
    <script type="text/javascript">
        $(function () {
            //begin of b-sizeinfo close
            (function () {
                $('.js-sizeclose').click(function () {
                    var _this = $(this);
                    $.ajax({
                        url: "/local/ajax/size_close.php",
                        data: {
                            setClose: "Y"
                        },
                        success: function () {
                            _this.closest('.b-sizeinfo').hide();
                        }
                    });
                });
            })();
            //end of b-sizeinfo close
        });
    </script>
<? endif; ?>