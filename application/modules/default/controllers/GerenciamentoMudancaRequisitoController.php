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

class GerenciamentoMudancaRequisitoController extends Base_Controller_Action
{
	private $objRequisito;
	private $objGerenciaMudanca;
	
	public function init()
	{
		parent::init();
        //Incluindo a classe para pegar as constantes
        Zend_Loader::loadClass('ItemControleBaselineController',Base_Util::baseUrlModule('default', 'controllers'));

		$this->objGerenciaMudanca = new GerenciaMudanca($this->_request->getControllerName());
		$this->objRequisito		  = new Requisito($this->_request->getControllerName());
	}

	public function indexAction(){$this->initView();}

	public function gridGerenciamentoMudancaRequisitoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$mes = $this->_request->getParam('mes');
		$ano = $this->_request->getParam('ano');
		$mes = ($mes == 0)?date('m'):$mes;
		$ano = ($ano == 0)?date('Y'):$ano;
		$mes = substr("00".$mes,-2);
		
		$res			 = $this->objGerenciaMudanca->getMudancaRequisito( $mes, $ano, ItemControleBaselineController::K_ITEM_CONTROLE_BASELINE_REQUISITO );
		$this->view->res = $res;
	}

	public function salvaNovaVersaoRequisitoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados					= $this->_request->getPost();
		
		$cd_projeto					= $arrDados['cd_projeto']; 
		$cd_requisito				= $arrDados['cd_requisito'];
		$dt_versao_requisito		= $arrDados['dt_versao_requisito'];
		$cd_item_controle_baseline	= $arrDados['cd_item_controle_baseline'];
		$dt_gerencia_mudanca		= $arrDados['dt_gerencia_mudanca'];
		$cd_item_controlado			= $arrDados['cd_item_controlado'];
		$dt_versao_item_controlado	= $arrDados['dt_versao_item_controlado'];
		$ni_versao_requisito		= $arrDados['ni_versao_requisito'];
		
		//recupera as informação do requisito a ser versionado
		$arrDadosRequisito = $this->objRequisito->find($cd_projeto, $cd_requisito, $dt_versao_requisito )->current()->toArray();
		
		//inicia a transação do banco
		$this->objRequisito->getDefaultAdapter()->beginTransaction();
		
		$novo							= $this->objRequisito->createRow();
		$novo->cd_projeto				= $cd_projeto;
		$novo->dt_versao_requisito		= date('Y-m-d H:i:s');
		$novo->cd_requisito				= $cd_requisito;
		$novo->ni_versao_requisito		= $arrDadosRequisito['ni_versao_requisito'	  ] + 1;
		$novo->tx_requisito				= $arrDadosRequisito['tx_requisito'			  ];
		$novo->tx_descricao_requisito	= $arrDadosRequisito['tx_descricao_requisito' ];
		$novo->tx_usuario_solicitante	= $arrDadosRequisito['tx_usuario_solicitante' ];
		$novo->tx_nivel_solicitante		= $arrDadosRequisito['tx_nivel_solicitante'	  ];
		$novo->st_tipo_requisito		= $arrDadosRequisito['st_tipo_requisito'	  ];
		$novo->st_prioridade_requisito	= $arrDadosRequisito['st_prioridade_requisito'];
		$novo->st_requisito				= $arrDadosRequisito['st_requisito'			  ];
		$novo->ni_ordem					= $arrDadosRequisito['ni_ordem'			  	  ];
		
		//salva a nova versao do requisito
		if($novo->save()){
			
			//atualiza a tabela a_gerencia_mudanca
			$arrUpdate['st_execucao_mudanca'] = "S";
			
			$where  = "dt_gerencia_mudanca			= '{$dt_gerencia_mudanca}'		and ";
			$where .= "cd_item_controle_baseline	= {$cd_item_controle_baseline}	and ";
			$where .= "cd_projeto					= {$cd_projeto}					and ";
			$where .= "cd_item_controlado			= {$cd_item_controlado}			and ";
			$where .= "dt_versao_item_controlado	= '{$dt_versao_item_controlado}'	";
			
			if($this->objGerenciaMudanca->update( $arrUpdate, $where )){
				
				$this->objRequisito->getDefaultAdapter()->commit();
				echo Base_Util::getTranslator('L_MSG_SUCESS_ABRIR_REQUISITO');
			}else{
				$this->objRequisito->getDefaultAdapter()->rollBack();
				echo Base_Util::getTranslator('L_MSG_ERRO_ABRIR_REQUISITO');
			}
		}else{
			$this->objRequisito->getDefaultAdapter()->rollBack();
			echo Base_Util::getTranslator('L_MSG_ERRO_ABRIR_REQUISITO');
		}
	}
}