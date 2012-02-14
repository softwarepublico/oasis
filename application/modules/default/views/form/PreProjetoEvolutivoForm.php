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

class PreProjetoEvolutivoForm extends Zend_Form
{

	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	
	private function generate()
	{
		$this->setName('pre_projeto_evolutivo');
		$this->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-21'));
		
		$cd_contrato = $_SESSION["oasis_logged"][0]["cd_contrato"];
		$projeto     = new ContratoProjeto();
		$arrProjeto  = $projeto->listaProjetosContrato($cd_contrato, true);
		
		$cd_pre_projeto_evolutivo = new Base_Form_Element_Hidden('cd_pre_projeto_evolutivo');
		
		$cd_projeto = new Base_Form_Element_Select('cd_projeto', array('class'=>'span-5 float-l'));
		$cd_projeto->setLabel(Base_Util::getTranslator('L_VIEW_PROJETO').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_projeto->addMultiOptions($arrProjeto);		
		
		$tx_pre_projeto_evolutivo = new Base_Form_Element_Text('tx_pre_projeto_evolutivo', array('class'=>'span-5 float-l'));
		$tx_pre_projeto_evolutivo->setLabel(Base_Util::getTranslator('L_VIEW_PRE_PROJETO_EVOLUTIVO').':')
		->addDecorator('Label', array('class'=>'span-5 right float-l clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');		

		$tx_objetivo_pre_proj_evol = new Base_Form_Element_Textarea('tx_objetivo_pre_proj_evol', array('class'=>'span-15 float-l height-3'));
		$tx_objetivo_pre_proj_evol->setLabel(Base_Util::getTranslator('L_VIEW_OBJETIVO_PRE_PROJETO_EVOLUTIVO').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');		

		$this->addElements(array(
								$cd_pre_projeto_evolutivo, 
								$cd_projeto,
								$tx_pre_projeto_evolutivo,
								$tx_objetivo_pre_proj_evol
								));
	}
}