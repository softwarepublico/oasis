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

class CondicaoSubItemMetricaForm extends Base_Form
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


		$this->setName('formCondicaoSubItemMetrica');

        $objDefinicaoMetrica = new DefinicaoMetrica();
        $arrDefinicaoMetrica = $objDefinicaoMetrica->getDefinicaoMetrica(true);

		$cd_condicao_sub_item_metrica  = new Base_Form_Element_Hidden('cd_condicao_sub_item_metrica', array('onKeyPress'=>'return soNumeros(this.value)'));
		$cd_sub_item_metrica_hidden    = new Base_Form_Element_Hidden('cd_sub_item_metrica_condicao_sub_item_hidden', array('onKeyPress'=>'return soNumeros(this.value)'));
		$cd_item_metrica_hidden        = new Base_Form_Element_Hidden('cd_item_metrica_condicao_sub_item_hidden', array('onKeyPress'=>'return soNumeros(this.value)'));
		$cd_definicao_metrica_hidden   = new Base_Form_Element_Hidden('cd_definicao_metrica_condicao_sub_item_hidden', array('onKeyPress'=>'return soNumeros(this.value)'));

        $cd_definicao_metrica = new Base_Form_Element_Select('cmb_definicao_metrica_condicao_sub_item', array('class'=>'float-l'));
		$cd_definicao_metrica->addMultiOptions($arrDefinicaoMetrica);
		$cd_definicao_metrica->setLabel(Base_Util::getTranslator('L_VIEW_METRICA').':')
		->addDecorator('Label', array('class'=>'float-l span-3 right'))
        ->setRegisterInArrayValidator(false)
        ->setRequired(true);

        $cd_item_metrica = new Base_Form_Element_Select('cmb_item_metrica_condicao_sub_item', array('class'=>'float-l'));
		$cd_item_metrica->addMultiOptions(array(0=>Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')));
		$cd_item_metrica->setLabel(Base_Util::getTranslator('L_VIEW_ITEM_METRICA').':')
		->addDecorator('Label', array('class'=>'float-l clear-l span-3 right'))
        ->setRegisterInArrayValidator(false)
        ->setRequired(true);

        $cd_sub_item_metrica = new Base_Form_Element_Select('cmb_sub_item_metrica_condicao_sub_item', array('class'=>'float-l'));
		$cd_sub_item_metrica->addMultiOptions(array(0=>Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')));
		$cd_sub_item_metrica->setLabel(Base_Util::getTranslator('L_VIEW_SUB_ITEM_METRICA').':')
		->addDecorator('Label', array('class'=>'float-l clear-l span-3 right'))
        ->setRegisterInArrayValidator(false)
        ->setRequired(true);

		$tx_condicao_sub_item_metrica = new Base_Form_Element_Text('tx_condicao_sub_item_metrica', array('class'=>'float-l span-9'));
		$tx_condicao_sub_item_metrica->setLabel(Base_Util::getTranslator('L_VIEW_CONDICAO').':')
		->addDecorator('Label', array('class'=>'clear-l float-l span-3 right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

        $ni_valor_condicao_satisfeita = new Base_Form_Element_SoNumero('ni_valor_condicao_satisfeita', array('class'=>'span-3 float-l', 'maxlength'=>'10'));
		$ni_valor_condicao_satisfeita->setLabel(Base_Util::getTranslator('L_VIEW_VALOR').':')
		->addDecorator('Label', array('class'=>'float-l clear-l span-3 right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$salvar = new Base_Form_Element_Button('salvar');
		$salvar->setAttrib('id', 'btn_salvar_condicao_sub_item_metrica');
		$salvar->setAttrib('class', 'float-l clear-l push-3 verde');

        $alterar = new Base_Form_Element_Button('alterar');
		$alterar->setAttrib('id', 'btn_alterar_condicao_sub_item_metrica');
		$alterar->setAttrib('class', 'float-l clear-l push-3 azul');
		$alterar->setAttrib('style', 'display: none;');

        $cancelar = new Base_Form_Element_Button('cancelar');
		$cancelar->setAttrib('id', 'btn_cancelar_condicao_sub_item_metrica');
		$cancelar->setAttrib('class', 'float-l push-3 vermelho');
		$cancelar->setAttrib('style', 'display: none;');

        $this->setDisableLoadDefaultDecorators(true);

		$this->addElements(array(
                                $cd_condicao_sub_item_metrica,
                                $cd_definicao_metrica_hidden,
                                $cd_item_metrica_hidden,
								$cd_sub_item_metrica_hidden,
                                
                                $cd_definicao_metrica,
								$cd_item_metrica,
                                $cd_sub_item_metrica,
                                $tx_condicao_sub_item_metrica,
                                $ni_valor_condicao_satisfeita,
								$salvar,
								$alterar,
                                $cancelar));
	}
}