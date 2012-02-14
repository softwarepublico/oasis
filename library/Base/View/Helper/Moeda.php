<?php
class Base_View_Helper_Moeda extends Zend_View_Helper_FormText
{
	
	public function moeda($name, $value = null, $attribs = null)
	{
		
		if (!is_array($attribs)) {
			$attribs = array();
		} else {
			if(array_key_exists('symbol',$attribs)){
				 $symbol = $attribs['symbol'];
				 unset($attribs['symbol']);
			} else {
				$symbol = "R$";
			}
		}
		echo "<script>
			$(document).ready(function(){
				$('#{$name}').maskMoney({symbol:'".$symbol."',decimal:',',thousands:'.'});
         	});
		</script>";
				
		return $this->formText($name, $value, $attribs);
	}
}