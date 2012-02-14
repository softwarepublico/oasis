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

class MenuForm extends Zend_Form
{

	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	private function generate()
	{
		$menu    = new Menu();
		$arrMenu = $menu->getMenu(true);
        $arrMenu[0] = ' '.$arrMenu[0];
		asort($arrMenu);
        
		$this->setName('form_menu')
        ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-10'))
        ->addDecorator('Form');		
		
		$cd_menu_pai = new Base_Form_Element_Select('cd_menu_pai', array('class'=>'span-7 float-l'));
		$cd_menu_pai->setLabel(Base_Util::getTranslator('L_VIEW_MENU_PAI'))
        ->addDecorator('Label', array('class'=>'float-l span-2 right'))
		->setRegisterInArrayValidator(false)
		->addMultiOptions($arrMenu);
		
		$cd_menu = new Base_Form_Element_Hidden('cd_menu');
		$tx_menu = new Base_Form_Element_Text('tx_menu', array('class'=>'span-4 float-l'));
		$tx_menu->setLabel(Base_Util::getTranslator('L_VIEW_MENU'))
		->addDecorator('Label', array('class'=>'float-l span-2 right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_modulo = new Base_Form_Element_Text('tx_modulo', array('class'=>'span-4 float-l'));
		$tx_modulo->setLabel(Base_Util::getTranslator('L_VIEW_MODULO'))
		->addDecorator('Label', array('class'=>'float-l span-2 right clear-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim');

		$tx_pagina = new Base_Form_Element_Text('tx_pagina', array('class'=>'span-4 float-l'));
		$tx_pagina->setLabel(Base_Util::getTranslator('L_VIEW_PAGINA'))
		->addDecorator('Label', array('class'=>'float-l span-2 right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$submit = new Base_Form_Element_Button('submit');
		$submit->setAttrib('id', 'submitbuttonMenu');
		$submit->setAttrib('class', 'verde float-l clear-l push-2');
		
		$excluir = new Base_Form_Element_Button('bt_excluir');
		$excluir->setAttrib('id', 'bt_excluir');
		$excluir->setAttrib('class', 'vermelho float-l push-2 hide');
		$excluir->setLabel(Base_Util::getTranslator('L_BTN_EXCLUIR'));
		
		$this->addElements(array($cd_menu_pai, $cd_menu, $tx_menu, $tx_modulo, $tx_pagina, $submit, $excluir));
	}
}