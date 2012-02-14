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

class RelatorioProjetoChecklistProposta extends Zend_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;

    public function getChecklistProposta( array $params )
    {
        $objTable = new ProcessamentoProposta();
        $select   = $objTable->select()
                             ->setIntegrityCheck(false);

        $select->from(array('procprop'=>KT_S_PROCESSAMENTO_PROPOSTA),
                      array('cd_projeto',
                            'cd_proposta',
                            'st_fechamento_proposta',
                            'st_parecer_tecnico_proposta',
                            'st_aceite_proposta',
                            'st_homologacao_proposta',
                            'st_alocacao_proposta'),
                      $this->_schema);

        $select->join(array('prop'=>KT_S_PROPOSTA),
                      '(procprop.cd_projeto  = prop.cd_projeto) and
                       (procprop.cd_proposta = prop.cd_proposta)',
                      array('ni_horas_proposta'),
                      $this->_schema);

        $select->join(array('proj'=>KT_S_PROJETO),
                      '(procprop.cd_projeto = proj.cd_projeto)',
                      array('tx_sigla_projeto'),
                      $this->_schema);

        //adicionado devido ao filtro colocado na tela de relatorio
        $select->join(array('cp'=>KT_A_CONTRATO_PROJETO),
                      '(procprop.cd_projeto = cp.cd_projeto)',
                      array(),
                      $this->_schema);

        $select->where('procprop.st_ativo               = ?', 'S')
               ->where('procprop.st_fechamento_proposta = ?', 'S')
               ->where('prop.ni_mes_proposta            = ?', $params['mes'], Zend_Db::INT_TYPE)
               ->where('prop.ni_ano_proposta            = ?', $params['ano'], Zend_Db::INT_TYPE)
               ->where("cp.cd_contrato                  = ?", $params['cd_contrato'], Zend_Db::INT_TYPE);

        $select->order('proj.tx_sigla_projeto')
               ->order('procprop.cd_proposta');

        return $objTable->fetchAll($select)->toArray();
    }
}