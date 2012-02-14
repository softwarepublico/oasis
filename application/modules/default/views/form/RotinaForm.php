<?php
/**
 * @Copyright Copyright 2006, 2007, 2008, 2009 MDIC - Ministério do Desenvolvimento, da Industria e do Comércio Exterior, Brasil.
 * @tutorial  Este arquivo é parte do programa OASIS - Sistema de Gestão de Demanda, Projetos e Serviços de TI.
 *			  O OASIS é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença
 *			  Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença,
 *			  ou (na sua opnião) qualquer versão.
 *			  Este programa é distribuido na esperança que possa ser util, mas SEM NENHUMA GARANTIA;
 *			  sem uma garantia implicita de ADEQUAÇÂO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR.
 *			  Veja a Licença Pública Geral GNU para maiores detalhes.
 *			  Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 *			  junto com este programa, se não, escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St,
 *			  Fifth Floor, Boston, MA 02110-1301 USA.
 */

class RotinaForm extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	private function generate()
	{
        $objAreaAtuacao = new AreaAtuacaoTi();

		$this->setName('rotina');
		$this->addDecorator('FormElements')
        ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-12'))
        ->addDecorator('Form');

		$cd_area_atuacao_ti = new Base_Form_Element_Select('cd_area_atuacao_ti_rotina', array('class'=>'float-l'));
		$cd_area_atuacao_ti->setLabel(Base_Util::getTranslator('L_VIEW_AREA_ATUACAO_TI').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right'))
		->setRequired(true)
		->setRegisterInArrayValidator(false)
        ->addMultiOptions($objAreaAtuacao->comboAreaAtuacaoTi(true));
		
		$cd_rotina = new Base_Form_Element_Hidden('cd_rotina');
        
		$tx_rotina = new Base_Form_Element_Text('tx_rotina', array('class'=>'span-5 float-l'));
		$tx_rotina->setLabel(Base_Util::getTranslator('L_VIEW_ROTINA').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
        $tx_hora_inicio_rotina = new Base_Form_Element_Hora('tx_hora_inicio_rotina',array('class' => 'span-2 float-l'));
		$tx_hora_inicio_rotina->setLabel(Base_Util::getTranslator('L_VIEW_HORA').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$st_periodicidade_rotina = new Base_Form_Element_Select('st_periodicidade_rotina', array('class'=>'span-3 float-l'));
		$st_periodicidade_rotina->setLabel(Base_Util::getTranslator('L_VIEW_PERIODICIDADE').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->setRequired(true)
		->setRegisterInArrayValidator(false);
        $st_periodicidade_rotina->addMultiOption('0', Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE'));
        $st_periodicidade_rotina->addMultiOption('D', Base_Util::getTranslator('L_VIEW_COMBO_DIARIA'));
		$st_periodicidade_rotina->addMultiOption('S', Base_Util::getTranslator('L_VIEW_COMBO_SEMANAL'));
		$st_periodicidade_rotina->addMultiOption('M', Base_Util::getTranslator('L_VIEW_COMBO_MENSAL'));
        
		$ni_prazo_execucao_rotina = new Base_Form_Element_SoNumero('ni_prazo_execucao_rotina', array('class'=>'span-5 float-l'));
		$ni_prazo_execucao_rotina->setLabel(Base_Util::getTranslator('L_VIEW_PRAZO_EXECUCAO_HORAS').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$ni_dia_semana_rotina = new Base_Form_Element_Select('ni_dia_semana_rotina', array('class'=>'span-3 float-l'));
		$ni_dia_semana_rotina->setLabel(Base_Util::getTranslator('L_VIEW_DIA_SEMANA').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right'))
        ->addDecorator('HtmlTag', array('tag'=>'div','class'=>'float-l clear-l span-10','id'=>'div_dia_semana'))
		->setRegisterInArrayValidator(false);
        $ni_dia_semana_rotina->addMultiOption('0', Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE'));
        $ni_dia_semana_rotina->addMultiOption('1', Base_Util::getTranslator('L_VIEW_DIA_SEMANA_DOMINGO_EXTENSO'));
		$ni_dia_semana_rotina->addMultiOption('2', Base_Util::getTranslator('L_VIEW_DIA_SEMANA_SEGUNDA_EXTENSO'));
		$ni_dia_semana_rotina->addMultiOption('3', Base_Util::getTranslator('L_VIEW_DIA_SEMANA_TERCA_EXTENSO'));
		$ni_dia_semana_rotina->addMultiOption('4', Base_Util::getTranslator('L_VIEW_DIA_SEMANA_QUARTA_EXTENSO'));
		$ni_dia_semana_rotina->addMultiOption('5', Base_Util::getTranslator('L_VIEW_DIA_SEMANA_QUINTA_EXTENSO'));
		$ni_dia_semana_rotina->addMultiOption('6', Base_Util::getTranslator('L_VIEW_DIA_SEMANA_SEXTA_EXTENSO'));
		$ni_dia_semana_rotina->addMultiOption('7', Base_Util::getTranslator('L_VIEW_DIA_SEMANA_SABADO_EXTENSO'));

		$arrDiaMes = array();
        $i         = 1;
        $arrDiaMes[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        for ($i; $i <= 31; $i++){
            $arrDiaMes[$i] = $i;
        }

        $ni_dia_mes_rotina = new Base_Form_Element_Select('ni_dia_mes_rotina', array('class'=>'span-3 float-l'));
		$ni_dia_mes_rotina->setLabel(Base_Util::getTranslator('L_VIEW_DIA_MES').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right'))
        ->addDecorator('HtmlTag', array('tag'=>'div','class'=>'float-l clear-l span-10','id'=>'div_dia_mes'))
		->setRegisterInArrayValidator(false)
        ->addMultiOptions($arrDiaMes);

        $st_rotina_inativa = new Base_Form_Element_Checkbox('st_rotina_inativa', array('class'=>'float-l'));
		$st_rotina_inativa->setLabel(Base_Util::getTranslator('L_VIEW_INATIVA').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim');
		$st_rotina_inativa->setCheckedValue('S');
		$st_rotina_inativa->setUncheckedValue(null);

		$this->addElements(array($cd_area_atuacao_ti, $cd_rotina, $tx_rotina, $st_periodicidade_rotina, $ni_dia_semana_rotina, $ni_dia_mes_rotina, $tx_hora_inicio_rotina, $ni_prazo_execucao_rotina, $st_rotina_inativa));
	}
}
