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

class BoxInicio extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_BOX_INICIO;
	protected $_primary  = 'cd_box_inicio';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    public function getArrTipoBox()
    {
        $arrTipoBox = array(
            'P' => Base_Util::getTranslator('L_VIEW_COMBO_PROJETO'),
            'D' => Base_Util::getTranslator('L_VIEW_COMBO_DEMANDA'),
            'A' => Base_Util::getTranslator('L_VIEW_COMBO_AMBOS')
        );
        return $arrTipoBox;
    }
	
	public function getTitulo( $tx_box_inicio,$cd_box_inicio=false )
    {
		$select = $this->select();
		if( $tx_box_inicio ){
		    $select->where("tx_box_inicio = ?", $tx_box_inicio);
		}
		if( $cd_box_inicio ){
		    $select->where("cd_box_inicio = ?", $cd_box_inicio);
		}
		
		$res = $this->fetchAll($select);
        return $res->current()->tx_titulo_box_inicio;
	}

	public function salvarBoxInicio($tx_box_inicio, $tx_titulo_box_inicio, $st_tipo_box_inicio, $cd_box_inicio)
    {
	    $st_tipo = implode('',$st_tipo_box_inicio);
        
	    if($cd_box_inicio == ''){
    	    $novo              		 	= $this->createRow();
            $novo->cd_box_inicio        = $this->getNextValueOfField('cd_box_inicio');
            $novo->tx_box_inicio     	= $tx_box_inicio;
            $novo->tx_titulo_box_inicio = $tx_titulo_box_inicio;
            $novo->st_tipo_box_inicio  	= $st_tipo;
    	
            if($novo->save()){
                $res = true;
            } else {
                $res = false;
            }
        } else {
            $arrUpdate['tx_box_inicio']        = $tx_box_inicio;
            $arrUpdate['tx_titulo_box_inicio'] = $tx_titulo_box_inicio;
            $arrUpdate['st_tipo_box_inicio']   = $st_tipo;
            $res = $this->update( $arrUpdate,array("cd_box_inicio = ?"=>$cd_box_inicio));
            $res = ( $res )?true:false;
        }
		return $res;
	}

	public function getAllBoxInicio()
    {
        return $this->fetchAll($this->select()->from($this,array('cd_box_inicio','tx_box_inicio','tx_titulo_box_inicio')))->toArray();
    }
    
	public function getComboBoxInicio($comSelecione = false)
    {
		$option = '';
		
		if($comSelecione === true){
			$option .= "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
		}
		$res = $this->fetchAll($this->select()->order("tx_titulo_box_inicio"));
		
    	foreach ($res as $valor) {
			$option .= "<option value=\"{$valor->cd_box_inicio}\">{$valor->tx_titulo_box_inicio}</option>";
		}
		return $option;
    }
    
	public function getBoxInicio($comSelecione = false)
    {
		$arrRetorno = array();
		
		if($comSelecione === true){
			$arrRetorno[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}

		$res = $this->fetchAll($this->select()->order("tx_titulo_box_inicio"));
		
    	foreach ($res as $valor) {
		 $arrRetorno[$valor->cd_box_inicio] = $valor->tx_titulo_box_inicio;
		}
		return $arrRetorno;
    }
    
	public function getBoxesNaoAssociados($cd_perfil, $cd_objeto)
    {
        $select = $this->_montaSelectBoxAssociacaoPerfil($cd_perfil, $cd_objeto);
        $select->where('pbi.cd_box_inicio IS NULL');
		
		return $this->fetchAll($select)->toArray();
    }
    
    public function getBoxesAssociados($cd_perfil, $cd_objeto)
    {
        $select = $this->_montaSelectBoxAssociacaoPerfil($cd_perfil, $cd_objeto);
        $select->where('pbi.cd_box_inicio IS NOT NULL');

        return $this->fetchAll($select)->toArray();
    }

    private function _montaSelectBoxAssociacaoPerfil($cd_perfil, $cd_objeto)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('bb'=>$this->_name),
                      array('cd_box_inicio','tx_titulo_box_inicio'),
                      $this->_schema);
        $select->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                      "((bb.st_tipo_box_inicio = oc.st_objeto_contrato) OR (bb.st_tipo_box_inicio = 'A'))",
                      array(),
                      $this->_schema);
        $select->joinLeft(array('pbi'=>$this->select()
                                            ->setIntegrityCheck(false)
                                            ->from(KT_A_PERFIL_BOX_INICIO,
                                                   'cd_box_inicio',
                                                   $this->_schema)
                                            ->where('cd_perfil = ?', $cd_perfil, Zend_Db::INT_TYPE)
                                            ->where('cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE)),
                          "(bb.cd_box_inicio = pbi.cd_box_inicio)",
                          array());
      $select->where('oc.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);
      $select->order('bb.tx_titulo_box_inicio');

      return $select;
    }
}