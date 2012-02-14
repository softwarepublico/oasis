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

class UsuarioPedido extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_USUARIO_PEDIDO;
	protected $_primary  = 'cd_usuario_pedido';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
    //protected $_sequence = "solicitacao.usuario_pedido_sq";

    /**
     * Método para recuperar os usuários cadastrados no modulo Pedido
     * 
     * @param Array $arrWhere
     * @param String|Array $order
     *
     * @return Zend_Db_RowSet
     */
    public function getUsuario(Array $arrWhere = array(), $order=null)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('up'=>$this->_name),
                      array('cd_usuario_pedido',
                            'cd_unidade_usuario',
                            'st_autoridade',
                            'st_inativo',
                            'tx_email_institucional',
                            'tx_nome_usuario',
                            'tx_sala_usuario',
                            'tx_telefone_usuario'),
                      $this->_schema);
        $select->join(array('unid' => KT_B_UNIDADE),
                      '(up.cd_unidade_usuario = unid.cd_unidade)',
                      array('cd_unidade',
                            'tx_sigla_unidade'),
                      //K_SCHEMA);
                      $this->_schema);

        if(count($arrWhere) > 0){
            foreach($arrWhere as $key=>$value){
                $select->where($key,$value);
            }
        }

        if(!is_null($order)){
            $select->order($order);
        }
        return $this->fetchAll($select);
    }

    public function salvarUsuario(Array $arrUsuario)
    {
        $novo             = $this->createRow();

        $novo->cd_usuario_pedido      = $this->getNextValueOfField('cd_usuario_pedido');
        $novo->cd_unidade_usuario     = $arrUsuario['cd_unidade_usuario'];
        $novo->tx_nome_usuario        = $arrUsuario['tx_nome_usuario'];
        $novo->tx_sala_usuario        = $arrUsuario['tx_sala_usuario'];
        $novo->tx_telefone_usuario    = $arrUsuario['tx_telefone_usuario'];
        $novo->tx_email_institucional = $arrUsuario['tx_email_institucional'];
        $novo->tx_senha_acesso        = isset($arrUsuario['tx_senha_acesso']) ? md5($arrUsuario['tx_senha_acesso']) : null;

		if(!$novo->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_SALVAR_USUARIO'));
		} else{
            return false;
		}
    }

    /**
     * Método para atualizar o status do usuário em relação a sua autoridade
     * 
     * @param Array $formData OBS: as chaves obrigatorias para o array são: 'cd_usuario_pedido' e 'st_autoridade'
     *
     * @return Base_Exception_Error ou Base_Exception_Alert
     */
    public function salvarStatusAutoridade(Array $formData)
    {
        if(count($formData) == 0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_ALTERACAO'));
        }

        $where  = "cd_usuario_pedido  = {$this->getDefaultAdapter()->quote($formData['cd_usuario_pedido'], Zend_Db::INT_TYPE)}";
        $row = $this->fetchRow($where);

        $row->st_autoridade  = $formData['st_autoridade'];

        if(!$row->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
        }
    }
}