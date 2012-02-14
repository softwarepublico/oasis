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

class TipoDadoTecnicoForm extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	private function generate()
	{
		$this->setName('tipo_dado_tecnico');
		$this->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-14'));
		
		$cd_tipo_dado_tecnico = new Base_Form_Element_Hidden('cd_tipo_dado_tecnico');
		$tx_tipo_dado_tecnico = new Base_Form_Element_Text('tx_tipo_dado_tecnico', array('class'=>'float-l span-10'));
		$tx_tipo_dado_tecnico->setLabel(Base_Util::getTranslator('L_VIEW_TIPO_DADO_TECNICO').':')
                             ->addDecorator('Label', array('class'=>'float-l span-4 right'))
                             ->setRequired(true)
                             ->addFilter('StripTags')
                             ->addFilter('StringTrim')
                             ->addValidator('NotEmpty');

		$submit = new Base_Form_Element_Button('submit');
		$submit->setAttrib('id', 'submitbutton');
		$submit->setAttrib('class', 'verde buttonBar');

		$this->addElements(array($cd_tipo_dado_tecnico, $tx_tipo_dado_tecnico, $submit));
	}
}