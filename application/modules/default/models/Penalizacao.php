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

class Penalizacao extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_PENALIZACAO;
	protected $_primary  = array('dt_penalizacao','cd_contrato','cd_penalidade');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getDadosPenalizacao($dt_penalizacao = null, $cd_contrato = null, $cd_penalidade = null, $dt_penalizacao_mesAno = null, $orderBy = "")
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('pz'=>$this->_name),
                      array('pz.dt_penalizacao',
                            'cd_contrato',
                            'cd_penalidade',
                            'tx_obs_penalizacao',
                            'tx_justificativa_penalizacao',
                            'ni_qtd_ocorrencia',
                            'st_aceite_justificativa',
                            'dt_penalizacao',
                            'dt_justificativa',
                            'tx_obs_justificativa'),
                      $this->_schema);
        $select->join(array('pd'=>KT_B_PENALIDADE),
                      '(pz.cd_penalidade = pd.cd_penalidade)',
                      array('tx_penalidade',
                            'tx_abreviacao_penalidade'),
                      $this->_schema);
        $select->join(array('co'=>KT_S_CONTRATO),
                      '(pz.cd_contrato = co.cd_contrato)',
                      'tx_numero_contrato',
                      $this->_schema);
        $select->join(array('em'=>KT_S_EMPRESA),
                      '(co.cd_empresa = em.cd_empresa)',
                      'tx_empresa',
                      $this->_schema);

		if(!is_null($dt_penalizacao)){
			$select->where(new Zend_Db_Expr("{$this->to_char('pz.dt_penalizacao', 'DD/MM/YYYY')} = '{$dt_penalizacao}'"));
		}
		if(!is_null($cd_contrato)){
			$select->where('pz.cd_contrato = ?',$cd_contrato, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_penalidade)){
			$select->where('pz.cd_penalidade = ?',$cd_penalidade, Zend_Db::INT_TYPE);
		}
		if(!is_null($dt_penalizacao_mesAno)){
			$select->where(new Zend_Db_Expr("{$this->to_char('pz.dt_penalizacao', 'MM/YYYY')} = '{$dt_penalizacao_mesAno}'"));
		}

		if($orderBy == ""){
            $select->order('pz.dt_penalizacao');
		} else {
            $select->order($orderBy);
		}
        return $this->fetchAll($select)->toArray();
	}
	
	public function verificaTransacaoPenalizacao(array $arrPenalizacao)
	{
		$where = $this->getWherePenalizacao($arrPenalizacao['dt_penalizacao'],$arrPenalizacao['cd_penalidade'],$arrPenalizacao['cd_contrato']);
		$arrDados = $this->fetchAll($where)->toArray();

		if(count($arrDados) > 0){
			return $this->alterarDadosPenalizacao($arrPenalizacao);
		} else {
			return $this->salvarPenalizacao($arrPenalizacao);
		}
	}
	
	private function salvarPenalizacao(array $arrDados)
	{

		$novo = $this->createRow();
		$novo->dt_penalizacao               = Base_Util::converterDate($arrDados['dt_penalizacao'], 'DD/MM/YYYY', 'YYYY-MM-DD');
		$novo->cd_contrato                  = $arrDados['cd_contrato'];
		$novo->cd_penalidade                = $arrDados['cd_penalidade'];
		if(array_key_exists('tx_obs_penalizacao',$arrDados))
			$novo->tx_obs_penalizacao = ($arrDados['tx_obs_penalizacao'])?$arrDados['tx_obs_penalizacao']:null;
		if(array_key_exists('ni_qtd_ocorrencia',$arrDados))
			$novo->ni_qtd_ocorrencia = ($arrDados['ni_qtd_ocorrencia'])?$arrDados['ni_qtd_ocorrencia']:null;
		if($novo->save()){
			return true;
		} else {
			return false;
		}
	}
	
	private function alterarDadosPenalizacao(array $arrDados)
	{
		$where = $this->getWherePenalizacao($arrDados['dt_penalizacao'],$arrDados['cd_penalidade'],$arrDados['cd_contrato']);

		$arrDados['dt_penalizacao'] = Base_Util::converterDate($arrDados['dt_penalizacao'], 'DD/MM/YYYY', 'YYYY-MM-DD');

		if($this->update($arrDados,$where)){
			return true;
		} else {
			return false;
		}
	}
    
	private function getWherePenalizacao($dt_penalizacao,$cd_penalidade,$cd_contrato)
	{
        $arrWhere['dt_penalizacao = ?'] = Base_Util::converterDate($dt_penalizacao, 'DD/MM/YYYY', 'YYYY-MM-DD');
        $arrWhere['cd_penalidade  = ?'] = $cd_penalidade;
        $arrWhere['cd_contrato    = ?'] = $cd_contrato;

        return $arrWhere;
	}
	
	public function excluirPenalizacao($dt_penalizacao,$cd_contrato,$cd_penalidade)
	{
		$where = "dt_penalizacao = '{$dt_penalizacao}' and cd_penalidade = {$cd_penalidade} and cd_contrato = {$cd_contrato}";
		if($this->delete($where)){
			return true;
		} else {
			return false;
		}
	}
}