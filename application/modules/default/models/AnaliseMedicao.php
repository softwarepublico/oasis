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

class AnaliseMedicao extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_ANALISE_MEDICAO;
	protected $_primary  = array('dt_analise_medicao','cd_medicao','cd_box_inicio');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	/**
     *
     * @param string $dt_analise
     * @param int $cd_medicao
     * @param int $cd_box_inicio
     * @return Zend_Db_Table_RowSet
     */
    public function getAnaliseMedicaoEspecifica($dt_analise,$cd_medicao,$cd_box_inicio)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('am'=>$this->_name),
                      array('*'),
                      $this->_schema);
        $select->join(array('m'=>KT_S_MEDICAO),
                      '(am.cd_medicao = m.cd_medicao)',
                      array('tx_medicao'),
                      $this->_schema);
        $select->join(array('bi'=>KT_B_BOX_INICIO),
                      '(am.cd_box_inicio = bi.cd_box_inicio)',
                      array('tx_titulo_box_inicio'),
                      $this->_schema);
        $select->where('am.dt_analise_medicao = ?', $dt_analise);
        $select->where('am.cd_medicao    = ?', $cd_medicao, Zend_Db::INT_TYPE);
        $select->where('am.cd_box_inicio = ?', $cd_box_inicio, Zend_Db::INT_TYPE);

        return $this->fetchRow($select);
	}

    /**
     *
     * @param int $condicao 0 ou 1
     * @return Zend_Db_Table_RowSet
     */
	public function getAnaliseMedicaoDecisao($condicao)
	{
        $condicao = ($condicao === 1) ? 'IS NULL' : 'IS NOT NULL';
        
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('am'=>$this->_name),
                      array('cd_medicao',
                            'cd_box_inicio',
                            'dt_analise_medicao',
                            'dt_decisao',
                            'st_decisao_executada'),
                      $this->_schema);
        $select->join(array('m'=>KT_S_MEDICAO),
                      '(am.cd_medicao = m.cd_medicao)',
                      array('tx_medicao'),
                      $this->_schema);
        $select->join(array('bi'=>KT_B_BOX_INICIO),
                      '(am.cd_box_inicio = bi.cd_box_inicio)',
                      array('tx_titulo_box_inicio'),
                      $this->_schema);
        $select->where("am.tx_decisao {$condicao}");
        $select->order('dt_analise_medicao');

        return $this->fetchAll($select);
	}
	
}