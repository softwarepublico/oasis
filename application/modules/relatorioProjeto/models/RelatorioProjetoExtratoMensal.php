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

class RelatorioProjetoExtratoMensal extends Zend_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;
	
    /**
     * Método que executa a query do relatório
     *
     * @param ARRAY $params
     * @return ARRAY $arrResult
     */
    public function getExtratoMensal( array $params )
    {
        $objTable = new Parcela();

        $select = $objTable->select()
                           ->setIntegrityCheck(false);

        $select->from(array('parc'=>KT_S_PARCELA),
                      array('parc.ni_parcela',
                            'parc.cd_proposta',
                            'parc.ni_horas_parcela'),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(parc.cd_projeto = proj.cd_projeto)',
                      array('cd_projeto','tx_sigla_projeto'),
                      $this->_schema);
        $select->join(array('pp'=>KT_S_PROCESSAMENTO_PARCELA),
                      '(parc.cd_projeto  = pp.cd_projeto)  and
                       (parc.cd_proposta = pp.cd_proposta) and
                       (parc.cd_parcela  = pp.cd_parcela)',
                      array(),
                      $this->_schema);
        $select->join(array('cp'=>KT_A_CONTRATO_PROJETO),
                      '(cp.cd_projeto = proj.cd_projeto)',
                      array(),
                      $this->_schema);
        $select->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                      '(oc.cd_objeto = pp.cd_objeto_execucao)',
                      array(),
                      $this->_schema);
        $select->where('cp.cd_contrato = ?'              , $params['cd_contrato'], Zend_Db::INT_TYPE)
               ->where('parc.ni_mes_previsao_parcela = ?', $params['mes'], Zend_Db::INT_TYPE)
               ->where('parc.ni_ano_previsao_parcela = ?', $params['ano'], Zend_Db::INT_TYPE)
               ->where('pp.st_ativo = ?', 'S')
               ->where('pp.st_homologacao_parcela = ?', 'A');

        $select->order('tx_sigla_projeto')
               ->order('ni_parcela');
     
		return $objTable->fetchAll($select)->toArray();
        
    }

    
    
    public function getExtratoMensalDemanda( array $params )
    {
        
        $objTable = new CustoContratoDemandaPorMetrica();

        $select = $objTable->select()
                           ->setIntegrityCheck(false);

        $select->from(array('parc'=>KT_S_CUSTO_CONTRATO_DEMANDA_POR_METRICA),
                            array('cd_custo_demanda_por_metrica',
                            'cd_contrato',
                            'nf_qtd_unidade_metrica',
                             'dt_registro'=> new Zend_Db_Expr("to_char(dt_registro, 'DD/MM/YYYY')")),
                      $this->_schema);
        $select->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                      '(oc.cd_contrato = parc.cd_contrato)',
                      array(),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(parc.cd_projeto = proj.cd_projeto)',
                      array('cd_projeto','tx_sigla_projeto'),
                      $this->_schema);
       
        $select->where('parc.cd_contrato = ?'         , $params['cd_contrato'], Zend_Db::INT_TYPE)
               ->where('parc.ni_mes = ?'              , $params['mes'], Zend_Db::INT_TYPE)
               ->where('parc.ni_ano = ?'              , $params['ano'], Zend_Db::INT_TYPE);
               //->where('pp.st_ativo = ?'              , 'S');
               //->where('pp.st_homologacao_parcela = ?', 'A');

        $select->order('tx_sigla_projeto')
               ->order('cd_custo_demanda_por_metrica');

        return $objTable->fetchAll($select)->toArray();
        
    }
}