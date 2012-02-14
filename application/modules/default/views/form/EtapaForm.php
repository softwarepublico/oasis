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

class EtapaForm extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	private function generate()
	{
        $objAreaAtuacao = new AreaAtuacaoTi();

		$this->setName('etapa');
		$this->addDecorator('FormElements')
        ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-8'))
        ->addDecorator('Form');

		$cd_area_atuacao_ti = new Base_Form_Element_Select('cd_area_atuacao_ti_etapa', array('class'=>'float-l'));
		$cd_area_atuacao_ti->setLabel(Base_Util::getTranslator('L_VIEW_AREA_ATUACAO_TI').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right'))
		->setRequired(true)
		->setRegisterInArrayValidator(false)
        ->addMultiOptions($objAreaAtuacao->comboAreaAtuacaoTi(true));
		
		$cd_etapa = new Base_Form_Element_Hidden('cd_etapa');
        
		$tx_etapa = new Base_Form_Element_Text('tx_etapa', array('class'=>'span-5 float-l'));
		$tx_etapa->setLabel(Base_Util::getTranslator('L_VIEW_ETAPA').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$tx_descricao_etapa = new Base_Form_Element_Textarea('tx_descricao_etapa', array('class'=>'span-14 height-4 float-l'));
		$tx_descricao_etapa->setLabel(Base_Util::getTranslator('L_VIEW_DESCRICAO').':')
		->addDecorator('Label', array('class'=>'float-l span-3 right clear-l'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-22 float-l clear gap-1'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');				

		$ni_ordem_etapa = new Base_Form_Element_SoNumero('ni_ordem_etapa', array('class'=>'span-5 float-l'));
		$ni_ordem_etapa->setLabel(Base_Util::getTranslator('L_VIEW_NR_ORDEM').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$this->addElements(array($cd_area_atuacao_ti, $cd_etapa, $tx_etapa, $ni_ordem_etapa, $tx_descricao_etapa));
	}
}