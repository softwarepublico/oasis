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

class Profissional extends Base_Db_Table_Abstract 
{
	protected $_name 	 = KT_S_PROFISSIONAL;
	protected $_primary  = 'cd_profissional';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getDadosProfissional($cd_profissional)
	{
		return $this->fetchAll($this->select()
                                    ->where("cd_profissional = ?",$cd_profissional, Zend_Db::INT_TYPE)
                              )->toArray();
	}
	
	public function getProfissional($comSelecione = false)
	{
		$select = $this->select()
                       ->where("cd_profissional <> 0 ")
                       ->where("st_inativo IS NULL")
                       ->order("tx_profissional");
		$rowSet = $this->fetchAll($select);
		

		$arrProfissional = array();
		if ($comSelecione === true) {
			$arrProfissional[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		foreach ($rowSet as  $valor) {
			$arrProfissional[$valor->cd_profissional] = $valor->tx_profissional;
		}

		return $arrProfissional;
	}
	
	public function validaUsuarioCad($tx_profissional = null, $cd_profissional = null)
	{
        $select = $this->select()
                       ->from($this,
                              array('cd_profissional',
                                    'tx_profissional',
                                    'tx_senha',
                                    'tx_email_institucional',
                                    'tx_email_pessoal',
                                    'st_nova_senha'));
		if(!is_null($cd_profissional)){
            $select->where('cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);
		} else {
            $select->where('tx_email_institucional = ?', $tx_profissional);
		}

        return $this->fetchAll($select)->toArray();
	}
	
	public function alteraSenhaProfissional($cd_profissional, $tx_senha)
	{
		$where = "cd_profissional = {$cd_profissional}";
		$arrUpdate['tx_senha'     ] = $tx_senha;
		$arrUpdate['st_nova_senha'] = "";
		if($this->update($arrUpdate,$where)){
			return true;
		} else {
			return false;
		}
	}

	public function getProfissionalEmpresa($cd_empresa = null, $st_inativo = null, $comAdministrador = false)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('prof'=>$this->_name),
                      array('prof.cd_profissional',
                            'prof.tx_nome_conhecido',
                            'prof.cd_empresa',
                            'desc_ativo'=>new Zend_Db_Expr("CASE WHEN prof.st_inativo = 'S' 
                                                                 THEN '".Base_Util::getTranslator('L_SQL_INATIVO')."'
                                                                 ELSE '".Base_Util::getTranslator('L_SQL_ATIVO')."' END")),
                      $this->_schema);
        $select->join(array('emp'=>KT_S_EMPRESA),
                      '(prof.cd_empresa = emp.cd_empresa)',
                      'tx_empresa',
                      $this->_schema);
        $select->order('prof.tx_nome_conhecido');

		if(!is_null($cd_empresa)){
            $select->where('prof.cd_empresa = ?', $cd_empresa, Zend_Db::INT_TYPE);
		}
		if(!is_null($st_inativo)){
			if($st_inativo == 'S'){
                $select->where('prof.st_inativo = ?', $st_inativo);
			} else {
                $select->where('prof.st_inativo IS NULL');
			}
		}
		if ($comAdministrador === false){
            $select->where('prof.cd_profissional <> 0');
		}
        return $this->fetchAll($select)->toArray();
	}
}