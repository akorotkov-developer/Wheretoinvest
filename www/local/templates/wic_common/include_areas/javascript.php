<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Cetera\Tools\JsIncludes;

JsIncludes::includeFiles('common');
JsIncludes::injectVariable('#SITE_TEMPLATE_PATH#', WIC_TEMPLATE_PATH);
echo JsIncludes::showIncludes();


/**
 * Предупреждение при использовании устаревшего браузера
 */
?><script type="text/javascript"> 
  var $buoop = {};
  $buoop.ol = window.onload; 
  window.onload=function(){ 
   try {if ($buoop.ol) $buoop.ol();}catch (e) {} 
   var e = document.createElement("script"); 
   e.setAttribute("type", "text/javascript"); 
   e.setAttribute("src", "http://browser-update.org/update.js"); 
   document.body.appendChild(e); 
  } 
</script><?

