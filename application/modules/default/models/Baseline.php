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

class Baseline extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_BASELINE;
	protected $_primary  = array('dt_baseline', 'cd_projeto');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
    /**
     *
     * @param int $cd_projeto
     * @param string $dt_baseline OPCIONAL
     * @return Zend_Db_Table_RowSet
     */
	public function getBaseline($cd_projeto, $dt_baseline=null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from($this,
                      array('cd_projeto',
                            'dt_baseline',
                            'st_ativa',
                            'st_ativa_desc'=>new Zend_Db_Expr("CASE WHEN st_ativa IS NOT NULL THEN '".Base_Util::getTranslator('L_SQL_ATIVA')."'
                                                                                              ELSE '".Base_Util::getTranslator('L_SQL_INATIVA')."' END")),
                      $this->_schema);
        $select->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
        $select->order('dt_baseline DESC');

        if(!is_null($dt_baseline))
            $select->where('dt_baseline = ?', $dt_baseline);

        return $this->fetchAll($select);
	}

    /**
     *
     * @param int $cd_projeto
     * @return Zend_Db_Table_RowSet
     */
	public function getDadosBaselineRelatorio( $cd_projeto )
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('b'=>$this->_name),
                      array('cd_projeto',
                            'dt_baseline',
                            'st_ativa'=>new Zend_Db_Expr("CASE WHEN st_ativa IS NOT NULL THEN '".Base_Util::getTranslator('L_SQL_ATIVA')."'
                                                                                         ELSE '".Base_Util::getTranslator('L_SQL_INATIVA')."' END")),
                      $this->_schema);
        $select->join(array('p'=>KT_S_PROJETO),
                      '(b.cd_projeto = p.cd_projeto)',
                      array('tx_sigla_projeto','tx_projeto'),
                      $this->_schema);
		if($cd_projeto === 0 ){//quando for selecionado todos os projetos trazer apenas a baseline ativa de cada projeto
            $select->where('st_ativa = ?', 'S');
            $select->order('tx_sigla_projeto');
		}else{
            $select->where('b.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
            $select->order('dt_baseline DESC');
		}
        return $this->fetchAll($select);
	}

    /**
     *
     * @param int $cd_projeto
     * @param string $status
     * @return Zend_Db_Table_RowSet
     */
	public function getBaselineAtivaInativa( $cd_projeto, $status )
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('b'=>$this->_name),
                      array('dt_baseline',
                            'st_ativa'=>new Zend_Db_Expr("CASE WHEN st_ativa IS NOT NULL THEN '".Base_Util::getTranslator('L_SQL_ATIVA')."'
                                                                                         ELSE '".Base_Util::getTranslator('L_SQL_INATIVA')."' END")),
                      $this->_schema);
        $select->join(array('p'=>KT_S_PROJETO),
                      '(b.cd_projeto = p.cd_projeto)',
                      array('tx_sigla_projeto','tx_projeto'),
                      $this->_schema);
        $select->where('b.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
        $select->order('dt_baseline DESC');

		if( $status === "A")
            $select->where('b.st_ativa IS NOT NULL');
		else
            $select->where('b.st_ativa IS NULL');

        return $this->fetchAll($select);
	}
}