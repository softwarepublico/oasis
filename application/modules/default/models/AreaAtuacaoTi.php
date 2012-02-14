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

class AreaAtuacaoTi extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_AREA_ATUACAO_TI;
	protected $_primary  = 'cd_area_atuacao_ti';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    /**
     * Método para recuperar as areas de atuação
     *
     * @param Array $arrWhere
     * @param String|Array $order
     *
     * @return Zend_Db_Table_Rowset
     */
    public function getAreaAtuacaoTi(Array $arrWhere=array(), $order='')
    {
        $select = $this->select()
                       ->from($this,
                              array('cd_area_atuacao_ti',
                                    'tx_area_atuacao_ti'));

        if(count($arrWhere) > 0 ){
            foreach ($arrWhere as $condicao => $valor) {
                $select->where($condicao, $valor);
            }
        }
        if($order != ''){
            $select->order($order);
        }else{
            $select->order('tx_area_atuacao_ti');
        }

        return $this->fetchAll($select);

    }

    public function pegarAreaAtuacaoTi($cd_area_atuacao_ti)
    {
        $select = $this->select()
                       ->from($this,
                              'tx_area_atuacao_ti');
         $select->where('cd_area_atuacao_ti = ? ', $cd_area_atuacao_ti, Zend_Db::INT_TYPE);

        return $this->fetchrow($select)->toArray();

    }


    public function comboAreaAtuacaoTi($comSelecione=false, $chamadaAjax=false)
    {
        $select = $this->select()
                       ->from($this,
                              array('cd_area_atuacao_ti',
                                    'tx_area_atuacao_ti'))
                       ->order('tx_area_atuacao_ti');

        $rowSet = $this->fetchAll($select);

        $arrAreaAtuacao = array();

        if($comSelecione === true){
            $arrAreaAtuacao[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        }
        foreach ($rowSet as $row) {
            $arrAreaAtuacao[$row->cd_area_atuacao_ti] = $row->tx_area_atuacao_ti;
        }

        if($chamadaAjax === true){
            $strOption = '';
            foreach ($arrAreaAtuacao as $key=>$value){
                $strOption .= "<option label=\"{$value}\" value=\"{$key}\">{$value}</option>";
            }
            return $strOption;
        }else{
            return $arrAreaAtuacao;
        }
    }

    /**
     * Método utilizado para salvar uma nova Area de atuação ou alterar uma area ja existente
     * @param array $arrDados
     * @return String $msg ou Base_Exception_Error
     */
    public function salvarAreaAtuacaoTi(Array $arrDados=array())
    {
        if(count($arrDados) == 0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_INCLUSAO'));
        }

        if(!empty($arrDados['cd_area_atuacao_ti'])){
            $msg = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
            $row = $this->fetchRow("cd_area_atuacao_ti = {$arrDados['cd_area_atuacao_ti']}");
        }else{
            $msg = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
            $row = $this->createRow();
            $row->cd_area_atuacao_ti = $this->getNextValueOfField('cd_area_atuacao_ti');
        }
        
        $row->tx_area_atuacao_ti = $arrDados['tx_area_atuacao_ti'];

        if(!$row->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
        }
        return $msg;
    }


    /**
     *
     * @param array $arrWhere array('cd_area_atuacao_ti = ?'=>10)
     */
    public function excluirAreaAtuacaoTi( array $arrWhere=array())
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
     * @param array $arrWhere array('cd_area_atuacao_ti = ?'=>10)
     */
    public function verificaIntegridadeAreaAtuacao(array $arrWhere )
    {
        $objEtapa              = new Etapa();
        $objPerfilProfissional = new PerfilProfissional();
        $objPapelProfissional  = new PapelProfissional();

        //se existir dados é porque possui dados vinculados
        if($objEtapa->fetchAll($arrWhere)->valid()){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO'));
        }

        //se existir dados é porque possui dados vinculados
        if($objPerfilProfissional->fetchAll($arrWhere)->valid()){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO_PERFIL_PROFISSIONAL'));
        }

        //se existir dados é porque possui dados vinculados
        if($objPapelProfissional->fetchAll($arrWhere)->valid()){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO_PAPEL_PROFISSIONAL'));
        }
    }

}