<?php
/**
 * @Copyright Copyright 2006, 2007, 2008, 2009 MDIC - MinistÃ©rio do Desenvolvimento, da Industria e do ComÃ©rcio Exterior, Brasil.
 * @tutorial  Este arquivo Ã© parte do programa OASIS - Sistema de GestÃ£o de Demanda, Projetos e ServiÃ§os de TI.
 *			  O OASIS Ã© um software livre; vocÃª pode redistribui-lo e/ou modifica-lo dentro dos termos da LicenÃ§a
 *			  PÃºblica Geral GNU como publicada pela FundaÃ§Ã£o do Software Livre (FSF); na versÃ£o 2 da LicenÃ§a,
 *			  ou (na sua opniÃ£o) qualquer versÃ£o.
 *			  Este programa Ã© distribuido na esperanÃ§a que possa ser util, mas SEM NENHUMA GARANTIA;
 *			  sem uma garantia implicita de ADEQUAÃ‡Ã‚O a qualquer MERCADO ou APLICAÃ‡ÃƒO EM PARTICULAR.
 *			  Veja a LicenÃ§a PÃºblica Geral GNU para maiores detalhes.
 *			  VocÃª deve ter recebido uma cÃ³pia da LicenÃ§a PÃºblica Geral GNU, sob o tÃ­tulo "LICENCA.txt",
 *			  junto com este programa, se nÃ£o, escreva para a FundaÃ§Ã£o do Software Livre(FSF) Inc., 51 Franklin St,
 *			  Fifth Floor, Boston, MA 02110-1301 USA.
 */

