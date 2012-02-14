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

class Parcela extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_PARCELA;
	protected $_primary  = array('cd_parcela','cd_projeto','cd_proposta');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getParcela($cd_projeto = null, $retornarArray = false)
	{
        $select = $this->select();
		if(!is_null($cd_projeto)){
			$select->where("cd_projeto = ?",$cd_projeto, Zend_Db::INT_TYPE);
		}

        $retorno = $this->fetchAll($select);

		if($retornarArray){
			$retorno = $retorno->toArray();
		}

		return $retorno;
	}
	
	public function getProximaParcela($cd_projeto)
	{
		$select = $this->select()
                       ->where("cd_projeto = ?", $cd_projeto, Zend_Db::INT_TYPE)
					   ->order('ni_parcela');
		
		$arrParcela = $this->fetchAll($select)->toArray();
		$proximaParcela = count($arrParcela);
        $proximaParcela += 1;
		return $proximaParcela;
	}
	
	public function getHorasDisponivelProjeto($cd_projeto,$cd_proposta)
	{
		
		$objProposta = new Proposta();
		//recupera a quantidade de horas total do projeto
		$quantidadeHorasTotal = $objProposta->getHorasProjetoProposta($cd_projeto,$cd_proposta);

		//recupera todos as parcelas do projeto
		$select = $this->select()
                       ->where("cd_projeto  = ?", $cd_projeto, Zend_Db::INT_TYPE )
                       ->where("cd_proposta = ?", $cd_proposta, Zend_Db::INT_TYPE)
                       ->where("st_modulo_proposta IS NULL");
		
		$arrHorasParcela = $this->fetchAll($select);
		$arrHorasParcela = $arrHorasParcela->toArray();
		
		$horasDisponiveis = 0;
		if($arrHorasParcela){
			foreach($arrHorasParcela as $key=>$value){
				$horasDisponiveis += $arrHorasParcela[$key]['ni_horas_parcela'];	
			}
		}
		$horasDisponiveis = (number_format($quantidadeHorasTotal,1,'.','') - number_format($horasDisponiveis,1,'.',''));

		return $horasDisponiveis;
	}

	public function getParcelaProposta($cd_projeto, $cd_proposta, $retornarArray=false)
	{
		$select = $this->select()
                       ->where("cd_projeto  = ?", $cd_projeto, Zend_Db::INT_TYPE)
                       ->where("cd_proposta = ?", $cd_proposta, Zend_Db::INT_TYPE)
                       ->order("ni_parcela");

		$parcelas = $this->fetchAll($select);
		
		if($retornarArray){
			$parcelas = $parcelas->toArray();
		}
		
		return $parcelas;
	}

    public function getValorParcelaProposta($cd_projeto, $cd_proposta, $cd_parcela)
	{

        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('par'=>$this->_name),
                      'ni_horas_parcela',
                     $this->_schema);
  
        
        $select->where("cd_projeto  = ?", $cd_projeto, Zend_Db::INT_TYPE)
                       ->where("cd_proposta = ?", $cd_proposta, Zend_Db::INT_TYPE)
                       ->where("cd_parcela  = ?", $cd_parcela, Zend_Db::INT_TYPE);
                       
		$valor_parcela = $this->fetchRow($select)->toArray();
		
		return $valor_parcela['ni_horas_parcela'];
	}

    public function getComboParcelaProposta($cd_projeto, $cd_proposta, $comSelecione=false)
	{
		$select = $this->select()
                       ->where("cd_projeto  = ?", $cd_projeto, Zend_Db::INT_TYPE)
                       ->where("cd_proposta = ?", $cd_proposta, Zend_Db::INT_TYPE)
                       ->order("ni_parcela");
		
		$rowSetParcelas = $this->fetchAll($select);

        $arrParcelas = array();
        if($comSelecione){
            $arrParcelas[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        }

        foreach ($rowSetParcelas as $value) {
            $arrParcelas[$value->cd_parcela] = Base_Util::getTranslator('L_VIEW_PARCELA_NR').' '.$value->ni_parcela;
        }
		return $arrParcelas;
	}
	
	public function getUltimaParcelaProposta($cd_projeto, $cd_proposta)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from($this,array('cd_ultima_parcela'=>new Zend_Db_Expr("MAX(cd_parcela)")));
        $select->where('cd_projeto  = ?', $cd_projeto, Zend_Db::INT_TYPE);
        $select->where('cd_proposta = ?', $cd_proposta, Zend_Db::INT_TYPE);
        $select->where('st_modulo_proposta IS NULL');
        $select->where(new Zend_Db_Expr("(ni_ano_previsao_parcela {$this->concat()}'/'{$this->concat()} {$this->substring("'00' {$this->concat()} ni_mes_previsao_parcela","{$this->length("'00' {$this->concat()} ni_mes_previsao_parcela")}-1","2")}) = ?"), $this->select()
                                                                                                                               ->from(array('a'=>$this->_name),
                                                                                                                                      array('data'=>new Zend_Db_Expr("MAX((ni_ano_previsao_parcela {$this->concat()}'/'{$this->concat()} {$this->substring("'00' {$this->concat()} ni_mes_previsao_parcela","{$this->length("'00' {$this->concat()} ni_mes_previsao_parcela")}-1","2")}))")),
                                                                                                                                      $this->_schema)
                                                                                                                               ->where('a.cd_projeto  = ?', $cd_projeto, Zend_Db::INT_TYPE)
                                                                                                                               ->where('a.cd_proposta = ?', $cd_proposta, Zend_Db::INT_TYPE)
                                                                                                                               ->where('a.st_modulo_proposta IS NULL'));
		return $this->fetchRow($select)->cd_ultima_parcela;
	}

	public function getControleParcelas($mes, $ano, $cd_contrato = null)	
	{
		//Seleciona os registros das parcelas que estão ativas (st_ativo = 'S')
		//da tabela s_processamento_parcela e faz um left join com a tabela de parcela
		//As parcelas ativas aparecerão com pelo menos o flag de autorização setado
		//As parcelas que nunca foram para processamento ou que tiveram o processamento
		//abandonado (st_ativo da tabela s_processamento_parcela igual a null)
		//estarão com todos os flags de processamento nulos
		//Somente buscará as parcelas cujas propostas foram processadas
		//(procprop.st_ativo = 'S' and	procprop.st_alocacao_proposta = 'S')

        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('par'=>$this->_name),
                      array('cd_projeto','cd_proposta','cd_parcela','ni_parcela','ni_horas_parcela'),
                     $this->_schema);
		$select->joinLeft(array('parcelas_ativas'=>$this->select()->setIntegrityCheck(false)
                                                        ->from(array('procpar'=>KT_S_PROCESSAMENTO_PARCELA),
                                                               array('cd_projeto','cd_proposta','cd_parcela','st_autorizacao_parcela','st_fechamento_parcela','st_parecer_tecnico_parcela','st_aceite_parcela','st_homologacao_parcela','st_pendente','cd_objeto_execucao'),
                                                               $this->_schema)
                                                        ->where('procpar.st_ativo = ?', 'S')),
                         '(par.cd_projeto  = parcelas_ativas.cd_projeto) AND (par.cd_proposta = parcelas_ativas.cd_proposta) AND (par.cd_parcela  = parcelas_ativas.cd_parcela)',
                         array('st_autorizacao_parcela', 'st_fechamento_parcela', 'st_parecer_tecnico_parcela','st_aceite_parcela','st_homologacao_parcela','st_pendente'));

        $select->join(array('prop'=>KT_S_PROPOSTA),
                      '(par.cd_projeto  = prop.cd_projeto) AND (par.cd_proposta = prop.cd_proposta)',
                      array(),
                      $this->_schema);
        $select->join(array('procprop'=>KT_S_PROCESSAMENTO_PROPOSTA),
                      '(procprop.cd_projeto  = prop.cd_projeto) AND (procprop.cd_proposta = prop.cd_proposta)',
                      array(),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(par.cd_projeto = proj.cd_projeto)',
                      'tx_sigla_projeto',
                      $this->_schema);
        $select->where('par.st_modulo_proposta IS NULL')
               ->where('procprop.st_ativo = ?','S')
