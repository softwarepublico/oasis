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

class PerfilProfissionalForm extends Zend_Form
{

	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	private function generate()
	{
		$this->setName('form_perfil_profissional');
		$this->addDecorator('FormElements')
        ->addDecorator('Form');

		$cd_area_atuacao = new Base_Form_Element_Select('cd_area_atuacao_perfil_profissional', array('class'=>'span-5 float-l'));
		$cd_area_atuacao->setLabel(Base_Util::getTranslator('L_VIEW_AREA_ATUACAO').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		
		$cd_perfil_profissional = new Base_Form_Element_Hidden('cd_perfil_profissional');
		$tx_perfil_profissional = new Base_Form_Element_Text('tx_perfil_profissional', array('class'=>'span-5 float-l'));
		$tx_perfil_profissional->setLabel(Base_Util::getTranslator('L_VIEW_PERFIL_PROFISSIONAL').':')
		->addDecorator('Label', array('class'=>'span-3 float-l clear-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$salvar = new Base_Form_Element_Button('salvar');
		$salvar->setAttrib('id', 'btn_salvar_perfil_profissional');
		$salvar->setAttrib('class', 'float-l clear-l push-3 verde');
		
		$alterar = new Base_Form_Element_Button('alterar');
		$alterar->setAttrib('id', 'btn_alterar_perfil_profissional');
		$alterar->setAttrib('class', 'float-l clear-l push-3 azul hide');
		
		$cancelar = new Base_Form_Element_Button('cancelar');
		$cancelar->setAttrib('id', 'btn_cancelar_perfil_profissional');
		$cancelar->setAttrib('class', 'float-l push-3 vermelho hide');
		
		$this->addElements(array($cd_area_atuacao,
								 $cd_perfil_profissional,
								 $tx_perfil_profissional,
								 $salvar,
								 $alterar,
								 $cancelar));
	}
}