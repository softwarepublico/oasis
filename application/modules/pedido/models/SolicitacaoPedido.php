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

class SolicitacaoPedido extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_SOLICITACAO_PEDIDO;
	protected $_primary  = 'cd_solicitacao_pedido';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
    //protected $_sequence = 'solicitacao.solicitacao_pedido_sq';

    public function getSituacaoSolicitacao($comSelecione=false)
    {
        //este array não contem o status I pois este, é apenas
        // um indicativo de criação da solicitação de serviço
        if($comSelecione===true){
            $arrSituacao[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        }
        $arrSituacao['D'] = Base_Util::getTranslator('L_SQL_ANALIZADO_COMITE');
        $arrSituacao['A'] = Base_Util::getTranslator('L_SQL_AUTORIZADO');
        $arrSituacao['X'] = Base_Util::getTranslator('L_SQL_RESERVADO');
        $arrSituacao['T'] = Base_Util::getTranslator('L_SQL_COMITE');
        $arrSituacao['C'] = Base_Util::getTranslator('L_SQL_COMPLETAR');
        $arrSituacao['E'] = Base_Util::getTranslator('L_SQL_ENCAMINHADO');
        $arrSituacao['M'] = Base_Util::getTranslator('L_SQL_MODIFICAR');
        $arrSituacao['P'] = Base_Util::getTranslator('L_SQL_PREENCHIDO');
        $arrSituacao['R'] = Base_Util::getTranslator('L_SQL_RECUSADO');
        $arrSituacao['J'] = Base_Util::getTranslator('L_SQL_REJEITADO');
        $arrSituacao['S'] = Base_Util::getTranslator('L_SQL_SOLICITACAO_SERVICO');
        $arrSituacao['V'] = Base_Util::getTranslator('L_SQL_VALIDADO');
        $arrSituacao['I'] = Base_Util::getTranslator('L_SQL_SOLICITACAO_SERVICO_CRIADA' );
        
        return $arrSituacao;
    }

    /**
     * Método para retornar a quantidade de solicitações existentes
     *
     * @param Array $arrWhere Opcional Exemplo: array('cd_solicitacao_pedido = ?'=> 5)
     * @return int
     */
    public function getTotalSolicitacaoPedido(Array $arrWhere=array())
    {
        $select = $this->select();

        if(count($arrWhere) > 0 ){
            foreach($arrWhere as $campo=>$valor){
                $select->where($campo,$valor);
            }
        }

        $objSelectCount = $this->getDefaultAdapter()->select()
                                                    ->from($select, 'COUNT(*)');

        //transforma o resultado do select em inteiro
        $qtdRegistros = $this->getDefaultAdapter()->fetchOne($objSelectCount);

        return $qtdRegistros;
    }

    /**
     * Método para retornar as finormações das solicitações
     *
     * @param Array        $arrWhere Opcional Exemplo: array('cd_solicitacao_pedido = ?'=> 5)
     * @param String|Array $order    Opcional Exemplo: 'cd_solicitacao_pedido DESC' ou array('cd_solicitacao_pedido DESC', 'st_situacao_pedido')
     * @param Array        $ArrLimit Opcional Exemplo: array('limit'=>10, 'start'=> 20)
     * @return Zend_Db_Table_Rowset
     */
    public function getSolicitacaoPedido(Array $arrWhere = array(), $order='', Array $arrLimit=array())
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('solped'=>$this->_name),
                      array('cd_solicitacao_pedido',
                            'cd_usuario_pedido',
                            'cd_unidade_pedido',
                            'dt_solicitacao_pedido',
                            'st_situacao_pedido',
                            'tx_observacao_pedido',
                            'dt_encaminhamento_pedido',
                            'dt_autorizacao_competente',
                            'tx_analise_aut_competente',
                            'dt_analise_area_ti_solicitacao',
                            'tx_analise_area_ti_solicitacao',
                            'dt_analise_area_ti_chefia_sol',
                            'tx_analise_area_ti_chefia_sol',
                            'dt_analise_comite',
                            'tx_analise_comite',
                            'dt_analise_area_ti_chefia_exec',
                            'tx_analise_area_ti_chefia_exec',
                            'cd_usuario_aut_competente',
                            'st_situacao_pedido_desc'=>new Zend_Db_Expr("case st_situacao_pedido
                                                                                  when 'D' then '".Base_Util::getTranslator('L_SQL_ANALIZADO_COMITE'    )."'
                                                                                  when 'P' then '".Base_Util::getTranslator('L_SQL_PREENCHIDO'          )."'
                                                                                  when 'E' then '".Base_Util::getTranslator('L_SQL_ENCAMINHADO'         )."'
                                                                                  when 'A' then '".Base_Util::getTranslator('L_SQL_AUTORIZADO'          )."'
                                                                                  when 'M' then '".Base_Util::getTranslator('L_SQL_MODIFICAR'           )."'
                                                                                  when 'R' then '".Base_Util::getTranslator('L_SQL_RECUSADO'            )."'
                                                                                  when 'J' then '".Base_Util::getTranslator('L_SQL_REJEITADO'           )."'
                                                                                  when 'C' then '".Base_Util::getTranslator('L_SQL_COMPLETAR'           )."'
                                                                                  when 'V' then '".Base_Util::getTranslator('L_SQL_VALIDADO'            )."'
                                                                                  when 'T' then '".Base_Util::getTranslator('L_SQL_COMITE'              )."'
                                                                                  when 'S' then '".Base_Util::getTranslator('L_SQL_SOLICITACAO_SERVICO' )."'
                                                                                  when 'I' then '".Base_Util::getTranslator('L_SQL_SOLICITACAO_SERVICO_CRIADA' )."'
                                                                                  when 'X' then '".Base_Util::getTranslator('L_SQL_RESERVADO'           )."' end")),
                      $this->_schema)
              ->join(array('unid' => KT_B_UNIDADE),
              '(solped.cd_unidade_pedido = unid.cd_unidade)',
              array('tx_sigla_unidade'),
              $this->_schema);

        if(count($arrWhere) > 0 ){
            foreach($arrWhere as $campo=>$valor){
                $select->where($campo,$valor);
            }
        }
        if(!empty($order)){
            $select->order($order);
        }else{
            $select->order('dt_solicitacao_pedido DESC');
        }
        if(count($arrLimit) > 0 ){
            $select->limit($arrLimit['limit'], $arrLimit['start']);
        }
        return $this->fetchAll($select);
    }

    /**
     * Método para retornar as finformações das solicitações
     *
     * @param Array $arrParam As condições para o select são opcionais
     *
     * @example array('st_situacao_pedido IN (?)'=>array('T','V'),'cd_usuario_pedido = ?'=>5)
     * 
     * @return Zend_Db_Table_Rowset
     */
    public function getSelicitacaoValidadaParaEncaminhar(Array $arrParam=array())
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('p' => 's_solicitacao_pedido'),
                      array('cd_solicitacao_pedido',
                            'cd_usuario_pedido',
                            'cd_unidade_pedido',
                            'dt_solicitacao_pedido',
                            'st_situacao_pedido',
                            'tx_observacao_pedido'),
                      $this->_schema);
        $select->join(array('up'=>'s_usuario_pedido'),
                      '(p.cd_usuario_pedido = up.cd_usuario_pedido)',
                      array('tx_nome_usuario'),
                      $this->_schema);
        $select->join(array('unid'=>KT_B_UNIDADE),
                      '(up.cd_unidade_usuario = unid.cd_unidade)',
                      array('tx_sigla_unidade'),
                      $this->_schema);
        $select->order(array('dt_solicitacao_pedido',
                             'cd_unidade_pedido',
                             'tx_nome_usuario'));

        if(count($arrParam) > 0 ){
            foreach($arrParam as $campo=>$valor){
                $select->where($campo,$valor);
            }
        }
        return $this->fetchAll($select);
    }

    public function insertSolicitacao(Array $arrInsert)
    {
        return $this->insert($arrInsert);
    }

    public function atualizaStatusPedido(Array $arrUpdate)
    {
        $row = $this->fetchRow("cd_solicitacao_pedido = {$arrUpdate['cd_solicitacao_pedido']}");

        if(array_key_exists('tx_observacao_pedido', $arrUpdate)){
            $row->tx_observacao_pedido = $arrUpdate['tx_observacao_pedido'];
        }
        
        $row->st_situacao_pedido = $arrUpdate['st_situacao_pedido'];

        if(!$row->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ATUALIZAR_STATUS_PEDIDO'));
        }
    }

    /**
     * Método para registrar o encaminhamento do pedido. Registra o status
     * para qual o pedido foi encaminhado
     
     * @param Array $arrDados Os dados obrigatórios no array são: 'cd_solicitacao_pedido' e 'status'
     * 
     * @return Base_Exception_Error caso ocorra algum erro durante a operação
     */
    public function registraEncaminhamentoSolicitantePedido(Array $arrDados = array())
    {
        if(count($arrDados)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_ATUALIZACAO_STATUS_PEDIDO'));
        }
        if($arrDados['cd_solicitacao_pedido'] == ''){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_SEM_CHAVE_DO_PEDIDO_ATUALIZAR_STATUS'));
        }
        $where = "cd_solicitacao_pedido = {$arrDados['cd_solicitacao_pedido']}";
        $row   = $this->fetchRow($where);

        $row->dt_encaminhamento_pedido = (!empty ($arrDados['dt_encaminhamento_pedido'])) ? $arrDados['dt_encaminhamento_pedido'] : null;
        $row->st_situacao_pedido       = $arrDados['st_situacao_pedido'];

        if(!$row->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ATUALIZAR_STATUS_ENCAMINHAMENTO'));
        }
    }

     /**
     * Método para registrar o encaminhamento da autorização do pedido
     *
     * @param Array $arrDados
     *
     * @return Base_Exception_Error caso ocorra algum erro durante a operação
     */
    public function registraEncaminhamentoAutorizacaoPedido(Array $arrDados = array())
    {
        if(count($arrDados)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_ATUALIZACAO_STATUS_PEDIDO'));
        }
        if($arrDados['cd_solicitacao_pedido'] == ''){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_SEM_CHAVE_DO_PEDIDO_ATUALIZAR_STATUS'));
        }
        $where = "cd_solicitacao_pedido = {$arrDados['cd_solicitacao_pedido']}";
        $row   = $this->fetchRow($where);

        $row->dt_autorizacao_competente         = (!empty ($arrDados['dt_autorizacao_competente'])) ? $arrDados['dt_autorizacao_competente'] : null;
        $row->tx_analise_aut_competente         = $arrDados['tx_analise_aut_competente'];
        $row->st_situacao_pedido                = $arrDados['st_situacao_pedido'];
        $row->cd_usuario_aut_competente         = $arrDados['cd_usuario_aut_competente'];

        if(!$row->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ATUALIZAR_STATUS_ENCAMINHAMENTO'));
        }
    }

     /**
     * Método para registrar o encaminhamento da validação do pedido
     *
     * @param Array $arrDados
     *
     * @return Base_Exception_Error caso ocorra algum erro durante a operação
     */
    public function registraEncaminhamentoValidacaoPedido(Array $arrDados = array())
    {

        if(count($arrDados)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_ATUALIZACAO_STATUS_PEDIDO'));
        }
        if($arrDados['cd_solicitacao_pedido'] == ''){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_SEM_CHAVE_DO_PEDIDO_ATUALIZAR_STATUS'));
        }
        $where = "cd_solicitacao_pedido = {$arrDados['cd_solicitacao_pedido']}";
        $row   = $this->fetchRow($where);

        $row->dt_analise_area_ti_solicitacao = (!empty ($arrDados['dt_analise_area_ti_solicitacao'])) ? $arrDados['dt_analise_area_ti_solicitacao']: null;
        $row->tx_analise_area_ti_solicitacao = $arrDados['tx_analise_area_ti_solicitacao'];
        $row->st_situacao_pedido             = $arrDados['st_situacao_pedido'];

        if(!$row->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ATUALIZAR_STATUS_ENCAMINHAMENTO'));
        }
    }

     /**
     * Método para registrar o encaminhamento do pedido para o comite
     *
     * @param Array $arrDados
     *
     * @return Base_Exception_Error caso ocorra algum erro durante a operação
     */
    public function registraEncaminhamentoParaComite(Array $arrDados = array())
    {

        if(count($arrDados)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_ATUALIZACAO_STATUS_PEDIDO'));
        }
        if($arrDados['cd_solicitacao_pedido'] == ''){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_SEM_CHAVE_DO_PEDIDO_ATUALIZAR_STATUS'));
        }
        $where = "cd_solicitacao_pedido = {$arrDados['cd_solicitacao_pedido']}";
        $row   = $this->fetchRow($where);

        $row->dt_analise_area_ti_chefia_sol         = (!empty ($arrDados['dt_analise_area_ti_chefia_sol'])) ? $arrDados['dt_analise_area_ti_chefia_sol'] : null;
        $row->tx_analise_area_ti_chefia_sol         = $arrDados['tx_analise_area_ti_chefia_sol'];
        $row->st_situacao_pedido                    = $arrDados['st_situacao_pedido'];

        if(!$row->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ATUALIZAR_STATUS_ENCAMINHAMENTO'));
        }
    }

     /**
     * Método para registrar o encaminhamento  para o comite efetuado pelo comite
     *
     * @param Array $arrDados
     *
     * @return Base_Exception_Error caso ocorra algum erro durante a operação
     */
    public function registraEncaminhamentoRecomendacaoComite(Array $arrDados = array())
    {

        if(count($arrDados)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_ATUALIZACAO_STATUS_PEDIDO'));
        }
        if($arrDados['cd_solicitacao_pedido'] == ''){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_SEM_CHAVE_DO_PEDIDO_ATUALIZAR_STATUS'));
        }
        $where = "cd_solicitacao_pedido = {$arrDados['cd_solicitacao_pedido']}";
        $row   = $this->fetchRow($where);

        $row->dt_analise_comite  = (!empty ($arrDados['dt_analise_comite'])) ? $arrDados['dt_analise_comite'] : null ;
        $row->tx_analise_comite  = $arrDados['tx_analise_comite' ];
        $row->st_situacao_pedido = $arrDados['st_situacao_pedido'];

        if(!$row->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ATUALIZAR_STATUS_ENCAMINHAMENTO'));
        }
    }
     /**
     * Método para registrar o encaminhamento final do pedido, este encaminhamento envia o pedido
     * para Solicitação de Proposta ou Arquivamento do pedido para o proximo contrato
     *
     * @param Array $arrDados
     *
     * @return Base_Exception_Error caso ocorra algum erro durante a operação
     */
    public function registraEncaminhamentoFinalPedido(Array $arrDados = array())
    {

        if(count($arrDados)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_ATUALIZACAO_STATUS_PEDIDO'));
        }
        if($arrDados['cd_solicitacao_pedido'] == ''){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_SEM_CHAVE_DO_PEDIDO_ATUALIZAR_STATUS'));
        }
        $where = "cd_solicitacao_pedido = {$arrDados['cd_solicitacao_pedido']}";
        $row   = $this->fetchRow($where);

        $row->dt_analise_area_ti_chefia_exec     = (!empty ($arrDados['dt_analise_area_ti_chefia_exec'])) ? $arrDados['dt_analise_area_ti_chefia_exec'] : null;
        $row->tx_analise_area_ti_chefia_exec     = $arrDados['tx_analise_area_ti_chefia_exec'];
        $row->st_situacao_pedido                 = $arrDados['st_situacao_pedido'];

        if(!$row->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ATUALIZAR_STATUS_ENCAMINHAMENTO'));
        }
    }
}