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

class PreProjetoEvolutivo extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_PRE_PROJETO_EVOLUTIVO;
	protected $_primary  = array('cd_pre_projeto_evolutivo', 'cd_projeto');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getPreProjetoEvolutivo($cd_projeto, $comSelecione = false)
	{
		
		$select = $this->select()
                       ->where("cd_projeto = ?",$cd_projeto, Zend_Db::INT_TYPE)
                       ->order("tx_pre_projeto_evolutivo");
		
		$rowSet = $this->fetchAll($select);
		
		$arrPreProjetosEvolutivos = array();
		if ($comSelecione === true) {
			$arrPreProjetosEvolutivos[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}

		foreach ($rowSet as  $row) {
			$arrPreProjetosEvolutivos[$row->cd_pre_projeto_evolutivo] = $row->tx_pre_projeto_evolutivo;
		}

		return $arrPreProjetosEvolutivos;
	}

	public function getListaPreProjetoEvolutivo($cd_contrato)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('pre'=>$this->_name),
                      array('cd_pre_projeto_evolutivo',
//                            'tx_horas_estimadas',  //Comentado pois no sql anterior estava comentado
                            'tx_pre_projeto_evolutivo',
                            'tx_objetivo_pre_proj_evol'),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(pre.cd_projeto = proj.cd_projeto)',
                      'tx_sigla_projeto',
                      $this->_schema);
        $select->join(array('uni'=>KT_B_UNIDADE),
                      '(proj.cd_unidade  = uni.cd_unidade)',
                      'tx_sigla_unidade',
                      $this->_schema);

		$select->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);
        $select->order('tx_sigla_projeto');

        return $this->fetchAll($select)->toArray();
	}
	
	public function salvaPreProjetoEvolutivo($arrDados)
	{
		$arrDados['cd_pre_projeto_evolutivo'] = $this->getNextValueOfField('cd_pre_projeto_evolutivo');
		$return                               = ($this->insert($arrDados))? true : false;
		return $return;
	}

	public function recuperaPreProjetoEvolutivo($cd_pre_projeto_evolutivo)
	{
		$arrDados = $this->fetchRow("cd_pre_projeto_evolutivo = {$cd_pre_projeto_evolutivo}")->toArray();
		return $arrDados;
	}
	
	public function alterarPreProjetoEvolutivo($arrDados)
	{
		$return = ( $this->update($arrDados, "cd_pre_projeto_evolutivo = {$arrDados['cd_pre_projeto_evolutivo']}" ) ) ? true : false;
		return $return;
	}
	
	public function excluirPreProjetoEvolutivo($cd_pre_projeto_evolutivo)
	{
		$return = ($this->delete("cd_pre_projeto_evolutivo = {$cd_pre_projeto_evolutivo}"))? true : false;
		return $return;
	}
	
	public function getDadosPreProjetoEvolutivo($cd_pre_projeto_evolutivo)
	{
		$resPreProjetoEvolutivo = $this->fetchRow("cd_pre_projeto_evolutivo = {$cd_pre_projeto_evolutivo}")->toArray();
		return $resPreProjetoEvolutivo;
	}	
}