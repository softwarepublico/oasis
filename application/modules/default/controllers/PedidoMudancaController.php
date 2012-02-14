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
                      
class PedidoMudancaController extends Base_Controller_Action
{
	private $objGerenciaMudanca;
	
	public function init()
	{
		parent::init();
        //Incluindo a classe para pegar as constantes
        Zend_Loader::loadClass('ItemControleBaselineController',Base_Util::baseUrlModule('default', 'controllers'));

		$this->objGerenciaMudanca = new GerenciaMudanca($this->_request->getControllerName());
	}

	public function indexAction()
	{
		$this->initView();
	}
	
	public function salvarPedidoMudancaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();
		
		// de acordo com o tipo de mudança será buscado o valor da constante para o cd_item_controle_baseline
		switch($arrDados['acao_mudanca']){
			case 'proposta' 	 : $cd_item_controle_baseline = ItemControleBaselineController::K_ITEM_CONTROLE_BASELINE_PROPOSTA; 		break;
			case 'requisito'	 : $cd_item_controle_baseline = ItemControleBaselineController::K_ITEM_CONTROLE_BASELINE_REQUISITO; 	break;
			case 'regra_negocio' : $cd_item_controle_baseline = ItemControleBaselineController::K_ITEM_CONTROLE_BASELINE_REGRA_NEGOCIO; break;
			case 'caso_de_uso'	 : $cd_item_controle_baseline = ItemControleBaselineController::K_ITEM_CONTROLE_BASELINE_CASO_USO; 		break;
		}
		
		$novo 							 = $this->objGerenciaMudanca->createRow();
		$novo->dt_gerencia_mudanca		 = date("Y-m-d H:i:s");
		$novo->cd_item_controle_baseline = $cd_item_controle_baseline;  
		$novo->cd_projeto				 = $arrDados['cd_projeto']; 
		$novo->cd_item_controlado		 = $arrDados['cd_item_controlado_corrente'];
		$novo->dt_versao_item_controlado = $arrDados['dt_versao_item_conrolado'];
		$novo->cd_projeto_reuniao		 = $arrDados['cd_projeto'];
		$novo->tx_motivo_mudanca		 = $arrDados['tx_motivo_mudanca'];
		$novo->st_mudanca_metrica		 = ($arrDados['rd_mudanca_metrica'] === 'S'					) ? "S" 					: "N";
		$novo->ni_custo_provavel_mudanca = (array_key_exists('qtd_horas', $arrDados)				) ? $arrDados['qtd_horas']	: null;
		$novo->st_reuniao				 = ($arrDados['rd_reuniao_mudanca'] === 'S'					) ? "S"						: "N";
		$novo->cd_reuniao				 = ( array_key_exists('cd_reuniao_pedido_mudanca',$arrDados)) ? $arrDados['cd_reuniao_pedido_mudanca'] : null ;

		if( $novo->save() ){
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			if (K_ENVIAR_EMAIL == "S") {
				$this->setDadosMsgEmailPedidoMudanca($arrDados);
			}
		}else{
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
	}
	
	public function setDadosMsgEmailPedidoMudanca($arrDados)
	{
		$_objMail                    = new Base_Controller_Action_Helper_Mail();
		$_objContrato                = new Contrato();
		$_objProjeto                 = new Projeto();
		$arrContrato                 = $_objContrato->getDadosContratoAtivoObjetoTipoProjeto($arrDados['cd_projeto']);
		$arrProjeto                  = $_objProjeto->find($arrDados['cd_projeto'])->current()->toArray();
		
		$arrInf['_tx_sigla_projeto'] = $arrProjeto['tx_sigla_projeto'];
		$arrInf['_tx_obs']           = $arrDados['tx_motivo_mudanca'];
		
		switch($arrDados['acao_mudanca']){
			case 'proposta'  	 : 
				$arrInf['st_msg_email']   = "1";
				$arrInf['_proposta']      = "<br>Proposta: {$arrDados['cd_item_controlado_corrente']}";
				$arrInf['_requisito']     = "";
				break;
			case 'requisito'	 : 
				$arrInf['st_msg_email']   = "1";
				$_objRequisito            = new Requisito();
				$arrRequisito             = $_objRequisito->find($arrDados['cd_projeto'],$arrDados['cd_item_controlado_corrente'],$arrDados['dt_versao_item_conrolado'])->current();
				$arrInf['_requisito']     = "<br>Requisito: {$arrRequisito['tx_requisito']}";
				$arrInf['_proposta']      = "";
				break;
			case 'regra_negocio' : 
				$arrInf['st_msg_email']   = "2";
				$_objRegraNegocio         = new RegraNegocio();
				$arrRegraNegocio          = $_objRegraNegocio->find($arrDados['cd_item_controlado_corrente'],$arrDados['dt_versao_item_conrolado'],$arrDados['cd_projeto'])->current();
				$arrInf['_regra_negocio'] = "<br>Regra de Negócio: {$arrRegraNegocio['tx_regra_negocio']}";
				$arrInf['_caso_de_uso']   = "";
				break;
			case 'caso_de_uso'	 : 
				$arrInf['st_msg_email']   = "2";
				$_objCasoDeUso            = new CasoDeUso();
				$arrCasoDeUso             = $_objCasoDeUso->getDadosCasoDeUsoEspecifico($arrDados['cd_item_controlado_corrente'], $arrDados['cd_projeto'], $arrDados['dt_versao_item_conrolado']);
				$arrInf['_caso_de_uso']   = "<br>Caso de Uso: {$arrCasoDeUso['tx_caso_de_uso']}";
				$arrInf['_regra_negocio'] = "";
				break;
		}
		
		$arrDadosEmail = $_objMail->enviaEmail($arrContrato['cd_objeto'], $this->_request->getControllerName(), $arrInf);
	}
}