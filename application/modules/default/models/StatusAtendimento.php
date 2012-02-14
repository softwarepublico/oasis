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

class StatusAtendimento extends Base_Db_Table_Abstract
{
    protected $_name     = KT_B_STATUS_ATENDIMENTO;
	protected $_primary  = 'cd_status_atendimento';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;


        /**
     * Método utilizado para salvar um novo Status de Atendimento ou alterar um ja existente
     * @param array $arrDados
     * @return String $msg ou Base_Exception_Error
     */
    public function salvarStatusAtendimento(Array $arrDados=array())
    {
        if(count($arrDados) == 0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_INCLUSAO'));
        }

        if(!empty($arrDados['cd_status_atendimento'])){
            $msg = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
            $row = $this->fetchRow(array("cd_status_atendimento = ?" => $arrDados['cd_status_atendimento']));
        }else{
            $msg = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
            $row = $this->createRow();
            $row->cd_status_atendimento = $this->getNextValueOfField('cd_status_atendimento');
        }

        $row->tx_status_atendimento         = $arrDados['tx_status_atendimento'];
        $row->tx_rgb_status_atendimento     = $arrDados['tx_rgb_status_atendimento'];
        $row->st_status_atendimento         = $arrDados['st_status_atendimento'];
		if ($arrDados['st_status_atendimento'] == 'P') {
			$row->ni_percent_tempo_resposta_ini = null;
			$row->ni_percent_tempo_resposta_ini = null;
		}else{
			$row->ni_percent_tempo_resposta_ini = ($arrDados['ni_percent_tempo_resposta_ini']>=0)  ? $arrDados['ni_percent_tempo_resposta_ini']   :null;
			$row->ni_percent_tempo_resposta_ini = ($arrDados['ni_percent_tempo_resposta_ini']<0)   ? 0   : $arrDados['ni_percent_tempo_resposta_ini'];
			$row->ni_percent_tempo_resposta_fim = ($arrDados['ni_percent_tempo_resposta_fim']<=100)? $arrDados['ni_percent_tempo_resposta_fim']   :null;
			$row->ni_percent_tempo_resposta_fim = ($arrDados['ni_percent_tempo_resposta_fim']>100) ? 100 : $arrDados['ni_percent_tempo_resposta_fim'];
		}

        if(!$row->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
        }
        return $msg;
    }

    /**
     * Método para recuperar as StatusAtendimento
     *
     * @param Array $arrParam
     * @param String|Array $order
     *
     * @return Zend_Db_Table_Rowset
     */
    public function getStatusAtendimento(Array $arrParam=array(), $order='')
    {
        $select = $this->select()
                       ->from($this,
                              array('cd_status_atendimento',
                                    'tx_status_atendimento',
                                    'st_status_atendimento',
                                    'ni_percent_tempo_resposta_ini',
                                    'ni_percent_tempo_resposta_fim',
									'status_atendimento' => new Zend_Db_Expr("CASE st_status_atendimento
                                                                              WHEN 'P' THEN '".Base_Util::getTranslator('L_SQL_PRIORIDADE')."'
                                                                              WHEN 'T' THEN '".Base_Util::getTranslator('L_SQL_TEMPO_RESPOSTA')."' END"),
                                    'tx_rgb_status_atendimento'));
        $this->_mountWhere($arrParam, $select);

        if($order != ''){
            $select->order($order);
        }else{
            $select->order(array('st_status_atendimento','tx_status_atendimento',));
        }
        return $this->fetchAll($select);
    }

    /**
     * Método para recuperar as StatusAtendimento para montagem de combo
     *
     * @param Boolean $comSelecione
     *
     * @return Zend_Db_Table_Rowset
     */
    public function getComboStatusAtendimento($comSelecione=false, $st_status_atendimento = 'P')
    {
        $arrStatusAtendimento = Array();
        $select = $this->select()
                       ->from($this,
                              array('cd_status_atendimento',
                                    'tx_status_atendimento'))
					   ->where('st_status_atendimento = ?', $st_status_atendimento)
                       ->order('tx_status_atendimento');

        $rowSet = $this->fetchAll($select);

        if($comSelecione === true)
            $arrStatusAtendimento[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        
        if($rowSet->valid())
            foreach($rowSet as $row)
                $arrStatusAtendimento[$row->cd_status_atendimento] = $row->tx_status_atendimento;

        return $arrStatusAtendimento;
    }

    /**
     * @param array $arrWhere array('cd_status_atendimento = ?'=>10)
     */
    public function excluirStatusAtendimento( array $arrWhere=array())
    {
        if(count($arrWhere)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_EXCLUSAO'));
        }

        if(!$this->delete($arrWhere)){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO'));
        }
    }

    /**
     *
     * @param array $arrWhere array('cd_status_atendimento = ?'=>10)
     */
    public function verificaIntegridadeStatusAtendimento(array $arrWhere )
    {
//        VERIFICAR QUAL SERÁ A INTEGRIDADE
//        $objEtapa              = new Etapa();
//
//        //se existir dados é porque possui dados vinculados
//        if($objEtapa->fetchAll($arrWhere)->valid()){
//            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO'));
//        }
    }

    public function getTempoResposta()
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from($this->_name,
                array('tx_status_atendimento',
                    'tx_rgb_status_atendimento',
                    'ni_percent_tempo_resposta_ini',
                    'ni_percent_tempo_resposta_fim'),
                $this->_schema);
        $select->where('st_status_atendimento = ?', 'T');
        $select->order('ni_percent_tempo_resposta_fim');

        $arr = $this->fetchAll($select)->toArray();

        return $arr;
    }
}