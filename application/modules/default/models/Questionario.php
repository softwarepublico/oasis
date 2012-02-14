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

class Questionario extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_A_QUEST_AVALIACAO_QUALIDADE;
	protected $_primary  = array('cd_projeto','cd_proposta','cd_grupo_fator','cd_item_grupo_fator');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getDadosQuestionario($cd_projeto = null, $cd_proposta = null, $cd_grupo_fator = null, $cd_item_grupo_fator = null)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('qaq'=>$this->_name),
                      array('cd_projeto',
                            'cd_proposta',
                            'cd_grupo_fator',
                            'cd_item_grupo_fator',
                            'st_avaliacao_qualidade'),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(qaq.cd_projeto = proj.cd_projeto)',
                      array('tx_projeto'),
                      $this->_schema);
        $select->join(array('prop'=>KT_S_PROPOSTA),
                      '(qaq.cd_projeto = prop.cd_projeto AND qaq.cd_proposta = prop.cd_proposta)',
                      array('tx_motivo_insatisfacao'),
                      $this->_schema);
        $select->join(array('gf'=>KT_B_GRUPO_FATOR),
                      '(qaq.cd_grupo_fator = gf.cd_grupo_fator)',
                      array('tx_grupo_fator'),
                      $this->_schema);
        $select->join(array('igf'=>KT_B_ITEM_GRUPO_FATOR),
                      '(qaq.cd_item_grupo_fator = igf.cd_item_grupo_fator)',
                      array('tx_item_grupo_fator'),
                      $this->_schema);

		if(!is_null($cd_projeto)){
            $select->where('qaq.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_proposta)){
            $select->where('qaq.cd_proposta = ?', $cd_proposta, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_grupo_fator)){
            $select->where('qaq.cd_grupo_fator = ?', $cd_grupo_fator, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_item_grupo_fator)){
            $select->where('qaq.cd_item_grupo_fator = ?', $cd_item_grupo_fator, Zend_Db::INT_TYPE);
		}

        return $this->fetchAll($select)->toArray();
	}
	
	public function salvarDadosQuestionario(array $arrDados)
	{
		$novo = $this->createRow();
		$novo->cd_projeto             = $arrDados['cd_projeto'];
		$novo->cd_proposta            = $arrDados['cd_proposta'];
		$novo->cd_grupo_fator         = $arrDados['cd_grupo_fator'];
		$novo->cd_item_grupo_fator    = $arrDados['cd_item_grupo_fator'];
		$novo->st_avaliacao_qualidade = $arrDados['st_avaliacao_qualidade'];
		
		if($novo->save()){
			return true;
		} else {
			return false;
		}
	}
	
	public function alterarDadosQuestionario(array $arrDados)
	{
		$where = "cd_projeto = {$arrDados['cd_projeto']} and 
		          cd_proposta = {$arrDados['cd_proposta']} and
		          cd_grupo_fator = {$arrDados['cd_grupo_fator']} and 
		          cd_item_grupo_fator = {$arrDados['cd_item_grupo_fator']}";
		
		if($this->update($arrDados,$where)){
			return true;
		} else {
			return false;
		}
	}

	public function getDadosRespostaQuestionario($cd_projeto, $cd_proposta){

		$select = $this->select()
					   ->from($this->_name,
						      array('cd_grupo_fator',
								    'st_avaliacao_qualidade'=> new Zend_Db_Expr("case st_avaliacao_qualidade
																					  when '1' then '1'    -- peso para Muito Satisfeito
																					  when '2' then '0.8'  -- peso para Satisfeito
																					  when '3' then '0.6'  -- peso para A Contento
																					  when '4' then '0.4'  -- peso para Insatisfeito
																					  when '5' then '0.2'  -- peso para Muito Insatisfeito
																				end "),
									'qtd_questao_respondida'=> new Zend_Db_Expr('count(st_avaliacao_qualidade)')),
							  $this->_schema)
					   ->where("cd_projeto  = ?", $cd_projeto)
					   ->where("cd_proposta = ?", $cd_proposta)
					   ->group(array('cd_grupo_fator','st_avaliacao_qualidade'))
					   ->order('cd_grupo_fator');
					   
		return $this->fetchAll($select);

	}

}