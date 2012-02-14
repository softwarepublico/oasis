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

class Pedido_PedidoSolicitacaoController extends Base_Controller_ActionPedido
{
	private $db;
    private $_objSolicitacaoPedido;
    private $_objHistoricoPedido;
    private $_objOpcaoRespostaPergunta;
    private $_objSolicitacaoRespostaPedido;
    private $_objArquivoPedido;

	public function init()
    {
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_PED_SOLICITACAO'));

        $this->_objSolicitacaoPedido         = new SolicitacaoPedido($this->_request->getControllerName());
        $this->_objHistoricoPedido           = new HistoricoPedido($this->_request->getControllerName());
        $this->_objOpcaoRespostaPergunta     = new OpcaoRespostaPerguntaPedido($this->_request->getControllerName());
        $this->_objSolicitacaoRespostaPedido = new SolicitacaoRespostaPedido($this->_request->getControllerName());
        $this->_objArquivoPedido             = new ArquivoPedido($this->_request->getControllerName());

		$this->view->situacao                = $this->_objSolicitacaoPedido->getSituacaoSolicitacao();
		$this->db                            = Zend_Db_Table::getDefaultAdapter();
	}

	public function indexAction()
    {
        $arrWhere = array('cd_usuario_pedido = ?'=>$_SESSION['oasis_pedido']['cd_usuario_pedido']);
        $order    = array('dt_solicitacao_pedido DESC');

        $rowSetSolicitacao   = $this->_objSolicitacaoPedido->getSolicitacaoPedido($arrWhere, $order);
		$this->view->pedidos = $rowSetSolicitacao;
	}

