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

class HistoricoExecucaoRotina extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_HISTORICO_EXECUCAO_ROTINA;
	protected $_primary  = array('dt_historico_execucao_rotina', 'dt_execucao_rotina', 'cd_profissional', 'cd_objeto', 'cd_rotina');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    public function getHistoricoExecucaoRotina($arrParams)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('her'=>$this->_name),
                      array('dt_execucao_rotina',
                            'cd_objeto',
                            'cd_rotina',
                            'cd_profissional',
                            'tx_historico_execucao_rotina',
                            'dt_historico_rotina',
                            'dt_historico_execucao_rotina'),
                      $this->_schema);

        $select->join(array('rot'=>KT_B_ROTINA),
                      '(her.cd_rotina = rot.cd_rotina)',
                      array('tx_rotina'),
                      $this->_schema);

        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(her.cd_profissional = prof.cd_profissional)',
                      array('tx_profissional'),
                      $this->_schema);

        $select->join(array('er'=>KT_S_EXECUCAO_ROTINA),
                      '(her.cd_profissional = er.cd_profissional) and
                       (her.dt_execucao_rotina = er.dt_execucao_rotina) and
                       (her.cd_objeto = er.cd_objeto) and
                       (her.cd_rotina = er.cd_rotina)',
                      array('st_fechamento_execucao_rotina'),
                      $this->_schema);

        $select->where('her.cd_objeto          = ?', $arrParams['cd_objeto'], Zend_Db::INT_TYPE);
        $select->where('her.dt_execucao_rotina = ?', $arrParams['dt_execucao_rotina']);
        $select->where('her.cd_profissional    = ?', $arrParams['cd_profissional'], Zend_Db::INT_TYPE);
        $select->where('her.cd_rotina          = ?', $arrParams['cd_rotina'], Zend_Db::INT_TYPE);
        $select->order('her.dt_historico_execucao_rotina');

        if (array_key_exists('dt_historico_execucao_rotina', $arrParams)) {
            if (!is_null($arrParams['dt_historico_execucao_rotina'])) {
                $select->where('her.dt_historico_execucao_rotina = ?', $arrParams['dt_historico_execucao_rotina']);
            }
        }

		return $this->fetchAll($select);
	}

    public function salvarHistoricoExecucaoRotina(array $arrHistoricoExecucaoRotina)
	{
		$novo = $this->createRow();
		$novo->dt_historico_execucao_rotina = date('Y-m-d H:i:s');
		$novo->cd_rotina                    = $arrHistoricoExecucaoRotina['cd_rotina'];
		$novo->cd_objeto                    = $arrHistoricoExecucaoRotina['cd_objeto'];
		$novo->cd_profissional              = $arrHistoricoExecucaoRotina['cd_profissional'];
		$novo->dt_execucao_rotina           = $arrHistoricoExecucaoRotina['dt_execucao_rotina'];
		$novo->tx_historico_execucao_rotina = $arrHistoricoExecucaoRotina['tx_historico_execucao_rotina'];
		$novo->dt_historico_rotina          = (!empty ($arrHistoricoExecucaoRotina['dt_historico_rotina'])) ? $arrHistoricoExecucaoRotina['dt_historico_rotina'] : null;

		if($novo->save()){
			return true;
		} else {
			return false;
		}
	}

	public function alterarHistoricoExecucaoRotina(array $arrHistoricoExecucaoRotina)
	{
		$arrUpdate['tx_historico_execucao_rotina'] = $arrHistoricoExecucaoRotina['tx_historico_execucao_rotina'];
		$arrUpdate['dt_historico_rotina']          = (!empty ($arrHistoricoExecucaoRotina['dt_historico_rotina'])) ? $arrHistoricoExecucaoRotina['dt_historico_rotina'] : null;

		$where = "dt_historico_execucao_rotina = '{$arrHistoricoExecucaoRotina['dt_historico_execucao_rotina']}'
				  and cd_rotina                = {$arrHistoricoExecucaoRotina['cd_rotina']}
				  and cd_objeto                = {$arrHistoricoExecucaoRotina['cd_objeto']}
                  and cd_profissional          = {$arrHistoricoExecucaoRotina['cd_profissional']}
                  and dt_execucao_rotina       = '{$arrHistoricoExecucaoRotina['dt_execucao_rotina']}'";
                  
		if($this->update($arrUpdate,$where)){
			return true;
		} else {
			return false;
		}
	}

    public function excluirHistoricoExecucaoRotina(array $arrHistoricoExecucaoRotina)
	{
		$where = "dt_historico_execucao_rotina = '{$arrHistoricoExecucaoRotina['dt_historico_execucao_rotina']}'
				  and cd_rotina                = {$arrHistoricoExecucaoRotina['cd_rotina']}
				  and cd_objeto                = {$arrHistoricoExecucaoRotina['cd_objeto']}
                  and cd_profissional          = {$arrHistoricoExecucaoRotina['cd_profissional']}
                  and dt_execucao_rotina       = '{$arrHistoricoExecucaoRotina['dt_execucao_rotina']}'";
                  
        if($this->delete($where)){
			return true;
		} else {
			return false;
		}
	}
}