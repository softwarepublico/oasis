<?php
class Base_View_Helper_Data extends Zend_View_Helper_FormText
{
	/**
	 * Método que defini um campo data para o sistema com 
	 * validação e componete de calendario.
	 * 
	 * @param text $name    Defini nome e Id no campo do input.
	 * @param text $value   Defini valor para o campo.
	 * @param Text $attribs Defini os atributos para o input.
	 * @return text $botaoData Retorno todo o input montado para a data
	 */
	public function data($name, $value = null, $attribs = null)
	{
		$url = new Base_View_Helper_BaseUrl();
		if (!is_array($attribs)) {
			$attribs = array();
		}
		echo " <script language=\"javascript\" type=\"text/javascript\">
					$(document).ready(function(){
						$('#{$name}').mask('99/99/9999').bind('blur', function(){dateValidade($(this));});
					    $('#{$name}_img').DatePicker({
					        format:'d/m/Y',
					        date: $('#{$name}').val(),
					        starts: 0,
					        position: 'right',
					        onBeforeShow: function(){
					            $('#{$name}').DatePickerSetDate($('#{$name}').val(), true);
					        },
					        onChange: function(formated, dates){
					            $('#{$name}').val(formated);
					        }
					    });
                    });
			   </script>";
			$botaoData  = $this->formText($name, $value, $attribs);
			$botaoData .= '<img src="'. $url->baseUrl() ."/public/img/calendario.gif\" alt=\"calendario\" id=\"{$name}_img\" class=\"calendario float-l\" style=\"margin-top: 0px;\" />";

			return $botaoData; 
	}
}