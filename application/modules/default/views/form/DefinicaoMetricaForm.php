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

class DefinicaoMetricaForm extends Base_Form
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


		$this->setName('definicaoMetrica');
		$cd_definicao_metrica = new Base_Form_Element_Hidden('cd_definicao_metrica');

		$tx_nome_metrica = new Base_Form_Element_Text('tx_nome_metrica', array('class'=>'float-l span-15','maxlength'=>'100'));
		$tx_nome_metrica->setLabel(Base_Util::getTranslator('L_VIEW_NOME').':')
		->addDecorator('Label', array('class'=>' float-l span-3 right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_sigla_metrica = new Base_Form_Element_Text('tx_sigla_metrica', array('class'=>'float-l span-4','maxlength'=>'10'));
		$tx_sigla_metrica->setLabel(Base_Util::getTranslator('L_VIEW_SIGLA').':')
		->addDecorator('Label', array('class'=>'clear-l float-l span-3 right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_descricao_metrica = new Base_Form_Element_Textarea('tx_descricao_metrica', array('class'=>'span-15 height-4 float-l'));
		$tx_descricao_metrica->setLabel(Base_Util::getTranslator('L_VIEW_DESCRICAO').':')
		->addDecorator('Label', array('class'=>' clear-l float-l span-3 right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

        $tx_formula_metrica = new Base_Form_Element_Textarea('tx_formula_metrica', array('class'=>'span-15 height-4 float-l'));
		$tx_formula_metrica->setLabel(Base_Util::getTranslator('L_VIEW_FORMULA').':')
		->addDecorator('Label', array('class'=>' clear-l float-l span-3 right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_sigla_unidade_metrica = new Base_Form_Element_Text('tx_sigla_unidade_metrica', array('class'=>'float-l span-4','maxlength'=>'10'));
		$tx_sigla_unidade_metrica->setLabel(Base_Util::getTranslator('L_VIEW_SIGLA_UNIDADE_MEDIDA').':')
		->addDecorator('Label', array('class'=>'clear-l float-l span-3 right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_unidade_metrica = new Base_Form_Element_Text('tx_unidade_metrica', array('class'=>'float-l span-15'));
		$tx_unidade_metrica->setLabel(Base_Util::getTranslator('L_VIEW_UNIDADE_MEDIDA').':')
		->addDecorator('Label', array('class'=>'clear-l float-l span-3 right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

        $st_justificativa_metrica = new Base_Form_Element_Checkbox('st_justificativa_metrica', array('class'=>'float-l'));
		$st_justificativa_metrica->setLabel(Base_Util::getTranslator('L_VIEW_COM_JUSTIFICATIVA').':')
		->addDecorator('Label', array('class'=>'span-3 float-l clear-l right'))
        ->setCheckedValue("S")
		->addFilter('StripTags')
		->addFilter('StringTrim');

		$salvar = new Base_Form_Element_Button('salvar');
		$salvar->setAttrib('id', 'btn_salvar_definicao_metrica');
		$salvar->setAttrib('class', 'float-l clear-l push-3 verde');

        $alterar = new Base_Form_Element_Button('alterar');
		$alterar->setAttrib('id', 'btn_alterar_definicao_metrica');
		$alterar->setAttrib('class', 'float-l clear-l push-3 azul');
		$alterar->setAttrib('style', 'display: none;');

        $cancelar = new Base_Form_Element_Button('cancelar');
		$cancelar->setAttrib('id', 'btn_cancelar_definicao_metrica');
		$cancelar->setAttrib('class', 'float-l push-3 vermelho');
		$cancelar->setAttrib('style', 'display: none;');

        $this->setDisableLoadDefaultDecorators(true);

		$this->addElements(array(
								$cd_definicao_metrica,
								$tx_nome_metrica,
                                $tx_sigla_metrica,
                                $tx_descricao_metrica,
                                $tx_formula_metrica,
								$tx_sigla_unidade_metrica,
								$tx_unidade_metrica,
                                $st_justificativa_metrica,
								$salvar,
								$alterar,
                                $cancelar));
	}
}