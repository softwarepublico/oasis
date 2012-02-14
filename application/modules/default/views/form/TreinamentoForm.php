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

class TreinamentoForm extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	private function generate()
	{
		$this->setName('treinamento');
		$this->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-20'));
		
		$cd_treinamento = new Base_Form_Element_Hidden('cd_treinamento');
		$tx_treinamento = new Base_Form_Element_Text('tx_treinamento', array('class'=>'span-12 float-l'));
		$tx_treinamento->setLabel(Base_Util::getTranslator('L_VIEW_TREINAMENTO').':')
                       ->addDecorator('Label', array('class'=>'span-3 right float-l'))
                       ->setRequired(true)
                       ->addFilter('StripTags')
                       ->addFilter('StringTrim')
                       ->addValidator('NotEmpty');
		
		$tx_obs_treinamento = new Base_Form_Element_Textarea('tx_obs_treinamento', array('class'=>'span-12 height-4 float-l'));
		$tx_obs_treinamento->setLabel(Base_Util::getTranslator('L_VIEW_OBSERVACAO').':')
                           ->addDecorator('Label', array('class'=>'float-l span-3 right'))
                           ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-22 float-l clear gap-1'))
                           ->addFilter('StripTags')
                           ->addFilter('StringTrim')
                           ->addValidator('NotEmpty');

		$submit = new Base_Form_Element_Button('Salvar');
		$submit->setAttrib('id', 'btn_salvar_treinamento')
               ->setAttrib('class', 'verde buttonBar');

		$this->addElements(array($cd_treinamento, $tx_treinamento, $tx_obs_treinamento, $submit));
	}
}