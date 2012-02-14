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

class SolicitacaoServiceDesk extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_SOLICITACAO;
	protected $_primary  = array('ni_solicitacao', 'ni_ano_solicitacao', 'cd_objeto');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    /**
     * Metodo para retornar o número da próxima solicitação de serviço
     * 
     * @param Integer $cd_objeto codigo do objeto
     * @param Integer $ni_ano_solicitacao ano da solicitação
     * @return Integer
     */
	public function getNewValueByObjeto($cd_objeto, $ni_ano_solicitacao) 
	{
		$select = $this->select()
                       ->from($this,
                              array('new_value' => new Zend_Db_Expr('MAX(ni_solicitacao)')))
                       ->where('ni_ano_solicitacao = ?', $ni_ano_solicitacao, Zend_Db::INT_TYPE)
                       ->where('cd_objeto          = ?', $cd_objeto, Zend_Db::INT_TYPE);

        $res = $this->fetchRow($select)->toArray();

		$new = 0;
		if (is_null($res['new_value'])) {
			$new = 1;
		} else {
			$new = $res['new_value'] + 1;
		}
		return $new;
	}
	
	public function getTipoSolicitacao($cd_objeto, $mostraParcela = false)
	{
                
		$arr            = array();
		
		if ($cd_objeto > -1) {
			$objetoContrato = new ObjetoContrato();
			$objeto = $objetoContrato->fetchRow("cd_objeto = {$cd_objeto}");

			switch ($objeto->st_objeto_contrato) {
				case 'P':
					$arr[1] = Base_Util::getTranslator('L_SQL_SOLICITACAO_PROPOSTA');

					// Só é acrescentada em alguns casos no select
					if ($mostraParcela == true) {
						$arr[2] = Base_Util::getTranslator('L_SQL_EXECUCAO_PROPOSTA');
					}

				break;
				case 'D':
					$arr[3] = Base_Util::getTranslator('L_SQL_DEMANDA');
					$arr[6] = Base_Util::getTranslator('L_SQL_SERVICE_DESK');
				break;
				// Somente para compor
				default:
				break;
			}
		}
		return $arr;
	}


