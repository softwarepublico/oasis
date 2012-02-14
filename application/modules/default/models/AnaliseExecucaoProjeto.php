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

class AnaliseExecucaoProjeto extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_ANALISE_EXECUCAO_PROJETO;
	protected $_primary  = array('dt_analise_execucao_projeto','cd_projeto');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	/**
	 * Método que retorna os projetos que estão em execução no mes e ano
	 * se não passar o mes nem o ano vai o mes e o ano atual
	 *
	 * @param boolean $comSelecione
	 * @param boolean | integer $mes
	 * @param boolean | integer $ano
	 * @return array $arrProjetoExecucao
	 */
	public function getProjetosExecucao($comSelecione=false,$mes=false,$ano=false, $cd_contrato=null)
	{

        $mes = (!$mes)? date('m'):$mes;
        $ano = (!$ano)? date('Y'):$ano;


        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('pr'=>KT_S_PROJETO),
                      array('tx_sigla_projeto', 'cd_projeto'),
                      $this->_schema);
        $select->join(array('pa'=>KT_S_PARCELA),
                      '(pr.cd_projeto = pa.cd_projeto)',
                      array(),
                      $this->_schema);
        $select->where('pa.ni_mes_previsao_parcela = ?', $mes, Zend_Db::INT_TYPE);
        $select->where('pa.ni_ano_previsao_parcela = ?', $ano, Zend_Db::INT_TYPE);
        $select->order('tx_sigla_projeto');

        if (!is_null($cd_contrato)){
            $select->join(array('cp'=>$this->select()
                                           ->setIntegrityCheck(false)
                                           ->from(KT_A_CONTRATO_PROJETO, 'cd_projeto', $this->_schema)
                                           ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE)),
                          '(cp.cd_projeto = pa.cd_projeto)',
                          array());
        }
        $rowSet = $this->fetchAll($select);

        $arrProjetoExecucao = array();
        if ($comSelecione === true) {
            $arrProjetoExecucao[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        }
        foreach ($rowSet as  $row) {
            $arrProjetoExecucao[ $row->cd_projeto ] = $row->tx_sigla_projeto;
        }

        return $arrProjetoExecucao;
	}
	
	public function getGridAnaliseExecucao($cd_projeto, $isObject=false)
	{
        $select = $this->select()
                       ->where('cd_projeto = ?', $cd_projeto)
                       ->order('dt_analise_execucao_projeto DESC');

        $retorno = $this->fetchAll($select);

        if($isObject === false){
            $retorno = $retorno->toArray();
        }
        return $retorno;
	}
	
	public function recuperaAnaliseExecucaoProjeto($dt_analise_execucao_projeto, $isObject=false)
	{
        $select = $this->select()->from($this,
                                        array('dt_analise_execucao_projeto',
                                              'dt_decisao_analise_execucao',
                                              'tx_resultado_analise_execucao',
                                              'tx_decisao_analise_execucao'))
                                 ->where("dt_analise_execucao_projeto = ?", $dt_analise_execucao_projeto);
        
        $retorno = $this->fetchAll($select);
        if($isObject === false){
            $retorno = $retorno->toArray();
        }
        return $retorno;
	}

    public function getcontratoprojeto($comSelecione=false, $cd_contrato=null)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('cp'=>KT_A_CONTRATO_PROJETO),
                      array('cd_projeto'),
                      $this->_schema);
        $select->join(array('pr'=>KT_S_PROJETO),
                      '(cp.cd_projeto = pr.cd_projeto)',
                      array('tx_sigla_projeto'),
                      $this->_schema);
        $select->where('cp.cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);
        $select->order('tx_sigla_projeto');

        $rowSet = $this->fetchAll($select);

        $arrProjetoExecucao = array();
        if ($comSelecione === true) {
            $arrProjetoExecucao[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        }
        foreach ($rowSet as  $row) {
            $arrProjetoExecucao[ $row->cd_projeto ] = $row->tx_sigla_projeto;
        }
        return $arrProjetoExecucao;

    }

}