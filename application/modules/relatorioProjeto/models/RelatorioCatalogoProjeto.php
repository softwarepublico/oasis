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

class RelatorioCatalogoProjeto extends Base_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;

	public function relCatalogoProjeto( $cd_projeto = null ){

		$objProjeto = new Projeto();

		$select = $objProjeto->select()
                             ->setIntegrityCheck(false);

        $select->from(array('proj'=>KT_S_PROJETO),
                      array('cd_projeto',
                            'tx_sigla_projeto',
                            'tx_projeto',
                            'tx_contexto_geral_projeto',
                            'inicio'=> new Zend_Db_Expr("proj.ni_mes_inicio_previsto{$this->concat()}'/'{$this->concat()}proj.ni_ano_inicio_previsto"),
                            'termino'=>new Zend_Db_Expr("ni_mes_termino_previsto{$this->concat()}'/'{$this->concat()}ni_ano_termino_previsto"),
                            'tx_publico_alcancado'),
                      $this->_schema);

           $select->join(array('unid'=>KT_B_UNIDADE),
                         'proj.cd_unidade = unid.cd_unidade',
                         array('tx_sigla_unidade'),
                         $this->_schema);

		if( !is_null($cd_projeto) ){
			$select->where("cd_projeto = ?", $cd_projeto, Zend_Db::INT_TYPE);
		}

		$select->order("tx_sigla_projeto");

		return $objProjeto->fetchAll($select);
	}

	public function getConhecimento($cd_projeto){

		$objConhecimentoProjeto = new ConhecimentoProjeto();

		$select = $objConhecimentoProjeto->select()
                                         ->setIntegrityCheck(false);
        $select->from(array('cp'=>KT_A_CONHECIMENTO_PROJETO),
                      array(),
                      $this->_schema);

        $select->joinLeft(array('conhe'=>KT_B_CONHECIMENTO),
                           "(cp.cd_conhecimento = conhe.cd_conhecimento)",
                           array('cd_tipo_conhecimento',
                                 'tx_conhecimento'),
                           $this->_schema);

        $select->joinLeft(array('tc'=>KT_B_TIPO_CONHECIMENTO),
                                "(tc.cd_tipo_conhecimento = cp.cd_tipo_conhecimento)",
                                array('tx_tipo_conhecimento'),
                                $this->_schema);

        $select->where("cp.cd_projeto = ?", $cd_projeto, Zend_Db::INT_TYPE);
        
        $select->order('cd_tipo_conhecimento');
	   
	   return $objConhecimentoProjeto->fetchAll($select);
	}
}