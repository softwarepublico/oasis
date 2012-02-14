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

class ModuloContinuadoForm extends Zend_Form
{

	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	
	private function generate()
	{

		$this->setName('modulo_continuado');
		$this->addDecorator('FormElements')
		->addDecorator('HtmlTag',array('tag'=>'div','class'=>'span-15'))
		->addDecorator('Form');
	
		$objetoContrato    = new ObjetoContrato();
		$arrObjetoContrato = $objetoContrato->getObjetoContratoAtivo('D', true, false);
	
		$projetoContinuado    = new ProjetoContinuado();
		$arrProjetoContinuado = $projetoContinuado->getProjetoContinuado();

		$cd_objeto = new Base_Form_Element_Select('cd_objeto_modulo_continuado', array('class'=>'span-4'));
		$cd_objeto->setLabel(Base_Util::getTranslator('L_VIEW_OBJETO').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_objeto->addMultiOptions($arrObjetoContrato);
		
		$cd_projeto_continuado = new Base_Form_Element_Select('cd_projeto_continuado_modulo_continuado', array('class'=>'span-4 float-l'));
		$cd_projeto_continuado->setLabel(Base_Util::getTranslator('L_VIEW_PROJETO_CONTINUO').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right clear-l'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);

		$cd_modulo_continuado = new Base_Form_Element_Hidden('cd_modulo_continuado');
		$tx_modulo_continuado = new Base_Form_Element_Text('tx_modulo_continuado', array('class'=>'span-4 float-l'));
		$tx_modulo_continuado->setLabel(Base_Util::getTranslator('L_VIEW_MODULO_CONTINUO').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$submit = new Base_Form_Element_Button('submit');
		$submit->setAttrib('id', 'submitbuttonModuloContinuado');
		$submit->setAttrib('class', 'verde');
		$this->addElements(array(
								$cd_objeto, 
								$cd_projeto_continuado,
								$cd_modulo_continuado, 
								$tx_modulo_continuado, 
								$submit));
	}
}