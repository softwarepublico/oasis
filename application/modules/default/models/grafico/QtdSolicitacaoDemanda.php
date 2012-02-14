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

include_once 'Abstract.php';
/**
 * Classe de acesso ao banco do gráfico
 */
class Grafico_QtdSolicitacaoDemanda extends Grafico_Abstract
{
    private $_schema = K_SCHEMA;

    public function __construct()
    {
        parent::__construct();
    }

    
    /**
     * Método que executa a query do gráfico
     *
     * @param ARRAY $params
     * @return ARRAY
     */ 
    public function getDadosSolicitacaoDemanda( array $params )
    {

		$sql = "select
			          sol.mes,
					  sol.qtd_sol,
					  dem.qtd_dem
				   from
					  (
							select
						      extract(month from dt_solicitacao)as mes,
							  count(*) as qtd_sol
							from
						      oasis.s_solicitacao
							where
							  cd_objeto = 2
							and
							  extract(year from dt_solicitacao) = extract(year from current_date)
							group by mes
					   ) as sol
					inner join
						(
							select
								extract(month from dt_demanda)as mes,
								count(*) as qtd_dem
							from
								oasis.s_demanda
							where
								cd_objeto = 2
							and
								extract(year from dt_demanda) = extract(year from current_date)
							group by mes
						) as dem
					on
						sol.mes = dem.mes
					order by sol.mes";
		
//		$arrResult = $this->getDefaultAdapter()->fetchAll($sql);
		$arrResult = $this->db->fetchAll( $sql, $param );
		return $arrResult;

        /*
        $objTable = new Parcela();

		$select = $objTable->select()
                           ->setIntegrityCheck(false);
        $select->from(array('p'=>KT_S_PARCELA),
                      array('ni_mes_previsao_parcela', 'horas'=>new Zend_Db_Expr('SUM(ni_horas_parcela)')),
                      $this->_schema);
        $select->join(array('procpar'=>KT_S_PROCESSAMENTO_PARCELA),
                      '(p.cd_projeto = procpar.cd_projeto) AND (p.cd_proposta = procpar.cd_proposta) AND (p.cd_parcela = procpar.cd_parcela)',
                      array(),
                      $this->_schema);
        $select->join(array('cp'=>KT_A_CONTRATO_PROJETO),
                      '(cp.cd_projeto = p.cd_projeto)',
                      array(),
                      $this->_schema);
        $select->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                      '(procpar.cd_objeto_execucao = oc.cd_objeto) AND (oc.cd_contrato = cp.cd_contrato)',
                      array(),
                      $this->_schema);
        $select->where('p.ni_ano_previsao_parcela = ?', $params['ano'], Zend_Db::INT_TYPE);
        $select->where('p.ni_mes_previsao_parcela = ?', $params['mes'], Zend_Db::INT_TYPE);
        $select->where('cp.cd_contrato = ?', $params['cd_contrato'], Zend_Db::INT_TYPE);
        $select->where("procpar.st_ativo = 'S'");
        $select->group('p.ni_mes_previsao_parcela');

        return $objTable->fetchAll($select)->toArray();
		*/


    }

    /**
     * Método que executa a query do gráfico
     *
     * @param ARRAY $params
     * @return ARRAY $result
     */ 
    public function getDadosRealizadoParcela( array $params )
    {

//    	$sql = "SELECT 
//		            p.ni_mes_extrato,sum(p.ni_hora_parcela_extrato) as horas
//                FROM
//					".K_SCHEMA.".".KT_S_EXTRATO_MENSAL_PARCELA." as p
//                WHERE
//                    p.ni_ano_extrato = :ano
//                AND
//                    p.ni_mes_extrato = :mes
//                AND
//                    p.cd_contrato = :cd_contrato
//                GROUP BY
//                    p.ni_mes_extrato";
//
//        $param[':mes'] = $params['mes'];
//        $param[':ano'] = $params['ano'];
//        $param[':cd_contrato'] = $params['cd_contrato'];
//
//        return $this->db->fetchAll( $sql, $param );

        $objTable = new ExtratoMensalParcela();

        $select = $objTable->select()
                           ->setIntegrityCheck(false);
        $select->from($objTable,
                      array('ni_mes_extrato',
                            'horas'=>new Zend_Db_Expr('sum(ni_hora_parcela_extrato)')));
        $select->where('ni_ano_extrato = ?', $params['ano'], Zend_Db::INT_TYPE)
               ->where('ni_mes_extrato = ?', $params['mes'], Zend_Db::INT_TYPE)
               ->where('cd_contrato = ?', $params['cd_contrato'], Zend_Db::INT_TYPE);
        $select->group('ni_mes_extrato');

        return $objTable->fetchAll($select)->toArray();
    }
}