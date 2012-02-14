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

class ContatoEmpresaForm extends Zend_Form
{

	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	
	private function generate()
	{
		
//		$empresa    = new Empresa();
//		$arrEmpresa = $empresa->getEmpresa(true);
	
		$this->setName('form_contato_empresa');
        $this->addDecorator('FormElements')
        ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-12'))
        ->addDecorator('Form');		

		$cd_empresa = new Base_Form_Element_Select('cd_empresa_contato_empresa', array('class'=>'span-8 float-l'));
		$cd_empresa->setLabel(Base_Util::getTranslator('L_VIEW_EMPRESA').':')
		->addDecorator('Label', array('class'=>'span-3 float-l clear-l right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
//		$cd_empresa->addMultiOptions($arrEmpresa);
		
		$cd_contato_empresa = new Base_Form_Element_Hidden('cd_contato_empresa');
		$cd_contato_empresa->setValue('1');
		$tx_contato_empresa = new Base_Form_Element_Text('tx_contato_empresa', array('class'=>'span-8 float-l'));
		$tx_contato_empresa->setLabel(Base_Util::getTranslator('L_VIEW_CONTATO').':')
		->addDecorator('Label', array('class'=>'span-3 float-l clear-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_telefone_contato = new Base_Form_Element_Telefone('tx_telefone_contato', array('class'=>'span-3 float-l'));
		$tx_telefone_contato->setLabel(Base_Util::getTranslator('L_VIEW_TELEFONE').':')
		->addDecorator('Label', array('class'=>'span-3 float-l clear-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_celular_contato = new Base_Form_Element_Telefone('tx_celular_contato', array('class'=>'span-3 gapLeft float-l'));
		$tx_celular_contato->setLabel(Base_Util::getTranslator('L_VIEW_CELULAR').':')
		->addDecorator('Label', array('class'=>'span-2 float-l right'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_email_contato = new Base_Form_Element_Text('tx_email_contato', array('class'=>'span-8 float-l'));
		$tx_email_contato->setLabel(Base_Util::getTranslator('L_VIEW_EMAIL').':')
		->addDecorator('Label', array('class'=>'span-3 float-l clear-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$tx_obs_contato = new Base_Form_Element_Textarea('tx_obs_contato', array('class'=>'span-8 float-l height-6'));
		$tx_obs_contato->setLabel(Base_Util::getTranslator('L_VIEW_OBSERVACAO').':')
		->addDecorator('Label', array('class'=>'span-3 float-l clear-l right'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');


		$salvar = new Base_Form_Element_Button('salvar');
		$salvar->setAttrib('id', 'btn_salvar_contato_empresa');
		$salvar->setAttrib('class', 'float-l clear-l push-3 verde');
		
		$alterar = new Base_Form_Element_Button('alterar');
		$alterar->setAttrib('id', 'btn_alterar_contato_empresa');
		$alterar->setAttrib('class', 'float-l clear-l push-3 azul hide');

        $cancelar = new Base_Form_Element_Button('cancelar');
		$cancelar->setAttrib('id', 'btn_cancelar_contato_empresa');
		$cancelar->setAttrib('class', 'float-l push-3 vermelho hide');
		
		$this->addElements(array( $cd_empresa,
		                          $cd_contato_empresa,
		                          $tx_contato_empresa,
		                          $tx_telefone_contato,
		                          $tx_celular_contato,
		                          $tx_email_contato,
		                          $tx_obs_contato,
		                          $salvar,
		                          $alterar,
		                          $cancelar
		                          ));
	}
}