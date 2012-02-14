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

class MedidaForm extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	private function generate()
	{
		$this->setName('medida');
        $this->addDecorator('FormElements')
        ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-20'))
        ->addDecorator('Form');
        
		$cd_medida       = new Base_Form_Element_Hidden('cd_medida');
		
		$tx_medida = new Base_Form_Element_Text('tx_medida', array('class'=>'span-10 flat-l'));
		$tx_medida->setLabel(Base_Util::getTranslator('L_VIEW_MEDIDA').':')
		->addDecorator('Label', array('class'=>'span-4 right float-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$tx_objetivo_medida = new Base_Form_Element_EditorHtml('tx_objetivo_medida', array('class'=>'float-l','cols'=>'56','rows'=>'13','editor'=>K_EDITOR));
        $tx_objetivo_medida->setLabel(Base_Util::getTranslator('L_VIEW_OBJETIVO_MEDIDA').':')
        ->addDecorator('Label', array('class'=>'span-4 float-l right clear-l', 'editor'=>'editor'))
        ->setRequired(true)
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty');
		
		$submit = new Base_Form_Element_Button('submit');
		$submit->setAttrib('id', 'submitbutton');
		$submit->setAttrib('class', 'verde buttonBar');
		
		$this->addElements(array($cd_medida,
								 $tx_medida,
								 $tx_objetivo_medida,
								 $submit));
	}
}