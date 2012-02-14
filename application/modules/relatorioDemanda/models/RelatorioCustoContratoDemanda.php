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

class RelatorioCustoContratoDemanda extends Base_Db_Table_Abstract {
    protected $_schema;
    protected $_objTable;

    public function __construct() {
        parent::__construct();
        $this->_objTable = new Contrato();
        $this->_schema   = K_SCHEMA;
    }

    public function getDadosContrato(Array $arrParam = array()) {
        $select = $this->_objTable->select()->setIntegrityCheck(false);

        $select->from(array('cont'=>KT_S_CONTRATO),
            array('tx_numero_contrato',
            'nf_valor_contrato',
            'ni_qtd_meses_contrato',
            'ni_mes_inicial_contrato',
            'ni_ano_inicial_contrato',
            'ni_mes_final_contrato',
            'ni_ano_final_contrato'),
            $this->_schema);
        $select->join(array('obj'=>KT_S_OBJETO_CONTRATO),
            'cont.cd_contrato = obj.cd_contrato',
            array('tx_objeto',
            'valor_contrato_demanda_mes'=>'(cont.nf_valor_contrato/cont.ni_qtd_meses_contrato)'),
            $this->_schema);

        $this->_mountWhere($arrParam, $select);
        
        return $this->_objTable->fetchAll($select);
    }

    public function getDadosContratoPeriodo(Array $arrParam = array())
    {
        $select = $this->_objTable->select()->setIntegrityCheck(false);
        $select->from(array('ccd'=>KT_S_CUSTO_CONTRATO_DEMANDA),
                      array('nf_total_multa',
                            'nf_total_glosa',
                            'nf_total_pago',
                            'ni_mes_custo_contrato_demanda',
                            'ni_ano_custo_contrato_demanda'),
                      $this->_schema);
                  
       $this->_mountWhere($arrParam, $select);
       $select->order(array('ni_mes_custo_contrato_demanda','ni_ano_custo_contrato_demanda'));
       
       return $this->_objTable->fetchAll($select);
    }
}