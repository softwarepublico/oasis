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

class Proposta extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_PROPOSTA;
	protected $_primary  = array('cd_proposta', 'cd_projeto');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getDadosProjetoProposta($cd_projeto, $cd_proposta)
	{
		$select = $this->select()
                       ->where("cd_projeto  = ?", $cd_projeto, Zend_Db::INT_TYPE)
                       ->where("cd_proposta = ?", $cd_proposta, Zend_Db::INT_TYPE);
                       
		return $this->fetchAll($select)->toArray();
	}

	public function getNewValueByProjeto($cd_projeto)
	{
        $select = $this->select()
                       ->from($this,array('new_value'=>new Zend_Db_Expr('MAX(cd_proposta)')))
                       ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);

        $row = $this->fetchRow($select);
		$new = 0;
		if (is_null($row->new_value)) {
			$new = 1;
		} else {
			$new = $row->new_value +1;
		}
		return $new;
	}

	public function getProposta($cd_projeto = null,$comSelecione = true)
	{
		$arrProposta = array();
		if ($comSelecione === true) {
			$arrProposta[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}

		$select = $this->select();
		if (!is_null($cd_projeto)) {
			$select->where("cd_projeto = ?",$cd_projeto, Zend_Db::INT_TYPE);
		}
		$select->order("cd_proposta");

		$rowSet = $this->fetchAll($select);
		if($rowSet->valid()){
			foreach ($rowSet as $row) {
				$arrProposta[$row->cd_proposta] = Base_Util::getTranslator('L_VIEW_PROPOSTA_NR')." ". $row->cd_proposta;
			}
		}
		return $arrProposta;
	}


	public function getPropostaAberta($cd_contrato)
	{
		//seleciona as propostas ativas da tabela s_processamento_proposta
		//e faz um left join com a tabela de proposta
		//Se o campo st_fechamento_proposta estiver null é pq a proposta
		//está aberta, ou seja, ela nunca foi fechada ou foi fechada e
		//o processamento foi abandonado em alguma etapa (st_ativo = null)

        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('prop'=>$this->_name),
                      array('cd_projeto',
                            'cd_proposta',
                            'indicador' => new Zend_Db_Expr("prop.st_descricao{$this->concat()}prop.st_profissional{$this->concat()}prop.st_metrica{$this->concat()}prop.st_requisito{$this->concat()}prop.st_modulo{$this->concat()}prop.st_parcela{$this->concat()}prop.st_produto")),
                      $this->_schema);
        $objTableProcProp = new ProcessamentoProposta();
        $select->joinLeft(array('proposta_ativa'=>$objTableProcProp->select()
                                                                   ->from($objTableProcProp,
                                                                          array('cd_projeto',
                                                                                'cd_proposta',
                                                                                'st_fechamento_proposta'))
                                                                   ->where('st_ativo = ?', 'S')),
                          '(prop.cd_projeto  = proposta_ativa.cd_projeto) AND
                           (prop.cd_proposta = proposta_ativa.cd_proposta)',
                          'st_fechamento_proposta');
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(prop.cd_projeto = proj.cd_projeto)',
                      'tx_sigla_projeto',
                      $this->_schema);
        $objTableContProj = new ContratoProjeto();
        $select->join(array('cp'=>$objTableContProj->select()
                                                   ->from($objTableContProj,
                                                          'cd_projeto')
                                                   ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE)),
                      '(prop.cd_projeto = cp.cd_projeto)',
                      array());
        $select->where('st_fechamento_proposta is null');
        $select->order(new Zend_Db_Expr('upper(tx_sigla_projeto)'));

        return $this->fetchAll($select)->toArray();
	}

	public function getPorcentagemHorasProposta($cd_projeto, $ni_porcentagem_parc_orcamento)
	{
		$select = $this->select()
                       ->where("cd_projeto  = ?", $cd_projeto, Zend_Db::INT_TYPE)
                       ->where("cd_proposta = ?", 1, Zend_Db::INT_TYPE);

        $row = $this->fetchRow($select);
		$percentualProposta = (($row->ni_horas_proposta * $ni_porcentagem_parc_orcamento)/100);

		return $percentualProposta;
	}

	public function getHorasProjetoProposta($cd_projeto,$cd_proposta)
	{
		$select = $this->select()
                       ->where("cd_projeto  = ?" , $cd_projeto, Zend_Db::INT_TYPE)
                       ->where("cd_proposta = ?", $cd_proposta, Zend_Db::INT_TYPE);

		$rowSetHorasProposta  = $this->fetchAll($select);
		$quantidadeHorasTotal = 0;
        if($rowSetHorasProposta->valid()){
            foreach($rowSetHorasProposta as $row){
                $quantidadeHorasTotal += $row->ni_horas_proposta;
            }
        }
		return $quantidadeHorasTotal;
	}


	//*** FUNÇÃO PARA MONTAGEM DA TELA DE CONTROLE DE PROPOSTAS
	public function getControlePropostas($mes, $ano, $cd_contrato = null)	
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('procprop'=>KT_S_PROCESSAMENTO_PROPOSTA),
                      array('cd_projeto',
                            'cd_proposta',
                            'st_fechamento_proposta',
                            'st_parecer_tecnico_proposta',
                            'st_aceite_proposta',
                            'st_homologacao_proposta',
                            'st_alocacao_proposta',
                            'cd_processamento_proposta'),
                      $this->_schema);
        $select->join(array('prop'=>$this->_name),
                      '(procprop.cd_projeto  = prop.cd_projeto) AND
					   (procprop.cd_proposta = prop.cd_proposta)',
                      'ni_horas_proposta',
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(procprop.cd_projeto = proj.cd_projeto)',
                      'tx_sigla_projeto',
                      $this->_schema);
       $select->where('procprop.st_ativo = ?', 'S')
              ->where('procprop.st_fechamento_proposta = ?', 'S')
              ->where('prop.ni_mes_proposta = ?', $mes, Zend_Db::INT_TYPE)
              ->where('prop.ni_ano_proposta = ?', $ano, Zend_Db::INT_TYPE);
       $select->order(array(new Zend_Db_Expr('upper(proj.tx_sigla_projeto)'),
                            'procprop.cd_proposta'));

        if (!is_null($cd_contrato)){
			$objTableContProj = new ContratoProjeto();

            $select->join(array('cp'=>$objTableContProj->select()
                                                       ->from($objTableContProj,
                                                              'cd_projeto')
                                                       ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE)),
                          '(prop.cd_projeto = cp.cd_projeto)',
                          array());
		}

        return $this->fetchAll($select)->toArray();
	}
	//*** FIM    FUNÇÃO PARA MONTAGEM DA TELA DE CONTROLE DE PROPOSTAS

	public function getControlePropostasMesAnterior($mes, $ano, $cd_contrato = null)	
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('procprop'=>KT_S_PROCESSAMENTO_PROPOSTA),
                      array('cd_projeto',
                            'cd_proposta',
                            'st_fechamento_proposta',
                            'st_parecer_tecnico_proposta',
                            'st_aceite_proposta',
                            'st_homologacao_proposta',
                            'st_alocacao_proposta',
                            'cd_processamento_proposta'),
                      $this->_schema);
        $select->join(array('prop'=>$this->_name),
                      '(procprop.cd_projeto  = prop.cd_projeto) AND
					   (procprop.cd_proposta = prop.cd_proposta)',
                      'ni_horas_proposta',
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(procprop.cd_projeto = proj.cd_projeto)',
                      'tx_sigla_projeto',
                      $this->_schema);
        $objTableProcProp = new ProcessamentoProposta();
        $select->join(array('maxcd'=>$objTableProcProp->select()
                                                      ->from($objTableProcProp,
                                                             array('cd_projeto',
                                                                   'cd_proposta',
                                                                   'cd_processamento_proposta'=>new Zend_Db_Expr('max(cd_processamento_proposta)')))
                                                      ->where(new Zend_Db_Expr("{$this->to_char('dt_fechamento_proposta', 'MM/YYYY')} = '{$mes}/{$ano}'"))
                                                      ->group(array('cd_projeto',
                                                                    'cd_proposta'))),
                      '(procprop.cd_projeto  = maxcd.cd_projeto) AND
                       (procprop.cd_proposta = maxcd.cd_proposta) AND
                       (procprop.cd_processamento_proposta = maxcd.cd_processamento_proposta)',
                      array());
        $select->where('procprop.st_fechamento_proposta = ?', 'S');
        $select->order(array(new Zend_Db_Expr('upper(proj.tx_sigla_projeto)'),
                             'procprop.cd_proposta'));

		if (!is_null($cd_contrato)){
			$objTableContProj = new ContratoProjeto();

            $select->join(array('cp'=>$objTableContProj->select()
                                                       ->from($objTableContProj,
                                                              'cd_projeto')
                                                       ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE)),
                          '(prop.cd_projeto = cp.cd_projeto)',
                          array());
		}

        return $this->fetchAll($select)->toArray();
	}

	//*** Função para montagem da tela Execução de Proposta
	public function getPropostasEmExecucao($cd_objeto, $mes, $ano)
	{

		//Seleciona as propostas que possuem parcelas autorizadas
		//(st_autorizacao_parcela = 'S' e st_ativo = 'S' da tabela s_processamento_parcela)
		// no mês e ano indicados
		//(ni_mes_execucao_parcela e ni_ano_execucao_parcela da tabela s_parcela)
		//e com os dados das parcelas, busca os dados do projeto e da proposta

        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('procpar'=>KT_S_PROCESSAMENTO_PARCELA),
                      array('cd_projeto',
                            'cd_proposta',
                            'cd_parcela',
                            'st_fechamento_parcela'),
                      $this->_schema);
        $select->join(array('par'=>KT_S_PARCELA),
                      '(procpar.cd_projeto  = par.cd_projeto) AND
					   (procpar.cd_proposta = par.cd_proposta) AND
					   (procpar.cd_parcela  = par.cd_parcela)',
                      array('ni_parcela',
                            'ni_horas_parcela'),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(procpar.cd_projeto = proj.cd_projeto)',
                      array('tx_sigla_projeto',
                            'st_dicionario_dados',
                            'st_informacoes_tecnicas'),
                      $this->_schema);
        $select->join(array('prop'=>$this->_name),
                      '(procpar.cd_projeto  = prop.cd_projeto) AND
                       (procpar.cd_proposta = prop.cd_proposta)',
                      array('st_caso_de_uso'),
                      $this->_schema);
        $select->where('procpar.st_ativo = ?', 'S')
               ->where('procpar.st_autorizacao_parcela = ?', 'S')
               ->where('procpar.cd_objeto_execucao = ?', $cd_objeto, Zend_Db::INT_TYPE)
               ->where('par.ni_mes_execucao_parcela = ?', $mes, Zend_Db::INT_TYPE)
               ->where('par.ni_ano_execucao_parcela = ?', $ano, Zend_Db::INT_TYPE)
               ->where('par.st_modulo_proposta is null');
        $select->order(array(new Zend_Db_Expr('upper(proj.tx_sigla_projeto)'),
                             'procpar.cd_proposta',
                             'par.ni_parcela'));

        return $this->fetchAll($select)->toArray();
