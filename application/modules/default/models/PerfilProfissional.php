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

class PerfilProfissional extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_PERFIL_PROFISSIONAL;
	protected $_primary  = 'cd_perfil_profissional';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    //TODO o metodo abaixo irá substituir este método quanto todas as telas que utilizam este método
    //forem migradas para a nova estrutura da tabela com o "cd_area_atuacao_ti" e sem o "cd_objeto"
	public function getPerfilProfissional($cd_objeto = null, $comSelecione = true, $comTodos = false)
	{
        $select = $this->select()
                       ->from(array('pp'=>$this->_name),
                              array('cd_perfil_profissional', 'tx_perfil_profissional'),
                              $this->_schema);
        $select->join(array('ocpp'=>KT_A_OBJETO_CONTRATO_PERFIL_PROF),
                      '(pp.cd_perfil_profissional = ocpp.cd_perfil_profissional)',
                      array(),
                      $this->_schema);
        $select->order("tx_perfil_profissional");

		if (!is_null($cd_objeto)) {
			$select->where("ocpp.cd_objeto = ?", $cd_objeto, Zend_Db::INT_TYPE);
		}

		$rowSet = $this->fetchAll($select);

		$arrPerfilProfissional = array();
		if ($comSelecione === true) {
			$arrPerfilProfissional[-1] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}

        if($comTodos === true){
            $arrPerfilProfissional[0] = Base_Util::getTranslator('L_VIEW_COMBO_TODOS');
        }
		
		foreach ($rowSet as  $row) {
			$arrPerfilProfissional[$row->cd_perfil_profissional] = $row->tx_perfil_profissional;
		}

		return $arrPerfilProfissional;
	}

    //TODO este metodo é o mesmo método acima apenas com outro nome até compatibilizar todas as telas que utilizam o metodo anterior
	public function getPerfilProfissionalAreaAtuacao($cd_area_atuacao_ti = null, $comSelecione = true, $comTodos = false)
	{
        $select = null;
		if (!is_null($cd_area_atuacao_ti)) {
			$select = $this->select()
                           ->where("cd_area_atuacao_ti = ?", $cd_area_atuacao_ti, Zend_Db::INT_TYPE)
                           ->order("tx_perfil_profissional");
		}
		$rowSet = $this->fetchAll($select);

		$arrPerfilProfissional = array();
		if ($comSelecione === true) {
			$arrPerfilProfissional[-1] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}

        if($comTodos === true){
            $arrPerfilProfissional[0] = Base_Util::getTranslator('L_VIEW_COMBO_TODOS');
        }

		foreach ($rowSet as  $row) {
			$arrPerfilProfissional[$row->cd_perfil_profissional] = $row->tx_perfil_profissional;
		}

		return $arrPerfilProfissional;
	}
	
	public function pesquisaPerfilProfissionalObjeto($comSelecione = false, $cd_objeto)
	{
        $select = $this->select()
                       ->from($this,
                              array('cd_perfil_profissional', 'tx_perfil_profissional'))
                       ->where('cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);
				   
		$rowSet = $this->fetchAll($select);

		$arrPerfilProfissionalObjeto = array();
		
		if ($comSelecione === true) {
			$arrPerfilProfissionalObjeto[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}				   
		foreach ($rowSet as $row) {
			$arrPerfilProfissionalObjeto[$row->cd_perfil_profissional] = $row->tx_perfil_profissional;
		}
		return $arrPerfilProfissionalObjeto;
	}

    /**
     * Metodo para recuperar todos os perfis de uma area de atuação
     * 
     * @param Integer $cd_area_atuacao_ti
     *
     * @return Zend_Db_Table_RowSet
     */
    public function pesquisaPerfilProfissionalAreaAtuacao($cd_area_atuacao_ti)
    {
        $select = $this->select()
                       ->where("cd_area_atuacao_ti = ?", $cd_area_atuacao_ti, Zend_Db::INT_TYPE)
                       ->order("tx_perfil_profissional");

        return $this->fetchAll($select);
    }
}