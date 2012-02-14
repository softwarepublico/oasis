<?php
class Base_View_Helper_SoNumero extends Zend_View_Helper_FormText
{
	
	public function soNumero($name, $value = null, $attribs = null)
	{
		
		if (!is_array($attribs)) {
			$attribs = array();
		}
		
		$attribs['onKeyPress'] = 'return soNumeros(event)';
		
		return $this->formText($name, $value, $attribs);
		
	}
}