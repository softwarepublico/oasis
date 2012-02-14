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

class ContratoDefinicaoMetricaController extends Base_Controller_Action
{
	private $contrato;
	private $contratoDefinicaoMetrica;
	private $st_contrato;
	
	public function init()
	{
		parent::init();
		$this->contrato                 = new Contrato($this->_request->getControllerName());
		$this->contratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());
	}

	public function indexAction()
	{}

	public function pesquisaDefinicaoMetricaAction()
	{

		// Como este metodo eh um metodo ajax, ele nao precisa renderizar com nenhum template e com nenhum layout.
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		// Recupera os parametros enviados por get
		$cd_contrato = $this->_request->getParam('cd_contrato');

		//  Caso tenha sido enviada a opcao selecione do combo. Apenas para garantir caso o js falhe nesta verificacao
		if ($cd_contrato == -1) {
			echo '';
		} else {
			
			$arrContrato       = $this->contrato->find($cd_contrato)->current();
			$this->st_contrato = $arrContrato->st_contrato;

			// Recordset de profissionais que nao se encontram no projeto selecionado
			$foraContrato = $this->contratoDefinicaoMetrica->pesquisaDefinicaoMetricaForaContrato($cd_contrato);

			// Recordset de profissionais que se encontram no projeto selecionado
			$noContrato   = $this->contratoDefinicaoMetrica->pesquisaDefinicaoMetricaNoContrato($cd_contrato);

			/*
			 * Os procedimentos abaixo criam os options dos selects de acordo com o seu respectivo recordset. 
			 * Posteriormente eh criado um json que eh enviado ao client (javascript) que adiciona os options aos selects
			 */
			$arr1 = "";

			foreach ($foraContrato as $fora) {
				$arr1 .= "<option value=\"{$fora['cd_definicao_metrica']}\">{$fora['tx_nome_metrica']} ({$fora['tx_sigla_metrica']})</option>";
			}

			$arr2 = "";
			foreach ($noContrato as $no) {
				if($no['st_metrica_padrao'] === 'S'){
					$arr2 .= "<option value=\"{$no['cd_definicao_metrica']}\">{$no['tx_nome_metrica']} ({$no['tx_sigla_metrica']}) *</option>";
				}else{
					$arr2 .= "<option value=\"{$no['cd_definicao_metrica']}\">{$no['tx_nome_metrica']} ({$no['tx_sigla_metrica']})</option>";
				}
			}

			$retornaOsDois = array($arr1, $arr2, $this->st_contrato);

			echo Zend_Json_Encoder::encode($retornaOsDois);
		}
	}
	
	public function associaContratoDefinicaoMetricaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();
		
		$cd_contrato = $post['cd_contrato'];
		$metricas    = Zend_Json_Decoder::decode($post['metricas']);
		
		$arrDados = array();
		
		foreach ($metricas as $metrica) {
			$novo = $this->contratoDefinicaoMetrica->createRow();
			$novo->cd_contrato          = $cd_contrato;
			$novo->cd_definicao_metrica = $metrica;
			$novo->save();
		}
	}
	
	public function desassociaContratoDefinicaoMetricaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();
		
		$cd_contrato = $post['cd_contrato'];
		$metricas    = Zend_Json_Decoder::decode($post['metricas']);
		
		$arrDados = array();
		
		foreach ($metricas as $metrica) {
			$where = "cd_contrato = {$cd_contrato} and cd_definicao_metrica = {$metrica}";
			$this->contratoDefinicaoMetrica->delete($where);
		}
	}

	public function gridMetricaAssociadaContratoAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_contrato = $this->_request->getParam('cd_contrato',0);
		$arrContrato = $this->contrato->find($cd_contrato)->current();

		$this->view->res = $this->contratoDefinicaoMetrica->pesquisaDefinicaoMetricaNoContrato($cd_contrato);
		$this->view->st_contrato = $arrContrato->st_contrato;
	}

	public function salvarDadosFatorMetricaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();

		$arrResult = array('error'=>'', 'errorType'=>'', 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_REALIZACAO_OPERACAO'));

		$arrStPadrao		= explode("_", $post["st_padrao"]);
		$cd_contrato		= trim($arrStPadrao[0]);
		$cd_metrica_padrao	= trim($arrStPadrao[1]);

		unset ($post['st_padrao']);
		
		$db = Zend_Registry::get('db');
		$db->beginTransaction();

		try{
			
			$arrUpdateStPadrao['st_metrica_padrao'			 ] = "S";
			$arrUpdateStPadrao['nf_fator_relacao_metrica_pad'] = null;

			$retorno = true;
			$retorno = ($retorno) ? $this->contratoDefinicaoMetrica->update($arrUpdateStPadrao, "cd_contrato = {$cd_contrato} and cd_definicao_metrica = {$cd_metrica_padrao}") : false;
			$retorno = ($retorno) ? $this->atualizaValorFator( $post ) : false;

        	if($retorno){
	        	$db->commit();
	        }else{
	        	$db->rollBack();
	        }
        }catch(Base_Exception_Alert $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 2;
            $arrResult['msg'	  ] = $e->getMessage();
        }catch(Base_Exception_Error $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'	  ] = $e->getMessage();
        }catch(Zend_Exception $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'	  ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
        }
        echo Zend_Json_Encoder::encode($arrResult);
	}

	private function atualizaValorFator( array $arrDados )
	{
		$retorno = true;

		foreach($arrDados as $key=>$value){
			$arrKey = Array();
			$arrKey = explode("_", $key);
			$cd_contrato		  = trim($arrKey[0]);
			$cd_definicao_metrica = trim($arrKey[1]);

			$arrUpdate['nf_fator_relacao_metrica_pad'] = $value;
			$arrUpdate['st_metrica_padrao'			 ] = null;

			$where = "cd_contrato = {$cd_contrato} and cd_definicao_metrica = {$cd_definicao_metrica}";

			if( !$this->contratoDefinicaoMetrica->update($arrUpdate, $where) ){
				throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_VALOR_FATOR'));
				$retorno = false;
			}
		}
		return $retorno;
	}

	public function verificaMetricaPadraoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post		 = $this->_request->getPost();
		
		$cd_contrato = $post['cd_contrato'];
		$metricas    = Zend_Json_Decoder::decode($post['metricas']);

		$arrRetorno = array('comPergunta'=>false, 'pergunta'=>'');

		foreach ($metricas as $metrica) {
			$arrDados = array();
			$arrDados = $this->contratoDefinicaoMetrica->find($cd_contrato, $metrica)->current();

			if( $arrDados->st_metrica_padrao === 'S'){
				$arrRetorno['comPergunta'] = true;
				$arrRetorno['pergunta'	 ] = Base_Util::getTranslator('L_MSG_ALERT_EXCLUSAO_METRICA_PADRO_CONTRATO');
			}
		}
		echo Zend_Json_Encoder::encode($arrRetorno);
	}
}