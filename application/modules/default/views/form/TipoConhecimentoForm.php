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

class TipoConhecimentoForm extends Zend_Form
{

	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	
	private function generate()
	{
		$this->setName('tipo_conhecimento');
		$this ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-14'));
		
		$cd_tipo_conhecimento = new Base_Form_Element_Hidden('cd_tipo_conhecimento');
		$tx_tipo_conhecimento = new Base_Form_Element_Text('tx_tipo_conhecimento', array('class'=>'float-l span-10'));
		$tx_tipo_conhecimento->setLabel(Base_Util::getTranslator('L_VIEW_TIPO_CONHECIMENTO').':')
                             ->addDecorator('Label', array('class'=>'span-4 right float-l'))
                             ->setRequired(true)
                             ->addFilter('StripTags')
                             ->addFilter('StringTrim')
                             ->addValidator('NotEmpty');

        $st_para_profissionais = new Base_Form_Element_Checkbox('st_para_profissionais', array('class'=>'float-l '));
		$st_para_profissionais->setLabel(Base_Util::getTranslator('L_VIEW_CONHECIMENTO_PROFISSIONAL').':')
		->addDecorator('Label', array('class'=>'span-4 float-l clear-l right'))
		->addFilter('StripTags')
		->addFilter('StringTrim');
		$st_para_profissionais->setCheckedValue('S');
		$st_para_profissionais->setUncheckedValue(null);

		$submit = new Base_Form_Element_Button('submit');
		$submit->setAttrib('id', 'submitbutton');
		$submit->setAttrib('class', 'verde buttonBar');

		$this->addElements(array($cd_tipo_conhecimento, $tx_tipo_conhecimento, $st_para_profissionais, $submit));
	}
}