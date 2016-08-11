<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
</div>
</div>
<section class="b-footer">
    <div class="row">
        <div class="column medium-3 medium-push-9">
            <form class="b-footer__form">
                <div class="b-footer__smalltext">Подписаться на рассылку</div>
                <div class="row collapse ">
                    <div class="small-10 columns">
                        <input type="text" placeholder="youremail@yandex.ru"/>
                    </div>
                    <div class="small-2 columns b-footer__bug end">
                        <input type="submit" class="b-footer__postfix">
                    </div>
                </div>
            </form>
            <div class="b-footer__icons">
                <a href="#" class="b-footer__icon">
                    <img src="<?= WIC_TEMPLATE_PATH ?>/images/icon.png">
                </a>
                <a href="#" class="b-footer__icon">
                    <img src="<?= WIC_TEMPLATE_PATH ?>/images/tw.png">
                </a>
                <a href="#" class="b-footer__icon">
                    <img src="<?= WIC_TEMPLATE_PATH ?>/images/vk.png">
                </a>
            </div>
        </div>
        <div class="column medium-4 medium-push-2">
            <ul class="b-footer__menu">
                <li>
                    <a href="#" class="b-footer__link">Карта сайта</a>
                </li>
                <li>
                    <a href="#" class="b-footer__link">Вакансии</a>
                </li>
                <li>
                    <a href="#" class="b-footer__link">Контакты</a>
                </li>
                <li>
                    <a href="#" class="b-footer__link">Отзывы и рекомендации</a>
                </li>
            </ul>
        </div>
        <div class="column medium-5 medium-pull-7">
            <ul class="b-footer__menu">
                <li>
                    <a href="#" class="b-footer__link">Методика определения надежности</a>
                </li>
                <li>
                    <a href="#" class="b-footer__link">Банкам и организациям</a>
                </li>

            </ul>

            <div class="b-footer__copyr">
                © 2016 Куда вложить<br>
                Создание сайта - <a href="//cetera.ru" class="b-footer__cetera">Cetera Labs</a>
            </div>
        </div>
    </div>
</section>

<?php require($_SERVER['DOCUMENT_ROOT'] . WIC_TEMPLATE_PATH . '/include_areas/javascript.php'); ?>

<? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array(
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "",
        "PATH" => "/include/metrics.php",
        "AREA_FILE_RECURSIVE" => "Y",
        "EDIT_TEMPLATE" => "standard.php"
    )
); ?>

</body>
</html>
