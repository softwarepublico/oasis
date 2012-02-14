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

class ItemTesteForm extends Zend_Form
{
    private $arrTipoItemTeste;
    
	public function __construct($options = null,array $arrTipoItemTeste=array())
	{
		parent::__construct($options);

		if( empty($arrTipoItemTeste) ){
		    throw new Exception('error loading the combo type of test. form: ItemTesteForm()');
		}
		$this->arrTipoItemTeste = $arrTipoItemTeste;
		$this->generate();
	}


	private function generate()
	{

		$this->setName('item_teste');
		$this->addDecorator('FormElements')
        ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-21'))
        ->addDecorator('Form');

		$cd_item_teste = new Base_Form_Element_Hidden('cd_item_teste');


		$st_tipo_item_teste = new Base_Form_Element_Select('st_tipo_item_teste', array('class'=>'float-l'));
        $st_tipo_item_teste->setLabel(Base_Util::getTranslator('L_VIEW_TIPO_TESTE').':')
        ->addDecorator('Label', array('class'=>'float-l span-5 right'))
        ->setRequired(true)
        ->setRegisterInArrayValidator(false)
        ->addMultiOptions( $this->arrTipoItemTeste ); //vem do controller...

		$ni_ordem_item_teste = new Base_Form_Element_SoNumero('ni_ordem_item_teste', array('class'=>'float-l span-1'));
		$ni_ordem_item_teste->setLabel(Base_Util::getTranslator('L_VIEW_ORDEM_ITEM_TESTE').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_item_teste = new Base_Form_Element_Text('tx_item_teste', array('class'=>'float-l span-10'));
		$tx_item_teste->setLabel(Base_Util::getTranslator('L_VIEW_ITEM_TESTE').':')
        ->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty');

		$st_item_teste = new Base_Form_Element_Checkbox('st_item_teste', array('class'=>'float-l'));
		$st_item_teste->setLabel(Base_Util::getTranslator('L_VIEW_INATIVO').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim');
		$st_item_teste->setCheckedValue('I');
		$st_item_teste->setUncheckedValue('A');

		$st_obrigatorio = new Base_Form_Element_Checkbox('st_obrigatorio', array('class'=>'float-l'));
		$st_obrigatorio->setLabel(Base_Util::getTranslator('L_VIEW_OBRIGATORIO').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim');
		$st_obrigatorio->setCheckedValue('S');
		$st_obrigatorio->setUncheckedValue(null);

		$submit = new Base_Form_Element_Button('submit');
		$submit->setAttrib('id', 'submitbutton');
		$submit->setAttrib('class', 'verde buttonBar');

		$new = new Base_Form_Element_Button('Novo');
		$new->setAttrib('id', 'newbutton');
		$new->setAttrib('class', 'azul buttonBar hide');

		$this->addElements(array($st_tipo_item_teste,
                                 $cd_item_teste,
                                 $ni_ordem_item_teste,
                                 $tx_item_teste,
                                 $st_item_teste,
                                 $st_obrigatorio,
                                 $submit,
                                 $new));
	}
}