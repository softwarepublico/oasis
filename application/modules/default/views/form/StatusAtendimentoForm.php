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

class StatusAtendimentoForm extends Zend_Form
{

	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	private function generate()
	{
		$this->setName('formStatusAtendimento');
        $this->addDecorator('FormElements')
        ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-13'))
        ->addDecorator('Form');
		
		$cd_status_atendimento = new Base_Form_Element_Hidden('cd_status_atendimento');

		$tx_status_atendimento = new Base_Form_Element_Text('tx_status_atendimento', array('class'=>'span-7 float-l'));
		$tx_status_atendimento->setLabel(Base_Util::getTranslator('L_VIEW_STATUS_ATENDIMENTO').':')
		->addDecorator('Label', array('class'=>'span-6 float-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$st_status_atendimento = new Base_Form_Element_Radio('st_status_atendimento', array('class'=>'span-1 float-l'));
		$st_status_atendimento->setLabel(Base_Util::getTranslator('L_VIEW_TIPO').':')
		->addDecorator('Label', array('class'=>'float-l span-6 right'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-13 float-l', 'id'=>'div_teste'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setMultiOptions(array(
		'P'=>Base_Util::getTranslator('L_VIEW_PRIORIDADE'),
		'T'=>Base_Util::getTranslator('L_VIEW_TEMPO_RESPOSTA')));

		$tx_rgb_status_atendimento = new Base_Form_Element_Text('tx_rgb_status_atendimento', array('class'=>'span-2 float-l', 'maxlength'=>'6'));
		$tx_rgb_status_atendimento->setLabel(Base_Util::getTranslator('L_VIEW_COR_STATUS_ATENDIMENTO').':')
		->addDecorator('Label', array('class'=>'span-6 float-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$ni_percent_tempo_resposta_ini = new Base_Form_Element_SoNumero('ni_percent_tempo_resposta_ini', array('class'=>'span-1 float-l', 'style'=>'margin-left: 5px'));
		$ni_percent_tempo_resposta_ini->setLabel(Base_Util::getTranslator('L_VIEW_INTERVALO_TEMPO_RESPOSTA').':')
		->addDecorator('Label', array('class'=>'span-6 float-l right'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$ni_percent_tempo_resposta_fim = new Base_Form_Element_SoNumero('ni_percent_tempo_resposta_fim', array('class'=>'span-1 float-l'));
		$ni_percent_tempo_resposta_fim->setLabel('a ')
		->addDecorator('Label', array('class'=>'span-1 float-l right'));
		$ni_percent_tempo_resposta_fim
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$btn_salvar_status_atendimento = new Base_Form_Element_Button(Base_Util::getTranslator('L_BTN_SALVAR'));
		$btn_salvar_status_atendimento->setAttrib('id', 'btn_salvar_status_atendimento');
		$btn_salvar_status_atendimento->setAttrib('class', 'verde buttonBar');

		$btn_cancelar_status_atendimento = new Base_Form_Element_Button(Base_Util::getTranslator('L_BTN_CANCELAR'));
		$btn_cancelar_status_atendimento->setAttrib('id', 'btn_cancelar_status_atendimento');
		$btn_cancelar_status_atendimento->setAttrib('class', 'vermelho buttonBar hide');

		$this->addElements(array(
                                $cd_status_atendimento,
                                $tx_status_atendimento,
								$st_status_atendimento,
                                $tx_rgb_status_atendimento,
								$ni_percent_tempo_resposta_ini,
								$ni_percent_tempo_resposta_fim,
                                $btn_salvar_status_atendimento,
                                $btn_cancelar_status_atendimento));

		$this->addDisplayGroup(array('ni_percent_tempo_resposta_ini',
							         'ni_percent_tempo_resposta_fim'),
                               'grupo_tempo_resposta',
                               array('displayGroupClass'=>'Base_Form_DisplayDivGroup'));
		$this->getDisplayGroup('grupo_tempo_resposta')
             ->addDecorator('HtmlTag', array('tag'=>'div', 'id'=>'div_tempo_resposta'));
	}
}