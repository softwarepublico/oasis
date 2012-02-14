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

class HistoricoForm extends Base_Form
{

	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	private function generate()
	{
		$arrSelects = array('0'=>Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE'));
		
		$cd_projeto = new Base_Form_Element_Hidden('cd_projeto');
        $this->setName('historico');
		$this->addDecorator('FormElements')
        ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-16'))
        ->addDecorator('Form');

		$profissionalProjeto = new ProfissionalProjeto();
		$cd_profissional     = $_SESSION['oasis_logged'][0]['cd_profissional'];
		$res                 = $profissionalProjeto->pesquisaProjetoPorProfissional(true,$cd_profissional);
		
		$etapa                      = new Etapa();
		$cd_objeto                  = $_SESSION['oasis_logged'][0]['cd_objeto'];
		$arrEtapaProfissionalObjeto = $etapa->pesquisaEtapaProfissionalObjeto(true,$cd_objeto);				
		
		$cd_projeto = new Base_Form_Element_Select('cd_projeto_historico', array('class'=>'span-5 float-l'));
		$cd_projeto->addMultiOptions($res);
		$cd_projeto->setLabel(Base_Util::getTranslator('L_VIEW_PROJETO').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		
		
		$cd_proposta = new Base_Form_Element_Select('cd_proposta_historico', array('class'=>'span-5 float-l'));
		$cd_proposta->addMultiOptions($arrSelects);
		$cd_proposta->setLabel(Base_Util::getTranslator('L_VIEW_PROPOSTA').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right clear-l'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
			
		$cd_modulo = new Base_Form_Element_Select('cd_modulo_historico', array('class'=>'span-5 float-l'));
		$cd_modulo->addMultiOptions($arrSelects);
		$cd_modulo->setLabel(Base_Util::getTranslator('L_VIEW_MODULO').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right gapLeft'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		
		$cd_etapa = new Base_Form_Element_Select('cd_etapa_historico', array('class'=>'span-5 float-l'));
		$cd_etapa->addMultiOptions($arrEtapaProfissionalObjeto);
		$cd_etapa->setLabel(Base_Util::getTranslator('L_VIEW_ETAPA').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right clear-l'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
				
		$cd_atividade = new Base_Form_Element_Select('cd_atividade_historico', array('class'=>'span-5 float-l'));
		$cd_atividade->addMultiOptions($arrSelects);
		$cd_atividade->setLabel(Base_Util::getTranslator('L_VIEW_ATIVIDADE').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right gapLeft'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);	

		/**
		 * Método instânciado para comparar as datas de inicio e fim 
		 * essa e a classe que esta o método
		 */
		$diferenciar_datas = new Base_View_Helper_Datediff();
		/**
		 * este e o método que ira escrever o script
		 * O primeiro paramêtro e a data de inicio e o segundo e a data de fim
		 */ 
	    $diferenciar_datas->comparaDataInicioFim('dt_inicio_historico','dt_fim_historico');
		
		$dt_inicio_historico = new Base_Form_Element_Data('dt_inicio_historico');
		$dt_inicio_historico->setLabel(Base_Util::getTranslator('L_VIEW_DATA_INICIAL').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right float-l clear-l'))
		->addDecorator('HtmlTag',array('class'=>'span-5 float-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');		
		
		$dt_fim_historico = new Base_Form_Element_Data('dt_fim_historico');
		$dt_fim_historico->setLabel(Base_Util::getTranslator('L_VIEW_DATA_FINAL').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$cd_historico = new Base_Form_Element_Hidden('cd_historico');
		
		$tx_historico = new Base_Form_Element_EditorHtml('tx_historico', array('class'=>'float-l','cols'=>'56','rows'=>'13','editor'=>K_EDITOR));
		$tx_historico->setLabel(Base_Util::getTranslator('L_VIEW_HISTORICO').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right clear-l', 'editor'=>'editor'))
		->setRequired(true)
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$submit = new Base_Form_Element_Button('submit');
		$submit->setAttrib('id', 'submitbuttonHistorico');
		$submit->setAttrib('class','verde float-l clear-l push-3');

		$this->addElements(array(
		$cd_historico,
		$cd_projeto,
		$cd_proposta,
		$cd_modulo,
		$cd_etapa,
		$cd_atividade,
		$dt_inicio_historico,
		$dt_fim_historico,
		$tx_historico,
		$submit));
	}
}