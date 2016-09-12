<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
require($_SERVER['DOCUMENT_ROOT'] . WIC_TEMPLATE_PATH . '/header.php');
IncludeTemplateLangFile(dirname(__FILE__)); ?>
<div class="content-line">
</div>
<? ob_start(); ?>
<? $APPLICATION->IncludeComponent("bitrix:menu", "left", Array(
        "ROOT_MENU_TYPE" => "left",
        "MAX_LEVEL" => "1",
        "CHILD_MENU_TYPE" => "left",
        "USE_EXT" => "Y",
        "DELAY" => "N",
        "ALLOW_MULTI_SELECT" => "N",
        "MENU_CACHE_TYPE" => "Y",
        "MENU_CACHE_TIME" => "3600",
        "MENU_CACHE_USE_GROUPS" => "Y",
        "MENU_CACHE_GET_VARS" => ""
    )
); ?>
<?
$left_menu = ob_get_contents();
ob_end_clean();
?>

<div class="row">
    <div class="columns small-12">
        <? $APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumb", Array(
            "START_FROM" => "0",    // Номер пункта, начиная с которого будет построена навигационная цепочка
            "PATH" => "",    // Путь, для которого будет построена навигационная цепочка (по умолчанию, текущий путь)
            "SITE_ID" => "s1",    // Cайт (устанавливается в случае многосайтовой версии, когда DOCUMENT_ROOT у сайтов разный)
        ),
            false
        ); ?>
    </div>
    <? if (!empty($left_menu)): ?>
        <div class="column small-12 medium-4 large-3 accord hide-for-small-only">
            <?= $left_menu ?>
        </div>
    <? endif; ?>
    <div
        class="column small-12<? if (!empty($left_menu)): ?> medium-8 large-9<? endif; ?> content">
        <h1 class="content__title"><? $APPLICATION->ShowTitle(); ?></h1>