	public function formularioAction()
    {
		if(!isset($_SESSION['oasis_pedido'])){
			$this->_redirect("{$this->_module}/pedido-solicitacao/login");
        }

        $usuario = $_SESSION['oasis_pedido'];
        $cd_solicitacao_pedido  = $this->_request->getParam('pedido', 0);


        $rowSetFormulario       = $this->_objOpcaoRespostaPergunta->getQuestionarioPreenchimentoPedido($cd_solicitacao_pedido);
        $this->view->formulario = $rowSetFormulario;

		if($cd_solicitacao_pedido){
            
            $arrWhere['cd_usuario_pedido     = ?'] = $usuario['cd_usuario_pedido'];
            $arrWhere['cd_solicitacao_pedido = ?'] = $cd_solicitacao_pedido;
            $rowSetSolicitacao = $this->_objSolicitacaoPedido->getSolicitacaoPedido($arrWhere);

            //envia para a view apenas o row zero pois o metodo retorna por um fetchAll
			$this->view->pedido = $rowSetSolicitacao->getRow(0);
            
            $rowSetHistorico       = $this->_objHistoricoPedido->getHistoricoPedido(array('cd_solicitacao_historico = ?'=> $cd_solicitacao_pedido));
			$this->view->historico = $rowSetHistorico;

            //como o $rowSetHistorico esta ordenado em ordem de data
            //pega a ultima observação preenchida (sempre no row '0' ) para colocar no textarea
            $this->view->ultimaObservacao = $rowSetHistorico->getRow(0)->tx_descricao_historico;
		}

        $dataInsert = date('Y-m-d H:i:s');
        $situacao = 'P'; // preenchido
		if(strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
			$obs = $this->clear(@$_POST['tx_observacao_pedido']);

            $this->db->beginTransaction();

			if($cd_solicitacao_pedido) {
				if($this->view->pedido) {
					if(strpos('PM', $this->view->pedido['st_situacao_pedido']) !== false){

                        $arrInsertHistorico['cd_solicitacao_pedido' ] = $cd_solicitacao_pedido;
                        $arrInsertHistorico['cd_unidade_pedido'     ] = $usuario['cd_unidade_usuario'];
                        $arrInsertHistorico['dt_registro_historico' ] = $dataInsert;
                        $arrInsertHistorico['status'                ] = $situacao;
                        $arrInsertHistorico['tx_descricao_historico'] = $obs;

                        $this->_objHistoricoPedido->registraHistoricoPedido($arrInsertHistorico);

                        $arrUpdate['cd_solicitacao_pedido'] = $cd_solicitacao_pedido;
                        $arrUpdate['st_situacao_pedido'   ] = $situacao;
                        $arrUpdate['tx_observacao_pedido' ] = $obs;
                        
                        $this->_objSolicitacaoPedido->atualizaStatusPedido($arrUpdate);
					}else{
                        $this->db->commit();
						$this->_redirect("{$this->_module}/pedido-solicitacao");
                    }
				}else{
                    $this->db->commit();
					$this->_redirect("{$this->_module}/pedido-solicitacao");
                }
			}else {

                $arrInsertSolicitacao['cd_solicitacao_pedido'] = $this->_objSolicitacaoPedido->getNextValueOfField('cd_solicitacao_pedido');
                $arrInsertSolicitacao['cd_usuario_pedido'    ] = $usuario['cd_usuario_pedido' ];
                $arrInsertSolicitacao['cd_unidade_pedido'    ] = $usuario['cd_unidade_usuario'];
                $arrInsertSolicitacao['dt_solicitacao_pedido'] = $dataInsert;
                $arrInsertSolicitacao['st_situacao_pedido'   ] = $situacao;
                $arrInsertSolicitacao['tx_observacao_pedido' ] = $obs;

                //insere o primeiro registra da solicitação e retorna o ID da tabela
                $cd_solicitacao_pedido = $this->_objSolicitacaoPedido->insertSolicitacao($arrInsertSolicitacao);

                $arrInsertHistorico['cd_solicitacao_pedido' ] = $cd_solicitacao_pedido;
                $arrInsertHistorico['dt_registro_historico' ] = $dataInsert;
                $arrInsertHistorico['status'                ] = $situacao;
                $arrInsertHistorico['tx_descricao_historico'] = $obs;

                $this->_objHistoricoPedido->registraHistoricoPedido($arrInsertHistorico);
			}

			foreach($this->view->formulario as $rs){
				$where = '';
				$resposta = array();
				$resposta['cd_solicitacao_pedido'] = $cd_solicitacao_pedido;
				$resposta['cd_pergunta_pedido'   ] = $rs['cd_pergunta_pedido'];
				$resposta['cd_resposta_pedido'   ] = $rs['cd_resposta_pedido'];

				foreach($resposta as $campo => $valor){
					$where .= (empty($where) ? '' : ' AND ') . "{$campo} = {$valor}";
                }
				if($rs['st_resposta_texto'] == 'T') {
					$descricao = $this->clear($_POST["perg{$rs['cd_pergunta_pedido']}resp{$rs['cd_resposta_pedido']}"]);

                    $this->_objSolicitacaoRespostaPedido->delete($where);

					if(!empty($descricao)) {
						$resposta['tx_descricao_resposta'] = $descricao;
                        $this->_objSolicitacaoRespostaPedido->insert($resposta);
					}
				}elseif($rs['st_multipla_resposta'] == 'S') {

                    $this->_objSolicitacaoRespostaPedido->delete($where);

					if(count($_POST["perg{$rs['cd_pergunta_pedido']}"])) {
						if(in_array($rs['cd_resposta_pedido'], $_POST["perg{$rs['cd_pergunta_pedido']}"])) {
							if($rs['st_resposta_texto'] == 'S'){
								$resposta['tx_descricao_resposta'] = $this->clear($_POST["perg{$rs['cd_pergunta_pedido']}resp{$rs['cd_resposta_pedido']}texto"]);
                            }
                            $this->_objSolicitacaoRespostaPedido->insert($resposta);
						}
					}
				}elseif($rs['cd_resposta_pedido'] == $_POST["perg{$rs['cd_pergunta_pedido']}"]) {
					if($rs['st_resposta_texto'] == 'U') {

                        $arquivo['cd_arquivo_pedido'] = $this->_objArquivoPedido->getNextValueOfField('cd_arquivo_pedido');
						$arquivo['cd_pergunta_pedido'   ] = $rs['cd_pergunta_pedido'];
                        $arquivo['cd_resposta_pedido'   ] = $rs['cd_resposta_pedido'];
                        $arquivo['cd_solicitacao_pedido'] = $cd_solicitacao_pedido;

						if($file = $this->upload("perg{$rs['cd_pergunta_pedido']}resp{$rs['cd_resposta_pedido']}arquivo")) {
							$arquivo['tx_nome_arquivo'  ] = $file['file'];
							$arquivo['tx_titulo_arquivo'] = $file['name'];
                            
                            if(count($this->_objSolicitacaoRespostaPedido->getRespostaPedido($where)) == 0){
                                $this->_objSolicitacaoRespostaPedido->insert($resposta);
                            }
                            $this->_objArquivoPedido->insert($arquivo);
						}
					}else{
						if($rs['st_resposta_texto'] == 'S'){
							$resposta['tx_descricao_resposta'] = $this->clear($_POST["perg{$rs['cd_pergunta_pedido']}resp{$rs['cd_resposta_pedido']}texto"]);
                        }

                        $this->_objSolicitacaoRespostaPedido->delete($where);
                        $this->_objSolicitacaoRespostaPedido->insert($resposta);
					}
				}else{
                    $arrArquivo = $this->_objArquivoPedido->getArquivoPedido($where);
                    foreach($arrArquivo as $rs){
						@unlink(str_replace('\\', '/', realpath('./upload/')) . "/{$rs['tx_nome_arquivo']}");
                    }
                    $this->_objSolicitacaoRespostaPedido->delete($where);
				}
			}
            $this->db->commit();
			$this->_redirect("{$this->_module}/pedido-solicitacao");
		}
	}

