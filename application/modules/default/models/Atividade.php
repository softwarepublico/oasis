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

class Atividade extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_ATIVIDADE;
	protected $_primary  = 'cd_atividade';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    /**
     * Método utilizado para salvar ou alterar atividades
     *
     * @param array $arrDados
     * @return string
     */
    public function salvarAtividade(array $arrDados=array())
    {
        if(count($arrDados)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_INCLUSAO'));
        }

        if(!empty($arrDados['cd_atividade'])) {
            $arrFetch['cd_atividade = ?'] = $arrDados['cd_atividade'];
            
            $row = $this->fetchRow($arrFetch);
            $msg = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
        } else {
            $row               = $this->createRow();
            $row->cd_atividade = $this->getNextValueOfField('cd_atividade');
            $msg               = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
        }

        $row->cd_etapa               = $arrDados['cd_etapa'];
        $row->tx_atividade           = $arrDados['tx_atividade'];
        $row->ni_ordem_atividade     = $arrDados['ni_ordem_atividade'];
        $row->tx_descricao_atividade = $arrDados['tx_descricao_atividade'];

        if(!$row->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
        }
        return $msg;
    }

    /**
     * Metodo utilizado para excluir as atividades
     *
     * @param array $arrWhere exemplo: array('cd_atividade = ?'=> 15)
     * @return Base_Exception_Error
     */
    public function excluirAtividade(array $arrWhere=array())
    {
        if(count($arrWhere)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_EXCLUSAO'));
        }
        if(!$this->delete($arrWhere)){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO'));
        }
    }

	public function getDadosAtividade( $cd_etapa )
    {
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('ativ'=>$this->_name),
                      array('cd_atividade',
                            'tx_atividade',
                            'cd_etapa',
                            'tx_descricao_atividade',
                            'ni_ordem_atividade'),
                      $this->_schema);
        $select->join(array('eta'=>KT_B_ETAPA),
                     '(ativ.cd_etapa = eta.cd_etapa)',
                     'tx_etapa',
                     $this->_schema);
        $select->where('ativ.cd_etapa = ?', $cd_etapa, Zend_Db::INT_TYPE);
        $select->order('ni_ordem_atividade');

		return $this->fetchAll($select)->toArray();
	}
	
	public function getAtividade($cd_etapa = null, $comSelecione = false){

        $select = $this->select()->order("ni_ordem_atividade");
		if (!is_null($cd_etapa)) {
            $select->where("cd_etapa = ?", $cd_etapa, Zend_Db::INT_TYPE);
		}
		
		$arrAtividade = array();
		if ($comSelecione === true) {
			$arrAtividade[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		
     
		$res = $this->fetchAll($select);
		if($res->valid()){
			foreach ($res as  $valor) {
				$arrAtividade[$valor->cd_atividade] = $valor->tx_atividade;
			}
		}
		return $arrAtividade;
	}
	public function getAtividade1($cd_etapa = null, $comSelecione = false){

        $select = $this->select()->order("ni_ordem_atividade");
		if (!is_null($cd_etapa)) {
            $select->where("cd_etapa = ?", $cd_etapa, Zend_Db::INT_TYPE);
		}
		
		$arrAtividade = array();
		if ($comSelecione === true) {
			$arrAtividade[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		
     
		$res = $this->fetchAll($select);
		if($res->valid()){
			foreach ($res as  $valor) {
				$arrAtividade[$valor->cd_atividade] = array($valor->tx_atividade);
			}
		}
		return $arrAtividade;
	}

    /**
     * Método para recuperar os dados de uma atividade específica
     *
     * @param integer $cd_atividade
     * @return Zend_Db_Table_Row
     */
	public function getRowAtividade($cd_atividade)
    {
		return $this->fetchRow(array("cd_atividade = ?"=>$cd_atividade));
	}
}