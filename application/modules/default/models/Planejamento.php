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

class Planejamento extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_PLANEJAMENTO;
	protected $_primary  = array('cd_etapa', 'cd_atividade', 'cd_projeto', 'cd_modulo');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    /**
     *
     * @param integer $cd_projeto
     * @param integer $cd_modulo
     * @param integer $cd_etapa
     * @param integer $cd_atividade
     * @return Zend_Db_Table_Row
     */
	public function getPlanejamento($cd_projeto, $cd_modulo, $cd_etapa,  $cd_atividade)
	{
        $select = $this->select()
                       ->where('cd_projeto   = ?', $cd_projeto, Zend_Db::INT_TYPE)
                       ->where('cd_modulo    = ?', $cd_modulo, Zend_Db::INT_TYPE)
                       ->where('cd_etapa     = ?', $cd_etapa, Zend_Db::INT_TYPE)
                       ->where('cd_atividade = ?', $cd_atividade, Zend_Db::INT_TYPE);

        return $this->fetchRow($select);
	}
	
	public function trataDadosGravacao(array $arrDados)
	{
		$return = true;
		foreach($arrDados as $key=>$value){
			$where = "cd_etapa     = {$value['cd_etapa']} and
			          cd_atividade = {$value['cd_atividade']} and
			          cd_projeto   = {$value['cd_projeto']} and 
			          cd_modulo    = {$value['cd_modulo']} ";
			$arrPlanejamento = $this->fetchAll($where)->toArray();
			if(count($arrPlanejamento) > 0 ){
				$return = $this->alteraPlanejamento($value, $where);
			} else {
				$return = $this->salvaPlanejamento($value);
			}
			
			if(!$return){
				break;
			}
		}
		return $return;
	}
	
	private function salvaPlanejamento(array $arrDados)
	{
		$novo                            = $this->createRow();
		$novo->cd_etapa                  = $arrDados['cd_etapa'];
		$novo->cd_atividade              = $arrDados['cd_atividade'];
		$novo->cd_projeto                = $arrDados['cd_projeto'];
		$novo->cd_modulo				 = $arrDados['cd_modulo'];
		$novo->dt_inicio_atividade	     = (!is_null($arrDados['dt_inicio_atividade']) && !empty ($arrDados['dt_inicio_atividade']) ) ? Base_Util::converterDate($arrDados['dt_inicio_atividade'],'DD/MM/YYYY','YYYY-MM-DD'):null;
		$novo->dt_fim_atividade          = (!is_null($arrDados['dt_fim_atividade']) && !empty ($arrDados['dt_fim_atividade'])    ) ? Base_Util::converterDate($arrDados['dt_fim_atividade'],'DD/MM/YYYY','YYYY-MM-DD'):null;
		$novo->nf_porcentagem_execucao	 = $arrDados['nf_porcentagem_execucao'];
		$novo->tx_obs_atividade			 = $arrDados['tx_obs_atividade'];
		if($novo->save()){
			return true;
		} else {
			return false;
		}
	}
	
	private function alteraPlanejamento(array $arrDados, $where)
	{
		$arrUpdate['dt_inicio_atividade'    ] = (!is_null($arrDados['dt_inicio_atividade']) && !empty ($arrDados['dt_inicio_atividade'])) ? 
                                                    Base_Util::converterDate($arrDados['dt_inicio_atividade'],'DD/MM/YYYY','YYYY-MM-DD'):null;
		$arrUpdate['dt_fim_atividade'       ] = (!is_null($arrDados['dt_fim_atividade'   ]) && !empty ($arrDados['dt_fim_atividade'])) ? 
                                                    Base_Util::converterDate($arrDados['dt_fim_atividade'],'DD/MM/YYYY','YYYY-MM-DD'):null;
		$arrUpdate['nf_porcentagem_execucao'] = $arrDados['nf_porcentagem_execucao'];
		$arrUpdate['tx_obs_atividade'       ] = $arrDados['tx_obs_atividade'];
		
		if($this->update($arrUpdate, $where)){
			return true;
		} else {
			return false;
		}
	}

    /**
     *
     * @param integer $cd_projeto
     * @param integer $cd_modulo
     * @param integer $cd_objeto
     * @return Zend_Db_Table_RowSet
     */
	public function getDadosGraficoGantt($cd_projeto, $cd_modulo = null, $cd_objeto, $gantt_tipo_rel)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->distinct();

        $select->from(array('pla'=>$this->_name),
                      array('cd_projeto',
                            'cd_modulo',
                            'cd_atividade',
                            'cd_etapa'),
                      $this->_schema);

        $select->join(array('mod'=>KT_S_MODULO),
                      '(pla.cd_modulo = mod.cd_modulo) AND (pla.cd_projeto = mod.cd_projeto)',
                      array(),
                      $this->_schema);
        $select->join(array('pm'=>KT_A_PROPOSTA_MODULO),
                      '(pla.cd_projeto = pm.cd_projeto) AND (pla.cd_modulo = pm.cd_modulo)',
                      array(),
                      $this->_schema);
        $select->join(array('pro'=>KT_S_PROPOSTA),
                      '(pla.cd_projeto = pro.cd_projeto) AND (pm.cd_proposta = pro.cd_proposta)',
                      array(),
                      $this->_schema);
        $select->join(array('eta'=>KT_B_ETAPA),
                      '(pla.cd_etapa = eta.cd_etapa)',
                      array(),
                      $this->_schema);
        $select->join(array('oca'=>KT_A_OBJETO_CONTRATO_ATIVIDADE),
                      '(eta.cd_etapa = oca.cd_etapa)',
                      array(),
                      $this->_schema);

        $select->where('pla.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
        $select->where('oca.cd_objeto  = ?', $cd_objeto, Zend_Db::INT_TYPE);

		if ($gantt_tipo_rel == 'E') {
			$select->where('pro.st_encerramento_proposta IS NULL');
		}

		if(!is_null($cd_modulo)){
			$select->where('pla.cd_modulo = ?', $cd_modulo, Zend_Db::INT_TYPE);
		}

        return $this->fetchAll($select);
	}

    /**
     *
     * @param integer $cd_projeto
     * @param integer $cd_modulo
     * @param integer $cd_objeto
     * @return Zend_Db_Table_RowSet
     */
	public function getDadosModuloGraficoGantt($cd_projeto, $cd_modulo, $cd_objeto)
	{
        $select = $this->select()
                       ->distinct()
                       ->setIntegrityCheck(false);

        $select->from(array('pla'=>$this->_name),
                      array('cd_modulo',
                            'porcentagem' => new Zend_Db_Expr('ROUND((SUM(nf_porcentagem_execucao)/COUNT(pla.cd_atividade)))'),
                            'inicio'      => new Zend_Db_Expr('MIN(dt_inicio_atividade)'),
                            'fim'         => new Zend_Db_Expr('MAX(dt_fim_atividade)')),
                      $this->_schema);
        $select->join(array('modu'=>KT_S_MODULO),
                      '(pla.cd_modulo = modu.cd_modulo)',
                      'tx_modulo',
                      $this->_schema);
        $select->join(array('eta'=>KT_B_ETAPA),
                      '(pla.cd_etapa = eta.cd_etapa)',
                      array(),
                      $this->_schema);
        $select->join(array('oca'=>KT_A_OBJETO_CONTRATO_ATIVIDADE),
                      '(eta.cd_etapa = oca.cd_etapa)',
                      array(),
                      $this->_schema);
        $select->where('pla.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
        $select->where('pla.cd_modulo  = ?', $cd_modulo,  Zend_Db::INT_TYPE);
        $select->where('oca.cd_objeto  = ?', $cd_objeto,  Zend_Db::INT_TYPE);
        $select->group(array('pla.cd_modulo', 'modu.tx_modulo'));

		return $this->fetchAll($select);
	}

    /**
     *
     * @param integer $cd_projeto
     * @param integer $cd_modulo
     * @param integer $cd_objeto
     * @return Zend_Db_Table_RowSet
     */
	public function getDadosEtapaGraficoGantt($cd_projeto, $cd_modulo, $cd_objeto)
	{
        $select = $this->select()
                       ->distinct()
                       ->setIntegrityCheck(false);

        $select->from(array('pla'=>$this->_name),
                      array('cd_etapa',
                            'porcentagem' => new Zend_Db_Expr('ROUND((SUM(nf_porcentagem_execucao)/COUNT(pla.cd_atividade)))'),
                            'inicio'      => new Zend_Db_Expr('MIN(dt_inicio_atividade)'),
                            'fim'         => new Zend_Db_Expr('MAX(dt_fim_atividade)')),
                      $this->_schema);
        $select->join(array('eta'=>KT_B_ETAPA),
                      '(pla.cd_etapa = eta.cd_etapa)',
                      'tx_etapa',
                      $this->_schema);
        $select->join(array('oca'=>KT_A_OBJETO_CONTRATO_ATIVIDADE),
                      '(eta.cd_etapa = oca.cd_etapa)',
                      array(),
                      $this->_schema);

        $select->where('pla.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
        $select->where('pla.cd_modulo  = ?', $cd_modulo,  Zend_Db::INT_TYPE);
        $select->where('oca.cd_objeto  = ?', $cd_objeto,  Zend_Db::INT_TYPE);
        $select->group(array('pla.cd_etapa', 'eta.tx_etapa'));

         return $this->fetchAll($select);
	}

    /**
     *
     * @param integer $cd_projeto
     * @param integer $cd_modulo
     * @param integer $cd_etapa
     * @return Zend_Db_Table_RowSet
     */
	public function getDadosAtividadeGraficoGantt($cd_projeto, $cd_modulo, $cd_etapa)
	{
        $select = $this->select()->setIntegrityCheck(false);
        
        $select->from(array('pla'=>$this->_name),
                      array('dt_inicio_atividade',
                            'dt_fim_atividade',
                            'nf_porcentagem_execucao'),
                      $this->_schema);
        $select->join(array('ati'=>KT_B_ATIVIDADE),
                      '(pla.cd_atividade = ati.cd_atividade)',
                      array('cd_atividade','tx_atividade'),
                      $this->_schema);
        $select->where('pla.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
        $select->where('pla.cd_modulo  = ?', $cd_modulo,  Zend_Db::INT_TYPE);
        $select->where('pla.cd_etapa   = ?', $cd_etapa,   Zend_Db::INT_TYPE);
        $select->order(array('dt_inicio_atividade', 'dt_fim_atividade'));
        
        return $this->fetchAll($select);
	}
}