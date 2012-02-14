<?php
class Base_View_Helper_TreeviewMenu {
	
	private $menu = array ();
	private $checked; 
	
	public function treeviewMenu($name, $data, $checkbox = true, $checked = null, $color = 'treeview-red', $mostraLink = false) {
	    
	    $baseUrl = new Base_View_Helper_BaseUrl();
	    $baseUrl = $baseUrl->baseUrl();
		
		$this->checked = $checked;
		
		if( is_object($data) ){
		    $data = $data->toArray();
		}
		foreach ( $data as $row ) {
			if (is_null ( $row['cd_menu_pai'] )) {
				$this->menu ['root'] [$row['cd_menu']] = Base_Util::getTranslator($row['tx_menu'])." ({$row['tx_pagina']})";
			} else {
				$this->menu [ $row['cd_menu_pai'] ] [ $row['cd_menu'] ] = Base_Util::getTranslator($row['tx_menu'])." ({$row['tx_pagina']})";
			}
		}
		
		print "<ul id='{$name}' class='dir'>\n";
		// Raiz
		foreach ( $this->menu ['root'] as $id => $label ) {
			echo "<li>";
			$checkIt = null;
			if ($checkbox) {
				if (is_array ( $this->checked )) {
					if (in_array ( $id, $this->checked )) {
						$checkIt = 'checked = "checked"';
					}
				}
				echo "<input type=\"checkbox\" name=\"chkMenu[]\" id_pai=\"{$id}\" id=\"{$id}\" value=\"{$id}\" {$checkIt}>";
			}
			if ($mostraLink === false) {
			    echo $label;
			} else {
			    echo "<a onClick=\"recuperaMenu($id)\">{$label}</a>";
			}
			
			if (isset ( $this->menu [$id] )) {
				$this->showTree ( $id, $checkbox, $mostraLink );
			}
			print "</li>\n";
		}
		print "</ul>";
		
		return $this->menu;
	
	}
	
	private function showTree($id_parent, $checkbox, $mostraLink = false) {
        
	    $baseUrl = new Base_View_Helper_BaseUrl();
	    $baseUrl = $baseUrl->baseUrl();
			
		print "<ul>\n";
		foreach ( $this->menu [$id_parent] as $id => $label ) {
			$checkIt = null;
			if (isset ( $this->menu [$id] )) {
				print "<li>";
				if ($checkbox) {
					if (is_array ( $this->checked )) {
						if (in_array ( $id, $this->checked )) {
							$checkIt = 'checked = "checked"';
						}
					}
					print "<input type=\"checkbox\" name=\"chkMenu[]\" id_pai=\"{$id_parent}\" id=\"{$id}\" value=\"{$id}\" {$checkIt}>";
				}
				
    			if ($mostraLink === false) {
    			    print $label;
    			} else {
    			    print "<a onClick=\"recuperaMenu($id)\">{$label}</a>";
    			}
				
				$this->showTree ( $id, $checkbox, $mostraLink );
			} else {
				print "<li>";
				if ($checkbox) {
					if (is_array ( $this->checked )) {
						if (in_array ( $id, $this->checked )) {
							$checkIt = 'checked = "checked"';
						}
					}
					print "<input type=\"checkbox\" name=\"chkMenu[]\" id_pai=\"{$id_parent}\" id=\"{$id}\" value=\"{$id}\" {$checkIt}>";
				}

				if ($mostraLink === false) {
    			    print $label;
    			} else {
    			    print "<a onClick=\"recuperaMenu($id)\">{$label}</a>";
    			}
			}
		}
		print "</li></ul>\n";
	}
}