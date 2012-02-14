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

class Interacao extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_INTERACAO;
	protected $_primary  = array('cd_interacao','cd_modulo','cd_projeto','cd_caso_de_uso','dt_versao_caso_de_uso','cd_ator');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	/**
	 * Método desenvolvido para montar uma combo com todos
	 * os dados da tebela de interação.
	 *
	 * @since: 08/10/2008
	 * @param: boolean $comSelecione
	 * @return: array
	 */
	public function getInteracao($comSelecione = false)
	{
		$arrInteracao = array();
		if ($comSelecione === true) {
			$arrInteracao[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		
		$res = $this->fetchAll();
		
		foreach ($res as  $valor) {
			$arrInteracao[$valor->cd_interacao] = $valor->tx_interacao;
		}
	
		return $arrInteracao;
	}

	/**
	 * Método desenvolvido para salva os dados na tabela da interação
	 *
	 * @since:  07/10/2008
	 * @param int $cd_projeto
	 * @param int $cd_modulo
	 * @param int $cd_caso_de_uso
	 * @param int $cd_ator
	 * @param int $ni_ordem_interacao
	 * @param text $st_interacao
	 * @param text $tx_descricao_caso_de_uso
	 * @return boolean true or false
	 */
	public function salvarInteracao(array $arrInteracao)
	{
        //Verificação se existe chave no array para incluir no lugar de pegar uma nova
        $cd_interacao = "";
        if(array_key_exists('cd_interacao', $arrInteracao)){
            if($arrInteracao['cd_interacao'] != ""){
                $cd_interacao = $arrInteracao['cd_interacao'];
            }
        }

		$novo                           = $this->createRow();
		$novo->cd_interacao             = ($cd_interacao!="")?$cd_interacao:$this->getNextValueOfField('cd_interacao');
  		$novo->cd_modulo                = $arrInteracao['cd_modulo'];
  		$novo->cd_projeto               = $arrInteracao['cd_projeto'];
  		$novo->cd_caso_de_uso           = $arrInteracao['cd_caso_de_uso'];
  		$novo->cd_ator                  = $arrInteracao['cd_ator'];
  		$novo->tx_interacao             = $arrInteracao['tx_interacao'];
  		$novo->ni_ordem_interacao       = (!empty($arrInteracao['ni_ordem_interacao']))?$arrInteracao['ni_ordem_interacao']:null;
  		$novo->st_interacao             = $arrInteracao['st_interacao'];
		$novo->dt_versao_caso_de_uso    = $arrInteracao['dt_versao_caso_de_uso'];

        if($novo->save()){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Método desenvolvido para alterar os dados na tabela da interação
	 * 
	 * @since:  07/10/2008
	 * @param array $arrInteracao
	 * @return boolean true or false
	 */
	public function alterarInteracao(array $arrInteracao)
	{
		$where = array('cd_interacao = ?'          => $arrInteracao['cd_interacao'],
                       'dt_versao_caso_de_uso = ?' => $arrInteracao['dt_versao_caso_de_uso']);
		
		$arrUpdate["cd_interacao"]       = $arrInteracao['cd_interacao'];
		$arrUpdate["cd_projeto"]         = $arrInteracao['cd_projeto'];
		$arrUpdate["cd_modulo"]          = $arrInteracao['cd_modulo'];
		$arrUpdate["cd_caso_de_uso"]     = $arrInteracao['cd_caso_de_uso'];
		$arrUpdate["cd_ator"]            = $arrInteracao['cd_ator'];
		$arrUpdate["ni_ordem_interacao"] = (!empty($arrInteracao['ni_ordem_interacao']))?$arrInteracao['ni_ordem_interacao']:null;
		$arrUpdate["st_interacao"]       = $arrInteracao['st_interacao'];
		$arrUpdate["tx_interacao"]       = $arrInteracao['tx_interacao'];
		
		if($this->update($arrUpdate, $where)){
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Método desenvolvido para excluir os registros da tabela
	 *
	 * @since:  08/10/2008
	 * @param: int $cd_interacao
	 * @return: boolean
	 */
	public function excluirInteracao($cd_interacao, $dt_versao_caso_de_uso)
	{	
		if($this->delete(array('cd_interacao = ?' => $cd_interacao, 'dt_versao_caso_de_uso = ?' => $dt_versao_caso_de_uso))){
			$msg = true;
		} else {
			$msg = false;
		}
		return $msg;
	}
	
	/**
	 * Método desenvolvido para pesquisar as interações do projeto
	 * este método já traz os dados verificando nas outras tabelas
	 * as descrições do modulo e do ator.
	 *
	 * @since:  08/10/2008
	 * @param: int $cd_projeto
	 * @return: array 
	 */
	public function pesquisaInteracao($cd_projeto,$cd_modulo,$cd_caso_de_uso)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('int'=>$this->_name),
                      array('cd_interacao',
					'int.cd_modulo',
					'int.cd_projeto',
					'int.cd_caso_de_uso',
					'int.cd_ator',
					'int.tx_interacao',
					'int.ni_ordem_interacao',
					'int.st_interacao',
                    'desc_interacao'=>new Zend_Db_Expr("CASE WHEN int.st_interacao = 'S' THEN '".Base_Util::getTranslator('L_SQL_SISTEMA')."'
                                                             WHEN int.st_interacao = 'A' THEN '".Base_Util::getTranslator('L_SQL_ATOR')."' END")),
                      $this->_schema);
        $select->join(array('mod'=>KT_S_MODULO), '(int.cd_modulo = mod.cd_modulo)','tx_modulo',$this->_schema);
        $select->join(array('at'=>KT_S_ATOR), '(int.cd_ator = at.cd_ator)', 'tx_ator',$this->_schema);
        $select->join(array('cdu'=>$this->select()
                                        ->setIntegrityCheck(false)
                                        ->from(array('cdu'=>KT_S_CASO_DE_USO),
                                               array('cd_caso_de_uso', 
                                                     'st_fechamento_caso_de_uso',
                                                     'dt_versao_caso_de_uso'),
                                               $this->_schema)
                                        ->join(array('dados'=>$this->select()
                                                                   ->setIntegrityCheck(false)
                                                                   ->from(KT_S_CASO_DE_USO,
                                                                          array('cd_caso_de_uso',
                                                                                'dt_versao_caso_de_uso'=>new Zend_Db_Expr("MAX(dt_versao_caso_de_uso)")),
                                                                          $this->_schema)
                                                                   ->where('cd_projeto = ?',$cd_projeto,Zend_Db::INT_TYPE)
                                                                   ->group('cd_caso_de_uso')),
                                               '(cdu.cd_caso_de_uso = dados.cd_caso_de_uso) AND (cdu.dt_versao_caso_de_uso = dados.dt_versao_caso_de_uso)',
                                               array())
                                        ->where('cd_projeto = ?',$cd_projeto,Zend_Db::INT_TYPE)),
                      '(cdu.cd_caso_de_uso = int.cd_caso_de_uso) AND (cdu.dt_versao_caso_de_uso = int.dt_versao_caso_de_uso)',
                      array('st_fechamento_caso_de_uso','dt_versao_caso_de_uso'));
        $select->where('int.cd_projeto     = ?', $cd_projeto, Zend_Db::INT_TYPE)
               ->where('int.cd_modulo      = ?', $cd_modulo, Zend_Db::INT_TYPE)
               ->where('int.cd_caso_de_uso = ?', $cd_caso_de_uso, Zend_Db::INT_TYPE);
        $select->order('int.ni_ordem_interacao');

		return $this->fetchAll($select)->toArray();
	}
	
	/**
	 * Método desenvolvido para recuperar uma interação especifica da tabela
	 *
	 * @since:  08/10/2008
	 * @param: int $cd_interacao
	 * @param: int $cd_caso_de_uso
	 * @return: array
	 */
	public function recuperaInteracao($cd_interacao = null, $cd_caso_de_uso = null, $cd_ator = null, $dt_versao_caso_de_uso = null)
	{
		$select = $this->select();
		
		if(!is_null($cd_interacao))
			$select->where("cd_interacao = ?", $cd_interacao, Zend_Db::INT_TYPE);
		if(!is_null($cd_caso_de_uso))
			$select->where("cd_caso_de_uso = ?", $cd_caso_de_uso, Zend_Db::INT_TYPE);
		if(!is_null($cd_ator))
			$select->where("cd_ator = ?", $cd_ator, Zend_Db::INT_TYPE);
		if(!is_null($dt_versao_caso_de_uso))
			$select->where("dt_versao_caso_de_uso = ?", $dt_versao_caso_de_uso);
        
		return $this->fetchAll($select)->toArray();
	}
}