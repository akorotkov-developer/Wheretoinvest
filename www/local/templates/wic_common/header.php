<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

IncludeTemplateLangFile(__FILE__);
?><!DOCTYPE html>
    <!--[if lt IE 7]><html class="no-js ie lt-ie9 lt-ie8 lt-ie7" lang="ru"><![endif]-->
    <!--[if IE 7]><html class="no-js ie ie7 lt-ie9 lt-ie8" lang="ru"><![endif]-->
    <!--[if IE 8]><html class="no-js ie ie8 lt-ie9" lang="ru"><![endif]-->
    <!--[if IE 9]><html class="no-js ie ie9" lang="ru"><![endif]-->
    <!--[if !IE]><!--> <html class="no-js" lang="ru"><!--<![endif]-->
    <head>


        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title><?php $APPLICATION->ShowTitle()?></title>


        <link rel="icon" href="<?=WIC_TEMPLATE_PATH;?>/images/favicon-96.png" type="image/x-icon">
        <link rel="shortcut icon" href="<?=WIC_TEMPLATE_PATH;?>/images/favicon-96.png" type="image/x-icon">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="copyright" content="Создание сайтов - Cetera Labs, www.cetera.ru, 2015" />
        <meta name="author" content="Cetera Labs, http://www.cetera.ru/, создание сайтов, поддержка сайтов, продвижение сайтов" />

        <?php
        $APPLICATION->AddHeadScript(WIC_TEMPLATE_PATH . "/js/vendor/modernizr-2.6.2.min.js");

        $APPLICATION->SetAdditionalCSS(WIC_TEMPLATE_PATH . "/css/style.css");

        $APPLICATION->ShowHead();
        ?>

    </head>
    <body>
        <?php $APPLICATION->ShowPanel();?>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter36400230 = new Ya.Metrika({
                        id:36400230,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true,
                        webvisor:true
                    });
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/36400230" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- Yandex.Metrika counter -->

    <!-- Google analytics -->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-75726146-1', 'auto');
        ga('send', 'pageview');

    </script>
    <!-- Google analytics -->
        <div class="menu">
            <div class="contain-to-grid row">
                <nav class="top-bar" data-topbar role="navigation">
                    <ul class="title-area">
                        <li class="name">
                            <span><a href="/"><img class="logo" src="<?=WIC_TEMPLATE_PATH;?>/images/logo.png"></a></span>
                        </li>
                        <li class="toggle-topbar menu-icon"><a href="#"><span></span></a></li>
                    </ul>
                    <div class="top-bar-section">
                        <ul class="left">
                            <ul class="left">
                                <li class="active"><a href="/about/">О проекте</a></li>
                                <li class="active"><a href="/ideas/">Виды вложений</a></li>
                                <li class="active"><a href="/banks/">Банки</a></li>
                                <li class="active"><a href="/news/">Новости</a></li>
                                <li class="active"><a href="/contacts/">Контакты</a></li>
                                <li class="active"><a href="/personal/">Личный кабинет</a></li>
                            </ul>
                        </ul>

                        <ul class="right">
                            <li>
                                <form id="x-top-search-form" class="hide" action="/search">
                                    <input name="query" placeholder="Поиск по сайту" />
                                </form>
                            </li>
                            <li><a id="x-top-search-btn" href="#"><span><img src="<?=WIC_TEMPLATE_PATH;?>/images/ico/search.png"></span></a></li>
                        </ul>

                    </div>
                </nav>
            </div>
        </div>