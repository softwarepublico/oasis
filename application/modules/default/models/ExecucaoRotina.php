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

class ExecucaoRotina extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_EXECUCAO_ROTINA;
	protected $_primary  = array('dt_execucao_rotina', 'cd_profissional', 'cd_objeto', 'cd_rotina');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    /**
     * Recupera as rotinas de um objeto
     * 
     * @param type $cd_objeto
     * @param type $dt_execucao_rotina
     * @param type $cd_profissional
     * @param type $cd_rotina
     * 
     * @return type Zend_Db_Table_Rowset
     */
    public function getExecucaoRotina($cd_objeto, $dt_execucao_rotina, $cd_profissional = null, $cd_rotina = null)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('er'=>$this->_name),
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
                            'ni_prazo_execucao_rotina'),
                      $this->_schema);

        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(er.cd_profissional = prof.cd_profissional)',
                      array('tx_profissional'),
                      $this->_schema);

        $select->where('er.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);
        $select->where('er.dt_execucao_rotina = ?', $dt_execucao_rotina);
        $select->order('tx_hora_execucao_rotina');

        if (!is_null($cd_profissional)) {
            $select->where('er.cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);
        }

        if (!is_null($cd_rotina)) {
            $select->where('er.cd_rotina = ?', $cd_rotina, Zend_Db::INT_TYPE);
        }

		return $this->fetchAll($select);
	}

    public function alterarExecucaoRotina(array $arrExecucaoRotina)
	{
		$where = array(
            "cd_rotina = ?"          =>$arrExecucaoRotina['cd_rotina'],
			"cd_objeto = ?"          =>$arrExecucaoRotina['cd_objeto'],
            "cd_profissional = ?"    => $arrExecucaoRotina['cd_profissional'],
            "dt_execucao_rotina = ?" => $arrExecucaoRotina['dt_execucao_rotina']
        );
		if($this->update(array('st_historico'=>'S'),$where)){
			return true;
		} else {
			return false;
		}
	}

    public function fechaExecucaoRotina(array $arrExecucaoRotina)
	{
		$arrUpdate['st_fechamento_execucao_rotina'] = 'S';

		$where = array(
            "cd_rotina = ?"          =>$arrExecucaoRotina['cd_rotina'],
			"cd_objeto = ?"          =>$arrExecucaoRotina['cd_objeto'],
            "cd_profissional = ?"    =>$arrExecucaoRotina['cd_profissional'],
            "dt_execucao_rotina = ?" =>$arrExecucaoRotina['dt_execucao_rotina']
        );

		if($this->update(array('st_fechamento_execucao_rotina' => 'S'),$where)){
			return true;
		} else {
			return false;
		}
	}
}