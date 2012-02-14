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

class Rotina extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_ROTINA;
	protected $_primary  = 'cd_rotina';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    /**
     * Método utilizado para salvar ou alterar rotinas
     * 
     * @param array $arrDados
     * @return string
     */
    public function salvarRotina(array $arrDados=array())
    {
        if(count($arrDados)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_INCLUSAO'));
        }

        if(!empty($arrDados['cd_rotina'])) {
            $arrFetch['cd_rotina = ?'] = $arrDados['cd_rotina'];
            
            $row = $this->fetchRow($arrFetch);
            $msg = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
        } else {
            $row            = $this->createRow();
            $row->cd_rotina  = $this->getNextValueOfField('cd_rotina');
            $msg            = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
        }

        $row->cd_area_atuacao_ti          = $arrDados['cd_area_atuacao_ti'];
        $row->tx_rotina                   = $arrDados['tx_rotina'];
        $row->tx_hora_inicio_rotina       = $arrDados['tx_hora_inicio_rotina'];
        $row->st_periodicidade_rotina     = $arrDados['st_periodicidade_rotina'];
        $row->ni_prazo_execucao_rotina    = $arrDados['ni_prazo_execucao_rotina'];
        $row->ni_dia_semana_rotina        = ( $arrDados['ni_dia_semana_rotina'] ) ? $arrDados['ni_dia_semana_rotina'] : null;
        $row->ni_dia_mes_rotina           = ( $arrDados['ni_dia_mes_rotina']    ) ? $arrDados['ni_dia_mes_rotina'] : null;
        $row->st_rotina_inativa           = ( $arrDados['st_rotina_inativa']    ) ? $arrDados['st_rotina_inativa'] : null;

        if(!$row->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
        }
        return $msg;
    }

    /**
     * Metodo utilizado para excluir as rotinas
     *
     * @param array $arrWhere exemplo: array('cd_rotina = ?'=> 15)
     * @return Base_Exception_Error
     */
    public function excluirRotina(array $arrWhere=array())
    {
        if(count($arrWhere)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_EXCLUSAO'));
        }
        if(!$this->delete($arrWhere)){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO'));
        }
    }

    /**
     * Método utilizado para recuperar as rotinas de uma determinada area de atuação
     * 
     * @param integer $cd_area_atuacao_ti
     * @return Zend_Db_Table_RowSet
     */
    public function getRotinaAreaAtuacaoTi($cd_area_atuacao_ti)
    {
		$select = $this->select()
                       ->where('cd_area_atuacao_ti = ?',$cd_area_atuacao_ti, Zend_Db::INT_TYPE)
                       ->order("tx_rotina");

        return $this->fetchAll($select);
    }

    /**
     * Método utilizado para montar o combo com as rotinas relacionadas a uma determinada area de atuação
     *
     * @param integer $cd_area_atuacao_ti
     * @return array
     */
    public function comboRotinaAreaAtuacaoTi($cd_area_atuacao_ti, $comSelecione = false)
    {
        //este método substitui o metoto getRotina() desta classe devido a mudança de objeto para area de atuaçao
        $select = null;
		if (!is_null($cd_area_atuacao_ti)) {
            $select = $this->select()
                           ->where('cd_area_atuacao_ti = ?',$cd_area_atuacao_ti, Zend_Db::INT_TYPE)
                           ->order("tx_rotina");
        }
		$arrRotina = array();

		if ($comSelecione === true) {
			$arrRotina[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}

		foreach ($this->fetchAll($select) as  $row) {
			$arrRotina[$row->cd_rotina] = $row->tx_rotina;
		}
		return $arrRotina;
    }

	public function getRotina($cd_objeto = null, $comSelecione = false)
	{
        $select = null;
		if (!is_null($cd_objeto)) {
			$select = $this->select()
                           ->setIntegrityCheck(false)
                           ->distinct()
                           ->from(array('ocr'=>KT_A_OBJETO_CONTRATO_ROTINA),
                                  array(),
                                  $this->_schema)
                           ->join(array('rot'=>$this->_name),
                                  '(rot.cd_rotina = ocr.cd_rotina)',
                                  array('cd_rotina',
                                        'tx_rotina'),
                                  $this->_schema)
                           ->where("ocr.cd_objeto = ?",$cd_objeto, Zend_Db::INT_TYPE)
                           ->order("tx_rotina");
		}

		$arrRotina = array();
		if ($comSelecione === true) {
			$arrRotina[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}

		foreach ($this->fetchAll($select) as  $row) {
			$arrRotina[$row->cd_rotina] = $row->tx_rotina;
		}
		return $arrRotina;
	}
	
	public function getDadosRotina($cd_objeto = null, $cd_rotina = null)
	{
		$select = $this->select();
		if(!is_null($cd_objeto)){
			$select->setIntegrityCheck(false)
                   ->distinct()
                   ->from(array('ocr'=> KT_A_OBJETO_CONTRATO_ROTINA),
                          array('cd_objeto'),
                          $this->_schema)
                   ->join(array('rot'=>$this->_name),
                          '(rot.cd_rotina = ocr.cd_rotina)',
                          array('cd_rotina',
                                'tx_rotina',
                                'tx_hora_inicio_rotina',
                                'st_periodicidade_rotina',
                                'ni_prazo_execucao_rotina',
                                'cd_area_atuacao_ti'),
                          $this->_schema)
                   ->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                          '(oc.cd_objeto = ocr.cd_objeto)',
                          array('tx_objeto'),
                          $this->_schema)
                   ->where("ocr.cd_objeto = ?", $cd_objeto, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_rotina)){
			$select->where("rot.cd_rotina = ?", $cd_rotina, Zend_Db::INT_TYPE);
		}
		return $this->fetchAll($select)->toArray();
	}

    /**
     * Método para recuperar os dados de uma rotina específica
     *
     * @param integer $cd_rotina
     * @return Zend_Db_Table_Row
     */
	public function getRowRotina($cd_rotina){

        $select = $this->select()->where("cd_rotina = ?",$cd_rotina, Zend_Db::INT_TYPE);

		return $this->fetchRow($select);
	}
}