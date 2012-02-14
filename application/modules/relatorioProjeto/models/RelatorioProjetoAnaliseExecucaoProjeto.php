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

class RelatorioProjetoAnaliseExecucaoProjeto extends Base_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;

    public function getDadosAnaliseExecucao( $cd_projeto, $mes, $ano, $isObject=false )
	{
        $objAnaliseExecucaoProjeto = new AnaliseExecucaoProjeto();

        $select = $objAnaliseExecucaoProjeto->select()
                                            ->setIntegrityCheck(false);
        $select->from(array('aep'=>KT_S_ANALISE_EXECUCAO_PROJETO),
                      array('cd_projeto',
                            'dt_analise_execucao_projeto',
                            'dt_analise_execucao_projeto_f'=>new Zend_Db_Expr("{$this->to_char('dt_analise_execucao_projeto', 'DD/MM/YYYY HH24:MI:SS')}"),
                            'tx_resultado_analise_execucao',
                            'tx_decisao_analise_execucao',
                            'dt_decisao_analise_execucao',
                            'dt_decisao_analise_execucao_f'=>new Zend_Db_Expr("{$this->to_char('dt_decisao_analise_execucao', 'DD/MM/YYYY HH24:MI:SS')}")),
                      $this->_schema);

        $select->join(array('p'=>KT_S_PROJETO),
                      'aep.cd_projeto = p.cd_projeto',
                      array('tx_sigla_projeto'),
                      $this->_schema);

        $select->where('aep.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
               ->where(new Zend_Db_Expr("{$this->to_char('dt_analise_execucao_projeto', 'YYYY')} = '{$ano}'"))
               ->where(new Zend_Db_Expr("{$this->to_char('dt_analise_execucao_projeto', 'MM')} = '{$mes}'"));

        $select->order('dt_analise_execucao_projeto desc');

        $retorno = $this->fetchAll($select);

        if($isObject === false){
            $retorno = $retorno->toArray();
        }
        return $retorno;
	}
}