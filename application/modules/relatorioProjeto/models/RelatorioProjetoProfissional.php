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

class RelatorioProjetoProfissional extends Base_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;
    private $_objProfissional;

	/**
	 * método que retorna os dados do profissional, mais verifica se o mesmo
	 * esta com seu perfil e com o objeto ativo.
	 * 
	 * @uses Método utilizado na tela de Relatorio-Projeto-Profissional
	 * @param array $arrDadosSql
	 */
	public function relDadosProfissional($cd_objeto, $isObject=false)
	{
        $this->_objProfissional = new Profissional();

        $select = $this->_objProfissional->select()->setIntegrityCheck(false);

        $select->from(array('prof'=>KT_S_PROFISSIONAL),
                      array('tx_profissional',
                            'tx_nome_conhecido',
                            'tx_telefone_residencial',
                            'tx_celular_profissional',
                            'tx_ramal_profissional',
                            'tx_email_pessoal',
                            'dt_nascimento'=>'dt_nascimento_profissional'),
                      $this->_schema);

        $select->join(array('poc'=>KT_A_PROFISSIONAL_OBJETO_CONTRATO),
                      'prof.cd_profissional = poc.cd_profissional',
                      array(),
                      $this->_schema);

        $select->join(array('pp'=>KT_B_PERFIL_PROFISSIONAL),
                      'poc.cd_perfil_profissional = pp.cd_perfil_profissional',
                      array('tx_perfil_profissional'),
                      $this->_schema);

        $select->where('prof.st_inativo is null')
               ->where ('poc.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE)
               ->where('poc.cd_perfil_profissional is not null');

        $select->order('tx_profissional');

        $retorno = $this->fetchAll($select);
        if($isObject === false){
            $retorno = $retorno->toArray();
        }
        return $retorno;
	}
    
    /* Método utilizados no Relatório Profissional Projeto*/
	
    /**
     * @param ARRAY $params
     * @return ARRAY $arrResult
     */
    public function relProjetoPorProfissional($isObject=false)
    {
        $this->_objProfissional = new Profissional();

        $select = $this->_objProfissional->select()
                                         ->distinct()
                                         ->setIntegrityCheck(false);

        $select->from(array('pp'=>KT_A_PROFISSIONAL_PROJETO),
                      array(),
                      $this->_schema);

        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      'pp.cd_profissional = prof.cd_profissional',
                      array('cd_profissional',
                            'tx_profissional',
                            'tx_nome_conhecido'),
                      $this->_schema);

        $select->join(array('proj'=>KT_S_PROJETO),
                      'pp.cd_projeto = proj.cd_projeto',
                      array('cd_projeto',
                            'tx_sigla_projeto',
                            'tx_projeto'),
                      $this->_schema);

        $select->where('st_inativo is null');
        $select->order('proj.tx_sigla_projeto');
        $select->order('prof.tx_profissional');
        

        $retorno = $this->fetchAll($select);
        if($isObject === false){
            $retorno = $retorno->toArray();
        }
        return $retorno;
    }

    public function relProfissionalPorProjeto($isObject=false)
    {
        $this->_objProfissional = new Profissional();

        $select = $this->_objProfissional->select()
                                         ->distinct()
                                         ->setIntegrityCheck(false);

        $select->from(array('pp'=>KT_A_PROFISSIONAL_PROJETO),
                      array(),
                      $this->_schema);

        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      'pp.cd_profissional = prof.cd_profissional',
                      array('cd_profissional',
                            'tx_profissional',
                            'tx_nome_conhecido'),
                      $this->_schema);

        $select->join(array('proj'=>KT_S_PROJETO),
                      'pp.cd_projeto = proj.cd_projeto',
                      array('cd_projeto',
                            'tx_sigla_projeto',
                            'tx_projeto'),
                      $this->_schema);

        $select->where('st_inativo is null');
        $select->order('prof.tx_profissional')
               ->order('proj.tx_sigla_projeto');
               
        $retorno = $this->fetchAll($select);
        if($isObject === false){
            $retorno = $retorno->toArray();
        }
        return $retorno;
    }
}