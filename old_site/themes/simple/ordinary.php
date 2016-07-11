<?php      
$uu = $a->getUnparsedUrl();

if ($t->config->material_template) {
	$material_tpl = str_replace('/themes/simple/design/','',$t->config->material_template);
} else {
	$material_tpl = 'material.twig';
}

if ($uu) {
     
    try {
        
        $twig->display($material_tpl, array(
            'material' => $c->getMaterialByAlias($uu)
        ));        
        
    } catch (Exception $e) {
    
        $twig->display( $page404 );
        
    }  

} else {


    try {
        
        $twig->display($material_tpl, array(
            'material' => $c->getMaterialByAlias('index')
        ));        
        
    } catch (Exception $e) {
    
        try {
    
            $page = (int)$_GET['page'];
            if (!$page) $page = 1;
    
            $list = $c->getMaterials()
                      ->select('dat','short')
                      ->orderBy('dat', 'DESC')
                      ->setItemCountPerPage(10)
                      ->setCurrentPageNumber($page);
                  
        } catch (Exception $e) {
    
            $list = null;
        
        }  
                            
        $twig->display($ordinary, array(
            'list' => $list,
            'material' => null
        ));
        
    } 

}