<?php
class Base_View_Helper_FlipFlop extends Zend_View_Helper_FormSelect {

	public function flipFlop( array $arrDadosCombo1, array $arrDadosCombo2, $nameBtn_1, $nameBtn_2, $span = null ) {

		//parâmetros para o combo select 1
		if(!array_key_exists('name', $arrDadosCombo1)){
			$arrDadosCombo1['name'] = 'combo_1';
		}
		if(!array_key_exists('label', $arrDadosCombo1)){
			$arrDadosCombo1['label'] = 'Label Combo 1 não informado.';
		}
		if(!array_key_exists('selectedValue', $arrDadosCombo1)){
			$arrDadosCombo1['selectedValue'] = null;
		}
		if(!array_key_exists('value', $arrDadosCombo1)){
			$arrDadosCombo1['value'] = null;
		}

		//parâmetros para o combo select 2
		if(!array_key_exists('name', $arrDadosCombo2)){
			$arrDadosCombo2['name'] = 'combo_2';
		}
		if(!array_key_exists('label', $arrDadosCombo2)){
			$arrDadosCombo2['label'] = 'Label Combo 2 não informado.';
		}
		if(!array_key_exists('selectedValue', $arrDadosCombo2)){
			$arrDadosCombo2['selectedValue'] = null;
		}
		if(!array_key_exists('value', $arrDadosCombo2)){
			$arrDadosCombo2['value'] = null;
		}


		$flipFlop  = '<div class="float-l clear-l">';
        if (isset($span)) {
            $span = 9;
        }

        $flipFlop .= "	<div class='span-$span'>";
		$flipFlop .= '		<label class="bold">'.$arrDadosCombo1['label'].'</label>';
		$flipFlop .=		$this->formSelect($arrDadosCombo1['name'], $arrDadosCombo1['selectedValue'], array('multiple'=>'multiple', 'size'=>'5', 'class'=>"span-$span", 'style'=>'height: 150px;'), $arrDadosCombo1['value'])."<br/>";
		$flipFlop .= '	</div>';
		$flipFlop .= '	<div class="span-1" style="position:relative; top:78px">';
		$flipFlop .= '		<button type="button" id="'.$nameBtn_1.'" value=">>">>></button>';
		$flipFlop .= '		<button type="button" id="'.$nameBtn_2.'" style="margin-top:10px;" value="<<"><<</button>';
		$flipFlop .= '	</div>';
		$flipFlop .= "	<div class='span-$span'>";
		$flipFlop .= '		<label class="bold">'.$arrDadosCombo2['label'].'</label>';
		$flipFlop .=		$this->formSelect($arrDadosCombo2['name'], $arrDadosCombo2['selectedValue'], array('multiple'=>'multiple', 'size'=>'5', 'class'=>"span-$span", 'style'=>'height: 150px;'),$arrDadosCombo2['value']);
		$flipFlop .= '	</div>';
		$flipFlop .= '</div>';
		
		return $flipFlop;
	}
}