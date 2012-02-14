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

class RelatorioPerfilProfissional extends Zend_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;

    public function getPerfilProfissional($cd_objeto = null, $cd_perfil_profissional = null)
    {
        $objProfissional = new Profissional();

        $select = $objProfissional->select()
                                  ->setIntegrityCheck(false);

        $select->from(array('poc'=>KT_A_PROFISSIONAL_OBJETO_CONTRATO),
                     array(),
                     $this->_schema);

        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                            'poc.cd_profissional = prof.cd_profissional',
                            array('tx_profissional'),
                            $this->_schema);

        $select->join(array('pp'=>KT_B_PERFIL_PROFISSIONAL),
                            'pp.cd_perfil_profissional = poc.cd_perfil_profissional',
                            array('tx_perfil_profissional'),
                            $this->_schema);

        $select->join(array('ocpp'=>KT_A_OBJETO_CONTRATO_PERFIL_PROF),
                      '(poc.cd_objeto             = ocpp.cd_objeto) AND
                       (pp.cd_perfil_profissional = ocpp.cd_perfil_profissional)',
                      array(),
                      $this->_schema);

       if(!is_null($cd_objeto)){
           $select->where('poc.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);
       }
       if(!is_null($cd_perfil_profissional)){
           $select->where('poc.cd_perfil_profissional = ?', $cd_perfil_profissional, Zend_Db::INT_TYPE);
       }

       $select->order(array('pp.tx_perfil_profissional','prof.tx_profissional'));

       return $this->fetchAll($select)->toArray();
    }
}