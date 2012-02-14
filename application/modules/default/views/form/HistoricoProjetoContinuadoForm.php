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

class HistoricoProjetoContinuadoForm extends Base_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}

	private function generate()
	{

		$this->setName('historico_projeto_continuado');
		$this->addDecorator('FormElements')
        ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-17'))
        ->addDecorator('Form');

		$objetoContrato    = new ObjetoContrato();
		$arrObjetoContrato = $objetoContrato->getObjetoContratoAtivo('D', true, false);
		
		$cd_profissional = new Base_Form_Element_Hidden('cd_profissional_historico_projeto_continuado');
		$cd_profissional->setValue($_SESSION['oasis_logged'][0]['cd_profissional']);

		$cd_objeto = new Base_Form_Element_Select('cd_objeto_historico_continuado', array('class'=>'span-4 float-l'));
		$cd_objeto->setLabel(Base_Util::getTranslator('L_VIEW_OBJETO').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_objeto->addMultiOptions($arrObjetoContrato);

		$cd_projeto_continuado = new Base_Form_Element_Select('cd_projeto_continuado_historico_continuado', array('class'=>'span-4 float-l'));
		$cd_projeto_continuado->setLabel(Base_Util::getTranslator('L_VIEW_PROJETO_CONTINUO').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
		->setRegisterInArrayValidator(false)
		->setRegisterInArrayValidator(false) 
		->setRequired(true);

		$cd_modulo_continuado = new Base_Form_Element_Select('cd_modulo_continuado_historico_continuado', array('class'=>'span-4 float-l'));
		$cd_modulo_continuado->setLabel(Base_Util::getTranslator('L_VIEW_MODULO_CONTINUO').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right gapLeft'))
		->setRegisterInArrayValidator(false)
		->setRegisterInArrayValidator(false)
		->setRequired(true);

		$cd_etapa = new Base_Form_Element_Select('cd_etapa', array('class'=>'span-4 float-l'));
		$cd_etapa->setLabel(Base_Util::getTranslator('L_VIEW_ETAPA').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
		->setRegisterInArrayValidator(false)
		->setRegisterInArrayValidator(false)
		->setRequired(true);

		$cd_atividade = new Base_Form_Element_Select('cd_atividade', array('class'=>'span-4 float-l'));
		$cd_atividade->setLabel(Base_Util::getTranslator('L_VIEW_ATIVIDADE').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right gapLeft'))
		->setRegisterInArrayValidator(false)
		->setRegisterInArrayValidator(false)
		->setRequired(true);

		$cd_historico_proj_continuado = new Base_Form_Element_Hidden('cd_historico_proj_continuado');

		$dt_inicio_hist_proj_continuado = new Base_Form_Element_Data('dt_inicio_hist_proj_continuado');
		$dt_inicio_hist_proj_continuado->setLabel(Base_Util::getTranslator('L_VIEW_DATA_INICIAL').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right float-l clear-l'))
		->addDecorator('HtmlTag',array('class'=>'span-5 float-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$dt_fim_hist_projeto_continuado = new Base_Form_Element_Data('dt_fim_hist_projeto_continuado');
		$dt_fim_hist_projeto_continuado->setLabel(Base_Util::getTranslator('L_VIEW_DATA_FINAL').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_hist_projeto_continuado = new Base_Form_Element_EditorHtml('tx_hist_projeto_continuado', array('class'=>'float-l','cols'=>'56','rows'=>'13','editor'=>K_EDITOR));
		$tx_hist_projeto_continuado->setLabel(Base_Util::getTranslator('L_VIEW_HISTORICO').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l', 'editor'=>'editor'))
		->setRequired(true)
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$submit = new Base_Form_Element_Button('submit');
		$submit->setAttrib('id', 'submitbuttonHistoricoProjetoContinuado');
		$submit->setAttrib('class', 'verde');

		$this->addElements(array(
		$cd_profissional,
		$cd_historico_proj_continuado,
		$cd_objeto,
		$cd_projeto_continuado,
		$cd_modulo_continuado,
		$cd_etapa,
		$cd_atividade,
		$dt_inicio_hist_proj_continuado,
		$dt_fim_hist_projeto_continuado,
		$tx_hist_projeto_continuado,
		$submit));
	}
}