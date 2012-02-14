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

class ProjetoPrevisto extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_PROJETO_PREVISTO;
	protected $_primary  = array('cd_projeto_previsto', 'cd_contrato');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    /**
     *
     * @param int $cd_contrato
     * @param array $arrProjetos    Ex: array('0'=>186,'1'=>190)
     * @return Zend_Db_Table_RowSet
     */
	public function getProjetoPrevisto($cd_contrato, array $arrProjetos = array())
	{
		$arrProjetoPrevisto = array();
		
		$select = $this->select()
                       ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);

        if(count($arrProjetos) > 0){
            $select->where("cd_projeto_previsto in (?)", $arrProjetos);
        }
		$select->order("tx_projeto_previsto");

		return $this->fetchAll($select);
	}

    /**
     * Método utilizado para recuperar os projetos previstos
     * 
     * @param int $cd_contrato
     * @return Zend_Db_Table_RowSet
     */
	public function getGridProjetoPrevisto($cd_contrato)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('pp'=>$this->_name),
                      array('cd_projeto_previsto',
                            'cd_contrato',
                            'cd_unidade',
                            'tx_projeto_previsto',
                            'ni_horas_projeto_previsto',
                            'st_projeto_previsto'=>new Zend_Db_Expr("case when pp.st_projeto_previsto = 'N' then '".Base_Util::getTranslator('L_SQL_NOVO')."'
                                                                          when pp.st_projeto_previsto = 'E' then '".Base_Util::getTranslator('L_SQL_EVOLUTIVO')."'
                                                                     end")),
                      $this->_schema);
        $select->join(array('c'=>KT_S_CONTRATO),
                     '(pp.cd_contrato = c.cd_contrato)',
                     'tx_numero_contrato',
                     $this->_schema);
        $select->joinLeft(array('u'=>KT_B_UNIDADE),
                          '(pp.cd_unidade = u.cd_unidade)',
                          'tx_sigla_unidade',
                          $this->_schema);
        $select->joinLeft(array('dm'=>KT_B_DEFINICAO_METRICA),
                          '(pp.cd_definicao_metrica = dm.cd_definicao_metrica)',
                          'tx_sigla_metrica',
                          $this->_schema);
        $select->where('pp.cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);
        $select->order('pp.tx_projeto_previsto');
        
        return $this->fetchAll($select);
	}

    /**
     * Método para recuperar os dados de um determinado projeto previsto
     * @param int $cd_projeto_previsto
     * @return Zend_Db_Table_Row
     */
	public function getRowProjetoPrevisto($cd_projeto_previsto)
	{
		return $this->fetchRow($this->select()->where("cd_projeto_previsto = ?",$cd_projeto_previsto, Zend_Db::INT_TYPE));
	}
}