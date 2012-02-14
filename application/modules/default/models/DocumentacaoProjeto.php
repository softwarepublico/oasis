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

class DocumentacaoProjeto extends Base_Db_Table_Abstract 
{
	protected $_name     = KT_A_DOCUMENTACAO_PROJETO;
	protected $_primary  = array('cd_projeto', 'cd_tipo_documentacao', 'dt_documentacao_projeto');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getDadosDocumentacaoProjeto($cd_projeto = null,$st_documentacao_controle = null)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('dp'=>$this->_name),
                      array('cd_projeto',
                            'cd_tipo_documentacao',
                            'dt_documentacao_projeto',
                            'tx_arq_documentacao_projeto',
                            'tx_nome_arquivo'),
                      $this->_schema);
        $select->join(array('td'=>KT_B_TIPO_DOCUMENTACAO),
                      '(dp.cd_tipo_documentacao = td.cd_tipo_documentacao)',
                      'tx_tipo_documentacao',
                      $this->_schema);
        $select->order('dt_documentacao_projeto DESC');

		if(!is_null($st_documentacao_controle))
            $select->where('dp.st_documentacao_controle IS NOT NULL');
        else
            $select->where('dp.st_documentacao_controle IS NULL');
        if(!is_null($cd_projeto))
            $select->where('dp.cd_projeto = ?',$cd_projeto, Zend_Db::INT_TYPE);

        return $this->fetchAll($select)->toArray();
	}

    public function getDadosDocumentacaoProjetoAcompanhamento($cd_projeto = null)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('dp'=>$this->_name),
                      array('cd_projeto',
                            'cd_tipo_documentacao',
                            'dt_documentacao_projeto',
                            'tx_arq_documentacao_projeto',
                            'tx_nome_arquivo'),
                      $this->_schema);
        $select->join(array('td'=>KT_B_TIPO_DOCUMENTACAO),
                      '(dp.cd_tipo_documentacao = td.cd_tipo_documentacao)',
                      'tx_tipo_documentacao',
                      $this->_schema);
        $select->where('dp.st_doc_acompanhamento IS NOT NULL');
        $select->order('dt_documentacao_projeto DESC');

        if(!is_null($cd_projeto))
            $select->where('dp.cd_projeto = ?',$cd_projeto, Zend_Db::INT_TYPE);

		return $this->fetchAll($select);
	}

	public function getDadosUltimaDocumentacaoProjeto($cd_projeto, $cd_tipo_documentacao)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('dp'=>$this->_name),
                      array('cd_projeto',
                            'cd_tipo_documentacao',
                            'dt_documentacao_projeto',
                            'tx_arq_documentacao_projeto',
                            'tx_nome_arquivo'),
                      $this->_schema);
        $select->join(array('td'=>KT_B_TIPO_DOCUMENTACAO),
                      '(dp.cd_tipo_documentacao = td.cd_tipo_documentacao)',
                      'tx_tipo_documentacao',
                      $this->_schema);
        $select->where('dp.cd_projeto = ?',$cd_projeto, Zend_Db::INT_TYPE)
               ->where('dp.cd_tipo_documentacao = ?',$cd_tipo_documentacao, Zend_Db::INT_TYPE)
               ->where('dp.st_documentacao_controle IS NULL')
               ->where('dp.dt_documentacao_projeto = ?', $this->select()
                                                                ->setIntegrityCheck(false)
                                                                ->from(array('dpmax'=>$this->_name),
                                                                              new Zend_Db_Expr('MAX(dpmax.dt_documentacao_projeto)'),
                                                                              $this->_schema)
                                                                ->join(array('tdmax'=>KT_B_TIPO_DOCUMENTACAO),
                                                                              '(dpmax.cd_tipo_documentacao = tdmax.cd_tipo_documentacao)',
                                                                              array(),
                                                                              $this->_schema)
                                                                ->where('dpmax.cd_projeto = ?',$cd_projeto, Zend_Db::INT_TYPE)
                                                                ->where('dpmax.cd_tipo_documentacao = ?',$cd_tipo_documentacao, Zend_Db::INT_TYPE));
        $select->order('dt_documentacao_projeto DESC');
        return $this->fetchAll($select)->toArray();
	}
	
	public function getDocumentoProposta($dt_documentacao_projeto, $cd_projeto, $cd_tipo_documentacao)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('dp'=>$this->_name),
                      array('cd_projeto',
                            'cd_tipo_documentacao',
                            'dt_documentacao_projeto',
                            'tx_arq_documentacao_projeto',
                            'tx_nome_arquivo'),
                      $this->_schema);
        $select->join(array('pro'=>KT_S_PROJETO),
                      '(dp.cd_projeto = pro.cd_projeto)',
                      array(),
                      $this->_schema);
        $select->where('dp.cd_projeto = ?',$cd_projeto, Zend_Db::INT_TYPE)
               ->where('dp.cd_tipo_documentacao = ?',$cd_tipo_documentacao, Zend_Db::INT_TYPE)
               ->where('dt_documentacao_projeto = ?', $dt_documentacao_projeto);

        return $this->fetchAll($select)->toArray();
	}
}