	public function encaminharAction()
    {
		if(!isset($_SESSION['oasis_pedido'])){
			$this->_redirect("{$this->_module}/pedido-solicitacao/login");
        }

        $cd_solicitacao_pedido  = $this->_request->getParam('cd_solicitacao_pedido', 0);

        $arrWhere['cd_usuario_pedido = ?'    ] = $_SESSION['oasis_pedido']['cd_usuario_pedido'];
        $arrWhere['cd_solicitacao_pedido = ?'] = $cd_solicitacao_pedido;
        $rowSet = $this->_objSolicitacaoPedido->getSolicitacaoPedido($arrWhere);

		if($rowSet->valid()){

            $row    = $rowSet->getRow(0);
            $data   = date('Y-m-d H:i:s');
            $status = 'E';

			if($row->st_situacao_pedido == 'P'){
                
                $this->db->beginTransaction();

                $arrDadosInsert['cd_solicitacao_pedido'  ] = $cd_solicitacao_pedido;
                $arrDadosInsert['dt_registro_historico'  ] = $data;
                $arrDadosInsert['status'                 ] = $status;
                $arrDadosInsert['tx_descricao_historico' ] = Base_Util::getTranslator('L_MSG_ALERT_ENCAMINHADO_AUTORIDADE_ANALISAR');

                $this->_objHistoricoPedido->registraHistoricoPedido($arrDadosInsert);
                                
                $arrDadosUpdate['cd_solicitacao_pedido'   ] = $cd_solicitacao_pedido;
                $arrDadosUpdate['dt_encaminhamento_pedido'] = $data;
                $arrDadosUpdate['st_situacao_pedido'      ] = $status;
                $this->_objSolicitacaoPedido->registraEncaminhamentoSolicitantePedido($arrDadosUpdate);

                $this->db->commit();
			}
		}
		$this->_redirect("{$this->_module}/pedido-solicitacao");
	}

