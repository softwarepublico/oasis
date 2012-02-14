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

class Etapa extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_ETAPA;
	protected $_primary  = 'cd_etapa';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    /**
     * Método utilizado para salvar ou alterar etapas
     * 
     * @param array $arrDados
     * @return string
     */
    public function salvarEtapa(array $arrDados=array())
    {
        if(count($arrDados)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_INCLUSAO'));
        }

        if(!empty($arrDados['cd_etapa'])) {
            $arrFetch['cd_etapa = ?'] = $arrDados['cd_etapa'];
            
            $row = $this->fetchRow($arrFetch);
            $msg = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
        } else {
            $row            = $this->createRow();
            $row->cd_etapa  = $this->getNextValueOfField('cd_etapa');
            $msg            = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
        }

        $row->cd_area_atuacao_ti = $arrDados['cd_area_atuacao_ti'];
        $row->tx_etapa           = $arrDados['tx_etapa'];
        $row->tx_descricao_etapa = $arrDados['tx_descricao_etapa'];
        $row->ni_ordem_etapa     = $arrDados['ni_ordem_etapa'];

        if(!$row->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
        }
        return $msg;
    }

    /**
     * Metodo utilizado para excluir as etapas
     *
     * @param array $arrWhere exemplo: array('cd_etapa = ?'=> 15)
     * @return Base_Exception_Error
     */
    public function excluirEtapa(array $arrWhere=array())
    {
        if(count($arrWhere)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_EXCLUSAO'));
        }
        if(!$this->delete($arrWhere)){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO'));
        }
    }

    /**
     * Método utilizado para recuperar as etapas de uma determinada area de atuação
     * 
     * @param integer $cd_area_atuacao_ti
     * @return Zend_Db_Table_RowSet
     */
    public function getEtapaAreaAtuacaoTi($cd_area_atuacao_ti)
    {
		$select = $this->select()
                       ->where('cd_area_atuacao_ti = ?',$cd_area_atuacao_ti, Zend_Db::INT_TYPE)
                       ->where('st_etapa_inativa is null')
                       ->order("ni_ordem_etapa");

        return $this->fetchAll($select);
    }

    /**
     * Método utilizado para montar o combo com as etapas relacionadas a uma determinada area de atuação
     *
     * @param integer $cd_area_atuacao_ti
     * @return array
     */
    public function comboEtapaAreaAtuacaoTi($cd_area_atuacao_ti, $comSelecione = false)
    {
        //este método substitui o metoto getEtapa() desta classe devido a mudança de objeto para area de atuaçao
        $select = null;
		if (!is_null($cd_area_atuacao_ti)) {
            $select = $this->select()
                           ->where('cd_area_atuacao_ti = ?',$cd_area_atuacao_ti, Zend_Db::INT_TYPE)
					       ->where('st_etapa_inativa is null')
                           ->order("ni_ordem_etapa");
        }
		$arrEtapa = array();

		if ($comSelecione === true) {
			$arrEtapa[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}

		foreach ($this->fetchAll($select) as  $row) {
			$arrEtapa[$row->cd_etapa] = $row->tx_etapa;
		}
		return $arrEtapa;
    }

	public function getEtapa($cd_objeto = null, $comSelecione = false)
	{
        $select = null;
		if (!is_null($cd_objeto)) {
			$select = $this->select()
                           ->setIntegrityCheck(false)
                           ->distinct()
                           ->from(array('oca'=>KT_A_OBJETO_CONTRATO_ATIVIDADE),
                                  array(),
                                  $this->_schema)
                           ->join(array('eta'=>$this->_name),
                                  '(eta.cd_etapa = oca.cd_etapa)',
                                  array('cd_etapa',
                                        'tx_etapa',
                                        'tx_descricao_etapa',
                                        'ni_ordem_etapa'),
                                  $this->_schema)
                           ->where("oca.cd_objeto = ?",$cd_objeto, Zend_Db::INT_TYPE)
                           ->order("ni_ordem_etapa");
		}

		$arrEtapa = array();
		if ($comSelecione === true) {
			$arrEtapa[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}

		foreach ($this->fetchAll($select) as  $row) {
			$arrEtapa[$row->cd_etapa] = $row->tx_etapa;
		}
		return $arrEtapa;
	}

    public function getEtapaAreaTI($cd_area_atuacao_ti = null, $comSelecione = false)
	{

        $select = null;
		if (!is_null($cd_area_atuacao_ti)) {
			$select = $this->select()
                           ->setIntegrityCheck(false)
                           ->distinct()
                           ->from(array('oca'=>KT_A_OBJETO_CONTRATO_ATIVIDADE),
                                  array(),
                                  $this->_schema)
                           ->join(array('eta'=>$this->_name),
                                  '(eta.cd_etapa = oca.cd_etapa)',
                                  array('cd_etapa',
                                        'tx_etapa',
                                        'tx_descricao_etapa',
                                        'ni_ordem_etapa'),
                                  $this->_schema)
                           ->where("eta.cd_area_atuacao_ti = ?",$cd_area_atuacao_ti, Zend_Db::INT_TYPE)
                           ->order("ni_ordem_etapa");
		}

         $arrEtapa = array();
        if (count($this->fetchAll($select)->toArray())>0) {
            if ($comSelecione === true) {
                $arrEtapa[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
            }

            foreach ($this->fetchAll($select) as  $row) {
                $arrEtapa[$row->cd_etapa] = $row->tx_etapa;
            }
        }
		return $arrEtapa;
	}

	public function pesquisaEtapaProfissionalObjeto($comSelecione = false, $cd_objeto)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->distinct()
                       ->from(array('oca'=>KT_A_OBJETO_CONTRATO_ATIVIDADE),
                              array(),
                              $this->_schema)
                       ->join(array('eta'=>$this->_name),
                              '(eta.cd_etapa = oca.cd_etapa)',
                              array('cd_etapa',
                                    'tx_etapa',
								    'ni_ordem_etapa'),
                              $this->_schema)
                       ->where('oca.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE)
                       ->order('ni_ordem_etapa');
					   
		$arrEtapaProfissionalObjeto = array();
		if ($comSelecione === true) {
			$arrEtapaProfissionalObjeto[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		
		foreach($this->fetchAll($select) as $row) {
			$arrEtapaProfissionalObjeto[$row->cd_etapa] = $row->tx_etapa;
		}
		
		return $arrEtapaProfissionalObjeto;
	}

	public function getDadosEtapa($cd_objeto = null, $cd_etapa = null)
	{
		$select = $this->select();
		if(!is_null($cd_objeto)){
			$select->setIntegrityCheck(false)
                   ->distinct()
                   ->from(array('oca'=>KT_A_OBJETO_CONTRATO_ATIVIDADE),
                          array('cd_objeto'),
                          $this->_schema)
                   ->join(array('eta'=>$this->_name),
                          '(eta.cd_etapa = oca.cd_etapa)',
                          array('cd_etapa',
                                'tx_etapa',
                                'ni_ordem_etapa',
                                'tx_descricao_etapa',
                                'cd_area_atuacao_ti'),
                          $this->_schema)
                   ->where("oca.cd_objeto = ?", $cd_objeto, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_etapa)){
			$select->where("eta.cd_etapa = ?", $cd_etapa, Zend_Db::INT_TYPE);
		}
		return $this->fetchAll($select)->toArray();
	}

	public function getQtdEtapa($cd_etapa = null, $cd_atividade = null)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(KT_B_ITEM_RISCO,
                              array('total_item'=>new Zend_Db_Expr('COUNT(cd_item_risco)')),
                              $this->_schema);
		if(!is_null($cd_etapa)){
			$select->where('cd_etapa = ?', $cd_etapa, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_atividade)){
			$select->where('cd_atividade = ?', $cd_atividade, Zend_Db::INT_TYPE);
		}

		return $this->fetchRow($select)->total_item;
	}

    /**
     * Método para recuperar os dados de uma etapa específica
     *
     * @param integer $cd_etapa
     * @return Zend_Db_Table_Row
     */
	public function getRowEtapa($cd_etapa){

        $select = $this->select()->where("cd_etapa = ?",$cd_etapa, Zend_Db::INT_TYPE);

		return $this->fetchRow($select);
	}
    
     /**
     * Método para recuperar os dados das etapas e atividade  de uma área de atuação
     *
     * @param integer $cd_area_atividade_ti
     * @return Zend_Db_Table_Row
     */
	public function getDadosEtapaAtividade($cd_area_atuacao_ti)
    {
		
    	$select = $this->select();
		if(!is_null($cd_area_atuacao_ti)){
			$select->setIntegrityCheck(false)
                   ->from(array('ati'=>KT_B_ATIVIDADE),
                          array('cd_atividade',
                                'tx_atividade',
                                'tx_descricao_atividade' 
                               ),
                          $this->_schema)
                   ->join(array('eta'=>$this->_name),
                          "(eta.cd_etapa = ati.cd_etapa                           
                            and eta.cd_area_atuacao_ti = $cd_area_atuacao_ti
                            and eta.st_etapa_inativa is null 
                           )",
                          array('cd_etapa',
                                'tx_etapa',
                                'tx_descricao_etapa'
                                ),
                          $this->_schema)
                   ->where("ati.st_atividade_inativa is null");
		}

        return $this->fetchAll($select)->toArray();
        
	}
    

    
}