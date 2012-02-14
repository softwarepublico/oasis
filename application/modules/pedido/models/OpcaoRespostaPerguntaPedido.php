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

class OpcaoRespostaPerguntaPedido extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_OPCAO_RESP_PERGUNTA_PEDIDO;
	protected $_primary  = array('cd_pergunta_pedido', 'cd_resposta_pedido');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    public function getTipoResposta($comSelecione=false)
    {
        if($comSelecione===true){
            $arrTipoResposta[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        }
        $arrTipoResposta['N'] = Base_Util::getTranslator('L_VIEW_OPCAO');
        $arrTipoResposta['S'] = Base_Util::getTranslator('L_VIEW_FRASE');
        $arrTipoResposta['T'] = Base_Util::getTranslator('L_VIEW_TEXTO');
        $arrTipoResposta['U'] = Base_Util::getTranslator('L_VIEW_ARQUIVO');
        
        return $arrTipoResposta;
    }

    public function getRespostaPergunta($cd_pergunta)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('orpp'=>$this->_name),
                      'cd_resposta_pedido',
                      $this->_schema);
        $select->join(array('rp'=>KT_B_RESPOSTA_PEDIDO),
                      '(orpp.cd_resposta_pedido = rp.cd_resposta_pedido)',
                      'tx_titulo_resposta',
                      $this->_schema);
        $select->where('cd_pergunta_pedido = ?', $cd_pergunta, Zend_Db::INT_TYPE);
        $select->order('tx_titulo_resposta');

        return $this->fetchAll($select);
    }

    public function comboResposta($cd_pergunta, $comSelecione=false, $chamadaAjax=false)
    {
        $resultSet = $this->getRespostaPergunta($cd_pergunta);

        $arr = array();
        if($comSelecione===true){
            $arr[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        }
        foreach($resultSet as $resposta){
            $arr[$resposta->cd_resposta_pedido] = $resposta->tx_titulo_resposta;
        }
        $retorno = $arr;
        if($chamadaAjax===true){
            $options = '';
            foreach($arr as $key=>$value){
                $fim = "";
				if(strlen($value) >= 50){
					$fim = "...";
				}
				$tx_titulo_resposta = substr($value,0,50).$fim;
                $options .= "<option title=\"{$value}\" value=\"{$key}\" label=\"{$value}\">{$tx_titulo_resposta}</option>";
            }
            $retorno = $options;
        }
        return $retorno;
    }

    public function getOpcaoRespostaPergunta($cd_pergunta)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);
                       
        $select->from(array('rp' => KT_B_RESPOSTA_PEDIDO),
                      array('cd_resposta_pedido',
                            'tx_titulo_resposta'),
                      $this->_schema);
        $select->join(array('orpp' => $this->_name),
                      '(rp.cd_resposta_pedido = orpp.cd_resposta_pedido)',
                      array('cd_pergunta_pedido',
                            'st_resposta_texto',
                            'ni_ordem_apresenta',
                            'st_resposta_texto_desc'=>new Zend_Db_Expr("CASE st_resposta_texto WHEN 'N' THEN '".Base_Util::getTranslator('L_SQL_OPCAO')."'
                                                                                               WHEN 'S' THEN '".Base_Util::getTranslator('L_SQL_FRASE')."'
                                                                                               WHEN 'T' THEN '".Base_Util::getTranslator('L_SQL_TEXTO')."'
                                                                                               WHEN 'U' THEN '".Base_Util::getTranslator('L_SQL_ARQUIVO')."' end")),
                      $this->_schema);
        $select->where("orpp.cd_pergunta_pedido = ?", $cd_pergunta, Zend_Db::INT_TYPE);
        $select->order(array('orpp.ni_ordem_apresenta',
                             'rp.tx_titulo_resposta'));

        return $this->fetchAll($select);
    }

    public function salvarOpcaoRespostaPergunta(array $formData=array())
    {
        if(count($formData) == 0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_INCLUSAO'));
        }
        $where  = "cd_pergunta_pedido = {$this->getDefaultAdapter()->quote($formData['cd_pergunta_pedido'], Zend_Db::INT_TYPE)} and ";
        $where .= "cd_resposta_pedido = {$this->getDefaultAdapter()->quote($formData['cd_resposta_pedido'], Zend_Db::INT_TYPE)}";

        $row = $this->fetchRow($where);
        
        if(is_object($row)){
            $msg = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
            
            $row->st_resposta_texto  = $formData['st_resposta_texto' ];
            $row->ni_ordem_apresenta = $formData['ni_ordem_apresenta'];
        }else{
            $msg = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
            $row = $this->createRow();
            
            $row->cd_pergunta_pedido = $formData['cd_pergunta_pedido'];
            $row->cd_resposta_pedido = $formData['cd_resposta_pedido'];
            $row->st_resposta_texto  = $formData['st_resposta_texto' ];
            $row->ni_ordem_apresenta = $formData['ni_ordem_apresenta'];
        }
        if(!$row->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
        }
        return $msg;
    }

    public function excluirOpcaoRespostaPergunta(array $formData)
    {
        if(count($formData) == 0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_EXCLUSAO'));
        }
        $where  = "cd_pergunta_pedido = {$this->getDefaultAdapter()->quote($formData['cd_pergunta_pedido'], Zend_Db::INT_TYPE)} and ";
        $where .= "cd_resposta_pedido = {$this->getDefaultAdapter()->quote($formData['cd_resposta_pedido'], Zend_Db::INT_TYPE)}";

        if(!$this->delete($where)){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO'));
        }
    }

    public function getQuestionario($cd_solicitacao_pedido)
    {
        $select = $this->_montaInicioSelectQuestionario();

        $select->join(array('srp' => 'a_solicitacao_resposta_pedido'),
                      '(orpp.cd_pergunta_pedido = srp.cd_pergunta_pedido) AND
                       (orpp.cd_resposta_pedido = srp.cd_resposta_pedido)',
                      array('cd_solicitacao_pedido',
                            'tx_descricao_resposta'),
                      $this->_schema);
        $select->where("cd_solicitacao_pedido = ?", $cd_solicitacao_pedido, Zend_Db::INT_TYPE);
        $select->order(array('tx_titulo_pergunta',
                             'ni_ordem_apresenta',
                             'cd_resposta_pedido'));

        return $this->fetchAll($select);
    }

    /**
     * Metodo para montar o inicio dos selects usados para a montagem do questionario
     * 
     * @return Zend_Db_Table_Select
     */
    private function _montaInicioSelectQuestionario()
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('pp' => KT_B_PERGUNTA_PEDIDO),
                      array('cd_pergunta_pedido',
                            'tx_titulo_pergunta',
                            'st_multipla_resposta',
                            'st_obriga_resposta',
                            'tx_ajuda_pergunta'),
                      $this->_schema);
        $select->join(array('orpp' => $this->_name),
                      '(pp.cd_pergunta_pedido = orpp.cd_pergunta_pedido)',
                      array('st_resposta_texto',
                            'ni_ordem_apresenta'),
                      $this->_schema);
        $select->join(array('rp' => KT_B_RESPOSTA_PEDIDO),
                      '(orpp.cd_resposta_pedido = rp.cd_resposta_pedido)',
                      array('cd_resposta_pedido',
                            'tx_titulo_resposta'),
                      $this->_schema);
                  
        return $select;
    }

    public function getQuestionarioPreenchimentoPedido($cd_solicitacao_pedido)
    {
        $select = $this->_montaInicioSelectQuestionario();

        $select->joinLeft(array('srp' => KT_A_SOLICITACAO_RESPOSTA_PEDIDO),
                          "(orpp.cd_pergunta_pedido    = srp.cd_pergunta_pedido) AND
                           (orpp.cd_resposta_pedido    = srp.cd_resposta_pedido) AND
                           (srp.cd_solicitacao_pedido  = {$cd_solicitacao_pedido})",
                          array('tx_descricao_resposta',
                                'cd_solicitacao_resposta'=>'cd_resposta_pedido'),
                          $this->_schema);
                         
        $select->order(array('pp.tx_titulo_pergunta',
                             'orpp.ni_ordem_apresenta',
                             'rp.cd_resposta_pedido'));

        return $this->fetchAll($select);
    }

    public function verificaExistenciaDados($cd_pergunta_pedido)
    {
        return $this->fetchAll($this->select()->where('cd_pergunta_pedido = ?', $cd_pergunta_pedido, Zend_Db::INT_TYPE));
    }
}