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

class InventarioController extends Base_Controller_Action
{
	private $objObjetoContrato;
	private $objAreaAtuacaoTi;
	private $objInventario;
	private $objFormInventario;
	private $_objItemInventariado;
	private $_objItemInventario;
	private $_objSubitemInventario;
	
	public function init()
	{
		parent::init();
		$this->objObjetoContrato     = new ObjetoContrato($this->_request->getControllerName());
		$this->objAreaAtuacaoTi      = new AreaAtuacaoTi($this->_request->getControllerName());
		$this->objInventario         = new Inventario($this->_request->getControllerName());
		$this->objFormInventario     = new FormInventario($this->_request->getControllerName());
		$this->_objItemInventariado  = new ItemInventariado($this->_request->getControllerName());
        $this->_objItemInventario    = new ItemInventario($this->_request->getControllerName());
        $this->_objSubitemInventario = new SubitemInventario($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
        $this->view->arrAreaAtuacaoTi = $this->objAreaAtuacaoTi->comboAreaAtuacaoTi(true);
    }
	
    public function gridInventarioAction()
    {
		$this->_helper->layout->disableLayout();

		$cd_area_atuacao_ti = $this->_request->getParam('cd_area_atuacao_ti', -1);
		$cd_area_atuacao_ti = ( $cd_area_atuacao_ti == 0 ) ? null : $cd_area_atuacao_ti;

		$this->view->res = $this->objInventario->getInventario($cd_area_atuacao_ti);
	}

    public function salvarInventarioAction(){

		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$formData	= $this->_request->getPost();

		$arrResult	= array('error'=>false,'typeMsg'=>1, 'msg'=>'');

		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

			if(!empty($formData['cd_inventario'])) {
				$novo             = $this->objInventario->fetchRow("cd_inventario= {$formData['cd_inventario']}");
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');

			} else {

				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');

				$novo									= $this->objInventario->createRow();
				$novo->cd_inventario		            = $this->objInventario->getNextValueOfField('cd_inventario');
			}

            $novo->cd_area_atuacao_ti		= $formData['cd_area_atuacao_ti_inventario'];
            $novo->tx_inventario			= $formData['tx_inventario'];
            $novo->tx_desc_inventario		= $formData['tx_desc_inventario'];
            $novo->dt_ult_atual_inventario	= new Zend_Db_Expr("{$this->objInventario->to_date("'".date('d/m/Y')."'", 'DD/MM/YYYY')}");
			$novo->tx_obs_inventario		= ($formData['tx_obs_inventario'] == '') ? null : $formData['tx_obs_inventario'];

			$novo->save();
            $db->commit();

		} catch(Base_Exception_Alert $e) {
			$arrResult['error'	] = true;
			$arrResult['typeMsg'] = 2;
			$arrResult['msg'	] = $e->getMessage();
            $db->rollBack();
		} catch(Zend_Exception $e) {
			$arrResult['error'	] = true;
			$arrResult['typeMsg'] = 3;
			$arrResult['msg'	] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
            $db->rollBack();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}

    public function salvarItemInventariadoAction(){
       
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$formData	= $this->_request->getPost();

        $arrResult	= array('error'=>false,'typeMsg'=>1, 'msg'=>'');

		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

			if(!empty($formData['cd_item_inventariado'])) {
                $novo             = $this->_objItemInventariado->fetchRow("cd_item_inventariado= {$formData['cd_item_inventariado']}
                                                                    and cd_inventario= {$formData['cd_inventario_item_inventariado']}
                                                                    and cd_item_inventario= {$formData['cd_item_inventario_item_inventariado']}
                                                                  ");
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');

			} else {
    			$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');

				$novo									= $this->_objItemInventariado->createRow();
				$novo->cd_item_inventariado		        = $this->_objItemInventariado->getNextValueOfField('cd_item_inventariado');
			}
            $novo->cd_inventario     		= $formData['cd_inventario_item_inventariado'];
            $novo->cd_item_inventario		= $formData['cd_item_inventario_item_inventariado'];
            $novo->tx_item_inventariado	    = $formData['tx_item_inventariado_item_inventariado'];
            
            $novo->save();
            $db->commit();
		} catch(Base_Exception_Alert $e) {
			$arrResult['error'	] = true;
			$arrResult['typeMsg'] = 2;
			$arrResult['msg'	] = $e->getMessage();
            $db->rollBack();
		} catch(Zend_Exception $e) {
			$arrResult['error'	] = true;
			$arrResult['typeMsg'] = 3;
			$arrResult['msg'	] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
            $db->rollBack();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}

    public function excluirAction()
    {
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$formData	= $this->_request->getPost();

		$arrResult	= array('error'=>false,'typeMsg'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'));

		$erro = false;
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

			$erro = (!$erro) ? $this->verificaExclusaoInventario( $formData['cd_inventario']): true ;

			if( !$erro ){
				if(!$this->objInventario->delete("cd_inventario = {$formData['cd_inventario']}")){
					throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO'));
					$erro = true;
				}
			}
			if(!$erro){
				$db->commit();
			} else {
				$db->rollBack();
			}
		} catch(Base_Exception_Alert $e) {
			$arrResult['error'	] = true;
			$arrResult['typeMsg'] = 2;
			$arrResult['msg'	] = $e->getMessage();
		} catch(Base_Exception_Error $e) {
			$arrResult['error'	] = true;
			$arrResult['typeMsg'] = 3;
			$arrResult['msg'	] = $e->getMessage();
		} catch(Zend_Exception $e) {
			$arrResult['error'	] = true;
			$arrResult['typeMsg'] = 3;
			$arrResult['msg'	] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}

    public function excluirItemInventariadoAction()
    {
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$formData	= $this->_request->getPost();
        
		$arrResult	= array('error'=>false,'typeMsg'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'));

		$erro = false;
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

			$erro = (!$erro) ? $this->verificaExclusaoItemInventariado( $formData['cd_item_inventariado'],
                                                                        $formData['cd_inventario'],
                                                                        $formData['cd_item_inventario']
                                                                      ): true ;

			if( !$erro ){
				if(!$this->_objItemInventariado->delete("cd_item_inventariado = {$formData['cd_item_inventariado']}")){
					throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO'));
					$erro = true;
				}
			}
			if(!$erro){
				$db->commit();
			} else {
				$db->rollBack();
			}
		} catch(Base_Exception_Alert $e) {
			$arrResult['error'	] = true;
			$arrResult['typeMsg'] = 2;
			$arrResult['msg'	] = $e->getMessage();
		} catch(Base_Exception_Error $e) {
			$arrResult['error'	] = true;
			$arrResult['typeMsg'] = 3;
			$arrResult['msg'	] = $e->getMessage();
		} catch(Zend_Exception $e) {
			$arrResult['error'	] = true;
			$arrResult['typeMsg'] = 3;
			$arrResult['msg'	] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}


    /**
	 * Função para verificar se existe dados na tabela associativa de documentacao
	 * no momento de exclusão de análise
	 *
	 * @param <int> $cd_objeto
	 * @param <int> $cd_analise
	 */
	private function verificaExclusaoInventario($cd_inventario)
    {
		$erro	= false;
		$select = $this->objFormInventario->select()->where("cd_inventario = ?", $cd_inventario);

		$result = $this->objFormInventario->fetchAll($select);

		// se for valido é porque existe registros de associação
		if( $result->valid() ){
			throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO_DOCUMENTACAO'));
			$erro = true;
		}
		return $erro;
	}

    /**
	 * Função para verificar se existe dados na tabela associativa de documentacao
	 * no momento de exclusão de análise
	 *
	 * @param <int> $cd_objeto
	 * @param <int> $cd_analise
	 */
	private function verificaExclusaoItemInventariado($cd_item_inventariado, $cd_inventario, $cd_item_inventario)
    {

		$erro	= false;
		$select = $this->objFormInventario->select()->where("cd_inventario = ?", $cd_inventario)
                                                    ->where("cd_item_inventario = ?", $cd_item_inventario)
                                                    ->where("cd_item_inventariado = ?", $cd_item_inventariado);

		$result = $this->_objItemInventariado->fetchAll($select);

		// se for valido é porque existe registros de associação
		if( $result->valid() ){
			throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO'));
			$erro = true;
		}
		return $erro;
	}


    public function recuperaDadosInventarioAction()
    {
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$rowset	= $this->objInventario->getInventario(null,$this->_request->getPost('cd_inventario'), true);

        echo Zend_Json::encode($rowset->current()->toArray());

	}

    public function recuperarItemInventariadoAction(){

		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrPost	= $this->_request->getPost();

		$row = $this->_objItemInventariado->fetchRow(array(
            'cd_item_inventariado  = ?'=> $arrPost['cd_item_inventariado'],
            'cd_inventario         = ?'=> $arrPost['cd_inventario'],
            'cd_item_inventario    = ?'=> $arrPost['cd_item_inventario']
        ));

		echo Zend_Json::encode($row->toArray());

	}


    public function montaComboItemInventarioAction()
	{
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_area_atuacao_ti     = $this->_request->getParam('cd_area_atuacao_ti');
		$selected = "";
		$cd_item_inventario = 0;
		if($cd_area_atuacao_ti){
			$arrDados = $this->_objItemInventario->comboItemInventario($cd_area_atuacao_ti,true);

			if(count($arrDados) > 0){
				$strOptions = "";
				foreach($arrDados as $key=>$conteudo){
					if($cd_item_inventario == $key){
						$strOptions .= "<option {$selected} label=\"{$conteudo}\" value=\"{$key}\">{$conteudo}</option>";
					} else {
						$strOptions .= "<option label=\"{$conteudo}\" value=\"{$key}\">{$conteudo}</option>";
					}
				}
			} else {
				$strOptions = "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
			}
		} else {
			$strOptions = "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
		}
		echo $strOptions;

	}

  public function montaComboItemInventariadoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
       
		$cd_inventario          = $this->_request->getPost('cd_inventario');
		$cd_item_inventario     = $this->_request->getPost('cd_item_inventario');

        $selected = "";

		if($cd_inventario){
			$arrDados = $this->_objItemInventariado->comboItemInventariado($cd_inventario,$cd_item_inventario,true);

			if(count($arrDados) > 0){
				$strOptions = "";
				foreach($arrDados as $key=>$conteudo){
					if($cd_item_inventario == $key){
						$strOptions .= "<option {$selected} label=\"{$conteudo}\" value=\"{$key}\">{$conteudo}</option>";
					} else {
						$strOptions .= "<option label=\"{$conteudo}\" value=\"{$key}\">{$conteudo}</option>";
					}
				}
			} else {
				$strOptions = "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
			}
		} else {
			$strOptions = "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
		}
		echo $strOptions;
	}

  public function montaComboSubitemInventarioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
        

//		$cd_inventario          = $this->_request->getPost('cd_inventario');
		$cd_item_inventario     = $this->_request->getPost('cd_item_inventario');

        $selected = "";
		if($cd_item_inventario){
			$arrDados = $this->_objSubitemInventario->comboSubitemInventario($cd_item_inventario,true);

			if(count($arrDados) > 0){
				$strOptions = "";
				foreach($arrDados as $key=>$conteudo){
					if($cd_item_inventario == $key){
						$strOptions .= "<option {$selected} label=\"{$conteudo}\" value=\"{$key}\">{$conteudo}</option>";
					} else {
						$strOptions .= "<option label=\"{$conteudo}\" value=\"{$key}\">{$conteudo}</option>";
					}
				}
			} else {
				$strOptions = "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
			}
		} else {
			$strOptions = "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
		}
		echo $strOptions;
	}

    public function montaComboNomeInventarioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_area_atuacao_ti     = $this->_request->getParam('cd_area_atuacao_ti');

		$selected = "";
		$cd_inventario = 0;
		if($cd_area_atuacao_ti){
			$arrDados = $this->objInventario->comboNomeInventario($cd_area_atuacao_ti,true);

			if(count($arrDados) > 0){
				$strOptions = "";
				foreach($arrDados as $key=>$conteudo){
					if($cd_inventario == $key){
						$strOptions .= "<option {$selected} label=\"{$conteudo}\" value=\"{$key}\">{$conteudo}</option>";
					} else {
						$strOptions .= "<option label=\"{$conteudo}\" value=\"{$key}\">{$conteudo}</option>";
					}
				}
			} else {
				$strOptions = "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
			}
		} else {
			$strOptions = "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
		}
		echo $strOptions;
	}

    public function montaComboNomeInventarioContratoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_contrato        = $this->_request->getPost('cd_contrato');
              
        $cd_area_atuacao_ti = $this->objObjetoContrato->getAreaAtuacaoTiObjetoContrato($cd_contrato);
        
		$selected = "";
		$cd_inventario = 0;
		if($cd_area_atuacao_ti){
			$arrDados = $this->objInventario->comboNomeInventario($cd_area_atuacao_ti,true);

			if(count($arrDados) > 0){
				$strOptions = "";
				foreach($arrDados as $key=>$conteudo){
					if($cd_inventario == $key){
						$strOptions .= "<option {$selected} label=\"{$conteudo}\" value=\"{$key}\">{$conteudo}</option>";
					} else {
						$strOptions .= "<option label=\"{$conteudo}\" value=\"{$key}\">{$conteudo}</option>";
					}
				}
			} else {
				$strOptions = "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
			}
		} else {
			$strOptions = "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
		}
		echo $strOptions;
	}
    
    
    
