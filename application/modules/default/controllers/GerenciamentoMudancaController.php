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

class GerenciamentoMudancaController extends Base_Controller_Action
{

	private $objGerenciaMudanca;
	
	public function init()
	{
		parent::init();
		$this->objGerenciaMudanca = new GerenciaMudanca($this->_request->getControllerName());
	}

	public function indexAction()
	{}

	public function getDadosGerenciaMudancaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();
		
		$dt_gerencia_mudanca 		= $arrDados['dt_gerencia_mudanca'];
		$cd_item_controle_baseline 	= $arrDados['cd_item_controle_baseline']; 
		$cd_projeto 				= $arrDados['cd_projeto'];
		$cd_item_controlado 		= $arrDados['cd_item_controlado'];
		$dt_versao_item_controlado 	= $arrDados['dt_versao_item_controlado'];
		
		
		$res = $this->objGerenciaMudanca->find($dt_gerencia_mudanca, $cd_item_controle_baseline, $cd_projeto, $cd_item_controlado, $dt_versao_item_controlado)
									    ->current()
									    ->toArray();
		
		echo Zend_Json::encode($res);
	}
	
	public function salvarDecisaoAnaliseDaMudancaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();
		
		$where  = "dt_gerencia_mudanca 			= '{$arrDados['dt_gerencia_mudanca']}'		and ";
		$where .= "cd_item_controle_baseline	= {$arrDados['cd_item_controle_baseline']}	and ";
		$where .= "cd_projeto					= {$arrDados['cd_projeto']}					and ";
		$where .= "cd_item_controlado			= {$arrDados['cd_item_controlado']}			and ";
		$where .= "dt_versao_item_controlado	= '{$arrDados['dt_versao_item_controlado']}'	";
		
		$arrUpdate['tx_decisao_mudanca'] = ( !empty($arrDados['tx_decisao_mudanca']) ) ? $arrDados['tx_decisao_mudanca'] : null ;  
		$arrUpdate['dt_decisao_mudanca'] = new Zend_Db_Expr("{$this->objGerenciaMudanca->to_date("'" . date("Ymd") . "'", 'YYYYMMDD')}");
		$arrUpdate['st_decisao_mudanca'] = $arrDados['rd_aceite_mudanca'];
		
		if( $this->objGerenciaMudanca->update( $arrUpdate, $where ) ){
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		}else{
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
	}
}