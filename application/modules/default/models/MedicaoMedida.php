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

class MedicaoMedida extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_A_MEDICAO_MEDIDA;
	protected $_primary  = array('cd_medicao','cd_medida');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function pesquisaMedidaForaMedicao( $cd_medicao )
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('m'=>KT_B_MEDIDA),
                      array('cd_medida','tx_medida'),
                      $this->_schema);
        $select->where('m.cd_medida NOT IN ?', $this->_montaSubSelectPesquisaMedidaMedicacao($cd_medicao));
        $select->order('tx_medida');

        return $this->fetchAll($select)->toArray();
	}
	
	public function pesquisaMedidaNaMedicao( $cd_medicao )
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('m'=>KT_B_MEDIDA),
                      array('cd_medida','tx_medida'),
                      $this->_schema);
        $select->where('m.cd_medida IN ?', $this->_montaSubSelectPesquisaMedidaMedicacao($cd_medicao));
        $select->order('tx_medida');

        return $this->fetchAll($select)->toArray();
	}

    private function _montaSubSelectPesquisaMedidaMedicacao($cd_medicao)
    {
        $select = $this->select()
                       ->from($this->_name,
                              'cd_medida',
                              $this->_schema)
                       ->where('cd_medicao = ?', $cd_medicao, Zend_Db::INT_TYPE);
        return $select;
    }

	public function getMedidaMedicao($cd_medicao)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('mm'=>$this->_name),
                      array('cd_medida','st_prioridade_medida'),
                      $this->_schema);
        $select->join(array('m'=>KT_B_MEDIDA),'(mm.cd_medida = m.cd_medida)','tx_medida',$this->_schema);
        $select->where('cd_medicao = ?',$cd_medicao, Zend_Db::INT_TYPE);
        $select->order('tx_medida');

		return $this->fetchAll($select)->toArray();
	}
}