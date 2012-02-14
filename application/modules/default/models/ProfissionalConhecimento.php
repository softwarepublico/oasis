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

class ProfissionalConhecimento extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_PROFISSIONAL_CONHECIMENTO;
	protected $_primary  = array('cd_profissional','cd_tipo_conhecimento','cd_conhecimento');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    /**
     * Método utilizado para recuperar os conhecimentos dos profissional
     * 
     * @param int $cd_profissional
     * @param int $cd_tipo_conhecimento
     * @param bool $utilizado
     * @return Zend_Db_Table_RowSet
     */
	public function getConhecimentoProfissional( $cd_profissional , $cd_tipo_conhecimento ,$utilizado=false)
	{
	    $not = ($utilizado) ? 'NOT' : '';

        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('c'=>KT_B_CONHECIMENTO),
                     array('cd_tipo_conhecimento','cd_conhecimento','tx_conhecimento'),
                     $this->_schema);
        $select->join(array('tc'=>KT_B_TIPO_CONHECIMENTO),
                     '(c.cd_tipo_conhecimento = tc.cd_tipo_conhecimento)',
                     array('tx_tipo_conhecimento'),
                     $this->_schema);
        $select->joinLeft(array('aux'=>$this->select()
                                            ->from(array('pc'=>$this->_name),
                                                   array('cd_conhecimento',
                                                         'cd_profissional',
                                                         'cd_tipo_conhecimento'),
                                                   $this->_schema)
                                            ->where('pc.cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE)),
                         '(c.cd_conhecimento = aux.cd_conhecimento)',
                         array());
        $select->where("aux.cd_tipo_conhecimento IS {$not} NULL")
               ->where("aux.cd_conhecimento IS {$not} NULL");
        $select->order(array('tx_tipo_conhecimento',
                             'tx_conhecimento'));
        
        if(!is_null($cd_tipo_conhecimento)){
            $select->where('tc.cd_tipo_conhecimento = ?',$cd_tipo_conhecimento, Zend_Db::INT_TYPE);
        }
        return $this->fetchAll($select);
	}
}