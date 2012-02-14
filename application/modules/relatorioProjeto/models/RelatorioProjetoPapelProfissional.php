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

class RelatorioProjetoPapelProfissional extends Zend_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;

	public function relPapelProfissional(array $arrParams)
    {
		$objPapelProfissional = new PapelProfissional();

        $select = $objPapelProfissional->select()
                                       ->setIntegrityCheck(false);

        $select->from(array('oc'=>KT_S_OBJETO_CONTRATO),
                      array(),
                      $this->_schema);

        $select->join(array('ocpp'=>KT_A_OBJETO_CONTRATO_PAPEL_PROF),
                      '(oc.cd_objeto = ocpp.cd_objeto)',
                      array(),
                      $this->_schema);

        $select->join(array('pprof'=>KT_B_PAPEL_PROFISSIONAL),
                      '(pprof.cd_papel_profissional = ocpp.cd_papel_profissional)',
                      array('cd_papel_profissional','tx_papel_profissional'),
                      $this->_schema);


        $select->join(array('pproj'=>KT_A_PROFISSIONAL_PROJETO),
                      '(pprof.cd_papel_profissional = pproj.cd_papel_profissional)',
                      array(),
                      $this->_schema);

        $select->join(array('proj'=>KT_S_PROJETO),
                      '(pproj.cd_projeto = proj.cd_projeto)',
                      array('cd_projeto','tx_sigla_projeto'),
                      $this->_schema);

        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(pproj.cd_profissional = prof.cd_profissional)',
                      array('cd_profissional','tx_profissional'),
                      $this->_schema);

        $objContratoProjeto = new ContratoProjeto();

        $subSelect = $objContratoProjeto->select()
                                         ->from($objContratoProjeto, array('cd_projeto'), $this->_schema)
                                         ->where("cd_contrato = ?", $arrParams['cd_contrato'], Zend_Db::INT_TYPE);

        $select->joinLeft(array('cp'=>$subSelect),
                      '(cp.cd_projeto = proj.cd_projeto)',
                      array());

        $select->where("oc.cd_contrato = ?", $arrParams['cd_contrato'], Zend_Db::INT_TYPE);

        if( $arrParams['cd_projeto'] != 0 ){
            $select->where("pproj.cd_projeto = ?", $arrParams['cd_projeto'], Zend_Db::INT_TYPE);
        }else if($arrParams['cd_projeto'] == 0){
            $select->where("cp.cd_projeto is not null");
        }

        if( $arrParams['cd_papel_profissional'] != 0 ){
            $select->where("pprof.cd_papel_profissional = ?", $arrParams['cd_papel_profissional'], Zend_Db::INT_TYPE);
        }

        $select->order('proj.tx_sigla_projeto')
               ->order('pprof.tx_papel_profissional')
               ->order('prof.tx_profissional');

        return $objPapelProfissional->fetchAll($select);
	}
}