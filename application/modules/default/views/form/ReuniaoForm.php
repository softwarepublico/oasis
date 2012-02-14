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

class ReuniaoForm extends Zend_Form
{

	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	private function generate()
	{
		$this->setName('reuniao');
		$this->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-21'));
		
		//comentado devido a mudança na regra
//		$projeto    = new Projeto();
//		$arrProjeto = $projeto->getProjeto(true);
		
		//recupera apenas os projetos associados ao contrato
		$objContratoProjeto = new ContratoProjeto();
		$arrProjeto = $objContratoProjeto->listaProjetosContrato( $_SESSION['oasis_logged'][0]['cd_contrato'], true );
		
		$cd_reuniao      = new Base_Form_Element_Hidden('cd_reuniao');
		$cd_profissional = new Base_Form_Element_Hidden('cd_profissional_reuniao');
		$cd_profissional->setValue($_SESSION['oasis_logged'][0]['cd_profissional']);

		$cd_projeto = new Base_Form_Element_Select('cd_projeto_reuniao', array('class'=>'float-l span-5'));
		$cd_projeto->setLabel(Base_Util::getTranslator('L_VIEW_PROJETO').':')
		->addDecorator('Label', array('class'=>'span-3 float-l clear-l right'))
		->setRegisterInArrayValidator(false)
		->setRequired(true);
		$cd_projeto->addMultiOptions($arrProjeto);
		
		$dt_reuniao = new Base_Form_Element_Data('dt_reuniao');
		$dt_reuniao->setLabel(Base_Util::getTranslator('L_VIEW_DATA_REUNIAO').':')
		->addDecorator('Label', array('class'=>'span-3 clear-l float-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_local_reuniao = new Base_Form_Element_Text('tx_local_reuniao', array('class'=>'span-13 float-l'));
		$tx_local_reuniao->setLabel(Base_Util::getTranslator('L_VIEW_LOCAL_REUNIAO').':')
		->addDecorator('Label', array('class'=>'span-3 float-l clear-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_pauta = new Base_Form_Element_Textarea('tx_pauta', array('class'=>'span-13 float-l height-5'));
		$tx_pauta->setLabel(Base_Util::getTranslator('L_VIEW_PAUTA').':')
		->addDecorator('Label', array('class'=>'span-3 float-l clear-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_participantes = new Base_Form_Element_Textarea('tx_participantes', array('class'=>'span-13 float-l height-7'));
		$tx_participantes->setLabel(Base_Util::getTranslator('L_VIEW_PARTICIPANTES').':')
		->addDecorator('Label', array('class'=>'span-3 float-l clear-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$tx_ata = new Base_Form_Element_EditorHtml('tx_ata', array('class'=>'float-l','cols'=>'69','rows'=>'13','editor'=>K_EDITOR));
		$tx_ata->setLabel(Base_Util::getTranslator('L_VIEW_ATA').':')
		->addDecorator('Label', array('class'=>'span-3 float-l clear-l right', 'editor'=>'editor'))
		->setRequired(true)
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$submit = new Base_Form_Element_Button('submit');
		$submit->setAttrib('id', 'submitbuttonReuniao');
		$submit->setAttrib('class', 'verde float-l clear-l push-3');

		$this->addElements(array(
								$cd_reuniao,
								$cd_profissional, 
								$cd_projeto,
								$dt_reuniao,
								$tx_local_reuniao,
								$tx_pauta,
								$tx_participantes,
								$tx_ata,
								$submit));
	}
}