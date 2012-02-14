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

class MatrizRastreabilidadeCasoDeUsoController extends Base_Controller_Action
{
	private $projeto;
	private $objContrato;
	private $objContratoProjeto;
	private $objRelatorio_CasoDeUso;
	private $objAnaliseMatrizRastreabilidade;
		
	public function init()
	{
		parent::init();
        Zend_Loader::loadClass('RelatorioProjetoCasoDeUso', Base_Util::baseUrlModule('relatorioProjeto','models'));
		$this->projeto			  				= new Projeto($this->_request->getControllerName());
		$this->objContrato 		  				= new Contrato($this->_request->getControllerName());
		$this->objContratoProjeto 				= new ContratoProjeto($this->_request->getControllerName());
		$this->objRelatorio_CasoDeUso			= new RelatorioProjetoCasoDeUso();
		$this->objAnaliseMatrizRastreabilidade	= new AnaliseMatrizRastreabilidade($this->_request->getControllerName());
	}

    /**
     * Action da tela de inicial
     */
    public function indexAction()
    {
    	$cd_contrato = null;
    	$comStatus	 = true;
		
		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		$this->view->arrContrato = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
    }

    /**
     * Action de geração do relatório de Matriz de Rastreabilidade na tela
     */
    public function matrizRastreabilidadeCasoDeUsoAction()
    {
       $this->_helper->layout->disableLayout();
	   
	   $cd_projeto				= (int) $this->_request->getParam('cd_projeto', 0);
       
	   //recupera os casos de uso
	   $arrCasoDeUso			= $this->objRelatorio_CasoDeUso->getCasoDeUsoMatrizRastreabilidade( $cd_projeto );
	   $arrCasoDeUsoDependente  = $this->objRelatorio_CasoDeUso->getCasoDeUsoDependente( $cd_projeto );
	   $arrDados                = array();
	   $arrRequisitoPai         = array();
	   $cd_requisito_pai        = 0;

	   foreach($arrCasoDeUsoDependente as $casoDeUso){
	   		$arrDados[$casoDeUso["cd_requisito_pai"]][] = array("filho"=>"{$casoDeUso["cd_caso_de_uso_filho"]}","st_fechamento_pai"=>"{$casoDeUso["st_fechamento_requisito_pai"]}");

	   		if( $cd_requisito_pai != $casoDeUso["cd_requisito_pai"] ){
	   			$arrRequisitoPai[$casoDeUso["cd_requisito_pai"]] = $casoDeUso["tx_requisito_pai"]."|".$casoDeUso["ni_ordem_pai"];
                $cd_requisito_pai    = $casoDeUso["cd_requisito_pai"];
	   		}
	   }
	   
        $this->view->arrDados        = $arrDados;
        $this->view->arrRequisitoPai = $arrRequisitoPai;
        $this->view->arrCasoDeUso    = $arrCasoDeUso;
        $this->view->qtdRequisitoPai = count($arrRequisitoPai);
    }

    /**
     * Action de geração do relatório de Matriz de Rastreabilidade para impressao
     */
    public function generateAction()
    {
       $this->_helper->layout->disableLayout();
	   
       $cd_projeto				= (int) $this->_request->getParam('cd_projeto_matriz_caso_de_uso', 0);
       
	   //recupera os casos de uso
	   $arrCasoDeUso			= $this->objRelatorio_CasoDeUso->getCasoDeUsoMatrizRastreabilidade( $cd_projeto );
	   $arrCasoDeUsoDependente	= $this->objRelatorio_CasoDeUso->getCasoDeUsoDependente( $cd_projeto );
	   $arrDadosProjeto			= $this->projeto->getDadosProjeto($cd_projeto);
		//recupera a analise da matriz que se encontra aberta, caso haja
		$arrDadosAnalise		= $this->objAnaliseMatrizRastreabilidade->getVersaoAbertaAnaliseMatrizRastreabilidade( $cd_projeto, 'RC');
	   
	   $arrDados                = array();
	   $arrRequisitoPai         = array();
	   $cd_requisito_pai        = 0;
	   
	   foreach($arrCasoDeUsoDependente as $casoDeUso){
	   		$arrDados[$casoDeUso["cd_requisito_pai"]][] = array("filho"=>"{$casoDeUso["cd_caso_de_uso_filho"]}","st_fechamento_pai"=>"{$casoDeUso["st_fechamento_requisito_pai"]}");

	   		if( $cd_requisito_pai != $casoDeUso["cd_requisito_pai"] ){
	   			$arrRequisitoPai[$casoDeUso["cd_requisito_pai"]] = $casoDeUso["tx_requisito_pai"]."|".$casoDeUso["ni_ordem_pai"];
                $cd_requisito_pai    = $casoDeUso["cd_requisito_pai"];
	   		}
	   }
        $this->view->arrDadosProjeto = $arrDadosProjeto;
        $this->view->arrDados        = $arrDados;
        $this->view->arrRequisitoPai = $arrRequisitoPai;
        $this->view->arrCasoDeUso    = $arrCasoDeUso;
		$this->view->arrDadosAnalise = (count($arrDadosAnalise) > 0) ? $arrDadosAnalise[0] : $arrDadosAnalise;
        $this->view->qtdRequisitoPai = count($arrRequisitoPai);
    }

	public function salvarAnaliseMatrizCasoDeUsoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$arrDados	  = $this->_request->getPost();
		
