<?php
class Base_View_Helper_TreeviewAjuda {

	private $menu = array ();

	public function treeviewAjuda($name, $data) {

	    $baseUrl = new Base_View_Helper_BaseUrl();
	    $baseUrl = $baseUrl->baseUrl();

		if( is_object($data) ){
		    $data = $data->toArray();
		}
		foreach ( $data as $row ) {
			
			$modulo = ( $row['tx_modulo'] ) ? $row['tx_modulo']."-" : '';

			if (is_null ( $row['cd_menu_pai'] )) {
				$this->menu ['root'] [$row['cd_menu']] = Base_Util::getTranslator($row['tx_menu'])."|{$modulo}{$row['tx_pagina']}";
			} else {
				$this->menu [ $row['cd_menu_pai'] ] [ $row['cd_menu'] ] = Base_Util::getTranslator($row['tx_menu'])."|{$modulo}{$row['tx_pagina']}";
			}
		}

		print "<ul id='{$name}' class='dir'>\n";
		// Raiz
		foreach ( $this->menu ['root'] as $id => $label ) {

			$arrLabel = array();
			$arrLabel = explode("|", $label);
			
			$label	= trim($arrLabel[0]);
			$pagina = trim($arrLabel[1]);

			echo "<li id=\"{$pagina}\">";

		    echo "<a onClick=\"abreAjudaPagina('{$pagina}')\">{$label}</a>";

			if (isset ( $this->menu [$id] )) {
				$this->showTree ( $id );
			}
			print "</li>\n";
		}
		print "</ul>";

		return $this->menu;
	}

	private function showTree($id_parent ) {

	    $baseUrl = new Base_View_Helper_BaseUrl();
	    $baseUrl = $baseUrl->baseUrl();

		print "<ul>\n";
		foreach ( $this->menu [$id_parent] as $id => $label ) {
			$checkIt = null;

			$arrLabel = array();
			$arrLabel = explode("|", $label);

			$label	= trim($arrLabel[0]);
			$pagina = trim($arrLabel[1]);

			if (isset ( $this->menu [$id] )) {
				print "<li id=\"{$pagina}\">";

   			    echo "<a onClick=\"abreAjudaPagina('{$pagina}')\">{$label}</a>";

				$this->showTree ( $id );
			} else {
				print "<li id=\"{$pagina}\">";
   			    print "<a onClick=\"abreAjudaPagina('{$pagina}')\">{$label}</a>";
			}
		}
		print "</li></ul>\n";
	}
}