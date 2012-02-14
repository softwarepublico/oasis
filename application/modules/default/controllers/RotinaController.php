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

class RotinaController extends Base_Controller_Action
{
	private $rotina;

	public function init()
	{
		parent::init();
		$this->rotina = new Rotina($this->_request->getControllerName());
	}

	public function indexAction(){}

	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$formData = $this->_request->getPost();

        //altera o nome do indice do array para ficar igual ao da tabela
        $formData['cd_area_atuacao_ti'] = $formData['cd_area_atuacao_ti_rotina'];
        unset($formData['cd_area_atuacao_ti_rotina']);

		$arrResult = array('erro'=>false,'type'=>1, 'msg'=>'');
		$erro = false;
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

            $arrResult['msg'] = $this->rotina->salvarRotina($formData);
            $db->commit();

		} catch(Base_Exception_Error $e){
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ]  = $e->getMessage();
            $db->rollBack();
		} catch(Zend_Exception $e){
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO') . $e->getMessage();
            $db->rollBack();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}

	public function recuperaRotinaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_rotina = $this->_request->getParam('cd_rotina');
		$row      = $this->rotina->getRowRotina($cd_rotina);

		echo Zend_Json::encode($row->toArray());
	}

	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_rotina = (int)$this->_request->getParam('cd_rotina', 0);

        $arrResult = array('erro'=>false,'type'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'));
		$erro = false;
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

            $arrWhere['cd_rotina = ?'] = $cd_rotina;

            $this->_verificaIntegridadeRotina($arrWhere);
            $this->rotina->excluirRotina($arrWhere);
            
            $db->commit();

		} catch(Base_Exception_Alert $e){
			$arrResult['erro'] = true;
			$arrResult['type'] = 2;
			$arrResult['msg' ] = $e->getMessage();
            $db->rollBack();
		} catch(Base_Exception_Error $e){
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = $e->getMessage();
            $db->rollBack();
		} catch(Zend_Exception $e){
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO') . $e->getMessage();
            $db->rollBack();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}
	
    private function _verificaIntegridadeRotina($arrWhere)
    {
        $objObjetoContratoRotina = new ObjetoContratoRotina();
        if($objObjetoContratoRotina->fetchAll($arrWhere)->valid()){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO_ATIVIDADE'));
        }
    }

	public function gridRotinaAction()
	{
        $this->_helper->layout->disableLayout();
        
        $cd_area_atuacao_ti       = $this->_request->getParam('cd_area_atuacao_ti', 0);
		$this->view->rowSetRotina = $this->rotina->getRotinaAreaAtuacaoTi($cd_area_atuacao_ti);
	}
	
	public function pesquisaRotinaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_objeto = (int)$this->_request->getParam('cd_objeto', 0);
		$cd_objeto = ($cd_objeto != 0) ? $cd_objeto : $_SESSION['oasis_logged'][0]['cd_objeto'];
		$res       = $this->rotina->getRotina($cd_objeto, true);
		
		$strOptions = "";
		if(count($res) > 0){
			foreach ($res as $cd_projeto_continuado => $tx_sigla_projeto_continuado) {
				$strOptions .= "<option label=\"$tx_sigla_projeto_continuado\" value=\"{$cd_projeto_continuado}\">{$tx_sigla_projeto_continuado}</option>";			
			}
		}
		echo $strOptions;
	}
}