<?php
namespace Cetera;

$config = array(

	'logo' => '/themes/simple/images/logo.png',
	'phone' => '+7 (499) 918-43-57',
	'email' => 'welcome@cetera.ru',
	'address' => 'г. Ярославль, проспект Ленина, дом 25, 6 этаж, офис 606',
	'link_vk' => 'http://vk.com/ceteralabs',
	'link_fb' => 'https://www.facebook.com/ceteralabs',
	'css' => '',
	'js' => '',
	'primary_color' => '#F04124',
	'secondary_color' => '#008CBA',
	'body_color' => '#FFFFFF',
	'text_color' => '#555555',
	'text_secondary_color' => '#FFFFFF',
	'heading_color' => '#000000',
	'layout_template' => '/themes/simple/design/layout.twig',
	'index_template' => '/themes/simple/design/index.twig',
	'ordinary_template' => '/themes/simple/design/ordinary.twig',
	'page404_template' => '/themes/simple/design/404.twig',
	'material_template' => '/themes/simple/design/material.twig',
	'search_template' => '/themes/simple/design/search.twig',
	'header_template' => '/themes/simple/design/blocks/header.twig',
  'footer_template' => '/themes/simple/design/blocks/footer.twig',
	'bottommenu_template' => '/themes/simple/design/blocks/bottommenu.twig'

);

foreach (Server::enum() as $s) {
	
	if ($currentTheme) $currentTheme->loadConfig( $s );

	if (!$currentTheme || !$currentTheme->config) 
	{
		$res = $config;
	}
	else
	{
		$current = get_object_vars($currentTheme->config);
    
		// в предыдущих версиях темы лого бралось из свойств сервера
		if ( !isset($current['logo']) && $s->pic ) $current['logo'] = $s->pic;
    
    // topmenu_template изменился на header_template
    if ( isset($current['topmenu_template']) && !isset($current['header_template']) )
    {
        $current['header_template'] = $current['topmenu_template'];
        if ( $current['header_template'] == '/themes/simple/design/blocks/topmenu.twig' )
            unset($current['header_template']);
        unset( $current['topmenu_template'] ); 
    }
    
		$res = array_merge( $config, $current );
	}
		
	$currentTheme->setConfig( $res, $s );
	
}