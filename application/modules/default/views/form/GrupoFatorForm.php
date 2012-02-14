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

class GrupoFatorForm extends Base_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->generate();
	}
	
	private function generate()
	{
		$this->setName('grupo_fator');
        $this->addDecorator('FormElements')
        ->addDecorator('HtmlTag', array('tag'=>'div', 'class'=>'span-21'))
        ->addDecorator('Form');
		
		$cd_grupo_fator      = new Base_Form_Element_Hidden('cd_grupo_fator');
		
		$tx_grupo_fator      = new Base_Form_Element_Text('tx_grupo_fator', array('class'=>'span-8 float-l'));
		$tx_grupo_fator->setLabel(Base_Util::getTranslator('L_VIEW_GRUPO_FATOR').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$ni_peso_grupo_fator = new Base_Form_Element_SoNumero('ni_peso_grupo_fator', array('class'=>'span-3 float-l','maxlength'=>'8'));
		$ni_peso_grupo_fator->setLabel(Base_Util::getTranslator('L_VIEW_PESO').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$ni_indice_grupo_fator = new Base_Form_Element_SoNumero('ni_indice_grupo_fator', array('class'=>'span-3 float-l','maxlength'=>'8'));
		$ni_indice_grupo_fator->setLabel(Base_Util::getTranslator('L_VIEW_INDICE_GRUPO').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$ni_ordem_grupo_fator = new Base_Form_Element_SoNumero('ni_ordem_grupo_fator', array('class'=>'span-3 float-l'));
		$ni_ordem_grupo_fator->setLabel(Base_Util::getTranslator('L_VIEW_NR_ORDEM').':')
		->addDecorator('Label', array('class'=>'span-3 float-l right clear-l'))
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$this->addElements(array($cd_grupo_fator,
								 $tx_grupo_fator,
								 $ni_peso_grupo_fator,
								 $ni_indice_grupo_fator,
								 $ni_ordem_grupo_fator));
	}
}