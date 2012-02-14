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

class MatrizRastreabilidadeRequisitoController extends Base_Controller_Action
{
	private $projeto;
	private $objContrato;
	private $objContratoProjeto;
	private $objAnaliseMatrizRastreabilidade;
	
	public function init()
	{
		parent::init();
		$this->projeto							= new Projeto($this->_request->getControllerName());
		$this->objContrato						= new Contrato($this->_request->getControllerName());
		$this->objContratoProjeto				= new ContratoProjeto($this->_request->getControllerName());
		$this->objAnaliseMatrizRastreabilidade	= new AnaliseMatrizRastreabilidade($this->_request->getControllerName());
        
        Zend_Loader::loadClass('RelatorioProjetoRequisito', Base_Util::baseUrlModule('relatorioProjeto','models'));
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
    public function matrizRastreabilidadeRequisitoAction()
    {
       $this->_helper->layout->disableLayout();
       
       $objRequisito           = new RelatorioProjetoRequisito();
       
	   $arrParans              = $this->_request->getPost();
	   $arrRequisitos          = $objRequisito->getRequisito($arrParans["cd_projeto"]);
	   $arrRequisitoDependente = $objRequisito->getRequisitoDependente($arrParans["cd_projeto"]);
	   $arrProjeto             = $this->projeto->find($arrParans["cd_projeto"])->current()->toArray();
	   $arrDados               = array();
	   $arrRequisitoPai        = array();
	   $cd_requisito_pai       = 0;

	   foreach($arrRequisitoDependente as $requisito){
	   		$arrDados[$requisito["cd_requisito_pai"]][] = array("filho"=>"{$requisito["cd_requisito_filho"]}","st_fechamento_pai"=>"{$requisito["st_fechamento_requisito_pai"]}");

	   		if( $cd_requisito_pai != $requisito["cd_requisito_pai"] ){
	   			$arrRequisitoPai[$requisito["cd_requisito_pai"]] = $requisito["tx_requisito_pai"]."|".$requisito["ni_ordem_pai"];
                $cd_requisito_pai    = $requisito["cd_requisito_pai"];
	   		}
	   }
	   
        $this->view->arrDados        = $arrDados;
        $this->view->arrRequisitoPai = $arrRequisitoPai;
        $this->view->arrRequisito    = $arrRequisitos;
        $this->view->qtdRequisitoPai = count($arrRequisitoPai);
    }
    
    /**
     * Action de geração do relatório de Matriz de Rastreabilidade para impressao
     */
    public function generateAction()
    {
       $this->_helper->layout->disableLayout();
	   
       $objRequisito            = new RelatorioProjetoRequisito();
       $objProjeto				= new Projeto($this->_request->getControllerName());
       
	   $cd_projeto				= (int) $this->_request->getParam('cd_projeto_matriz_requisito', 0);
	   $arrDadosProjeto			= $objProjeto->getDadosProjeto($cd_projeto);

       $arrRequisitos           = $objRequisito->getRequisito($cd_projeto);
	   $arrRequisitoDependente  = $objRequisito->getRequisitoDependente($cd_projeto);
	   $arrProjeto              = $this->projeto->find($cd_projeto)->current()->toArray();
	
	   $arrDadosAnalise			= $this->objAnaliseMatrizRastreabilidade->getVersaoAbertaAnaliseMatrizRastreabilidade( $cd_projeto, 'RR');

	   $arrDados                = array();
	   $arrRequisitoPai         = array();
	   $cd_requisito_pai        = 0;

	   foreach($arrRequisitoDependente as $requisito){
	   		$arrDados[$requisito["cd_requisito_pai"]][] = array("filho"=>"{$requisito["cd_requisito_filho"]}","st_fechamento_pai"=>"{$requisito["st_fechamento_requisito_pai"]}");

	   		if( $cd_requisito_pai != $requisito["cd_requisito_pai"] ){
	   			$arrRequisitoPai[$requisito["cd_requisito_pai"]] = $requisito["tx_requisito_pai"]."|".$requisito["ni_ordem_pai"];
                $cd_requisito_pai    = $requisito["cd_requisito_pai"];
	   		}
	   }
	   
        $this->view->arrDadosProjeto = $arrDadosProjeto;
        $this->view->arrDados        = $arrDados;
        $this->view->arrRequisitoPai = $arrRequisitoPai;
        $this->view->arrRequisito    = $arrRequisitos;
        $this->view->arrDadosAnalise = (count($arrDadosAnalise) > 0) ? $arrDadosAnalise[0] : $arrDadosAnalise;
        $this->view->qtdRequisitoPai = count($arrRequisitoPai);
    }

	public function salvarAnaliseMatrizRequisitoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$arrDados = $this->_request->getPost();
		
		$arrResult 	  = array('error'=>false, 'errorType'=> '', 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO') );
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
			if( (empty($arrDados['cd_analise_matriz_rastreabilidade_requisito']) || is_null($arrDados['cd_analise_matriz_rastreabilidade_requisito'])) && 
			    (empty($arrDados['dt_analise_matriz_rastreabilidade_requisito']) || is_null($arrDados['dt_analise_matriz_rastreabilidade_requisito']))
			   ){
			   	//insert
			   	
			   	$cd_analise_matriz_rastreab			= $this->objAnaliseMatrizRastreabilidade->getNextValueOfField('cd_analise_matriz_rastreab');
			   	$dt_analise_matriz_rastreab			= date('Y-m-d');
			   	
			   	$novo										= $this->objAnaliseMatrizRastreabilidade->createRow();
			   	$novo->cd_analise_matriz_rastreab	= $cd_analise_matriz_rastreab;
			   	$novo->cd_projeto							= $arrDados['cd_projeto_matriz_requisito'];
			   	$novo->dt_analise_matriz_rastreab	= new Zend_Db_Expr("{$this->objAnaliseMatrizRastreabilidade->to_date("'{$dt_analise_matriz_rastreab}'", 'YYYY-MM-DD')}");
			   	$novo->tx_analise_matriz_rastreab	= strip_tags($arrDados['tx_analise_matriz_rastreabilidade_requisito']);
			   	$novo->st_matriz_rastreabilidade			= "RR";
			   	
			   	if( !$novo->save() ){
			   		$db->rollBack();
			   		throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
			   	}else{
			   		$dadosRetorno['cd_analise_matriz_rastreab'] = $cd_analise_matriz_rastreab;
			   		$dadosRetorno['dt_analise_matriz_rastreab'] = $dt_analise_matriz_rastreab;
			   	}
			}else{
				//update
				
				$arrUpdate['tx_analise_matriz_rastreab'] = strip_tags($arrDados['tx_analise_matriz_rastreabilidade_requisito']);
				
				$where  = "cd_analise_matriz_rastreab = {$arrDados['cd_analise_matriz_rastreabilidade_requisito']} 	and ";
				$where .= "cd_projeto						 = {$arrDados['cd_projeto_matriz_requisito']} 					and ";
				$where .= "st_matriz_rastreabilidade		 = 'RR' 														and ";
				$where .= "dt_analise_matriz_rastreab = {$this->objAnaliseMatrizRastreabilidade->to_date("'{$arrDados['dt_analise_matriz_rastreabilidade_requisito']}'", 'YYYY-MM-DD')} ";
				
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

		$arrDados = $this->_request->getPost();
		
		$arrResult = array('error'=>false, 'errorType'=> '', 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_ANALISE_FECHADA'));
		
		$db		   = Zend_Registry::get('db');
		$db->beginTransaction();

		try{
			if( !ChecaPermissao::possuiPermissao('matriz-rastreabilidade-caso-de-uso') ){
				throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'));
			}
			if( !empty($arrDados['cd_analise_matriz_rastreabilidade_requisito']) && !is_null($arrDados['cd_analise_matriz_rastreabilidade_requisito']) ){
				if( !empty($arrDados['dt_analise_matriz_rastreabilidade_requisito']) && !is_null($arrDados['dt_analise_matriz_rastreabilidade_requisito']) ){
					
					$arrUpdate['st_fechamento'				 ] = "S";
					$arrUpdate['tx_analise_matriz_rastreab'	 ] = strip_tags($arrDados['tx_analise_matriz_rastreabilidade_requisito']);
			
					$arrCondicoes['cd_analise_matriz_rastreab'] = strip_tags($arrDados['cd_analise_matriz_rastreabilidade_requisito']);
					$arrCondicoes['dt_analise_matriz_rastreab'] = strip_tags($arrDados['dt_analise_matriz_rastreabilidade_requisito']);
					$arrCondicoes['cd_projeto'				  ] = strip_tags($arrDados['cd_projeto_matriz_requisito']);
					$arrCondicoes['st_matriz_rastreabilidade' ] = "RR";
					
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