    public function gridFormInventarioAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_area_atuacao_ti    = $this->_request->getPost("cd_area_atuacao_ti",0);
		$cd_inventario         = $this->_request->getPost("cd_inventario");
		$cd_item_inventario    = $this->_request->getPost("cd_item_inventario");
		$cd_item_inventariado  = $this->_request->getPost("cd_item_inventariado");
		$cd_subitem_inventario = $this->_request->getPost("cd_subitem_inventario");

        $arrParItem = array('cd_area_atuacao_ti'   => $cd_area_atuacao_ti,
                            'cd_inventario'        => $cd_inventario,
                            'cd_item_inventario'   => $cd_item_inventario,
                            'cd_item_inventariado' => $cd_item_inventariado,
                            'cd_subitem_inventario'=> $cd_subitem_inventario
                        );
//		$arrItemInventario    = $this->_objItemInventario->getDadosItemInventario($cd_area_atuacao_ti, $cd_item_inventario);
		$arrItemInventariado  = $this->_objItemInventariado->getItensInventariadoDados($cd_inventario,$cd_item_inventario, $cd_item_inventariado,$cd_subitem_inventario);
 
//        $arrSubitemInventario = array();
//		$arrDados             = array();

//        foreach($arrItemInventariado as $key=>$value){
//			$arrSubitemInventario[$value['cd_item_inventariado']] = $this->_objSubitemInventario->getDadosSubitemInventario( $value['cd_item_inventario'] );
//            if(count($arrSubitemInventario[$value['cd_item_inventario']]) > 0 ){
//				foreach($arrSubitemInventario[$value['cd_item_inventariado']] as $chave=>$valor){
//					$rowDados = $this->objFormInventario->getFormInventario($cd_inventario, $cd_item_inventario, $value['cd_item_inventariado'], $valor['cd_subitem_inventariado']);
//                    if(count($rowDados) > 0 ){
//						$arrSubitemInventario[$value['cd_item_inventariado']][$chave]['tx_valor_subitem_inventario'] = $rowDados->tx_valor_subitem_inventario;
//					} else {
//						$arrSubitemInventario[$value['cd_item_inventariado']][$chave]['tx_valor_subitem_inventario'] = "";
//					}
//				}
//			}
//		}
        $this->view->arrItemInventariado = $arrItemInventariado;
        $this->view->arrParItem          = $arrParItem;
//		$this->view->arrSubitemInventario = $arrSubitemInventario;
	}

     public function gridItemInventariadoAction()
	{
        $this->_helper->layout->disableLayout();
        $post = $this->_request->getPost();

        $this->view->res = $this->_objItemInventariado->fetchAll(array(
            'cd_inventario = ?'       => $post['cd_inventario'],
            'cd_item_inventario = ?'  => $post['cd_item_inventario']

        ),'cd_item_inventariado')->toArray();

	}


    public function salvaDadosFormInventarioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrPost = $this->_request->getPost();
        
        $cd_inventario          = $arrPost['cd_inventario_form_inventario'];
		$cd_item_inventario     = $arrPost['cd_item_inventario_form_inventario'];
		$cd_item_inventariado   = $arrPost['cd_item_inventariado_form_inventario'];
		$cd_subitem_inventario  = $arrPost['cd_subitem_inventario_form_inventario'];
		unset($arrPost['cd_area_atuacao_ti_form_inventario']);
		unset($arrPost['cd_inventario_form_inventario']);
		unset($arrPost['cd_item_inventario_form_inventario']);
		unset($arrPost['cd_item_inventariado_form_inventario']);
		unset($arrPost['cd_subitem_inventario_form_inventario']);

        $arrResult	= array('error'=>false,'typeMsg'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'));

		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

            $i        = 0;
            $arrDados = array();

            foreach($arrPost as $key=>$value){
                $arrCampo = explode('_',$key);
                $valor    = "valor_{$arrCampo[1]}_{$arrCampo[2]}_{$arrCampo[3]}_{$arrCampo[4]}";
                if(empty($arrPost[$valor])){
                    continue;
                } else {
                    $arrDados[$i]['cd_inventario']                 = $cd_inventario;
                    $arrDados[$i]['cd_item_inventario']            = $cd_item_inventario;
                    $arrDados[$i]['cd_item_inventariado']          = $cd_item_inventariado;
                    $arrDados[$i]['cd_subitem_inventario']         = $cd_subitem_inventario;
                    $arrDados[$i]['cd_subitem_inv_descri']         = $arrCampo[4];

                    if(!empty($arrPost[$valor])){
                        $arrDados[$i]['tx_valor_subitem_inventario'] = $arrPost[$valor];
                        unset($arrPost[$valor]);
                    } else {
                        $arrDados[$i]['tx_valor_subitem_inventario'] = null;
                    }
                }
                $i++;
            }
            $arrDelete = array(
                'cd_inventario = ?'         => $cd_inventario,
                'cd_item_inventario = ?'    => $cd_item_inventario,
                'cd_item_inventariado = ?'  => $cd_item_inventariado,
                'cd_subitem_inventario = ?' => $cd_subitem_inventario
            );
            if( $this->objFormInventario->delete($arrDelete) ){
                $arrResult['msg'	] = Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
            }
            
            if( count($arrDados) > 0 ){
                $return = $this->objFormInventario->trataDadosGravacao($arrDados);
                if( $return ){
                    $arrResult['msg'	] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
                }else{
                    throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
                }
            } 
            $db->commit();
		} catch(Base_Exception_Alert $e) {
            $db->rollBack();
			$arrResult['error'	] = true;
			$arrResult['typeMsg'] = 2;
			$arrResult['msg'	] = $e->getMessage();
		} catch(Base_Exception_Error $e) {
            $db->rollBack();
			$arrResult['error'	] = true;
			$arrResult['typeMsg'] = 3;
			$arrResult['msg'	] = $e->getMessage();
		} catch(Zend_Exception $e) {
            $db->rollBack();
			$arrResult['error'	] = true;
			$arrResult['typeMsg'] = 3;
			$arrResult['msg'	] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}

    public function salvaDadosItemInventariadoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $arrPost = $this->_request->getPost();

		$cd_inventario        = $arrPost['cd_inventario_item_inventariado'];
		$cd_item_inventario   = $arrPost['cd_item_inventario_item_inventariado'];
		unset($arrPost['cd_inventario_item_inventariado']);
		unset($arrPost['cd_item_inventario_item_inventariado']);

		$i        = 0;
		$arrDados = array();

		foreach($arrPost as $key=>$value){
			$arrCampo = explode('_',$key);
			$valor    = "valor_{$arrCampo[1]}_{$arrCampo[2]}";

			if(empty($arrPost[$valor])){
                continue;
			} else {
				if(!empty($arrPost[$valor])){
					$arrDados[$i]['tx_item_inventariado'] = $arrPost[$valor];
					unset($arrPost[$valor]);
				} else {
					$arrDados[$i]['tx_item_inventariado'] = null;
				}

				$arrDados[$i]['cd_item_inventariado']  = $arrCampo[1];
				$arrDados[$i]['cd_item_inventario']    = $arrCampo[2];
				$arrDados[$i]['cd_inventario']         = $cd_inventario;
			}
			$i++;
		}

        $arrDelete = array(
            'cd_inventario = ?'        => $cd_inventario,
            'cd_item_inventario = ?'   => $cd_item_inventario,
            'cd_item_inventariado = ?' => $cd_item_inventariado
        );
        $this->objFormInventario->delete($arrDelete);
		$msg = Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
        if( count($arrDados) > 0 ){
			$return = $this->_objItemInventariado->trataDadosGravacao($arrDados);
			$msg    = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'):Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
		echo $msg;
	}

}