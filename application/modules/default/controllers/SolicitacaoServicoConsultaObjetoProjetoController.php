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

class SolicitacaoServicoConsultaObjetoProjetoController extends Base_Controller_Action
{
	private $objSolicitacao;

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_SOLICITACOES_DE_SERVICO'));
		$this->objSolicitacao                     = new Solicitacao($this->_request->getControllerName());
	}

	public function indexAction()
	{}

	public function gridSolicitacaoServicoConsultaAction()
	{
		$this->_helper->layout->disableLayout();

		$solicitacao     = ($this->_request->getParam('solicitacao')    ) ? $this->_request->getParam('solicitacao')                    : null;
		$cd_unidade      = ($this->_request->getParam('cd_unidade')     ) ? $this->_request->getParam('cd_unidade')                     : null;
		$tx_solicitante  = ($this->_request->getParam('tx_solicitante') ) ? $this->toUpper($this->_request->getParam('tx_solicitante')) : null;
		$dt_inicio       = ($this->_request->getParam('dt_inicio')      ) ? Base_Util::converterDate($this->_request->getParam('dt_inicio'), 'DD/MM/YYYY', 'YYYY-MM-DD') : null;
		$dt_fim          = ($this->_request->getParam('dt_fim')         ) ? Base_Util::converterDate($this->_request->getParam('dt_fim'), 'DD/MM/YYYY', 'YYYY-MM-DD')    : null;
		$tx_solicitacao  = ($this->_request->getParam('tx_solicitacao') ) ? $this->toUpper($this->_request->getParam('tx_solicitacao')) : null;
		$tipo_consulta   = ($this->_request->getParam('tipo_consulta')  ) ? $this->toUpper($this->_request->getParam('tipo_consulta'))  : "AND";

		if (!is_null($solicitacao) && strpos($solicitacao, "/") != 0){
			$arrSolicitacao     = explode("/", $solicitacao);
			$ni_solicitacao     = $arrSolicitacao[0];	
			$ni_ano_solicitacao = $arrSolicitacao[1];	
		}
		else{
			$ni_solicitacao     = null;	
			$ni_ano_solicitacao = null;	
		}
		
		$cd_objeto                     = $_SESSION['oasis_logged'][0]['cd_objeto'];	
		$arrSolicitacaoServicoConsulta = $this->objSolicitacao->getSolicitacaoServicoConsulta($cd_objeto, $cd_unidade, $ni_solicitacao, $ni_ano_solicitacao, $tx_solicitante, $dt_inicio, $dt_fim, $tx_solicitacao, $tipo_consulta);
		$this->view->res = $arrSolicitacaoServicoConsulta;
	}
			
	public function tabDetalheSolicitacaoAction()
	{
		$this->_helper->layout->disableLayout();
				
		$cd_objeto          = (int)$this->_request->getParam('cd_objeto', 0);
		$ni_solicitacao     = (int)$this->_request->getParam('ni_solicitacao', 0);
		$ni_ano_solicitacao = (int)$this->_request->getParam('ni_ano_solicitacao', 0);
		$tab_origem         = (int)$this->_request->getParam('tab_origem', 0);

		// Consulta Solicitacao
		$solicitacao 	= new Solicitacao($this->_request->getControllerName());
		$resSolicitacao = $solicitacao->getSolicitacao($cd_objeto, $ni_solicitacao, $ni_ano_solicitacao);
		
		$this->view->solicitacao = $resSolicitacao;
		$this->view->tab_origem  = $tab_origem;
	}	
}