<?php
$uu = $a->getUnparsedUrl();

if ($uu == 'themes/simple/css/style') {
	include('style.php');
	die();
}

if ($s->id == $c->id) {  

	if ( $uu ) {
		
		$twig->display( $page404 );
		
	} else {
	
		if ($t->config->index_template) {
			$tpl = str_replace('/themes/simple/design/','',$t->config->index_template);
			$twig->display($tpl);
		} else {
			$twig->display('index.twig');
		}
	
	}

} else {
	
    include( 'ordinary.php' );
	
}
    