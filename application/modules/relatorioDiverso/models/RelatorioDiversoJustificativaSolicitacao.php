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

class RelatorioDiversoJustificativaSolicitacao extends Base_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;

    public function getDadosRelatorioJustificativaSolicitacao(array $arrParam=array())
    {
        $objTable = new Solicitacao();

        $select = $objTable->select()
                           ->setIntegrityCheck(false)
                           ->from(array('sol'=>KT_S_SOLICITACAO),
                                  array('cd_objeto',
                                        'solicitacao' =>new Zend_Db_Expr("ni_solicitacao{$this->concat()}'/'{$this->concat()}ni_ano_solicitacao"),
                                        'ni_solicitacao',
                                        'ni_ano_solicitacao',
                                        'dt_solicitacao',
                                        'tx_solicitante',
                                        'tx_solicitacao',
                                        'dt_justificativa',
                                        'tx_justificativa_solicitacao',
                                        'st_aceite_just_solicitacao'=>new Zend_Db_Expr("case st_aceite_just_solicitacao
                                                                                                      when 'S' then '".Base_Util::getTranslator('L_SQL_ACEITA')."'
                                                                                                      when 'N' then '".Base_Util::getTranslator('L_SQL_NAO_ACEITA')."'
                                                                                                               else '".Base_Util::getTranslator('L_SQL_PENDENTE')."' end"),
                                        'tx_obs_aceite_just_solicitacao'),
                                  $this->_schema);

        $select->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                      '(sol.cd_objeto = oc.cd_objeto)',
                      'tx_objeto',
                      $this->_schema);
        $select->order('dt_solicitacao');

        //adiciona as condições ao select
        $this->_mountWhere($arrParam, $select);

        return $objTable->fetchAll($select);
    }
}