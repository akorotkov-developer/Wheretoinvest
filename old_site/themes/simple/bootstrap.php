<?php
$a = Cetera\Application::getInstance();

// Сервер
$s = $a->getServer();

// Активный раздел
$c = $a->getCatalog();

// Tema
$t = $s->getTheme();

$cat = $c;
while (!$keywords && !$cat->isRoot()) {
    $keywords = $cat->meta_keywords;
    $cat = $cat->getParent();
}

$cat = $c;
while (!$description && !$cat->isRoot()) {
    $description = $cat->meta_description;
    $cat = $cat->getParent();
}

$twig = new Twig_Environment(
    new Twig_Loader_Filesystem(__DIR__.'/design'),
    array(
        'cache'       => CACHE_DIR.'/twig',
        'auto_reload' => true,
        'strict_variables' => true,
    )
);

if ($t->config->layout_template) {
	$layout = str_replace('/themes/simple/design/','',$t->config->layout_template);
} else {
	$layout = 'layout.twig';
}

if ($t->config->ordinary_template) {
	$ordinary = str_replace('/themes/simple/design/','',$t->config->ordinary_template);
} else {
	$ordinary = 'ordinary.twig';
}

if ($t->config->page404_template) {
	$page404 = str_replace('/themes/simple/design/','',$t->config->page404_template);
} else {
	$page404= '404.twig';
}

if ($t->config->header_template) {
	$header = str_replace('/themes/simple/design/','',$t->config->header_template);
} else {
	$header= 'blocks/header.twig';
}

if ($t->config->footer_template) {
	$footer = str_replace('/themes/simple/design/','',$t->config->footer_template);
} else {
	$footer= 'blocks/footer.twig';
}

if ($t->config->bottommenu_template) {
	$bottommenu = str_replace('/themes/simple/design/','',$t->config->bottommenu_template);
} else {
	$bottommenu= 'blocks/bottommenu.twig';
}

$twig->addGlobal('s',  $_SERVER);
$twig->addGlobal('server',  $s);
$twig->addGlobal('catalog', $c);
$twig->addGlobal('application', $a);
$twig->addGlobal('config',  $t->config);
$twig->addGlobal('basePath', '/themes/simple/');
$twig->addGlobal('layout', $layout);
$twig->addGlobal('ordinary', $ordinary);
$twig->addGlobal('description', $description );
$twig->addGlobal('keywords', $keywords );
$twig->addGlobal('header', $header );
$twig->addGlobal('footer', $footer );
$twig->addGlobal('bottommenu', $bottommenu );