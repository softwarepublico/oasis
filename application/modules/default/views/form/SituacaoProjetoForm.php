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

class SituacaoProjetoForm extends Zend_Form
{

	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	
	private function generate()
	{
		$this->setName('situacao_projeto');
		
		$projeto    = new Projeto();
		$arrProjeto = $projeto->getProjeto(true);
		
		$cd_situacao_projeto     = new Zend_Form_Element_Hidden('cd_situacao_projeto');

		$cd_projeto = new Zend_Form_Element_Select('cd_projeto');
		$cd_projeto->setLabel(Base_Util::getTranslator('L_VIEW_PROJETO'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_projeto->addMultiOptions($arrProjeto);
		
		$ni_mes_situacao_projeto = new Zend_Form_Element_MesCombo('ni_mes_situacao_projeto');
		$ni_mes_situacao_projeto->setLabel(Base_Util::getTranslator('L_VIEW_MES'))
		->setRequired(true);
		
		$ni_ano_situacao_projeto = new Zend_Form_Element_AnoCombo('ni_ano_situacao_projeto');
		$ni_ano_situacao_projeto->setLabel(Base_Util::getTranslator('L_VIEW_ANO'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		
		$tx_situacao_projeto = new Zend_Form_Element_Textarea('tx_situacao_projeto');
		$tx_situacao_projeto->setLabel(Base_Util::getTranslator('L_VIEW_SITUACAO'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$submit = new Zend_Form_Element_Button('submit');
		$submit->setAttrib('id', 'submitbutton');
		$submit->setAttrib('class', 'verde buttonBar');

		$this->addElements(array(
								$cd_situacao_projeto, 
								$cd_projeto,
								$ni_mes_situacao_projeto,
								$ni_ano_situacao_projeto,
								$tx_situacao_projeto,
								$submit));
	}
}