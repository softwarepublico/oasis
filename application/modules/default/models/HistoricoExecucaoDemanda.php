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

class HistoricoExecucaoDemanda extends Base_Db_Table_Abstract 
{
	protected $_name     = KT_S_HISTORICO_EXECUCAO_DEMANDA;
	protected $_primary  = 'cd_historico_execucao_demanda';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function salvarHistoricoExecucaoDemanda(array $arrHistoricoExecucaoDemanda)
	{
		$novo = $this->createRow();
		$novo->cd_historico_execucao_demanda = $this->getNextValueOfField('cd_historico_execucao_demanda');
		$novo->cd_demanda                    = $arrHistoricoExecucaoDemanda['cd_demanda'];
		$novo->dt_inicio                     = (!empty ($arrHistoricoExecucaoDemanda['dt_inicio'])) ? $arrHistoricoExecucaoDemanda['dt_inicio'] : null;
		$novo->dt_fim                        = (!empty ($arrHistoricoExecucaoDemanda['dt_fim'   ])) ? $arrHistoricoExecucaoDemanda['dt_fim'   ] : null;
		$novo->tx_historico                  = $arrHistoricoExecucaoDemanda['tx_historico'];
		$novo->cd_profissional               = $arrHistoricoExecucaoDemanda['cd_profissional'];
		$novo->cd_nivel_servico              = $arrHistoricoExecucaoDemanda['cd_nivel_servico'];
		
		if($novo->save()){
			return true;
		} else {
			return false;			
		}
	}
	
	public function alterarHistoricoExecucaoDemanda(array $arrHistoricoExecucaoDemanda)
	{
		$arrUpdate['cd_historico_execucao_demanda'] = $arrHistoricoExecucaoDemanda['cd_historico_execucao_demanda'];
		$arrUpdate['cd_demanda']                    = $arrHistoricoExecucaoDemanda['cd_demanda'];
		$arrUpdate['dt_inicio']                     = (!empty ($arrHistoricoExecucaoDemanda['dt_inicio'])) ? $arrHistoricoExecucaoDemanda['dt_inicio'] : null;
		$arrUpdate['dt_fim']                        = (!empty ($arrHistoricoExecucaoDemanda['dt_fim'   ])) ? $arrHistoricoExecucaoDemanda['dt_fim'   ] : null;
		$arrUpdate['tx_historico']                  = $arrHistoricoExecucaoDemanda['tx_historico'];
		$arrUpdate['cd_profissional']               = $arrHistoricoExecucaoDemanda['cd_profissional'];
		$arrUpdate['cd_nivel_servico']              = $arrHistoricoExecucaoDemanda['cd_nivel_servico'];
		
		$where = array(
            "cd_historico_execucao_demanda = ?" => $arrHistoricoExecucaoDemanda['cd_historico_execucao_demanda'],
            "cd_demanda = ?"                    => $arrHistoricoExecucaoDemanda['cd_demanda'],
            "cd_nivel_servico = ?"              => $arrHistoricoExecucaoDemanda['cd_nivel_servico']
        );
		if($this->update($arrUpdate,$where)){
			return true;
		} else {
			return false;			
		}
	}
	
	public function getDadosHistoricoExecucaoDemanda($cd_demanda = null,$cd_historico_execucao = null, $cd_profissional = null, $cd_nivel_servico = null)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('hed'=>$this->_name),
                      array('cd_historico_execucao_demanda',
                            'cd_demanda',
                            'tx_historico',
                            'dt_inicio',
                            'dt_fim'),
                      $this->_schema);
        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(hed.cd_profissional = prof.cd_profissional)',
                      array('cd_profissional','tx_profissional','tx_nome_conhecido'),
                      $this->_schema);
        $select->join(array('dem'=>KT_S_DEMANDA),
                      '(hed.cd_demanda = dem.cd_demanda)',
                      'cd_objeto',
                      $this->_schema);

		if(!is_null($cd_demanda))
            $select->where('hed.cd_demanda = ?',$cd_demanda, Zend_Db::INT_TYPE);
		if(!is_null($cd_historico_execucao))
            $select->where('cd_historico_execucao_demanda = ?',$cd_historico_execucao, Zend_Db::INT_TYPE);
		if(!is_null($cd_profissional))
            $select->where('prof.cd_profissional = ?',$cd_profissional, Zend_Db::INT_TYPE);
		if(!is_null($cd_nivel_servico))
            $select->where('hed.cd_nivel_servico = ?',$cd_nivel_servico, Zend_Db::INT_TYPE);
        return $this->fetchAll($select)->toArray();
	}
	
	public function excluirHistoricoExecucaoDemanda($cd_historico_execucao)
	{
		if($this->delete(array('cd_historico_execucao_demanda = ?'=>$cd_historico_execucao))){
			return true;
		} else {
			return false;
		}
	}
	
	public function historicoDemandaNivelServico($cd_demanda)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('dem'=>KT_S_DEMANDA),
                      array('cd_demanda',
                            'tx_demanda',
                            'ni_solicitacao',
                            'ni_ano_solicitacao',
                            'tx_solicitante_demanda',
                            'cd_unidade',
                            'dt_demanda'),
                      $this->_schema);
        $select->join(array('dp'=>KT_A_DEMANDA_PROFISSIONAL),
                      '(dem.cd_demanda = dp.cd_demanda)',
                      'cd_profissional',
                      $this->_schema);
        $select->join(array('dpns'=>KT_A_DEMANDA_PROF_NIVEL_SERVICO),
                      '(dem.cd_demanda = dpns.cd_demanda) AND (dp.cd_profissional = dpns.cd_profissional)',
                      array('cd_nivel_servico',
                            'dt_demanda_nivel_servico'),
                      $this->_schema);
        $select->join(array('ns'=>KT_B_NIVEL_SERVICO),
                      '(dpns.cd_nivel_servico = ns.cd_nivel_servico)',
                      array('tx_nivel_servico','ni_horas_prazo_execucao'),
                      $this->_schema);
        $select->joinLeft(array('hed'=>KT_S_HISTORICO_EXECUCAO_DEMANDA),
                      '(dem.cd_demanda = hed.cd_demanda) AND (dpns.cd_nivel_servico = hed.cd_nivel_servico) AND (dpns.cd_profissional = hed.cd_profissional)',
                      array('tx_historico',
                            'dt_inicio',
                            'dt_fim'),
                      $this->_schema);
        $select->joinLeft(array('unid'=>KT_B_UNIDADE),
                          '(dem.cd_unidade = unid.cd_unidade)',
                          'tx_sigla_unidade',
                          $this->_schema);
        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(dp.cd_profissional = prof.cd_profissional)',
                      'tx_profissional',
                      $this->_schema);
        $select->where('dem.cd_demanda = ?',$cd_demanda,Zend_Db::INT_TYPE);
        $select->order(array('hed.dt_inicio', 'prof.tx_profissional'));

        return $this->fetchAll($select)->toArray();
	}
}