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

class ProfissionalForm extends Base_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	private function generate()
	{
		//Nome do formulario
		$this->setName('form_profissional');
		$this->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-19'));

		//Criando os objetos necessários
		$objEmpresa           = new Empresa();
		$objRelacaoContratual = new RelacaoContratual();		
		$objPerfil            = new Perfil();		
		
		//Campo do tipo HIDDEN
		$cd_profissional = new Base_Form_Element_Hidden('cd_profissional');
		
		//Combo Empresa
		$arrEmpresa = $objEmpresa->getEmpresa(true);
		$cd_empresa = new Base_Form_Element_Select('cd_empresa', array('class'=>'span-5 float-l'));
		$cd_empresa->setLabel(Base_Util::getTranslator('L_VIEW_EMPRESA').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_empresa->addMultiOptions($arrEmpresa);
		
		//Combo com a relaçao contratual
		$arrRelacaoContratual = $objRelacaoContratual->getRelacaoContratual(true);
		$cd_relacao_contratual = new Base_Form_Element_Select('cd_relacao_contratual', array('class'=>'float-l span-5'));
		$cd_relacao_contratual->setLabel(Base_Util::getTranslator('L_VIEW_RELACAO_CONTRATUAL').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right gapLeft'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_relacao_contratual->addMultiOptions($arrRelacaoContratual);
		
		//campo do nome do profissional
		$tx_profissional = new Base_Form_Element_Text('tx_profissional', array('class'=>'float-l span-14'));
		$tx_profissional->setLabel(Base_Util::getTranslator('L_VIEW_PROFISSIONAL').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setAttrib("size",70)
		->addValidator('NotEmpty');
		
		$tx_nome_conhecido = new Base_Form_Element_Text('tx_nome_conhecido', array('class'=>'float-l span-5'));
		$tx_nome_conhecido->setLabel(Base_Util::getTranslator('L_VIEW_NOME_CONHECIDO').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$dt_nascimento_profissional = new Base_Form_Element_Data('dt_nascimento_profissional');
		$dt_nascimento_profissional->setLabel(Base_Util::getTranslator('L_VIEW_DATA_NASCIMENTO').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right gapLeft'))
		->addDecorator('HtmlTag', array('tag'=>'div'));
		
		$tx_email_institucional = new Base_Form_Element_Text('tx_email_institucional', array('class'=>'float-l span-5'));
		$tx_email_institucional->setLabel(Base_Util::getTranslator('L_VIEW_EMAIL_LOGIN').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_email_pessoal = new Base_Form_Element_Text('tx_email_pessoal', array('class'=>'float-l span-5'));
		$tx_email_pessoal->setLabel(Base_Util::getTranslator('L_VIEW_EMAIL_OUTRO').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right gapLeft'))
		->addFilter('StripTags')
		->addFilter('StringTrim');
		
		$tx_telefone_residencial = new Base_Form_Element_Telefone('tx_telefone_residencial', array('class'=>'float-l span-3'));
		$tx_telefone_residencial->setLabel(Base_Util::getTranslator('L_VIEW_TELEFONE_RESIDENCIAL').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
//		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-11 float-l clear-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim');

		$tx_celular_profissional = new Base_Form_Element_Telefone('tx_celular_profissional', array('class'=>'float-l span-3'));
		$tx_celular_profissional->setLabel(Base_Util::getTranslator('L_VIEW_TELEFONE_CELULAR').':')
		->addDecorator('Label', array('class'=>'span-6 float-l right gapLeft'))
		->addFilter('StripTags')
		->addFilter('StringTrim');
		
		$tx_ramal_profissional = new Base_Form_Element_SoNumero('tx_ramal_profissional', array('class'=>'float-l span-3'));
		$tx_ramal_profissional->setLabel(Base_Util::getTranslator('L_VIEW_RAMAL').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
		->setAttrib("maxlength",4)
		->setAttrib("size",10)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		//Combo Empresa
		$arrPerfil = $objPerfil->getPerfil(true, true);
		$cd_perfil = new Base_Form_Element_Select('cd_perfil', array('class'=>'span-5 float-l'));
		$cd_perfil->setLabel(Base_Util::getTranslator('L_VIEW_PERFIL').':')
		->addDecorator('Label', array('class'=>'span-6 float-l right gapLeft'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_perfil->addMultiOptions($arrPerfil);
		
		$tx_endereco_profissional = new Base_Form_Element_Text('tx_endereco_profissional', array('class'=>'float-l span-14'));
		$tx_endereco_profissional->setLabel(Base_Util::getTranslator('L_VIEW_ENDERECO').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
		->setAttrib("size",70)
		->addFilter('StripTags')
		->addFilter('StringTrim');

		/**
		 * Método instânciado para comparar as datas de inicio e fim 
		 * essa e a classe que esta o método
		 */
		$diferenciar_datas = new Base_View_Helper_Datediff();
		/**
		 * este e o método que ira escrever o script
		 * O primeiro paramêtro e a data de inicio e o segundo e a data de fim
		 */ 
	    $diferenciar_datas->comparaDataInicioFim('dt_inicio_trabalho','dt_saida_profissional');
		
		$dt_inicio_trabalho = new Base_Form_Element_Data('dt_inicio_trabalho');
		$dt_inicio_trabalho->setLabel(Base_Util::getTranslator('L_VIEW_DATA_INICIO').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
        ->addDecorator('HtmlTag', array('tag'=>'div'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$dt_saida_profissional = new Base_Form_Element_Data('dt_saida_profissional');
		$dt_saida_profissional->setLabel(Base_Util::getTranslator('L_VIEW_DATA_SAIDA').':')
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
		->addDecorator('HtmlTag', array('tag'=>'div'));
		
		$st_nova_senha = new Base_Form_Element_Checkbox('st_nova_senha', array('class'=>'float-l'));
		$st_nova_senha->setLabel(Base_Util::getTranslator('L_VIEW_NOVA_SENHA'))
		->addDecorator('Label', array('class'=>'span-6 float-l right gapLeft'))
		->setCheckedValue("S")
		->addFilter('StripTags')
		->addFilter('StringTrim');

		$st_inativo = new Base_Form_Element_Checkbox('st_inativo', array('class'=>'float-l'));
		$st_inativo->setLabel(Base_Util::getTranslator('L_VIEW_INATIVO').':')
		->addDecorator('Label', array('class'=>'span-6 float-l right gapLeft'))
		->setCheckedValue("S")
		->addFilter('StripTags')
		->addFilter('StringTrim');

		$st_dados_todos_contratos = new Base_Form_Element_Checkbox('st_dados_todos_contratos', array('class'=>'float-l'));
		$st_dados_todos_contratos->setLabel(Base_Util::getTranslator('L_VIEW_VER_TODOS_CONTRATOS'))
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
		->setCheckedValue("S")
		->addFilter('StripTags')
		->addFilter('StringTrim');

		$this->addElements(array(
								$cd_profissional, 
								$cd_empresa, 
								$cd_relacao_contratual, 
								$tx_profissional, 
								$tx_nome_conhecido, 
								$dt_nascimento_profissional,
								$tx_email_institucional,
								$tx_telefone_residencial,
								$tx_celular_profissional,
								$tx_email_pessoal));
	$this->addDisplayGroup(array('tx_telefone_residencial', 'tx_celular_profissional'),
                           'linha1',
                           array('displayGroupClass'=>'Base_Form_DisplayDivGroup'));
	$this->addElements(array(	$tx_ramal_profissional,
								$cd_perfil,
								$tx_endereco_profissional,
								$diferenciar_datas,
								$dt_inicio_trabalho,
								$dt_saida_profissional,
								$st_nova_senha,
								$st_inativo));
	$this->addDisplayGroup(array('dt_inicio_trabalho', 'st_nova_senha'),
                           'linha2',
                           array('displayGroupClass'=>'Base_Form_DisplayDivGroup'));
	$this->addDisplayGroup(array('dt_saida_profissional', 'st_inativo'),
                           'linha3',
                           array('displayGroupClass'=>'Base_Form_DisplayDivGroup'));
	$this->addElements(array($st_dados_todos_contratos));
	}
}