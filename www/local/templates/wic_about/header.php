<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

IncludeTemplateLangFile(dirname(__FILE__));

require($_SERVER['DOCUMENT_ROOT'] . WIC_TEMPLATE_PATH . '/header.php');
?>
<div class="content-line">
</div>

<div class="row">
    <div class="columns small-12 medium-7 large-8">
        <?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumb", Array(
	"START_FROM" => "0",	// Номер пункта, начиная с которого будет построена навигационная цепочка
		"PATH" => "",	// Путь, для которого будет построена навигационная цепочка (по умолчанию, текущий путь)
		"SITE_ID" => "s1",	// Cайт (устанавливается в случае многосайтовой версии, когда DOCUMENT_ROOT у сайтов разный)
	),
	false
);?>


<h1><?=$APPLICATION->ShowTitle(false)?></h1>


<div class="panel radius right share">
	<script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="small" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir" data-yashareTheme="counter"></div>
</div>



