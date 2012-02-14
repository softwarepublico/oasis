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

class RelatorioProjetoAlocacao extends Zend_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;
	
	public function relAlocacaoRecurso($cd_projeto, $cd_proposta)
	{
        $objControle = new Controle();
        $subSelect = $objControle->select()
                                 ->from(array('a'=>KT_A_CONTROLE),
                                        array('cd_projeto_previsto',
                                              'cd_contrato',
                                              'ni_horas'=> new Zend_Db_Expr("CASE WHEN st_controle = 'D' THEN SUM(ni_horas)
                                                                                  WHEN st_controle = 'C' THEN SUM(ni_horas)*(-1) END")),
                                        $this->_schema)
                                 ->where('a.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                                 ->where('cd_proposta  = ?', $cd_proposta, Zend_Db::INT_TYPE)
                                 ->group('cd_projeto_previsto')
                                 ->group('st_controle')
                                 ->group('cd_contrato');

        $objContrato = new Contrato();
        $select      = $objContrato->select()
                                   ->setIntegrityCheck(false);

        $select->from(array('b'=>$subSelect),
                      array('cd_projeto_previsto',
                            'ni_horas'=>new Zend_Db_Expr('SUM(ni_horas)')));

        $select->join(array('s'=>KT_S_CONTRATO),
                      '(b.cd_contrato = s.cd_contrato)',
                      array('tx_numero_contrato'),
                      $this->_schema);

        $select->join(array('projprev'=>KT_S_PROJETO_PREVISTO),
                      '(b.cd_projeto_previsto = projprev.cd_projeto_previsto)',
                      array('tx_projeto_previsto'),
                      $this->_schema);

        $select->group('b.cd_projeto_previsto')
               ->group('tx_projeto_previsto')
               ->group('tx_numero_contrato');

        $select->order('tx_numero_contrato')
               ->order('b.cd_projeto_previsto');

       return $objContrato->fetchAll($select)->toArray();
	}
}