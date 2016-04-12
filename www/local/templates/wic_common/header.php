<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

IncludeTemplateLangFile(dirname(__FILE__));
?><!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js ie lt-ie9 lt-ie8 lt-ie7" lang="en"><![endif]-->
<!--[if IE 7]><html class="no-js ie ie7 lt-ie9 lt-ie8" lang="en"><![endif]-->
<!--[if IE 8]><html class="no-js ie ie8 lt-ie9" lang="en"><![endif]-->
<!--[if IE 9]><html class="no-js ie ie9" lang="en"><![endif]-->
<!--[if !IE]><!--> <html class="no-js" lang="en"><!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    <meta name="author" content="Cetera Labs, http://www.cetera.ru/, создание сайтов, поддержка сайта, корпоративный портал, eCommerce, CMS" />
    
    <title><?php $APPLICATION->ShowTitle()?></title>

    <?php
    $APPLICATION->AddHeadScript(WIC_TEMPLATE_PATH . "/js/vendor/modernizr-2.6.2.min.js");

    $APPLICATION->SetAdditionalCSS(WIC_TEMPLATE_PATH . "/css/style.css");

    $APPLICATION->ShowHead();
    ?>
</head>
<body>
    <?php $APPLICATION->ShowPanel();?>

        