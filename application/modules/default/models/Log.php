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

class Log extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_LOG;
	protected $_primary  = array('cd_log', 'dt_ocorrencia');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getRegistroLog( array $arrDados )
	{
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('log'=>$this->_name),
                      array('tx_msg_log',
                            'tx_controller',
                            'tx_tabela',
                            'tx_ip',
                            'dt_ocorrencia',
                            'tx_host'),
                      $this->_schema);

		$select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(log.cd_profissional = prof.cd_profissional)',
                      array('cd_profissional','tx_profissional'),
                      $this->_schema);
        $select->where('dt_ocorrencia >= ?', $arrDados['dt_inicial'].' 00:00:00')
               ->where('dt_ocorrencia <= ?', $arrDados['dt_final'  ].' 23:59:59');
        $select->order(array('tx_profissional','dt_ocorrencia DESC'));

		if(!is_null($arrDados['cd_profissional'])){
            $select->where('log.cd_profissional = ?', $arrDados['cd_profissional'], Zend_Db::INT_TYPE);
		}
		if(!is_null($arrDados['tx_tabela'])){
            $select->where('log.tx_tabela = ?', $arrDados['tx_tabela']);
		}
		if(!is_null($arrDados['tx_ip'])){
            $select->where('log.tx_ip = ?', $arrDados['tx_ip']);
		}

		return $this->fetchAll($select)->toArray();
	}
}