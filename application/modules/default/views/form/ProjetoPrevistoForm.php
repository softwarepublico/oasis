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

class ProjetoPrevistoForm extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
		
	private function generate()
	{
//		$contrato    = new Contrato();
//		$arrContrato = $contrato->getContratoPorTipoDeObjeto(true, 'P');

		$unidade    = new Unidade();
		$arrUnidade = $unidade->getUnidade(true);
		
		$this->setName('projeto_previsto');
		$this->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-14'));

		$cd_contrato = new Base_Form_Element_Select('cd_contrato_projeto_previsto', array('class'=>'span-5 float-l'));
		$cd_contrato->setLabel(Base_Util::getTranslator('L_VIEW_CONTRATO').':')
		->addDecorator('Label', array('class'=>'float-l span-3 right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
//		$cd_contrato->addMultiOptions($arrContrato);

		$cd_unidade = new Base_Form_Element_Select('cd_unidade_projeto_previsto', array('class'=>'span-5 float-l'));
		$cd_unidade->setLabel(Base_Util::getTranslator('L_VIEW_UNIDADE').':')
		->addDecorator('Label', array('class'=>'span-3 float-l clear-l right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_unidade->addMultiOptions($arrUnidade);
		
		$cd_projeto_previsto = new Base_Form_Element_Hidden('cd_projeto_previsto');
		$tx_projeto_previsto = new Base_Form_Element_Text('tx_projeto_previsto', array('class'=>'span-10 float-l'));
		$tx_projeto_previsto->setLabel(Base_Util::getTranslator('L_VIEW_PROJETO_PREVISTO').':')
		->addDecorator('Label', array('class'=>'span-3 float-l clear-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$ni_horas_projeto_previsto = new Base_Form_Element_SoNumero('ni_horas_projeto_previsto', array('class'=>'span-2 float-l'));
		$ni_horas_projeto_previsto->setLabel(Base_Util::getTranslator('L_VIEW_UNID_METRICA_PREVISTA').':')
		->addDecorator('Label', array('class'=>'span-3 float-l clear-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$objDefinicaoMetrica = new DefinicaoMetrica();
		$arrSiglaMetrica	 = $objDefinicaoMetrica->getComboSiglaDefinicaoMetrica(true);

		$cd_metrica_unidade_prevista_projeto_previsto = new Base_Form_Element_Select('cd_metrica_unidade_prevista_projeto_previsto', array('class'=>'float-l span-3'));
		$cd_metrica_unidade_prevista_projeto_previsto->addMultiOptions($arrSiglaMetrica)
		->setLabel('&nbsp;')
		->addDecorator('Label', array('class'=>'float-l right lb_combo_sigla_metrica_unidade_prevista_projeto_previsto', 'style'=>'margin-left: 5px;'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'float-l', 'style'=>'height:27px;'))
		->setRequired(true)
        ->setRegisterInArrayValidator(false);

		$arrProjetoPrevisto = array();
		$arrProjetoPrevisto['0'] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		$arrProjetoPrevisto['E'] = Base_Util::getTranslator('L_VIEW_COMBO_EVOLUTIVO');
		$arrProjetoPrevisto['N'] = Base_Util::getTranslator('L_VIEW_COMBO_NOVO');
		
		$st_projeto_previsto = new Base_Form_Element_Select('st_projeto_previsto', array('class'=>'span-5 float-l'));
		$st_projeto_previsto->setLabel(Base_Util::getTranslator('L_VIEW_TIPO_PROJETO').':')
		->addDecorator('Label', array('class'=>'span-3 float-l clear-l right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$st_projeto_previsto->addMultiOptions($arrProjetoPrevisto);
		
		$tx_descricao_projeto_previsto = new Base_Form_Element_Textarea('tx_descricao_projeto_previsto', array('class'=>'span-14 height-4 float-l'));
		$tx_descricao_projeto_previsto->setLabel(Base_Util::getTranslator('L_VIEW_DESCRICAO').':')
		->addDecorator('Label', array('class'=>'float-l span-3 right'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-22 float-l clear gap-1'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');						
		
		$this->addElements(array(
								$cd_projeto_previsto, 
								$cd_contrato, 
								$cd_unidade, 
								$st_projeto_previsto,
								$tx_projeto_previsto, 
								$ni_horas_projeto_previsto,
								$cd_metrica_unidade_prevista_projeto_previsto,
								$tx_descricao_projeto_previsto));
	}
}