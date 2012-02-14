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
 
class GerenciamentoMudancaRegraDeNegocioController extends Base_Controller_Action
{
	private $objRegraDeNegocio;
	private $objGerenciaMudanca;
	
	public function init()
	{
		parent::init();
        //Incluindo a classe para pegar as constantes
        Zend_Loader::loadClass('ItemControleBaselineController',Base_Util::baseUrlModule('default', 'controllers'));

		$this->objGerenciaMudanca	= new GerenciaMudanca($this->_request->getControllerName());
		$this->objRegraDeNegocio	= new RegraNegocio($this->_request->getControllerName());
	}

	public function indexAction(){$this->initView();}

	public function gridGerenciamentoMudancaRegraDeNegocioAction()
	{
		$this->_helper->layout->disableLayout();
		
		$mes = $this->_request->getParam('mes');
		$ano = $this->_request->getParam('ano');
		$mes = ($mes == 0)?date('m'):$mes;
		$ano = ($ano == 0)?date('Y'):$ano;
		$mes = substr("00".$mes,-2);
		
		$res			 = $this->objGerenciaMudanca->getMudancaRegraDeNegocio( $mes, $ano, ItemControleBaselineController::K_ITEM_CONTROLE_BASELINE_REGRA_NEGOCIO);
		$this->view->res = $res;
	}

	public function salvaNovaVersaoRegraDeNegocioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados					= $this->_request->getPost();
		
		$cd_projeto					= $arrDados['cd_projeto']; 
		$cd_regra_negocio			= $arrDados['cd_regra_negocio'];
		$dt_regra_negocio			= $arrDados['dt_regra_negocio'];
		$cd_item_controle_baseline	= $arrDados['cd_item_controle_baseline'];
		$dt_gerencia_mudanca		= $arrDados['dt_gerencia_mudanca'];
		$cd_item_controlado			= $arrDados['cd_item_controlado'];
		$dt_versao_item_controlado	= $arrDados['dt_versao_item_controlado'];
		
		//recupera as informação da regra de negocio a ser versionado
		$arrDadosRegraDeNegocio = $this->objRegraDeNegocio->find($cd_regra_negocio, $dt_regra_negocio, $cd_projeto )->current()->toArray();
		
		//inicia a transação do banco
		$this->objRegraDeNegocio->getDefaultAdapter()->beginTransaction();
		
		$novo								= $this->objRegraDeNegocio->createRow();
		$novo->cd_projeto_regra_negocio		= $cd_projeto;
		$novo->dt_regra_negocio				= date('Y-m-d H:i:s');
		$novo->cd_regra_negocio				= $cd_regra_negocio;
		$novo->ni_versao_regra_negocio		= $arrDadosRegraDeNegocio['ni_versao_regra_negocio'	  ] + 1;
		$novo->tx_regra_negocio				= $arrDadosRegraDeNegocio['tx_regra_negocio'		  ];
		$novo->tx_descricao_regra_negocio	= $arrDadosRegraDeNegocio['tx_descricao_regra_negocio'];
		$novo->st_regra_negocio				= $arrDadosRegraDeNegocio['st_regra_negocio'		  ];
		$novo->ni_ordem_regra_negocio		= $arrDadosRegraDeNegocio['ni_ordem_regra_negocio'	  ];
		
		
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
				
				$this->objRegraDeNegocio->getDefaultAdapter()->commit();
				echo Base_Util::getTranslator('L_MSG_SUCESS_ABRIR_REGRA_NEGOCIO');
			}else{
				$this->objRegraDeNegocio->getDefaultAdapter()->rollBack();
				echo Base_Util::getTranslator('L_MSG_ERRO_ABRIR_REGRA_NEGOCIO');
			}
			
		}else{
			$this->objRegraDeNegocio->getDefaultAdapter()->rollBack();
			echo Base_Util::getTranslator('L_MSG_ERRO_ABRIR_REGRA_NEGOCIO');
		}
	}
}