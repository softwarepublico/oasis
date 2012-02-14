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

class ProfissionalObjetoContrato extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_A_PROFISSIONAL_OBJETO_CONTRATO;
	protected $_primary  = array('cd_profissional','cd_objeto');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getProfissionalObjetoContrato($cd_objeto = null, $comSelecione = false, $comAdm = false, $comPerfilProfissional = false)
	{
		if (!is_null($cd_objeto)) {
			if ($comPerfilProfissional === true) {
				$res = $this->pesquisaProfissionalNoObjetoComPerfilProfissional($cd_objeto, $comAdm);
			} else {
				$res = $this->pesquisaProfissionalNoObjeto($cd_objeto, $comAdm);
			}
		} else {
			$select = null;
			if(!$comAdm){
				$select	= $this->select()
                               ->where("cd_profissioanl <> 0");
			}
			$res = $this->fetchAll($select);
		}
		
		$arrProfissionalProjeto = array();
		if ($comSelecione === true) {
			$arrProfissionalProjeto[-1] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}

		foreach ($res as  $valor) {
			$arrProfissionalProjeto[$valor["cd_profissional"]] = $valor["tx_profissional"];
		}
		
		return $arrProfissionalProjeto;
	}
	
	public function getProfissionalGerenteObjetoContrato($cd_objeto, $comSelecione = false)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('poc'=>$this->_name),
                      'cd_profissional',
                      $this->_schema);
        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(poc.cd_profissional = prof.cd_profissional)',
                      'tx_profissional',
                      $this->_schema);
        $select->where('poc.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);
        $select->where('prof.cd_perfil = ?', K_CODIGO_PERFIL_GERENTE_PROJETO, Zend_Db::INT_TYPE);
        $select->where('prof.st_inativo is null');
        $select->order('prof.tx_profissional');

		$rowSet = $this->fetchAll($select);

		$arrProfissionalProjeto = array();
		
		if ($comSelecione === true) {
			$arrProfissionalProjeto[-1] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}		
				
		foreach ($rowSet as  $row) {
			$arrProfissionalProjeto[$row->cd_profissional] = $row->tx_profissional;
		}
		return $arrProfissionalProjeto;
	}
	
	public function getProfissionalGerenteTecnicoObjetoContrato($cd_objeto, $comSelecione = false)
	{

        $arrParam['poc.cd_objeto = ?'      ] = (int) $cd_objeto;
        $arrParam['prof.cd_perfil IN (?)'  ] = array(K_CODIGO_PERFIL_GERENTE_PROJETO, K_CODIGO_PERFIL_TECNICO);
        $arrParam['prof.st_inativo IS NULL'] = '';
        
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('poc'=>$this->_name),
                      'cd_profissional',
                      $this->_schema);
        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(poc.cd_profissional = prof.cd_profissional)',
                      'tx_profissional',
                      $this->_schema);
        $select->order('prof.tx_profissional');

        //monta as condições para o select
        $this->_mountWhere($arrParam, $select);

        $rowSet = $this->fetchAll($select);

		$arrProfissionalProjeto = array();
		if ($comSelecione === true) {
			$arrProfissionalProjeto[-1] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}		
		if($rowSet->valid()){
            foreach($rowSet as  $row) {
                $arrProfissionalProjeto[$row->cd_profissional] = $row->tx_profissional;
            }
        }
		return $arrProfissionalProjeto;
	}

	public function pesquisaProfissionalForaObjeto($cd_objeto)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('prof'=>KT_S_PROFISSIONAL),
                      array('cd_profissional',
                            'tx_profissional'),
                      $this->_schema);
        $select->where('prof.cd_profissional NOT IN ?', $this->_montaSubSelectPesquisaProfissionalObjeto($cd_objeto));
        $select->where('st_inativo is null');
        $select->order('tx_profissional');

        return $this->fetchAll($select)->toArray();
	}

	public function pesquisaProfissionalNoObjeto($cd_objeto, $comAdm = false)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('prof'=>KT_S_PROFISSIONAL),
                      array('cd_profissional',
                            'tx_profissional'),
                      $this->_schema);
        $select->where('prof.cd_profissional IN ?', $this->_montaSubSelectPesquisaProfissionalObjeto($cd_objeto));
        $select->where('st_inativo is null');
        $select->order('tx_profissional');

		if(!$comAdm){
            $select->where('prof.cd_profissional != ?',0);
            $select->where('prof.cd_perfil       != ?',1);
		}
        return $this->fetchAll($select)->toArray();
	}

    private function _montaSubSelectPesquisaProfissionalObjeto($cd_objeto)
    {
        $select = $this->select()
                       ->from(array('poc'=>$this->_name), 'cd_profissional', $this->_schema)
                       ->where('poc.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);

        return $select;
    }

	public function pesquisaProfissionalNoObjetoComPerfilProfissional($cd_objeto, $comAdm = false)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('prof'=>KT_S_PROFISSIONAL),
                      array('cd_profissional',
                            'tx_profissional'),
                      $this->_schema);
        $select->join(array('t'=>$this->select()
                                      ->from($this,
                                             array('cd_profissional',
                                                   'cd_perfil_profissional'))
                                      ->where('cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE)),
                      '(prof.cd_profissional = t.cd_profissional)',
                      array());
        $select->where('t.cd_profissional IS NOT NULL')
               ->where('prof.st_inativo IS NULL')
               ->where('t.cd_perfil_profissional IS NOT NULL');
        $select->order('prof.tx_profissional');

		if(!$comAdm){
            $select->where('cd_profissional != ?',0)
                   ->where('cd_perfil != ?', 1);
		}
        return $this->fetchAll($select)->toArray();
	}
	
	public function getPosicaoBoxInicio( $cd_profissional, $cd_objeto )
	{
	    $find = $this->find( $cd_profissional,$cd_objeto );
	    return $find->current()->tx_posicao_box_inicio;
	}
	
	public function updatePosicaoBoxInicio( array $arrUpdatePosicaoBoxInicio )
	{
	    $where = " cd_profissional = {$arrUpdatePosicaoBoxInicio['cd_profissional']} 
	               and cd_objeto   = {$arrUpdatePosicaoBoxInicio['cd_objeto']} ";
	    return $this->update($arrUpdatePosicaoBoxInicio,$where);
	}
	
	public function updateFlagProfissionalObjetoContrato($cd_profissional, $cd_objeto, $flag)
	{
		$where = "cd_profissional = {$cd_profissional} and cd_objeto = {$cd_objeto}";
		return $this->update($flag, $where);
	}
	
	public function getFlagProfissionalObjetoContrato($cd_profissional, $cd_objeto)
	{
		$arrDados = $this->find($cd_profissional, $cd_objeto);
		$flag = array('st_recebe_email'  => $arrDados->current()->st_recebe_email, 
					  'st_objeto_padrao' => $arrDados->current()->st_objeto_padrao,
					  'cd_perfil_profissional' => $arrDados->current()->cd_perfil_profissional);
		return $flag;
	}
	
	public function buscaObjetoPadrao($cd_profissional)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('poc'=>$this->_name), 'cd_objeto', $this->_schema);
        $select->join(array('obj'=>KT_S_OBJETO_CONTRATO),
                      '(poc.cd_objeto = obj.cd_objeto)',
                      array(),
                      $this->_schema);
        $select->join(array('con'=>KT_S_CONTRATO),
                      '(obj.cd_contrato = con.cd_contrato)',
                      array(),
                      $this->_schema);
        $select->where('cd_profissional = ?',$cd_profissional, Zend_Db::INT_TYPE)
               ->where('con.st_contrato = ?', 'A')
               ->where('poc.st_objeto_padrao = ?', 'S');

        return $this->fetchRow($select)->cd_objeto;
	}
	
	public function getDadosProfissionalObjetoContrato($cd_profissional, $cd_objeto = null, $comContratoInativo = false)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('poc'=>$this->_name),
                      array('cd_objeto','st_objeto_padrao'),
                      $this->_schema);
        $select->join(array('obj'=>KT_S_OBJETO_CONTRATO),
                      '(poc.cd_objeto = obj.cd_objeto)',
                      array('tx_objeto','cd_contrato'),
                      $this->_schema);
        $select->join(array('con'=>KT_S_CONTRATO),
                      '(obj.cd_contrato = con.cd_contrato)',
                      array('tx_numero_contrato',
                            'st_contrato',
                            'situacao'=>new Zend_Db_Expr("case con.st_contrato when 'I' then '".Base_Util::getTranslator('L_SQL_INATIVO')."'
                                                                                        else '".Base_Util::getTranslator('L_SQL_ATIVO')."' end")),
                      $this->_schema);
		$select->where('cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);
		$select->order('obj.tx_objeto');

		if(!is_null($cd_objeto)){
            $select->where('poc.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);
		}
        
        $arrIn = array('A');
		if ($comContratoInativo){
            $arrIn[] = 'I';
		}
		$select->where('st_contrato in (?)', $arrIn);

		return $this->fetchAll($select)->toArray();
	}
	
	public function getObjetoProfissionalObjetoContrato($cd_profissional)
	{
		$arrDadosProfissionalObjetoContrato = $this->getDadosProfissionalObjetoContrato($cd_profissional);
		
		$arrObjeto     = array();
  		$arrObjeto[-1] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
  					
		foreach ($arrDadosProfissionalObjetoContrato as $dado){
			$arrObjeto[$dado["cd_objeto"]] = $dado["tx_objeto"];
		}
		
		return $arrObjeto;
	}

	public function getProfissionalObjetoPerfil($cd_objeto, $cd_perfil = null)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('poc'=>$this->_name),
                      'cd_profissional',
                       $this->_schema);
        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(poc.cd_profissional = prof.cd_profissional)',
                      'tx_profissional',
                      $this->_schema);
        $select->where('poc.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE)
               ->where('prof.st_inativo IS NULL')
               ->where('poc.cd_perfil_profissional IS NOT NULL');
        $select->order('tx_profissional');

		if( !is_null( $cd_perfil ) ){
            $select->where('cd_perfil = ?', $cd_perfil, Zend_Db::INT_TYPE);
		}
        return $this->fetchAll($select)->toArray();
	}

	public function getDestinatarioEmail($cd_objeto, $perfis = null, $flag_recebe_email = true, $cd_profissional = null)
	{
		$select = $this->select()
                       ->setIntegrityCheck(false);
		$select->from(array('poc'=>$this->_name),
				      array('cd_profissional'),
				      $this->_schema);
		$select->join(array('prof'=>KT_S_PROFISSIONAL),
					  'poc.cd_profissional = prof.cd_profissional',
					  array('prof.tx_profissional',
					        'tx_nome_conhecido',
					        'tx_email_institucional'),
					  $this->_schema);
		$select->where('poc.cd_objeto = ?',$cd_objeto,Zend_Db::INT_TYPE);

		if (!is_null($perfis)){
			$select->where("prof.cd_perfil IN (?)", $perfis);
		}
		if ($flag_recebe_email == true){
			$select->where("st_recebe_email IS NOT NULL");
		}
		if (!is_null($cd_profissional)){
			$select->where("poc.cd_profissional = ?", $cd_profissional, Zend_Db::INT_TYPE);
		}
		return $this->fetchAll($select);
	}
}