<?php
class Base_Controller_Action_Helper_I18n extends Zend_Controller_Action_Helper_Abstract
{

	/**
     * Getter das Mensagens da Regra de Negócio
     *
     * @param string       $indice
     * @param string|array $value Exemplo array('value1'=>10, 'value2'=>20)
     * @return string
     */
    public function get( $indice , $value='' )
    {
        $msg = Zend_Registry::get('Zend_Translate')->_( $indice );
        if( !empty($value) ) {
            if(is_array($value)){
                foreach($value as $key=>$value){
                    $msg = str_replace("%{$key}%", $value, $msg);
                }
            }else{
                $msg = str_replace('%value%',$value,$msg);
            }
        }
        return $msg;
    }
}