//               ->where('procprop.st_alocacao_proposta = ?','S')
               ->where('par.ni_mes_previsao_parcela = ?', $mes, Zend_Db::INT_TYPE)
               ->where('par.ni_ano_previsao_parcela = ?', $ano, Zend_Db::INT_TYPE);
        $select->order(array('proj.tx_sigla_projeto','par.cd_proposta','par.ni_parcela'));

		if (!is_null($cd_contrato)){

            $select->join(array('cp'=>$this->select()
                                           ->setIntegrityCheck(false)
                                           ->from(KT_A_CONTRATO_PROJETO, 'cd_projeto', $this->_schema)
                                           ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE)),
                          '(cp.cd_projeto = prop.cd_projeto)',
                          array());
            $select->joinLeft(array('obj'=>KT_S_OBJETO_CONTRATO),
                          '(obj.cd_objeto = parcelas_ativas.cd_objeto_execucao) AND (obj.cd_contrato = '.$cd_contrato.')',
                          array(),
                          $this->_schema);
		}
		if ($ano >= date('Y') && ($mes >= date('m'))) {
			$select->where('procprop.st_alocacao_proposta = ?','S');
		}
		
		return $this->fetchAll($select)->toArray();
	}
	
	public function getDiferencaParcelaHorasProposta($cd_projeto, $ni_horas_parcela_proposta)
	{
        $select = $this->select()
                       ->from($this,array('soma_horas_parcela_proposta'=>new Zend_Db_Expr('SUM(ni_horas_parcela)')))
                       ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                       ->where('cd_proposta = ?', 1, Zend_Db::INT_TYPE)
                       ->where('st_modulo_proposta = ?', 'S');

		$rowSomaHorasParcelaProposta = $this->fetchRow($select);
		$diferencaHoras              = (float)$ni_horas_parcela_proposta - (float)$rowSomaHorasParcelaProposta->soma_horas_parcela_proposta;
		
		return $diferencaHoras;					
	}
	
	public function defineParcelaProposta($cd_projeto, $ni_horas_parcela_proposta)
	{
		$ret = 1;
		$proposta = new Proposta();
		$rowProposta = $proposta->fetchRow(array("cd_projeto = ?"=>$cd_projeto, "cd_proposta = ?"=>1));
		
		$whereParcelaUm = array("cd_projeto = ?"=>$cd_projeto, "cd_proposta = ?"=>1, "ni_parcela = ?"=>1);
		$rowParcelaUm   = $this->fetchRow($whereParcelaUm);
		$cd_parcela_um  = $rowParcelaUm->cd_parcela;
		if (is_null($rowProposta->st_contrato_anterior)){
			if (is_null($rowProposta->st_alteracao_proposta)){
				if (!is_null($rowParcelaUm)){
					$row = array('ni_horas_parcela' => $ni_horas_parcela_proposta);
					
					if ($this->update($row, $whereParcelaUm)){
						$ret = 1;
					}else{
						$ret = 0;
					}
				}
			}else{
				$ni_horas_parcela_proposta_diferenca = $this->getDiferencaParcelaHorasProposta($cd_projeto, $ni_horas_parcela_proposta);
		
				if ($ni_horas_parcela_proposta_diferenca != 0){
					$erros = false;	
					
					//cria uma parcela automática com a diferença de horas de orçamento da proposta
					$erros = $this->criaParcelaDocumentoPropostaAutomatica($cd_projeto, $ni_horas_parcela_proposta_diferenca, $cd_parcela);
			    	//cria o registro de processamento da parcela automática
					if ($erros === false){
						$erros = $this->criaProcessamentoParcelaDocumentoPropostaAutomatica($cd_projeto, $cd_parcela_um, $cd_parcela);
					}
				    
					//cria um produto para a parcela automática
					if ($erros === false){
						$erros = $this->criaProdutoParcelaDocumentoPropostaAutomatica($cd_projeto, $cd_parcela);
				    }
			    
					$ret = ($erros === true)? 0 : 1;
				}
			}
		}
		return $ret;	
	}

	public function criaParcelaDocumentoPropostaAutomatica($cd_projeto, $ni_horas_parcela_proposta_diferenca, &$cd_parcela)
	{
		$erros = false;
		
		$row                       = array();
		$cd_parcela                = $this->getNextValueOfField('cd_parcela');
		$row['cd_parcela']         = $cd_parcela;
	    $row['cd_projeto']         = $cd_projeto;
	    $row['cd_proposta']        = 1;
	    $row['ni_parcela']         = $this->getProximaParcela($cd_projeto);
	    $row['ni_horas_parcela']   = $ni_horas_parcela_proposta_diferenca;
	    $row['st_modulo_proposta'] = "S";

	    if (!$this->insert($row)){
	    	$erros = true;
	    }
	    
	    return $erros;
	}
	
	public function criaProcessamentoParcelaDocumentoPropostaAutomatica($cd_projeto, $cd_parcela_um, $cd_parcela)
	{
		$erros = false;
		
    	$processamentoParcela    = new ProcessamentoParcela();
		$rowProcessamentoParcela = $processamentoParcela->fetchRow(array("cd_projeto = ?" =>$cd_projeto,
                                                                         "cd_proposta = ?"=>1,
                                                                         "cd_parcela = ?" =>$cd_parcela_um));
		
		$row = array();
		$row['cd_processamento_parcela']            = $processamentoParcela->getNextValueOfField('cd_processamento_parcela');
	    $row['cd_projeto']                          = $cd_projeto;
	    $row['cd_proposta']                         = 1;
	    $row['cd_parcela']                          = $cd_parcela;
	    $row['cd_objeto_execucao']                  = $rowProcessamentoParcela->cd_objeto_execucao;
	    $row['ni_ano_solicitacao_execucao']         = $rowProcessamentoParcela->ni_ano_solicitacao_execucao;
	    $row['ni_solicitacao_execucao']             = $rowProcessamentoParcela->ni_solicitacao_execucao;
	    $row['st_autorizacao_parcela']              = $rowProcessamentoParcela->st_autorizacao_parcela;
	    $row['dt_autorizacao_parcela']              = $rowProcessamentoParcela->dt_autorizacao_parcela;
	    $row['cd_prof_autorizacao_parcela']         = $rowProcessamentoParcela->cd_prof_autorizacao_parcela;
	    $row['st_fechamento_parcela']               = "S";
	    $row['dt_fechamento_parcela']               = date('Y-m-d H:i:s');
	    $row['cd_prof_fechamento_parcela']          = $_SESSION["oasis_logged"][0]["cd_profissional"];
	    $row['st_ativo']                            = "S";

	    if (!$processamentoParcela->insert($row)){
	    	$erros = true;
	    }
	    	
	    return $erros;	    
	}
	
	public function criaProdutoParcelaDocumentoPropostaAutomatica($cd_projeto, $cd_parcela)
	{
		$erros = false;
		
    	$produtoParcela = new ProdutoParcela();
    	$mes            = date("n");
    	$ano            = date("Y");
    	
    	$row = array();
		$row['cd_produto_parcela'] = $produtoParcela->getNextValueOfField('cd_produto_parcela');
		$row['cd_proposta']        = 1;
		$row['cd_projeto']         = $cd_projeto;
		$row['cd_parcela']         = $cd_parcela;

        $mesAno = Base_Util::getMes($mes)."/{$ano}";
		$row['tx_produto_parcela'] = Base_Util::getTranslator('L_SQL_CRIACAO_PARCELA_AUTOMATICA_DIFERENCA_DECORRENTE_ALTERACAO', $mesAno);
		
		if (!$produtoParcela->insert($row)){
			$erros = true;
		}

	    return $erros;		
	}
	
	public function getSomaHorasParcelaPeriodoContrato($cd_contrato, $cd_projeto, $cd_proposta, $mes_inicial, $ano_inicial, $mes_final, $ano_final)
	{
		/* Comentado para ajuste devido a contratos seguidos envolvendo o mesmo projeto
		 * sendo estes contratos um terminando e outro iniciando no mesmo mês e ano.
		 * Com o select abaixo, uma parcela do mesmo projeto autorizada no contrato anterior entrava na soma
		 * de horas do contrato que se iniciou
		 * 
		 * $sql = "SELECT
					sum(ni_horas_parcela) as ni_horas_parcela 
				FROM 
					{$this->_schema}.".KT_S_PARCELA."
				WHERE 
					cd_projeto = {$cd_projeto} 
				and 
					cd_proposta = {$cd_proposta} 
				and 
					(ni_ano_previsao_parcela*100)+ni_mes_previsao_parcela  between {$ano_mes_inicial} and {$ano_mes_final}
				and 
					st_modulo_proposta is null";*/

		$ano_mes_inicial = ($ano_inicial*100)+$mes_inicial;
		$ano_mes_final   = ($ano_final*100)+$mes_final;

        $select = $this->select()
                       ->setIntegrityCheck(FALSE);
        $select->from(array('par'=>$this->_name),
                      array('ni_horas_parcela'=>new Zend_Db_Expr('SUM(ni_horas_parcela)')),
                      $this->_schema);
        $select->joinLeft(array('parcelas_autorizadas_outros_contratos'=> $this->select()
                                                                               ->setIntegrityCheck(false)
                                                                               ->from(array('procpar'=>KT_S_PROCESSAMENTO_PARCELA),
                                                                                      'cd_objeto_execucao',
                                                                                      $this->_schema)
                                                                               ->join(array('obj'=>KT_S_OBJETO_CONTRATO),
                                                                                      '(procpar.cd_objeto_execucao = obj.cd_objeto)',
                                                                                      array(),
                                                                                      $this->_schema)
                                                                               ->join(array('cont'=>KT_S_CONTRATO),
                                                                                      '(obj.cd_contrato = cont.cd_contrato)',
                                                                                      array(),
                                                                                      $this->_schema)
                                                                               ->join(array('parc'=>$this->_name),
                                                                                      '(procpar.cd_parcela = parc.cd_parcela)',
                                                                                      'cd_parcela',
                                                                                      $this->_schema)
                                                                               ->where('parc.cd_projeto  = ?', $cd_projeto, Zend_Db::INT_TYPE)
                                                                               ->where('parc.cd_proposta = ?', $cd_proposta, Zend_Db::INT_TYPE)
                                                                               ->where(new Zend_Db_Expr("(ni_ano_previsao_parcela*100)+ni_mes_previsao_parcela  BETWEEN {$ano_mes_inicial} AND {$ano_mes_final}"))
                                                                               ->where('st_modulo_proposta IS NULL')
                                                                               ->where('cont.cd_contrato <> ?', $cd_contrato, Zend_Db::INT_TYPE)
                                                                               ->where('procpar.st_ativo  = ?', 'S')),
                          '(par.cd_parcela = parcelas_autorizadas_outros_contratos.cd_parcela)',
                          array());
        $select->where('cd_projeto  = ?', $cd_projeto, Zend_Db::INT_TYPE)
               ->where('cd_proposta = ?', $cd_proposta, Zend_Db::INT_TYPE)
               ->where(new Zend_Db_Expr("(ni_ano_previsao_parcela*100)+ni_mes_previsao_parcela  BETWEEN {$ano_mes_inicial} AND {$ano_mes_final}"))
               ->where('st_modulo_proposta IS NULL')
               ->where('parcelas_autorizadas_outros_contratos.cd_parcela IS NULL');

        return $this->fetchRow($select)->toArray();
	}
	
	public function getSomaHorasParcelaMes($cd_contrato, $cd_projeto, $cd_proposta, $mes, $ano)
	{
		/* Comentado para ajuste devido a contratos seguidos envolvendo o mesmo projeto
		 * sendo estes contratos um terminando e outro iniciando no mesmo mês e ano.
		 * Com o select abaixo, uma parcela do mesmo projeto autorizada no contrato anterior entrava na soma
		 * de horas do contrato que se iniciou
		 *
		 * $sql = "select
					sum(ni_horas_parcela) as ni_horas_parcela 
				from 
					{$this->_schema}.".KT_S_PARCELA."
				where 
					cd_projeto = {$cd_projeto}
				and 
					cd_proposta = {$cd_proposta}
				and 
					ni_mes_previsao_parcela = {$mes} 
				and 
					ni_ano_previsao_parcela = {$ano} 
				and 
					st_modulo_proposta is null";*/

		$select = $this->select()->setIntegrityCheck(false);

        $select->from(array('par'=>$this->_name),
                      array('ni_horas_parcela'=>new Zend_Db_Expr('SUM(ni_horas_parcela)')),
                      $this->_schema);
        $select->joinLeft(array('parcelas_autorizadas_outros_contratos'=> $this->select()
                                                                               ->setIntegrityCheck(false)
                                                                               ->from(array('procpar'=>KT_S_PROCESSAMENTO_PARCELA),
                                                                                      'cd_objeto_execucao',
                                                                                      $this->_schema)
                                                                               ->join(array('obj'=>KT_S_OBJETO_CONTRATO),
                                                                                      '(procpar.cd_objeto_execucao = obj.cd_objeto)',
                                                                                      array(),
                                                                                      $this->_schema)
                                                                               ->join(array('cont'=>KT_S_CONTRATO),
                                                                                      '(obj.cd_contrato = cont.cd_contrato)',
                                                                                      array(),
                                                                                      $this->_schema)
                                                                               ->join(array('parc'=>$this->_name),
                                                                                      '(procpar.cd_parcela = parc.cd_parcela)',
                                                                                      'cd_parcela',
                                                                                      $this->_schema)
                                                                               ->where('parc.cd_projeto  = ?', $cd_projeto, Zend_Db::INT_TYPE)
                                                                               ->where('parc.cd_proposta = ?', $cd_proposta, Zend_Db::INT_TYPE)
                                                                               ->where('ni_ano_previsao_parcela = ?', $ano, Zend_Db::INT_TYPE)
                                                                               ->where('ni_mes_previsao_parcela = ?', $mes, Zend_Db::INT_TYPE)
                                                                               ->where('st_modulo_proposta IS NULL')
                                                                               ->where('cont.cd_contrato <> ?', $cd_contrato, Zend_Db::INT_TYPE)
                                                                               ->where('procpar.st_ativo  = ?', 'S')),
                          '(par.cd_parcela = parcelas_autorizadas_outros_contratos.cd_parcela)',
                          array());
        $select->where('cd_projeto  = ?', $cd_projeto, Zend_Db::INT_TYPE)
               ->where('cd_proposta = ?', $cd_proposta, Zend_Db::INT_TYPE)
               ->where('ni_ano_previsao_parcela = ?', $ano, Zend_Db::INT_TYPE)
               ->where('ni_mes_previsao_parcela = ?', $mes, Zend_Db::INT_TYPE)
               ->where('st_modulo_proposta IS NULL')
               ->where('parcelas_autorizadas_outros_contratos.cd_parcela IS NULL');

        return $this->fetchRow($select)->ni_horas_parcela;
	}
	
	public function getParcelasHomologadasNoMes($mes, $ano, $cd_contrato)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('par'=>$this->_name),
                      array('cd_projeto',
                            'cd_proposta',
                            'cd_parcela',
                            'ni_parcela',
                            'ni_horas_parcela'),
                      $this->_schema);
        $select->join(array('procpar'=>KT_S_PROCESSAMENTO_PARCELA),
                      '(par.cd_projeto = procpar.cd_projeto) AND (par.cd_proposta = procpar.cd_proposta) AND (par.cd_parcela = procpar.cd_parcela)',
                      array(),
                      $this->_schema);
        $select->join(array('prop'=>KT_S_PROPOSTA),
                      '(par.cd_projeto = prop.cd_projeto) AND (par.cd_proposta = prop.cd_proposta)',
                      'st_encerramento_proposta',
                      $this->_schema);
        $select->join(array('cp'=>KT_A_CONTRATO_PROJETO),
                      '(cp.cd_projeto = prop.cd_projeto)',
                      array(),
                      $this->_schema);
        $select->join(array('obj'=>KT_S_OBJETO_CONTRATO),
                      "(procpar.cd_objeto_execucao = obj.cd_objeto)",
                      array(),
                      $this->_schema);
        $select->where('ni_mes_execucao_parcela = ?', $mes, Zend_Db::INT_TYPE)
               ->where('ni_ano_execucao_parcela = ?', $ano, Zend_Db::INT_TYPE)
               ->where('st_homologacao_parcela  = ?', 'A')
               ->where('st_ativo                = ?', 'S')
               ->where('cp.cd_contrato          = ?', $cd_contrato, Zend_Db::INT_TYPE)
               ->where('obj.cd_contrato         = ?', $cd_contrato, Zend_Db::INT_TYPE);

        return $this->fetchAll($select)->toArray();
	}	

	public function getSumParcelasHomologadasNoMes($mes, $ano, $cd_contrato)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('par'=>$this->_name),
                      array('ni_horas_parcela'),
                      $this->_schema);
        
        $select->join(array('procpar'=>KT_S_PROCESSAMENTO_PARCELA),
                      '(par.cd_projeto = procpar.cd_projeto) AND (par.cd_proposta = procpar.cd_proposta) AND (par.cd_parcela = procpar.cd_parcela)',
                      array(),
                      $this->_schema);
        $select->join(array('prop'=>KT_S_PROPOSTA),
                      '(par.cd_projeto = prop.cd_projeto) AND (par.cd_proposta = prop.cd_proposta)',
                      'st_encerramento_proposta',
                      $this->_schema);
        $select->join(array('cp'=>KT_A_CONTRATO_PROJETO),
                      '(cp.cd_projeto = prop.cd_projeto)',
                      array(),
                      $this->_schema);
        $select->join(array('obj'=>KT_S_OBJETO_CONTRATO),
                      "(procpar.cd_objeto_execucao = obj.cd_objeto)",
                      array(),
                      $this->_schema);
        $select->where('ni_mes_execucao_parcela = ?', $mes, Zend_Db::INT_TYPE)
               ->where('ni_ano_execucao_parcela = ?', $ano, Zend_Db::INT_TYPE)
               ->where('st_homologacao_parcela  = ?', 'A')
               ->where('st_ativo                = ?', 'S')
               ->where('cp.cd_contrato          = ?', $cd_contrato, Zend_Db::INT_TYPE)
               ->where('obj.cd_contrato         = ?', $cd_contrato, Zend_Db::INT_TYPE);
        return $this->fetchAll($select)->toArray();
	}	
    
    
	public function getDadosParcelaUm($cd_projeto, $cd_proposta)
	{
		$objDadosParcelaUm = $this->fetchRow(array("cd_projeto  = ?" =>$cd_projeto,
                                                   "cd_proposta = ?" =>$cd_proposta,
                                                   "ni_parcela  = ?" =>1));
		
		return $objDadosParcelaUm->toArray();
	}
	
	public function getParcelasAutorizadasNaoFechadas($cd_projeto, $cd_proposta)
	{

        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('procpar'=>KT_S_PROCESSAMENTO_PARCELA),
                      array('*'),
                      $this->_schema);
        $select->join(array('par'=>$this->_name),
                      '(par.cd_projeto = procpar.cd_projeto) AND (par.cd_proposta = procpar.cd_proposta) AND (par.cd_parcela = procpar.cd_parcela)',
                      array(),
                      $this->_schema);
        $select->where('procpar.cd_projeto     = ?', $cd_projeto,  Zend_Db::INT_TYPE)
               ->where('procpar.cd_proposta    = ?', $cd_proposta, Zend_Db::INT_TYPE)
               ->where('st_ativo               = ?', 'S')
               ->where('st_autorizacao_parcela = ?', 'S')
               ->where('st_fechamento_parcela IS NULL')
               ->where('st_modulo_proposta IS NULL');

        return $this->fetchAll($select)->toArray();
	}

	public function getSomaHorasParcelasDeExecucaoDaProposta($cd_projeto, $cd_proposta)
	{
        $select = $this->select()->from($this,array('total_horas_parcelas' => new Zend_Db_Expr('SUM(ni_horas_parcela)')))
                                 ->where("cd_projeto  = ?", $cd_projeto, Zend_Db::INT_TYPE)
                                 ->where("cd_proposta = ?", $cd_proposta, Zend_Db::INT_TYPE)
                                 ->where("st_modulo_proposta IS NULL");

        return $this->fetchRow($select)->total_horas_parcelas;
	}

	public function getParcelasExecutadasProposta($cd_contrato, $cd_projeto, $cd_proposta, $mes_inicial, $ano_inicial, $mes_final, $ano_final)
	{
		$ano_mes_inicial = ($ano_inicial*100)+$mes_inicial;
		$ano_mes_final   = ($ano_final*100)+$mes_final;

		/*$sql = "SELECT SUM(ni_horas_parcela) AS ni_horas_parcela FROM oasis.s_parcela AS par
	  JOIN

	  (SELECT procpar.cd_objeto_execucao, parc.cd_parcela FROM oasis.s_processamento_parcela AS procpar
	 INNER JOIN oasis.s_objeto_contrato AS obj ON (procpar.cd_objeto_execucao = obj.cd_objeto)
	 INNER JOIN oasis.s_contrato AS cont ON (obj.cd_contrato = cont.cd_contrato)
	 INNER JOIN oasis.s_parcela AS parc ON (procpar.cd_parcela = parc.cd_parcela)
	 WHERE (parc.cd_projeto  = {$cd_projeto}) AND (parc.cd_proposta = {$cd_proposta})
	 AND (((ni_ano_previsao_parcela*100)+ni_mes_previsao_parcela
	 BETWEEN {$ano_mes_inicial} AND {$ano_mes_final}) or ni_mes_previsao_parcela is null)
	 AND (cont.cd_contrato = {$cd_contrato}) AND (procpar.st_ativo  = 'S') AND st_homologacao_parcela is not null) AS parcelas_executadas

	 ON (par.cd_parcela = parcelas_executadas.cd_parcela)";*/

	 $subSelect = $this->select()->setIntegrityCheck(false);
	 $subSelect->from(array('parc' => $this->_name), 'cd_parcela', $this->_schema);
	 $subSelect->joinInner(array('procpar' => KT_S_PROCESSAMENTO_PARCELA), '(procpar.cd_parcela = parc.cd_parcela)', 'cd_objeto_execucao', $this->_schema);
	 $subSelect->joinInner(array('obj'     => KT_S_OBJETO_CONTRATO), '(procpar.cd_objeto_execucao = obj.cd_objeto)', array(), $this->_schema);
	 $subSelect->joinInner(array('cont'    => KT_S_CONTRATO), '(obj.cd_contrato = cont.cd_contrato)', array(), $this->_schema);
	 $subSelect->where('parc.cd_projeto   = ?', $cd_projeto, Zend_Db::INT_TYPE);
	 $subSelect->where('parc.cd_proposta  = ?', $cd_proposta, Zend_Db::INT_TYPE);
	 $subSelect->where("((ni_ano_previsao_parcela*100)+ni_mes_previsao_parcela
						 BETWEEN {$ano_mes_inicial} AND {$ano_mes_final}) or ni_mes_previsao_parcela is null");
	 $subSelect->where('cont.cd_contrato  = ?', $cd_contrato, Zend_Db::INT_TYPE);
	 $subSelect->where('procpar.st_ativo  = ?', 'S');
	 $subSelect->where('st_homologacao_parcela is not null');

	 $select = $this->select()->setIntegrityCheck(false);
	 $select->from(array('par' => $this->_name), array('ni_horas_parcela' => new Zend_Db_Expr('SUM(ni_horas_parcela)')), $this->_schema);
     $select->join(array('parcelas_executadas' => $subSelect), '(par.cd_parcela = parcelas_executadas.cd_parcela)', $cols, $schema);

	 return $this->getDefaultAdapter()->fetchRow($select);
	}
}