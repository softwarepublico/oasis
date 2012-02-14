<?php
class Base_View_Helper_AnoCombo extends Zend_View_Helper_FormSelect
{
	private $anoInicio     = K_ANO_INICIO_COMBOS;
	private $anoQuantidade = 2;
	
	public function anoCombo($name, $value = null, $attribs = null, $anoQuantidade = null, $anoInicio = null)
	{
		if(!is_null($anoQuantidade)){
			$this->anoQuantidade = $anoQuantidade;
		}
		
		if(!is_null($anoInicio)){
			$this->anoInicio = $anoInicio; 
		}
		if(is_null($value)){
			$value = date('Y');
		}
		
		if (!is_array($attribs)) {
			$attribs = array('select' => 'select');
		}
		if(array_key_exists('descricaoAno',$attribs)){
			$descricaoAno = $attribs['descricaoAno'];
		} else {
			 $descricaoAno = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE_ANO');
		}
		//Array Ano inicio
		$arrAno = self::createArray($descricaoAno);
		
		return $this->formSelect($name, $value, $attribs,$arrAno);
	}
	
	protected  function createArray($descricaoAno)
	{
		$data  = (int)date('Y');
		$data += $this->anoQuantidade;
		$i     = $this->anoInicio;
		$arrAno[0] = $descricaoAno;
		
		for($i;$i <= $data ;$i++){
			$arrAno[$i] = $i;
		}
		
		return $arrAno;
	}
}