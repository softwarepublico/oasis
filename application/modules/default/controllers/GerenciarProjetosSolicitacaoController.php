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

class GerenciarProjetosSolicitacaoController extends Base_Controller_Action
{
	private $zendDate;
	private $objUtil;
	
	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_SOLICITACOES_DE_SERVICO'));
		$this->objUtil      = new Base_Controller_Action_Helper_Util();
		$this->zendDate = new Zend_Date();
	}

	public function indexAction()
	{
		// exibição para conteúdo da página inicial
		$params  = $this->_request->getParams();
		if( $params['pagina'] == "inicio" ){
			$this->_helper->layout->disableLayout();
		}
		
		$mesAtual = $this->zendDate->get(Zend_Date::MONTH_SHORT);
		$anoAtual = date("Y");

		$this->view->mesComboValue = $mesAtual;
		$this->view->anoComboValue = $anoAtual;

		$solicitacao = new Solicitacao($this->_request->getControllerName());
		$solicitacaoSelect = $solicitacao->select();
		$solicitacaoSelect->distinct();
		$solicitacaoSelect->from($solicitacao, array('ni_ano_solicitacao'));
		$resAnoSolicitacao = $solicitacao->fetchAll($solicitacaoSelect);

		$arrAnos = array(Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE'));
		foreach ($resAnoSolicitacao as $ano) {
			$arrAnos[$ano->ni_ano_solicitacao] = $ano->ni_ano_solicitacao;
		}

		$this->view->mesCombo = $this->objUtil->getMes();
		$this->view->anoCombo = $arrAnos;
	}

	public function pesquisaSolicitacoesAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$solicitacao = new Solicitacao($this->_request->getControllerName());

		if (!is_null($this->_request->getParam('mes'))) {
			$mesAtual = $this->_request->getParam('mes');
		} else {
			$mesAtual = $this->zendDate->get(Zend_Date::MONTH_SHORT);
		}

		if (!is_null($this->_request->getParam('ano'))) {
			$anoAtual = $this->_request->getParam('ano');

		} else {
			$anoAtual = date("Y");
		}

		$mesAtual        = substr("00".$mesAtual,-2);
		$cd_objeto       = $_SESSION["oasis_logged"][0]["cd_objeto"];
		$arrSolicitacoes = $solicitacao->getSolicitacaoGerenteProposta($cd_objeto, $mesAtual, $anoAtual)->toArray();
		$arrSolicitacoes = $solicitacao->verificaJustificativa($cd_objeto, $arrSolicitacoes);
		
		$this->view->st_necessita_justificativa = $arrSolicitacoes[0];
		$this->view->ni_minutos_justificativa   = $arrSolicitacoes[1];
		$this->view->res                        = $arrSolicitacoes[2];

		echo $this->view->render('gerenciar-projetos-solicitacao/pesquisa-solicitacoes.phtml');
	}

	public function gravaDataHoraLeituraSolicitacaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$ni_solicitacao     = (int)$this->_request->getParam('ni_solicitacao', 0);
		$ni_ano_solicitacao = (int)$this->_request->getParam('ni_ano_solicitacao', 0);
		$cd_objeto          = (int)$this->_request->getParam('cd_objeto', 0);
		
		$solicitacao    = new Solicitacao($this->_request->getControllerName());
		$rowSolicitacao = $solicitacao->getSolicitacao($cd_objeto, $ni_solicitacao, $ni_ano_solicitacao);
		
		if (is_null($rowSolicitacao['dt_leitura_solicitacao']))
		{
			$addRow = array();
			$addRow['dt_leitura_solicitacao'] = date('Y-m-d H:i:s');
			
			$erros = $solicitacao->atualizaSolicitacao($cd_objeto, $ni_solicitacao, $ni_ano_solicitacao, $addRow);
			
			if ($erros === false)
			{
				echo Base_Util::getTranslator('L_MSG_SUCESS_LER_SOLICITACAO');
			}
			else
			{
				echo Base_Util::getTranslator('L_MSG_ERRO_LER_SOLICITACAO');
			}
		}
		else
		{
			echo Base_Util::getTranslator('L_MSG_ALERT_SOLICITACAO_LIDA_ANTERIORMENTE');
		}
	}
	
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
		$objSolicitacao                = new Solicitacao($this->_request->getControllerName());
		$arrSolicitacaoServicoConsulta = $objSolicitacao->getSolicitacaoServicoConsulta($cd_objeto, $cd_unidade, $ni_solicitacao, $ni_ano_solicitacao, $tx_solicitante, $dt_inicio, $dt_fim, $tx_solicitacao, $tipo_consulta);
		
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