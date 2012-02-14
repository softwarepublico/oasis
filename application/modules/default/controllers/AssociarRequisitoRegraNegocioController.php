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

Class AssociarRequisitoRegraNegocioController extends Base_Controller_Action
{
	private $objRegraNegocioRequisito;
	private $objRequisito;
	private $objRegraNegocio;
	
	function init()
	{
		parent::init();
		$this->objRegraNegocioRequisito = new RegraNegocioRequisito($this->_request->getControllerName());
		$this->objRequisito 			= new Requisito($this->_request->getControllerName());
		$this->objRegraNegocio 			= new RegraNegocio($this->_request->getControllerName());
	}
	
	public function indexAction()
	{}
	
	public function pesquisaAssociacaoRegraRequisitoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();
		
		$cd_projeto   = $arrDados['cd_projeto'  ];
		$cd_requisito = $arrDados['cd_requisito'];
		$dt_versao	  = $arrDados['dt_versao'	];

		if ($cd_requisito == 0) {
			echo '';
		} else {
			
			// Recordset de regras que nao se encontram associadas
			$arrNaoAssociadas = $this->objRegraNegocioRequisito->getRegraNegocioRequisitoNaoAssociadas( $cd_projeto, $cd_requisito, $dt_versao ); 

			// Recordset de regras que se encontram associadas
			$arrAssociadas	  = $this->objRegraNegocioRequisito->getRegraNegocioRequisitoAssociadas( $cd_projeto, $cd_requisito, $dt_versao );

			/*
			 * Os procedimentos abaixo criam os options dos selects de acordo com o seu respectivo recordset. 
			 * Posteriormente eh criado um json que eh enviado ao client (javascript) que adiciona os options aos selects
			 */
			$arr1 = "";

			foreach ($arrNaoAssociadas as $rs) {
				$arr1 .= "<option value=\"{$rs['cd_regra_negocio']}|{$rs['dt_regra_negocio']}\">{$rs['tx_regra_negocio']}</option>";
			}

			$arr2 = "";
			foreach ($arrAssociadas as $rs1) {
				$arr2 .= "<option value=\"{$rs1['cd_regra_negocio']}|{$rs1['dt_regra_negocio']}\">{$rs1['tx_regra_negocio']}</option>";
			}

			$retornaOsDois = array($arr1, $arr2);

			echo Zend_Json::encode($retornaOsDois);
		}
	}
	
	public function associaRegraRequisitoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrDados = $this->_request->getPost();
		$regras   = Zend_Json_Decoder::decode( str_ireplace("\\","",$arrDados['regras']) );
        $arr1     = explode("|",$arrDados['cmb_requisitos_associar_requisito_regra']);

		foreach ($regras as $regra) {
			
			$arr2 = explode("|",$regra);
			
			$novo 							= $this->objRegraNegocioRequisito->createRow();
			$novo->cd_projeto				= $arrDados['cd_projeto']; 
			$novo->cd_projeto_regra_negocio	= $arrDados['cd_projeto'];
			$novo->cd_requisito				= trim($arr1[0]);
			$novo->dt_versao_requisito		= (!empty (trim($arr1[1]))) ? trim($arr1[1]) : null ;
			$novo->cd_regra_negocio			= trim($arr2[0]);
			$novo->dt_regra_negocio			= (!empty (trim($arr2[1]))) ? trim($arr2[1]) : null;
			
			$novo->save();
		}
	}
	
	public function desassociaRegraRequisitoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrDados = $this->_request->getPost();
		$regras   = Zend_Json_Decoder::decode( str_ireplace("\\","",$arrDados['regras']) );
		$arr1     = explode("|",$arrDados['cmb_requisitos_associar_requisito_regra']);

		foreach ($regras as $regra) {
			
			$arr2 = explode("|",$regra);
		
			$where  = "cd_projeto				= {$arrDados['cd_projeto']} and ";
			$where .= "cd_projeto_regra_negocio	= {$arrDados['cd_projeto']} and ";
			$where .= "cd_requisito				=  ".trim($arr1[0])." and ";
			$where .= "cd_regra_negocio			=  ".trim($arr2[0])." and ";
			$where .= "dt_versao_requisito		= '".trim($arr1[1])."' and ";
			$where .= "dt_regra_negocio			= '".trim($arr2[1])."'";

			$this->objRegraNegocioRequisito->delete($where);
		}
		
	}
	
	public function pesquisaAssociacaoVersaoAnteriorRequisitoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrRetorno = array('comMsg'=>false,'msg'=>'','arrDados'=>'');
		
		$arrDados = $this->_request->getPost();
		
		$ultimaVersao = $this->objRequisito->getUltimaVersaoRequisito( $arrDados['cd_projeto'], $arrDados['cd_requisito'] );
						
		if( $ultimaVersao[0]['ni_versao_requisito'] != 1 ){
			
			$versaoAnterior = $ultimaVersao[0]['ni_versao_requisito'] - 1;
			
			//Busca os dados da versao anterior do requisito que esta sendo passado como parâmetro
			//para verificar se há associações na versão anterior
			$dadosRequisitoVersaoAnterior = $this->objRequisito->getRequisitoEspecifico( $arrDados['cd_requisito'], $versaoAnterior );
			
			//Busca os dados das associações da versao anterior caso haja.
			$associacoes = $this->objRegraNegocioRequisito->getDadosVersaoRequisitoAnterior($arrDados['cd_projeto'],
															   								$arrDados['cd_requisito'],
															   								$dadosRequisitoVersaoAnterior['dt_versao_requisito']);
			if( !empty($associacoes) ){
				$arrRetorno['comMsg'	] = true;
				$arrRetorno['msg'		] = Base_Util::getTranslator('L_MSG_ALERT_QUESTIONAMENTO_ATUALIZACAO_REQUISITO');
				$arrRetorno['arrDados'	] = Zend_Json::encode($associacoes);
			}
		}
		echo Zend_Json::encode($arrRetorno);
	}
	
	public function atualizaDadosVersaoRequisitoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		//dados recebidos por post
		$arrDados					= $this->_request->getPost();
		//array para retorno das informações
		$arrResult					= array('error'=>'', 'errorType'=>'', 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_ATUALIZACAO'));
		$ni_versao_atual_requisito	= $arrDados['ni_versao_requisito'];
		
		//array com os dados a serem atualizados
		$regras			 			= Zend_Json_Decoder::decode( str_ireplace("\\","",$arrDados['arrAtualizacao']) );
		
		//busca os dados da versao anterior do requisito que possui as associações que serão atualizadas
		$arrDadosRequisitoAnterior = $this->objRequisito->getRequisitoEspecifico($arrDados['cd_requisito'], ($ni_versao_atual_requisito - 1), null, $arrDados['cd_projeto']);
		
		$db	= Zend_Registry::get('db');
		$db->beginTransaction();
		
		try{
			foreach ($regras as $regra) {
				$novo 							= $this->objRegraNegocioRequisito->createRow();
				$novo->cd_projeto				= $regra['cd_projeto']; 
				$novo->cd_projeto_regra_negocio	= $regra['cd_projeto_regra_negocio'];
				$novo->cd_requisito				= $regra['cd_requisito'];
				$novo->cd_regra_negocio			= $regra['cd_regra_negocio'];
				$novo->dt_regra_negocio			= (!empty ($regra['dt_regra_negocio'])) ? $regra['dt_regra_negocio'] : null;
				$novo->dt_versao_requisito		= (!empty ($arrDados['dt_versao'    ])) ? $arrDados['dt_versao'    ] : null;
				
				if(!$novo->save()){
					$db->rollBack();
					throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO'));
				}
			}
			
			//apos criar os novos registros para a nova vesao do requisito
			//inativa-se os registros da versão anterior
			$arrUpdate['st_inativo'] = "S";

			$where  = "cd_projeto   = {$arrDados['cd_projeto']}   and ";
			$where .= "cd_requisito = {$arrDados['cd_requisito']} and ";
			$where .= "dt_versao_requisito = '{$arrDadosRequisitoAnterior['dt_versao_requisito']}'";
			
			if( !$this->objRegraNegocioRequisito->update($arrUpdate, $where) ){
				$db->rollBack();
				throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO'));
			}
			
			$db->commit();
			
		}catch(Base_Exception_Error $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Zend_Exception $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ERRO_PROCESSO_ATUALIZACAO').$e->getMessage();
        }
        echo Zend_Json_Encoder::encode($arrResult);
	}
	
	public function atualizaDadosVersaoRegraNegocioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrResult = array('error'=>'', 'errorType'=>'', 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_ATUALIZACAO_VERSAO'));
		$arrDados = $this->_request->getPost();
		
		$cd_projeto   		 = $arrDados['cd_projeto'  ];
		$cd_requisito 		 = $arrDados['cd_requisito'];
		$dt_versao_requisito = $arrDados['dt_versao'   ];
		
		// Recordset de regras que se encontram associadas
		$arrAssociadas	  = $this->objRegraNegocioRequisito->getRegraNegocioRequisitoAssociadas( $cd_projeto, $cd_requisito, $dt_versao_requisito );
		
		$possuiVersao	= false;
		
		$db = Zend_Registry::get('db');
		$db->beginTransaction();

		try{
		
			foreach( $arrAssociadas as $rs ){
				
				//busca a ultima versão do regra de negocio
				$arrMaxRegra = $this->objRegraNegocio->getUltimaVersaoRegraNegocio($rs['cd_regra_negocio']);
				
				//Se as dadas forem diferente é porque a regra de $rs possui uma versão mais atual 
				//entao devera ser feitas atualizações
				/*caso as datas sejam iguais, não faz nada pois a regra já esta na sua ultima versao*/
				if($rs['dt_regra_negocio'] != $arrMaxRegra[0]['dt_regra_negocio']){
	
					$possuiVersao = true;
					
					$novo							= $this->objRegraNegocioRequisito->createRow();
					$novo->cd_projeto				= $cd_projeto; 
					$novo->cd_projeto_regra_negocio	= $cd_projeto;
					$novo->cd_requisito				= $cd_requisito;
					$novo->cd_regra_negocio			= $rs['cd_regra_negocio'];
					$novo->dt_regra_negocio			= (!empty ($arrMaxRegra[0]['dt_regra_negocio'])) ? $arrMaxRegra[0]['dt_regra_negocio'] : null;
					$novo->dt_versao_requisito		= (!empty ($dt_versao_requisito)) ? $dt_versao_requisito : null;
					
					//se salvou efetua o update
					if($novo->save()){
						$arrUpdate['st_inativo'] = "S";					
						$arrUpdate['dt_inativacao_regra'] = new Zend_Db_Expr("{$this->objRegraNegocioRequisito->to_date("'" . date("Ymd") . "'", 'YYYYMMDD')}");
						
						$where  = "cd_projeto				= {$cd_projeto}				  and ";
						$where .= "cd_projeto_regra_negocio = {$cd_projeto}				  and ";
						$where .= "cd_requisito				= {$cd_requisito}			  and ";
						$where .= "cd_regra_negocio			= {$rs['cd_regra_negocio']}   and ";
						$where .= "dt_regra_negocio			= '{$rs['dt_regra_negocio']}' and ";
						$where .= "dt_versao_requisito		= '{$dt_versao_requisito}' ";
						
						//efetura o update
						if( !$this->objRegraNegocioRequisito->update( $arrUpdate, $where ) ){
							throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_PROCESSO_ATUALIZACAO'));
						}
					}else{
						throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_PROCESSO_ATUALIZACAO'));
					}
				}//fim if
			}//fim foreach
			
			if( !$possuiVersao ){
				throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_NAO_EXISTE_VERSAO_ATUALIZAR'));
			}
			
			$db->commit();

		}catch(Base_Exception_Alert $e){
            $db->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 2;
            $arrResult['msg'      ] = $e->getMessage();
		}catch(Base_Exception_Error $e){
            $db->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Zend_Exception $e){
            $db->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ERRO_PROCESSO_ATUALIZACAO') . $e->getMessage();
        }
        echo Zend_Json_Encoder::encode($arrResult);
	}
}


