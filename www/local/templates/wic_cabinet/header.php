<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

IncludeTemplateLangFile(dirname(__FILE__));

require($_SERVER['DOCUMENT_ROOT'] . WIC_TEMPLATE_PATH . '/header.php');
?>
<div class="content-line">
</div>

<div class="row">
    <div class="columns small-12 medium-7 large-12">
        <?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumb", Array(
	"START_FROM" => "0",	// Номер пункта, начиная с которого будет построена навигационная цепочка
		"PATH" => "",	// Путь, для которого будет построена навигационная цепочка (по умолчанию, текущий путь)
		"SITE_ID" => "s1",	// Cайт (устанавливается в случае многосайтовой версии, когда DOCUMENT_ROOT у сайтов разный)
	),
	false
);?>
	</div>
		<div class="columns large-3">
			<h3>Доходность</h3>
			<ul>
				<li><a href="/cabinet/partner/offers/">Банковские вклады</a> </li>
				<li><a href="/cabinet/partner/loan/">Займы</a> </li>
			</ul>
		</div>
	<div class="column large-9">





