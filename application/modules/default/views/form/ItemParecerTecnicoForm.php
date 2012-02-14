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

class ItemParecerTecnicoForm extends Zend_Form
{

	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	
	private function generate()
	{
		
		$this->setName('item_parecer_tecnico');
		$this->addDecorator('FormElements')
        ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-15'))
        ->addDecorator('Form');
		
		$cd_item_parecer_tecnico = new Base_Form_Element_Hidden('cd_item_parecer_tecnico');
		$tx_item_parecer_tecnico = new Base_Form_Element_Text('tx_item_parecer_tecnico', array('class'=>'span-9'));
		$tx_item_parecer_tecnico->setLabel(Base_Util::getTranslator('L_VIEW_ITEM_PARECER_TECNICO').':')
		->addDecorator('Label', array('class'=>'span-6 float-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$st_proposta = new Base_Form_Element_Checkbox('st_proposta', array('class'=>'float-l '));
		$st_proposta->setLabel(Base_Util::getTranslator('L_VIEW_USADO_PARCER_TECNICO_PROPOSTA').':')
		->addDecorator('Label', array('class'=>'span-6 float-l clear-l right'))
		->addFilter('StripTags')
		->addFilter('StringTrim');
		$st_proposta->setCheckedValue('S');
		$st_proposta->setUncheckedValue(null);

		$st_parcela = new Base_Form_Element_Checkbox('st_parcela', array('class'=>'float-l'));
		$st_parcela->setLabel(Base_Util::getTranslator('L_VIEW_USADO_PARCER_TECNICO_PARCELA').':')
		->addDecorator('Label', array('class'=>'span-6 float-l right clear-l'))	
		->addFilter('StripTags')
		->addFilter('StringTrim');
		$st_parcela->setCheckedValue('S');
		$st_parcela->setUncheckedValue(null);

		$tx_descricao = new Base_Form_Element_Textarea('tx_descricao', array('class'=>'span-9 float-l height-4'));
		$tx_descricao->setLabel(Base_Util::getTranslator('L_VIEW_DESCRICAO').':')
		->addDecorator('Label', array('class'=>'span-6 float-l clear-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');


		$submit = new Base_Form_Element_Button('submit');
//		$submit->setAttrib('cd_item_parecer_tecnico', 'submitbutton');
		$submit->setAttrib('id', 'submitbutton');
		$submit->setAttrib('class', 'verde buttonBar');
		$this->addElements(array($cd_item_parecer_tecnico,
                                 $tx_item_parecer_tecnico,
                                 $st_proposta,
                                 $st_parcela,
                                 $tx_descricao,
                                 $submit));
	}
}