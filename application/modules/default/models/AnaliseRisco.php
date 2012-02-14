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

class AnaliseRisco extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_ANALISE_RISCO;
	protected $_primary  = array("dt_analise_risco","cd_projeto","cd_proposta","cd_etapa","cd_atividade","cd_item_risco");
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function validaDadosGravacao(array $arrRisco)
	{
		$objDados = $this->fetchAll(
								$this->select()
                                     ->from($this,"dt_analise_risco")
									 ->where("cd_projeto    = ?", $arrRisco['cd_projeto'   ], Zend_Db::INT_TYPE)
								     ->where("cd_proposta   = ?", $arrRisco['cd_proposta'  ], Zend_Db::INT_TYPE)
								     ->where("cd_etapa      = ?", $arrRisco['cd_etapa'     ], Zend_Db::INT_TYPE)
								     ->where("cd_atividade  = ?", $arrRisco['cd_atividade' ], Zend_Db::INT_TYPE)
								     ->where("cd_item_risco = ?", $arrRisco['cd_item_risco'], Zend_Db::INT_TYPE)
					);
					
		if($objDados->valid()){
			$obj = $objDados->current();		
			$arrRisco['dt_analise_risco'] = (!empty($obj->dt_analise_risco)) ? $obj->dt_analise_risco : null;
			return $this->alteraAnaliseRisco($arrRisco);
		} else {
			unset($objDados);
			$objDados = $this->fetchAll(
						$this->select()
                             ->from($this,"dt_analise_risco")
							 ->where("cd_projeto    = ?", $arrRisco['cd_projeto' ], Zend_Db::INT_TYPE)
						     ->where("cd_proposta   = ?", $arrRisco['cd_proposta'], Zend_Db::INT_TYPE)
			);
			if($objDados->valid()){
				$obj = $objDados->current();
				$arrRisco['dt_analise_risco'] = (!empty($obj->dt_analise_risco)) ? $obj->dt_analise_risco : null;
			}
			return $this->salvaAnaliseRisco($arrRisco);
		}
	}
	
	private function salvaAnaliseRisco(array $arrRisco)
	{
		$error = false;
		$novo  = $this->createRow();
        
		if(array_key_exists('dt_analise_risco',$arrRisco)){
			$novo->dt_analise_risco = (!empty($arrRisco['dt_analise_risco'])) ? $arrRisco['dt_analise_risco'] : null;;
		} else {
			$novo->dt_analise_risco = date("Y-m-d H:i:s");
		}
		$novo->cd_projeto       		   = trim($arrRisco['cd_projeto']);
		$novo->cd_profissional    		   = $_SESSION['oasis_logged'][0]['cd_profissional'];

		$novo->cd_proposta      		   = trim($arrRisco['cd_proposta']);
		$novo->cd_etapa         		   = trim($arrRisco['cd_etapa']);
		$novo->cd_atividade     		   = trim($arrRisco['cd_atividade']);
		$novo->cd_item_risco               = trim($arrRisco['cd_item_risco']);
		
 	    $novo->st_impacto_projeto_risco    = (array_key_exists('st_impacto_projeto_risco',$arrRisco)    ) ? trim($arrRisco['st_impacto_projeto_risco'   ]):null;
        $novo->st_impacto_tecnico_risco    = (array_key_exists('st_impacto_custo_risco',$arrRisco)      ) ? trim($arrRisco['st_impacto_custo_risco'     ]):null;
        $novo->st_impacto_custo_risco      = (array_key_exists('st_impacto_tecnico_risco',$arrRisco)    ) ? trim($arrRisco['st_impacto_tecnico_risco'   ]):null;
        $novo->st_impacto_cronograma_risco = (array_key_exists('st_impacto_cronograma_risco',$arrRisco) ) ? trim($arrRisco['st_impacto_cronograma_risco']):null;
        $novo->tx_analise_risco            = (array_key_exists('tx_analise_risco',$arrRisco)            ) ? trim($arrRisco['tx_analise_risco'           ]):null;
        $novo->tx_acao_analise_risco       = (array_key_exists('tx_acao_analise_risco',$arrRisco)       ) ? trim($arrRisco['tx_acao_analise_risco'      ]):null;
        $novo->cd_profissional_responsavel = (array_key_exists('cd_profissional_responsavel',$arrRisco) ) ? $arrRisco['cd_profissional_responsavel'     ] :null;
		$novo->dt_limite_acao              = (array_key_exists('dt_limite_acao',$arrRisco)              ) ? $arrRisco['dt_limite_acao'                  ] :null;
		$novo->tx_observacao_acao          = (array_key_exists('tx_observacao_acao',$arrRisco)          ) ? $arrRisco['tx_observacao_acao'              ] :null;
		$novo->tx_mitigacao_risco          = (array_key_exists('tx_mitigacao_risco',$arrRisco)          ) ? $arrRisco['tx_mitigacao_risco'              ] :null;
		$novo->st_nao_aplica_risco         = (array_key_exists('st_nao_aplica_risco',$arrRisco)         ) ? $arrRisco['st_nao_aplica_risco'             ] :null;
		
		if(!empty($arrRisco['st_acao'])) {
			if($arrRisco['st_acao'] == "F"){
				$novo->st_fechamento_risco = "S";
				$novo->dt_fechamento_risco = date("Y-m-d");
			} else {
				$novo->st_fechamento_risco = null;
			}
			$novo->st_acao  = $arrRisco['st_acao'];
		} else {
			$novo->st_acao  = null;
		}
		
		if(!$novo->save()){
			$error = true;
		}

		return $error;
	}
	
	private function alteraAnaliseRisco(array $arrRisco)
	{
		$error = false;
		$where = array(
            'dt_analise_risco  = ?'=>$arrRisco['dt_analise_risco'],
            'cd_projeto = ?'       =>$arrRisco['cd_projeto'],
            'cd_proposta = ?'      =>$arrRisco['cd_proposta'],
            'cd_etapa = ?'         =>$arrRisco['cd_etapa'],
            'cd_atividade = ?'     =>$arrRisco['cd_atividade'],
            'cd_item_risco = ?'    =>$arrRisco['cd_item_risco']
        );
		if(!empty($arrRisco['st_acao'])) {
			if($arrRisco['st_acao'] == "F"){
				$arrRisco['st_fechamento_risco'] = "S";
				$arrRisco['dt_fechamento_risco'] = date("Y-m-d");
			} else {
				$arrRisco['st_fechamento_risco'] = null;
			}
		}
		//limpa os campos que esta vázio
		foreach($arrRisco as $key=>$value){
			if(empty($value)){
				unset($arrRisco[$key]);
			}
		}
		$arrRisco['st_nao_aplica_risco'] = (array_key_exists('st_nao_aplica_risco',$arrRisco)) ? $arrRisco['st_nao_aplica_risco'] : null;
		if(!$this->update($arrRisco,$where)){
			$error = true;
		}
		return $error;
	}
	
	public function recuperaUltimaAnaliseRisco($cd_projeto, $cd_proposta, $cd_etapa, $cd_atividade, $cd_item_risco)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('ar'=>$this->_name),
                      array('*'),
                      $this->_schema);
        $select->join(array('dados'=>$this->select()
                                          ->from($this,array('dt_analise_risco'=>new Zend_Db_Expr('MAX(dt_analise_risco)')))
                                          ->where('cd_projeto    = ?', $cd_projeto, Zend_Db::INT_TYPE)
                                          ->where('cd_proposta   = ?', $cd_proposta, Zend_Db::INT_TYPE)
                                          ->where('cd_etapa      = ?', $cd_etapa, Zend_Db::INT_TYPE)
                                          ->where('cd_atividade  = ?', $cd_atividade, Zend_Db::INT_TYPE)
                                          ->where('cd_item_risco = ?', $cd_item_risco, Zend_Db::INT_TYPE)),
                      '(ar.dt_analise_risco = dados.dt_analise_risco)',
                      array());
        $select->where('cd_projeto    = ?', $cd_projeto, Zend_Db::INT_TYPE)
               ->where('cd_proposta   = ?', $cd_proposta, Zend_Db::INT_TYPE)
               ->where('cd_etapa      = ?', $cd_etapa, Zend_Db::INT_TYPE)
               ->where('cd_atividade  = ?', $cd_atividade, Zend_Db::INT_TYPE)
               ->where('cd_item_risco = ?', $cd_item_risco, Zend_Db::INT_TYPE);

        return $this->fetchAll($select)->toArray();
	}

	public function getQtdImpacto($cd_projeto = null, $cd_proposta = null, $cd_etapa = null, $cd_atividade = null, $cd_item_risco = null, $impacto = "tx_cor_impacto_projeto_risco")
	{

        $select = $this->select()->setIntegrityCheck(false);
        $subSelect = $this->select()
                          ->from($this,new Zend_Db_Expr('max(dt_analise_risco)'));

        $arrCampos = array();
		if(!is_null($cd_projeto)){
            $arrCampos[] = 'cd_projeto';
            $select   ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
            $subSelect->where('cd_projeto = ?',$cd_projeto, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_proposta)){
            $arrCampos[] = 'cd_proposta';
            $select   ->where('cd_proposta = ?', $cd_proposta, Zend_Db::INT_TYPE);
            $subSelect->where('cd_proposta = ?', $cd_proposta, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_etapa)){
            $arrCampos[] = 'cd_etapa';
            $select   ->where('cd_etapa = ?', $cd_etapa, Zend_Db::INT_TYPE);
            $subSelect->where('cd_etapa = ?', $cd_etapa, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_atividade)){
            $arrCampos[] = 'cd_atividade';
            $select   ->where('cd_atividade = ?', $cd_atividade, Zend_Db::INT_TYPE);
            $subSelect->where('cd_atividade = ?', $cd_atividade, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_item_risco)){
            $arrCampos[] = 'cd_item_risco';
            $select   ->where('cd_item_risco = ?', $cd_item_risco, Zend_Db::INT_TYPE);
            $subSelect->where('cd_item_risco = ?', $cd_item_risco, Zend_Db::INT_TYPE);
		}
        $arrCampos[] = $impacto;
        $arrCampos['count'] = new Zend_Db_Expr("COUNT({$impacto})");
		
        $select->from($this,$arrCampos);
        $select->where('dt_analise_risco = ?', $subSelect);

        unset($arrCampos['count']);
        $select->group($arrCampos);

        return $this->fetchAll($select)->toArray();
/*
		$filtro = "";
		$campos = "";
		if(!is_null($cd_projeto)){
			$filtro .= " cd_projeto = {$cd_projeto} and";
			$campos .= " cd_projeto,";
		}
		if(!is_null($cd_proposta)){
			$filtro .= " cd_proposta = {$cd_proposta} and";
			$campos .= " cd_proposta,";
		}
		if(!is_null($cd_etapa)){
			$filtro .= " cd_etapa = {$cd_etapa} and";
			$campos .= " cd_etapa,";
		}
		if(!is_null($cd_atividade)){
			$filtro .= " cd_atividade = {$cd_atividade} and";
			$campos .= " cd_atividade,";
		}
		if(!is_null($cd_item_risco)){
			$filtro .= " cd_item_risco = {$cd_item_risco} and";
			$campos .= " cd_item_risco,";
		}
		$filtroSub = ($filtro)?"where ".substr($filtro,0,-3):'';


		$sql = " select 
					{$campos}
					{$impacto},
					count({$impacto}) as count
				from 
					{$this->_schema}.{$this->_name}
				where
					{$filtro}
					dt_analise_risco = (
						select max(dt_analise_risco)
						from 
							{$this->_schema}.{$this->_name}
						{$filtroSub}
					)
				group by
					{$campos}
					{$impacto} ";
 */
	}
}