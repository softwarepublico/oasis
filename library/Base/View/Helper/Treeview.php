<?php
class Base_View_Helper_Treeview
{

	private $ulRoot;
	private $dom;

	public function __construct()
	{
		$this->dom    = new DOMDocument("1.0", "utf-8");
		$this->ulRoot = $this->dom->createElement("ul");
	}

	public function treeview($name, $data, $checkbox = true, $color = 'treeview-red')
	{

		$this->ulRoot->setAttribute("id", $name);
		$this->ulRoot->setAttribute("class", $color);
		
		foreach ($data as $chave => $valor) {
			$this->percorreArray($valor);	
		}

		$this->dom->appendChild($this->ulRoot);
		echo $this->dom->saveXML();
		
	}
	
	private function percorreArray($arr)
	{
		if (is_array($arr)) {
			foreach ($arr as $item) {
				if (is_array($item)) {
					$this->percorreArray($item);
				} else {
					//echo $item . '<br>';	
				}
			}
		} else {
//			echo $arr;
		}
	}
	
	
/*
	private function createLi($text, $li)
	{

		if (is_array($li)) {

			$liPai = $this->createParent($text);

			foreach ($li as $key => $value) {
				
				if (!isset($ulParentChild)) {
					$ulParentChild = $this->createNode($key);
				}
				
				if (is_array($value)) {
					foreach ($value as $valueChild) {
						$liChild = $this->createNode($valueChild);
						$lis = $ulParentChild->getElementsByTagName("li");
						$lis->item(0)->appendChild($liChild);
					}
						$liPai->appendChild($ulParentChild);
				} else { 
					$liChild = $this->createNode($value);
					$liPai->appendChild($liChild);
				}
			}
		}

		$this->ulRoot->appendChild($liPai);
	}


	private function createParent($name)
	{
		$checkbox = $this->dom->createElement("input");
		$checkbox->setAttribute("type", "checkbox");

		$li 	  = $this->dom->createElement("li");
		$liText   = $this->dom->createTextNode($name);
		$li->appendChild($checkbox);
		$li->appendChild($liText);

		return $li;
	}


	private function createNode($name)
	{
		$ul       = $this->dom->createElement("ul");
		$checkbox = $this->dom->createElement("input");
		$checkbox->setAttribute("type", "checkbox");

		$li 	  = $this->dom->createElement("li");
		$liText   = $this->dom->createTextNode($name);
		$li->appendChild($checkbox);
		$li->appendChild($liText);
		$ul->appendChild($li);

		return $ul;
	}
*/




}