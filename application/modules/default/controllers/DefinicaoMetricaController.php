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

class DefinicaoMetricaController extends Base_Controller_Action
{
    private $_objDefinicaoMetrica;
    private $_objItemMetrica;

	public function init()
	{
		parent::init();
        $this->_objDefinicaoMetrica  = new DefinicaoMetrica($this->_request->getControllerName());
        $this->_objItemMetrica       = new ItemMetrica($this->_request->getControllerName());
	}

	public function indexAction(){}

    public function salvarAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $post      = $this->_request->getPost();
        $arrResult = array('error'=>'', 'errorType'=>'', 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'));

        $this->_objDefinicaoMetrica->getDefaultAdapter()->beginTransaction();
        try{
        
			$error = false;

            $novo                           = $this->_objDefinicaoMetrica->createRow();
            $novo->cd_definicao_metrica     = $this->_objDefinicaoMetrica->getNextValueOfField('cd_definicao_metrica');
            $novo->tx_nome_metrica          = $post['tx_nome_metrica'];
            $novo->tx_sigla_metrica         = $post['tx_sigla_metrica'];
            $novo->tx_descricao_metrica     = $post['tx_descricao_metrica'];
            $novo->tx_formula_metrica       = $post['tx_formula_metrica'];
            $novo->tx_unidade_metrica       = $post['tx_unidade_metrica'];
            $novo->tx_sigla_unidade_metrica = $post['tx_sigla_unidade_metrica'];
            $novo->st_justificativa_metrica = ($post['st_justificativa_metrica'] == "S") ? "S" : null;

            if( !$novo->save() ){
                throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
                $error = true;
            }

        	if($error){
	        	$this->_objDefinicaoMetrica->getDefaultAdapter()->rollBack();
	        }else{
	        	$this->_objDefinicaoMetrica->getDefaultAdapter()->commit();
	        }
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
        echo Zend_Json_Encoder::encode($arrResult);
    }

    public function alterarAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $post = $this->_request->getPost();

        $arrResult = array('error'=>'', 'errorType'=>'', 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO'));
        $this->_objDefinicaoMetrica->getDefaultAdapter()->beginTransaction();
        try{

			$error = false;
            $arrUpdate['tx_sigla_metrica'        ] = $post['tx_sigla_metrica'];
            $arrUpdate['tx_descricao_metrica'    ] = $post['tx_descricao_metrica'];
            $arrUpdate['tx_formula_metrica'      ] = $post['tx_formula_metrica'];
            $arrUpdate['tx_unidade_metrica'      ] = $post['tx_unidade_metrica'];
            $arrUpdate['tx_sigla_unidade_metrica'] = $post['tx_sigla_unidade_metrica'];
            $arrUpdate['st_justificativa_metrica'] = ($post['st_justificativa_metrica'] == "S") ? "S" : null;

            if( !$this->_objDefinicaoMetrica->update($arrUpdate, "cd_definicao_metrica = {$post['cd_definicao_metrica']}") ){
                throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO'));
                $error = true;
            }

        	if($error){
	        	$this->_objDefinicaoMetrica->getDefaultAdapter()->rollBack();
	        }else{
	        	$this->_objDefinicaoMetrica->getDefaultAdapter()->commit();
	        }
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
        echo Zend_Json_Encoder::encode($arrResult);
    }

    public function excluirAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_definicao_metrica = $this->_request->getParam('cd_definicao_metrica', 0);

        $arrResult = array('error'=>'', 'errorType'=>'', 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'));
        $this->_objDefinicaoMetrica->getDefaultAdapter()->beginTransaction();
        try{

			$error = false;
            $error = (!$error) ? $this->verificaExistenciaRegistro( $cd_definicao_metrica ) : true;
            $error = (!$error) ? $this->verificaAssociacaoRegistro( $cd_definicao_metrica ) : true;

            if(!$error){
                if( !$this->_objDefinicaoMetrica->delete("cd_definicao_metrica = {$cd_definicao_metrica}") ){
                    throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO'));
                    $error = true;
                }
            }
        	if($error){
	        	$this->_objDefinicaoMetrica->getDefaultAdapter()->rollBack();
	        }else{
	        	$this->_objDefinicaoMetrica->getDefaultAdapter()->commit();
	        }
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
        echo Zend_Json_Encoder::encode($arrResult);
    }

    public function recuperarAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
        $cd_definicao_metrica = $this->_request->getParam("cd_definicao_metrica", 0);

        $retorno = $this->_objDefinicaoMetrica->find($cd_definicao_metrica)->current()->toArray();
        echo Zend_Json::encode($retorno);
    }

    public function gridDefinicaoMetricaAction()
    {
        $this->_helper->layout->disableLayout();

        $select = $this->_objDefinicaoMetrica->select()->order('tx_nome_metrica');
		$res = $this->_objDefinicaoMetrica->fetchAll($select)->toArray();
		$this->view->res = $res;
    }

    public function getComboDefinicaoMetricaAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $arrMetrica = $this->_objDefinicaoMetrica->getDefinicaoMetrica(true);

        $options = '';
        foreach($arrMetrica as $key=>$value){
            $options .= "<option value=\"{$key}\">{$value}</option>";
        }
        echo $options;
    }

    private function verificaExistenciaRegistro( $cd_definicao_metrica )
    {
        $erro = false;
        $res = $this->_objDefinicaoMetrica->find($cd_definicao_metrica)->current();

        if( count($res) == 0 ){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_NAO_LOCALIZADO'));
            $erro = true;
        }
        return $erro;
    }

    private function verificaAssociacaoRegistro( $cd_definicao_metrica )
    {
        $erro = false;
        $select = $this->_objItemMetrica->select()->where("cd_definicao_metrica = ?", $cd_definicao_metrica);
        $res    = $this->_objItemMetrica->fetchAll($select);

        if(count($res) > 0 ){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO'));
            $erro = true;
        }
        return $erro;
    }
}