/*
		$sql = "select
					procpar.cd_projeto,
					procpar.cd_proposta,
					procpar.cd_parcela,
					procpar.st_fechamento_parcela,
					par.ni_parcela,
					par.ni_horas_parcela,
					proj.tx_sigla_projeto,
					proj.st_dicionario_dados,
					proj.st_informacoes_tecnicas,
					prop.st_caso_de_uso
				from
					{$this->_schema}.".KT_S_PROCESSAMENTO_PARCELA." as procpar
				join
					{$this->_schema}.".KT_S_PARCELA." as par
				on
					procpar.cd_projeto = par.cd_projeto
				and
					procpar.cd_proposta = par.cd_proposta
				and
					procpar.cd_parcela = par.cd_parcela
				join
					{$this->_schema}.".KT_S_PROJETO." as proj
				on
					procpar.cd_projeto = proj.cd_projeto
				join
					{$this->_schema}.".KT_S_PROPOSTA." as prop
				on
					procpar.cd_projeto = prop.cd_projeto
				and
					procpar.cd_proposta = prop.cd_proposta
				where
					procpar.st_ativo = 'S'
				and
					procpar.st_autorizacao_parcela = 'S'
				and
					procpar.cd_objeto_execucao = {$cd_objeto}
				and
					par.ni_mes_execucao_parcela = {$mes}
				and
					par.ni_ano_execucao_parcela = {$ano}
				and
					par.st_modulo_proposta is null
				order by
					upper(proj.tx_sigla_projeto),
					procpar.cd_proposta,
					par.ni_parcela";
        return $this->getAdapter()->fetchAll($sql);
*/

	}
	//*** FIM   Função para montagem da tela Execução de Proposta

	//função para listagem das propostas na tab Alteração de Propostas
	//Menu Controle
	public function getPropostasAlteracao($cd_projeto)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('prop'=>$this->_name),
                      array('cd_projeto',
                            'cd_proposta',
                            'ni_mes_proposta',
                            'ni_ano_proposta',
                            'ni_horas_proposta',
                            'ni_solicitacao',
                            'ni_ano_solicitacao',
                            'cd_objeto',
                            'st_encerramento_proposta'),
                      $this->_schema);

        $objTableProcProp = new ProcessamentoProposta();

        $select->joinLeft(array('proposta_ativa'=>$objTableProcProp->select()
                                                                   ->from($objTableProcProp,
                                                                          array('cd_projeto',
                                                                                'cd_proposta',
                                                                                'st_homologacao_proposta',
                                                                                'dt_fechamento_proposta'))
                                                                   ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                                                                   ->where('st_ativo = ? ', 'S')),
                          '(prop.cd_projeto  = proposta_ativa.cd_projeto) AND
                           (prop.cd_proposta = proposta_ativa.cd_proposta)',
                          array('st_homologacao_proposta',
                                'dt_fechamento_proposta'));
        $select->where('prop.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
        $select->order('cd_proposta');

        return $this->fetchAll($select)->toArray();
	}

	public function getPropostasEncerramento($cd_contrato)
	{
        $select = $this->select()->setIntegrityCheck(false);
        
        $select->from(array('prop'=>$this->_name),
                      array('cd_projeto',
                            'cd_proposta',
                            'ni_horas_proposta'),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(prop.cd_projeto = proj.cd_projeto)',
                      'tx_sigla_projeto',
                      $this->_schema);

        $objContratoProjeto = new ContratoProjeto();
        $select->join(array('cp'=>$objContratoProjeto->select()
                                                     ->from($objContratoProjeto, 'cd_projeto')
                                                     ->where('cd_contrato = ?',$cd_contrato, Zend_Db::INT_TYPE)),
                      '(cp.cd_projeto = prop.cd_projeto)',
                      array());
        $select->where('st_encerramento_proposta = ?', 'E');
        $select->order(array('tx_sigla_projeto','cd_proposta'));

		return $this->fetchAll($select)->toArray();
/*
		$sql = "select
					prop.cd_projeto,
					prop.cd_proposta,
					prop.ni_horas_proposta,
					proj.tx_sigla_projeto
				from
					{$this->_schema}.".KT_S_PROPOSTA." as prop
				join
					{$this->_schema}.".KT_S_PROJETO." as proj
				on
					prop.cd_projeto = proj.cd_projeto
				join
				(
					select
						cd_projeto
					from
						{$this->_schema}.".KT_A_CONTRATO_PROJETO."
					where
						cd_contrato = {$cd_contrato}
				) as cp
				on
					cp.cd_projeto = prop.cd_projeto
				where
					st_encerramento_proposta = 'E'
				order by
					tx_sigla_projeto,
					cd_proposta";
		return $this->getDefaultAdapter()->fetchAll($sql);
*/
	}

	public function atualizaProposta($cd_projeto, $cd_proposta, $addRow)
	{
		$erros = false;

		$where       = "cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta}";
		$rowProposta = $this->fetchRow($where)->toArray();

		if ($rowProposta){
			if (!$this->update($addRow, $where)){
				$erros = true;
			}
		}
		return $erros;
	}

	public function getPropostaAbertaHistorico($cd_projeto)
	{
        $select = $this->select()
                       ->from($this, array('cd_projeto','cd_proposta'))
                       ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                       ->where('st_encerramento_proposta is null')
                       ->order('cd_proposta');

		$rowSet = $this->fetchAll($select);

        $arrPropostaAberta = array();
		if($rowSet->valid()){
			foreach ($rowSet as  $row) {
				$arrPropostaAberta[$row->cd_proposta] = $row->cd_proposta;
			}
		}

		return $arrPropostaAberta;
	}

	public function getProjetosExecucaoSemEncerramentoProposta($comSelecione=false, $cd_contrato = null)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->distinct();

        $select->from(array('prop'=>$this->_name),
                      'cd_projeto',
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(prop.cd_projeto = proj.cd_projeto) AND
                       (prop.st_encerramento_proposta is null)',
                      'tx_sigla_projeto',
                      $this->_schema);
        $select->order('proj.tx_sigla_projeto');

		if( !is_null($cd_contrato)){
            $objContratoProjeto = new ContratoProjeto();
            $select->join(array('cp'=>$objContratoProjeto->select()
                                                         ->from($objContratoProjeto, 'cd_projeto')
                                                         ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE)),
                          '(cp.cd_projeto = prop.cd_projeto)',
                          array());
        }

		$arrRetorno = array();
		if($comSelecione === true){
			$arrRetorno[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}

		$rowSet = $this->fetchAll($select);

        if($rowSet->valid()){
            foreach($rowSet as $row){
                $arrRetorno[$row->cd_projeto] = $row->tx_sigla_projeto;
            }
        }
	    return $arrRetorno; 
	}

	public function getPropostasSuspensaoProposta($cd_contrato, $status = null)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('prop'=>$this->_name),
                      array('cd_projeto','cd_proposta'),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(prop.cd_projeto = proj.cd_projeto)',
                      'tx_sigla_projeto',
                      $this->_schema);
		$select->join(array('cp'=>KT_A_CONTRATO_PROJETO),
					  '(cp.cd_projeto = proj.cd_projeto)',
					  null,
                      $this->_schema);
				  
		$select->where('(cp.cd_contrato = ?)', $cd_contrato, Zend_Db::INT_TYPE);
		$select->where('(prop.st_encerramento_proposta is null)');

		if (is_null($status)) {
			$select->where('(prop.st_suspensao_proposta is null)');
		}else{
			$select->where('(prop.st_suspensao_proposta = ?)', 'S');
		}
		
        $select->order(array('proj.tx_sigla_projeto','prop.cd_proposta'));

		$arrRetorno = array();
		if($comSelecione === true){
			$arrRetorno[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}

		$rowSet = $this->fetchAll($select);

        if($rowSet->valid()){
            foreach($rowSet as $row){
                $arrRetorno["{$row->cd_projeto}_{$row->cd_proposta}"] = "{$row->tx_sigla_projeto} (Proposta Nº {$row->cd_proposta})";
            }
        }
	    return $arrRetorno;
	}

	public function getUltimaVersaoPropostaParaBaseline($cd_projeto)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('pr'=>$this->_name),
                      array('cd_projeto',
                            'cd_proposta'),
                      $this->_schema);

        $tableProcProp = new ProcessamentoProposta();
        $select->join(array('maxdata'=>$tableProcProp->select()
                                            ->from($tableProcProp,
                                                   array('cd_projeto',
                                                         'cd_proposta',
                                                         'dt_fechamento_proposta'=>new Zend_Db_Expr('max(dt_fechamento_proposta)')),
                                                   $this->_schema)
                                            ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                                            ->group(array('cd_projeto',
                                                          'cd_proposta'))),
                      '(maxdata.cd_projeto  = pr.cd_projeto AND
                        maxdata.cd_proposta = pr.cd_proposta)',
                      'dt_fechamento_proposta');
        $select->where('pr.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);

        return $this->fetchAll($select)->toArray();
	}
}