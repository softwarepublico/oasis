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

class ControleEncerramentoPropostaController extends Base_Controller_Action
{
	private	$proposta;
	private $objContratoDefinicaoMetrica;

	public function init()
	{
		parent::init();
		$this->proposta                    = new Proposta($this->_request->getControllerName());
		$this->objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());
	}

	public function indexAction()
	{}
	
	public function gridControleEncerramentoPropostaAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_contrato     = $this->_request->getParam('cd_contrato');	
		$this->view->res = $this->proposta->getPropostasEncerramento($cd_contrato);

		//Busca a métrica indicada como padrão para o contrato
		//e obtém a sigla da métrica
		$this->arrDadosMetricaPadrao	  = $this->objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica($cd_contrato);
		$this->view->unidadePadraoMetrica = ( count($this->arrDadosMetricaPadrao) > 0 ) ? $this->arrDadosMetricaPadrao[0]['tx_sigla_metrica'] : Base_Util::getTranslator('L_VIEW_UNID_METRICA');
	}
}