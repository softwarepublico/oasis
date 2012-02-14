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

class QuestionarioAnaliseRisco extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_QUESTIONARIO_ANALISE_RISCO;
	protected $_primary  = array('dt_analise_risco','cd_projeto','cd_proposta','cd_etapa','cd_atividade','cd_item_risco','cd_questao_analise_risco');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function validaDadosGravacao(array $arrQuestionario)
	{
		$where = "dt_analise_risco = {$this->to_timestamp("'{$arrQuestionario['dt_analise_risco']}'",'YYYY-MM-DD HH24:MI:SS')}
				  and cd_projeto               = {$arrQuestionario['cd_projeto']}
				  and cd_proposta              = {$arrQuestionario['cd_proposta']}
				  and cd_etapa                 = {$arrQuestionario['cd_etapa']}
				  and cd_atividade             = {$arrQuestionario['cd_atividade']}
				  and cd_item_risco            = {$arrQuestionario['cd_item_risco']}
				  and cd_questao_analise_risco = {$arrQuestionario['cd_questao_analise_risco']}";
		$objDados = $this->fetchAll($where);
		if($objDados->valid()){
			return $this->alteraQuestionario($arrQuestionario,$where);
		} else {
			return $this->salvaQuestionario($arrQuestionario);
		}
	}
	
	private function salvaQuestionario(array $arrQuestionario)
	{

		$error = false;
		$novo                   		   = $this->createRow();
		$novo->dt_analise_risco 		   = new Zend_Db_Expr("{$this->to_timestamp("'{$arrQuestionario['dt_analise_risco']}'", 'YYYY-MM-DD HH24:MI:SS')}");
	    $novo->cd_profissional             = $arrQuestionario['cd_profissional'];
	    $novo->cd_projeto                  = $arrQuestionario['cd_projeto'];
	    $novo->cd_proposta                 = $arrQuestionario['cd_proposta'];
	    $novo->cd_etapa                    = $arrQuestionario['cd_etapa'];
	    $novo->cd_atividade                = $arrQuestionario['cd_atividade'];
	    $novo->cd_item_risco               = $arrQuestionario['cd_item_risco'];
	    $novo->cd_questao_analise_risco    = $arrQuestionario['cd_questao_analise_risco'];
	    $novo->st_resposta_analise_risco   = $arrQuestionario['st_resposta_analise_risco'];
	    
    	if(!$novo->save()){
			$error = true;
		}
		return $error;
	} 
	
	private function alteraQuestionario(array $arrQuestionario, $where)
	{
		$arrUpdate['cd_profissional']           = $arrQuestionario['cd_profissional'];
		$arrUpdate['st_resposta_analise_risco'] = $arrQuestionario['st_resposta_analise_risco'];
		
		$error = false;
		if(!$this->update($arrUpdate,$where)){
			$error = true;
		}
		return $error;
	}

	public function getValorPesoResposta($cd_projeto = null, $cd_proposta = null, $cd_etapa = null, $cd_atividade = null, $cd_item_risco = null, $st_resposta_analise_risco = null, $cond_st_resposta = "=")
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('qtar'=>$this->_name),
                      array('valor'=>new zend_db_expr('sum(qar.ni_peso_questao_analise_risco)')),
                      $this->_schema);
        $select->join(array('qar'=>KT_B_QUESTAO_ANALISE_RISCO),
                      '(qtar.cd_questao_analise_risco = qar.cd_questao_analise_risco)',
                      array(),
                      $this->_schema);

        $subSelect = $this->select()
                          ->from(array('qtar'=>$this->_name),
                                 new zend_db_expr('max(dt_analise_risco)'),
                                 $this->_schema);

		if(!is_null($cd_projeto)){
            $select->where('qtar.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
            $subSelect->where('qtar.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_proposta)){
            $select->where('qtar.cd_proposta = ?', $cd_proposta, Zend_Db::INT_TYPE);
            $subSelect->where('qtar.cd_proposta = ?', $cd_proposta, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_etapa)){
            $select->where('qtar.cd_etapa = ?', $cd_etapa, Zend_Db::INT_TYPE);
            $subSelect->where('qtar.cd_etapa = ?', $cd_etapa, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_atividade)){
            $select->where('qtar.cd_atividade = ?', $cd_atividade, Zend_Db::INT_TYPE);
            $subSelect->where('qtar.cd_atividade = ?', $cd_atividade, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_item_risco)){
            $select->where('qtar.cd_item_risco = ?', $cd_item_risco, Zend_Db::INT_TYPE);
            $subSelect->where('qtar.cd_item_risco = ?', $cd_item_risco, Zend_Db::INT_TYPE);
		}
		if(!is_null($st_resposta_analise_risco)){
            $select->where("qtar.st_resposta_analise_risco {$cond_st_resposta} ? ", $st_resposta_analise_risco);
            $subSelect->where("qtar.st_resposta_analise_risco {$cond_st_resposta} ?", $st_resposta_analise_risco);
		}

        $select->where('qtar.dt_analise_risco = ?', $subSelect);

		$rowValor = $this->fetchRow($select);

		return (count($rowValor) > 0)?$rowValor->valor:0;
	}
}