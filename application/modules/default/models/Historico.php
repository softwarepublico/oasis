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

class Historico extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_HISTORICO;
	protected $_primary  = array('cd_historico', 'cd_projeto','cd_proposta','cd_modulo','cd_etapa','cd_atividade');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getHistorico($comSelecione = false,$cd_projeto = null,$cd_proposta = null)
	{
		$arrHistorico = array();
		
        $select = null;
		if(!is_null($cd_projeto)){
            $select = $this->select()->where("cd_projeto = ?",$cd_projeto,Zend_Db::INT_TYPE);
            
			if(!is_null($cd_proposta))
				$select->where("cd_proposta = ?",$cd_proposta,Zend_Db::INT_TYPE);
		}

		$rowSet = $this->fetchAll($select);

		if ($comSelecione === true) {
			$arrHistorico[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		foreach ($rowSet as $valor) {
			$arrHistorico[$valor->cd_historico] = $valor->tx_historico;
		}
		return $arrHistorico;
	}
	
	public function listaHistorico($cd_projeto = null, $cd_proposta = null, $cd_profissional = null)
	{
	    $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('h'=>$this->_name),
                      array('cd_historico',
                            'cd_projeto',
                            'cd_proposta',
                            'dt_inicio_historico',
                            'dt_fim_historico'),
                      $this->_schema);
        $select->join(array('p'=>KT_S_PROJETO),
                      '(h.cd_projeto = p.cd_projeto)',
                      'tx_sigla_projeto',
                      $this->_schema);
        $select->where('h.cd_projeto      = ?',$cd_projeto,Zend_Db::INT_TYPE);
        $select->where('h.cd_proposta     = ?',$cd_proposta,Zend_Db::INT_TYPE);
        $select->where('h.cd_profissional = ?',$cd_profissional,Zend_Db::INT_TYPE);
		//$select->order('h.cd_projeto'); Comentado por Wunilberto Melo
		// Incluído por Wunilberto Melo para ordenação dos registros de históricos decrescente
        $select->order(array( 'dt_inicio_historico desc', 'dt_fim_historico desc')); 

		return $this->fetchAll($select)->toArray();
	}

	public function recuperaHistorico($cd_historico)
    {
        $select = $this->select()->where('cd_historico = ?',$cd_historico,Zend_Db::INT_TYPE);

        return $this->fetchAll($select)->toArray();
	}	
	
	public function excluirHistorico($cd_historico)
	{	
		if($this->delete(array('cd_historico = ?'=>$cd_historico))){
			return true;
		} else {
			return false;
		}
	}
	
	public function salvaHistorico(array $arrHistorico)
	{
		$novo 				        = $this->createRow();
		$novo->cd_historico			= $this->getNextValueOfField('cd_historico');
		$novo->cd_projeto           = $arrHistorico['cd_projeto_historico'];
		$novo->cd_proposta			= $arrHistorico['cd_proposta_historico'];
		$novo->cd_modulo		    = $arrHistorico['cd_modulo_historico'];
		$novo->cd_etapa            	= $arrHistorico['cd_etapa_historico'];
		$novo->cd_atividade         = $arrHistorico['cd_atividade_historico'];
		$novo->dt_inicio_historico	= (!empty ($arrHistorico['dt_inicio_historico'])) ? $arrHistorico['dt_inicio_historico'] : null;
		$novo->dt_fim_historico		= (!empty ($arrHistorico['dt_fim_historico'   ])) ? $arrHistorico['dt_fim_historico'   ] : null;
		$novo->tx_historico			= $arrHistorico['tx_historico'];
		$novo->cd_profissional   	= $arrHistorico['cd_profissional'];
		
		if ($novo->save()) {
			return true;
		} else {
			return false;				
		}
	}
	
	public function alterarHistorico(array $arrHistorico)
	{
		$arrUpdate['cd_historico']			= $arrHistorico['cd_historico'];
		$arrUpdate['cd_projeto']            = $arrHistorico['cd_projeto_historico'];
		$arrUpdate['cd_proposta']			= $arrHistorico['cd_proposta_historico'];
		$arrUpdate['cd_modulo']		        = $arrHistorico['cd_modulo_historico'];
		$arrUpdate['cd_etapa']            	= $arrHistorico['cd_etapa_historico'];
		$arrUpdate['cd_atividade']          = $arrHistorico['cd_atividade_historico'];
		$arrUpdate['dt_inicio_historico']   = (!empty ($arrHistorico['dt_inicio_historico'])) ? $arrHistorico['dt_inicio_historico'] : null;
		$arrUpdate['dt_fim_historico']	    = (!empty ($arrHistorico['dt_fim_historico'   ])) ? $arrHistorico['dt_fim_historico'   ] : null;
		$arrUpdate['tx_historico']		    = $arrHistorico['tx_historico'];
		$arrUpdate['cd_profissional']       = $arrHistorico['cd_profissional'];
		
		if($this->update($arrUpdate, array('cd_historico = ?'=>$arrHistorico['cd_historico']))){
			return true;
		} else {
			return false;
		}
	}
}