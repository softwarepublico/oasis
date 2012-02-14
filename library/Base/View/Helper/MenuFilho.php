<?php
class Base_View_Helper_MenuFilho
{
	public function menuFilho($controller)
	{
		$cd_profissional = $_SESSION['oasis_logged'][0]['cd_profissional'];
    	$cd_objeto       = $_SESSION['oasis_logged'][0]['cd_objeto'];

	    $menusFilho = ConsultaMenuUsuario::getMenusFilhos($controller, $cd_profissional, $cd_objeto);
	    $baseUrl = new Base_View_Helper_BaseUrl();
        
	    $htmlMenu  = '<ul style="padding-left: 25px;padding-top: 5px;" class="span-21">';
	    foreach ($menusFilho as $menu) {
	    	
	    	$pesquisaPermissao = ( !is_null($menu['tx_modulo']) ) ? mb_strtolower($menu['tx_modulo'], 'utf-8')."_".$menu['tx_pagina']: $menu['tx_pagina'];

	    	$permissoes = array_search($pesquisaPermissao, $_SESSION['permissoes']);
	    	
	    	if($permissoes){
				//Condição que verifica se a pagina possui módulo
				$tx_modulo = "";
				if(!empty($menu['tx_modulo'])){
					$tx_modulo = "/{$menu['tx_modulo']}";
				}
	            $htmlMenu .= "<li class=\"float-l span-10 liColums\"><a href=\"" . $baseUrl->baseUrl() .$tx_modulo. "/{$menu['tx_pagina']}\">".$menu['tx_menu']."</a></li>";
	    	}
	    }

	    $htmlMenu .= '</ul>';

	    return $htmlMenu;
	}
}