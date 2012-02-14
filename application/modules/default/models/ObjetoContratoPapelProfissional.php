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

class ObjetoContratoPapelProfissional extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_OBJETO_CONTRATO_PAPEL_PROF;
	protected $_primary  = array('cd_objeto', 'cd_papel_profissional');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    /**
     * Método para retornar os papeis profissional não associados ao Objeto do Contrato
     *
     * @param Integer $cd_objeto
     * @param Integer $cd_area_atuacao_ti
     * @return Zend_Db_Table_RowSet
     */
    public function pesquisaPapelForaObjetoContrato($cd_objeto, $cd_area_atuacao_ti)
    {
        $select = $this->_montaSelectPesquisaPapelObjetoContrato($cd_objeto, $cd_area_atuacao_ti);
        $select->where('ocpp.cd_papel_profissional IS NULL');

        return $this->fetchAll($select);
    }

    /**
     * Método para retornar os papeis profissional associados ao Objeto do Contrato
     *
     * @param Integer $cd_objeto
     * @param Integer $cd_area_atuacao_ti
     * @return Zend_Db_Table_RowSet
     */
    public function pesquisaPapelNoObjetoContrato($cd_objeto, $cd_area_atuacao_ti)
    {
        $select = $this->_montaSelectPesquisaPapelObjetoContrato($cd_objeto, $cd_area_atuacao_ti);
        $select->where('ocpp.cd_papel_profissional IS NOT NULL');

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
    private function _montaSelectPesquisaPapelObjetoContrato($cd_objeto, $cd_area_atuacao_ti)
    {
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('perf'=>KT_B_PAPEL_PROFISSIONAL),
                      array('cd_papel_profissional',
                            'tx_papel_profissional'),
                      $this->_schema);
        $select->joinLeft(array('ocpp'=>$this->select()
                                             ->from($this,
                                                    array('cd_papel_profissional',
                                                          'cd_objeto',
                                                          'tx_descricao_papel_prof'))
                                             ->where('cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE)),
                          '(perf.cd_papel_profissional = ocpp.cd_papel_profissional)',
                          array('cd_objeto',
                                'tx_descricao_papel_prof'));
        $select->join(array('aati'=>KT_B_AREA_ATUACAO_TI),
                      '(perf.cd_area_atuacao_ti = aati.cd_area_atuacao_ti)',
                      array(),
                      $this->_schema);
        $select->where('perf.cd_area_atuacao_ti = ?', $cd_area_atuacao_ti, Zend_Db::INT_TYPE);
        $select->order('tx_papel_profissional');

        return $select;
    }

    /**
     * Método utilizado para salvar a descrição do papel associado ao objeto do contrato
     *
     * @param array $arrDados
     * @return Base_Exception_Error
     */
    public function salvarDescricaoPapelProfissional(Array $arrDados=array())
    {
        if(count($arrDados)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_INCLUSAO'));
        }
        $arrUpdate['tx_descricao_papel_prof'] = strip_tags($arrDados['tx_descricao_papel_prof']);
        unset($arrDados['tx_descricao_perfil_prof']);

        $arrWhere['cd_objeto = ?'            ] = $arrDados['cd_objeto'];
        $arrWhere['cd_papel_profissional = ?'] = $arrDados['cd_papel_profissional'];

        if(!$this->update($arrUpdate, $arrWhere)){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO'));
        }
    }

    /**
     * Metodo utilizado para criar as associações dos papeis ao objeto do contrato
     * @param array $arrDados
     * @return Base_Exception_Error
     */
    public function salvarNovoPapelProfissionalAoObjetoContrato(Array $arrDados=array())
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
     * Metodo utilizado para excluir as associações dos papeis ao objeto do contrato
     *
     * @param array $arrDados exemplo: array('cd_objeto = ?'=> 14,'cd_papel_profissional = ?'=> 2)
     * @return Base_Exception_Error
     */
    public function excluirPapelProfissionalAoObjetoContrato(Array $arrDados=array())
    {
        if(count($arrDados)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_EXCLUSAO'));
        }
        if(!$this->delete($arrDados)){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO'));
        }
    }
}