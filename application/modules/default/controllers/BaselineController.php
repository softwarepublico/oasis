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

class BaselineController extends Base_Controller_Action
{

	private $objBaseline;
	private $objRequisito;
	private $objRegraDeNegocio;
	private $objProposta;
	private $objCasoDeUso;
	private $objBaselineItemControle;
	
	public function init()
	{
		parent::init();
        //Incluindo a classe para pegar as constantes
        Zend_Loader::loadClass('ItemControleBaselineController',Base_Util::baseUrlModule('default', 'controllers'));

		$this->objBaseline				= new Baseline($this->_request->getControllerName());
		$this->objRequisito				= new Requisito($this->_request->getControllerName());
		$this->objRegraDeNegocio		= new RegraNegocio($this->_request->getControllerName());
		$this->objProposta				= new Proposta($this->_request->getControllerName());
		$this->objCasoDeUso				= new CasoDeUso($this->_request->getControllerName());
		$this->objBaselineItemControle	= new BaselineItemControle($this->_request->getControllerName());
	}

	public function indexAction()
	{
		$this->initView();
	}

	public function gridBaselineAction()
	{
		$this->_helper->layout->disableLayout();
		$cd_projeto = $this->_request->getParam('cd_projeto');
		
		$res			 = $this->objBaseline->getBaseline($cd_projeto);
		$this->view->res = $res;
	}

	/**
	 * Método que trata a gravação da nova Baseline
	 *
	 */
	public function gerarNovaBaselineAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto = $this->_request->getParam('cd_projeto');
		
		$dt_baseline = date('Y-m-d H:i:s');
		
		//cria a Row para adição em s_baseline
		$addRowBaseline['cd_projeto'	] = $cd_projeto;
		$addRowBaseline['dt_baseline'	] = $dt_baseline;
		$addRowBaseline['st_ativa'		] = "S";
		
		$arrResult = array('error'=>'', 'errorType'=>'', 'errorText'=>'', 'arrData'=>'');
		    
