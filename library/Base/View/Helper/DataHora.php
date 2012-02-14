<?php
class Base_View_Helper_DataHora extends Zend_View_Helper_FormText
{
	/**
	 * Método que defini um campo data e hora para o sistema com 
	 * validação e componete de calendario.
	 * 
	 * @param text $name    Defini nome e Id no campo do input.
	 * @param text $value   Defini valor para o campo.
	 * @param Text $attribs Defini os atributos para o input.
	 * @return text $botaoData Retorno todo o input montado para a data
	 */
	public function DataHora($name, $value = null, $attribs = null)
	{
		$objScript = new Base_View_Helper_Script();
		if (!is_array($attribs)) {
			$attribs = array();
		}

		$objScript->script("mascara");
        
		echo " <script>
					//Mascará da jquery para os campos
					//Adiciona uma função no metodo
					jQuery(function($){
						$(\"#{$name}\").mask(\"99/99/9999 99:99:99\",{completed:function(){dateTimeValidade($(\"#{$name}\"));}});
					});
			   </script>";
		return $this->formText($name, $value, $attribs);
	}
}