//    public function getSolicitacaoParaAutoComplete($cd_objeto, $ni_ano_solicitacao)
//	{
//        
//        $select = $this->select()->setIntegrityCheck(false);
//
//        $select->from(array('sol'=>$this->_name),
//                      array('ni_solicitacao',
//                            'ni_ano_solicitacao'),
//                      $this->_schema);
//        
//         $select->where('sol.ni_ano_solicitacao = ?', $ni_ano_solicitacao, Zend_Db::INT_TYPE)
//                 ->where('sol.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE)
//                 ->where('dt_leitura_solicitacao is not null');
//        
//        return $this->fetchAll($select);
//        
////        Select ni_solicitacao, ni_ano_solicitacao 
////          from oasis.s_solicitacao 
////          where ni_ano_solicitacao = $ni_ano_solicitacao 
////            and cd_objeto = $cd_objeto 
////            and dt_leitura_solicitacao is not null
//    }    
//
    /**
     * Pega solicitações lidas para autocomplete
     * 
     * @param integer $cd_objeto
     * @param integer $ni_ano_solicitacao
     * @return Zend_Db_Table_Rowset 
     */
    public function getSolicitacaoParaAutoComplete($cd_objeto, $ni_ano_solicitacao)
	{
        $select = $this->select();
        $select->from($this,array('ni_solicitacao','ni_ano_solicitacao'));
        
         $select->where('ni_ano_solicitacao = ?', $ni_ano_solicitacao, Zend_Db::INT_TYPE)
                 ->where('cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE)
                 ->where('dt_leitura_solicitacao is not null');
        
        return $this->fetchAll($select);
    }    

    
	public function getSolicitacaoServiceDesk($cd_objeto, $ni_solicitacao, $ni_ano_solicitacao)
	{
		//*** Seleciona a solicitação de serviço
		//que possui a chave indicada no parâmetro
        
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('sol'=>$this->_name),
                      array('*',
                          'st_solicitacao'=> new Zend_Db_Expr("CASE st_solicitacao
                                                                        WHEN '1' THEN '".Base_Util::getTranslator('L_SQL_SOLICITACAO_PROPOSTA')."'
                                                                        WHEN '2' THEN '".Base_Util::getTranslator('L_SQL_EXECUCAO_PROPOSTA')."'
                                                                        WHEN '3' THEN '".Base_Util::getTranslator('L_SQL_DEMANDA')."'
                                                                        ELSE '".Base_Util::getTranslator('L_SQL_INDEFINIDO')."' END"),

                            'tipo_solicitacao'=>'st_solicitacao',
                            'ni_prazo_atendimento'),
                      $this->_schema);
        $select->join(array('obj'=>KT_S_OBJETO_CONTRATO),
                      '(sol.cd_objeto = obj.cd_objeto)',
                      'tx_objeto',
                      $this->_schema);
        $select->join(array('uni'=>KT_B_UNIDADE),
                      '(sol.cd_unidade = uni.cd_unidade)',
                      'tx_sigla_unidade',
                      $this->_schema);
        $select->where('sol.cd_objeto      = ?', $cd_objeto         , Zend_Db::INT_TYPE);
        $select->where('ni_solicitacao     = ?', $ni_solicitacao    , Zend_Db::INT_TYPE);
        $select->where('ni_ano_solicitacao = ?', $ni_ano_solicitacao, Zend_Db::INT_TYPE);

        $row = $this->fetchRow($select);

        $row->dt_solicitacao = date('d/m/Y H:i:s',strtotime($row->dt_solicitacao));

		return $row->toArray();

/*
        $sql = "select
					sol.*,
					tx_objeto,
					to_char(dt_solicitacao, 'DD/MM/YYYY') as dt_solicitacao,
					case st_solicitacao
						when '1' then 'Solicitação de Proposta'
						when '2' then 'Execução de Proposta'
						when '3' then 'Demanda'
						else 'Indefinido'
						end as st_solicitacao,
					tx_sigla_unidade,
					ni_prazo_atendimento,
					st_solicitacao as tipo_solicitacao
				from
					{$this->_schema}.".KT_S_SOLICITACAO." as sol
				join
					{$this->_schema}.".KT_S_OBJETO_CONTRATO." as obj
				on
					sol.cd_objeto = obj.cd_objeto
				join
					{$this->_schema}.".KT_B_UNIDADE." as uni
				on
					sol.cd_unidade = uni.cd_unidade
				where
					sol.cd_objeto = {$cd_objeto}
				and
					ni_solicitacao = {$ni_solicitacao}
				and
					ni_ano_solicitacao = {$ni_ano_solicitacao}";


        $res = $this->getAdapter()->fetchRow($sql);
*/
	}
	
	public function getSolicitacaoServiceDeskGerenteProposta($cd_objeto, $mes, $ano)
	{
                
		//*** Seleciona as solicitações de serviço
		//enviadas para os gerentes de projeto do tipo
		//solicitação de proposta e execução de proposta

        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('sol'=>$this->_name),
                      array('solicitacao'=>new Zend_Db_Expr("sol.ni_solicitacao{$this->concat()}'/'{$this->concat()}sol.ni_ano_solicitacao"),
                          'st_solicitacao',
                          'tx_solicitacao',
                          'dt_solicitacao',
                          'dt_leitura_solicitacao',
                          'ni_solicitacao',
                          'ni_ano_solicitacao',
                          'cd_objeto',
                          'tx_justificativa_solicitacao'),
                      $this->_schema);
        
        $select->join(array('uni'=>KT_B_UNIDADE),
                      '(sol.cd_unidade = uni.cd_unidade)',
                      'tx_sigla_unidade',
                      $this->_schema);

        $select->joinLeft(array('prop'=>KT_S_PROPOSTA),
                          '(sol.cd_objeto          = prop.cd_objeto) AND
                           (sol.ni_solicitacao     = prop.ni_solicitacao) AND
                           (sol.ni_ano_solicitacao = prop.ni_ano_solicitacao)',
                          array('tem_proposta'=>'ni_solicitacao'),
                          $this->_schema);

        $select->where('sol.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE)
               ->where(new Zend_Db_Expr("{$this->to_char('dt_solicitacao','YYYY')} = ?"), $ano)
               ->where(new Zend_Db_Expr("{$this->to_char('dt_solicitacao','MM')}   = ?"), $mes)
               ->where('sol.st_solicitacao in (?)', array('1','2'));

        $select->order('sol.dt_solicitacao DESC');
        /*
            Comentado para filtra por data da solicitacao
            $select->order('sol.ni_ano_solicitacao desc');
            $select->order('sol.ni_solicitacao desc');
		*/
        return $this->fetchAll($select);

//		$sql = "SELECT
//					sol.ni_solicitacao||'/'||sol.ni_ano_solicitacao as solicitacao,
//					st_solicitacao,
//					tx_sigla_unidade,
//					tx_solicitacao,
//					dt_solicitacao,
//					dt_leitura_solicitacao,
//					sol.ni_solicitacao,
//					sol.ni_ano_solicitacao,
//					prop.ni_solicitacao as tem_proposta,
//					sol.cd_objeto,
//					tx_justificativa_solicitacao
//				FROM
//					{$this->_schema}.".KT_S_SOLICITACAO." as sol
//				JOIN
//					{$this->_schema}.".KT_B_UNIDADE." as uni
//				ON
//					sol.cd_unidade = uni.cd_unidade
//				LEFT JOIN
//					{$this->_schema}.".KT_S_PROPOSTA." as prop
//				ON
//					sol.cd_objeto = prop.cd_objeto
//				AND
//					sol.ni_solicitacao = prop.ni_solicitacao
//				AND
//					sol.ni_ano_solicitacao = prop.ni_ano_solicitacao
//				WHERE
//					sol.cd_objeto = {$cd_objeto}
//				AND
//					to_char(dt_solicitacao,'YYYY') = '{$ano}'
//				AND
//					to_char(dt_solicitacao,'MM') = '{$mes}'
//				AND
//					sol.st_solicitacao in ('1', '2')
//				ORDER BY
//					sol.dt_solicitacao DESC";
//					/*
//					 * Comentado para filtra por data da solicitacao
//					 * sol.ni_ano_solicitacao desc,
//					 * sol.ni_solicitacao desc
//					 */
//		return $this->getAdapter()->fetchAll($sql);
	}
	
	public function getSolicitacaoServiceDeskTipoDemanda($mes, $ano, $cd_objeto, $cd_profissional=null, $isObject=false)
	{
        
        
		//*** Seleciona as solicitações de serviço
		//enviadas para os gerentes de projeto do tipo DEMANDA

        $select = $this->select()
                       ->distinct()
                       ->setIntegrityCheck(false);

        $select->from(array('sol'=>$this->_name),
                      array('solicitacao'=>new Zend_Db_Expr("sol.ni_solicitacao{$this->concat()}'/'{$this->concat()}sol.ni_ano_solicitacao"),
                            'st_solicitacao',
                            'cd_unidade',
                            'tx_solicitacao',
                            'dt_solicitacao',
                            'dt_leitura_solicitacao',
                            'ni_solicitacao',
                            'ni_ano_solicitacao',
                            'cd_objeto',
                            'tx_justificativa_solicitacao'),
                      $this->_schema);
        $select->join(array('uni'=>KT_B_UNIDADE),
                      'sol.cd_unidade = uni.cd_unidade',
                      array('tx_sigla_unidade'),
                      $this->_schema);

        $select->joinLeft(array('dem'=>KT_S_DEMANDA),
                          'sol.cd_objeto          = dem.cd_objeto       AND
                           sol.ni_solicitacao     = dem.ni_solicitacao  AND
                           sol.ni_ano_solicitacao = dem.ni_ano_solicitacao',
                          array('tem_demanda'=>'ni_solicitacao',
                                'cd_demanda'),
                          $this->_schema);

        $select->joinLeft(array('sa'=>KT_B_STATUS_ATENDIMENTO),
                          'dem.cd_status_atendimento = sa.cd_status_atendimento',
                          array('tx_rgb_status_atendimento',
                                'tx_status_atendimento'),
                          $this->_schema);

        $select->joinLeft(array('dp'=>KT_A_DEMANDA_PROFISSIONAL),
                          'dem.cd_demanda = dp.cd_demanda',
                          array(),
                          $this->_schema);

        $select->where(new Zend_Db_Expr("{$this->to_char('dt_solicitacao', 'YYYY')} = '{$ano}'"))
               ->where(new Zend_Db_Expr("{$this->to_char('dt_solicitacao','MM')} = '{$mes}'"))
               ->where("sol.st_solicitacao in ('3','6')")
               ->where("dem.st_fechamento_demanda is null")
               ->where("dem.st_conclusao_demanda is null")
               ->where("sol.cd_objeto = ?", $cd_objeto, Zend_Db::INT_TYPE)
               ->where("sol.st_fechamento is null");

        if ( (!is_null($cd_profissional)) && (!empty($cd_profissional)) ){
            $select->where('dp.cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);
		}

        $select->order("sol.ni_ano_solicitacao desc")
               ->order("sol.ni_solicitacao desc");

       $retorno = $this->fetchAll($select);
        if($isObject === false){
            $retorno = $retorno->toArray();
        }

        return $retorno;


//		$and = "";
//		if (!is_null($cd_profissional)){
//			$and = " and dp.cd_profissional = {$cd_profissional}";
//		}
//
//		$sql = "SELECT
//					distinct
//					sol.ni_solicitacao||'/'||sol.ni_ano_solicitacao as solicitacao,
//					st_solicitacao,
//					sol.cd_unidade,
//					tx_sigla_unidade,
//					tx_solicitacao,
//					dt_solicitacao,
//					dt_leitura_solicitacao,
//					sol.ni_solicitacao,
//					sol.ni_ano_solicitacao,
//					dem.ni_solicitacao as tem_demanda,
//					sol.cd_objeto,
//					tx_justificativa_solicitacao,
//					dem.cd_demanda
//				FROM
//					{$this->_schema}.".KT_S_SOLICITACAO." as sol
//				JOIN
//					{$this->_schema}.".KT_B_UNIDADE." as uni
//				ON
//					sol.cd_unidade = uni.cd_unidade
//				LEFT JOIN
//					{$this->_schema}.".KT_S_DEMANDA." as dem
//				ON
//					sol.cd_objeto = dem.cd_objeto
//				AND
//					sol.ni_solicitacao = dem.ni_solicitacao
//				AND
//					sol.ni_ano_solicitacao = dem.ni_ano_solicitacao
//
//				LEFT JOIN
//					{$this->_schema}.".KT_A_DEMANDA_PROFISSIONAL." as dp
//				ON
//					dem.cd_demanda = dp.cd_demanda
//				WHERE
//					to_char(dt_solicitacao,'YYYY') = '{$ano}'
//				AND
//					to_char(dt_solicitacao,'MM') = '{$mes}'
//				AND
//					sol.st_solicitacao in ('3')
//				AND
//					dem.st_fechamento_demanda is null
//				AND
//					dem.st_conclusao_demanda is null
//				AND
//					sol.cd_objeto = {$cd_objeto}
//				AND
//					sol.st_fechamento is null
//				{$and}
//				ORDER BY
//					sol.ni_ano_solicitacao desc,
//					sol.ni_solicitacao desc";
//
//		return $this->getAdapter()->fetchAll($sql);
	}
	
	public function getSolicitacaoServiceDeskTipoDemandaExecutada($mes, $ano, $cd_objeto, $cd_profissional=null, $isObject=false)
	{
        
		/**
		 * Solicitações do Tipo Demanda cujas demandas originadas foram executadas
         *e esperam novo reencaminhamento ou a conclusão de execução
		 */
        $select = $this->select()
                       ->distinct()
                       ->setIntegrityCheck(false);

        $select->from(array('sol'=>$this->_name),
                      array('solicitacao'=>new Zend_Db_Expr("sol.ni_solicitacao{$this->concat()}'/'{$this->concat()}sol.ni_ano_solicitacao"),
                            'st_solicitacao',
                            'cd_unidade',
                            'tx_solicitacao',
                            'dt_solicitacao',
                            'dt_leitura_solicitacao',
                            'ni_solicitacao',
                            'ni_ano_solicitacao',
                            'cd_objeto',
                            'tx_justificativa_solicitacao'),
                      $this->_schema);

        $select->join(array('uni'=>KT_B_UNIDADE),
                      'sol.cd_unidade = uni.cd_unidade',
                      array('tx_sigla_unidade'),
                      $this->_schema);

        $select->joinLeft(array('dem'=>KT_S_DEMANDA),
                          'sol.cd_objeto          = dem.cd_objeto       AND
                           sol.ni_solicitacao     = dem.ni_solicitacao  AND
                           sol.ni_ano_solicitacao = dem.ni_ano_solicitacao',
                          array('tem_demanda'=>'ni_solicitacao',
                                'cd_demanda'),
                          $this->_schema);

        $select->joinLeft(array('pre'=>KT_S_PRE_DEMANDA),
                          'sol.cd_objeto            = pre.cd_objeto_receptor AND
                           sol.ni_solicitacao       = pre.ni_solicitacao     AND
                           sol.ni_ano_solicitacao   = pre.ni_ano_solicitacao',
                          array('tem_pre_demanda'=>'cd_pre_demanda',
                                'st_reabertura_pre_demanda'),
                          $this->_schema);

        $select->joinLeft(array('dp'=>KT_A_DEMANDA_PROFISSIONAL),
                          'dem.cd_demanda = dp.cd_demanda',
                          array(),
                          $this->_schema);

        $select->where(new Zend_Db_Expr("{$this->to_char('dt_solicitacao','YYYY')} = '{$ano}'"))
               ->where(new Zend_Db_Expr("{$this->to_char('dt_solicitacao','MM')} = '{$mes}'"))
               ->where("sol.st_solicitacao in ('3','6')")
               ->where("dem.st_fechamento_demanda is not null")
               ->where("dem.st_conclusao_demanda is null")
               ->where("sol.cd_objeto = ?", $cd_objeto, Zend_Db::INT_TYPE)
               ->where("dem.cd_demanda is not null");

        if ( (!is_null($cd_profissional)) && (!empty($cd_profissional)) ){
            $select->where('dp.cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);
		}

        $select->order("sol.ni_ano_solicitacao desc")
               ->order("sol.ni_solicitacao desc");

       $retorno = $this->fetchAll($select);
        if($isObject === false){
            $retorno = $retorno->toArray();
        }

        return $retorno;

//		$and = "";
//		if (!is_null($cd_profissional)){
//			$and = " and dp.cd_profissional = {$cd_profissional}";
//		}
//
//		$sql = "SELECT
//					distinct
//					sol.ni_solicitacao||'/'||sol.ni_ano_solicitacao as solicitacao,
//					st_solicitacao,
//					sol.cd_unidade,
//					tx_sigla_unidade,
//					tx_solicitacao,
//					dt_solicitacao,
//					dt_leitura_solicitacao,
//					sol.ni_solicitacao,
//					sol.ni_ano_solicitacao,
//					dem.ni_solicitacao as tem_demanda,
//					sol.cd_objeto,
//					tx_justificativa_solicitacao,
//					cd_pre_demanda as tem_pre_demanda,
//					st_reabertura_pre_demanda,
//					dem.cd_demanda
//				FROM
//					{$this->_schema}.".KT_S_SOLICITACAO." as sol
//				JOIN
//					{$this->_schema}.".KT_B_UNIDADE." as uni
//				ON
//					sol.cd_unidade = uni.cd_unidade
//				LEFT JOIN
//					{$this->_schema}.".KT_S_DEMANDA." as dem
//				ON
//					sol.cd_objeto = dem.cd_objeto
//				AND
//					sol.ni_solicitacao = dem.ni_solicitacao
//				AND
//					sol.ni_ano_solicitacao = dem.ni_ano_solicitacao
//				LEFT JOIN
//					{$this->_schema}.".KT_S_PRE_DEMANDA." as pre
//				ON
//					sol.cd_objeto = pre.cd_objeto_receptor
//				AND
//					sol.ni_solicitacao = pre.ni_solicitacao
//				AND
//					sol.ni_ano_solicitacao = pre.ni_ano_solicitacao
//				LEFT JOIN
//					{$this->_schema}.".KT_A_DEMANDA_PROFISSIONAL." as dp
//				ON
//					dem.cd_demanda = dp.cd_demanda
//
//				WHERE
//					to_char(dt_solicitacao,'YYYY') = '{$ano}'
//				AND
//					to_char(dt_solicitacao,'MM') = '{$mes}'
//				AND
//					sol.st_solicitacao in ('3')
//				AND
//					dem.st_fechamento_demanda is not null
//				AND
//					dem.st_conclusao_demanda is null
//				AND
//					sol.cd_objeto = {$cd_objeto}
//				AND
//					sol.st_fechamento is null
//				AND
//					dem.cd_demanda is not null
//				{$and}
//				ORDER BY
//					sol.ni_ano_solicitacao desc,
//					sol.ni_solicitacao desc";
//
//		return $this->getAdapter()->fetchAll($sql);
	}	
	
	public function getSolicitacaoServiceDeskTipoDemandaConcluida($mes, $ano, $cd_objeto, $cd_profissional=null, $isObject=false)
	{
        

		//*** Seleciona as solicitações de serviço
		//enviadas para os gerentes de projeto do tipo DEMANDA

        $select = $this->select()
                       ->distinct()
                       ->setIntegrityCheck(false);

        $select->from(array('sol'=>$this->_name),
                      array('solicitacao'=>new Zend_Db_Expr("sol.ni_solicitacao{$this->concat()}'/'{$this->concat()}sol.ni_ano_solicitacao"),
                            'st_solicitacao',
                            'cd_unidade',
                            'tx_solicitacao',
                            'dt_solicitacao',
                            'dt_leitura_solicitacao',
                            'ni_solicitacao',
                            'ni_ano_solicitacao',
                            'cd_objeto',
                            'tx_justificativa_solicitacao'),
                      $this->_schema);

        $select->join(array('uni'=>KT_B_UNIDADE),
                      'sol.cd_unidade = uni.cd_unidade',
                      array('tx_sigla_unidade'),
                      $this->_schema);

        $select->joinLeft(array('dem'=>KT_S_DEMANDA),
                          'sol.cd_objeto          = dem.cd_objeto       AND
                           sol.ni_solicitacao     = dem.ni_solicitacao  AND
                           sol.ni_ano_solicitacao = dem.ni_ano_solicitacao',
                          array('tem_demanda'=>'ni_solicitacao',
                                'cd_demanda'),
                          $this->_schema);

        $select->joinLeft(array('dp'=>KT_A_DEMANDA_PROFISSIONAL),
                          'dem.cd_demanda = dp.cd_demanda',
                          array(),
                          $this->_schema);

        $select->where(new Zend_Db_Expr("{$this->to_char('dt_solicitacao','YYYY')} = '{$ano}'"))
               ->where(new Zend_Db_Expr("{$this->to_char('dt_solicitacao','MM')} = '{$mes}'"))
               ->where("sol.st_solicitacao in ('3','6')")
               ->where("dem.st_fechamento_demanda is not null")
               ->where("dem.st_conclusao_demanda is not null")
               ->where("sol.cd_objeto = ?", $cd_objeto, Zend_Db::INT_TYPE)
               ->where("sol.st_fechamento is not null");

        if ( (!is_null($cd_profissional)) && (!empty($cd_profissional)) ){
            $select->where('dp.cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);
		}

        $select->order("sol.ni_ano_solicitacao desc")
               ->order("sol.ni_solicitacao desc");

       $retorno = $this->fetchAll($select);
        if($isObject === false){
            $retorno = $retorno->toArray();
        }
        return $retorno;
        
//		$and = "";
//		if (!is_null($cd_profissional)){
//			$and = " and dp.cd_profissional = {$cd_profissional}";
//		}
//
//		$sql = "SELECT
//					distinct
//					sol.ni_solicitacao||'/'||sol.ni_ano_solicitacao as solicitacao,
//					st_solicitacao,
//					sol.cd_unidade,
//					tx_sigla_unidade,
//					tx_solicitacao,
//					dt_solicitacao,
//					dt_leitura_solicitacao,
//					sol.ni_solicitacao,
//					sol.ni_ano_solicitacao,
//					dem.ni_solicitacao as tem_demanda,
//					dem.cd_demanda,
//					sol.cd_objeto,
//					tx_justificativa_solicitacao
//				FROM
//					{$this->_schema}.".KT_S_SOLICITACAO." as sol
//				JOIN
//					{$this->_schema}.".KT_B_UNIDADE." as uni
//				ON
//					sol.cd_unidade = uni.cd_unidade
//				LEFT JOIN
//					{$this->_schema}.".KT_S_DEMANDA." as dem
//				ON
//					sol.cd_objeto = dem.cd_objeto
//				AND
//					sol.ni_solicitacao = dem.ni_solicitacao
//				AND
//					sol.ni_ano_solicitacao = dem.ni_ano_solicitacao
//
//				LEFT JOIN
//					{$this->_schema}.".KT_A_DEMANDA_PROFISSIONAL." as dp
//				ON
//					dem.cd_demanda = dp.cd_demanda
//
//				WHERE
//					to_char(dt_solicitacao,'YYYY') = '{$ano}'
//				AND
//					to_char(dt_solicitacao,'MM') = '{$mes}'
//				AND
//					sol.st_solicitacao in ('3')
//				AND
//					dem.st_fechamento_demanda is not null
//				AND
//					dem.st_conclusao_demanda is not null
//				AND
//					sol.cd_objeto = {$cd_objeto}
//				AND
//					sol.st_fechamento is not null
//				{$and}
//				ORDER BY
//					sol.ni_ano_solicitacao desc,
//					sol.ni_solicitacao desc";
//
//		return $this->getAdapter()->fetchAll($sql);
	}	
		
	public function getSolicitacaoServiceDeskCoordenacao($mes, $ano, $cd_objeto = null, $st_solicitacao = null)
	{
        
		//*** Seleciona todas solicitações de serviço
		//enviadas pela coordenação
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('sol'=>$this->_name),
                      array('solicitacao'     => new Zend_Db_Expr("sol.ni_solicitacao{$this->concat()}'/'{$this->concat()}sol.ni_ano_solicitacao"),
                            'st_solicitacao',
                            'tipo_solicitacao'=> new Zend_Db_Expr("CASE st_solicitacao
                                                                        WHEN '1' THEN '".Base_Util::getTranslator('L_SQL_SOLICITACAO_PROPOSTA')."'
                                                                        WHEN '2' THEN '".Base_Util::getTranslator('L_SQL_EXECUCAO_PROPOSTA')."'
                                                                        WHEN '3' THEN '".Base_Util::getTranslator('L_SQL_DEMANDA')."'
                                                                        ELSE '".Base_Util::getTranslator('L_SQL_INDEFINIDO')."' END"),
                            'tx_solicitacao',
                            'dt_solicitacao',
                            'cd_objeto',
                            'ni_solicitacao',
                            'ni_ano_solicitacao',
                            'st_fechamento',
                            'st_aceite',
                            'st_homologacao',
                            'dt_grau_satisfacao',  
							'dt_leitura_solicitacao',
                            'dt_justificativa',
                            'st_aceite_just_solicitacao'),
                      $this->_schema);
        $select->join(array('uni'=>KT_B_UNIDADE),
                      '(sol.cd_unidade = uni.cd_unidade)',
                      'tx_sigla_unidade',
                      $this->_schema);
         $select->join(array('obj'=>KT_S_OBJETO_CONTRATO),
                      '(sol.cd_objeto = obj.cd_objeto)',
                      'tx_objeto',
                      $this->_schema);
        $select->where(new Zend_Db_Expr("{$this->to_char('dt_solicitacao','YYYY')} = ?"), $ano)
               ->where(new Zend_Db_Expr("{$this->to_char('dt_solicitacao','MM')}   = ?"), $mes);

        $select->order('sol.dt_solicitacao DESC');

        /*comentado pois o filtro e por data da solicitação
            $select->order('sol.ni_ano_solicitacao DESC');
            $select->order('sol.ni_solicitacao DESC');
		*/

		if (!is_null($cd_objeto)){
            $select->where('sol.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);
		}

		if (!is_null($st_solicitacao)){
            $select->where('st_solicitacao = ?', $st_solicitacao);
		}

        return $this->fetchAll($select);
/*
		$sql = "SELECT
					sol.ni_solicitacao||'/'||sol.ni_ano_solicitacao as solicitacao,
					st_solicitacao,
					case st_solicitacao
						when '1' then 'Solicitação de Proposta'
						when '2' then 'Execução de Proposta'
						when '3' then 'Demanda'
						else 'Indefinido'
						end as tipo_solicitacao,
					tx_sigla_unidade,
					tx_solicitacao,
					cd_objeto,
					ni_solicitacao,
					ni_ano_solicitacao,
					st_fechamento,
					st_aceite,
					st_homologacao
				FROM
					{$this->_schema}.".KT_S_SOLICITACAO." as sol
				JOIN
					{$this->_schema}.".KT_B_UNIDADE." as uni
				ON
					sol.cd_unidade = uni.cd_unidade	";
		
		$where = "WHERE
					to_char(dt_solicitacao,'YYYY') = '{$ano}'
				  AND
					to_char(dt_solicitacao,'MM') = '{$mes}'	";
		
		if (!is_null($cd_objeto)){
			$where .= "AND sol.cd_objeto = {$cd_objeto}	";
		}
				
		if (!is_null($st_solicitacao)){
			$where .= "AND st_solicitacao = '{$st_solicitacao}'	";
		}

		$sql .= $where."ORDER BY
							sol.dt_solicitacao DESC";
							
							//comentado pois o filtro e por data da solicitação
							//sol.ni_ano_solicitacao desc,
							//sol.ni_solicitacao desc
		return $this->getAdapter()->fetchAll($sql);
*/
	}
	
	public function atualizaSolicitacaoServiceDesk($cd_objeto, $ni_solicitacao, $ni_ano_solicitacao, $arrUpdate)
	{
		$erros = false;
		$where = "cd_objeto = {$cd_objeto} and ni_solicitacao = {$ni_solicitacao} and ni_ano_solicitacao = {$ni_ano_solicitacao}";
		$rowSolicitacao = $this->fetchRow($where);
		
		if (!is_null($rowSolicitacao)){
			if (!$this->update($arrUpdate, $where)){
				$erros = true;
			}
		}
		return $erros;
	}

   /**
     * Método utilizado para criar uma nova solicitação de serviço.
     *
     * OBS: Este método somente pode ser usado quando sua chamada for feita dentro de um 'try catch' com transação.
     *      As KEYS do arrInsert deve ser iguais aos campos da tabela.
     * 
     * @param array $arrInsert
     * @THROWS Base_Exception_Alert ou Base_Exception_Error
     */
    public function criaNovaSolicitacaoServicoServiceDesk(Array $arrInsert=array())
    {

        if(count($arrInsert)==0){
           throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_SEM_INFO_CRIACAO_SOLICITACAO_SERVICO'));
       }

       $row = $this->createRow($arrInsert);

       if(!$row->save()){
           throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_CRIAR_SOLICITACAO_SERVICO'));
       }
    }
	
    /**
     * Metodo para buscar a quantidade de solicitações conforme o seu status de não lida,
     * em execucao e executada.
     */
	public function getQtdSolicitacoesServiceDesk($cd_objeto, $st_status_solicitacao, $ni_ano_solicitacao)
	{
        $select = $this->select()
                       ->from($this,new Zend_Db_Expr("count(*)"));

        $select->where('cd_objeto          = ?', $cd_objeto         , Zend_Db::INT_TYPE)
               ->where('ni_ano_solicitacao = ?', $ni_ano_solicitacao, Zend_Db::INT_TYPE);

		if ($st_status_solicitacao == 'E'){
            $select->where('st_fechamento is null')
                   ->where('dt_leitura_solicitacao is not null');
		}

		if ($st_status_solicitacao == 'L'){
            $select->where('dt_leitura_solicitacao is null');
		}

		if ($st_status_solicitacao == 'C'){
            $select->where('st_fechamento = ?', 'S');
		}

        return $this->fetchRow($select);
	}

    /**
     *
     * @param Integer $cd_objeto
     * @param Integer $cd_unidade
     * @param String  $tx_solicitante
     * @param String  $dt_inicio         formato YYYY-MM-DD
     * @param String  $dt_fim            formato YYYY-MM-DD
     * @param Integer $cd_profissional
     * @param String  $tx_demanda
     * @param String  $tipo_consulta
     * @param Integer $ni_solicitacao
     * @param Integer $ni_ano_solicitacao
     * @return Array
     */
	public function getSolicitacaoServiceDeskTipoDemandaConsulta($cd_objeto, $cd_unidade = null, $tx_solicitante = null, $dt_inicio = null, $dt_fim = null, $cd_profissional = null, $tx_demanda = null, $tipo_consulta = 'and', $ni_solicitacao = null, $ni_ano_solicitacao = null)
	{
        
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->distinct();

        $select->from(array('dem'=>KT_S_DEMANDA),
                      array('*',
                            'solicitacao' => new Zend_Db_Expr("dem.ni_solicitacao{$this->concat()}'/'{$this->concat()}dem.ni_ano_solicitacao")),
                      $this->_schema);
        $select->joinLeft(array('sol'=>$this->_name),
                          '(dem.cd_objeto          = sol.cd_objeto) AND
                           (dem.ni_solicitacao     = sol.ni_solicitacao) AND
                           (dem.ni_ano_solicitacao = sol.ni_ano_solicitacao)',
                          array(),
                          $this->_schema);
        $select->joinLeft(array('uni'=>KT_B_UNIDADE),
                          'dem.cd_unidade = uni.cd_unidade',
                          'tx_sigla_unidade',
                          $this->_schema);
        $select->join(array('dp'=>KT_A_DEMANDA_PROFISSIONAL),
                      '(dem.cd_demanda = dp.cd_demanda)',
                      array(),
                      $this->_schema);
        $select->where('dem.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);
        $select->order('dt_demanda DESC');

		if (!is_null($cd_unidade)){
            $select->where('dem.cd_unidade = ?', $cd_unidade, Zend_Db::INT_TYPE);
		}
		if (!is_null($tx_solicitante)){
            $select->where(new Zend_Db_Expr("UPPER(dem.tx_solicitante_demanda) LIKE '%{$tx_solicitante}%'"));
		}
		if (!is_null($dt_inicio) && !is_null($dt_fim)){
            $select->where("dem.dt_demanda between '{$dt_inicio} 00:00:00' and '{$dt_fim} 23:59:59'");
		}
		if (!is_null($cd_profissional)){
            $select->where('dp.cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);
		}
		if (!is_null($ni_solicitacao) && !is_null($ni_ano_solicitacao)){
            $select->where('sol.ni_solicitacao     = ?', $ni_solicitacao, Zend_Db::INT_TYPE);
            $select->where('sol.ni_ano_solicitacao = ?', $ni_ano_solicitacao, Zend_Db::INT_TYPE);
		}

		if (!is_null($tx_demanda)){
			$arrDemanda = explode(" ",$tx_demanda);
			foreach ($arrDemanda as $demanda){
				$arrTexto[] = " UPPER(tx_demanda) LIKE '%{$demanda}%'";
			}
			$strTexto = implode(" {$tipo_consulta} ", $arrTexto);
			
            $select->where($strTexto);
		}
        return $this->fetchAll($select)->toArray();

/*
		$and = "";
		if (!is_null($cd_unidade)){
			$and .= " and dem.cd_unidade = {$cd_unidade}";
		}
		if (!is_null($tx_solicitante)){
			$and .= " and upper(dem.tx_solicitante_demanda) like '%{$tx_solicitante}%'";
		}
		if (!is_null($dt_inicio) && !is_null($dt_fim)){
			$and .= " and dem.dt_demanda between to_timestamp('{$dt_inicio} 00:00:00','DD/MM/YYYY HH:24MI:SS') and to_timestamp('{$dt_fim} 23:59:99','DD/MM/YYYY HH:24MI:SS')";
		}
		if (!is_null($cd_profissional)){
			$and .= " and dp.cd_profissional = $cd_profissional";
		}
		if (!is_null($tx_demanda)){
			$and       .= " and (";
			$arrDemanda = explode(" ",$tx_demanda);
			
			foreach ($arrDemanda as $demanda){
				$arrTexto[] = " upper(tx_demanda) like '%{$demanda}%'";
			}
			
			$strTexto = implode(" {$tipo_consulta} ", $arrTexto);
			$and     .= $strTexto;
			$and     .= ")";
		}

		$sql = "select
					distinct
					dem.*,
					uni.tx_sigla_unidade,
					dem.ni_solicitacao||'/'||dem.ni_ano_solicitacao as solicitacao
				from
					{$this->_schema}.".KT_S_DEMANDA." as dem
				left join
					{$this->_schema}.".KT_S_SOLICITACAO." as sol
				on
					(dem.cd_objeto = sol.cd_objeto)
				and
					(dem.ni_solicitacao = sol.ni_solicitacao)
				and
					(dem.ni_ano_solicitacao = sol.ni_ano_solicitacao)
				left join
					{$this->_schema}.".KT_B_UNIDADE." as uni
				on
					dem.cd_unidade = uni.cd_unidade
				join
					{$this->_schema}.".KT_A_DEMANDA_PROFISSIONAL." as dp
				on
					(dem.cd_demanda = dp.cd_demanda)
				where
					dem.cd_objeto = {$cd_objeto}
				{$and}
				order by dt_demanda desc";
				
		return $this->getDefaultAdapter()->fetchAll($sql);
*/
	}	

    /**
     *
     * @param Integer $cd_objeto
     * @param Integer $cd_unidade
     * @param Integer $ni_solicitacao
     * @param Integer $ni_ano_solicitacao
     * @param String $tx_solicitante
     * @param String $dt_inicio         formato YYYY-MM-DD
     * @param String $dt_fim            formato YYYY-MM-DD
     * @param String $tx_solicitacao
     * @param String $tipo_consulta
     *
     * @return Array
     */
	public function getSolicitacaoServicoServiceDeskConsulta($cd_objeto, $cd_unidade = null, $ni_solicitacao = null, $ni_ano_solicitacao = null, $tx_solicitante = null, $dt_inicio = null, $dt_fim = null, $tx_solicitacao = null, $tipo_consulta)
	{
     
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->distinct();

        $select->from(array('sol'=>$this->_name),
                      array('*','solicitacao' => new Zend_Db_Expr("sol.ni_solicitacao{$this->concat()}'/'{$this->concat()}sol.ni_ano_solicitacao")),
                      $this->_schema);
        $select->joinLeft(array('uni'=>KT_B_UNIDADE),
                          '(sol.cd_unidade = uni.cd_unidade)',
                          'tx_sigla_unidade',
                          $this->_schema);
        $select->where('sol.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);
        $select->order('dt_solicitacao DESC');

		if (!is_null($cd_unidade)){
            $select->where('sol.cd_unidade = ?', $cd_unidade, Zend_Db::INT_TYPE);
		}
		if (!is_null($ni_solicitacao) && !is_null($ni_ano_solicitacao)){
            $select->where('sol.ni_solicitacao     = ?', $ni_solicitacao, Zend_Db::INT_TYPE);
            $select->where('sol.ni_ano_solicitacao = ?', $ni_ano_solicitacao, Zend_Db::INT_TYPE);
		}
		if (!is_null($tx_solicitante)){
            $select->where(new Zend_Db_Expr("UPPER(sol.tx_solicitante) LIKE '%{$tx_solicitante}%'"));
		}
		if (!is_null($dt_inicio) && !is_null($dt_fim)){
            $select->where("sol.dt_solicitacao between '{$dt_inicio} 00:00:00' AND '{$dt_fim} 23:59:59'");
		}
		if (!is_null($tx_solicitacao)){
			$arrSolicitacao = explode(" ",$tx_solicitacao);
			foreach ($arrSolicitacao as $solicitacao){
				$arrTexto[] = " upper(tx_solicitacao) like '%{$solicitacao}%'";
			}
			$strTexto = implode(" {$tipo_consulta} ", $arrTexto);
            
            $select->where($strTexto);
		}

        return $this->fetchAll($select)->toArray();
/*
		$and = "";
		if (!is_null($cd_unidade)){
			$and .= " and sol.cd_unidade = {$cd_unidade}";
		}
		if (!is_null($ni_solicitacao) && !is_null($ni_ano_solicitacao)){
			$and .= " and sol.ni_solicitacao = {$ni_solicitacao} and sol.ni_ano_solicitacao = {$ni_ano_solicitacao} ";
		}
		if (!is_null($tx_solicitante)){
			$and .= " and upper(sol.tx_solicitante) like '%{$tx_solicitante}%'";
		}
		if (!is_null($dt_inicio) && !is_null($dt_fim)){
			$and .= " and sol.dt_solicitacao between to_timestamp('{$dt_inicio} 00:00:00','DD/MM/YYYY HH:24MI:SS') and to_timestamp('{$dt_fim} 23:59:99','DD/MM/YYYY HH:24MI:SS')";
		}
		if (!is_null($tx_solicitacao)){
			$and           .= " and (";
			$arrSolicitacao = explode(" ",$tx_solicitacao);
			
			foreach ($arrSolicitacao as $solicitacao)
			{
				$arrTexto[] = " upper(tx_solicitacao) like '%{$solicitacao}%'";
			}
			
			$strTexto = implode(" {$tipo_consulta} ", $arrTexto);
			$and     .= $strTexto;
			$and     .= ")";
		}

        $sql = "select
					distinct
					sol.*,
					uni.tx_sigla_unidade,
					sol.ni_solicitacao||'/'||sol.ni_ano_solicitacao as solicitacao
				from
					{$this->_schema}.".KT_S_SOLICITACAO." as sol
				left join
					{$this->_schema}.".KT_B_UNIDADE." as uni
				on 
					(sol.cd_unidade = uni.cd_unidade)
				where
					sol.cd_objeto = {$cd_objeto}
				{$and}
				order by dt_solicitacao desc";
				
		return $this->getDefaultAdapter()->fetchAll($sql);
*/
	}

	/*
	 * Função que recebe um array de solicitações de serviço e transforma o array
	 * verificando se o objeto do contrato necessita de justiticativa para solicitações
	 * de serviço lidas após o período indicado e se o objeto necessitar, transforma
	 * o array de solicitações verificando para cada solicitação os minutos para leitura
	 * e se a hora da emissão da solicitação está dentro do período estabelecido no
	 * cadastro do objeto do contrato
	 */
	public function verificaJustificativa($cd_objeto, $arrSolicitacoes)
	{
		if (count($arrSolicitacoes)>=1) {
			//Dados sobre a justificativa
			$_objObjetoContrato         = new ObjetoContrato();
			$arrJustificativa           = $_objObjetoContrato->getDadosJustificativa($cd_objeto);
			$st_necessita_justificativa = $arrJustificativa["st_necessita_justificativa"];

			if ($st_necessita_justificativa == "S") {
				$dataHoraAgora            = date("Y-m-d H:i:s");
				$dataAgora                = date("Y-m-d");
				$arrHoras                 = array();
				$arrHoras[1]              = $arrJustificativa["tx_hora_inicio_just_periodo_1"];
				$arrHoras[2]              = $arrJustificativa["tx_hora_fim_just_periodo_1"];
				$arrHoras[3]              = $arrJustificativa["tx_hora_inicio_just_periodo_2"];
				$arrHoras[4]              = $arrJustificativa["tx_hora_fim_just_periodo_2"];
				$ni_minutos_justificativa = $arrJustificativa["ni_minutos_justificativa"];

				if (is_array($arrSolicitacoes)) {
					foreach ($arrSolicitacoes as $key => $solicitacao)
					{
						if (!is_null($solicitacao['dt_leitura_solicitacao'])) {
							$dataHoraLeitura = $solicitacao['dt_leitura_solicitacao'];
							$arrDataLeitura  = explode(" ",$solicitacao['dt_leitura_solicitacao']);
							$dataLeitura     = $arrDataLeitura[0];
							$arrDados        = $this->buscaDadosJustificativaSolicitacao($arrHoras,$solicitacao['dt_solicitacao'],$ni_minutos_justificativa,$dataHoraLeitura,$dataLeitura);
						}else{
							$arrDados      = $this->buscaDadosJustificativaSolicitacao($arrHoras,$solicitacao['dt_solicitacao'],$ni_minutos_justificativa,$dataHoraAgora,$dataAgora);
						}
						$arrSolicitacoes[$key]["hora_no_periodo"]      = $arrDados['hora_no_periodo'];
						$arrSolicitacoes[$key]["minutos_para_leitura"] = $arrDados['minutos_para_leitura'];
					}
				}else{
					$solicitacao                         = $arrSolicitacoes->toArray();
					$arrDados                            = $this->buscaDadosJustificativaSolicitacaoServiceDesk($arrHoras,$solicitacao['dt_solicitacao'],$ni_minutos_justificativa,$dataHoraAgora,$dataAgora);
					$solicitacao["hora_no_periodo"]      = $arrDados['hora_no_periodo'];
					$solicitacao["minutos_para_leitura"] = $arrDados['minutos_para_leitura'];
					$arrSolicitacoes                     = $solicitacao;
				}
			}else{
				if (is_array($arrSolicitacoes)) {
					foreach ($arrSolicitacoes as $key => $solicitacao)
					{
						$ni_minutos_justificativa                      = 0;
						$arrSolicitacoes[$key]["hora_no_periodo"]      = false;
						$arrSolicitacoes[$key]["minutos_para_leitura"] = Base_Util::getTranslator('L_VIEW_SEM_TEMPO_LIMITE_LEITURA');
					}
				}else{
					$solicitacao                         = $arrSolicitacoes->toArray();
					$ni_minutos_justificativa            = 0;
					$solicitacao["hora_no_periodo"]      = false;
					$solicitacao["minutos_para_leitura"] = Base_Util::getTranslator('L_VIEW_SEM_TEMPO_LIMITE_LEITURA');
					$arrSolicitacoes                     = $solicitacao;
				}
			}
		}else{
			$st_necessita_justificativa = "";
			$ni_minutos_justificativa   = 0;
			$arrSolicitacoes            = array();
		}

		return array($st_necessita_justificativa,$ni_minutos_justificativa,$arrSolicitacoes);
	}

	/*
	 * Esta função verifica se a hora de emissão da solicitação de serviço está de dentro do período
	 * indicado no cadastro do objeto do contrato e contabiliza os minutos para leitura da solicitação
	 */
	public function buscaDadosJustificativaSolicitacaoServiceDesk($arrHoras,$dt_solicitacao,$ni_minutos_justificativa,$dataHoraAgora,$dataAgora)
	{

        $hora_solicitacao   = explode(" ",$dt_solicitacao);
		$arrHoras[0]        = $hora_solicitacao[1];
		$hora_no_periodo    = Base_Util::verifyHoraNoPeriodo($arrHoras);
		if ($hora_no_periodo == true) :
			$objDateDiff        = new Util_Datediff($dt_solicitacao,$dataHoraAgora);
			$minutosParaLeitura = $objDateDiff->datediff();
		else:
			if ($arrHoras[0] < $arrHoras[1]) {
				$objDateDiff           = new Util_Datediff($dataHoraAgora,"{$dataAgora} {$arrHoras[1]}");
				$minutosParaLeitura    = $objDateDiff->datediff()+$ni_minutos_justificativa;
			}elseif ($arrHoras[0] > $arrHoras[2] && $arrHoras[0] <= $arrHoras[3]){
				$objDateDiff           = new Util_Datediff($dataHoraAgora,"{$dataAgora} {$arrHoras[3]}");
				$minutosParaLeitura    = $objDateDiff->datediff()+$ni_minutos_justificativa;
			}elseif ($arrHoras[0] > $arrHoras[4]) {
				$dt_solicitacao        = explode("-",$hora_solicitacao[0]);
				$dt_solicitacao_mais_1 = date('Y-m-d', mktime(0,0,0,$dt_solicitacao[1],$dt_solicitacao[2]+1,$dt_solicitacao[0]));
				$objDateDiff_1         = new Util_Datediff($dataHoraAgora,"{$dataAgora} 23:59:59");
				$objDateDiff_2         = new Util_Datediff("{$dt_solicitacao_mais_1} 00:00:00","{$dt_solicitacao_mais_1} {$arrHoras[1]}");
				$minutosParaLeitura    = $objDateDiff_1->datediff()+$objDateDiff_2->datediff()+$ni_minutos_justificativa;
			}
		endif;
		return array('hora_no_periodo'=>$hora_no_periodo,'minutos_para_leitura'=>$minutosParaLeitura);
	}
}