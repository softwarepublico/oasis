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

class GerenciarProjetosExecucaoPropostaController extends Base_Controller_Action
{
	private $zendDate;
	private $objProfissionalProjeto;
	private $objContratoDefinicaoMetrica;
	
	public function init()
	{
		parent::init();
		$this->zendDate = new Zend_Date();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_EXECUCAO_PROPOSTA'));
		$this->objProfissionalProjeto = new ProfissionalProjeto($this->_request->getControllerName());
		$this->objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());
	}

	public function indexAction()
	{
	}

	public function pesquisaPropostasEmExecucaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$proposta = new Proposta($this->_request->getControllerName());

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

		$mesAtual  = substr("00".$mesAtual,-2);
		$cd_objeto = $_SESSION["oasis_logged"][0]["cd_objeto"];
		
		$arrDados = $proposta->getPropostasEmExecucao($cd_objeto, $mesAtual, $anoAtual);
		
		if(K_CODIGO_PERFIL_TECNICO == $_SESSION['oasis_logged'][0]['cd_perfil']){
			$cd_profissional = $_SESSION['oasis_logged'][0]['cd_profissional'];
			$arrProjetosProfissional = $this->objProfissionalProjeto->pesquisaProjetoPorProfissional(false,$cd_profissional, $mesAtual, $anoAtual);
			
			foreach($arrDados as $key=>$value){
				$possui = "N";
				foreach($arrProjetosProfissional as $chave=>$valor){
					if($arrDados[$key]['cd_projeto'] == $chave){
						$possui = "S";				
					}
				}
				if($possui != "S"){
					unset($arrDados[$key]);
				}
			}
		}

		foreach($arrDados as $key=>$value)
		{
			$siglaMetricaPadraoContrato = $this->objContratoDefinicaoMetrica->getSiglaUnidadeMetricaPadraoContratoAtivoProjeto($value["cd_projeto"]);
			$siglaMetricaPadrao         = (count($siglaMetricaPadraoContrato)>0)? $siglaMetricaPadraoContrato["tx_sigla_unidade_metrica"] : Base_Util::getTranslator('L_VIEW_UNIDADES');
			$arrDados[$key]["ni_horas_parcela"] = "{$arrDados[$key]["ni_horas_parcela"]} {$siglaMetricaPadrao}";
		}
		
		$this->view->res = $arrDados;
		echo $this->view->render('gerenciar-projetos-execucao-proposta/pesquisa-execucao-proposta.phtml');
	}

	public function produtosParcelaAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_parcela = $this->_request->getParam('cd_parcela');
		
		$produtosParcela = new ProdutoParcela($this->_request->getControllerName());
		$this->view->res = $produtosParcela->getProdutosDaParcela($cd_parcela);
	}

	public function profissionalProjetoAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_projeto      = $this->_request->getParam('cd_projeto');
        $cd_objeto       = $_SESSION["oasis_logged"][0]["cd_objeto"];

        $objResult = $this->objProfissionalProjeto->getProfissionalPapelProfissionalOrderPapel($cd_objeto, $cd_projeto, true);

        if($objResult->valid()){
            $res = array();
            $cd_papel_profissional = '';
            foreach($objResult as $key=>$value) {
                if($cd_papel_profissional != $value->cd_papel_profissional){
                    if($cd_papel_profissional != ''){
                        $res[$value->tx_papel_profissional][] = $value->tx_profissional;
                        $cd_papel_profissional = $value->cd_papel_profissional;
                    }
                    $res[$value->tx_papel_profissional][] = $value->tx_profissional;
                }
            }
        }else{
            $res = $objResult->toArray();
        }

        $this->view->res = $res;
	}
}