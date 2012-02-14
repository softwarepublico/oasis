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

class EmpresaForm extends Zend_Form
{

	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	
	private function generate()
	{
		
		$this->setName('formulario_empresa');
		$this->addDecorator('FormElements')
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-13'))
        ->addDecorator('Form');
		
		$cd_empresa = new Base_Form_Element_Hidden('cd_empresa');
		
		$tx_empresa = new Base_Form_Element_Text('tx_empresa', array('class'=>'span-10 float-l'));
		$tx_empresa->setLabel(Base_Util::getTranslator('L_VIEW_EMPRESA').':')
		->addDecorator('Label', array('class'=>'span-2 float-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_cnpj_empresa = new Base_Form_Element_Cnpj('tx_cnpj_empresa', array('class'=>'span-4 float-l'));
		$tx_cnpj_empresa->setLabel(Base_Util::getTranslator('L_VIEW_CNPJ').':')
		->addDecorator('Label',array('class'=>'span-2 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_endereco_empresa = new Base_Form_Element_Text('tx_endereco_empresa', array('class'=>'span-10 float-l'));
		$tx_endereco_empresa->setLabel(Base_Util::getTranslator('L_VIEW_ENDERECO').':')
		->addDecorator('Label',array('class'=>'span-2 float-l right clear-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$tx_telefone_empresa = new Base_Form_Element_Telefone('tx_telefone_empresa', array('class'=>'span-3 float-l'));
		$tx_telefone_empresa->setLabel(Base_Util::getTranslator('L_VIEW_TELEFONE').':')
		->addDecorator('Label',array('class'=>'span-2 float-l right clear-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$tx_fax_empresa = new Base_Form_Element_Telefone('tx_fax_empresa', array('class'=>'span-3 float-l'));
		$tx_fax_empresa->setLabel(Base_Util::getTranslator('L_VIEW_FAX').':')
		->addDecorator('Label',array('class'=>'span-2 float-l right clear-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$tx_email_empresa = new Base_Form_Element_Text('tx_email_empresa', array('class'=>'span-10 float-l'));
		$tx_email_empresa->setLabel(Base_Util::getTranslator('L_VIEW_EMAIL').':')
		->addDecorator('Label',array('class'=>'span-2 float-l right clear-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$salvar = new Base_Form_Element_Button('salvar');
		$salvar->setAttrib('id', 'btn_salvar_empresa');
		$salvar->setAttrib('class', 'float-l clear-l push-2 verde');
		
		$alterar = new Base_Form_Element_Button('alterar');
		$alterar->setAttrib('id', 'btn_alterar_empresa');
		$alterar->setAttrib('class', 'float-l clear-l push-2 azul hide');

        $cancelar = new Base_Form_Element_Button('cancelar');
		$cancelar->setAttrib('id', 'btn_cancelar_empresa');
		$cancelar->setAttrib('class', 'float-l push-2 vermelho hide');
		
		
		$this->addElements(array( $cd_empresa,
		                          $tx_empresa,
		                          $tx_cnpj_empresa, 
		                          $tx_endereco_empresa,
		                 		  $tx_email_empresa,          
		                          $tx_telefone_empresa,
		                          $tx_fax_empresa,
		                          $salvar,
		                          $alterar,
		                          $cancelar
		                         ));
	}
}