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

class RequisitoPropostaController extends Base_Controller_Action
{
	private $requisito;
	private $objRequisitoDependente;
	
	public function init()
	{
		parent::init();
		$this->requisito				= new Requisito($this->_request->getControllerName());
		$this->objRequisitoDependente	= new RequisitoDependente($this->_request->getControllerName());
	}
	
	public function indexAction(){$this->initView();}
	
	public function requisitosAction(){$this->initView();}
	
	public function requisitoAction(){$this->initView();}

	/**
	 * Método que salva um novo requisito
	 */
	public function salvarNovoRequisitoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();

		$novo 			       			= $this->requisito->createRow();
		$novo->cd_requisito				= $this->requisito->getNextValueOfField('cd_requisito');
		$novo->ni_ordem					= $this->requisito->getNextValueNiOrdemRequisito($arrDados['cd_projeto']);
		$novo->cd_projeto				= $arrDados['cd_projeto'];
		$novo->tx_usuario_solicitante	= $arrDados['tx_usuario_solicitante'];
		$novo->tx_nivel_solicitante		= $arrDados['tx_nivel_solicitante'];
		$novo->tx_requisito				= $arrDados['tx_requisito'];
		$novo->tx_descricao_requisito	= $arrDados['tx_descricao_requisito'];
		$novo->st_prioridade_requisito	= $arrDados['st_prioridade_requisito'];
		$novo->st_requisito				= $arrDados['st_requisito'];
		$novo->st_tipo_requisito		= ( array_key_exists('st_tipo_requisito', $arrDados )) ? 'N' : 'F';
		$novo->dt_versao_requisito		= date("Y-m-d H:i:s");
		$novo->ni_versao_requisito		= 1; // sempre que for gravar o requisito pela 1ª vez esse valor é default

