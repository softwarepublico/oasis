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

class NivelServicoForm extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
		
	private function generate()
	{
		
		$objetoContrato    = new ObjetoContrato();
		$arrObjetoContrato = $objetoContrato->getObjetoContratoAtivo("D", true, false);
		
	
		$this->setName('nivel_servico');
		$this->addDecorator('FormElements')
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-15'))
		->addDecorator('Form');

		$cd_objeto = new Base_Form_Element_Select('cd_objeto_nivel_servico', array('class'=>'span-4'));
		$cd_objeto->setLabel(Base_Util::getTranslator('L_VIEW_OBJETO').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_objeto->addMultiOptions($arrObjetoContrato);
		
		$cd_nivel_servico = new Base_Form_Element_Hidden('cd_nivel_servico');
		$tx_nivel_servico = new Base_Form_Element_Text('tx_nivel_servico', array('class'=>'span-4 float-l'));
		$tx_nivel_servico->setLabel(Base_Util::getTranslator('L_VIEW_NIVEL_SERVICO').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$st_nivel_servico = new Base_Form_Element_Select('st_nivel_servico', array('class'=>'span-4'));
		$st_nivel_servico->setLabel(Base_Util::getTranslator('L_VIEW_CLASSIFICACAO').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$st_nivel_servico->addMultiOption('1', Base_Util::getTranslator('L_VIEW_COMBO_NIVEL_UM'));
		$st_nivel_servico->addMultiOption('2', Base_Util::getTranslator('L_VIEW_COMBO_NIVEL_DOIS'));
		
		$ni_horas_prazo_execucao = new Base_Form_Element_SoNumero('ni_horas_prazo_execucao', array('class'=>'span-4 float-l'));
		$ni_horas_prazo_execucao->setLabel(Base_Util::getTranslator('L_VIEW_PRAZO_EXECUCAO_HORAS').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$this->addElements(array($cd_objeto, $cd_nivel_servico, $tx_nivel_servico, $ni_horas_prazo_execucao));
	}
}