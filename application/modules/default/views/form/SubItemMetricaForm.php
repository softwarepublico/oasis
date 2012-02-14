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

class SubItemMetricaForm extends Base_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}

	private function generate()
	{
        $this->addDecorator('FormElements')
             ->addDecorator('HtmlTag', array('tag' => 'div', 'class'=>'span-21')) //insere uma <div> no <form>
             ->addDecorator('Form');

		$this->setName('subItemMetrica');

        $objDefinicaoMetrica = new DefinicaoMetrica();
        $arrDefinicaoMetrica = $objDefinicaoMetrica->getDefinicaoMetrica(true);

		$cd_sub_item_metrica         = new Base_Form_Element_Hidden('cd_sub_item_metrica');
		$cd_item_metrica_hidden      = new Base_Form_Element_Hidden('cd_item_metrica_hidden');
		$cd_definicao_metrica_hidden = new Base_Form_Element_Hidden('cd_definicao_metrica_sub_item_hidden');

        $cd_definicao_metrica = new Base_Form_Element_Select('cmb_definicao_metrica_sub_item', array('class'=>'float-l'));
		$cd_definicao_metrica->addMultiOptions($arrDefinicaoMetrica);
		$cd_definicao_metrica->setLabel(Base_Util::getTranslator('L_VIEW_METRICA').':')
		->addDecorator('Label', array('class'=>'float-l span-3 right'))
        ->setRegisterInArrayValidator(false)
        ->setRequired(true);

        $cd_item_metrica = new Base_Form_Element_Select('cmb_item_metrica_sub_item', array('class'=>'float-l'));
		$cd_item_metrica->addMultiOptions(array(0=>Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')));
		$cd_item_metrica->setLabel(Base_Util::getTranslator('L_VIEW_ITEM_METRICA').':')
		->addDecorator('Label', array('class'=>'float-l clear-l span-3 right'))
        ->setRegisterInArrayValidator(false)
        ->setRequired(true);

		$tx_sub_item_metrica = new Base_Form_Element_Text('tx_sub_item_metrica', array('class'=>'float-l span-10', 'maxlength'=>'100'));
		$tx_sub_item_metrica->setLabel(Base_Util::getTranslator('L_VIEW_NOME').':')
		->addDecorator('Label', array('class'=>'clear-l float-l span-3 right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_variavel_sub_item_metrica = new Base_Form_Element_Text('tx_variavel_sub_item_metrica', array('class'=>'span-3 float-l', 'maxlength'=>'10'));
		$tx_variavel_sub_item_metrica->setLabel(Base_Util::getTranslator('L_VIEW_VARIAVEL').':')
		->addDecorator('Label', array('class'=>' clear-l float-l span-3 right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

        $ni_ordem_sub_item_metrica = new Base_Form_Element_SoNumero('ni_ordem_sub_item_metrica', array('class'=>'span-3 float-l', 'maxlength'=>'10'));
		$ni_ordem_sub_item_metrica->setLabel(Base_Util::getTranslator('L_VIEW_NR_ORDEM').':')
		->addDecorator('Label', array('class'=>'float-l clear-l span-3 right ni_ordem_sub_item_metrica_teste'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

        $st_interno = new Base_Form_Element_Checkbox('st_interno', array('class'=>'float-l'));
		$st_interno->setLabel(Base_Util::getTranslator('L_VIEW_INTERNO').':')
		->addDecorator('Label', array('class'=>'span-3 float-l clear-l right'))
		->setCheckedValue("S")
		->addFilter('StripTags')
		->addFilter('StringTrim');

		$salvar = new Base_Form_Element_Button('salvar');
		$salvar->setAttrib('id', 'btn_salvar_sub_item_metrica');
		$salvar->setAttrib('class', 'float-l clear-l push-3 verde');

        $alterar = new Base_Form_Element_Button('alterar');
		$alterar->setAttrib('id', 'btn_alterar_sub_item_metrica');
		$alterar->setAttrib('class', 'float-l clear-l push-3 azul');
		$alterar->setAttrib('style', 'display: none;');

        $cancelar = new Base_Form_Element_Button('cancelar');
		$cancelar->setAttrib('id', 'btn_cancelar_sub_item_metrica');
		$cancelar->setAttrib('class', 'float-l push-3 vermelho');
		$cancelar->setAttrib('style', 'display: none;');

        $this->setDisableLoadDefaultDecorators(true);

		$this->addElements(array(
								$cd_sub_item_metrica,
                                $cd_item_metrica_hidden,
                                $cd_definicao_metrica_hidden,
                                $cd_definicao_metrica,
								$cd_item_metrica,
                                $tx_sub_item_metrica,
                                $tx_variavel_sub_item_metrica,
                                $ni_ordem_sub_item_metrica,
                                $st_interno,
								$salvar,
								$alterar,
                                $cancelar));
	}
}