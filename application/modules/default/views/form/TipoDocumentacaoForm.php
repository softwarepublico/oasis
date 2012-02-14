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

class TipoDocumentacaoForm extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}

    private function generate()
	{
		$this->setName('tipo_documentacao');
		$this->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-10'));

		$cd_tipo_documentacao = new Base_Form_Element_Hidden('cd_tipo_documentacao');
		$tx_tipo_documentacao = new Base_Form_Element_Text('tx_tipo_documentacao', array('class'=>'span-5 float-l'));
		$tx_tipo_documentacao->setLabel(Base_Util::getTranslator('L_VIEW_TIPO_DOCUMENTACAO').':')
                             ->addDecorator('Label', array('class'=>'span-4 float-l right'))
                             ->setRequired(true)
                             ->addFilter('StripTags')
                             ->addFilter('StringTrim')
                             ->addValidator('NotEmpty');

		$tx_extensao_documentacao = new Base_Form_Element_Text('tx_extensao_documentacao', array('class'=>'span-5 float-l','onfocus'=>"mostraExemplo()"));
		$tx_extensao_documentacao->setLabel(Base_Util::getTranslator('L_VIEW_EXTENSAO_ARQUIVO').':')
                                 ->addDecorator('Label', array('class'=>'span-4 float-l clear-l right'))
                                 ->setRequired(true)
                                 ->addFilter('StripTags')
                                 ->addFilter('StringTrim')
                                 ->addValidator('NotEmpty');

		$st_classificacao = new Base_Form_Element_Select('st_classificacao');
		$st_classificacao->setLabel(Base_Util::getTranslator('L_VIEW_CLASSIFICACAO').':')
                         ->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
                         ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'float-l span-15'))
                         ->addFilter('StripTags')
                         ->addFilter('StringTrim')
                         ->addValidator('NotEmpty');
		$st_classificacao->addMultiOption('0', Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE'));
		$st_classificacao->addMultiOption('P', Base_Util::getTranslator('L_VIEW_COMBO_PROJETO'));
		$st_classificacao->addMultiOption('R', Base_Util::getTranslator('L_VIEW_COMBO_PROFISSIONAL'));
		$st_classificacao->addMultiOption('T', Base_Util::getTranslator('L_VIEW_COMBO_ITEM_TESTE'));
		$st_classificacao->addMultiOption('C', Base_Util::getTranslator('L_VIEW_COMBO_CONTROLE'));
		$st_classificacao->addMultiOption('D', Base_Util::getTranslator('L_VIEW_COMBO_DISPONIBILIDADE_SERVICO'));
		$st_classificacao->addMultiOption('O', Base_Util::getTranslator('L_VIEW_COMBO_CONTRATO'));
		$st_classificacao->addMultiOption('J', Base_Util::getTranslator('L_VIEW_COMBO_PROJETO_CONTINUO'));

		$this->addElements(array($cd_tipo_documentacao, $tx_tipo_documentacao, $tx_extensao_documentacao, $st_classificacao));
	}
}