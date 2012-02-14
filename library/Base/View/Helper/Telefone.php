<?php
class Base_View_Helper_Telefone extends Zend_View_Helper_FormText
{
	
	public function telefone($name, $value = null, $attribs = null)
	{
		if (!is_array($attribs)) {
			$attribs = array('size'=>'12');
		}
		echo " <script>
					//Mascará da jquery para os campos
					//Adiciona uma função no metodo
					jQuery(function($){
						$(\"#{$name}\").mask(\"(99) 9999-9999\");
					});
			   </script>";
		
		return $this->formText($name, $value, $attribs);
	}
}