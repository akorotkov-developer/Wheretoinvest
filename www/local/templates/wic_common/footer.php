<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
</div>
</div>
<section class="b-footer">
    <div class="row">
        <div class="column medium-3 medium-push-9">
            <? $APPLICATION->IncludeComponent(
                "asd:subscribe.quick.form",
                ".default",
                array(
                    "FORMAT" => "html",
                    "INC_JQUERY" => "N",
                    "NOT_CONFIRM" => "N",
                    "RUBRICS" => array(
                        0 => "1",
                    ),
                    "SHOW_RUBRICS" => "N",
                    "COMPONENT_TEMPLATE" => ".default"
                ),
                false
            ); ?>
            <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array(
                "AREA_FILE_SHOW" => "file",
                "AREA_FILE_SUFFIX" => "",
                "PATH" => "/include/social.php",
                "AREA_FILE_RECURSIVE" => "Y",
                "EDIT_TEMPLATE" => "standard.php"
            ),
                false,
                array(
                    "ACTIVE_COMPONENT" => "N"
                )
            ); ?>
        </div>
        <div class="column medium-4 medium-push-2">
            <? $APPLICATION->IncludeComponent("bitrix:menu", "bottom", Array(
                    "ROOT_MENU_TYPE" => "bottom",
                    "MAX_LEVEL" => "1",
                    "CHILD_MENU_TYPE" => "bottom",
                    "USE_EXT" => "Y",
                    "DELAY" => "N",
                    "ALLOW_MULTI_SELECT" => "N",
                    "MENU_CACHE_TYPE" => "Y",
                    "MENU_CACHE_TIME" => "3600",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "MENU_CACHE_GET_VARS" => ""
                )
            ); ?>
        </div>
        <div class="column medium-5 medium-pull-7">
            <? $APPLICATION->IncludeComponent("bitrix:menu", "bottom", Array(
                    "ROOT_MENU_TYPE" => "bottom_left",
                    "MAX_LEVEL" => "1",
                    "CHILD_MENU_TYPE" => "bottom_left",
                    "USE_EXT" => "Y",
                    "DELAY" => "N",
                    "ALLOW_MULTI_SELECT" => "N",
                    "MENU_CACHE_TYPE" => "Y",
                    "MENU_CACHE_TIME" => "3600",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "MENU_CACHE_GET_VARS" => ""
                )
            ); ?>

            <? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "",
                    "PATH" => "/include/copyright.php",
                    "AREA_FILE_RECURSIVE" => "Y",
                    "EDIT_TEMPLATE" => "standard.php"
                )
            ); ?>
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
