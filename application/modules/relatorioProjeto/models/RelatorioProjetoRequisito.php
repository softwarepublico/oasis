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

class RelatorioProjetoRequisito extends Zend_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;
	private $_objTable;

    public function init()
    {
        parent::init();
        $this->_objTable = new Requisito();
    }

    public function getRequisito($cd_projeto)
    {
        $select = $this->_objTable->select()
                                  ->setIntegrityCheck(false);
        $select->from(array('req'=>KT_S_REQUISITO),
                      array('cd_requisito',
                            'tx_requisito',
                            'ni_versao_requisito',
                            'ni_ordem'),
                      $this->_schema);

        $subSelect = $this->_objTable->select()
                                     ->from($this->_objTable,
                                            array('cd_requisito',
                                                  'dt_versao_requisito'=>new Zend_Db_Expr('MAX(dt_versao_requisito)')),
                                            $this->_schema)
                                     ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                                     ->group('cd_requisito');
                                     
        $select->join(array('maxdat'=>$subSelect),
                      '(req.cd_requisito        = maxdat.cd_requisito) AND
                       (req.dt_versao_requisito = maxdat.dt_versao_requisito)',
                      array());
        $select->order('req.cd_requisito');

        return $this->fetchAll($select)->toArray();
    }

    public function getRequisitoDependente($cd_projeto)
    {
        $select = $this->_objTable->select()->setIntegrityCheck(false);

        $select->from(array('reqdep'=>KT_A_REQUISITO_DEPENDENTE),
                      array(),
                      $this->_schema);
        $select->join(array('reqasc'=>KT_S_REQUISITO),
                      '(reqdep.cd_projeto_ascendente          = reqasc.cd_projeto) AND
                       (reqdep.cd_requisito_ascendente        = reqasc.cd_requisito) AND
                       (reqdep.dt_versao_requisito_ascendente = reqasc.dt_versao_requisito)',
                      array('cd_requisito_pai'           =>'cd_requisito',
                            'tx_requisito_pai'           =>'tx_requisito',
                            'ni_ordem_pai'               =>'ni_ordem',
                            'ni_versao_requisito_pai'    =>'ni_versao_requisito',
                            'st_fechamento_requisito_pai'=>'st_fechamento_requisito'),
                      $this->_schema);
        $select->join(array('reqdsc'=>KT_S_REQUISITO),
                      '(reqdep.cd_projeto           = reqdsc.cd_projeto) AND
                       (reqdep.cd_requisito         = reqdsc.cd_requisito) AND
                       (reqdep.dt_versao_requisito  = reqdsc.dt_versao_requisito)',
                      array('cd_requisito_filho'           =>'cd_requisito',
                            'tx_requisto_filho'            =>'ni_ordem',
                            'ni_ordem_filho'               =>'ni_ordem',
                            'ni_versao_requisito_filho'    =>'ni_versao_requisito',
                            'st_fechamento_requisito_filho'=>'st_fechamento_requisito'),
                      $this->_schema);
        $select->where('reqdep.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
        $select->where('reqdep.st_inativo IS NULL');
        $select->order(array('cd_requisito_pai',
                             'cd_requisito_filho'));

        return $this->fetchAll($select)->toArray();
    }
}