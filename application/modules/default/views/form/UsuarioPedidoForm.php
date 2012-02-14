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

class UsuarioPedidoForm extends Base_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	private function generate()
	{
		//Nome do formulario
		$this->setName('formCadastroUsuarioPedido');
		$this->addDecorator('HtmlTag', array('tag'=>'div'));

		//Combo Empresa
		$cd_unidade_usuario = new Base_Form_Element_Select('cd_unidade_usuario', array('class'=>'float-l span-9'));
		$cd_unidade_usuario->setLabel(Base_Util::getTranslator('L_VIEW_UNIDADE').':')
		->addDecorator('Label', array('class'=>'float-l span-3 right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
        
		//campo do nome do profissional
		$tx_nome_usuario = new Base_Form_Element_Text('tx_nome_usuario', array('class'=>'span-9 float-l'));
		$tx_nome_usuario->setLabel(Base_Util::getTranslator('L_VIEW_NOME').':')
		->addDecorator('Label', array('class'=>'float-l span-3 right clear-l'))
		->setAttrib('maxlength',100)
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$tx_sala_usuario = new Base_Form_Element_Text('tx_sala_usuario', array('class'=>'span-3 float-l'));
		$tx_sala_usuario->setLabel(Base_Util::getTranslator('L_VIEW_SALA').':')
		->addDecorator('Label', array('class'=>'float-l span-3 right clear-l'))
		->setRequired(true)
		->setAttrib('maxlength',50)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
        
		$tx_telefone_usuario = new Base_Form_Element_Telefone('tx_telefone_usuario', array('class'=>'gapLeft span-3 float-l'));
		$tx_telefone_usuario->setLabel(Base_Util::getTranslator('L_VIEW_TELEFONE').':')
		->addDecorator('Label', array('class'=>'float-l span-3 right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
        $tx_email_institucional = new Base_Form_Element_Text('tx_email_institucional', array('class'=>'span-9 float-l'));
		$tx_email_institucional->setLabel(Base_Util::getTranslator('L_VIEW_EMAIL_LOGIN').':')
		->addDecorator('Label', array('class'=>'float-l span-3 right clear-l'))
		->setRequired(true)
		->setAttrib('maxlength',100)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
        
        $tx_senha_acesso = new Base_Form_Element_Password('tx_senha_acesso', array('class'=>'span-5 float-l'));
		$tx_senha_acesso->setLabel(Base_Util::getTranslator('L_VIEW_SENHA').':')
		->addDecorator('Label', array('class'=>'float-l span-3 right clear-l'))
		->setRequired(true)
		->setAttribs(array('onkeypress'=>'checarCapsLock(event,$(this))','maxlength'=>15));
        
        $tx_senha_acesso_confirma = new Base_Form_Element_Password('tx_senha_acesso_confirma', array('class'=>'span-5 float-l'));
		$tx_senha_acesso_confirma->setLabel(Base_Util::getTranslator('L_VIEW_CONFIRMA').':')
		->addDecorator('Label', array('class'=>'float-l span-3 right clear-l'))
		->setRequired(true)
		->setAttribs(array('onkeypress'=>'checarCapsLock(event,$(this))','maxlength'=>15));
        
        $btnCadastrar = new Base_Form_Element_Button('btCadastrar',array('class'=>'float-l clear-l push-3 verde'));
        $btnReset     = new Base_Form_Element_Button('btReset',array('class'=>'float-l push-4 azul','type'=>'reset'));
        $btnCancelar  = new Base_Form_Element_Button('btCancelar',array('class'=>'float-l push-5 vermelho'));
        
        $btnCadastrar->setLabel(Base_Util::getTranslator('L_BTN_CADASTRAR'));
        $btnReset->setLabel(Base_Util::getTranslator('L_BTN_LIMPAR'));
        $btnCancelar->setLabel(Base_Util::getTranslator('L_BTN_CANCELAR'));
        

		$this->addElements(array(
            $cd_unidade_usuario, 
            $tx_nome_usuario, 
            $tx_sala_usuario, 
            $tx_telefone_usuario,
            $tx_email_institucional,
            $tx_senha_acesso,
            $tx_senha_acesso_confirma,
            $btnCadastrar,
            $btnReset,
            $btnCancelar
        ));
    }
}