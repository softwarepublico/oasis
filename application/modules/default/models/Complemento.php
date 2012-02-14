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

class Complemento extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_COMPLEMENTO;
	protected $_primary  = array('cd_complemento','cd_modulo','cd_projeto','cd_caso_de_uso');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getComplemento($comSelecione = false){

		$arrComplemento = array();
		if ($comSelecione === true)
			$arrComplemento[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		
		$res = $this->fetchAll();
		foreach ($res as  $valor)
			$arrComplemento[$valor->cd_complemento] = $valor->tx_complemento;
		
		return $arrComplemento;
	}
	
	/**
	 * Método desenvolvido para salvar os dados na tabela de complemento
	 *
	 * @param array $arrComplemento
	 * @return boolean
	 */
	public function salvarComplemento(array $arrComplemento){

//      Verificação se existe chave no array para incluir no lugar de pegar uma nova
        $cd_complemento = "";
        if(array_key_exists('cd_complemento', $arrComplemento)){
            if($arrComplemento['cd_complemento'] != ""){
                $cd_complemento = $arrComplemento['cd_complemento'];
            }
        }
		$novo                        = $this->createRow();
		$novo->cd_complemento        = ($cd_complemento!="") ? $cd_complemento : $this->getNextValueOfField('cd_complemento');
  		$novo->cd_projeto            = $arrComplemento['cd_projeto'];
  		$novo->cd_modulo             = $arrComplemento['cd_modulo'];
  		$novo->cd_caso_de_uso        = $arrComplemento['cd_caso_de_uso'];
  		$novo->ni_ordem_complemento  = ($arrComplemento['ni_ordem_complemento'])?$arrComplemento['ni_ordem_complemento']:null;
  		$novo->st_complemento        = $arrComplemento['st_complemento'];
  		$novo->tx_complemento        = $arrComplemento['tx_complemento'];
  		$novo->dt_versao_caso_de_uso = $arrComplemento['dt_versao_caso_de_uso']; //YYYY-MM-DD HH24:MI:SS

  		if($novo->save()){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Método desenvolvido para alterar os dados da tabela
	 *
	 * @param array $arrComplemento
	 * @return boolean
	 */
	public function alterarComplemento(array $arrComplemento)
    {
        $where = array(
                    'cd_complemento = ?'       => $arrComplemento['cd_complemento'],
                    'dt_versao_caso_de_uso = ?'=> $arrComplemento['dt_versao_caso_de_uso'] //YYYY-MM-DD HH24:MI:SS
                );
        
		$arrUpdate["cd_complemento"]        = $arrComplemento['cd_complemento'];
		$arrUpdate["cd_projeto"]            = $arrComplemento['cd_projeto'];
		$arrUpdate["cd_modulo"]             = $arrComplemento['cd_modulo'];
		$arrUpdate["cd_caso_de_uso"]        = $arrComplemento['cd_caso_de_uso'];
		$arrUpdate["ni_ordem_complemento"]  = ($arrComplemento['ni_ordem_complemento']) ? $arrComplemento['ni_ordem_complemento'] : null;
		$arrUpdate["st_complemento"]        = $arrComplemento['st_complemento'];
		$arrUpdate["tx_complemento"]        = $arrComplemento['tx_complemento'];

		if($this->update($arrUpdate, $where)){
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Método desenvolvido para excluir os dados da tabela
	 *
	 * @param int $cd_complemento
	 * @return boolean
	 */
	public function excluirComplemento($cd_complemento, $dt_versao_caso_de_uso)
    {
        $where = array(
                    'cd_complemento = ?'       => $cd_complemento,
                    'dt_versao_caso_de_uso = ?'=> $dt_versao_caso_de_uso //YYYY-MM-DD HH24:MI:SS
                );
        
		if($this->delete($where)){
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Método desenvolvido para recuperar os dados da tabela com a descrição
	 * das vinculações das outras tabelas.
	 *
	 * @param int $cd_projeto
	 * @param int $cd_modulo
	 * @param int $cd_caso_de_uso
	 * @param text $st_complemento
	 * @return boolean
	 */
	public function gridComplemento($cd_projeto,$cd_modulo,$cd_caso_de_uso,$st_complemento)
    {
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('com'=>$this->_name),
                      array('cd_complemento',
                            'cd_projeto',
                            'cd_modulo',
                            'cd_caso_de_uso',
                            'tx_complemento',
                            'ni_ordem_complemento',
                            'st_complemento',
                            'desc_complemento'=>new Zend_Db_Expr("CASE WHEN com.st_complemento = 'E' THEN '".Base_Util::getTranslator('L_SQL_EXCECAO')."'
                                                                       WHEN com.st_complemento = 'R' THEN '".Base_Util::getTranslator('L_SQL_REGRA')."'
                                                                       WHEN com.st_complemento = 'F' THEN '".Base_Util::getTranslator('L_SQL_FLUXO_ALTERNATIVO')."' END")),
                      $this->_schema);
        $select->join(array('mod'=>KT_S_MODULO),
                      '(com.cd_modulo = mod.cd_modulo)',
                      'tx_modulo',
                      $this->_schema);
        $select->join(array('cdu'=>KT_S_CASO_DE_USO),
                      '(com.cd_caso_de_uso = cdu.cd_caso_de_uso) AND (cdu.dt_versao_caso_de_uso = com.dt_versao_caso_de_uso)',
                      array('tx_caso_de_uso','st_fechamento_caso_de_uso'),
                      $this->_schema);
        $select->join(array('dados'=>$this->select()
                                          ->setIntegrityCheck(false)
                                          ->from(KT_S_CASO_DE_USO,
                                                 array('cd_caso_de_uso',
                                                       'dt_versao_caso_de_uso'=>new Zend_Db_Expr('MAX(dt_versao_caso_de_uso)')),
                                                 $this->_schema)
                                          ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                                          ->group('cd_caso_de_uso')),
                      '(cdu.cd_caso_de_uso = dados.cd_caso_de_uso) AND (cdu.dt_versao_caso_de_uso = dados.dt_versao_caso_de_uso)',
                      'dt_versao_caso_de_uso');
        $select->where('com.cd_projeto     = ?', $cd_projeto, Zend_Db::INT_TYPE)
               ->where('com.cd_modulo      = ?', $cd_modulo, Zend_Db::INT_TYPE)
               ->where('com.cd_caso_de_uso = ?', $cd_caso_de_uso, Zend_Db::INT_TYPE)
               ->where('com.st_complemento = ?', $st_complemento);
        $select->order('com.ni_ordem_complemento');

        return $this->fetchAll($select)->toArray();
	}
	
	/**
	 * Método desenvolvido para recuperar os dados da tabela
	 * utilizando o código da tabela para ter dados especificos
	 * ou vazio para ter todos os dados.
	 *
	 * @param int $cd_complemento
	 * @param int $cd_caso_de_uso
	 * @param boolean $objeto
	 * @return array ou Zend_Db_Table_RowSet
	 */
	public function recuperaComplemento($cd_complemento = null, $objeto = false, $cd_caso_de_uso = null, $dt_versao_caso_de_uso = null)
    {
		$select = $this->select();
		if(!is_null($cd_complemento))
			$select->where("cd_complemento = ?",$cd_complemento, Zend_Db::INT_TYPE);
		if(!is_null($cd_caso_de_uso))
			$select->where("cd_caso_de_uso = ?",$cd_caso_de_uso, Zend_Db::INT_TYPE);
		if(!is_null($dt_versao_caso_de_uso))
			$select->where("dt_versao_caso_de_uso = ?", $dt_versao_caso_de_uso); //YYYY-MM-DD HH24:MI:SS
		
		$arrDados = $this->fetchAll($select);
		
		if(!$objeto){
			$arrDados = $arrDados->toArray();
		}
		return $arrDados;
	}
}