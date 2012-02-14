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

class ProjetoForm extends Base_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}

	private function generate()
	{
		$this->setName('projeto');

		$profissional               = new Profissional();
		$profissionalObjetoContrato = new ProfissionalObjetoContrato();
		$cd_objeto                  = $_SESSION['oasis_logged'][0]['cd_objeto'];
		$arrProfissional            = $profissionalObjetoContrato->getProfissionalGerenteObjetoContrato($cd_objeto, true);;

		$unidade    = new Unidade();
		$arrUnidade = $unidade->getUnidade(true);

		$status    = new Status();
		$arrStatus = $status->getStatus(true);

		$this->addDecorator('FormElements')
		->addDecorator('HtmlTag', array('tag'=>'div'))
		->addDecorator('Form');

		$cd_projeto = new Base_Form_Element_Hidden('cd_projeto');

		$tx_projeto = new Base_Form_Element_Text('tx_projeto', array('class'=>'span-10 float-l'));
		$tx_projeto->setLabel(Base_Util::getTranslator('L_VIEW_NOME_PROJETO').':')
		->addDecorator('Label', array('class'=>'span-4 right float-l'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-21 gap-1 float-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_sigla_projeto = new Base_Form_Element_Text('tx_sigla_projeto', array('class'=>'span-5'));
		$tx_sigla_projeto->setLabel(Base_Util::getTranslator('L_VIEW_SIGLA').':')
		->addDecorator('Label', array('class'=>'span-4 right float-l'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-21 gap-1 float-1'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

//		$tx_contexto_geral_projeto = new Base_Form_Element_EditorHtml('tx_contexto_geral_projeto', array('class'=>'float-l','cols'=>'70','rows'=>'15','editor'=>K_EDITOR));
		$tx_contexto_geral_projeto = new Base_Form_Element_Textarea('tx_contexto_geral_projeto', array('class'=>'float-l height-10','cols'=>'70'));
		$tx_contexto_geral_projeto->setLabel(Base_Util::getTranslator('L_VIEW_CONTEXTUALIZACAO_PROJETO').':')
		->addDecorator('Label', array('class'=>'span-4 right float-l'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-21 gap-1'))
		->setRequired(true)
		->addValidator('NotEmpty');

//		$tx_escopo_projeto = new Base_Form_Element_EditorHtml('tx_escopo_projeto', array('class'=>'float-l','cols'=>'70','rows'=>'15','editor'=>K_EDITOR,'editor'=>'editor'));
		$tx_escopo_projeto = new Base_Form_Element_Textarea('tx_escopo_projeto', array('class'=>'float-l height-10','cols'=>'70'));
		$tx_escopo_projeto->setLabel(Base_Util::getTranslator('L_VIEW_ESCOPO_RESUMIDO').':')
		->addDecorator('Label', array('class'=>'span-4 right float-l'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-21 gap-1'))
		->setRequired(true)
		->addValidator('NotEmpty');

		$st_prioridade_projeto = new Base_Form_Element_Select('st_prioridade_projeto', array('class'=>'span-5 float-l'));
		$st_prioridade_projeto->setLabel(Base_Util::getTranslator('L_VIEW_PRIORIDADE').':')
		->addDecorator('Label', array('class'=>'span-4 right float-l'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-21 gap-1 float-l'))
		->setRegisterInArrayValidator(false)
		->setRequired(true)
		->addMultiOptions(Base_Util::getPrioridade());

		$st_impacto_projeto = new Base_Form_Element_Radio('st_impacto_projeto', array('class'=>'float-l'));
		$st_impacto_projeto->setLabel(Base_Util::getTranslator('L_VIEW_ABRANGENCIA_PROJETO').':')
		->addDecorator('Label', array('class'=>'span-4 right float-l'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-21 gap-1 float-l radio-h'))
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
        ->addMultiOptions(Base_Util::getAbrangencia());

		$tx_publico_alcancado = new Base_Form_Element_Text('tx_publico_alcancado', array('class'=>'span-10 float-l'));
		$tx_publico_alcancado->setLabel(Base_Util::getTranslator('L_VIEW_PUBLICO_SER_ALCANCADO').':')
		->addDecorator('Label', array('class'=>'span-4 right float-l'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-21 gap-1 float-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$cd_unidade = new Base_Form_Element_Select('cd_unidade', array('class'=>'span-6 float-l'));
		$cd_unidade->setLabel(Base_Util::getTranslator('L_VIEW_UNIDADE_GESTORA').':')
		->addDecorator('Label', array('class'=>'span-4 right float-l'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-21 gap-1'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_unidade->addMultiOptions($arrUnidade);

		$tx_gestor_projeto = new Base_Form_Element_Text('tx_gestor_projeto', array('class'=>'span-6 float-l'));
		$tx_gestor_projeto->setLabel(Base_Util::getTranslator('L_VIEW_GESTOR_PROJETO').':')
		->addDecorator('Label', array('class'=>'span-4 right float-l'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-10 gap-1 float-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_co_gestor_projeto = new Base_Form_Element_Text('tx_co_gestor_projeto', array('class'=>'span-6 float-l'));
		$tx_co_gestor_projeto->setLabel(Base_Util::getTranslator('L_VIEW_CO_GESTOR_PROJETO').':')
		->addDecorator('Label', array('class'=>'span-4 right float-l'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-10 gap-1 float-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$cd_profissional_gerente = new Base_Form_Element_Select('cd_profissional_gerente', array('class'=>'span-6 float-l'));
		$cd_profissional_gerente->setLabel(Base_Util::getTranslator('L_VIEW_GERENTE_DO_PROJETO').':')
		->addDecorator('Label', array('class'=>'span-4 right float-l'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-10 gap-1 float-l'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_profissional_gerente->addMultiOptions($arrProfissional);

		$cd_status = new Base_Form_Element_Select('cd_status', array('class'=>'float-l span-6'));
		$cd_status->setLabel(Base_Util::getTranslator('L_VIEW_STATUS_PROJETO').':')
		->addDecorator('Label', array('class'=>'span-4 right float-l'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-10 gap-1 float-l'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_status->addMultiOptions($arrStatus);

		//Início Previsto
		$ni_mes_inicio_previsto = new Base_Form_Element_MesCombo('ni_mes_inicio_previsto', array('class'=>'float-l span-3'));
		$ni_mes_inicio_previsto->setLabel(Base_Util::getTranslator('L_VIEW_PREVISAO_INICIO').':')
		->addDecorator('Label', array('class'=>'span-4 right float-l'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-7 float-l'))
		->setRequired(true);

		$ni_ano_inicio_previsto = new Base_Form_Element_AnoCombo('ni_ano_inicio_previsto', array('class'=>'span-2 float-l'));
        $ni_ano_inicio_previsto->setLabel(Base_Util::getTranslator('L_VIEW_ANO').':')
        ->addDecorator('Label', array('class'=>'span-1 right float-l'));
		$ni_ano_inicio_previsto->setRequired(true);

		//Termino Previsto
		$ni_mes_termino_previsto = new Base_Form_Element_MesCombo('ni_mes_termino_previsto', array('class'=>'float-l span-3'));
		$ni_mes_termino_previsto->setLabel(Base_Util::getTranslator('L_VIEW_PREVISAO_TERMINO').':')
		->addDecorator('Label', array('class'=>'span-4 right float-l'))
		->setRequired(true);

		$ni_ano_termino_previsto = new Base_Form_Element_AnoCombo('ni_ano_termino_previsto', array('class'=>'float-l span-2'));
        $ni_ano_termino_previsto->setLabel(Base_Util::getTranslator('L_VIEW_ANO').':')
        ->addDecorator('Label', array('class'=>'span-1 right float-l','style'=>'margin-left:10px;'))
		->setRequired(true);

		$tx_obs_projeto = new Base_Form_Element_Textarea('tx_obs_projeto', array('class'=>'float-l', 'style'=>'width:625px; height:75px;'));
		$tx_obs_projeto->setLabel(Base_Util::getTranslator('L_VIEW_OBSERVACOES_PARTICULARIADES_PROJETO').':')
		->addDecorator('Label', array('class'=>'span-4 right float-l'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-21 gap-1 float-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$this->addElements(array(
		$cd_projeto,
		$tx_projeto,
		$tx_sigla_projeto,
		$tx_contexto_geral_projeto,
		$tx_escopo_projeto,
		$st_prioridade_projeto,
		$st_impacto_projeto,
		$tx_publico_alcancado,
		$cd_unidade,
		$tx_gestor_projeto,
		$tx_co_gestor_projeto,
		$cd_profissional_gerente,
		$cd_status,
		$ni_mes_inicio_previsto,
		$ni_ano_inicio_previsto,
		$ni_mes_termino_previsto,
		$ni_ano_termino_previsto,

		//,$submit
		));
		$this->addDisplayGroup(array('ni_mes_inicio_previsto','ni_ano_inicio_previsto'), 'grupo_inicio', array('displayGroupClass'=>'Base_Form_DisplayDivGroup'));
		$this->getDisplayGroup('grupo_inicio')->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-10 gap-1 float-l'));

		$this->addDisplayGroup(array('ni_mes_termino_previsto','ni_ano_termino_previsto'), 'grupo_termino', array('displayGroupClass'=>'Base_Form_DisplayDivGroup'));
		$this->getDisplayGroup('grupo_termino')->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-10 gap-1 float-l'));

		$this->addElement($tx_obs_projeto);
	}
}