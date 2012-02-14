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

class RespostaPedido extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_RESPOSTA_PEDIDO;
	protected $_primary  = 'cd_resposta_pedido';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
    //protected $_sequence = 'solicitacao.resposta_pedido_sq';


    public function getResposta($cd_resposta_pedido=null)
    {
        $select = $this->select()->order('tx_titulo_resposta');

        $retorno = '';
        if(!is_null($cd_resposta_pedido )){
            $select->where('cd_resposta_pedido = ?', $cd_resposta_pedido , Zend_Db::INT_TYPE);
            $retorno = $this->fetchRow($select);
        }else{
            $retorno = $this->fetchAll($select);
        }
        return $retorno;
    }

    public function comboResposta($comSelecione=false)
    {
        $resultSet = $this->getResposta();

        $arrResult = array();
        if($comSelecione === true){
            $arrResult[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        }
        foreach ($resultSet as $resposta) {
            $arrResult[$resposta->cd_resposta_pedido] = $resposta->tx_titulo_resposta;
        }

        return $arrResult;
    }
}