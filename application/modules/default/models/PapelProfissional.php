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

class PapelProfissional extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_PAPEL_PROFISSIONAL;
	protected $_primary  = 'cd_papel_profissional';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getPapelProfissional($cd_objeto, $comSelecione = false)
	{
		$select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('pp'=>$this->_name),
                              array('cd_papel_profissional',
                                    'tx_papel_profissional'),
                              $this->_schema)
                       ->join(array('ocpp'=>KT_A_OBJETO_CONTRATO_PAPEL_PROF),
                              '(pp.cd_papel_profissional = ocpp.cd_papel_profissional)',
                              array(),
                              $this->_schema)
                       ->where("ocpp.cd_objeto = ?", $cd_objeto, Zend_Db::INT_TYPE)
                       ->order("tx_papel_profissional");
		$rowSet    = $this->fetchAll($select);
		
		$arrPapelProfissional = array();

		if ($comSelecione === true) {
			$arrPapelProfissional[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		foreach ($rowSet as $row) {
			$arrPapelProfissional[$row->cd_papel_profissional] = $row->tx_papel_profissional;
		}
	
		return $arrPapelProfissional;
	}

	public function getPapelProfissionalPeloContrato($cd_contrato, $comSelecione=false, $comTodos=false)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('pp'=>$this->_name),
                      array('cd_papel_profissional',
                            'tx_papel_profissional'),
                      $this->_schema);

        $select->join(array('ocpp'=>KT_A_OBJETO_CONTRATO_PAPEL_PROF),
                      '(pp.cd_papel_profissional = ocpp.cd_papel_profissional)',
                      array(),
                      $this->_schema);
				  
        $select->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                      '(oc.cd_objeto             = ocpp.cd_objeto)',
                      array(),
                      $this->_schema);


        $select->where('oc.cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);
        $select->order('tx_papel_profissional');

		$arrPapel = array();
        if ($comSelecione === true && $comTodos === true) {
			$arrPapel[-1] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
			$arrPapel[0] = Base_Util::getTranslator('L_VIEW_COMBO_TODOS');
		} else if($comSelecione === true){
			$arrPapel[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}

		foreach ( $this->fetchAll($select) as  $value) {
			$arrPapel[$value->cd_papel_profissional] = $value->tx_papel_profissional;
		}

		return $arrPapel;
	}

     /**
     * Metodo para recuperar todos os papeis de uma area de atuação
     *
     * @param Integer $cd_area_atuacao_ti
     *
     * @return Zend_Db_Table_RowSet
     */
    public function pesquisaPapelProfissionalAreaAtuacao($cd_area_atuacao_ti)
    {
        $select = $this->select()
                       ->where("cd_area_atuacao_ti = ?", $cd_area_atuacao_ti, Zend_Db::INT_TYPE)
                       ->order("tx_papel_profissional");

        return $this->fetchAll($select);
    }
}