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

class PerguntaPedido extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_PERGUNTA_PEDIDO;
	protected $_primary  = 'cd_pergunta_pedido';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    public function salvarPergunta(Array $arrDados=array())
    {
        if(count($arrDados) == 0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_INCLUSAO'));
        }

        if(!empty($arrDados['cd_pergunta_pedido'])){
            $msg = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
            $row = $this->fetchRow("cd_pergunta_pedido = {$arrDados['cd_pergunta_pedido']}");
        }else{
            $msg = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
            $row = $this->createRow();
        }
        $row->cd_pergunta_pedido   = $this->getNextValueOfField('cd_pergunta_pedido');
        $row->tx_titulo_pergunta   = strip_tags($arrDados['tx_titulo_pergunta'  ]);
        $row->tx_ajuda_pergunta    = strip_tags($arrDados['tx_ajuda_pergunta'   ]);
        $row->st_obriga_resposta   = strip_tags($arrDados['st_obriga_resposta'  ]);
        $row->st_multipla_resposta = strip_tags($arrDados['st_multipla_resposta']);

        if(!$row->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
        }
        return $msg;
    }

    public function excluirPergunta($cd_pergunta_pedido=null)
    {
        if(is_null($cd_pergunta_pedido)){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_EXCLUSAO'));
        }
        if(!$this->delete("cd_pergunta_pedido = {$cd_pergunta_pedido}")){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO'));
        }
    }

    public function getPergunta($cd_pergunta_pedido=null)
    {
        $select = $this->select()
                       ->from($this->_name,
                              array('*',
                                    'st_obriga_resposta_desc'  =>new Zend_Db_Expr("CASE st_obriga_resposta   WHEN 'S' THEN '".Base_Util::getTranslator('L_SQL_SIM')."' else '".Base_Util::getTranslator('L_SQL_NAO')."' end"),
                                    'st_multipla_resposta_desc'=>new Zend_Db_Expr("CASE st_multipla_resposta WHEN 'S' THEN '".Base_Util::getTranslator('L_SQL_SIM')."' else '".Base_Util::getTranslator('L_SQL_NAO')."' end")),
                              $this->_schema)
                       ->order('tx_titulo_pergunta');

        $retorno = '';
        if(!is_null($cd_pergunta_pedido)){
            $select->where('cd_pergunta_pedido = ?', $cd_pergunta_pedido, Zend_Db::INT_TYPE);
            $retorno = $this->fetchRow($select);
        }else{
            $retorno = $this->fetchAll($select);
        }

        return $retorno;
    }

    public function comboPergunta($comSelecione=false, $chamadaAjax=false)
    {
        $resultSet = $this->getPergunta();

        $arrResult = array();
        if($comSelecione === true){
            $arrResult[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        }
        foreach ($resultSet as $pergunta) {
            $arrResult[$pergunta->cd_pergunta_pedido] = $pergunta->tx_titulo_pergunta;
        }
        $retorno = $arrResult;
        
        if($chamadaAjax===true){
            $options = '';
            foreach($arrResult as $key=>$value){
                $fim = "";
				if(strlen($value) >= 50){
					$fim = "...";
				}
				$tx_titulo_pergunta = substr($value,0,50).$fim;
                $options .= "<option title=\"{$value}\" value=\"{$key}\" label=\"{$value}\">{$tx_titulo_pergunta}</option>";
            }
            $retorno = $options;
        }
        return $retorno;
    }

    public function getQuestionario($cd_solicitacao_pedido)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('p' => 'b_pergunta_pedido'),
                      array('*'),
                      $this->_schema);
        $select->join(array('o' => KT_A_OPCAO_RESP_PERGUNTA_PEDIDO),
                      'p.cd_pergunta_pedido = o.cd_pergunta_pedido',
                      array('*'),
                      $this->_schema);
        $select->join(array('r' => KT_B_RESPOSTA_PEDIDO),
                      'r.cd_resposta_pedido = o.cd_resposta_pedido',
                      array('*'),
                      $this->_schema);
        $select->join(array('s' => KT_A_SOLICITACAO_RESPOSTA_PEDIDO),
                      '(o.cd_pergunta_pedido = s.cd_pergunta_pedido) AND
                       (o.cd_resposta_pedido = s.cd_resposta_pedido)',
                      array('*'),
                      $this->_schema);
        $select->where("s.cd_solicitacao_pedido = ?", $cd_solicitacao_pedido, Zend_Db::INT_TYPE);
        $select->order(array('p.tx_titulo_pergunta',
                             'o.ni_ordem_apresenta',
                             'r.cd_resposta_pedido'));

        return $this->fetchAll($select);
    }

}