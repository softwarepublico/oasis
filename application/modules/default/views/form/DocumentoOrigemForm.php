<?php
/**
 * @Copyright Copyright 2012 Hudson Carrano Filho, Brasil.
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

class DocumentoOrigemForm extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	private function generate()
	{
		$this->setName('docorigem');
		$this->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-20'));
		
		$cd_documento_origem = new Base_Form_Element_Hidden('cd_documento_origem');
		$tx_documento_origem = new Base_Form_Element_Text('tx_documento_origem', array('class'=>'span-12 float-l'));
		$tx_documento_origem->setLabel(Base_Util::getTranslator('L_VIEW_DOCUMENTO_ORIGEM').':')
                       ->addDecorator('Label', array('class'=>'span-4 right float-l'))
                       ->setRequired(true)
                       ->addFilter('StripTags')
                       ->addFilter('StringTrim')
                       ->addValidator('NotEmpty');
        
        $dt_documento_origem = new Base_Form_Element_Data('dt_documento_origem');
		$dt_documento_origem->setLabel(Base_Util::getTranslator('L_VIEW_DATA_DOCUMENTO').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right float-l clear-l'))
		->addDecorator('HtmlTag',array('class'=>'span-5 float-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');	
		
		$tx_obs_documento_origem = new Base_Form_Element_Textarea('tx_obs_documento_origem', array('class'=>'span-12 height-4 float-l'));
		$tx_obs_documento_origem->setLabel(Base_Util::getTranslator('L_VIEW_OBSERVACAO').':')
                           ->addDecorator('Label', array('class'=>'float-l span-4 right'))
                           ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-22 float-l clear gap-1'))
                           ->addFilter('StripTags')
                           ->addFilter('StringTrim')
                           ->addValidator('NotEmpty');

		$submit = new Base_Form_Element_Button('Salvar');
		$submit->setAttrib('id', 'btn_salvar_documento_origem')
               ->setAttrib('class', 'verde buttonBar');

		$this->addElements(array($cd_documento_origem, $tx_documento_origem,$dt_documento_origem, $tx_obs_documento_origem, $submit));
	}
}