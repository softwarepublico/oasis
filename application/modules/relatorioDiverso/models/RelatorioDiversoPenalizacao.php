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

class RelatorioDiversoPenalizacao extends Base_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;

	public function getPenalizacoes($cd_contrato, $dt_inicio_periodo, $dt_fim_periodo, $justificativaAceita = true){

		$objPenalizacao = new Penalizacao();

		$select = $objPenalizacao->select()->setIntegrityCheck(false);

		$select->from(array('pen1'=>KT_S_PENALIZACAO),
					  array('dt_penalizacao'=> new Zend_Db_Expr("{$this->to_char('pen1.dt_penalizacao','DD/MM/YYYY')}"),
							'pen1.cd_contrato',
							'pen1.cd_penalidade',
							'tx_obs_penalizacao',
							'tx_justificativa_penalizacao',
							'pen1.ni_qtd_ocorrencia',
							'st_aceite_justificativa'=> new Zend_Db_Expr("CASE WHEN pen1.st_aceite_justificativa = 'S' THEN '".Base_Util::getTranslator('L_SQL_ACEITA')."'
																			   WHEN pen1.st_aceite_justificativa = 'N' THEN '".Base_Util::getTranslator('L_SQL_NAO_ACEITA')."'
                                                                                                                       ELSE '".Base_Util::getTranslator('L_SQL_SEM_JUSTIFICATIVA')."' END"),
							'pen2.tx_penalidade',
							'pen2.ni_valor_penalidade',
							'ni_valor_total_penalidade'=> new Zend_Db_Expr("CASE WHEN pen1.ni_qtd_ocorrencia IS NOT NULL THEN (pen1.ni_qtd_ocorrencia*pen2.ni_valor_penalidade) ELSE pen2.ni_valor_penalidade END"),
							),
					  $this->_schema)
				  ->join(array('pen2'=>KT_B_PENALIDADE), "pen1.cd_penalidade = pen2.cd_penalidade", array(), $this->_schema)
				  ->where("pen1.dt_penalizacao between '{$dt_inicio_periodo}' and '{$dt_fim_periodo}'")
				  ->where("pen1.cd_contrato = ?", $cd_contrato)
		;

		if($justificativaAceita === true){
			$select->where("st_aceite_justificativa = ?", "S");
		}else if($justificativaAceita === false){
			$select->where("st_aceite_justificativa = ?", "N")
				   ->orWhere("st_aceite_justificativa is null");
		}

		return $objPenalizacao->fetchAll($select);
	}
}