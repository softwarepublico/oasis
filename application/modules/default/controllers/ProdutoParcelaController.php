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

class ProdutoParcelaController extends Base_Controller_Action 
{
	private $produtoParcela;
	private $parcela;
	private $objUtil;
	
	
	public function init() 
    {
		parent::init ();
		$this->produtoParcela = new ProdutoParcela($this->_request->getControllerName());
		$this->parcela        = new Parcela($this->_request->getControllerName());
		$this->objUtil        = new Base_Controller_Action_Helper_Util();
	}
	
	public function indexAction() 
	{
	}
	
	/**
	 * Método desenvolvido para verificar se a parcela possui produto
	 * Método utilizado por ajax
	 * 
	 * @author wunilberto.melo
	 * @since 22/09/2008
	 */
	public function parcelaComProdutosAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_projeto        = $this->_request->getParam('cd_projeto');
		$cd_proposta 	   = $this->_request->getParam('cd_proposta');
		
		$arrProdutos = $this->produtoParcela->getParcelaComProdutos($cd_projeto, $cd_proposta);
		
		if($arrProdutos){
			$strParcelas      = "";
            $descPrev = "";
            $cd_parcela_temp  = 0;
            $ni_previsao_temp = 0;
            foreach($arrProdutos as $key=>$value){
                $cod_parcela      = $arrProdutos[$key]['cd_parcela'];
                $ni_parcela       = $arrProdutos[$key]['ni_parcela'];
                $ni_horas_parcela = $arrProdutos[$key]['ni_horas_parcela'];
                $ni_previsao      = $value['ni_mes_previsao_parcela']."/".$value['ni_ano_previsao_parcela'];
                
                if($cd_parcela_temp != $ni_parcela){
                	if($value['ni_mes_previsao_parcela']){
                        $descMes   = $this->objUtil->getMesRes($value['ni_mes_previsao_parcela']);
                        $descPrev  = "<b>(".$descMes."/".$value['ni_ano_previsao_parcela'].")</b>";
                	} else {
                		$descPrev = "<b>(XX/XXXX)</b>";
                	}
                    $ni_parcela   = substr("0".$ni_parcela,-2);
                    $strParcelas  .= "<a style='cursor:pointer;' onClick='ajaxCriarProdutoParcela({$cod_parcela},{$ni_parcela},{$ni_horas_parcela})'>{$ni_parcela}</a>".$descPrev.", ";
                } 
                
                $cd_parcela_temp  = $value['ni_parcela'];
                $ni_previsao_temp = $value['ni_mes_previsao_parcela']."/".$value['ni_ano_previsao_parcela'];
            }
            $strParcelas = substr($strParcelas,0,-2);
            echo $strParcelas;
		} else {
			echo "<label class='bold' style='padding-left: 22px;'>".Base_Util::getTranslator('L_MSG_ALERT_NAO_POSSUI_PARCELA')."</label>";
		}
	}
	
	/**
	 * Método desenvolvido para verificar se a parcela não possui produto
	 * Método utilizado por ajax
	 * 
	 * @author wunilberto.melo
	 * @since 22/09/2008
	 */
	public function parcelaSemProdutosAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_projeto        = $this->_request->getParam('cd_projeto');
		$cd_proposta 	   = $this->_request->getParam('cd_proposta');

		$arrProdutos = $this->produtoParcela->getParcelaSemProdutos($cd_projeto, $cd_proposta);
		
		if($arrProdutos){
            $strParcelas  = "";
            $descPrev = "";
            $cd_parcela_temp  = 0;
            $ni_previsao_temp = 0;
            foreach($arrProdutos as $key=>$value){
                $cod_parcela      = $arrProdutos[$key]['cd_parcela'];
                $ni_parcela       = $arrProdutos[$key]['ni_parcela'];
                $ni_horas_parcela = $arrProdutos[$key]['ni_horas_parcela'];                
                $ni_previsao      = $value['ni_mes_previsao_parcela']."/".$value['ni_ano_previsao_parcela'];
                
                if($cd_parcela_temp != $ni_parcela){
                    $descMes   = $this->objUtil->getMesRes($value['ni_mes_previsao_parcela']);
                    $descPrev  = "<b>(".$descMes."/".$value['ni_ano_previsao_parcela'].")</b>";
                    
                    $ni_parcela   = substr("0".$ni_parcela,-2);
                    $strParcelas  .= "<a style='cursor:pointer;' onClick='ajaxCriarProdutoParcela({$cod_parcela},{$ni_parcela},{$ni_horas_parcela})'>{$ni_parcela}</a>".$descPrev.", ";
                } 
                
                $cd_parcela_temp  = $value['ni_parcela'];
                $ni_previsao_temp = $value['ni_mes_previsao_parcela']."/".$value['ni_ano_previsao_parcela'];
            }
            $strParcelas = substr($strParcelas,0,-2);
            echo $strParcelas;
		} else {
			echo "<label class='bold' style='padding-left: 22px;'>".Base_Util::getTranslator('L_MSG_ALERT_NAO_POSSUI_PARCELA')."</label>";
		}
	}
	
	/**
	 * Método desenvolvido para pesquisar os produtos cadastrados
	 * da parcela, montando uma grid com duas colunas
	 * 1 Coluna para exluir o produto e a 2 para mostrar a descrição do 
	 * produto
	 * 
	 * @author wunilberto.melo
	 * @since 22/09/2008
	 */
	public function pesquisaProdutoParcelaAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_projeto      = $this->_request->getParam('cd_projeto');
		$cd_proposta     = $this->_request->getParam('cd_proposta');
		$cd_parcela      = $this->_request->getParam('cd_parcela');
		$escondeExclusao = $this->_request->getParam('escondeExclusao',0); 
		
		//Verifica se o flag escondeExclusao já veio setado. Ele sempre virá setado quando este método for chamado
		//da tela Execução de Proposta
		if (empty($escondeExclusao))
		{
			//Verifica se a parcela mãe do produto é um parcela relacionada ao orçamento da proposta (st_modulo_proposta = S)
			//Se for, o produto da parcela não pode ser excluído nem alterado e não se pode incluir produto na parcela
			//O flag escondeExclusao servirá para todos estes propósitos
			//Verifica também se a parcela já foi autorizada. Se já foi autorizada, seus produtos não podem ser alterados
			//nem um novo produto pode ser incluído
			$rowParcela = $this->parcela->fetchRow("cd_parcela = {$cd_parcela}")->toArray();
			if ($rowParcela["st_modulo_proposta"] == "S" || (!is_null($rowParcela["ni_mes_execucao_parcela"]) && !is_null($rowParcela["ni_ano_execucao_parcela"]))){
				$escondeExclusao = "S";
			}
		}
		
		$arrProdutoParcela = $this->produtoParcela->pesquisaProdutoParcela($cd_projeto,$cd_proposta,$cd_parcela);
		
		$this->view->res             = $arrProdutoParcela;
		$this->view->escondeExclusao = $escondeExclusao;
	}
	
	/**
	 * Método desenvolvido para salvar os produtos na parcela da proposta
	 * M'etodo utilizado por ajax
	 * @author wunilberto.melo
	 * @since 22/09/2008
	 */
	public function salvarProdutoParcelaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto  		= (int)$this->_request->getParam('cd_projeto');
		$cd_proposta 		= (int)$this->_request->getParam('cd_proposta');
		$cd_parcela  		= (int)$this->_request->getParam('cd_parcela');
		$tx_produto_parcela = $this->_request->getParam('tx_produto_parcela');
		$cd_tipo_produto    = $this->_request->getParam('cd_tipo_produto');
		
		$salvar = $this->produtoParcela->salvarProdutoParcela($cd_projeto,$cd_proposta,$cd_parcela,$tx_produto_parcela,$cd_tipo_produto);
		
		$msg = ($salvar)? Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'):Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		echo $msg;
	}
	
	public function recuperaProdutoParcelaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_produto_parcela = (int)$this->_request->getParam('cd_produto_parcela');
		$arrProduto = $this->produtoParcela->getProdutoParcela($cd_produto_parcela);
		
		$arrRes[0] = $arrProduto[0]['tx_produto_parcela'];
		$arrRes[1] = $arrProduto[0]['cd_tipo_produto'];
		$arrRes[2] = $arrProduto[0]['cd_produto_parcela'];
		
		echo Zend_Json_Encoder::encode($arrRes);
	}
	
	
	public function alterarProdutoParcelaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$tx_produto_parcela = $this->_request->getParam('tx_produto_parcela');
		$cd_produto_parcela = (int)$this->_request->getParam('cd_produto_parcela');
		$cd_projeto  		= (int)$this->_request->getParam('cd_projeto');
		$cd_proposta 		= (int)$this->_request->getParam('cd_proposta');
		$cd_parcela  		= (int)$this->_request->getParam('cd_parcela');
		$cd_tipo_produto 	= $this->_request->getParam('cd_tipo_produto');
		
		$alterar = $this->produtoParcela->alterarProdutoParcela($cd_produto_parcela,$cd_projeto,$cd_proposta,$cd_parcela,$tx_produto_parcela,$cd_tipo_produto);
		
		$msg = ($alterar)? Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO'):Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		echo $msg;
	}
	
	/**
	 * Método desenvolvido para exluir os produtos da parcela
	 * 
	 * @author wunilberto.melo
	 * @since 22/09/2008
	 * @return $msg
	 */
	public function excluirProdutoParcelaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_produto_parcela = $this->_request->getParam('cd_produto_parcela');
		
		$excluir = $this->produtoParcela->excluirProdutoParcela($cd_produto_parcela);
		
		$msg = ($excluir)? Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		echo $msg;
	}
}