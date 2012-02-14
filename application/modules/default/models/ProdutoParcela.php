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

class ProdutoParcela extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_PRODUTO_PARCELA;
	protected $_primary  = array ('cd_produto_parcela', 'cd_proposta', 'cd_projeto', 'cd_parcela');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	/**
	 * Método desenvolvido para verificar as parcelas da
	 * proposta com produto cadastrado.
	 *
	 * @since 22/09/2008
	 * @param int $cd_projeto
	 * @param int $cd_proposta
	 * @param int $mes
	 * @param int $ano
	 * @return array $res
	 */
	/* apagar depois
	public function getParcelaComProdutos($cd_projeto, $cd_proposta, $mes, $ano) {
		$sql = " select  par.cd_parcela,
					par.cd_projeto,
					par.cd_proposta,
					par.ni_parcela,
					par.ni_mes_previsao_parcela,
					par.ni_ano_previsao_parcela,
					pp.tx_produto_parcela,
					tp.tx_tipo_produto
				from {$this->_schema}.s_parcela as par
				left join {$this->_schema}.s_produto_parcela as pp ON(par.cd_projeto = pp.cd_projeto 
									    and par.cd_projeto = pp.cd_projeto 
									    and par.cd_parcela = pp.cd_parcela)
				left join {$this->_schema}.b_tipo_produto as tp ON(pp.cd_tipo_produto = tp.cd_tipo_produto)					     
				where par.cd_projeto = {$cd_projeto}
				  	  and par.cd_proposta = {$cd_proposta}
				  	  and par.ni_mes_previsao_parcela = {$mes}
					  and par.ni_ano_previsao_parcela = {$ano}
					  and par.cd_parcela in (
					                               select pp.cd_parcela
												   from {$this->_schema}.s_produto_parcela as pp 
												   where pp.cd_projeto = {$cd_projeto}
													    and pp.cd_proposta = {$cd_proposta})
                order by par.cd_parcela ASC ";
                
		$res = $this->getAdapter ()->fetchAll ( $sql );
		return $res;
	}
	*/
	public function getParcelaComProdutos($cd_projeto, $cd_proposta)
	{
        $select = $this->_montaSelectProdutoParcela($cd_projeto, $cd_proposta);
        
        $select->where('pp.cd_parcela IS NOT NULL');

        return $this->fetchAll($select)->toArray();
	}

	/**
	 * Método desenvolvido para verificar as parcelas da
	 * proposta sem produto cadastrado.
	 *
	 * @since 22/09/2008
	 * @param int $cd_projeto
	 * @param int $cd_proposta
	 * @param int $mes
	 * @param int $ano
	 * @return array $res
	 */
	/* apagar depois
	public function getParcelaSemProdutos($cd_projeto, $cd_proposta) {
			$sql = " select  par.cd_parcela,
					par.cd_projeto,
					par.cd_proposta,
					par.ni_parcela,
					par.ni_mes_previsao_parcela,
					par.ni_ano_previsao_parcela,
					pp.tx_produto_parcela,
					pp.cd_tipo_produto,
					tp.tx_tipo_produto
				from {$this->_schema}.s_parcela as par
				left join {$this->_schema}.s_produto_parcela as pp ON(par.cd_projeto = pp.cd_projeto 
									    and par.cd_projeto = pp.cd_projeto 
									    and par.cd_parcela = pp.cd_parcela)
				left join {$this->_schema}.b_tipo_produto as tp ON (pp.cd_tipo_produto = tp.cd_tipo_produto) 
				where par.cd_projeto = {$cd_projeto}
				  	  and par.cd_proposta = {$cd_proposta}
				  	  and par.ni_mes_previsao_parcela = {$mes}
					  and par.ni_ano_previsao_parcela = {$ano}
					  and par.cd_parcela not in (
			                               select pp.cd_parcela
										   from {$this->_schema}.s_produto_parcela as pp 
										   where pp.cd_projeto = {$cd_projeto}
											    and pp.cd_proposta = {$cd_proposta})
				order by par.cd_parcela ASC";
			
		$res = $this->getAdapter ()->fetchAll ( $sql );
		
		return $res;
	}*/
	public function getParcelaSemProdutos($cd_projeto, $cd_proposta)
	{
        $select = $this->_montaSelectProdutoParcela($cd_projeto, $cd_proposta);

        $select->where('pp.cd_parcela IS NULL');

        return $this->fetchAll($select)->toArray();
	}

    private function _montaSelectProdutoParcela($cd_projeto, $cd_proposta)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('parc'=>KT_S_PARCELA),
                      array('cd_parcela',
                            'ni_parcela',
                            'ni_horas_parcela',
                            'ni_mes_previsao_parcela',
                            'ni_ano_previsao_parcela'),
                      $this->_schema);
        $select->joinLeft(array('pp'=>$this->select()
                                           ->distinct()
                                           ->from(array('pp'=>$this->_name),
                                                  'cd_parcela',
                                                  $this->_schema)
                                           ->where('pp.cd_projeto  = ?', $cd_projeto, Zend_Db::INT_TYPE)
                                           ->where('pp.cd_proposta = ?', $cd_proposta, Zend_Db::INT_TYPE)),
                         '(parc.cd_parcela = pp.cd_parcela)',
                         array());
        $select->where('parc.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
               ->where('cd_proposta     = ?', $cd_proposta, Zend_Db::INT_TYPE);
        $select->order('ni_parcela');

        return $select;
    }

	/**
	 * Método desenvolvido para verificar as parcelas da
	 * proposta com produto cadastrado na tabela s_produto_parcela.
	 *
	 * @since 22/09/2008
	 * @param int $cd_projeto
	 * @param int $cd_proposta
	 * @param int $cd_parcela
	 * @return array $res
	 */
	public function pesquisaProdutoParcela($cd_projeto, $cd_proposta, $cd_parcela)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('pp'=>$this->_name),
                      array('cd_produto_parcela',
                            'cd_proposta',
                            'cd_projeto',
                            'cd_parcela',
                            'tx_produto_parcela',
                            'cd_tipo_produto'),
                      $this->_schema);
        $select->joinLeft(array('tp'=>KT_B_TIPO_PRODUTO),
                          '(pp.cd_tipo_produto = tp.cd_tipo_produto)',
                          'tx_tipo_produto',
                          $this->_schema);
		$select->where('cd_projeto  = ?', $cd_projeto,  Zend_Db::INT_TYPE)
               ->where('cd_proposta = ?', $cd_proposta, Zend_Db::INT_TYPE)
               ->where('cd_parcela  = ?', $cd_parcela,  Zend_Db::INT_TYPE);
		$select->order('tx_produto_parcela');

        return $this->fetchAll($select)->toArray();
	}
	
	/**
	 * Método desenvolvido para salvar os dados na tabela
	 *
	 * @author wunilberto.melo
	 * @since 22/09/2008
	 * @param int $cd_projeto
	 * @param int $cd_proposta
	 * @param int $cd_parcela
	 * @param var $tx_produto_parcela
	 * @return boolean $msg
	 */
	public function salvarProdutoParcela($cd_projeto, $cd_proposta, $cd_parcela, $tx_produto_parcela, $cd_tipo_produto)
    {
		$novo                     = $this->createRow();
		$novo->cd_produto_parcela = $this->getNextValueOfField('cd_produto_parcela');
		$novo->cd_proposta        = $cd_proposta;
		$novo->cd_projeto         = $cd_projeto;
		$novo->cd_parcela         = $cd_parcela;
		$novo->tx_produto_parcela = $tx_produto_parcela;
		$novo->cd_tipo_produto    = $cd_tipo_produto;
        
		if (! $novo->save ()) {
			$msg = false;
		} else {
			$msg = true;
		}
		return $msg;
	}
	
	public function getProdutoParcela($cd_produto_parcela, $object = false)
    {
		$select = $this->select()
                       ->where("cd_produto_parcela = ?", $cd_produto_parcela, Zend_Db::INT_TYPE);
		
		$res = $this->fetchAll($select);

		if(!$object){
            $res = $res->toArray();
		}
		
		return $res;
	}
	
	public function getProdutosDaParcela($cd_parcela)
    {
		$select = $this->select ();
		$select->where("cd_parcela = ?", $cd_parcela, Zend_Db::INT_TYPE );
		
		return $this->fetchAll($select);
	}
	
	/**
	 * Método desenvolvido para alterar os dados na tabela
	 *
	 * @since 22/09/2008
	 * @param int $cd_projeto
	 * @param int $cd_proposta
	 * @param int $cd_parcela
	 * @param var $tx_produto_parcela
	 * @return boolean $msg
	 */
	public function alterarProdutoParcela($cd_produto_parcela, $cd_projeto, $cd_proposta, $cd_parcela, $tx_produto_parcela, $cd_tipo_produto)
    {
		
		$arrUpdate ['cd_produto_parcela'] = $cd_produto_parcela;
		$arrUpdate ['cd_projeto'        ] = $cd_projeto;
		$arrUpdate ['cd_proposta'       ] = $cd_proposta;
		$arrUpdate ['cd_parcela'        ] = $cd_parcela;
		$arrUpdate ['tx_produto_parcela'] = $tx_produto_parcela;
		$arrUpdate ['cd_tipo_produto'   ] = $cd_tipo_produto;
		$where = " cd_produto_parcela = {$cd_produto_parcela} ";
		
		if (! $this->update( $arrUpdate, $where )) {
			$msg = false;
		} else {
			$msg = true;
		}
		return $msg;
	}
	
	/**
	 * Método desenvolvido para excluir os dados na tabela
	 *
	 * @since 22/09/2008
	 * @param var $cd_produto_parcela
	 * @return boolean $msg
	 */
	public function excluirProdutoParcela($cd_produto_parcela)
    {
		$where = " cd_produto_parcela = {$cd_produto_parcela} ";
		
		if (! $this->delete( $where )) {
			$msg = false;
		} else {
			$msg = true;
		}
		return $msg;
	}

    public function getComboProdutoParcela($cd_projeto, $cd_proposta, $cd_parcela, $comSelecione=false)
	{
		$select = $this->select()
                       ->where("cd_projeto  = ?", $cd_projeto,  Zend_Db::INT_TYPE)
                       ->where("cd_proposta = ?", $cd_proposta, Zend_Db::INT_TYPE)
                       ->where("cd_parcela  = ?", $cd_parcela,  Zend_Db::INT_TYPE)
                       ->order("tx_produto_parcela");

		$rowProdutos = $this->fetchAll($select);

        $arrProdutos = array();
        if($comSelecione){
            $arrProdutos[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        }

        foreach ($rowProdutos as $value) {
            $arrProdutos[$value->cd_produto_parcela] = $value->tx_produto_parcela;
        }
		return $arrProdutos;
	}
}