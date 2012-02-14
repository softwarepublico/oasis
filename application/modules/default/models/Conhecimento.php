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

class Conhecimento extends Base_Db_Table_Abstract
{
	protected $_name    = KT_B_CONHECIMENTO;
	protected $_primary = 'cd_conhecimento';
	protected $_schema  = K_SCHEMA;
	protected $_sequence = false;

	public function getDadosConhecimento($cd_tipo_conhecimento)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('c'=>$this->_name),
                      array('cd_conhecimento',
                            'cd_tipo_conhecimento',
                            'tx_conhecimento',
                            'st_padrao',
                            'st_padrao_desc'=>new Zend_Db_Expr("CASE WHEN c.st_padrao = 'S' THEN '".Base_Util::getTranslator('L_SQL_SIM')."' END")),
                      $this->_schema);
        $select->join(array('tc'=>KT_B_TIPO_CONHECIMENTO),
                      '(c.cd_tipo_conhecimento = tc.cd_tipo_conhecimento)',
                      'tx_tipo_conhecimento',
                      $this->_schema);
        $select->where('c.cd_tipo_conhecimento = ?', $cd_tipo_conhecimento, Zend_Db::INT_TYPE);

        return $this->fetchAll($select)->toArray();
	}
	 
	public function getTipoConhecimentoNaoUtilizado($cd_projeto,$cd_tipo_conhecimento = null)
    {
        $select = $this->_montaSelectPesquisaConhecimento();
        
        $select->joinLeft(array('cp'=>$this->select()
                                           ->setIntegrityCheck(false)
                                           ->from(KT_A_CONHECIMENTO_PROJETO,
                                                  '*',
                                                  $this->_schema)
                                           ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)),
                          '(con.cd_conhecimento = cp.cd_conhecimento)',
                          array());
        $select->where('cp.cd_tipo_conhecimento IS NULL');
        $select->where('cp.cd_conhecimento IS NULL');

		if(!is_null($cd_tipo_conhecimento))
            $select->where('tc.cd_tipo_conhecimento = ?', $cd_tipo_conhecimento, Zend_Db::INT_TYPE);

		return $this->fetchAll($select)->toArray();
	}
	
	public function getTipoConhecimentoUtilizado($cd_projeto = null,$cd_tipo_conhecimento = null)
    {
        $subSql = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(KT_A_CONHECIMENTO_PROJETO, '*', $this->_schema);
		if(!is_null($cd_projeto))
            $subSql->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);


        $select = $this->_montaSelectPesquisaConhecimento();
        $select->joinLeft(array('cp'=>$subSql),
                          '(con.cd_conhecimento = cp.cd_conhecimento)',
                          array());
        $select->where('cp.cd_tipo_conhecimento IS NOT NULL');
        $select->where('cp.cd_conhecimento IS NOT NULL');

		if(!is_null($cd_tipo_conhecimento))
            $select->where('tc.cd_tipo_conhecimento = ?', $cd_tipo_conhecimento, Zend_Db::INT_TYPE);

		return $this->fetchAll($select)->toArray();
	}

    private function _montaSelectPesquisaConhecimento()
    {
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('con'=>$this->_name),
                      array('cd_tipo_conhecimento',
                            'cd_conhecimento',
                            'tx_conhecimento',
                            'codigo_conhecimento'=>new Zend_Db_Expr("con.cd_tipo_conhecimento{$this->concat()}'|'{$this->concat()}con.cd_conhecimento")),
                      $this->_schema);
        $select->join(array('tc'=>KT_B_TIPO_CONHECIMENTO),
                      '(con.cd_tipo_conhecimento = tc.cd_tipo_conhecimento)',
                      array('tx_tipo_conhecimento',
                            'desc_tipo_conhecimento'=>new Zend_Db_Expr("tc.tx_tipo_conhecimento{$this->concat()}' - '{$this->concat()}con.tx_conhecimento")),
                      $this->_schema);
        $select->order(array('tx_tipo_conhecimento','tx_conhecimento'));

        return $select;
    }
}