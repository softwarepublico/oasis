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

class PenalidadeForm extends Zend_Form
{

	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	private function generate()
	{
		$this->setName('penalidade');
		$this->addDecorator('FormElements')
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-20'))
		->addDecorator('Form');

		$cd_contrato = new Base_Form_Element_Select('cd_contrato_penalidade', array('class'=>'span-4 float-l'));
		$cd_contrato->setLabel(Base_Util::getTranslator('L_VIEW_CONTRATO').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		
		$cd_penalidade = new Base_Form_Element_Hidden('cd_penalidade');
		$tx_penalidade = new Base_Form_Element_Textarea('tx_penalidade', array('class'=>'span-10 float-l height-3'));
		$tx_penalidade->setLabel(Base_Util::getTranslator('L_VIEW_PENALIDADE').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
	
		$tx_abreviacao_penalidade = new Base_Form_Element_Text('tx_abreviacao_penalidade',array('class'=>'span-10 float-l'));
		$tx_abreviacao_penalidade->setLabel(Base_Util::getTranslator('L_VIEW_DESCRICAO_RESUMIDA').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$ni_valor_penalidade = new Base_Form_Element_Moeda('ni_valor_penalidade', array('class'=>'span-4 float-l'));
		$ni_valor_penalidade->setLabel(Base_Util::getTranslator('L_VIEW_PENALIDADE').':(%)')
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$ni_penalidade = new Base_Form_Element_SoNumero('ni_penalidade', array('class'=>'span-4 float-l'));
		$ni_penalidade->setLabel(Base_Util::getTranslator('L_VIEW_NUMERO_PENALIDADE').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$st_ocorrencia = new Base_Form_Element_Checkbox('st_ocorrencia', array('class'=>'float-l'));
		$st_ocorrencia->setLabel(Base_Util::getTranslator('L_VIEW_POR_OCORRENCIA').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim');
		$st_ocorrencia->setCheckedValue('S');
		$st_ocorrencia->setUncheckedValue(null);

		$this->addElements(array(
								$cd_contrato, 
								$cd_penalidade, 
								$ni_penalidade, 
								$tx_abreviacao_penalidade, 
								$tx_penalidade, 
								$ni_valor_penalidade, 
								$st_ocorrencia));
		}
}