		$this->objBaseline->getDefaultAdapter()->beginTransaction();
        try{
        	//verifica se usuário possui permissão apra acessar BaselineController
        	$this->verificaPermissao();
        	
			$retorno = true;
			$retorno = ($retorno) ? $this->salvaRegistroBaseline($cd_projeto, $addRowBaseline)				: false;
			$retorno = ($retorno) ? $this->salvaRegistroBaselineRequisito( $cd_projeto, $dt_baseline)		: false;
			$retorno = ($retorno) ? $this->salvaRegistroBaselineRegraDeNegocio( $cd_projeto, $dt_baseline)	: false;
			$retorno = ($retorno) ? $this->salvaRegistroBaselineProposta( $cd_projeto, $dt_baseline)		: false;
			$retorno = ($retorno) ? $this->salvaRegistroBaselineCasoDeUso( $cd_projeto, $dt_baseline)		: false;
	
        	if($retorno){
	        	$this->objBaseline->getDefaultAdapter()->commit();
	        }else{
	        	$this->objBaseline->getDefaultAdapter()->rollBack();
	        }
        }catch(Base_Exception_Alert $e){
            $this->objBaseline->getDefaultAdapter()->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 2;
            $arrResult['errorText'] = $e->getMessage();
        }catch(Base_Exception_Error $e){
            $this->objBaseline->getDefaultAdapter()->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['errorText'] = $e->getMessage();
        }catch(Zend_Exception $e){
            $this->objBaseline->getDefaultAdapter()->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['errorText'] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
        }
        echo Zend_Json_Encoder::encode($arrResult);
	}

	/**
	 * Método para salvar um novo registro de Baseline
	 * o método primeiramente atualiza todos os registros referentes ao
	 * projeto passado como parâmetro para null (não ativo) e em seguida cria um novo registro
	 * com o status "S" (ativo)
	 *
	 * @param array $addRow
	 * @param int $cd_projeto
	 * @return boolean
	 * @return Exception
	 */
	private function salvaRegistroBaseline( $cd_projeto, $addRow )
	{
		$select = $this->objBaseline->select()->where("cd_projeto = ?", $cd_projeto);
		$res = $this->objBaseline->getDefaultAdapter()->fetchAll($select);
		
		if( count($res) > 0 ){
			$arrUpdate['st_ativa'] = null;
			if( !$this->objBaseline->update($arrUpdate, "cd_projeto = {$cd_projeto}") ){
				throw new Base_Exception_Error('L_MSG_ERRO_ATUALIZAR_REGISTRO_BASELINE');
			}
		}
		if(!$this->objBaseline->insert($addRow)){
			throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_CRIAR_BASELINE'));
		}
		return true;
	}
	
	/**
	 * Método que cria na tabela a_baseline_item_controlado os registro referentes
	 * aos requitos associados ao projeto passado como parâmetro 
	 *
	 * @param int $cd_projeto
	 * @param timesTamp $dt_baseline
	 * @return boolean
	 * @return Exception
	 */
	private function salvaRegistroBaselineRequisito( $cd_projeto, $dt_baseline )
	{
		//recupera a ultima versao de cada requisito fechado associado ao projeto passado como parametro
		$arrRequisitos = $this->objRequisito->getRequisito( $cd_projeto, true );
		
		if(count($arrRequisitos) == 0){
			return true; // não possui registro não faz nada, retorna verdadeiro para continuar o processamento
		}else{
			foreach($arrRequisitos as $rs){
				$novo								= $this->objBaselineItemControle->createRow();
				$novo->cd_projeto					= $cd_projeto;
				$novo->dt_baseline					= $dt_baseline;
				$novo->cd_item_controle_baseline	= ItemControleBaselineController::K_ITEM_CONTROLE_BASELINE_REQUISITO;
				$novo->cd_item_controlado			= $rs['cd_requisito']; 
				$novo->dt_versao_item_controlado	= $rs['dt_versao_requisito'];
				if(!$novo->save()){
					throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_GERAR_REGISTRO_REQUISITO_BASELINE'));
				}
			}
		}
		return true;
	}
	
	/**
	 * Método que cria na tabela a_baseline_item_controlado os registro referentes
	 * às regras de negócio associadas ao projeto passado como parâmetro 
	 *
	 * @param int $cd_projeto
	 * @param timesTamp $dt_baseline
	 * @return boolean
	 * @return Exception
	 */
	private function salvaRegistroBaselineRegraDeNegocio( $cd_projeto, $dt_baseline )
	{
		//recupera a ultima versao de cada regra de negocio fechada associado ao projeto passado como parametro
		$arrRegraDeNegocio = $this->objRegraDeNegocio->getRegraNegocioWithLastVersion( $cd_projeto, false, true );
		
		if(count($arrRegraDeNegocio) == 0){
			return true; // não possui registro não faz nada, retorna verdadeiro para continuar o processamento
		}else{
			foreach($arrRegraDeNegocio as $rs){
				$novo								= $this->objBaselineItemControle->createRow();
				$novo->cd_projeto					= $cd_projeto;
				$novo->dt_baseline					= $dt_baseline;
				$novo->cd_item_controle_baseline	= ItemControleBaselineController::K_ITEM_CONTROLE_BASELINE_REGRA_NEGOCIO;
				$novo->cd_item_controlado			= $rs['cd_regra_negocio']; 
				$novo->dt_versao_item_controlado	= $rs['dt_regra_negocio'];
				if(!$novo->save()){
					throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_GERAR_REGISTRO_REGRA_DE_NEGOCIO_BASELINE'));
				}
			}
		}
		return true;
	}
	
	/**
	 * Método que cria na tabela a_baseline_item_controlado os registro referentes
	 * às propostas associadas ao projeto passado como parâmetro 
	 *
	 * @param int $cd_projeto
	 * @param timesTamp $dt_baseline
	 * @return boolean
	 * @return Exception
	 */
	private function salvaRegistroBaselineProposta( $cd_projeto, $dt_baseline )
	{
		//recupera a ultima versao de cada proposta fechada associado ao projeto passado como parametro
		$arrProposta = $this->objProposta->getUltimaVersaoPropostaParaBaseline( $cd_projeto );
		
		if(count($arrProposta) == 0){
			return true; // não possui registro não faz nada, retorna verdadeiro para continuar o processamento
		}else{
			foreach($arrProposta as $rs){
				$novo								= $this->objBaselineItemControle->createRow();
				$novo->cd_projeto					= $cd_projeto;
				$novo->dt_baseline					= $dt_baseline;
				$novo->cd_item_controle_baseline	= ItemControleBaselineController::K_ITEM_CONTROLE_BASELINE_PROPOSTA;
				$novo->cd_item_controlado			= $rs['cd_proposta']; 
				$novo->dt_versao_item_controlado	= $rs['dt_fechamento_proposta'];
				if(!$novo->save()){
					throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_GERAR_REGISTRO_PROPOSTA_BASELINE'));
				}
			}
		}
		return true;
	}

	/**
	 * Método que cria na tabela a_baseline_item_controlado os registro referentes
	 * aos casos de uso associados ao projeto passado como parâmetro 
	 *
	 * @param int $cd_projeto
	 * @param timesTamp $dt_baseline
	 * @return boolean
	 * @return Exception
	 */
	private function salvaRegistroBaselineCasoDeUso( $cd_projeto, $dt_baseline )
	{
		//recupera a ultima versao de cada proposta fechada associado ao projeto passado como parametro
		$arrCasoDeUso = $this->objCasoDeUso->getUltimaVersaoTodosCasosDeUsoProjeto( $cd_projeto, true );

		if(count($arrCasoDeUso) == 0){
			return true; // não possui registro não faz nada, retorna verdadeiro para continuar o processamento
		}else{
			foreach($arrCasoDeUso as $rs){
				
				$novo								= $this->objBaselineItemControle->createRow();
				$novo->cd_projeto					= $cd_projeto;
				$novo->dt_baseline					= $dt_baseline;
				$novo->cd_item_controle_baseline	= ItemControleBaselineController::K_ITEM_CONTROLE_BASELINE_CASO_USO;
				$novo->cd_item_controlado			= $rs['cd_caso_de_uso']; 
				$novo->dt_versao_item_controlado	= $rs['dt_versao_caso_de_uso'];
				if(!$novo->save()){
					throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_GERAR_REGISTRO_CASO_DE_USO_BASELINE'));
				}
			}
		}
		return true;
	}	
	
	/**
	 * Método para verificar se o usuário possui permissão para gerar baseline
	 */
	private function verificaPermissao()
	{
		if (ChecaPermissao::possuiPermissao('baseline') === false){
			throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_USUARIO_SEM_PERMISSAO_EFETURAR_OPERACAO'));
		}
	}
}