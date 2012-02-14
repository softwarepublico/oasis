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

class PerguntaDependeRespostaPedido extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_PERGUNTA_DEPENDE_RESP_PEDIDO;
	protected $_primary  = array('cd_pergunta_dependente','cd_pergunta_pedido','cd_resposta_pedido');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    public function pesquisaPerguntaForaAssociacao(array $dados)
    {
        $select = $this->select()
                       ->distinct()
                       ->setIntegrityCheck(false);

        $select->from(array('pp'=>KT_B_PERGUNTA_PEDIDO),
                      array('cd_pergunta_pedido',
                            'tx_titulo_pergunta'),
                      $this->_schema);
        $select->join(array('orpp'=>KT_A_OPCAO_RESP_PERGUNTA_PEDIDO),
                      '(pp.cd_pergunta_pedido = orpp.cd_pergunta_pedido)',
                      array(),
                      $this->_schema);

        $subSelect = $this->select()
                          ->from($this->_name,
                                 'cd_pergunta_depende',
                                 $this->_schema)
                          ->where('cd_pergunta_pedido = ?', $dados['cd_pergunta'], Zend_Db::INT_TYPE)
                          ->where('cd_resposta_pedido = ?', $dados['cd_resposta'], Zend_Db::INT_TYPE);

        $select->joinLeft(array('pd'=>$subSelect),
                          '(pd.cd_pergunta_depende = pp.cd_pergunta_pedido)',
                          array());
        $select->where('pd.cd_pergunta_depende is null');

        return $this->fetchAll($select);
    }

    public function pesquisaPerguntaAssociada(array $dados)
    {
        $select = $this->select()
                       ->distinct()
                       ->setIntegrityCheck(false);

        $select->from(array('pp'=>KT_B_PERGUNTA_PEDIDO),
                      array('cd_pergunta_pedido',
                            'tx_titulo_pergunta'),
                      $this->_schema);
        $select->join(array('orpp'=>KT_A_OPCAO_RESP_PERGUNTA_PEDIDO),
                      '(pp.cd_pergunta_pedido = orpp.cd_pergunta_pedido)',
                      array(),
                      $this->_schema);

        $subSelect = $this->select()
                          ->from($this->_name,
                                 'cd_pergunta_depende',
                                 $this->_schema)
                          ->where('cd_pergunta_pedido = ?', $dados['cd_pergunta'], Zend_Db::INT_TYPE)
                          ->where('cd_resposta_pedido = ?', $dados['cd_resposta'], Zend_Db::INT_TYPE);

        $select->joinLeft(array('pd'=>$subSelect),
                          '(pd.cd_pergunta_depende = pp.cd_pergunta_pedido)',
                          array());
        $select->where('pd.cd_pergunta_depende is not null');

        return $this->fetchAll($select);
    }

}