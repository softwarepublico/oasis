<?php
class Base_View_Helper_SelectUtil extends Zend_View_Helper_FormSelect
{
    /**
     * @var Array _arrAttribs
     */
    private $_arrOptions;

    /**
     * Método de visão que chama outros métodos pra monta o select
     *
     * @param String $function Nome do Método a ser chamado
     * @param Array  $arrAttribs
     * @return formSelect
     */
    public function selectUtil($function, $name, $value = null , Array $attribs = array(), Array $options = array())
    {
        $this->_setOptions($options);

        $function = '_'.$function;

        if(method_exists($this, $function)){
            $this->$function();
        }
        return $this->formSelect($name, $value, $attribs, $this->_arrOptions);
    }

    private function _impactoRisco()
    {
        $this->_arrOptions = array();
        $this->_arrOptions['tx_cor_impacto_projeto_risco']    = Base_Util::getTranslator('L_VIEW_COMBO_PROJETO');
		$this->_arrOptions['tx_cor_impacto_tecnico_risco']    = Base_Util::getTranslator('L_VIEW_COMBO_TECNICO');
		$this->_arrOptions['tx_cor_impacto_custo_risco']      = Base_Util::getTranslator('L_VIEW_COMBO_CUSTO');
		$this->_arrOptions['tx_cor_impacto_cronog_risco'] = Base_Util::getTranslator('L_VIEW_COMBO_CRONOGRAMA');
    }

    private function _etapa()
    {
        //verificar como fazer, pois etapa sempre está ligada a uma area de atuação
        $this->_arrOptions = array('0'=>Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE'));
    }

    /**
     * Método que seta as options para o select caso não tenha
     * @param Array $options
     */
    private function _setOptions(Array $options)
    {
        if(count($options) == 0){
            $this->_arrOptions = array('0'=>Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE'));
        } else {
            $this->_arrOptions = $options;
        }
    }
}