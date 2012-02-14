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

class ContratoForm extends Base_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	private function generate()
	{
        $this->addDecorator('FormElements')
             ->addDecorator('HtmlTag', array('tag' => 'div')) //insere uma <div> no <form>
             ->addDecorator('Form');
		
		$empresa    = new Empresa();
		$arrEmpresa = $empresa->getEmpresa(true);
	
		$this->setName('form_contrato');
		$cd_contrato = new Base_Form_Element_Hidden('cd_contrato');

		$cd_empresa = new Base_Form_Element_Select('cd_empresa_contrato', array('class'=>'float-l span-14'));
		//$cd_empresa->addMultiOptions($arrEmpresa);
		$cd_empresa->setLabel(Base_Util::getTranslator('L_VIEW_EMPRESA').':')
		->addDecorator('Label', array('class'=>'float-l span-4 right'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-18 float-l', 'style'=>'height:27px;'))
        ->setRegisterInArrayValidator(false)
        ->setRequired(true);

        $cd_contato_empresa = new Base_Form_Element_Select('cd_contato_empresa_contrato', array('class'=>'float-l span-14'));
		$cd_contato_empresa->setLabel(Base_Util::getTranslator('L_VIEW_PREPOSTO').':')
		->addDecorator('Label', array('class'=>'float-l span-4 right'))
		->setRequired(true)
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-18 float-l', 'style'=>'height:27px;'))
        ->setRegisterInArrayValidator(false);
		
		$tx_numero_contrato = new Base_Form_Element_Text('tx_numero_contrato', array('class'=>'float-l span-4','maxlength'=>'10'));
		$tx_numero_contrato->setLabel(Base_Util::getTranslator('L_VIEW_NR_CONTRATO').':')
		->addDecorator('Label', array('class'=>' float-l span-4 right'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-10 clear-l float-l', 'style'=>'height:27px;', 'id'=>'div_tx_num_contrato'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
        
		$st_contrato = new Base_Form_Element_Checkbox('st_contrato', array('class'=>'float-l'));
		$st_contrato->setLabel(Base_Util::getTranslator('L_VIEW_INATIVO').':')
		->addDecorator('Label', array('class'=>'float-l span-4 right'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-9 float-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		$st_contrato->setCheckedValue('I');
		$st_contrato->setUncheckedValue('A');

		/*$st_contrato->setLabel('Situação:')
		->addDecorator('Label', array('class'=>'float-l span-5 right'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-12 float-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		$st_contrato->addMultiOption('A', 'Ativo');
		$st_contrato->addMultiOption('I', 'Inativo');
		*/
		/**
		 * Método instânciado para comparar as datas de inicio e fim 
		 * essa e a classe que esta o método
		 */
		$diferenciar_datas = new Base_View_Helper_Datediff();
		/**
		 * este e o método que ira escrever o script
		 * O primeiro paramêtro e a data de inicio e o segundo e a data de fim
		 */ 
	    $diferenciar_datas->comparaDataInicioFim('dt_inicio_contrato','dt_fim_contrato');
		
		$dt_inicio_contrato = new Base_Form_Element_Data('dt_inicio_contrato', array('class'=>'float-l span-3'));
		$dt_inicio_contrato->setLabel(Base_Util::getTranslator('L_VIEW_DATA_INICIO').':')
		->addDecorator('Label', array('class'=>'float-l span-4 right'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-5 float-l', 'style'=>'height:27px;'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$dt_fim_contrato = new Base_Form_Element_Data('dt_fim_contrato', array('class'=>'float-l span-3'));
		$dt_fim_contrato->setLabel(Base_Util::getTranslator('L_VIEW_DATA_FIM').':')
		->addDecorator('Label', array('class'=>'float-l span-5 right'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-4 float-l', 'style'=>'height:27px;'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$tx_numero_processo = new Base_Form_Element_Text('tx_numero_processo', array('class'=>'float-l span-4'));
		$tx_numero_processo->setLabel(Base_Util::getTranslator('L_VIEW_NR_PROCESSO').':')
		->addDecorator('Label', array('class'=>'float-l span-4 right'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-10 float-l', 'style'=>'height:27px;'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$st_aditivo = new Base_Form_Element_Checkbox('st_aditivo', array('class'=>'float-l'));
		$st_aditivo->setLabel(Base_Util::getTranslator('L_VIEW_ADITIVO').':')
		->addDecorator('Label', array('class'=>'float-l span-5 right'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-9 float-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		$st_aditivo->setCheckedValue('S');
		$st_aditivo->setUncheckedValue(null);

		$tx_cpf_gestor = new Base_Form_Element_Cpf('tx_cpf_gestor', array('class'=>'float-l span-4'));
		$tx_cpf_gestor->setLabel(Base_Util::getTranslator('L_VIEW_CPF_GESTOR').':')
		->addDecorator('Label', array('class'=>'float-l span-4 right'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-10 float-l', 'style'=>'height:27px;'))
        ->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$tx_valor_contrato = new Base_Form_Element_Moeda('nf_valor_contrato', array('class'=>'float-l span-4','symbol'=>''));
		$tx_valor_contrato->setLabel(Base_Util::getTranslator('L_VIEW_VALOR_CONTRATO').':')
		->addDecorator('Label', array('class'=>'float-l span-4 right'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-8 float-l float-l'))
        ->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$tx_gestor_contrato = new Base_Form_Element_Text('tx_gestor_contrato', array('class'=>'float-l span-14'));
		$tx_gestor_contrato->setLabel(Base_Util::getTranslator('L_VIEW_GESTOR_CONTRATO').':')
		->addDecorator('Label', array('class'=>'float-l span-4 right'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-19 float-l', 'style'=>'height:27px;'))
        ->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$tx_fone_gestor_contrato = new Base_Form_Element_Telefone('tx_fone_gestor_contrato', array('class'=>'float-l span-4'));
		$tx_fone_gestor_contrato->setLabel(Base_Util::getTranslator('L_VIEW_TELEFONE_GESTOR').':')
		->addDecorator('Label', array('class'=>'float-l span-4 right'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-10 float-l', 'style'=>'height:27px;'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');		
		
		$tx_objeto = new Base_Form_Element_EditorHtml('tx_objeto', array('class'=>'float-l','cols'=>'66', 'rows'=>'12','editor'=>K_EDITOR));
		$tx_objeto->setLabel(Base_Util::getTranslator('L_VIEW_OBJETO').':')
		->addDecorator('Label', array('class'=>'float-l span-4 right', 'editor'=>'editor'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-22 float-l clear gap-1'))
		->setRequired(true)
		->addFilter('StringTrim')
		->addValidator('NotEmpty');		
		
		$tx_obs_contrato = new Base_Form_Element_Textarea('tx_obs_contrato', array('class'=>'span-14 height-4 float-l'));
		$tx_obs_contrato->setLabel(Base_Util::getTranslator('L_VIEW_OBSERVACAO').':')
		->addDecorator('Label', array('class'=>'float-l span-4 right'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-22 float-l clear gap-1'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');		
		
		$tx_localizacao_arquivo = new Base_Form_Element_Text('tx_localizacao_arquivo', array('class'=>'span-14 float-l'));
		$tx_localizacao_arquivo->setLabel(Base_Util::getTranslator('L_VIEW_LOCAL_DOCUMENTOS_AREA_TI').':')
		->addDecorator('Label', array('class'=>'float-l span-4 right'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-22 float-l clear gap-1'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$tx_co_gestor = new Base_Form_Element_Text('tx_co_gestor', array('class'=>'float-l span-14'));
		$tx_co_gestor->setLabel(Base_Util::getTranslator('L_VIEW_CO_GESTOR_CONTRATO').':')
		->addDecorator('Label', array('class'=>'float-l span-4 right'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-19 float-l', 'style'=>'height:27px;'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$tx_cpf_co_gestor = new Base_Form_Element_Cpf('tx_cpf_co_gestor', array('class'=>'float-l span-4'));
		$tx_cpf_co_gestor->setLabel(Base_Util::getTranslator('L_VIEW_CPF_CO_GESTOR').':')
		->addDecorator('Label', array('class'=>'float-l span-4 right'))
		      ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-9 float-l', 'style'=>'height:27px;'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$tx_fone_co_gestor_contrato = new Base_Form_Element_Telefone('tx_fone_co_gestor_contrato', array('class'=>'float-l span-4'));
		$tx_fone_co_gestor_contrato->setLabel(Base_Util::getTranslator('L_VIEW_TELEFONE_CO_GESTOR').':')
		->addDecorator('Label', array('class'=>'float-l span-4 right'))
        ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-9 float-l prepend-1', 'style'=>'height:27px;'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$nf_valor_unitario_hora = new Base_Form_Element_Moeda('nf_valor_unitario_hora', array('class'=>'float-l span-4'));
		$nf_valor_unitario_hora->setLabel(Base_Util::getTranslator('L_VIEW_VALOR_UNIT_UNID_METRICA').':')
		->addDecorator('Label', array('class'=>'float-l span-4 right'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-9 float-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		//$ni_horas_previstas = new Base_Form_Element_SoNumero('ni_horas_previstas', array('class'=>'float-l span-2'));
		$ni_horas_previstas = new Base_Form_Element_SoNumero('ni_horas_previstas', array('class'=>'float-l', 'style'=>'width: 62px;','onblur'=>'validaConteudoUnidadeMetricaPrevistaContrato(this.value)'));
		$ni_horas_previstas->setLabel(Base_Util::getTranslator('L_VIEW_UNID_METRICA_PREVISTA').':')
		->addDecorator('Label', array('class'=>'float-l span-5 right'))
		//->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-10 float-l', 'style'=>'height:27px;'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'float-l', 'style'=>'height:27px; width: 266px;'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$objDefinicaoMetrica = new DefinicaoMetrica();
		$arrSiglaMetrica	 = $objDefinicaoMetrica->getComboSiglaDefinicaoMetrica(true);

		$cd_metrica_unidade_prevista_contrato = new Base_Form_Element_Select('cd_metrica_unidade_prevista_contrato', array('class'=>'float-l span-3'));
		$cd_metrica_unidade_prevista_contrato->addMultiOptions($arrSiglaMetrica)
		->setLabel('&nbsp;')
		->addDecorator('Label', array('class'=>'float-l right lb_combo_sigla_metrica_unidade_prevista_contrato'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'float-l', 'style'=>'height:27px;'))
        ->setRegisterInArrayValidator(false);
		
		$this->addElements(array(
								$cd_contrato,
								$cd_empresa,
								$cd_contato_empresa,
								$tx_numero_contrato,
								$tx_numero_processo, 
								$dt_inicio_contrato, 
								$dt_fim_contrato, 
								$st_contrato,
								$st_aditivo,
								$tx_valor_contrato, 
								$ni_horas_previstas,
								$cd_metrica_unidade_prevista_contrato,
								$nf_valor_unitario_hora,
								$tx_gestor_contrato, 
								$tx_cpf_gestor, 
								$tx_fone_gestor_contrato,  
								$tx_co_gestor, 
								$tx_cpf_co_gestor, 
								$tx_fone_co_gestor_contrato, 
								$tx_objeto,
								$tx_obs_contrato, 
								$tx_localizacao_arquivo));
	}
}