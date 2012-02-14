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

class ProfissionalProduto extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_A_PROFISSIONAL_PRODUTO;
	protected $_primary  = array('cd_profissional',
                                 'cd_produto_parcela',
                                 'cd_proposta',
                                 'cd_projeto',
                                 'cd_parcela');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;


    public function pesquisaProfissionalSemProduto(array $params)
    {
        $select = $this->_montaSelectProdutoProfissional($params);

        $select->where('profprod.cd_profissional IS NULL');

        return $this->fetchAll($select);
    }

    public function pesquisaProfissionalComProduto(array $params)
    {
        $select = $this->_montaSelectProdutoProfissional($params);

        $select->where('profprod.cd_profissional IS NOT NULL');

        return $this->fetchAll($select);
    }

    private function _montaSelectProdutoProfissional(array $params)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('prof'=>KT_S_PROFISSIONAL),
                      array('cd_profissional',
                            'tx_profissional'),
                      $this->_schema);

        $subSelect2 = $this->select()->setIntegrityCheck(false)->distinct()
                          ->from(array('a' => KT_A_PROFISSIONAL_PROJETO), array('cd_profissional'), $this->_schema)
                          ->where("cd_projeto = ?", $params['cd_projeto' ], Zend_Db::INT_TYPE);

        $subSelect = $this->select()
                          ->from($this, array('cd_profissional'), $this->_schema)
                          ->where("cd_projeto         = ?", $params['cd_projeto' ], Zend_Db::INT_TYPE)
                          ->where("cd_proposta        = ?", $params['cd_proposta'], Zend_Db::INT_TYPE)
                          ->where("cd_parcela         = ?", $params['cd_parcela' ], Zend_Db::INT_TYPE)
                          ->where("cd_produto_parcela = ?", $params['cd_produto' ], Zend_Db::INT_TYPE);

        $select->join(array('profproj'=>$subSelect2),
                          'prof.cd_profissional = profproj.cd_profissional',
                          array());
        
        $select->joinLeft(array('profprod'=>$subSelect),
                          'profprod.cd_profissional = prof.cd_profissional',
                          array());

        $select->order('tx_profissional');

        return $select;
    }

    public function addProfissionalProduto($cd_projeto, $cd_proposta, $cd_parcela, $cd_produto, $profissional )
    {
        $novo                     = $this->createRow();
        $novo->cd_projeto         = $cd_projeto;
        $novo->cd_proposta        = $cd_proposta;
        $novo->cd_parcela         = $cd_parcela;
        $novo->cd_produto_parcela = $cd_produto;
        $novo->cd_profissional    = $profissional;
        $novo->save();
    }

    public function removeProfissionalProduto($cd_projeto, $cd_proposta, $cd_parcela, $cd_produto, $profissional )
    {
        //trata os parametros do where
        $where  = "cd_projeto         = {$this->getDefaultAdapter()->quote($cd_projeto,      'INTEGER')} and ";
        $where .= "cd_proposta        = {$this->getDefaultAdapter()->quote($cd_proposta,     'INTEGER')} and ";
        $where .= "cd_parcela         = {$this->getDefaultAdapter()->quote($cd_parcela,      'INTEGER')} and ";
        $where .= "cd_produto_parcela = {$this->getDefaultAdapter()->quote($cd_produto,      'INTEGER')} and ";
        $where .= "cd_profissional    = {$this->getDefaultAdapter()->quote($profissional,    'INTEGER')}";

        $this->delete($where);
    }

}