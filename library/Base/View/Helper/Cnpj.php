<?php
class Base_View_Helper_Cnpj extends Zend_View_Helper_FormText
{
	
	public function cnpj($name, $value = null, $attribs = null)
	{
		if (!is_array($attribs)) {
			$attribs = array();
		}
		//echo $objScript->script("mascara");
		echo " <script>
					//Mascará da jquery para os campos
					//Adiciona uma função no metodo
					jQuery(function($){
						$(\"#{$name}\").mask(\"99.999.999/9999-99\",{completed:function(){cnpjValidade($(\"#{$name}\"));}});
					});
			   </script>";
		
		return $this->formText($name, $value, $attribs);
	}
}