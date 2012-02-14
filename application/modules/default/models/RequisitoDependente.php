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

class RequisitoDependente extends Base_Db_Table_Abstract 
{
	protected $_name 	 = KT_A_REQUISITO_DEPENDENTE;
	protected $_primary  = array('cd_projeto','dt_versao_requisito','cd_requisito','cd_projeto_ascendente','cd_requisito_ascendente','dt_versao_requisito_ascendente');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    /**
     *  Método para recuperar os requisitos não associados a um requisito pai
     *
     * @param Integer $cd_projeto
     * @param Integer $cd_requisito
     * @param String $dt_versao_requisito formato YYYY-MM-DD HH:MM:SS
     *
     * @return Array
     */
	public function getRequisitoNaoAssociados( $cd_projeto, $cd_requisito, $dt_versao_requisito )
	{
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('req'=>KT_S_REQUISITO),
                      array('cd_requisito',
                            'dt_versao_requisito',
                            'tx_requisito' => new Zend_Db_Expr("tx_requisito {$this->concat()} ' (".Base_Util::getTranslator('L_SQL_VERSAO')." ' {$this->concat()} ni_versao_requisito {$this->concat()} ')'")),
                      $this->_schema);

        $select->join(array('maxdata'=>$this->_montaSelectUltimaVersaoRequisito($cd_projeto, $cd_requisito)),
                      '(req.cd_requisito        = maxdata.cd_requisito) AND
                       (req.dt_versao_requisito = maxdata.dt_versao_requisito)',
                       array());
        $select->joinLeft(array('rd'=>$this->_montaSelectRequisitoDependente($cd_projeto, $cd_requisito, $dt_versao_requisito)),
                          '(req.cd_projeto          = rd.cd_projeto) AND
                           (req.cd_requisito        = rd.cd_requisito) AND
                           (req.dt_versao_requisito = rd.dt_versao_requisito)',
                          array());
        $select->where('req.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
        $select->where('rd.cd_requisito        IS NULL');
        $select->where('rd.cd_projeto          IS NULL');
        $select->where('rd.dt_versao_requisito IS NULL');
        $select->order('tx_requisito');

        return $this->fetchAll($select)->toArray();
	}

    private function _montaSelectUltimaVersaoRequisito($cd_projeto, $cd_requisito)
    {
        $objTable = new Requisito();
        $select = $objTable->select()
                           ->from($objTable,
                                  array('cd_requisito',
                                        'dt_versao_requisito' => new Zend_Db_Expr('max(dt_versao_requisito)')))
                           ->where('cd_projeto    = ?', $cd_projeto, Zend_Db::INT_TYPE)  //o projeto em questao
                           ->where('cd_requisito <> ?', $cd_requisito, Zend_Db::INT_TYPE)//Requisito PAI escolhido no combo
                           ->group('cd_requisito');
        return $select;
    }

    private function _montaSelectRequisitoDependente($cd_projeto, $cd_requisito, $dt_versao_requisito)
    {
        $select = $this->select()
                       ->from($this,
                              array('cd_requisito',
                                    'cd_projeto',
                                    'st_inativo',
                                    'dt_versao_requisito'))
                       ->where('cd_projeto_ascendente   = ?', $cd_projeto, Zend_Db::INT_TYPE)  //o projeto em questao
                       ->where('cd_requisito_ascendente = ?', $cd_requisito, Zend_Db::INT_TYPE)//Requisito PAI escolhido no combo
                       ->where('dt_versao_requisito_ascendente = ?', $dt_versao_requisito);
        return $select;
    }

    /**
     *  Método para recuperar os requisitos associados a um requisito pai
     *
     * @param Integer $cd_projeto
     * @param Integer $cd_requisito
     * @param String $dt_versao_requisito formato YYYY-MM-DD HH:MM:SS
     *
     * @return Array
     */
	public function getRequisitoAssociados( $cd_projeto, $cd_requisito, $dt_versao_requisito )
	{
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('req'=>KT_S_REQUISITO),
                      array('cd_requisito',
                            'dt_versao_requisito',
                            'tx_requisito' => new Zend_Db_Expr("tx_requisito {$this->concat()} ' (".Base_Util::getTranslator('L_SQL_VERSAO')." ' {$this->concat()} ni_versao_requisito {$this->concat()} ')'")),
                      $this->_schema);

        $select->joinLeft(array('rd'=>$this->_montaSelectRequisitoDependente($cd_projeto, $cd_requisito, $dt_versao_requisito)),
                          '(req.cd_projeto          = rd.cd_projeto) AND
                           (req.cd_requisito        = rd.cd_requisito) AND
                           (req.dt_versao_requisito = rd.dt_versao_requisito)',
                          array());
        $select->where('req.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
        $select->where('rd.cd_requisito        IS NOT NULL');
        $select->where('rd.cd_projeto          IS NOT NULL');
        $select->where('rd.dt_versao_requisito IS NOT NULL');
        $select->where('rd.st_inativo          IS NULL');
        $select->order('tx_requisito');

        return $this->fetchAll($select)->toArray();
	}

	public function getDadosVersaoRequisitoAnterior( $cd_projeto, $cd_requisito, $dt_versao_requisito )
	{
        $select = $this->select()
                       ->where('cd_requisito_ascendente		   = ?', $cd_requisito, Zend_Db::INT_TYPE)
                       ->where('cd_projeto_ascendente		   = ?', $cd_projeto, Zend_Db::INT_TYPE)
                       ->where('dt_versao_requisito_ascendente = ?', $dt_versao_requisito)
                       ->where('st_inativo IS NULL');

        return $this->fetchRow($select)->toArray();
	}
}