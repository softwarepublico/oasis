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

class SubitemInvDescriForm extends Base_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}

	private function generate()
	{
		$objItemInventario    = new ItemInventario();
		$objSubitemInventario = new SubitemInventario();
        $objAreaAtuacao       = new AreaAtuacaoTi();

        $this->setName('subitem_inv_descri_form');
        $this->addDecorator('FormElements')
        ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-21'))
        ->addDecorator('Form');

		$cd_subitem_inv_descri = new Base_Form_Element_Hidden('cd_subitem_inv_descri');
        
        $cd_area_atuacao_ti = new Base_Form_Element_Select('cd_area_atuacao_ti', array('class'=>'float-l'));
		$cd_area_atuacao_ti->setLabel(Base_Util::getTranslator('L_VIEW_AREA_ATUACAO_TI').':')
		->addDecorator('Label', array('class'=>'span-4 float-l '))
		->setRequired(true)
		->setRegisterInArrayValidator(false)
        ->addMultiOptions($objAreaAtuacao->comboAreaAtuacaoTi(true));

		$cd_item_inventario = new Base_Form_Element_Select('cd_item_inventario', array('class'=>'span-5 float-l'));
        $cd_item_inventario->setLabel(Base_Util::getTranslator('L_VIEW_ITEM_INVENTARIO').':')
		->addDecorator('Label', array('class'=>'span-4 float-l clear-l'))
		->setRequired(true);

		$cd_subitem_inventario = new Base_Form_Element_Select('cd_subitem_inventario', array('class'=>'span-5 float-l'));
        $cd_subitem_inventario->setLabel(Base_Util::getTranslator('L_VIEW_SUBITEM_INVENTARIO').':')
		->addDecorator('Label', array('class'=>'span-4 float-l clear-l'))
		->setRequired(true);
        
		$tx_subitem_inv_descri = new Base_Form_Element_Text('tx_subitem_inv_descri', array('class'=>'span-8 float-l','maxlength'=>200));
		$tx_subitem_inv_descri->setLabel(Base_Util::getTranslator('L_VIEW_SUBITEM_INV_DESCRI').':')
		->addDecorator('Label', array('class'=>'span-4 float-l clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$ni_ordem = new Base_Form_Element_SoNumero('ni_ordem', array('class'=>'span-2 float-l','maxlength'=>10));
		$ni_ordem->setLabel(Base_Util::getTranslator('L_VIEW_NR_ORDEM').':')
		->addDecorator('Label', array('class'=>'span-4 float-l clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
        
        $salvar = new Base_Form_Element_Button('Salvar');
		$salvar->setAttrib('id', 'btn_salvar_subitem_inv_descri');
		$salvar->setAttrib('class', 'verde buttonBar');
        

		$this->addElements(array($cd_subitem_inv_descri,
                                 $cd_area_atuacao_ti,
                                 $cd_item_inventario,
                                 $cd_subitem_inventario,
                                 $tx_subitem_inv_descri,
                                 $ni_ordem,
                                 $salvar
            ));
	}
}