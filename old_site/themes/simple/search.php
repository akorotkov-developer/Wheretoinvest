<?php     
$page = (int)$_GET['page'];
if (!$page) $page = 1;

$q =$_GET['query'];
       
if ($q) {

	if (!isset($_GET['m']) && !isset($_GET['r'])) {
		$all = 1;
	}
		   
	$q = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $q);
	$query = $q;

	$q = mb_strtoupper($q);
	$words = explode(" ", $q);
	foreach ($words as $i => $w) {
		if (strlen(trim($w)) < 3) unset($words[$i]); 
	}

	require_once( 'phpmorphy/src/common.php');

	$morphy = new phpMorphy(DOCROOT.LIBRARY_PATH.'/phpmorphy/dicts/', 'ru_RU', array('storage' => PHPMORPHY_STORAGE_FILE));

	$res = $morphy->getAllForms($words);
	
	if (count($res)) {
	
		$search = Cetera\ObjectDefinition::findById(1)
					->getMaterials()
					->orderBy('dat', 'DESC')
					->where( build_where(array('name','text','short'),$res) )
					->setItemCountPerPage( 20 )
					->setCurrentPageNumber( $page );
				
	}

}

if ($t->config->search_template) {
	$search_tpl = str_replace('/themes/simple/design/','',$t->config->search_template);
} else {
	$search_tpl = 'search.twig';
}

$twig->display($search_tpl, array(
	'list'  => $search,
    'query' => $query,
));		

function build_where($fields,$words) {
    $res = array();
    foreach ($fields as $f) {
        $res2 = array();
        foreach ($words as $key => $word) {
            $res3 = array();
            if (is_array($word)) {
                foreach ($word as $w) {
                    $res3[] = $f.' LIKE "%'.$w.'%"';
                }
                $res2[] = '('.implode(' or ',$res3).')';
            } else {
                $res2[] = '('.$f.' LIKE "%'.$key.'%")';
            }
        }
        $res[] = '('.implode(' and ',$res2).')';
    }
    return '('.implode(' or ',$res).')';
} 