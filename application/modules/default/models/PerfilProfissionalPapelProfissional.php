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

class PerfilProfissionalPapelProfissional extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_A_PERFIL_PROF_PAPEL_PROF;
	protected $_primary  = array('cd_perfil_profissional', 'cd_papel_profissional');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function pesquisaPapelProfissionalForaPerfilProfissional($cd_area_atuacao_ti, $cd_perfil_profissional)
	{

        $select = $this->select()
                       ->setIntegrityCheck(false);
        
        $select->from(array('papel'=>KT_B_PAPEL_PROFISSIONAL),
                      array('cd_papel_profissional',
                            'tx_papel_profissional'),
                      $this->_schema);
        $select->joinLeft(array('t'=>$this->select()
                                          ->from($this, 'cd_papel_profissional')
                                          ->where('cd_perfil_profissional = ?', $cd_perfil_profissional, Zend_Db::INT_TYPE)),
                          '(papel.cd_papel_profissional = t.cd_papel_profissional)',
                          array());
        $select->where('papel.cd_area_atuacao_ti = ?', $cd_area_atuacao_ti, Zend_Db::INT_TYPE);
        $select->where('t.cd_papel_profissional IS NULL');
        $select->order('tx_papel_profissional');

        return $this->fetchAll($select);
	}

	public function pesquisaPapelProfissionalNoPerfilProfissional($cd_area_atuacao_ti, $cd_perfil_profissional)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('papel'=>KT_B_PAPEL_PROFISSIONAL),
                      array('cd_papel_profissional',
                            'tx_papel_profissional'),
                      $this->_schema);
        $select->joinLeft(array('t'=>$this->select()
                                          ->from($this, 'cd_papel_profissional')
                                          ->where('cd_perfil_profissional = ?', $cd_perfil_profissional, Zend_Db::INT_TYPE)),
                          '(papel.cd_papel_profissional = t.cd_papel_profissional)',
                          array());
        $select->where('papel.cd_area_atuacao_ti = ?', $cd_area_atuacao_ti, Zend_Db::INT_TYPE);
        $select->where('t.cd_papel_profissional IS NOT NULL');
        $select->order('tx_papel_profissional');

        return $this->fetchAll($select);
	}

	public function getAssociacaoPerfilProfissional( $cd_perfil_profissional )
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('pppp'=>$this->_name),
                      array(),
                      $this->_schema);
        $select->join(array('pp'=>KT_B_PAPEL_PROFISSIONAL),
                      '(pppp.cd_papel_profissional = pp.cd_papel_profissional)',
                      array('cd_papel_profissional','tx_papel_profissional'),
                      $this->_schema);
        $select->where('pppp.cd_perfil_profissional = ?', $cd_perfil_profissional, Zend_Db::INT_TYPE);

        return $this->fetchAll($select)->toArray();
	}

	public function getAssociacaoPapelProfissional( $cd_papel_profissional )
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('pppp'=>$this->_name),
                      array(),
                      $this->_schema);
        $select->join(array('pp'=>KT_B_PERFIL_PROFISSIONAL),
                      '(pppp.cd_perfil_profissional = pp.cd_perfil_profissional)',
                      array('cd_perfil_profissional', 'tx_perfil_profissional'),
                      $this->_schema);
        $select->where('pppp.cd_papel_profissional = ?', $cd_papel_profissional, Zend_Db::INT_TYPE);

        return $this->fetchAll($select)->toArray();
	}

}