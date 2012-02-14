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
class Grafico_SaldoHorasContrato extends Grafico_Abstract
{
    private $_schema = K_SCHEMA;
     private $_objTable;

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
    public function getDadosContrato( array $params )
    {
        $objTable = new Contrato();

        $select = $objTable->select()
                           ->setIntegrityCheck(false)
                           ->from($objTable,
                                  array(
                                        'dt_inicio'=> 'dt_inicio_contrato',
                                        'dt_final' => 'dt_fim_contrato',
                                        'ni_horas_previstas',
                                        'ni_mes_inicial_contrato',
                                        'ni_ano_inicial_contrato',
                                        'ni_mes_final_contrato',
                                        'ni_ano_final_contrato',
                                        'ni_qtd_meses_contrato'))
                           ->where('cd_contrato = ?', $params['cd_contrato'], Zend_Db::INT_TYPE);

        return $objTable->fetchAll($select)->toArray();
    }

    /**
     * Método que executa a query do gráfico
     *
     * @param ARRAY $params
     * @return ARRAY
     */ 
    public function getDadosPrevisaoParcela( array $params )
    {
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
	}

public function getDadosEncerramentoProposta(array $params)
    {
    $cd_contrato = $params['cd_contrato'];
    
    $sql=
					"select sum(prop.ni_horas_proposta) as horas
				from
					".K_SCHEMA.".".KT_S_PROPOSTA." as prop
				join
					".K_SCHEMA.".".KT_S_PROJETO." as proj
				on
					prop.cd_projeto = proj.cd_projeto
				join
				(
					select
						cd_projeto
					from
						".K_SCHEMA.".".KT_A_CONTRATO_PROJETO."
					where
						cd_contrato = ".$cd_contrato."
				) as cp
				on
					cp.cd_projeto = prop.cd_projeto
				where
					st_encerramento_proposta = 'E'";
    
      $arrResult = $this->db->fetchAll($sql);
      return $arrResult;
    }    
    
public function getHorasPrevisaoParcelaMes(array $params)
    {
    $sql = 'SELECT DISTINCT parc.cd_projeto 
            FROM '.K_SCHEMA.'.'.KT_S_PARCELA.' AS parc
            INNER JOIN '.K_SCHEMA.'.'.KT_S_PROJETO.' AS proj ON (parc.cd_projeto = proj.cd_projeto)
            INNER JOIN '.K_SCHEMA.'.'.KT_A_CONTRATO_PROJETO.' AS cp ON (cp.cd_projeto = proj.cd_projeto)
            WHERE (cp.cd_contrato ='. $params['cd_contrato'].') 
              AND (parc.ni_ano_previsao_parcela = '. $params['ano'].') 
              AND (parc.ni_mes_previsao_parcela  BETWEEN '. $params['mes'].' and '. $params['mes'].' ) 
            GROUP BY parc.cd_projeto,
                    parc.ni_mes_previsao_parcela';

     $arrProj     = $this->db->fetchAll($sql);
     $horas_total = 0;

     //  PEGAR TODAS AS PROPOSTAS E SOMAR
     if(count($arrProj) > 0){
         
	   foreach($arrProj as $value){

           $sql1='SELECT DISTINCT parc.ni_mes_previsao_parcela, sum(ni_horas_parcela) AS horas_total_projeto 
             FROM '.K_SCHEMA.'.'.KT_S_PARCELA.' AS parc 
             WHERE (cd_projeto = '. $value['cd_projeto'].') AND (ni_ano_previsao_parcela = '. $params['ano'].') AND (ni_mes_previsao_parcela = '. $params['mes'].') 
             GROUP BY ni_mes_previsao_parcela';
           $arrPar = $this->db->fetchAll($sql1);
           if(!empty ($arrPar)){
              
              $horas_total += round($arrPar[0]['horas_total_projeto'],1);    
           
           }
           
        }
      }    
      return  $horas_total;
    }
}