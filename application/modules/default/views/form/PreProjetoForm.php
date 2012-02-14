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

class PreProjetoForm extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
		
	private function generate()
	{
		$this->setName('pre_projeto');
		$this->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-21'));
		
		$cd_objeto = $_SESSION["oasis_logged"][0]["cd_objeto"];
		
		$profissional    = new ProfissionalObjetoContrato();
		$arrProfissional = $profissional->getProfissionalGerenteObjetoContrato($cd_objeto, true);

		$unidade    = new Unidade();
		$arrUnidade = $unidade->getUnidade(true);
		
		$cd_pre_projeto     = new Base_Form_Element_Hidden('cd_pre_projeto');

		$tx_pre_projeto = new Base_Form_Element_Text('tx_pre_projeto', array('class'=>'float-l span-5'));
		$tx_pre_projeto->setLabel(Base_Util::getTranslator('L_VIEW_NOME_PRE_PROJETO').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_sigla_pre_projeto = new Base_Form_Element_Text('tx_sigla_pre_projeto', array('class'=>'float-l span-5'));
		$tx_sigla_pre_projeto->setLabel(Base_Util::getTranslator('L_VIEW_SIGLA').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right gapLeft'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_contexto_geral_pre_projeto = new Base_Form_Element_Textarea('tx_contexto_geral_pre_projeto', array('class'=>'span-15 float-l height-3'));
		$tx_contexto_geral_pre_projeto->setLabel(Base_Util::getTranslator('L_VIEW_CONTEXTUALIZACAO_PROJETO').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$tx_escopo_pre_projeto = new Base_Form_Element_Textarea('tx_escopo_pre_projeto', array('class'=>'span-15 float-l height-3'));
		$tx_escopo_pre_projeto->setLabel(Base_Util::getTranslator('L_VIEW_ESCOPO_RESUMIDO').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_horas_estimadas = new Base_Form_Element_Text('tx_horas_estimadas', array('class'=>'span-5 float-l'));
		$tx_horas_estimadas->setLabel(Base_Util::getTranslator('L_VIEW_UNID_METRICA_ESTIMADA').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
				
		$cd_unidade = new Base_Form_Element_Select('cd_unidade', array('class'=>'span-5 float-l'));
		$cd_unidade->setLabel(Base_Util::getTranslator('L_VIEW_UNIDADE').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right gapLeft'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_unidade->addMultiOptions($arrUnidade);
	
		
		$tx_gestor_pre_projeto = new Base_Form_Element_Text('tx_gestor_pre_projeto', array('class'=>'span-5 float-l'));
		$tx_gestor_pre_projeto->setLabel(Base_Util::getTranslator('L_VIEW_GESTOR').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_obs_pre_projeto = new Base_Form_Element_Textarea('tx_obs_pre_projeto', array('class'=>'span-15 float-l height-3'));
		$tx_obs_pre_projeto->setLabel(Base_Util::getTranslator('L_VIEW_OBSERVACOES_PARTICULARIADES_PROJETO').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$st_impacto_pre_projeto = new Base_Form_Element_Radio('st_impacto_pre_projeto');
		$st_impacto_pre_projeto->setLabel(Base_Util::getTranslator('L_VIEW_ABRANGENCIA_PROJETO').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right height-3'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-10 float-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->addMultiOptions(Base_Util::getAbrangencia());
		
		$st_prioridade_pre_projeto = new Base_Form_Element_Select('st_prioridade_pre_projeto', array('class'=>'span-5 float-l'));
		$st_prioridade_pre_projeto->setLabel(Base_Util::getTranslator('L_VIEW_PRIORIDADE').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right clear-l'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$st_prioridade_pre_projeto->addMultiOptions(Base_Util::getPrioridade());
		
		$cd_gerente_pre_projeto = new Base_Form_Element_Select('cd_gerente_pre_projeto', array('class'=>'span-5 float-l'));
		$cd_gerente_pre_projeto->setLabel(Base_Util::getTranslator('L_VIEW_GERENTE').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right gapLeft'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_gerente_pre_projeto->addMultiOptions($arrProfissional);

		$tx_pub_alcancado_pre_proj = new Base_Form_Element_Text('tx_pub_alcancado_pre_proj', array('class'=>'span-5 float-l'));
		$tx_pub_alcancado_pre_proj->setLabel(Base_Util::getTranslator('L_VIEW_PUBLICO_SER_ALCANCADO').':')
		->addDecorator('Label', array('class'=>'span-5 float-l right gapLeft'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
				
		$submit = new Base_Form_Element_Button('submit');
		$submit->setAttrib('id', 'submitbutton');
		$submit->setAttrib('class', 'verde buttonBar');
		$this->addElements(array(
								$cd_pre_projeto, 
								$tx_pre_projeto,
								$tx_sigla_pre_projeto, 
								$tx_contexto_geral_pre_projeto, 
								$tx_escopo_pre_projeto, 
								$tx_horas_estimadas,
								$cd_unidade,
								$tx_gestor_pre_projeto, 
								$cd_gerente_pre_projeto,
								$st_prioridade_pre_projeto,
								$tx_pub_alcancado_pre_proj,
								$st_impacto_pre_projeto,
								$tx_obs_pre_projeto, 
								$submit
								));
	}
}