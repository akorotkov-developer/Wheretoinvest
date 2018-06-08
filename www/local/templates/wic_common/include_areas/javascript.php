<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $APPLICATION;
use Cetera\Tools\JsIncludes;

JsIncludes::registerFile('common.add-to-home-screen', '#WIC_TEMPLATE_PATH#/js/vendor/add-to-home-screen/addtohomescreen.js?v=1');
JsIncludes::registerFile('common.common', '#WIC_TEMPLATE_PATH#/js/common.js?v=1');
JsIncludes::registerFile('common.foundation', '#WIC_TEMPLATE_PATH#/js/foundation/foundation.js?v=1');
JsIncludes::registerFile('common.foundation.reveal', '#WIC_TEMPLATE_PATH#/js/foundation/foundation.reveal.js?v=1');
JsIncludes::registerFile('common.foundation.equalizer', '#WIC_TEMPLATE_PATH#/js/foundation/foundation.equalizer.js?v=1');
JsIncludes::registerFile('common.foundation.alert', '#WIC_TEMPLATE_PATH#/js/foundation/foundation.alert.js?v=1');

$detect = new Mobile_Detect;
if (!$detect->isMobile())
    JsIncludes::registerFile('common.foundation.tooltip', '#WIC_TEMPLATE_PATH#/js/foundation/foundation.tooltip.js?v=1');

//JsIncludes::registerFile('common.foundation.accordion', '#WIC_TEMPLATE_PATH#/js/foundation/foundation.accordion.js?v=1');
//JsIncludes::registerFile('common.js.resize', '#WIC_TEMPLATE_PATH#/js/vendor/jquery.resize.js?v=1');
//JsIncludes::registerFile('common.foundation.orbit', '#WIC_TEMPLATE_PATH#/js/foundation/foundation.orbit.js?v=1');
//JsIncludes::registerFile('common.js.fastclick', '#WIC_TEMPLATE_PATH#/js/vendor/fastclick.js?v=1');

if ($APPLICATION->GetCurPage() !== "/") {
    JsIncludes::registerFile('common.js.maskedinput', '#WIC_TEMPLATE_PATH#/js/vendor/jquery.maskedinput.js?v=1');
    JsIncludes::registerFile('common.foundation.tab', '#WIC_TEMPLATE_PATH#/js/foundation/foundation.tab.js?v=1');
}

if ($APPLICATION->GetCurPage() == "/cabinet/method/") {
    JsIncludes::registerFile('common.js.sortable', '#WIC_TEMPLATE_PATH#/js/vendor/sortable/jquery-ui.min.js?v=1');
    JsIncludes::registerFile('common.js.sortabletouch', '#WIC_TEMPLATE_PATH#/js/vendor/sortable/jquery.ui.touch-punch.min.js?v=1');
}

JsIncludes::includeFiles('common.add-to-home-screen');
JsIncludes::includeFiles('common.foundation');
JsIncludes::includeFiles('common.js');
JsIncludes::includeFiles('common.common');
JsIncludes::injectVariable('#WIC_TEMPLATE_PATH#', WIC_TEMPLATE_PATH);

if (strpos($_SERVER["SERVER_NAME"], "beta") === false)
    JsIncludes::compressFiles();
echo JsIncludes::showIncludes();

/**
 * Предупреждение при использовании устаревшего браузера
 */
?>
    <script type="text/javascript">
        var $buoop = {};
        $buoop.ol = window.onload;
        window.onload = function () {
            try {
                if ($buoop.ol) $buoop.ol();
            } catch (e) {
            }
            var e = document.createElement("script");
            e.setAttribute("type", "text/javascript");
            e.setAttribute("src", "https://browser-update.org/update.js");
            document.body.appendChild(e);
        }
    </script>
    <script>
    jQuery(document).ready(function ($) {
        //addToHomescreen.removeSession();
        <?php
        $startDelay = \Ceteralabs\UserVars::GetVar('ATH_START_DELAY')["VALUE"];
        $lifespan = \Ceteralabs\UserVars::GetVar('ATH_LIFESPAN')["VALUE"];
        $displayPace = \Ceteralabs\UserVars::GetVar('ATH_DISPLAYPACE')["VALUE"];
        $debug = \Ceteralabs\UserVars::GetVar('ATH_DEBUG')["VALUE"];
        ?>
      
            addToHomescreen({
                debug: <?=!empty($debug) ? intval($debug) : 0?>,
                startDelay: <?=!empty($startDelay) ? intval($startDelay) : 10?>,
                lifespan: <?=!empty($lifespan) ? intval($lifespan) : 10?>,
                displayPace: <?=!empty($displayPace) ? intval($displayPace) : 60?>,
                customIcon: "/local/templates/wic_common/images/wic-aths.jpg"
            });
        
    });
    
    
    </script>   

        
     <?php

