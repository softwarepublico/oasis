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

class InformacaoTecnicaForm extends Zend_Form
{

	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	private function generate()
	{

		$this->setName('informacao_tecnica');
		 $this->addDecorator('FormElements')
        ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-12'))
        ->addDecorator('Form');
	
		$projeto    = new Projeto();
		$arrProjeto = $projeto->getProjeto(true);

		$tipodadotecnico    = new TipoDadoTecnico();
		$arrTipoDadoTecnico = $tipodadotecnico->getTipoDadoTecnico(true);

		$cd_projeto = new Base_Form_Element_Select('cd_projeto', array('class'=>'span-5 '));
		$cd_projeto->setLabel(Base_Util::getTranslator('L_VIEW_PROJETO').':')
		->addDecorator('Label',array('class'=>'span-4 float-l clear-l right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_projeto->addMultiOptions($arrProjeto);

		$cd_tipo_dado_tecnico = new Base_Form_Element_Select('cd_tipo_dado_tecnico', array('class'=>'span-5 '));
		$cd_tipo_dado_tecnico->setLabel(Base_Util::getTranslator('L_VIEW_TIPO_DADO_TECNICO').':')
		->addDecorator('Label',array('class'=>'span-4 float-l clear-l right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_tipo_dado_tecnico->addMultiOptions($arrTipoDadoTecnico);

		
		$tx_conteudo_informacao_tecnica = new Base_Form_Element_Text('tx_conteudo_informacao_tecnica', array('class'=>'span-5 '));
		$tx_conteudo_informacao_tecnica->setLabel(Base_Util::getTranslator('L_VIEW_INFORMACAO_TECNICA').':')
		->addDecorator('Label',array('class'=>'span-4 float-l clear-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$submit = new Base_Form_Element_Button('submit');
		$submit->setAttrib('id', 'submitbutton');
		$submit->setAttrib('class', 'verde buttonBar');
		$this->addElements(array(
									$cd_projeto, 
									$cd_tipo_dado_tecnico,
									$tx_conteudo_informacao_tecnica, 
									$submit));
	}
}