		if($novo->save()) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
	}
	
	/**
	 * Método que altera os dados do requisito caso este ainda esteja aberto
	 */
	public function alterarRequisitoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();

		$where  = "cd_requisito			= {$arrDados['cd_requisito']} and ";
		$where .= "dt_versao_requisito	= '{$arrDados['dt_versao_requisito']}' and ";
		$where .= "cd_projeto			= {$arrDados['cd_projeto']}";
		
		//verifica se o requisito esta aberto
		if( $this->verificaSituacaoRequisito( $arrDados['cd_projeto'], $arrDados['cd_requisito'], $arrDados['dt_versao_requisito'] ) ){
			echo Base_Util::getTranslator('L_MSG_ALERT_REQUISITO_FECHADO_NAO_ALTERAR');
		}else{
			$arrUpdate['tx_usuario_solicitante'		] = $arrDados['tx_usuario_solicitante'];
			$arrUpdate['tx_nivel_solicitante'		] = $arrDados['tx_nivel_solicitante'];
			$arrUpdate['tx_requisito'               ] = $arrDados['tx_requisito'];
			$arrUpdate['tx_descricao_requisito'		] = $arrDados['tx_descricao_requisito'];
			$arrUpdate['st_prioridade_requisito'	] = $arrDados['st_prioridade_requisito'];
			$arrUpdate['st_requisito'				] = $arrDados['st_requisito'];
			$arrUpdate['st_tipo_requisito'			] = ( array_key_exists('st_tipo_requisito', $arrDados )) ? 'N' : 'F';
			
			if($this->requisito->update($arrUpdate, $where)) {
				echo Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
			} else {
				echo Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
			}
		}
	}

	/**
	 * Método que recupera os dados de um requisito
	 */
	public function recuperaDadosRequisitoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$arrDados = $this->_request->getPost();
		
		$cd_requisito		 = $arrDados['cd_requisito'];
		$ni_versao_requisito = $arrDados['ni_versao_requisito'];
		
		$arrDados = $this->requisito->getRequisitoEspecifico( $cd_requisito, $ni_versao_requisito );
		$arrDados["tx_descricao_requisito"] = strip_tags($arrDados["tx_descricao_requisito"]);
				
		echo Zend_Json::encode($arrDados);
	}
	
	/**
	 * Método que recupera os dados para montar a grid
	 */
	public function gridRequisitoPropostaAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_projeto  	 = $this->_request->getParam('cd_projeto');
		$res			 = $this->requisito->getRequisito($cd_projeto);
		$this->view->res = $res;
	}

	/**
	 * Método para montar o combo dos requisitos da aba Dependencia de requisito
	 * cada vez que é alterada a tabela s_requisito
	 */
	public function getComboRequisitosAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados      = $this->_request->getPost();
		$arrRequisitos = $this->requisito->getComboRequisito( $arrDados['cd_projeto'], true );
		
		$options = '';
		foreach( $arrRequisitos as $key=>$value ){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}
		
		echo $options;		
	}

	/**
	 * Método para pesquisar as associações entre os requisitos e suas dependencias
	 */
	public function pesquisaAssociacaoDependenciaRequisitoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();
		
		$cd_projeto   = $arrDados['cd_projeto'  ];
		$cd_requisito = $arrDados['cd_requisito'];
		$dt_versao	  = $arrDados['dt_versao'	];

		if ($cd_requisito == 0) {
			echo '';
		} else {
			// Recordset de regras que nao se encontram associadas
			$arrRequisitoNaoAssociados = $this->objRequisitoDependente->getRequisitoNaoAssociados( $cd_projeto, $cd_requisito, $dt_versao); 
			
			// Recordset de regras que se encontram associadas
			$arrRequisitoAssociados = $this->objRequisitoDependente->getRequisitoAssociados( $cd_projeto, $cd_requisito, $dt_versao ); 

			/*
			 * Os procedimentos abaixo criam os options dos selects de acordo com o seu respectivo recordset. 
			 * Posteriormente eh criado um json que eh enviado ao client (javascript) que adiciona os options aos selects
			 */
			$arr1 = "";
			foreach ($arrRequisitoNaoAssociados as $rs) {
				$arr1 .= "<option value=\"{$rs['cd_requisito']}|{$rs['dt_versao_requisito']}\">{$rs['tx_requisito']}</option>";
			}

			$arr2 = "";
			foreach ($arrRequisitoAssociados as $rs1) {
				$arr2 .= "<option value=\"{$rs1['cd_requisito']}|{$rs1['dt_versao_requisito']}\">{$rs1['tx_requisito']}</option>";
			}
			$retornaOsDois = array($arr1, $arr2);
			echo Zend_Json::encode($retornaOsDois);
		}
	}

	/**
	 * Método que executa a associação de dependencia entre requisitos
	 */
	public function associaDependenciaRequisitoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();
		
		$requisitos = Zend_Json::decode( str_ireplace("\\","",$arrDados['requisitos']) );
		
		$arr1 = explode("|",$arrDados['cmb_requisitos_dependencia']);

		$cd_projeto_ascendente			= $arrDados['cd_projeto'];
		$cd_requisito_ascendente		= trim( $arr1[0] );
		$dt_versao_requisito_ascendente = trim( $arr1[1] );
		
		foreach ($requisitos as $requisito) {
			
			$arr2 = explode("|",$requisito);
			
			$novo 									= $this->objRequisitoDependente->createRow();
			$novo->cd_projeto						= $arrDados['cd_projeto'];
			$novo->cd_requisito						= trim( $arr2[0] );
			$novo->dt_versao_requisito				= trim( $arr2[1] );
			$novo->cd_projeto_ascendente			= $cd_projeto_ascendente;
			$novo->cd_requisito_ascendente			= $cd_requisito_ascendente;
			$novo->dt_versao_requisito_ascendente	= $dt_versao_requisito_ascendente;
			$novo->save();
		}
	}
	
	/**
	 * Método que exclui uma associação de dependencia entre requisitos
	 */
	public function desassociaDependenciaRequisitoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrDados = $this->_request->getPost();
		
		$requisitos = Zend_Json_Decoder::decode( str_ireplace("\\","",$arrDados['requisitos']) );

		$arr1 = explode("|",$arrDados['cmb_requisitos_dependencia']);

		foreach ($requisitos as $requisito) {
			
			$arr2 = explode("|",$requisito);
		
			$where  = "cd_projeto						= {$arrDados['cd_projeto']}		and ";
			$where .= "cd_requisito						= ".trim($arr2[0])."			and ";
			$where .= "cd_projeto_ascendente			= {$arrDados['cd_projeto']}		and ";
			$where .= "cd_requisito_ascendente			= ".trim($arr1[0])."			and ";
			$where .= "dt_versao_requisito_ascendente	= '".trim($arr1[1])."'			and ";	
			$where .= "dt_versao_requisito				= '".trim($arr2[1])."'";
			$this->objRequisitoDependente->delete($where);
		}
	}
	
	public function pesquisaAssociacaoVersaoAnteriorRequisitoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrRetorno		= array('comMsg'=>false,'msg'=>'','arrDados'=>'');
		$arrDados		= $this->_request->getPost();
		$versaoAnterior = ((int)$arrDados['ni_versao_requisito']) - 1;
		
		//Busca os dados da versao anterior do requisito que esta sendo passado como parâmetro
		//para verificar se há associações na versão anterior
		$dadosRequisitoVersaoAnterior = $this->requisito->getRequisitoEspecifico( $arrDados['cd_requisito'], $versaoAnterior );
		
		//Busca os dados das associações da versao anterior caso haja.
		$associacoes = $this->objRequisitoDependente->getDadosVersaoRequisitoAnterior($dadosRequisitoVersaoAnterior['cd_projeto'],
														   							  $dadosRequisitoVersaoAnterior['cd_requisito'],
														   							  $dadosRequisitoVersaoAnterior['dt_versao_requisito']);
														   							  
		if( !empty($associacoes) ){
			$arrRetorno['comMsg'	] = true;
			$arrRetorno['msg'		] = Base_Util::getTranslator('L_MSG_ALERT_VERSAO_ANTERIOR_POSSUI_ASSOCIACAO');
			$arrRetorno['arrDados'	] = Zend_Json::encode($associacoes);
		}
		echo Zend_Json::encode($arrRetorno);
	}

	/**
	 * Método para atualizar as associações do requisto pai (selecionado no combo) na sua ultima versao
	 * quando este possuir uma versão anterior que possuía associações
	 */
	public function atualizaDadosVersaoRequisitoPaiAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrDados 	= $this->_request->getPost();
		$requisitos = Zend_Json_Decoder::decode($arrDados['arrAtualizacao']);
		
		foreach ($requisitos as $requisito) {
			
			$novo		 							= $this->objRequisitoDependente->createRow();
			$novo->dt_versao_requisito				= $requisito['dt_versao_requisito'];
			$novo->cd_requisito						= $requisito['cd_requisito'];
			$novo->cd_projeto						= $arrDados['cd_projeto'];
			$novo->cd_projeto_ascendente			= $arrDados['cd_projeto'];
			$novo->cd_requisito_ascendente			= $arrDados['cd_requisito'];
			$novo->dt_versao_requisito_ascendente	= $arrDados['dt_versao'];
			$novo->save();
		}
	}
	
	public function atualizaDadosVersaoRequisitoDependenteAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrDados = $this->_request->getPost();
		
		//dados do ascendente
		$cd_projeto   		 = $arrDados['cd_projeto'  ];
		$cd_requisito 		 = $arrDados['cd_requisito'];
		$dt_versao_requisito = $arrDados['dt_versao'   ];
		
		// Recordset de regras que se encontram associadas
		$arrRequisitoAssociados = $this->objRequisitoDependente->getRequisitoAssociados( $cd_projeto, $cd_requisito, $dt_versao_requisito );
		
		$erro = '';
		$possuiVersoes = false;

		foreach( $arrRequisitoAssociados as $rs ){

			//busca a ultima versão do requisito
			$arrMaxRequisito = $this->requisito->getUltimaVersaoRequisito( $cd_projeto, $rs['cd_requisito'] );
			
			//Se as dadas forem diferente é porque o requisito de $rs possui uma versão mais atual 
			//entao devera ser feitas atualizações
			/*caso as datas sejam iguais, não faz nada pois a regra já esta na sua ultima versao*/
			if($rs['dt_versao_requisito'] != $arrMaxRequisito[0]['dt_versao_requisito']){

				$possuiVersoes = true;
				$novo		 							= $this->objRequisitoDependente->createRow();
				$novo->dt_versao_requisito				= $arrMaxRequisito[0]['dt_versao_requisito'];
				$novo->cd_requisito						= $rs['cd_requisito'];
				$novo->cd_projeto						= $cd_projeto;
				$novo->cd_projeto_ascendente			= $cd_projeto;
				$novo->cd_requisito_ascendente			= $cd_requisito;
				$novo->dt_versao_requisito_ascendente	= $dt_versao_requisito;
				
				//se salvou efetua o update
				if($novo->save()){
					
					$arrUpdate['st_inativo'] = "S";					
					$arrUpdate['dt_inativacao_requisito'] = new Zend_Db_Expr("{$this->objRequisitoDependente->to_date("'" . date("Ymd") . "'", 'YYYYMMDD')}");

					$where  = "cd_projeto						= {$cd_projeto}					 and ";
					$where .= "dt_versao_requisito				= '{$rs['dt_versao_requisito']}' and ";
					$where .= "cd_requisito						= {$rs['cd_requisito']}			 and ";
					$where .= "cd_projeto_ascendente			= {$cd_projeto}					 and ";
					$where .= "cd_requisito_ascendente			= {$cd_requisito}				 and ";
					$where .= "dt_versao_requisito_ascendente	= '{$dt_versao_requisito}'			 ";
					
					//efetura o update
					if($this->objRequisitoDependente->update( $arrUpdate, $where )){
						$erro = false;
					}
				}else{
					$erro = true;
				}
			}//fim if
			
			if($erro){echo Base_Util::getTranslator('L_MSG_ERRO_ATUALIZAR_VERSAO');}
		}//fim foreach
		
		if($possuiVersoes){
			echo Base_Util::getTranslator('L_MSG_SUCESS_ATUALIZACAO_VERSAO');
		}else{
			echo Base_Util::getTranslator('L_MSG_ALERT_NAO_EXISTE_VERSAO_ATUALIZAR');
		}
	}

	/**
	 * Método que recupera os dados para montar a grid de fechamento de versão de requisito
	 */
	public function gridFechamentoVersaoRequisitoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_projeto  	 = $this->_request->getParam('cd_projeto');
		$res			 = $this->requisito->getRequisito($cd_projeto);
		$this->view->res = $res;
	}
	
	/**
	 * Método para fechar a versão do requisito
	 */
	public function fecharVersaoRequisitoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrDados = $this->_request->getPost();
		
		$where  = "cd_projeto 			= {$arrDados['cd_projeto']}				and ";
        $where .= "cd_requisito			= {$arrDados['cd_requisito']}			and ";
        $where .= "dt_versao_requisito	= '{$arrDados['dt_versao_requisito']}'		";
        
        $arrUpdate['st_fechamento_requisito'] = "S";
        $arrUpdate['dt_fechamento_requisito'] = new Zend_Db_Expr("{$this->requisito->to_date("'" . date("Ymd") . "'", 'YYYYMMDD')}");
        
        if( $this->requisito->update( $arrUpdate, $where ) ){
        	echo Base_Util::getTranslator('L_MSG_SUCESS_FECHAR_REQUISITO');
        }else{
        	echo Base_Util::getTranslator('L_MSG_ERRO_REQUISITO_FECHADO');
        }
	}
	
	/**
	 * Método para verificar se o requisito está fechado ou não
	 */
	private function verificaSituacaoRequisito($cd_projeto, $cd_requisito, $dt_versao_requisito)
	{
		$rs = $this->requisito->find($cd_projeto, $cd_requisito, $dt_versao_requisito)->current()->toArray();
		
		if( !empty($rs) && !is_null($rs['st_fechamento_requisito']) ){ // requisito fechado
			return true;
		}else{
			return false;
		}
	}
}