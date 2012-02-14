<?php
class Base_View_Helper_MesCombo extends Zend_View_Helper_FormSelect
{
	public function mesCombo($name, $value = null, $attribs = null)
	{
		if (!is_array($attribs)) {
			$attribs = array('select' => 'select');
		}
		if(array_key_exists('descricaoMes',$attribs)){
			$descricaoMes = $attribs['descricaoMes'];
		} else {
			 $descricaoMes = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE_MES');
		}
		
		if(is_null($value)){
			$value = date('n');
		}
		
		//Monta um array para os mêses do projeto 
		$arrMeses     = array();
		$arrMeses[0]  = $descricaoMes;
		$arrMeses[1]  = Base_Util::getTranslator('L_VIEW_COMBO_JANEIRO');
		$arrMeses[2]  = Base_Util::getTranslator('L_VIEW_COMBO_FEVEREIRO');
		$arrMeses[3]  = Base_Util::getTranslator('L_VIEW_COMBO_MARCO');
		$arrMeses[4]  = Base_Util::getTranslator('L_VIEW_COMBO_ABRIL');
		$arrMeses[5]  = Base_Util::getTranslator('L_VIEW_COMBO_MAIO');
		$arrMeses[6]  = Base_Util::getTranslator('L_VIEW_COMBO_JUNHO');
		$arrMeses[7]  = Base_Util::getTranslator('L_VIEW_COMBO_JULHO');
		$arrMeses[8]  = Base_Util::getTranslator('L_VIEW_COMBO_AGOSTO');
		$arrMeses[9]  = Base_Util::getTranslator('L_VIEW_COMBO_SETEMBRO');
		$arrMeses[10] = Base_Util::getTranslator('L_VIEW_COMBO_OUTUBRO');
		$arrMeses[11] = Base_Util::getTranslator('L_VIEW_COMBO_NOVEMBRO');
		$arrMeses[12] = Base_Util::getTranslator('L_VIEW_COMBO_DEZEMBRO');
		
		return $this->formSelect($name, $value, $attribs,$arrMeses);
	}
}