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

class ModuloContinuado extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_MODULO_CONTINUADO;
	protected $_primary  = 'cd_modulo_continuado';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getModuloContinuado($cd_projeto_continuado = null, $comSelecione = false)
	{

        $select = null;
		if (!is_null($cd_projeto_continuado)) {
			$select = $this->select()
                           ->where("cd_projeto_continuado = ?", $cd_projeto_continuado, Zend_Db::INT_TYPE)
                           ->order("tx_modulo_continuado");
		}

		$arrModuloContinuados = array();
		if ($comSelecione === true) {
			$arrModuloContinuados[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		
		$res = $this->fetchAll($select);

		foreach ($res as $valor){
			$arrModuloContinuados[$valor->cd_modulo_continuado] = $valor->tx_modulo_continuado;
		}
		return $arrModuloContinuados;
	}

	public function recuperaModuloContinuado($cd_projeto_continuado = null, $cd_objeto = null,$cd_modulo_continuado=null)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('mc'=>$this->_name),
                      array('cd_modulo_continuado',
                            'tx_modulo_continuado',
                            'cd_objeto',
                            'cd_projeto_continuado'),
                      $this->_schema);
        $select->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                      '(mc.cd_objeto = oc.cd_objeto)',
                      'tx_objeto',
                      $this->_schema);
        $select->join(array('pc'=>KT_S_PROJETO_CONTINUADO),
                      '(mc.cd_projeto_continuado = pc.cd_projeto_continuado)',
                      'tx_projeto_continuado',
                      $this->_schema);

		if(!is_null($cd_projeto_continuado)){
			$select->where('mc.cd_projeto_continuado = ?', $cd_projeto_continuado, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_objeto)){
			$select->where('mc.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_modulo_continuado)){
			$select->where('mc.cd_modulo_continuado = ?', $cd_modulo_continuado, Zend_Db::INT_TYPE);
		}

        return $this->fetchAll($select)->toArray();
	}
	
	public function salvarModuloContinuado(array $arrDados)
	{
		$novo 				           = $this->createRow();
		$novo->cd_modulo_continuado	   = $this->getNextValueOfField('cd_modulo_continuado');
		$novo->tx_modulo_continuado    = $arrDados['tx_modulo_continuado'];
		$novo->cd_objeto               = $arrDados['cd_objeto_modulo_continuado'];
		$novo->cd_projeto_continuado   = $arrDados['cd_projeto_continuado_modulo_continuado'];

		if ($novo->save()){
			return true;
		} else {
			return false;
		}
	}
	
	public function alterarModuloContinuado(array $arrDados)
	{
		$arrUpdate['cd_modulo_continuado']	  = $arrDados['cd_modulo_continuado'];
		$arrUpdate['tx_modulo_continuado']    = $arrDados['tx_modulo_continuado'];
		$arrUpdate['cd_objeto']               = $arrDados['cd_objeto_modulo_continuado'];
		$arrUpdate['cd_projeto_continuado']   = $arrDados['cd_projeto_continuado_modulo_continuado'];

		$where = array("cd_modulo_continuado = ?" => $arrDados['cd_modulo_continuado']);
		
		if ($this->update($arrUpdate,$where)) {
			return true;
		} else {
			return false;
		}
	}
}
