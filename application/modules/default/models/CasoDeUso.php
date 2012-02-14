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

class CasoDeUso extends Base_Db_Table_Abstract
{
	protected $_name    = KT_S_CASO_DE_USO;
	protected $_primary = array('cd_caso_de_uso','dt_versao_caso_de_uso','cd_projeto','cd_modulo');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	/**
	 * Método que captura os dados da tabela caso de uso e monta um array para uma combo
	 *
	 * @param boolean $comSelecione
	 * @param int     $cd_projeto
	 * @param int     $cd_modulo
	 * @param text    $st_fechamento_caso_de_uso
	 * @return array  $arrCasoDeUso
	 */
	public function getCasoDeUso($comSelecione = false,$cd_projeto = null,$cd_modulo = null, $st_fechamento_caso_de_uso = null)
    {
/*		$where    = "where";
		$whereSub = "";
		if(!is_null($cd_projeto)){
			$where .= " cd_projeto = {$cd_projeto} and";
			$whereSub = "where cd_projeto = {$cd_projeto}";
			if(!is_null($cd_modulo)){
				$where .= " cd_modulo = {$cd_modulo} and";
			}
            
			if(!is_null($st_fechamento_caso_de_uso)){
				if($st_fechamento_caso_de_uso == 'N'){
					$where .= " st_fechamento_caso_de_uso is null and";
				}
				if($st_fechamento_caso_de_uso == 'S'){
					$where .= " st_fechamento_caso_de_uso is not null and";
				}
			}
		}
		if($where != "where"){
			$where = substr($where,0,-3);
		} else {
			$where = "";
		}
		$sql = " select
					cdu.cd_caso_de_uso,
					cdu.tx_caso_de_uso,
					cdu.ni_versao_caso_de_uso
				from
					{$this->_schema}.{$this->_name} as cdu
				join (
						select cd_caso_de_uso,
							max(dt_versao_caso_de_uso) as dt_versao_caso_de_uso
						from {$this->_schema}.{$this->_name}
						{$whereSub}
						group by cd_caso_de_uso
					) as dados ON (cdu.cd_caso_de_uso = dados.cd_caso_de_uso and cdu.dt_versao_caso_de_uso = dados.dt_versao_caso_de_uso)

				{$where}
				order by
					cdu.tx_caso_de_uso ";
*/
		$arrCasoDeUso = array();
		if ($comSelecione === true) {
			$arrCasoDeUso[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}

        $subSelect = $this->select()
                          ->from($this,
                                 array('cd_caso_de_uso','dt_versao_caso_de_uso'=>new Zend_Db_Expr('MAX(dt_versao_caso_de_uso)')))
                          ->group('cd_caso_de_uso');

        $select = $this->select()->setIntegrityCheck(false);

		if(!is_null($cd_projeto)){
            $select->where('cd_projeto = ?',$cd_projeto, Zend_Db::INT_TYPE);
            $subSelect->where('cd_projeto = ?',$cd_projeto, Zend_Db::INT_TYPE);

			if(!is_null($cd_modulo))
                $select->where('cd_modulo = ?',$cd_modulo, Zend_Db::INT_TYPE);

			if(!is_null($st_fechamento_caso_de_uso)){
				if($st_fechamento_caso_de_uso == 'N')
                    $select->where('st_fechamento_caso_de_uso IS NULL');
				if($st_fechamento_caso_de_uso == 'S')
                    $select->where('st_fechamento_caso_de_uso IS NOT NULL');
			}
		}

        $select->from(array('cdu'=>$this->_name),
                      array('cd_caso_de_uso','tx_caso_de_uso','ni_versao_caso_de_uso'),
                      $this->_schema);
        $select->join(array('dados'=>$subSelect),
                      '(cdu.cd_caso_de_uso = dados.cd_caso_de_uso) AND (cdu.dt_versao_caso_de_uso = dados.dt_versao_caso_de_uso)',
                      array());
        $select->order('cdu.tx_caso_de_uso');

		$rowSet = $this->fetchAll($select);

		foreach ($rowSet as  $row) {
			$arrCasoDeUso[$row->cd_caso_de_uso] = "{$row->tx_caso_de_uso} (".Base_Util::getTranslator('L_SQL_VERSAO')." {$row->ni_versao_caso_de_uso})";
		}
		return $arrCasoDeUso;
	}

	/**
	 * Método salva os dados na tabela s_caso_de_uso
	 * Recebe um array com todos os paramêtros da tabela
	 * @param array $arrCasoDeUso
	 * @return boolean $res
	 * @author Wunilberto Melo
	 */
	public function salvaDefinicaoCasoDeUso(array $arrCasoDeUso)
    {
		if(!array_key_exists('ni_versao_caso_de_uso',$arrCasoDeUso)){
			$arrCasoDeUso['ni_versao_caso_de_uso'] = 1;
            $dtVersao = date("Y-m-d H:i:s");
		}else{
            $dtVersao = $arrCasoDeUso['dt_versao_caso_de_uso'];
        }

        if(!array_key_exists('cd_caso_de_uso',$arrCasoDeUso) || empty($arrCasoDeUso['cd_caso_de_uso']) ){
            $arrCasoDeUso['cd_caso_de_uso'] = $this->getNextValueOfField("cd_caso_de_uso");
        }

		$novo                            = $this->createRow();
		$novo->cd_caso_de_uso            = $arrCasoDeUso['cd_caso_de_uso']; 
  		$novo->cd_projeto                = $arrCasoDeUso['cd_projeto'];
  		$novo->cd_modulo                 = $arrCasoDeUso['cd_modulo'];
  		$novo->ni_ordem_caso_de_uso      = $arrCasoDeUso['ni_ordem_caso_de_uso'];
  		$novo->tx_caso_de_uso            = $arrCasoDeUso['tx_caso_de_uso'];
  		$novo->tx_descricao_caso_de_uso  = $arrCasoDeUso['tx_descricao_caso_de_uso'];
  		$novo->dt_versao_caso_de_uso     = $dtVersao;
  		$novo->ni_versao_caso_de_uso     = $arrCasoDeUso['ni_versao_caso_de_uso'];

		if($novo->save()){
			$res = true;
		}else{
			$res = false;
		}
		return $res;
	}

	/**
	 * Método que recebe os dados e alteraa as informações da tabela
	 * Recebe um array com todos os paramêtros da tabela
	 * @param array $arrCasoDeUso
	 * @return boolean true or false
	 */
    public function alteraCasoDeUsoProjeto(array $arrCasoDeUso)
    {
        $where = array('cd_caso_de_uso = ?'        => $arrCasoDeUso['cd_caso_de_uso'],
                       'cd_projeto = ?'            => $arrCasoDeUso['cd_projeto'],
                       'dt_versao_caso_de_uso = ?' => $arrCasoDeUso['dt_versao_caso_de_uso']);

		$arrUpdate["cd_modulo"               ] = $arrCasoDeUso['cd_modulo'];
		$arrUpdate["tx_caso_de_uso"          ] = $arrCasoDeUso['tx_caso_de_uso'];
		$arrUpdate["ni_ordem_caso_de_uso"    ] = $arrCasoDeUso['ni_ordem_caso_de_uso'];
		$arrUpdate["tx_descricao_caso_de_uso"] = $arrCasoDeUso['tx_descricao_caso_de_uso'];

		if($this->update($arrUpdate, $where)){
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Método desenvolvido para excluir um caso de uso da tabela
	 *
	 * @param int $cd_caso_de_uso
	 * @return boolean true or false
	 */
	public function excluirCasoDeUsoProjeto($cd_caso_de_uso, $dt_versao_caso_de_uso){
		
//		$where = " cd_caso_de_uso = {$cd_caso_de_uso}
//				   /*and dt_versao_caso_de_uso = to_timestamp('{$dt_versao_caso_de_uso}','YYYY-MM-DD HH24:MI:SS')*/	";

		$where = array('cd_caso_de_uso = ?'       => $cd_caso_de_uso,
                       'dt_versao_caso_de_uso = ?'=> $dt_versao_caso_de_uso //YYYY-MM-DD HH24:MI:SS
                      );
        if($this->delete($where)){
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Método realiza uma pesquisa com o código do projeto
	 *
	 * @param integer $cd_projeto
	 * @param integer $cd_modulo
	 * @param string  $st_fechamento_caso_de_uso
	 * @return array  $arrCasoDeUso
	 */
	public function pesquisaCasoDeUsoProjeto($cd_projeto, $cd_modulo = null, $st_fechamento_caso_de_uso = null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('cdu'=>$this->_name),
                      array('cd_caso_de_uso',
                            'cd_projeto',
                            'cd_modulo',
                            'ni_ordem_caso_de_uso',
                            'tx_caso_de_uso',
                            'st_fechamento_caso_de_uso',
                            'tx_descricao_caso_de_uso',
                            'dt_fechamento_caso_de_uso',
                            'dt_versao_caso_de_uso',
                            'ni_versao_caso_de_uso',
                            'st_fechamento_caso_de_uso_desc'=>new Zend_Db_Expr("CASE WHEN cdu.st_fechamento_caso_de_uso = 'S' THEN '".Base_Util::getTranslator('L_SQL_FECHADO')."'
                                                                                                                              ELSE '".Base_Util::getTranslator('L_SQL_ABERTO')."' END")),
                      $this->_schema);
        $select->join(array('mod'=>KT_S_MODULO),
                      '(cdu.cd_modulo = mod.cd_modulo)',
                      'tx_modulo',
                      $this->_schema);
        $select->join(array('dados'=>$this->select()
                                          ->setIntegrityCheck(false)
                                          ->from($this,
                                                 array('cd_caso_de_uso',
                                                       'dt_versao_caso_de_uso'=>new Zend_Db_Expr('MAX(dt_versao_caso_de_uso)')))
                                          ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                                          ->group('cd_caso_de_uso')),
                      '(cdu.cd_caso_de_uso = dados.cd_caso_de_uso) AND (cdu.dt_versao_caso_de_uso = dados.dt_versao_caso_de_uso)',
                      array());
        $select->where('cdu.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
        $select->order(array('cdu.ni_ordem_caso_de_uso','cdu.tx_caso_de_uso'));

		if(!is_null($cd_modulo)){
            $select->where('mod.cd_modulo = ?', $cd_modulo, Zend_Db::INT_TYPE);
		}
		if(!is_null($st_fechamento_caso_de_uso)){
    		if($st_fechamento_caso_de_uso == 'N'){
                $select->where('cdu.st_fechamento_caso_de_uso IS NULL');
            } else if($st_fechamento_caso_de_uso == 'S'){
                $select->where('cdu.st_fechamento_caso_de_uso IS NOT NULL');
            }
		}
        return $this->fetchAll($select)->toArray();
	}

	/**
	 * Método que recupera os dados especifico da tabela
	 *
	 * @param int $cd_caso_de_uso
	 * @param int $cd_projeto
	 * @param int $cd_modulo
	 * @param date $dt_versao_caso_de_uso
	 * @param boolean $objeto
	 * @return array $arrDados ou obj $arrDados
	 */
	public function recuperaCasoDeUsoProjeto($cd_caso_de_uso,$cd_projeto,$cd_modulo, $objeto = false, $dt_versao_caso_de_uso){
		
		$select = $this->select();
		$select->where("cd_caso_de_uso = {$cd_caso_de_uso}")
			   ->where("cd_projeto = {$cd_projeto}")
//		       ->where("dt_versao_caso_de_uso = '{$dt_versao_caso_de_uso}'")
			   ->where("cd_modulo = {$cd_modulo}");

		$arrDados = $this->fetchAll($select);

		if($objeto){
			$arrDados = $arrDados->toArray();
		}

		return $arrDados;
	}

	public function getUltimaVersaoCasoDeUso( $cd_caso_de_uso = null ,$cd_projeto = null ,$cd_modulo = null )
    {

        $select    = $this->select()->setIntegrityCheck(false);
        $subSelect = $this->select()
                          ->from($this,
                                 array('cd_caso_de_uso',
                                       'dt_versao_caso_de_uso'=>new Zend_Db_Expr('MAX(dt_versao_caso_de_uso)')))
                          ->group('cd_caso_de_uso');

		if(!is_null($cd_caso_de_uso)){
            $select->where('cdu.cd_caso_de_uso = ?', $cd_caso_de_uso, Zend_Db::INT_TYPE);
            $subSelect->where('cd_caso_de_uso = ?', $cd_caso_de_uso, Zend_Db::INT_TYPE);
		}
		if($cd_projeto){
            $select->where('cdu.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
            $subSelect->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
		}
		if($cd_modulo){
            $select->where('cdu.cd_modulo = ?', $cd_modulo, Zend_Db::INT_TYPE);
            $subSelect->where('cd_modulo = ?', $cd_modulo, Zend_Db::INT_TYPE);
		}

        $select->from(array('cdu'=>$this->_name),
                      array('cd_caso_de_uso','st_fechamento_caso_de_uso'),
                      $this->_schema);
        $select->join(array('dados'=>$subSelect),
                      '(cdu.cd_caso_de_uso = dados.cd_caso_de_uso) AND (cdu.dt_versao_caso_de_uso = dados.dt_versao_caso_de_uso)',
                      'dt_versao_caso_de_uso');

		return $this->fetchRow($select)->toArray();
	}

	public function fechaCasoDeUso($cd_caso_de_uso, $dt_versao_caso_de_uso)
    {
		$where = array("cd_caso_de_uso = ?"        => $cd_caso_de_uso,
				       "dt_versao_caso_de_uso = ?" => $dt_versao_caso_de_uso);

		$arrUpdate['st_fechamento_caso_de_uso'] = "S";
		$arrUpdate['dt_fechamento_caso_de_uso'] = date("Y-m-d H:i:s");

		if($this->update($arrUpdate, $where)){
			return true;
		} else {
			return false;
		}
	}

	public function getDadosCasoDeUsoEspecifico( $cd_caso_de_uso, $cd_projeto=null, $dt_versao_caso_de_uso=null, $cd_modulo=null )
    {
		$select = $this->select()->where("cd_caso_de_uso = ?", $cd_caso_de_uso, Zend_Db::INT_TYPE);

		if(!is_null($cd_projeto))
			$select->where("cd_projeto = ?", $cd_projeto, Zend_Db::INT_TYPE);
		if(!is_null($dt_versao_caso_de_uso))
			$select->where("dt_versao_caso_de_uso = ?", $dt_versao_caso_de_uso);
		if(!is_null($cd_modulo))
			$select->where("cd_modulo = ?", $cd_modulo, Zend_Db::INT_TYPE);

		return $this->fetchRow($select)->toArray();
	}

	public function getUltimaVersaoTodosCasosDeUsoProjeto( $cd_projeto, $st_fechamento_caso_de_uso=false )
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('cdu'=>$this->_name),
                      array('cd_caso_de_uso','st_fechamento_caso_de_uso'),
                      $this->_schema);
        $select->join(array('dados'=>$this->select()
                                          ->from($this,
                                                 array('cd_caso_de_uso',
                                                       'dt_versao_caso_de_uso'=>new Zend_Db_Expr('MAX(dt_versao_caso_de_uso)')))
                                          ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                                          ->group('cd_caso_de_uso')),
                      '(cdu.cd_caso_de_uso = dados.cd_caso_de_uso) AND (cdu.dt_versao_caso_de_uso = dados.dt_versao_caso_de_uso)',
                      'dt_versao_caso_de_uso');
        $select->where('cdu.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);

		if( $st_fechamento_caso_de_uso === true )
            $select->where('cdu.st_fechamento_caso_de_uso = ?', 'S');

        return $this->fetchAll($select)->toArray();
	}
}