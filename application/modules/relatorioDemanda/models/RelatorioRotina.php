<?php
/**
 * @Copyright Copyright 2006, 2007, 2008, 2009 MDIC - Ministério do Desenvolvimento, da Industria e do Comércio Exterior, Brasil.
 * @tutorial  Este arquivo é parte do programa OASIS - Sistema de Gestão de Rotina, Projetos e Serviços de TI.
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

class RelatorioRotina extends Base_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;

    private $_objTable;

    public function init()
    {
        parent::init();
        $this->_objTable = new ExecucaoRotina();
    }

    /**
     * Método que executa a query do relatório
     *
     * @param Integer $cd_objeto
     * @param Integer $cd_profissional
     * @param String  $dt_inicio         formato YYYY-MM-DD
     * @param String  $dt_fim            formato YYYY-MM-DD
     *
     * @return Zend_Db_Table_Rowset
     */
    public function getExecucaoRotina($arrWhere)
	{
        $select = $this->_objTable->select()
                       ->setIntegrityCheck(false);

        $select->from(array('er'=>KT_S_EXECUCAO_ROTINA),
                      array('dt_execucao_rotina',
                            'cd_objeto',
                            'cd_rotina',
                            'tx_hora_execucao_rotina',
                            'cd_profissional',
                            'st_historico',
                            'st_fechamento_execucao_rotina'),
                      $this->_schema);

        $select->join(array('rot'=>KT_B_ROTINA),
                      '(er.cd_rotina = rot.cd_rotina)',
                      array('tx_rotina',
                            'tx_hora_inicio_rotina',
                            'st_periodicidade_rotina',
                            'ni_prazo_execucao_rotina'),
                      $this->_schema);
        
        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(er.cd_profissional = prof.cd_profissional)',
                      array('tx_profissional'),
                      $this->_schema);

        $select->where('er.cd_objeto = ?', $arrWhere['cd_objeto'], Zend_Db::INT_TYPE);
        $select->where("er.dt_execucao_rotina between '{$arrWhere['dt_inicio']}' and '{$arrWhere['dt_fim']}'");
        $select->order(array('dt_execucao_rotina','tx_hora_execucao_rotina'));

        if (array_key_exists('cd_profissional', $arrWhere)) {
            $select->where('er.cd_profissional = ?', $arrWhere['cd_profissional'], Zend_Db::INT_TYPE);
        }

        if (array_key_exists('cd_rotina', $arrWhere)) {
            $select->where('er.cd_rotina = ?', $arrWhere['cd_rotina'], Zend_Db::INT_TYPE);
        }
		return $this->_objTable->fetchAll($select);
	}

    public function getHistoricoExecucaoRotina($arrParams)
	{
        $select = $this->_objTable->select()
                       ->setIntegrityCheck(false);

        $select->from(array('her'=>KT_S_HISTORICO_EXECUCAO_ROTINA),
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
		return $this->_objTable->fetchAll($select);
	}
}