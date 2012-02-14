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

class AcompanhamentoProposta extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_ACOMPANHAMENTO_PROPOSTA;
	protected $_primary  = array('cd_acompanhamento_proposta', 'cd_projeto', 'cd_proposta');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getProjetoProposta($ni_mes_execucao_parcela, $ni_ano_execucao_parcela, $cd_contrato, $isObject=false)
	{
        $select = $this->select()->setIntegrityCheck(false)
                                 ->distinct()
                                 ->from(array('proj'=>KT_S_PROJETO),
                                        array('cd_projeto','tx_sigla_projeto'),
                                        $this->_schema)
                                 ->join(array('par'=>KT_S_PARCELA),
                                        'proj.cd_projeto = par.cd_projeto',
                                        array('proposta'=>new Zend_Db_Expr("'Proposta Nº '{$this->concat()}par.cd_proposta"),
                                              'cd_proposta_order'=>'cd_proposta'),
                                        $this->_schema)
                                 ->join(array('cp'=>KT_A_CONTRATO_PROJETO),
                                        'cp.cd_projeto = proj.cd_projeto',
                                        array(),
                                        $this->_schema)
                                 ->join(array('obj'=>KT_S_OBJETO_CONTRATO),
                                        'obj.cd_contrato = cp.cd_contrato',
                                        array(),
                                        $this->_schema)
                                 ->join(array('procpar'=>KT_S_PROCESSAMENTO_PARCELA),
                                        '    procpar.cd_projeto         = par.cd_projeto
										 and procpar.cd_proposta        = par.cd_proposta
										 and procpar.cd_parcela         = par.cd_parcela
										 and procpar.cd_objeto_execucao = obj.cd_objeto',
                                        array(),
                                        $this->_schema)
                                 ->where('cp.cd_contrato = ?'         , $cd_contrato, Zend_Db::INT_TYPE)
                                 ->where('ni_mes_execucao_parcela = ?', $ni_mes_execucao_parcela, Zend_Db::INT_TYPE)
                                 ->where('ni_ano_execucao_parcela = ?', $ni_ano_execucao_parcela, Zend_Db::INT_TYPE)
                                 ->order('proj.tx_sigla_projeto')
                                 ->order('par.cd_proposta')
                                   ;
        $retorno = $this->fetchAll($select);
        if($isObject === false){
            $retorno = $retorno->toArray();
        }
        return $retorno;
	}
	
	public function salvarAcompanhamentoProposta($arrDados)
	{
		$novo                             = $this->createRow();
		$novo->cd_acompanhamento_proposta = $this->getNextValueOfField("cd_acompanhamento_proposta");
  		$novo->cd_projeto                 = $arrDados['cd_projeto'];
  		$novo->cd_proposta                = $arrDados['cd_proposta'];
  		$novo->tx_acompanhamento_proposta = $arrDados['tx_acompanhamento_proposta'];
  		$novo->st_restrito                = ($arrDados['st_restrito']) ? $arrDados['st_restrito'] : null;
  		$novo->dt_acompanhamento_proposta = date('Y-m-d H:i:s');
  		
		if($novo->save()){
			$res = true;
		}else{
			$res = false;
		}
		return $res;
	}
	
	public function getDadosAcompanhamentoProduto($cd_projeto, $cd_proposta, $cd_perfil, $isObject=false)
	{
        $select = $this->select()->setIntegrityCheck(false)
                                 ->from(array('ap'=>$this->_name),
                                        array('cd_proposta','tx_acompanhamento_proposta','dt_acompanhamento_proposta'),
                                        $this->_schema)
                                  ->join(array('proj'=>KT_S_PROJETO),
                                         'ap.cd_projeto = proj.cd_projeto',
                                         array('tx_projeto','tx_sigla_projeto'),
                                         $this->_schema)
                                  ->join(array('prof'=>KT_S_PROFISSIONAL),
                                         'ap.id = prof.cd_profissional',
                                         array('tx_profissional','tx_nome_conhecido'),
                                         $this->_schema)
                                  ->where('ap.cd_projeto  = ?', $cd_projeto, Zend_Db::INT_TYPE)
                                  ->where('ap.cd_proposta = ?', $cd_proposta, Zend_Db::INT_TYPE)
                                  ->where('ap.st_restrito is null');

		if($cd_perfil == K_CODIGO_PERFIL_COORDENADOR || $cd_perfil == K_CODIGO_PERFIL_CONTROLE){
			$select->orWhere('st_restrito = ?', 'S');
		}
        $retorno = $this->fetchAll($select);
        if($isObject === false){
            $retorno = $retorno->toArray();
        }

        return $retorno;
	}
}