		$arrResult 	  = array('error'=>false, 'errorType'=> '', 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'));
		$dadosRetorno = array('cd_analise_matriz_rastreab'=>'',
							  'dt_analise_matriz_rastreab'=>'',
							  'insert'=>true
							 );
		
		$db			  = Zend_Registry::get('db');
		$db->beginTransaction();

		try{
			if( !ChecaPermissao::possuiPermissao('matriz-rastreabilidade-caso-de-uso') ){
				throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'));
			}
			if( (empty($arrDados['cd_analise_matriz_rastreabilidade_caso_de_uso']) || is_null($arrDados['cd_analise_matriz_rastreabilidade_caso_de_uso'])) && 
			    (empty($arrDados['dt_analise_matriz_rastreabilidade_caso_de_uso']) || is_null($arrDados['dt_analise_matriz_rastreabilidade_caso_de_uso']))
			   ){
			   	//insert
			   	
			   	$cd_analise_matriz_rastreab			= $this->objAnaliseMatrizRastreabilidade->getNextValueOfField('cd_analise_matriz_rastreab');
			   	$dt_analise_matriz_rastreab			= date('Y-m-d');
			   	
			   	$novo										= $this->objAnaliseMatrizRastreabilidade->createRow();
			   	$novo->cd_analise_matriz_rastreab	= $cd_analise_matriz_rastreab;
			   	$novo->cd_projeto							= $arrDados['cd_projeto_matriz_caso_de_uso'];
			   	$novo->dt_analise_matriz_rastreab	= new Zend_Db_Expr("{$this->objAnaliseMatrizRastreabilidade->to_date("'{$dt_analise_matriz_rastreab}'", 'YYYY-MM-DD')}");
			   	$novo->tx_analise_matriz_rastreab	= strip_tags($arrDados['tx_analise_matriz_rastreabilidade_caso_de_uso']);
			   	$novo->st_matriz_rastreabilidade			= "RC";
			   	
			   	if( !$novo->save() ){
			   		$db->rollBack();
			   		throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
			   	}else{
			   		$dadosRetorno['cd_analise_matriz_rastreab'] = $cd_analise_matriz_rastreab;
			   		$dadosRetorno['dt_analise_matriz_rastreab'] = $dt_analise_matriz_rastreab;
			   	}
			}else{
				//update
				
				$arrUpdate['tx_analise_matriz_rastreab'] = strip_tags($arrDados['tx_analise_matriz_rastreabilidade_caso_de_uso']);
				
				$where  = "cd_analise_matriz_rastreab = {$arrDados['cd_analise_matriz_rastreabilidade_caso_de_uso']} 	and ";
				$where .= "cd_projeto						 = {$arrDados['cd_projeto_matriz_caso_de_uso']}			and ";
				$where .= "st_matriz_rastreabilidade		 = 'RC' 														and ";
				$where .= "dt_analise_matriz_rastreab = {$this->objAnaliseMatrizRastreabilidade->to_date("'{$arrDados['dt_analise_matriz_rastreabilidade_caso_de_uso']}'", 'YYYY-MM-DD')} ";
				
				if( !$this->objAnaliseMatrizRastreabilidade->update($arrUpdate, $where) ){
			   		$db->rollBack();
					throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
				}else{
			   		$dadosRetorno['insert'] = false;
				}
			}
			
			$db->commit();
			
		}catch(Base_Exception_Alert $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 2;
            $arrResult['msg'      ] = $e->getMessage();
		}catch(Base_Exception_Error $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Zend_Exception $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
        }
		echo Zend_Json::encode( array($arrResult,$dadosRetorno) );
	}
    
   public function fecharAnaliseMatrizRastreabilidadeAction()
    {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$arrDados  = $this->_request->getPost();

		$arrResult = array('error'=>false, 'errorType'=> '', 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_ANALISE_FECHADA'));
		
		$db		   = Zend_Registry::get('db');
		$db->beginTransaction();

		try{
			if( !ChecaPermissao::possuiPermissao('matriz-rastreabilidade-caso-de-uso') ){
				throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'));
			}
			if( !empty($arrDados['cd_analise_matriz_rastreabilidade_caso_de_uso']) && !is_null($arrDados['cd_analise_matriz_rastreabilidade_caso_de_uso']) ){
				if( !empty($arrDados['dt_analise_matriz_rastreabilidade_caso_de_uso']) && !is_null($arrDados['dt_analise_matriz_rastreabilidade_caso_de_uso']) ){
					
					$arrUpdate['st_fechamento'					  	 ] = "S";
					$arrUpdate['tx_analise_matriz_rastreab'	 ] = strip_tags($arrDados['tx_analise_matriz_rastreabilidade_caso_de_uso']);
			
					$arrCondicoes['cd_analise_matriz_rastreab'] = strip_tags($arrDados['cd_analise_matriz_rastreabilidade_caso_de_uso']);
					$arrCondicoes['dt_analise_matriz_rastreab'] = strip_tags($arrDados['dt_analise_matriz_rastreabilidade_caso_de_uso']);
					$arrCondicoes['cd_projeto'						 ] = strip_tags($arrDados['cd_projeto_matriz_caso_de_uso']);
					$arrCondicoes['st_matriz_rastreabilidade'		 ] = "RC";
					
					if( !$this->objAnaliseMatrizRastreabilidade->fechaAnaliseMatrizRastreablidade( $arrUpdate, $arrCondicoes ) ){
						throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ANALISE_FECHADA'));
					}
				}
			}
			$db->commit();
			
		}catch(Base_Exception_Alert $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 2;
            $arrResult['msg'      ] = $e->getMessage();
		}catch(Base_Exception_Error $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Zend_Exception $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
        }
		echo Zend_Json::encode($arrResult);
    }
}