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

class CriarParcelaController extends Base_Controller_Action
{
	private $parcela;
	private $objContratoDefinicaoMetrica;
	private $objContrato;
	
	public function init()
	{
		parent::init();
		$this->parcela                     = new Parcela($this->_request->getControllerName());
		$this->objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());
		$this->objContrato				   = new Contrato($this->_request->getControllerName());
	}
	
	public function indexAction()
	{}
	
	public function criarParcelaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_projeto               = (int)$this->_request->getParam('cd_projeto', 0);
		$cd_proposta              = (int)$this->_request->getParam('cd_proposta', 0);
		$ni_mes_previsao_parcela  = (int)$this->_request->getParam('mes', 0);
		$ni_ano_previsao_parcela  = (int)$this->_request->getParam('ano', 0);
		$ni_horas_parcela         = $this->_request->getParam('horas_parcela', 0);
		$ni_parcela               = (int)$this->_request->getParam('proxima_parcela_hidden', 0);
		
		$novo 			     	       = $this->parcela->createRow();
		$novo->cd_parcela 	           = $this->parcela->getNextValueOfField('cd_parcela');
		$novo->cd_projeto              = $cd_projeto;
		$novo->cd_proposta             = $cd_proposta ;
		$novo->ni_mes_previsao_parcela = $ni_mes_previsao_parcela ;
		$novo->ni_ano_previsao_parcela = $ni_ano_previsao_parcela ;
		$novo->ni_horas_parcela        = $ni_horas_parcela ;
		$novo->ni_parcela              = $ni_parcela ;

		$quantidadeHorasDisponivel = $this->parcela->getHorasDisponivelProjeto($cd_projeto,$cd_proposta);

        if (number_format($quantidadeHorasDisponivel,1,'.','') - number_format($novo->ni_horas_parcela,1,'.','') >= 0){
    
            if($novo->save()){
                echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
            } else {
                echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
            }
       }else{
        
           echo Base_Util::getTranslator('L_MSG_UlTRAPASSOU_QUANTIDADE_DISPONIVEL');
       }
	}
	
	public function horasProjetoDisponivelAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_projeto  = (int)$this->_request->getParam('cd_projeto', 0);
		$cd_proposta = (int)$this->_request->getParam('cd_proposta', 0);
		
		$quantidadeHorasDisponivel = $this->parcela->getHorasDisponivelProjeto($cd_projeto,$cd_proposta);
		echo $quantidadeHorasDisponivel;
	}
	
	public function pesquisaParcelasPropostaAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_projeto  = (int)$this->_request->getParam('cd_projeto', 0);
		$cd_proposta = (int)$this->_request->getParam('cd_proposta', 0);

		$arrParcelaProposta = $this->parcela->getParcelaProposta($cd_projeto,$cd_proposta,true);

		//recupera os dados do contrato ativo ao qual pertence o projeto em questao
		//para obter a métrica padrão definida para o contrato e mostrar a sigla da métrica padrão
		$arrDadosContrato				= $this->objContrato->getDadosContratoAtivoObjetoTipoProjeto($cd_projeto);
		$this->arrDadosMetricaPadrao	= $this->objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica($arrDadosContrato['cd_contrato']);
		
		$this->view->unidadePadraoMetrica = ( count($this->arrDadosMetricaPadrao) > 0 ) ? $this->arrDadosMetricaPadrao[0]['tx_sigla_metrica'] : null ;
		$this->view->res = $arrParcelaProposta;
	}
	
	/**
	 * Este Método só pode ser acessado por ajax.
	 * @access public
	 * @author Wunilberto Melo
	 * @deprecated 1.0 - 17/09/2008
	 * @param $cd_projeto
	 * @param $cd_proposta
	 * @param $ni_mes_previsao_parcela
	 * @param $ni_ano_previsao_parcela
	 * @param $ni_horas_parcela
	 * @param $ni_parcela
	 */
	
	public function alterarParcelaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_parcela               = (int)$this->_request->getParam('cd_parcela', 0);
		$cd_projeto               = (int)$this->_request->getParam('cd_projeto', 0);
		$cd_proposta              = (int)$this->_request->getParam('cd_proposta', 0);
		$ni_mes_previsao_parcela  = (int)$this->_request->getParam('mes', 0);
		$ni_ano_previsao_parcela  = (int)$this->_request->getParam('ano', 0);
		$ni_horas_parcela         = $this->_request->getParam('horas_parcela', 0);

		$arrUpdate['cd_parcela']              = $cd_parcela;
		$arrUpdate['cd_projeto']              = $cd_projeto;
		$arrUpdate['cd_proposta']             = $cd_proposta;
		$arrUpdate['ni_mes_previsao_parcela'] = $ni_mes_previsao_parcela; 
		$arrUpdate['ni_ano_previsao_parcela'] = $ni_ano_previsao_parcela; 
		$arrUpdate['ni_horas_parcela']        = $ni_horas_parcela; 

		$whereParcela = "cd_parcela = {$cd_parcela} and cd_projeto = {$cd_projeto} and  cd_proposta = {$cd_proposta}";
        
        $valor_parcela_if = $this->parcela->getValorParcelaProposta($cd_projeto,$cd_proposta,$cd_parcela);

        if ($arrUpdate['ni_horas_parcela'] <= $valor_parcela_if ){
            if($this->parcela->update($arrUpdate,$whereParcela)){
                echo Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
            } else{ 
                echo Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
            }
        }else{
            $quantidadeHorasDisponivel = $this->parcela->getHorasDisponivelProjeto($cd_projeto,$cd_proposta);
           if (($quantidadeHorasDisponivel + $valor_parcela_if) - $arrUpdate['ni_horas_parcela'] >= 0){
                if($this->parcela->update($arrUpdate,$whereParcela)){
                    echo Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
                } else{ 
                    echo Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
                }
           }else{
               echo Base_Util::getTranslator('L_MSG_UlTRAPASSOU_QUANTIDADE_DISPONIVEL');
           } 
        }  
	}
	
	/**
	 * Este Método só pode ser acessado por ajax.
	 * @deprecated Método que exclui a parcela da proposta
	 * @access public
	 * @author Wunilberto Melo
	 * @deprecated 1.0 - 17/09/2008
	 * @param $cd_projeto
	 * @param $cd_proposta
	 * @param $ni_mes_previsao_parcela
	 * @param $ni_ano_previsao_parcela
	 * @param $ni_horas_parcela
	 * @param $ni_parcela
	 */
	public function excluirParcelaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
        $produtoParcela       = new ProdutoParcela();
        $processamentoParcela = new ProcessamentoParcela();
		
		$cd_parcela               = (int)$this->_request->getParam('cd_parcela', 0);
		$cd_projeto               = (int)$this->_request->getParam('cd_projeto', 0);
		$cd_proposta              = (int)$this->_request->getParam('cd_proposta', 0);

		$whereParcela = "cd_parcela = {$cd_parcela} and cd_projeto = {$cd_projeto} and  cd_proposta = {$cd_proposta}";

        if ($produtoParcela->fetchAll($whereParcela)->valid()) {
            throw new Exception(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_PARCELA_PRODUTO'));
        }
        
        if ($processamentoParcela->fetchAll($whereParcela)->valid()) {
            throw new Exception(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_PARCELA_AUTORIZADA'));
        }
		
		if($this->parcela->delete($whereParcela)){
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else{
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
	
	public function pesquisaParcelaAction()
	{

		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto  = (int)$this->_request->getParam('cd_projeto', 0);
		$cd_proposta = (int)$this->_request->getParam('cd_proposta', 0);
		
		$parcela = new Parcela($this->_request->getControllerName());
		$res = $parcela->getParcelaProposta($cd_projeto, $cd_proposta, true);

		$strOptions = "<option label=\"".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."\" value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";

		foreach ($res as $parcela) {
			$strOptions .= "<option label=\"".Base_Util::getTranslator('L_VIEW_PARCELA_NR')." {$parcela['ni_parcela']}\" value=\"{$parcela['cd_parcela']}\">".Base_Util::getTranslator('L_VIEW_PARCELA_NR')." {$parcela['ni_parcela']}</option>";
		}

		echo $strOptions;

	}

	public function pesquisaUltimaParcelaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto  = (int)$this->_request->getParam('cd_projeto', 0);

		echo $this->parcela->getProximaParcela($cd_projeto);
	}
}