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

class HistoricoPropostaMetrica extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_HISTORICO_PROPOSTA_METRICA;
	protected $_primary  = array('cd_proposta', 'cd_projeto','dt_historico_proposta','cd_definicao_metrica');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    public function getHistoricoPropostaMetrica($cd_projeto, $cd_proposta, $dt_historico_proposta)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array('hpm'=>$this->_name),
                array('*'),
                $this->_schema);
        $select->join(array('dm'=>KT_B_DEFINICAO_METRICA),
                '(hpm.cd_definicao_metrica = dm.cd_definicao_metrica)',
                array('*'),
                $this->_schema);
        $select->where('cd_projeto = ?'           , $cd_projeto, Zend_Db::INT_TYPE);
        $select->where('cd_proposta = ?'          , $cd_proposta, Zend_Db::INT_TYPE);
        $select->where('dt_historico_proposta = ?', $dt_historico_proposta);

        return $this->fetchAll($select)->toArray();
    }
}