class RelatorioProjetoParecerTecnico extends Zend_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;
	    
	public function getDadoDoProjetoProposta(array $arrParams)
	{
        $objTable = new ProcessamentoParcela();

        $subSelect = $objTable->select()->setIntegrityCheck(false);

		$subSelect->from(KT_S_PROCESSAMENTO_PROPOSTA,
				array('maxCod' => new Zend_Db_Expr('max(cd_processamento_proposta)')),
				$this->_schema);
		$subSelect->where('cd_projeto  = ?', $arrParams['cd_projeto'], Zend_Db::INT_TYPE);
		$subSelect->where('cd_proposta = ?', $arrParams['cd_proposta'], Zend_Db::INT_TYPE);
		$subSelect->where('st_parecer_tecnico_proposta is not null');

        $select = $objTable->select()->setIntegrityCheck(false);

        $select->from(array('procprop'=>KT_S_PROCESSAMENTO_PROPOSTA),
                      array('cd_projeto',
                            'cd_proposta',
                            'tx_obs_parecer_tecnico_prop',
                            'cd_processamento_proposta',
                            'dt_parecer_tecnico_proposta'),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(procprop.cd_projeto = proj.cd_projeto)',
                      array('tx_projeto',
                            'tx_sigla_projeto'),
                      $this->_schema);
        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(prof.cd_profissional = procprop.cd_prof_parecer_tecnico_propos)',
                      array('tx_profissional'),
                      $this->_schema);

        $select->where('procprop.cd_projeto = ?', $arrParams['cd_projeto'], Zend_Db::INT_TYPE);
        $select->where('cd_proposta = ?', $arrParams['cd_proposta'], Zend_Db::INT_TYPE);
        $select->where('procprop.cd_processamento_proposta = ?', $subSelect);

        return $objTable->fetchAll($select)->toArray();
	}
	
	public function getListaProdutosProposta(array $arrParams)
	{
        return $this->fetchAll($this->_montaSelectListaProdutos($arrParams))->toArray();
	}
	
	public function getItensAvaliadosProposta($cd_processamento_proposta)
	{
        $objTable = new ParecerTecnicoProposta();
        $select = $objTable->select()->setIntegrityCheck(false);

        $select->from(array('ptp'=>KT_A_PARECER_TECNICO_PROPOSTA),
                      array('st_avaliacao'),
                      $this->_schema);
        $select->join(array('ipt'=>KT_B_ITEM_PARECER_TECNICO),
                      '(ptp.cd_item_parecer_tecnico = ipt.cd_item_parecer_tecnico)',
                      array('tx_item_parecer_tecnico'),
                      $this->_schema);
        $select->where('cd_processamento_proposta = ?', $cd_processamento_proposta, Zend_Db::INT_TYPE);
        $select->order('tx_item_parecer_tecnico');

        return $this->fetchAll($select)->toArray();
	}
	
	/*Funções da Tela do relatório de parecer técnico da Parcela*/
	public function getDadosDoProjetoParcela(array $arrParams)
	{
        $objTable = new ProcessamentoParcela();
        $select = $objTable->select()->setIntegrityCheck(false);

        $select->from(array('procparc'=>KT_S_PROCESSAMENTO_PARCELA),
                      array('cd_projeto',
                            'cd_proposta',
                            'cd_parcela',
                            'tx_obs_parecer_tecnico_parcela',
                            'cd_processamento_parcela',
                            'dt_parecer_tecnico_parcela'),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(procparc.cd_projeto = proj.cd_projeto)',
                      array('tx_projeto',
                            'tx_sigla_projeto'),
                      $this->_schema);
        $select->join(array('parc'=>KT_S_PARCELA),
                      '(procparc.cd_parcela = parc.cd_parcela)',
                      array('ni_parcela'),
                      $this->_schema);
        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(procparc.cd_prof_parecer_tecnico_parc = prof.cd_profissional)',
                      array('tx_profissional'),
                      $this->_schema);
        $select->where('procparc.cd_projeto  = ?', $arrParams['cd_projeto' ], Zend_Db::INT_TYPE)
               ->where('procparc.cd_proposta = ?', $arrParams['cd_proposta'], Zend_Db::INT_TYPE)
               ->where('procparc.cd_parcela  = ?', $arrParams['cd_parcela' ], Zend_Db::INT_TYPE)
               ->where('st_ativo             = ?', 'S');

        return $this->fetchAll($select)->toArray();
	}

	public function getListaProdutosParcela(array $arrParams)
	{
        $select = $this->_montaSelectListaProdutos($arrParams);
        
        $select->where('cd_parcela = ?', $arrParams['cd_parcela'], Zend_Db::INT_TYPE);

        return $this->fetchAll($select)->toArray();
	}
	 
	public function getItemAvaliadoParcela($cd_processamento_parcela)
	{
        $objTable = new ParecerTecnicoParcela();
        $select = $objTable->select()->setIntegrityCheck(false);

        $select->from(array('ptp'=>KT_A_PARECER_TECNICO_PARCELA),
                      array('st_avaliacao'),
                      $this->_schema);
        $select->join(array('ipt'=>KT_B_ITEM_PARECER_TECNICO),
                      '(ptp.cd_item_parecer_tecnico = ipt.cd_item_parecer_tecnico)',
                      array('tx_item_parecer_tecnico'),
                      $this->_schema);
        $select->where('cd_processamento_parcela = ?', $cd_processamento_parcela, Zend_Db::INT_TYPE);
        $select->order('tx_item_parecer_tecnico');

        return $this->fetchAll($select)->toArray();
	}

    private function _montaSelectListaProdutos(array $params)
    {
        $objProdutoParcela = new ProdutoParcela();

        $select = $objProdutoParcela->select()
                                    ->from(KT_S_PRODUTO_PARCELA,
                                           'tx_produto_parcela',
                                           $this->_schema)
                                    ->where('cd_projeto  = ?', $params['cd_projeto' ], Zend_Db::INT_TYPE)
                                    ->where('cd_proposta = ?', $params['cd_proposta'], Zend_Db::INT_TYPE)
                                    ->order('tx_produto_parcela');
        return $select;
    }

}