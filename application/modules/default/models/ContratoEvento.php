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

class ContratoEvento extends Base_Db_Table_Abstract 
{
	protected $_name     = KT_A_CONTRATO_EVENTO;
	protected $_primary  = array('cd_contrato', 'cd_evento');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	/*	Lista os eventos que estão associados ao contrato 
		e os que não estão associados ao contrato selecionado
	*/
	public function pesquisaEventoForaContrato($cd_contrato)
	{
        $select = $this->_montaSelectPesquisaEventoContrato($cd_contrato);
        $select->where('ce.cd_evento IS NULL');
        
        return $this->fetchAll($select)->toArray();
	}

	public function pesquisaEventoNoContrato($cd_contrato)
	{
        $select = $this->_montaSelectPesquisaEventoContrato($cd_contrato);
        $select->where('ce.cd_evento IS NOT NULL');

        return $this->fetchAll($select)->toArray();
	}

    private function _montaSelectPesquisaEventoContrato($cd_contrato)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('eve'=>KT_B_EVENTO),
                      array('cd_evento',
                            'tx_evento'),
                      $this->_schema);
        $select->joinLeft(array('ce'=>$this->select()
                                           ->from($this,'cd_evento')
                                           ->where('cd_contrato = ?',$cd_contrato, Zend_Db::INT_TYPE)),
                          '(eve.cd_evento = ce.cd_evento)',
                          array());
        $select->order('tx_evento');

        return $select;
    }
}