	public function atualizarAction()
    {
		if($_SESSION['oasis_pedido']['st_autoridade'] == 'A' || $_SESSION['oasis_pedido']['st_autoridade'] == 'T') {
			$pedido = $this->_request->getParam('pedido', 0);

            $arrWhere['cd_unidade_pedido = ?'    ] = $_SESSION['oasis_pedido']['cd_unidade_usuario'];
            $arrWhere['cd_solicitacao_pedido = ?'] = $pedido;
            $rowSetSolicitacao = $this->_objSolicitacaoPedido->getSolicitacaoPedido($arrWhere);

			if($rowSetSolicitacao->valid()) {
                $row = $rowSetSolicitacao->getRow(0);
				if(strpos('EC', $row->st_situacao_pedido) !== false) {
					if(isset($_POST['situacao'])&& isset($_POST['observacao'])){

                        $status               = $_POST['situacao'];
                        $data                 = date('Y-m-d H:i:s');
                        $observacao           = trim($this->clear(@$_POST['observacao']));
                        $tx_observacao_pedido = ($observacao != '') ? $observacao : null;

                        $this->db->beginTransaction();
                        
                        $arrInsert['cd_solicitacao_pedido' ] = $pedido;
                        $arrInsert['dt_registro_historico' ] = $data;
                        $arrInsert['status'                ] = $status;
                        $arrInsert['tx_descricao_historico'] = $tx_observacao_pedido;
                        $this->_objHistoricoPedido->registraHistoricoPedido($arrInsert);

                        $arrUpdate['cd_solicitacao_pedido'             ] = $pedido;
                        $arrUpdate['st_situacao_pedido'                ] = $status;
                        $arrUpdate['tx_analise_aut_competente' ]         = $tx_observacao_pedido;
                        $arrUpdate['dt_autorizacao_competente'         ] = $data;
                        $arrUpdate['cd_usuario_aut_competente' ]         = $_SESSION['oasis_pedido']['cd_usuario_pedido'];
                        
                        $this->_objSolicitacaoPedido->registraEncaminhamentoAutorizacaoPedido($arrUpdate);

                        $this->db->commit();
					}
				}
			}
		}
		$this->_redirect("{$this->_module}/pedido-solicitacao");
	}

	public function autorizarAction()
    {
        $this->_helper->layout->disableLayout();

        $arrWhere = array('cd_unidade_pedido = ?'=>$_SESSION['oasis_pedido']['cd_unidade_usuario'],
                          'st_situacao_pedido IN (?)'=>array('E','C'));
        $order    = array('dt_solicitacao_pedido DESC');

        $rowSetSolicitacao            = $this->_objSolicitacaoPedido->getSolicitacaoPedido($arrWhere);
        $this->view->pedidosAutorizar = $rowSetSolicitacao;
	}

    public function historicoAction()
    {
        $cd_solicitacao_pedido = $this->_request->getParam('cd_solicitacao_pedido', 0);

        $arrWhere["cd_solicitacao_pedido = ?"] = $cd_solicitacao_pedido;
        
        
		if(($_SESSION['oasis_pedido']['st_autoridade'] == 'A') || ($_SESSION['oasis_pedido']['st_autoridade'] == 'T') ){
            $arrWhere["cd_unidade_pedido = ?"] = $_SESSION['oasis_pedido']['cd_unidade_usuario'];
        }else{
            $arrWhere["cd_usuario_pedido = ?"] = $_SESSION['oasis_pedido']['cd_usuario_pedido'];
        }
        $rowSetSolicitaco = $this->_objSolicitacaoPedido->getSolicitacaoPedido($arrWhere);

        
        //caso não exista dados devido algum problema, redireciona
		if(!$rowSetSolicitaco->valid()){
			$this->_redirect("{$this->_module}/pedido-solicitacao");
        }
        $this->view->pedido = $rowSetSolicitaco->getRow(0);// pega o row zero do fetchAll  pois só retorna 1 elemento

        $rowSetHistorico        = $this->_objHistoricoPedido->getHistoricoPedido(array('cd_solicitacao_historico = ?'=>$cd_solicitacao_pedido));
        $this->view->historico  = $rowSetHistorico;

        $rowSetFormulario       = $this->_objOpcaoRespostaPergunta->getQuestionario($cd_solicitacao_pedido);
		$this->view->formulario = $rowSetFormulario;
		$this->view->usuario    = $_SESSION['oasis_pedido'];
	}

	private function clear($value)
    {
		return str_replace("'", '`', stripslashes((string) $value));
	}

	private function upload($field)
    {
		if(is_uploaded_file($_FILES[$field]['tmp_name'])) {
			$file = array('name' => $_FILES[$field]['name']);
			$file['file'] = 'arq' . time() .  '.' . strtolower(array_pop(explode('.', $file['name'])));
			if(@move_uploaded_file($_FILES[$field]['tmp_name'], str_replace('\\', '/', realpath('./upload/')) . "/$file[file]"))
				return $file;
			@unlink($_FILES[$field]['tmp_name']);
		}
		return false;
	}
}