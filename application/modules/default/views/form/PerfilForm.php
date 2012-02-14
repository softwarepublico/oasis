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

class PerfilForm extends Zend_Form
{

	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	
	private function generate()
	{
		
		$this->setName('perfil');
		$this->addDecorator('FormElements')
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-20'))
		->addDecorator('Form');
		
		$cd_perfil = new Base_Form_Element_Hidden('cd_perfil');
		$tx_perfil = new Base_Form_Element_Text('tx_perfil', array('class'=>'span-4 float-l'));
		$tx_perfil->setLabel(Base_Util::getTranslator('L_VIEW_PERFIL').':')
		->addDecorator('Label', array('class'=>'span-1 float-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$submit = new Base_Form_Element_Button('submit');
		$submit->setAttrib('id', 'submitbutton');
		$submit->setAttrib('class', 'float-l clear-l push-1 verde');
		
		$alterar = new Base_Form_Element_Button('alterar');
		$alterar->setAttrib('id', 'btn_alterar_perfil');
		$alterar->setAttrib('class', 'float-l clear-l push-1 azul hide');

        $cancelar = new Base_Form_Element_Button('cancelar');
		$cancelar->setAttrib('id', 'btn_cancelar_perfil');
		$cancelar->setAttrib('class', 'float-l push-1 vermelho hide');
		
		$this->addElements(array($cd_perfil, $tx_perfil, $submit, $alterar, $cancelar));
	}
}