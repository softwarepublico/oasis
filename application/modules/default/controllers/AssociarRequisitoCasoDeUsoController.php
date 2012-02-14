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

Class AssociarRequisitoCasoDeUsoController extends Base_Controller_Action
{
	private $objRequisitoCasoDeUso;
	private $objRequisito;
	private $objCasoDeUso;
	
	function init()
	{
		parent::init();
		$this->objRequisitoCasoDeUso = new RequisitoCasoDeUso($this->_request->getControllerName());
		$this->objRequisito 		 = new Requisito($this->_request->getControllerName());
		$this->objCasoDeUso 		 = new CasoDeUso($this->_request->getControllerName());
	}
	
	public function indexAction(){$this->initView();}
	
	public function pesquisaAssociacaoCasoDeUsoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados	  = $this->_request->getPost();
		
		$cd_projeto   = $arrDados['cd_projeto'  ];
		$cd_requisito = $arrDados['cd_requisito'];
		$dt_versao	  = $arrDados['dt_versao'	];

		if ($cd_projeto == 0) {
			echo '';
		} else {
			
			// Recordset de caso de uso que nao se encontram associados
			$arrNaoAssociados = $this->objRequisitoCasoDeUso->getRequisitoCasoDeUsoNaoAssociados( $cd_projeto, $cd_requisito, $dt_versao ); 

			// Recordset de caso de uso que se encontram associados
			$arrAssociados	  = $this->objRequisitoCasoDeUso->getRequisitoCasoDeUsoAssociados( $cd_projeto, $cd_requisito, $dt_versao );

			/*
			 * Os procedimentos abaixo criam os options dos selects de acordo com o seu respectivo recordset. 
			 * Posteriormente eh criado um json que eh enviado ao client (javascript) que adiciona os options aos selects
			 */
			$arr1 = "";

			foreach ($arrNaoAssociados as $rs) {
				$arr1 .= "<option value=\"{$rs['cd_caso_de_uso']}|{$rs['cd_modulo']}|{$rs['dt_versao_caso_de_uso']}\">{$rs['tx_caso_de_uso']}</option>";
			}
			
			$arr2 = "";
			foreach ($arrAssociados as $rs1) {
				$arr2 .= "<option value=\"{$rs1['cd_caso_de_uso']}|{$rs1['cd_modulo']}|{$rs1['dt_versao_caso_de_uso']}\">{$rs1['tx_caso_de_uso']}</option>";
			}

			$retornaOsDois = array($arr1, $arr2);

			echo Zend_Json::encode($retornaOsDois);
		}
	}

	public function associaCasoDeUsoRequisitoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrDados   = $this->_request->getPost();
		$cd_projeto = $arrDados['cd_projeto'];
		$casosDeUso = Zend_Json_Decoder::decode( str_ireplace("\\","",$arrDados['casos_de_uso']) );
		$arr1       = explode("|",$arrDados['cmb_requisitos_associar_requisito_caso_de_uso']);
		
		foreach ($casosDeUso as $cdu) {
			
			$arr2 = explode("|",$cdu);
			
			$novo 							= $this->objRequisitoCasoDeUso->createRow();
			$novo->cd_projeto				= $cd_projeto; 
			$novo->cd_requisito				= trim($arr1[0]);
			$novo->dt_versao_requisito		= (!empty(trim($arr1[1]))) ? trim($arr1[1]) : null;
			$novo->cd_caso_de_uso			= trim($arr2[0]);
			$novo->cd_modulo				= trim($arr2[1]);
			$novo->dt_versao_caso_de_uso	= (!empty(trim($arr2[2]))) ? trim($arr2[2]) : null;
			
			$novo->save();
		}
	}
	
	public function desassociaCasoDeUsoRequisitoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrDados 	= $this->_request->getPost();
		$cd_projeto = $arrDados['cd_projeto'];
		$casosDeUso = Zend_Json_Decoder::decode( str_ireplace("\\","",$arrDados['casos_de_uso']) );
		$arr1       = explode("|",$arrDados['cmb_requisitos_associar_requisito_caso_de_uso']);
		
		foreach ($casosDeUso as $cdu) {
			
			$arr2 = explode("|",$cdu);
		
			$where  = "cd_projeto				= {$cd_projeto} and ";
			$where .= "cd_requisito				=  ".trim($arr1[0])." and ";
			$where .= "dt_versao_requisito		= '".trim($arr1[1])."' and ";
			$where .= "cd_caso_de_uso			=  ".trim($arr2[0])." and ";
			$where .= "cd_modulo				=  ".trim($arr2[1])." and ";
			$where .= "dt_versao_caso_de_uso	= '".trim($arr2[2])."'";

			$this->objRequisitoCasoDeUso->delete($where);
		}
	}
	
	public function pesquisaAssociacaoVersaoAnteriorRequisitoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrRetorno 	= array('comMsg'=>false,'msg'=>'','arrDados'=>'');
		$arrDados 		= $this->_request->getPost();
		$ultimaVersao 	= $this->objRequisito->getUltimaVersaoRequisito( $arrDados['cd_projeto'], $arrDados['cd_requisito'] );
		
		if( $ultimaVersao[0]['ni_versao_requisito'] != 1 ){
			
			$versaoAnterior = $ultimaVersao[0]['ni_versao_requisito'] - 1;
			
			//Busca os dados da versao anterior do requisito que esta sendo passado como parâmetro
			//para verificar se há associações na versão anterior
			$dadosRequisitoVersaoAnterior = $this->objRequisito->getRequisitoEspecifico( $arrDados['cd_requisito'], $versaoAnterior );
			
			//Busca os dados das associações da versao anterior caso haja.
			$associacoes = $this->objRequisitoCasoDeUso->getDadosVersaoRequisitoAnterior($arrDados['cd_projeto'],
															   							 $arrDados['cd_requisito'],
															   							 $dadosRequisitoVersaoAnterior['dt_versao_requisito']);
			if( count($associacoes) > 0 ){
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
		$arrAtualizacao 			= Zend_Json_Decoder::decode( str_ireplace("\\","",$arrDados['arrAtualizacao']) );
		
		//busca os dados da versao anterior do requisito que possui as associações que serão atualizadas
		$arrDadosRequisitoAnterior = $this->objRequisito->getRequisitoEspecifico($arrDados['cd_requisito'], ($ni_versao_atual_requisito - 1), null, $arrDados['cd_projeto']);
		
		$db	= Zend_Registry::get('db');
		$db->beginTransaction();

		try{
			foreach ($arrAtualizacao as $atualizacao) {
				$novo 							= $this->objRequisitoCasoDeUso->createRow();
				$novo->cd_projeto				= $atualizacao['cd_projeto']; 
				$novo->dt_versao_requisito		= (!empty ($arrDados['dt_versao'])) ? $arrDados['dt_versao'] : null;
				$novo->cd_requisito				= $atualizacao['cd_requisito'];
				$novo->dt_versao_caso_de_uso	= (!empty ($atualizacao['dt_versao_caso_de_uso']))  ? $atualizacao['dt_versao_caso_de_uso'] : null;
				$novo->cd_caso_de_uso			= $atualizacao['cd_caso_de_uso'];
				$novo->cd_modulo				= $atualizacao['cd_modulo'];
				
				if(!$novo->save()){
					throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_PROCESSO_ATUALIZACAO'));
				}
			}
			
			//apos criar os novos registros para a nova vesao do requisito
			//inativa-se os registros da versão anterior
			$arrUpdate['st_inativo'] = "S";

			$where  = array(
                "cd_projeto   = ?"=> $arrDados['cd_projeto'],
                "cd_requisito = ?"=> $arrDados['cd_requisito'],
                "dt_versao_requisito = ?" => $arrDadosRequisitoAnterior['dt_versao_requisito']
            );
			
			if( !$this->objRequisitoCasoDeUso->update($arrUpdate, $where) ){
				throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_PROCESSO_ATUALIZACAO'));
			}
			
			$db->commit();
			
		}catch(Base_Exception_Error $e){
            $db->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Zend_Exception $e){
            $db->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO') . $e->getMessage();
        }
        echo Zend_Json_Encoder::encode($arrResult);
	}
	
	public function atualizaDadosVersaoCasoDeUsoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrResult = array('error'=>'', 'errorType'=>'', 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_ATUALIZACAO'));
		$arrDados = $this->_request->getPost();
		
		$cd_projeto   		 = $arrDados['cd_projeto'  ];
		$cd_requisito 		 = $arrDados['cd_requisito'];
		$dt_versao_requisito = $arrDados['dt_versao'   ];

		// Recordset de casos de uso que se encontram associados
		$arrAssociados	= $this->objRequisitoCasoDeUso->getRequisitoCasoDeUsoAssociados( $cd_projeto, $cd_requisito, $dt_versao_requisito );
		$possuiVersao	= false;
		$db				= Zend_Registry::get('db');
		
		$db->beginTransaction();

		try{
			
			foreach( $arrAssociados as $rs ){
	            		
				//busca a ultima versão do Caso de uso
				$arrMaxCasoDeUso = $this->objCasoDeUso->getUltimaVersaoCasoDeUso($rs['cd_caso_de_uso'], $rs['cd_projeto'], $rs['cd_modulo']);
				
				//Se as dadas forem diferente é porque o caso de uso de $rs possui uma versão mais atual 
				//entao devera ser feitas atualizações
				/*caso as datas sejam iguais, não faz nada pois o caso de uso já está na sua última versao*/
				if($rs['dt_versao_caso_de_uso'] != $arrMaxCasoDeUso['dt_versao_caso_de_uso']){

					$possuiVersao = true;
					
					$novo							= $this->objRequisitoCasoDeUso->createRow();
					$novo->cd_projeto				= $cd_projeto; 
					$novo->cd_requisito				= $cd_requisito;
					$novo->dt_versao_requisito		= (!empty ($dt_versao_requisito)) ? $dt_versao_requisito : null;
					$novo->cd_caso_de_uso			= $rs['cd_caso_de_uso'];
					$novo->dt_versao_caso_de_uso	= (!empty ($arrMaxCasoDeUso['dt_versao_caso_de_uso'])) ? $arrMaxCasoDeUso['dt_versao_caso_de_uso'] : null;
					$novo->cd_modulo				= $rs['cd_modulo'];
					
					//se salvou efetua o update
					if($novo->save()){
						$arrUpdate['st_inativo'] = "S";
						$arrUpdate['dt_inativacao_caso_de_uso'] = new Zend_Db_Expr("{$this->objRequisitoCasoDeUso->to_date("'".date('Ymd')."'", 'YYYYMMDD')}");
						
						$where  = array(
                            "cd_projeto				= ?" => $cd_projeto,
                            "cd_requisito			= ?" => $cd_requisito,
                            "dt_versao_requisito	= ?" => $dt_versao_requisito,
                            "cd_caso_de_uso			= ?" => $rs['cd_caso_de_uso'],
                            "dt_versao_caso_de_uso	= ?" => $rs['dt_versao_caso_de_uso'],
                            "cd_modulo				= ?" => $rs['cd_modulo']
                        );
						
						//efetura o update
						if(!$this->objRequisitoCasoDeUso->update( $arrUpdate, $where )){
							throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO'));
						}
					}else{
						throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO'));
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
            $arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ERRO_PROCESSO_ATUALIZACAO').$e->getMessage();
        }
        echo Zend_Json_Encoder::encode($arrResult);
	}
}
