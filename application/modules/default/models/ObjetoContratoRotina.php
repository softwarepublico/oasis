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

class ObjetoContratoRotina extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_OBJETO_CONTRATO_ROTINA;
	protected $_primary  = array('cd_objeto', 'cd_rotina');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    /**
     * Método para retornar as atividades não associados ao Objeto do Contrato
     *
     * @param Integer $cd_objeto
     * @param Integer $cd_etapa
     * @return Zend_Db_Table_RowSet
     */
    public function pesquisaRotinaForaObjetoContrato($cd_objeto, $cd_area_atuacao_ti)
    {
        $select = $this->_montaSelectPesquisaRotinaObjetoContrato($cd_objeto, $cd_area_atuacao_ti);
        $select->where('ocr.cd_rotina IS NULL');

        return $this->fetchAll($select);
    }

    /**
     * Método para retornar as atividades associados ao Objeto do Contrato
     *
     * @param Integer $cd_objeto
     * @param Integer $cd_etapa
     * @return Zend_Db_Table_RowSet
     */
    public function pesquisaRotinaNoObjetoContrato($cd_objeto, $cd_area_atuacao_ti)
    {
        $select = $this->_montaSelectPesquisaRotinaObjetoContrato($cd_objeto, $cd_area_atuacao_ti);
        $select->where('ocr.cd_rotina is not null');

        return $this->fetchAll($select);
    }

    /**
     * Método utilizado para montar o objeto Select generico para recuperar as informações
     * das associações de atividade ao objeto do contrato
     *
     * @param Integer $cd_objeto
     * @param Integer $cd_etapa
     * @return Zend_Db_Table_Select
     */
    private function _montaSelectPesquisaRotinaObjetoContrato($cd_objeto, $cd_area_atuacao_ti)
    {
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('rot'=>KT_B_ROTINA),
                      array('cd_rotina',
                            'tx_rotina'),
                      $this->_schema);
        $select->join(array('aati'=>KT_B_AREA_ATUACAO_TI),
                      '(aati.cd_area_atuacao_ti = rot.cd_area_atuacao_ti)',
                      null,
                      $this->_schema);
        $select->joinLeft(array('ocr'=>$this->select()
                                             ->from($this,
                                                    array('cd_rotina'))
                                             ->where('cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE)),
                          '(rot.cd_rotina = ocr.cd_rotina )',
                          array());
        $select->where('rot.cd_area_atuacao_ti = ?', $cd_area_atuacao_ti, Zend_Db::INT_TYPE);
        $select->order('tx_rotina');

        return $select;
    }

    /**
     * Metodo utilizado para criar as associações das atividades ao objeto do contrato
     * @param array $arrInsert
     * @return Base_Exception_Error
     */
    public function salvarNovaRotinaAoObjetoContrato(Array $arrInsert=array())
    {
        if(count($arrInsert)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_INCLUSAO'));
        }
        $row = $this->createRow($arrInsert);
        if(!$row->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
        }
    }

    /**
     * Metodo utilizado para excluir as associações das atividade do objeto do contrato
     *
     * @param array $arrWhere exemplo: array('cd_objeto = ?'=> 14,'cd_perfil_profissional = ?'=> 2)
     * @return Base_Exception_Error
     */
    public function excluirRotinaDoObjetoContrato(Array $arrWhere=array())
    {
        if(count($arrWhere)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_EXCLUSAO'));
        }
        if(!$this->delete($arrWhere)){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO'));
        }
    }

    public function getRotinaObjetoContrato($cd_objeto, $comSelecione=false)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('rot'=>KT_B_ROTINA),
                      array('cd_rotina',
                            'tx_rotina'),
                      $this->_schema);

        $select->join(array('ocr'=>$this->_name),
                      '(rot.cd_rotina = ocr.cd_rotina)',
                      array(),
                      $this->_schema);

        $select->where('ocr.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);
        $select->order('tx_rotina');

		$arrRotina = array();
        if ($comSelecione === true){
			$arrRotina[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}

		foreach ( $this->fetchAll($select) as  $value) {
			$arrRotina[$value->cd_rotina] = $value->tx_rotina;
		}

		return $arrRotina;
	}

}