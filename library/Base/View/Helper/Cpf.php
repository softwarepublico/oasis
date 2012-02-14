<?php
class Base_View_Helper_Cpf extends Zend_View_Helper_FormText
{
	public function cpf($name, $value = null, $attribs = null)
	{
		if (!is_array($attribs)) {
			$attribs = array();
		}
		//echo $objScript->script("mascara");
		echo " <script>
					//Mascará da jquery para os campos
					//Adiciona uma função no metodo
					jQuery(function($){
						$(\"#{$name}\").mask(\"999.999.999-99\",{completed:function(){cpfValidade($(\"#{$name}\"));}});
					});
			   </script>";
		
		return $this->formText($name, $value, $attribs);
	}
}