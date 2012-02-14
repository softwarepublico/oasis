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

class PlanejamentoForm extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	private function generate()
	{
		$this->setName('planejamento');
	
		$projeto    = new Projeto();
		$arrProjeto = $projeto->getProjeto(true);

		$etapa    = new Etapa();
		$arrEtapa = $etapa->getProjeto(null, true);

		$cd_projeto = new Zend_Form_Element_Select('cd_projeto');
		$cd_projeto->setLabel(Base_Util::getTranslator('L_VIEW_PROJETO'))
                   ->setRegisterInArrayValidator(false)
                   ->setRequired(true)
                   ->addMultiOptions($arrProjeto);

		$cd_modulo = new Zend_Form_Element_Select('cd_modulo');
		$cd_modulo->setLabel(Base_Util::getTranslator('L_VIEW_MODULO'))
                  ->setRegisterInArrayValidator(false)
                  ->setRequired(true);

		$cd_etapa = new Zend_Form_Element_Select('cd_etapa');
		$cd_etapa->setLabel(Base_Util::getTranslator('L_VIEW_ETAPA'))
                 ->setRegisterInArrayValidator(false)
                 ->setRequired(true)
                 ->addMultiOptions($arrEtapa);

		$cd_atividade = new Zend_Form_Element_Select('cd_atividade');
		$cd_atividade->setLabel(Base_Util::getTranslator('L_VIEW_ATIVIDADE'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);

		$dt_inicio_atividade = new Zend_Form_Element_Text('dt_inicio_atividade');
		$dt_inicio_atividade->setLabel(Base_Util::getTranslator('L_VIEW_DATA_INICIAL'))
                            ->setRequired(true)
                            ->addFilter('StripTags')
                            ->addFilter('StringTrim')
                            ->addValidator('NotEmpty');

		$dt_fim_atividade = new Zend_Form_Element_Text('dt_fim_atividade');
		$dt_fim_atividade->setLabel(Base_Util::getTranslator('L_VIEW_DATA_FINAL'))
                         ->setRequired(true)
                         ->addFilter('StripTags')
                         ->addFilter('StringTrim')
                         ->addValidator('NotEmpty');

		$nf_porcentagem_execucao = new Zend_Form_Element_Textarea('nf_porcentagem_execucao');
		$nf_porcentagem_execucao->setLabel(Base_Util::getTranslator('L_VIEW_PORCENTAGEM_EXECUCAO'))
                                ->setRequired(true)
                                ->addFilter('StripTags')
                                ->addFilter('StringTrim')
                                ->addValidator('NotEmpty');

		$tx_obs_atividade = new Zend_Form_Element_Textarea('tx_obs_atividade');
		$tx_obs_atividade->setLabel(Base_Util::getTranslator('L_VIEW_OBSERVACAO'))
                         ->setRequired(true)
                         ->addFilter('StripTags')
                         ->addFilter('StringTrim')
                         ->addValidator('NotEmpty');

		$submit = new Zend_Form_Element_Button('submit');
		$submit->setAttrib('id', 'submitbutton');
		$submit->setAttrib('class', 'verde buttonBar');
        
		$this->addElements(array(
								$cd_projeto, 
								$cd_modulo, 
								$cd_etapa,
								$cd_atividade,
								$dt_inicio_atividade,
								$dt_fim_atividade,
								$nf_porcentagem_execucao, 
								$tx_obs_atividade,
								$submit));
	}
}