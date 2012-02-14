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

class RelatorioProjetoRegraDeNegocio extends Zend_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;

    public function getRegraDeNegocioDependente( $cd_projeto )
    {
        $objTable = new RegraNegocio();

        $select   = $objTable->select()
                             ->setIntegrityCheck(false);
        $select->from(array('rnr'=>KT_A_REGRA_NEGOCIO_REQUISITO),
                      array(),
                      $this->_schema);
        $select->join(array('reqasc'=>KT_S_REQUISITO),
                      '(rnr.cd_projeto          = reqasc.cd_projeto) AND
					   (rnr.cd_requisito        = reqasc.cd_requisito) AND
					   (rnr.dt_versao_requisito = reqasc.dt_versao_requisito)',
                      array('cd_requisito_pai'           =>'cd_requisito',
                            'tx_requisito_pai'           =>'tx_requisito',
                            'ni_ordem_pai'               =>'ni_ordem',
                            'ni_versao_requisito_pai'    =>'ni_versao_requisito',
                            'st_fechamento_requisito_pai'=>'st_fechamento_requisito'),
                      $this->_schema);
        $select->join(array('rn'=>KT_S_REGRA_NEGOCIO),
                      '(rnr.cd_projeto_regra_negocio = rn.cd_projeto_regra_negocio) AND
                       (rnr.cd_regra_negocio         = rn.cd_regra_negocio)         AND
                       (rnr.dt_regra_negocio         = rn.dt_regra_negocio)',
                      array('cd_regra_negocio_filho'           =>'cd_regra_negocio',
                            'tx_regra_negocio_filho'           =>'tx_regra_negocio',
//                            'ni_ordem_filho'                   =>'ni_ordem', //
                            'ni_versao_regra_negocio_filho'    =>'ni_versao_regra_negocio',
                            'st_fechamento_regra_negocio_filho'=>'st_fechamento_regra_negocio'),
                      $this->_schema);
        $select->where('rnr.cd_projeto = ?',$cd_projeto, Zend_Db::INT_TYPE);
        $select->where('rnr.st_inativo is null');
        $select->order(array('cd_requisito_pai',
                             'cd_regra_negocio_filho'));

        return $this->fetchAll($select)->toArray();
    }
    
    public function getRegraDeNegocioMatrizRastreabilidade( $cd_projeto )
    {
        $objTable = new RegraNegocio();

        $select   = $objTable->select()
                             ->setIntegrityCheck(false);
        $select->from(array('rdn'=>KT_S_REGRA_NEGOCIO),
                      array('cd_regra_negocio',
                            'tx_regra_negocio',
                            'ni_versao_regra_negocio',
                            'ni_ordem_regra_negocio'),
                      $this->_schema);

        $subSelect = $objTable->select()
                              ->from($objTable,
                                     array('cd_regra_negocio',
                                           'dt_regra_negocio'=>'max(dt_regra_negocio)'))
                              ->where('cd_projeto_regra_negocio = ?', $cd_projeto, Zend_Db::INT_TYPE)
                              ->group('cd_regra_negocio');

        $select->join(array('maxdat'=>$subSelect),
                            '(rdn.cd_regra_negocio = maxdat.cd_regra_negocio) AND
                             (rdn.dt_regra_negocio = maxdat.dt_regra_negocio)',
                      array());
        $select->order('cd_regra_negocio');

        return $this->fetchAll($select)->toArray();
    }
}