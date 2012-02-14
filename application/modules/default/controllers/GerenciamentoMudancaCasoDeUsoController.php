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
 
class GerenciamentoMudancaCasoDeUsoController extends Base_Controller_Action
{

	private $objGerenciaMudanca;
	private $objCasoDeUso;
	private $objComplemento;
	private $objInteracao;
    private $dataAtual;
    private $arrPost = array();
    private $arrDadosCasoDeUsoNov = array();
    private $arrDadosInteracaoNov = array();
    private $arrDadosComplementoNov = array();

	public function init()
	{
		parent::init();
        //Incluindo a classe para pegar as constantes
        Zend_Loader::loadClass('ItemControleBaselineController',Base_Util::baseUrlModule('default', 'controllers'));

		$this->objGerenciaMudanca = new GerenciaMudanca($this->_request->getControllerName());
		$this->objCasoDeUso 	  = new CasoDeUso($this->_request->getControllerName());
		$this->objComplemento 	  = new Complemento($this->_request->getControllerName());
		$this->objInteracao		  = new Interacao($this->_request->getControllerName());
	}

	public function indexAction(){$this->initView();}

	public function gridGerenciamentoMudancaCasoDeUsoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$mes = $this->_request->getParam('mes');
		$ano = $this->_request->getParam('ano');
		$mes = ($mes == 0)?date('m'):$mes;
		$ano = ($ano == 0)?date('Y'):$ano;
		$mes = substr("00".$mes,-2);
		
		$res			 = $this->objGerenciaMudanca->getMudancaCasoDeUso( $mes, $ano, ItemControleBaselineController::K_ITEM_CONTROLE_BASELINE_CASO_USO);
		$this->view->res = $res;
	}

	public function salvaNovaVersaoCasoDeUsoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
//		Recebendo os dados da tela 
		$this->arrPost =  $this->_request->getPost();

//		Data Atual do sistema para novo cadastro do caso de uso		
		$this->dataAtual = date('Y-m-d H:i:s');
		
//		Recupera os dados do caso de uso antigo para abrir um novo versionamento;
        $this->montaVersionamentoCasoDeUso();
//      Recupera os dados das interações e abri novas interações com a nova
//		Data do caso de uso.
		$this->montaVersionamentoInteracao();
//      Recupera os dados do complemento para inclui-los novamente com uma nova data
        $this->montaVersionamentoComplemento();

//      Recebe todos as informações para atualizar os casos de uso
        $this->atualizaInformacoesCasoDeUso();
	}

    private function montaVersionamentoCasoDeUso()
    {
        $arrDadosCasoDeUsoAnt = $this->objCasoDeUso->getDadosCasoDeUsoEspecifico($this->arrPost['cd_caso_de_uso'],$this->arrPost['cd_projeto'],$this->arrPost['dt_versao_caso_de_uso']);

        $ni_versao_caso_de_uso = (int)$arrDadosCasoDeUsoAnt['ni_versao_caso_de_uso'];
        
		$this->arrDadosCasoDeUsoNov = $arrDadosCasoDeUsoAnt;
        $this->arrDadosCasoDeUsoNov['st_fechamento_caso_de_uso'] = null;
		$this->arrDadosCasoDeUsoNov['dt_fechamento_caso_de_uso'] = null;
		$this->arrDadosCasoDeUsoNov['ni_versao_caso_de_uso']     = $ni_versao_caso_de_uso + 1;
		$this->arrDadosCasoDeUsoNov['dt_versao_caso_de_uso']     = $this->dataAtual;
    }

    private function montaVersionamentoInteracao()
    {
    	$arrDadosInteraçãoAnt = $this->objInteracao->recuperaInteracao(null,$this->arrPost['cd_caso_de_uso'],null,$this->arrPost['dt_versao_caso_de_uso']);

    	$this->arrDadosInteracaoNov = $arrDadosInteraçãoAnt;
    	foreach($arrDadosInteraçãoAnt as $key=>$value){
    		$this->arrDadosInteracaoNov[$key]['dt_versao_caso_de_uso'] = $this->dataAtual;
    	}
    }

    public function montaVersionamentoComplemento()
    {
        $arrDadosComplementoAnt = $this->objComplemento->recuperaComplemento(null, null, $this->arrPost['cd_caso_de_uso'], $this->arrPost['dt_versao_caso_de_uso']);
        $this->arrDadosComplementoNov = $arrDadosComplementoAnt;
        foreach($arrDadosComplementoAnt as $key=>$value){
            $this->arrDadosComplementoNov[$key]['dt_versao_caso_de_uso'] = $this->dataAtual;
        }
    }

    private function atualizaInformacoesCasoDeUso()
    {
        $arrResult = array('error'=>false,'type'=>'1','msg'=>Base_Util::getTranslator('L_MSG_SUCESS_ABRIR_CASO_USO'));
        $retorno = true;

        $db = Zend_Registry::get('db');
        
        $retorno = true;
        try{
            $db->beginTransaction();
            
        	if($retorno){
	            if(!$this->objCasoDeUso->salvaDefinicaoCasoDeUso($this->arrDadosCasoDeUsoNov)){
	                throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ABRIR_CASO_USO'));
	                $retorno = false;
	            }
        	}

        	if($retorno){
	            foreach($this->arrDadosInteracaoNov as $key=>$value){
	                $retorno = $this->objInteracao->salvarInteracao($value);
	                if(!$retorno){
	                    throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ABRIR_INTERACAO'));
	                }
	            }
        	}
        	if($retorno){
	            foreach($this->arrDadosComplementoNov as $chave=>$valor){
	                $retorno = $this->objComplemento->salvarComplemento($valor);
	                if(!$retorno){
	                    throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ABRIR_COMPLEMENTO'));
	                }
	            }
        	}
            if($retorno){
                $whereGerenciaMudanca = array(
                    'dt_gerencia_mudanca = ?'      => new Zend_Db_Expr($this->objGerenciaMudanca->to_timestamp("'{$this->arrPost['dt_gerencia_mudanca']}'",'YYYY-MM-DD HH24:MI:SS')),
                    'cd_item_controle_baseline = ?'=> $this->arrPost['cd_item_controle_baseline'],
                    'cd_projeto = ?'               => $this->arrPost['cd_projeto'],
                    'cd_item_controlado = ?'       => $this->arrPost['cd_item_controlado'],
                    'dt_versao_item_controlado = ?'=> new Zend_Db_Expr($this->objGerenciaMudanca->to_timestamp("'{$this->arrPost['dt_versao_item_controlado']}'",'YYYY-MM-DD HH24:MI:SS')),
                );
                $arrUpdate['st_execucao_mudanca'] = 'S';
                if(!$this->objGerenciaMudanca->update($arrUpdate, $whereGerenciaMudanca)){
                    $retorno = false;
                    throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ATUALIZAR_GERENCIA_MUDANCA'));
                }
            }
            if($retorno){
                $db->commit();
            }
        }catch(Base_Exception_Error $e){
            $db->rollback();
            $arrResult['error'] = true;
            $arrResult['type']  = 3;
            $arrResult['msg']   = $e->getMessage();
        }catch(Zend_Exception $e){
            $db->rollback();
            $arrResult['error'] = true;
            $arrResult['type']  = 3;
            $arrResult['msg']   = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
        }
        echo Zend_Json_Encoder::encode($arrResult);
    }
}