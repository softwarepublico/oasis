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

class ObjetoContratoPerfilProfissional extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_OBJETO_CONTRATO_PERFIL_PROF;
	protected $_primary  = array('cd_objeto', 'cd_perfil_profissional');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    /**
     * Método para retornar os perfil profissional não associados ao Objeto do Contrato
     *
     * @param Integer $cd_objeto
     * @param Integer $cd_area_atuacao_ti
     * @return Zend_Db_Table_RowSet
     */
    public function pesquisaPerfilForaObjetoContrato($cd_objeto, $cd_area_atuacao_ti)
    {
        $select = $this->_montaSelectPesquisaPerfilObjetoContrato($cd_objeto, $cd_area_atuacao_ti);
        $select->where('ocpp.cd_perfil_profissional IS NULL');
        
        return $this->fetchAll($select);
    }

    /**
     * Método para retornar os perfil profissional associados ao Objeto do Contrato
     *
     * @param Integer $cd_objeto
     * @param Integer $cd_area_atuacao_ti
     * @return Zend_Db_Table_RowSet
     */
    public function pesquisaPerfilNoObjetoContrato($cd_objeto, $cd_area_atuacao_ti)
    {
        $select = $this->_montaSelectPesquisaPerfilObjetoContrato($cd_objeto, $cd_area_atuacao_ti);
        $select->where('ocpp.cd_perfil_profissional IS NOT NULL');

        return $this->fetchAll($select);
    }

    /**
     * Método utilizado para montar o objeto Select generico para recuperar as informações
     * das associações de perfil ao objeto do contrato
     * 
     * @param Integer $cd_objeto
     * @param Integer $cd_area_atuacao_ti
     * @return Zend_Db_Table_Select
     */
    private function _montaSelectPesquisaPerfilObjetoContrato($cd_objeto, $cd_area_atuacao_ti)
    {
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('perf'=>KT_B_PERFIL_PROFISSIONAL),
                      array('cd_perfil_profissional',
                            'tx_perfil_profissional'),
                      $this->_schema);
        $select->joinLeft(array('ocpp'=>$this->select()
                                             ->from($this, 
                                                    array('cd_perfil_profissional',
                                                          'cd_objeto',
                                                          'tx_descricao_perfil_prof'))
                                             ->where('cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE)),
                          '(perf.cd_perfil_profissional = ocpp.cd_perfil_profissional)',
                          array('cd_objeto',
                                'tx_descricao_perfil_prof'));
        $select->join(array('aati'=>KT_B_AREA_ATUACAO_TI),
                      '(perf.cd_area_atuacao_ti = aati.cd_area_atuacao_ti)',
                      array(),
                      $this->_schema);
        $select->where('perf.cd_area_atuacao_ti = ?', $cd_area_atuacao_ti, Zend_Db::INT_TYPE);
        $select->order('tx_perfil_profissional');

        return $select;
    }

    /**
     * Método utilizado para salvar a descrição do perfil associado ao objeto do contrato
     *
     * @param array $arrDados
     * @return Base_Exception_Error
     */
    public function salvarDescricaoPerfilProfissional(Array $arrDados=array())
    {
        if(count($arrDados)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_INCLUSAO'));
        }
        $arrUpdate['tx_descricao_perfil_prof'] = strip_tags($arrDados['tx_descricao_perfil_prof']);
        unset($arrDados['tx_descricao_perfil_prof']);

        $arrWhere['cd_objeto = ?'             ] = $arrDados['cd_objeto'];
        $arrWhere['cd_perfil_profissional = ?'] = $arrDados['cd_perfil_profissional'];

        if(!$this->update($arrUpdate, $arrWhere)){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO'));
        }
    }

    /**
     * Metodo utilizado para criar as associações dos perfis ao objeto do contrato
     * @param array $arrDados
     * @return Base_Exception_Error
     */
    public function salvarNovoPerfilProfissionalAoObjetoContrato(Array $arrDados=array())
    {
        if(count($arrDados)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_INCLUSAO'));
        }
        $row = $this->createRow($arrDados);
        if(!$row->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
        }
    }

    /**
     * Metodo utilizado para excluir as associações dos perfis ao objeto do contrato
     *
     * @param array $arrDados exemplo: array('cd_objeto = ?'=> 14,'cd_perfil_profissional = ?'=> 2)
     * @return Base_Exception_Error
     */
    public function excluirPerfilProfissionalAoObjetoContrato(Array $arrDados=array())
    {
        if(count($arrDados)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_EXCLUSAO'));
        }
        if(!$this->delete($arrDados)){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO'));
        }
    }

}