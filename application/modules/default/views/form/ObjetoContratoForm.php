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

class ObjetoContratoForm extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
		
	private function generate()
	{
        
        $objAreaAtuacao = new AreaAtuacaoTi();
        
		$this->setName('objeto_contrato');
		$this->addDecorator('FormElements')
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-15'))
		->addDecorator('Form');

		$cd_contrato = new Base_Form_Element_Select('cd_contrato_objeto_contrato', array('class'=>'span-4'));
		$cd_contrato->setLabel(Base_Util::getTranslator('L_VIEW_CONTRATO').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);

        $cd_area_atuacao_ti = new Base_Form_Element_Select('cd_area_atuacao_ti_objeto_contrato', array('class'=>'float-l span-8'));
		$cd_area_atuacao_ti->setLabel(Base_Util::getTranslator('L_VIEW_AREA_ATUACAO_TI').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->setRequired(true)
		->setRegisterInArrayValidator(false)
        ->addMultiOptions($objAreaAtuacao->comboAreaAtuacaoTi(true));

        
		$cd_objeto = new Base_Form_Element_Hidden('cd_objeto');
		$tx_objeto = new Base_Form_Element_Text('tx_objeto_contrato', array('class'=>'float-l span-8'));
		$tx_objeto->setLabel(Base_Util::getTranslator('L_VIEW_OBJETO').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$st_objeto_contrato = new Base_Form_Element_Radio('st_objeto_contrato', array('class'=>'float-l'));
		$st_objeto_contrato->setLabel(Base_Util::getTranslator('L_VIEW_TIPO').':')
		->addDecorator('Label', array('class'=>'span-5 height-4 float-l right clear-l'))
		->addDecorator('HtmlTag', array('tag'=>'div','class'=>'float-l clear-l span-9'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		$st_objeto_contrato->addMultiOption('P', Base_Util::getTranslator('L_VIEW_COMBO_PROJETO'));
		$st_objeto_contrato->addMultiOption('D', Base_Util::getTranslator('L_VIEW_COMBO_DEMANDA'));
		$st_objeto_contrato->addMultiOption('S', Base_Util::getTranslator('L_VIEW_COMBO_SERVICO'));

		$st_parcela_orcamento = new Base_Form_Element_Checkbox('st_parcela_orcamento', array('class'=>'float-l'));
		$st_parcela_orcamento->setLabel(Base_Util::getTranslator('L_VIEW_PERMITE_PARCELA_ORCAMENTO').':')
		->setCheckedValue("S")
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'));
		
		$ni_porcentagem_parc_orcamento = new Base_Form_Element_Moeda('ni_porcentagem_parc_orcamento', array('class'=>'float-l span-2'));
		$ni_porcentagem_parc_orcamento->setLabel(Base_Util::getTranslator('L_VIEW_PORCENTAGEM_PARCELA_ORCAMENTO').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->addDecorator('HtmlTag', array('tag'=>'div','class'=>'float-l clear-l span-9','id'=>'div_porcentagem_orcamento'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$st_necessita_justificativa = new Base_Form_Element_Checkbox('st_necessita_justificativa', array('class'=>'float-l'));
		$st_necessita_justificativa->setLabel(Base_Util::getTranslator('L_VIEW_NECESSITA_JUSTIFICATIVA_SOLICITACAO_SERVICO').':')
		->setCheckedValue("S")
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'));
		
		$ni_minutos_justificativa = new Base_Form_Element_SoNumero('ni_minutos_justificativa', array('class'=>'float-l span-2'));
		$ni_minutos_justificativa->setLabel(Base_Util::getTranslator('L_VIEW_TEMPO_LEITURA_SOLICITACAO_SERVICO').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$hora1 = new Base_Form_Element_Hora('tx_hora_inicio_just_periodo_1',array('class' => 'span-2 float-l'));
		$hora1->setLabel(Base_Util::getTranslator('L_VIEW_PERIODO').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$hora2 = new Base_Form_Element_Hora('tx_hora_fim_just_periodo_1',array('class' => 'span-2 float-l', 'style'=>'margin-left: 5px',
			'onBlur'=>'comparaHoraInicioFim(\'tx_hora_inicio_just_periodo_1\', \'tx_hora_fim_just_periodo_1\',\''.Base_Util::getTranslator('L_MSG_HORA_FINAL_PRIMEIRO_PERIODO_MENOR_HORA_INICIAL_PRIMEIRO_PERIODO').'\')'));
		$hora2
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$hora3 = new Base_Form_Element_Hora('tx_hora_inicio_just_periodo_2',array('class' => 'span-2 float-l', 'style'=>'margin-left: 5px',
			'onBlur'=>'comparaHoraInicioFim(\'tx_hora_inicio_just_periodo_1\', \'tx_hora_inicio_just_periodo_2\',\''.Base_Util::getTranslator('L_MSG_HORA_INICIAL_SEGUNDO_PERIODO_MENOR_HORA_INICIAL_PRIMEIRO_PERIODO').'\');
					   comparaHoraInicioFim(\'tx_hora_fim_just_periodo_1\', \'tx_hora_inicio_just_periodo_2\',\''.Base_Util::getTranslator('L_MSG_HORA_INICIAL_SEGUNDO_PERIODO_MENOR_HORA_FINAL_PRIMEIRO_PERIODO').'\')'));
		$hora3
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$hora4 = new Base_Form_Element_Hora('tx_hora_fim_just_periodo_2',array('class' => 'span-2 float-l', 'style'=>'margin-left: 5px',
			'onBlur'=>'comparaHoraInicioFim(\'tx_hora_inicio_just_periodo_1\', \'tx_hora_fim_just_periodo_2\',\''.Base_Util::getTranslator('L_MSG_HORA_FINAL_SEGUNDO_PERIODO_MENOR_HORA_INICIAL_PRIMEIRO_PERIODO').'\');
					   comparaHoraInicioFim(\'tx_hora_fim_just_periodo_1\', \'tx_hora_fim_just_periodo_2\',\''.Base_Util::getTranslator('L_MSG_HORA_FINAL_SEGUNDO_PERIODO_MENOR_HORA_FINAL_PRIMEIRO_PERIODO').'\');
					   comparaHoraInicioFim(\'tx_hora_inicio_just_periodo_2\', \'tx_hora_fim_just_periodo_2\',\''.Base_Util::getTranslator('L_MSG_HORA_FINAL_SEGUNDO_PERIODO_MENOR_HORA_INICIAL_SEGUNDO_PERIODO').'\')'));
		$hora4
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$this->addElements(array($cd_contrato, $cd_area_atuacao_ti, $cd_objeto, $tx_objeto, $st_objeto_contrato, 
								 $st_parcela_orcamento,$ni_porcentagem_parc_orcamento,
								 $st_necessita_justificativa,$ni_minutos_justificativa, 
								 $hora1,$hora2,$hora3,$hora4));

		$this->addDisplayGroup(array('ni_minutos_justificativa',
							         'tx_hora_inicio_just_periodo_1',
							         'tx_hora_fim_just_periodo_1',
							         'tx_hora_inicio_just_periodo_2',
							         'tx_hora_fim_just_periodo_2'),
                               'grupo_just',
                               array('displayGroupClass'=>'Base_Form_DisplayDivGroup'));
		$this->getDisplayGroup('grupo_just')
             ->addDecorator('HtmlTag', array('tag'=>'div', 'id'=>'div_justificativa'));
	}
}