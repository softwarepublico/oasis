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

class RelatorioProjetoAnaliseMedicao extends Base_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;
	
    public function analiseMedicao($cd_medicao=null, $cd_box_inicio=null)
    {

        $objAnaliseMedicao = new AnaliseMedicao();

        $select = $objAnaliseMedicao->select()
                                    ->setIntegrityCheck(false);

        $select->from(array('am'=>KT_S_ANALISE_MEDICAO),
                      array('*',
                            'dt_analise_medicao_f'      =>new Zend_Db_Expr("{$this->to_char('dt_analise_medicao','DD/MM/YYYY HH24:MI:SS')}"),
                            'dt_decisao_f'              =>new Zend_Db_Expr("{$this->to_char('dt_decisao','DD/MM/YYYY')}"),
                            'dt_decisao_executada_f'    =>new Zend_Db_Expr("{$this->to_char('dt_decisao_executada','DD/MM/YYYY')}"),
                            'st_decisao_executada_desc' =>new Zend_Db_Expr("CASE am.st_decisao_executada WHEN 'E' THEN '".Base_Util::getTranslator('L_SQL_EXECUTADA')."'
                                                                                                                  ELSE '".Base_Util::getTranslator('L_SQL_NAO_EXECUTADA')."' END")),
                      $this->_schema);

        $select->join(array('m'=>KT_S_MEDICAO),
                      '(am.cd_medicao = m.cd_medicao)',
                      array('tx_medicao'),
                      $this->_schema);

        $select->join(array('box'=>KT_B_BOX_INICIO),
                      '(am.cd_box_inicio = box.cd_box_inicio)',
                      array('tx_titulo_box_inicio'),
                      $this->_schema);

        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(am.cd_profissional = prof.cd_profissional)',
                      array('tx_profissional'),
                      $this->_schema);

    	if( !is_null($cd_medicao) ){
    		if( is_null($cd_box_inicio) ){
                $select->where('am.cd_medicao = ?', $cd_medicao, Zend_Db::INT_TYPE);
    		}
    	}

    	if( !is_null($cd_box_inicio) ){
    		if( is_null($cd_medicao) ){
                $select->where('am.cd_box_inicio = ?', $cd_box_inicio, Zend_Db::INT_TYPE);
    		}
    	}

        $select->order('tx_titulo_box_inicio')
               ->order('dt_analise_medicao_f');

        return $this->fetchAll($select)->toArray();
    }
}