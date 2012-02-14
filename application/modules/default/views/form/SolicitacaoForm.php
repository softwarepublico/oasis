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

class SolicitacaoForm extends Base_Form
{

	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	private function generate()
	{
		$this->setName('solicitacao');
		$this->addDecorator('FormElements')
             ->addDecorator('HtmlTag', array('tag' => 'div', 'class'=>'span-21'))
             ->addDecorator('Form');
		
//		$objetocontrato    = new ObjetoContrato();
		$profissionalObjetoContrato = new ProfissionalObjetoContrato();
//		$arrObjetoContrato = $objetocontrato->getObjetoContratoAtivo(null, true, false);
		$cd_profissional_logado     = $_SESSION["oasis_logged"][0]["cd_profissional"];
		$arrObjetoContrato          = $profissionalObjetoContrato->getObjetoProfissionalObjetoContrato($cd_profissional_logado);
		
		$unidade    = new Unidade();
		$arrUnidade = $unidade->getUnidade(true);
	
        $documentoOrigem    = new DocumentoOrigem();
		$arrDocumentoOrigem = $documentoOrigem->getDocumentoOrigem(true);
        
		$ni_solicitacao     = new Base_Form_Element_Hidden('ni_solicitacao');
		$ni_ano_solicitacao = new Base_Form_Element_Hidden('ni_ano_solicitacao');

		$cd_objeto = new Base_Form_Element_Select('cd_objeto', array('class'=>'span-5 float-l'));
		$cd_objeto->setLabel(Base_Util::getTranslator('L_VIEW_OBJETO'))
		->addDecorator('Label', array('class'=>'span-4 float-l right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_objeto->addMultiOptions($arrObjetoContrato);

		$st_solicitacao = new Base_Form_Element_Select('st_solicitacao', array('class'=>'span-5 float-l'));
		$st_solicitacao->setLabel(Base_Util::getTranslator('L_VIEW_TIPO'))
		->addDecorator('Label', array('class'=>'span-4 float-l right gapLeft'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		
		$tx_solicitante = new Base_Form_Element_Text('tx_solicitante', array('class'=>'span-5 float-l'));
		$tx_solicitante->setLabel(Base_Util::getTranslator('L_VIEW_NOME'))
		->addDecorator('Label', array('class'=>'span-4 float-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_sala_solicitante = new Base_Form_Element_Text('tx_sala_solicitante', array('class'=>'span-5 float-l'));
		$tx_sala_solicitante->setLabel(Base_Util::getTranslator('L_VIEW_SALA'))
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_telefone_solicitante = new Base_Form_Element_Telefone('tx_telefone_solicitante', array('class'=>'span-3 float-l'));
		$tx_telefone_solicitante->setLabel(Base_Util::getTranslator('L_VIEW_TELEFONE'))
		->addDecorator('Label', array('class'=>'span-4 float-l right'))
		->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-9 gapLeft'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$cd_unidade = new Base_Form_Element_Select('cd_unidade', array('class'=>'span-5 float-l'));
		$cd_unidade->setLabel(Base_Util::getTranslator('L_VIEW_UNIDADE'))
		->addDecorator('Label', array('class'=>'span-4 float-l right gapLeft'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_unidade->addMultiOptions($arrUnidade);
	
		$tx_solicitacao = new Base_Form_Element_EditorHtml('tx_solicitacao',array('class'=>'span-14 float-l height-6','editor'=>'1073804283'));
		$tx_solicitacao->setLabel(Base_Util::getTranslator('L_VIEW_DESCRICAO_SOLICITACAO'))
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l border', 'editor'=>'editor'))
		->setRequired(true)
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$ni_prazo_atendimento = new Base_Form_Element_SoNumero('ni_prazo_atendimento', array('class'=>'span-5 float-l'));
		$ni_prazo_atendimento->setLabel(Base_Util::getTranslator('L_VIEW_PRAZO_ATENDIMENTO').' ('.Base_Util::getTranslator('L_VIEW_DIAS').')')
		->setRequired(true)
		->addDecorator('Label', array('class'=>'span-4 float-l right'));

        $cd_documento_origem = new Base_Form_Element_Select('cd_documento_origem', array('class'=>'span-5 float-l'));
		$cd_documento_origem->setLabel(Base_Util::getTranslator('L_VIEW_DOCUMENTO_ORIGEM'))
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
		->setRegisterInArrayValidator(false)
		->setRequired(false);
		$cd_documento_origem->addMultiOptions($arrDocumentoOrigem);
		
		$tx_obs_solicitacao = new Base_Form_Element_Textarea('tx_obs_solicitacao', array('class'=>'span-14 float-l height-3'));
		$tx_obs_solicitacao->setLabel(Base_Util::getTranslator('L_VIEW_OBSERVACAO'))
		->addDecorator('Label', array('class'=>'span-4 float-l right clear-l'))
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');


        $caddocorigem = new Base_Form_Element_Button('cadorigem'); 
        $caddocorigem->setAttrib('id', 'caddocorigembutton');
        $caddocorigem->setAttrib('class', 'azul float-l');
        $caddocorigem->setLabel(Base_Util::getTranslator('L_VIEW_CADASTRAR_ORIGEM'));
        
		$submit = new Base_Form_Element_Button('submit');
		$submit->setAttrib('id', 'submitbutton');
		$submit->setAttrib('class', 'verde buttonBar');

		$this->addElements(array(
								$ni_solicitacao, 
								$ni_ano_solicitacao, 
								$cd_objeto, 
								$st_solicitacao,
								$tx_solicitante, 
								$tx_sala_solicitante, 
								$tx_telefone_solicitante, 
								$cd_unidade,
								$tx_solicitacao, 
								$tx_obs_solicitacao, 
                                $cd_documento_origem,
                                $caddocorigem,
								$ni_prazo_atendimento,
								$submit));
	}
}