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

class ProjetoContinuadoForm extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}

	private function generate()
	{
		$this->setName('projeto_continuado');
		$this->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-20'));
		
		$objetoContrato    = new ObjetoContrato();
		$arrObjetoContrato = $objetoContrato->getObjetoContratoAtivo('D', true, false);

		$cd_objeto = new Base_Form_Element_Select('cd_objeto_projeto_continuado', array('class'=>'float-l span-5'));
		$cd_objeto->setLabel(Base_Util::getTranslator('L_VIEW_OBJETO').':')
		->addDecorator('Label', array('class'=>'span-4 float-l clear-l right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_objeto->addMultiOptions($arrObjetoContrato);
		
		$cd_projeto_continuado = new Base_Form_Element_Hidden('cd_projeto_continuado');
		$tx_projeto_continuado = new Base_Form_Element_Text('tx_projeto_continuado', array('class'=>'float-l span-5'));
		$tx_projeto_continuado->setLabel(Base_Util::getTranslator('L_VIEW_PROJETO_CONTINUO').':')
		->addDecorator('Label', array('class'=>'span-4 float-l clear-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$tx_objetivo_projeto_continuado = new Base_Form_Element_EditorHtml('tx_objetivo_projeto_continuado', array('class'=>'float-l','rows'=>'13', 'cols'=>'61','editor'=>K_EDITOR));
		$tx_objetivo_projeto_continuado->setLabel(Base_Util::getTranslator('L_VIEW_OBJETIVO').':')
		->addDecorator('Label', array('class'=>'span-4 float-l clear-l right', 'editor'=>'editor'))
		->setRequired(true)
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_obs_projeto_continuado = new Base_Form_Element_Textarea('tx_obs_projeto_continuado', array('class'=>'float-l span-13 height-3'));
		$tx_obs_projeto_continuado->setLabel(Base_Util::getTranslator('L_VIEW_OBSERVACAO').':')
		->addDecorator('Label', array('class'=>'span-4 float-l clear-l right'))
		->addFilter('StripTags')
		->addFilter('StringTrim');

		$st_prioridade_proj_continuado = new Base_Form_Element_Select('st_prioridade_proj_continuado', array('class'=>'float-l span-5'));
		$st_prioridade_proj_continuado->setLabel(Base_Util::getTranslator('L_VIEW_PRIORIDADE').':')
		->addDecorator('Label', array('class'=>'span-4 float-l clear-l right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$st_prioridade_proj_continuado->addMultiOptions(Base_Util::getPrioridade());

//		$st_prioridade_proj_continuado->addMultiOption(0, Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE'));
//		$st_prioridade_proj_continuado->addMultiOption('A', Base_Util::getTranslator('L_VIEW_COMBO_ALTISSIMA'));
//		$st_prioridade_proj_continuado->addMultiOption('L', Base_Util::getTranslator('L_VIEW_COMBO_ALTA'));
//		$st_prioridade_proj_continuado->addMultiOption('M', Base_Util::getTranslator('L_VIEW_COMBO_MEDIA'));
//		$st_prioridade_proj_continuado->addMultiOption('B', Base_Util::getTranslator('L_VIEW_COMBO_BAIXA'));
		
		$submit = new Base_Form_Element_Button('submit');
		$submit->setAttrib('id', 'submitbuttonProjetoContinuado');
		$submit->setAttrib('class', 'verde');
		$this->addElements(array(
								$cd_projeto_continuado, 
								$cd_objeto, 
								$tx_projeto_continuado, 
								$tx_objetivo_projeto_continuado, 
								$tx_obs_projeto_continuado, 
								$st_prioridade_proj_continuado,
								$submit));
	}
}