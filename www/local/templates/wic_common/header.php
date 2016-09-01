<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile(__FILE__);
CJSCore::Init(array("fx"));
?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js ie lt-ie9 lt-ie8 lt-ie7" lang="ru"><![endif]-->
<!--[if IE 7]>
<html class="no-js ie ie7 lt-ie9 lt-ie8" lang="ru"><![endif]-->
<!--[if IE 8]>
<html class="no-js ie ie8 lt-ie9" lang="ru"><![endif]-->
<!--[if IE 9]>
<html class="no-js ie ie9" lang="ru"><![endif]-->
<!--[if !IE]><!-->
<html class="no-js" lang="ru"><!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php $APPLICATION->ShowTitle() ?></title>
    <link rel="icon" href="/uploads/favicon-96.png" type="image/x-icon">
    <link rel="shortcut icon" href="/uploads/favicon-96.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="/uploads/apple-touch-icon-60x60.png">
    <? /*?>
    <link rel="apple-touch-icon" href="/uploads/apple-touch-icon-29x29.png">
    <link rel="apple-touch-icon" href="/uploads/apple-touch-icon-40x40.png">
    <link rel="apple-touch-icon" href="/uploads/apple-touch-icon-50x50.png">
    <link rel="apple-touch-icon" href="/uploads/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" href="/uploads/apple-touch-icon-58x58.png">
    <link rel="apple-touch-icon" href="/uploads/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" href="/uploads/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" href="/uploads/apple-touch-icon-80x80.png">
    <link rel="apple-touch-icon" href="/uploads/apple-touch-icon-87x87.png">
    <link rel="apple-touch-icon" href="/uploads/apple-touch-icon-100x100.png">
    <link rel="apple-touch-icon" href="/uploads/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" href="/uploads/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" href="/uploads/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" href="/uploads/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" href="/uploads/apple-touch-icon-167x167.png">
    <link rel="apple-touch-icon" href="/uploads/apple-touch-icon-180x180.png">
    <link rel="apple-touch-icon" href="/uploads/apple-touch-icon-1024x1024.png">
    <?*/ ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="copyright" content="Создание сайтов - Cetera Labs, www.cetera.ru, 2015"/>
    <meta name="author"
          content="Cetera Labs, http://www.cetera.ru/, создание сайтов, поддержка сайтов, продвижение сайтов"/>

    <script data-skip-moving="true" src="//yastatic.net/jquery/1.11.2/jquery.min.js"></script>
    <script data-skip-moving="true" src="<?= WIC_TEMPLATE_PATH ?>/js/vendor/modernizr.js"></script>
    <?php
    $APPLICATION->SetAdditionalCSS(WIC_TEMPLATE_PATH . "/js/vendor/sortable/jquery-ui.min.css");
    $APPLICATION->SetAdditionalCSS(WIC_TEMPLATE_PATH . "/js/jqueryui/jquery-ui.min.css");
    $APPLICATION->SetAdditionalCSS(WIC_TEMPLATE_PATH . "/css/style.css");
    $APPLICATION->ShowHead();
    ?>
    <?php require($_SERVER['DOCUMENT_ROOT'] . WIC_TEMPLATE_PATH . '/include_areas/javascript.php'); ?>
</head>
<body>
<?php $APPLICATION->ShowPanel(); ?>
<header class="b_header">
    <div class="b-header__firstline row">
        <div class="small-8 small-offset-2 medium-offset-0 medium-5 column small-only-text-center b-header__logotype">
            <a href="/"><img src="<?= WIC_TEMPLATE_PATH ?>/images/logo.png" alt="" class="b-header__logo"></a>
        </div>
        <div class="column b-header__place text-center">
            <? $APPLICATION->IncludeComponent(
                "cetera:super.component",
                "sale.location",
                array(
                    "COMPONENT_TEMPLATE" => "sale.location",
                    "CACHE_TYPE" => "N",
                    "CACHE_TIME" => "1",
                    "MODAL_ID" => "saleLocation",
                    "COL_CNT" => "3",
                    "EMPTY_NAME" => "Выберите регион"
                ),
                false
            ); ?>
        </div>
        <div class="column small-2 medium-4  medium-text-right small-text-center b-header__loginform">
            <? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "",
                    "PATH" => "/include/auth.php",
                    "AREA_FILE_RECURSIVE" => "Y",
                    "EDIT_TEMPLATE" => "standard.php"
                )
            ); ?>
        </div>
        <div class="column small-2 b-header__showMenu_bg show-for-small-only text-right js-show-menu">
            <div class="b-header__showMenu">&nbsp;</div>
        </div>
    </div>
    <? $APPLICATION->IncludeComponent("bitrix:menu", "top", Array(
            "ROOT_MENU_TYPE" => "top",
            "MAX_LEVEL" => "1",
            "CHILD_MENU_TYPE" => "top",
            "USE_EXT" => "Y",
            "DELAY" => "N",
            "ALLOW_MULTI_SELECT" => "N",
            "MENU_CACHE_TYPE" => "N",
            "MENU_CACHE_TIME" => "1",
            "MENU_CACHE_USE_GROUPS" => "Y",
            "MENU_CACHE_GET_VARS" => Array(
                "method",
                "favorite"
            )
        )
    ); ?>
</header>