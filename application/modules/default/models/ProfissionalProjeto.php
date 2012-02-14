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

class ProfissionalProjeto extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_A_PROFISSIONAL_PROJETO;
	protected $_primary  = array('cd_profissional', 'cd_projeto');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    /**
     * Método q captura os dados da tabela a_profissional_projeto e monta um array para uma combo
     * @param int $cd_projeto
     * @param bool $comSelecione
     * @param bool $nomeConhecido
     * @return array
     */
	public function getProfissionalProjeto($cd_projeto = null, $comSelecione = false, $nomeConhecido = false)
	{
		$arrProfissionalProjeto = array();
		if ($comSelecione === true) {
			$arrProfissionalProjeto[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		
		$select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(KT_S_PROFISSIONAL,
                      array('cd_profissional',
                            'tx_profissional',
						    'tx_nome_conhecido'),
                      $this->_schema);
        $select->where('cd_profissional IN ?', $this->select()
                                                    ->from($this, 'cd_profissional')
                                                    ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE));
        $select->where('st_inativo is null');
        $select->order('tx_profissional');
		$res = $this->fetchAll($select);

		if($nomeConhecido === true){
			foreach ($res as  $valor) {
				$arrProfissionalProjeto[$valor->cd_profissional] = $valor->tx_nome_conhecido;
			}
		}else{
			foreach ($res as  $valor) {
				$arrProfissionalProjeto[$valor->cd_profissional] = $valor->tx_profissional;
			}
		}
		
		return $arrProfissionalProjeto;
	}
	

	/*
	public function pesquisaProfissionalForaProjeto($cd_projeto)
	{
		$sql = "SELECT 
					prof.cd_profissional, 
					tx_profissional
				FROM
					{$this->_schema}.s_profissional as prof
				JOIN
					{$this->_schema}.a_profissional_objeto_contrato as poc
				ON
					prof.cd_profissional = poc.cd_profissional
				WHERE
					prof.cd_profissional NOT IN (
						SELECT 
							cd_profissional 
						FROM 
							{$this->_schema}.a_profissional_projeto 
						WHERE 
							cd_projeto = '{$cd_projeto}')
				AND
					poc.cd_objeto = (
										SELECT
											cd_objeto
										FROM
											{$this->_schema}.s_objeto_contrato as obj
										JOIN
											{$this->_schema}.s_contrato as con
										ON
											obj.cd_contrato = con.cd_contrato
										WHERE
											st_contrato = 'A'
										AND
											st_objeto_contrato = 'P'
									)
				AND
					st_inativo is null
				ORDER BY
					tx_profissional";

		return $this->getDefaultAdapter()->fetchAll($sql);
	}
 */	

    /**
	 *	Lista os profissionais que estão associados ao objeto de contrato do
	 *	tipo 'P' (Projeto) do contrato de Sistemas e Sítios que está ativo
	 *	e que não estão associados ao projeto selecionado
     *
     * @param int $cd_objeto
     * @param int $cd_projeto
     * @param int $cd_papel_profissional
     * @param bool $isObject
     * @return array|Zend_Db_Table_RowSet
     */
	public function pesquisaProfissionalForaProjeto($cd_objeto, $cd_projeto, $cd_papel_profissional, $isObject=false)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('prof'=>KT_S_PROFISSIONAL),
                      array('cd_profissional',
                            'tx_profissional'),
                      $this->_schema);

        $select->join(array('poc'=>KT_A_PROFISSIONAL_OBJETO_CONTRATO),
                      'prof.cd_profissional = poc.cd_profissional',
                      array(),
                      $this->_schema);

        $select->join(array('pppp'=>KT_A_PERFIL_PROF_PAPEL_PROF),
                      "poc.cd_perfil_profissional = pppp.cd_perfil_profissional AND
                       pppp.cd_papel_profissional = {$cd_papel_profissional}",
                      array(),
                      $this->_schema);

        $subSelect = $this->select()
                          ->from($this->_name,
                                 'cd_profissional',
                                 $this->_schema)
                          ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                          ->where('cd_papel_profissional = ?', $cd_papel_profissional, Zend_Db::INT_TYPE);

        $select->joinLeft(array('profprop'=>$subSelect),
                      'profprop.cd_profissional = prof.cd_profissional',
                      array());

        $select->where('profprop.cd_profissional is null')
               ->where('poc.cd_objeto = ?',$cd_objeto, Zend_Db::INT_TYPE)
               ->where('st_inativo is null');
        $select->order('tx_profissional');

        $retorno = $this->fetchAll($select);

        if($isObject===false){
            $retorno = $retorno->toArray();
        }
        return $retorno;

//		$sql = "SELECT
//					prof.cd_profissional,
//					prof.tx_profissional
//				FROM
//					{$this->_schema}.".KT_S_PROFISSIONAL." as prof
//				JOIN
//					{$this->_schema}.".KT_A_PROFISSIONAL_OBJETO_CONTRATO." as poc
//				ON
//					(prof.cd_profissional = poc.cd_profissional)
//				JOIN
//					{$this->_schema}.".KT_A_PERFIL_PROF_PAPEL_PROF." as pppp
//				ON
//					(poc.cd_perfil_profissional = pppp.cd_perfil_profissional)
//				AND
//					pppp.cd_papel_profissional = {$cd_papel_profissional}
//				LEFT JOIN
//				(
//					SELECT
//						cd_profissional
//					FROM
//						{$this->_schema}.".KT_A_PROFISSIONAL_PROJETO."
//					WHERE
//						cd_projeto = {$cd_projeto}
//					and
//						cd_papel_profissional = {$cd_papel_profissional}
//				) as profprop
//				ON
//					profprop.cd_profissional = prof.cd_profissional
//				WHERE
//					profprop.cd_profissional is null
//				AND
//					poc.cd_objeto = {$cd_objeto}
//				AND
//					st_inativo is null
//				ORDER BY
//					tx_profissional";
//
//		return $this->getDefaultAdapter()->fetchAll($sql);
	}

	/**
	 * Método que recupera os profissionais do projeto, retornando
	 * um array com o codigo do profissional no indece e o valor o 
	 * nome do profissional
	 * @param $cd_projeto
	 * @return array
	 */
	public function getProfissionalNoProjeto($cd_projeto)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(KT_S_PROFISSIONAL,
                      array('cd_profissional',
                            'tx_profissional'),
                      $this->_schema);
        $select->where('cd_profissional IN ?', $this->select()
                                                    ->from($this, 'cd_profissional')
                                                    ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE));
        $select->where('st_inativo is null');
        $select->order('tx_profissional');

        return $this->fetchAll($select)->toArray();
	}

	public function pesquisaProfissionalNoProjeto($cd_objeto, $cd_projeto, $cd_papel_profissional, $isObject=false)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('prof'=>KT_S_PROFISSIONAL),
                      array('cd_profissional',
                            'tx_profissional'),
                      $this->_schema);

        $select->join(array('poc'=>KT_A_PROFISSIONAL_OBJETO_CONTRATO),
                      'prof.cd_profissional = poc.cd_profissional',
                      array(),
                      $this->_schema);

        $select->join(array('pppp'=>KT_A_PERFIL_PROF_PAPEL_PROF),
                      "poc.cd_perfil_profissional = pppp.cd_perfil_profissional AND
                       pppp.cd_papel_profissional = {$cd_papel_profissional}",
                      array(),
                      $this->_schema);

        $subSelect = $this->select()
                          ->from($this->_name,
                                 'cd_profissional',
                                 $this->_schema)
                          ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                          ->where('cd_papel_profissional = ?', $cd_papel_profissional, Zend_Db::INT_TYPE);

        $select->joinLeft(array('profprop'=>$subSelect),
                      'profprop.cd_profissional = prof.cd_profissional',
                      array());

        $select->where('profprop.cd_profissional is not null')
               ->where('poc.cd_objeto = ?',$cd_objeto, Zend_Db::INT_TYPE)
               ->where('st_inativo is null');
        $select->order('tx_profissional');

        $retorno = $this->fetchAll($select);

        if($isObject===false){
            $retorno = $retorno->toArray();
        }
        return $retorno;
	}

    /**
     * Método utilizado para recuperar os projetos em que um determinado profissional está alocado
     * 
     * @param bool $comSelecione
     * @param int $cd_profissional
     * @param int $mes OPCIONAL
     * @param int $ano OPCIONAL
     * @return array
     */
	public function pesquisaProjetoPorProfissional($comSelecione = false, $cd_profissional, $mes = null, $ano = null)
	{
/*		$sql = "SELECT proj.cd_projeto,tx_sigla_projeto
				FROM   {$this->_schema}.s_projeto as proj
				join (
				select distinct cd_projeto from {$this->_schema}.s_processamento_proposta
				where st_fechamento_proposta is null
				and st_ativo = 'S') as pp
				on proj.cd_projeto = pp.cd_projeto
				WHERE  proj.cd_projeto 
				IN     (SELECT cd_projeto 
						FROM   {$this->_schema}.a_profissional_projeto 
						WHERE  cd_profissional = {$cd_profissional})
				order by tx_sigla_projeto";

		$sql = "SELECT 
					proj.cd_projeto,
					tx_sigla_projeto
				FROM   
					{$this->_schema}.s_projeto as proj
				join (
					select 
						distinct cd_projeto 
					from 
						{$this->_schema}.a_profissional_projeto
					where
						cd_profissional = {$cd_profissional}
				) as pp
				on 
					proj.cd_projeto = pp.cd_projeto
				join
				(
				select 
					distinct cd_projeto
				from 
					{$this->_schema}.s_parcela
				where 
					ni_mes_previsao_parcela = ".$mes."
				and 
					ni_ano_previsao_parcela = ".$ano."
				) as parc
				on 
					proj.cd_projeto = parc.cd_projeto
				order by 
					tx_sigla_projeto";
		* 
* 
		
		if (is_null($mes))
		{
			$mes = date("n");
		}
		
		if (is_null($ano))
		{
			$ano = date("Y");
		}
* */
		$select = $this->select()->setIntegrityCheck(false);
        
        $select->from(array('proj'=>KT_S_PROJETO),
                      array('cd_projeto',
                            'tx_sigla_projeto'),
                      $this->_schema);
        $select->join(array('pp'=>$this->select()
                                       ->distinct()
                                       ->from($this,'cd_projeto')
                                       ->where('cd_profissional = ?',$cd_profissional, Zend_Db::INT_TYPE)),
                      '(proj.cd_projeto = pp.cd_projeto)',
                      array());

        $objTableContProj = new ContratoProjeto();
        $select->join(array('cp'=>$objTableContProj->select()
                                       ->from($objTableContProj,'cd_projeto')
                                       ->where('cd_contrato = ?',$_SESSION["oasis_logged"][0]['cd_contrato'], Zend_Db::INT_TYPE)),
                      '(proj.cd_projeto = cp.cd_projeto)',
                      array());
        
        $select->order('tx_sigla_projeto');

        $rowSet = $this->fetchAll($select);
		$arr    = array();
		if ($comSelecione === true) {
			$arr[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		if ($rowSet->valid()){
			foreach ($rowSet as  $row) {
				$arr[$row->cd_projeto] = $row->tx_sigla_projeto;
			}
		}	
		return $arr;
	}

    /**
     *
     * @param int $cd_objeto
     * @param int $cd_projeto
     * @param bool $isObject
     * @return array|Zend_Db_Table_RowSet
     */
    public function getProfissionalPapelProfissionalOrderProfissional($cd_objeto, $cd_projeto, $isObject=false)
    {
        $select = $this->_montaSelectProfissionalPapelProfissional();

        //adiciona as condições
        $select->where('oc.cd_objeto     = ?', $cd_objeto, Zend_Db::INT_TYPE)
               ->where('pproj.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
        //adiciona ordenação
        $select->order('prof.tx_profissional');
        
        $retorno = $this->fetchAll($select);

        if($isObject===false){
            $retorno = $retorno->toArray();
        }
        return $retorno;
    }

    /**
     *
     * @param int $cd_objeto
     * @param int $cd_projeto
     * @param bool $isObject
     * @return array|Zend_Db_Table_RowSet
     */
    public function getProfissionalPapelProfissionalOrderPapel($cd_objeto, $cd_projeto, $isObject=false)
    {
        $select = $this->_montaSelectProfissionalPapelProfissional();

        //adiciona as condições
        $select->where('oc.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE)
               ->where('pproj.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
        //adiciona ordenação
        $select->order('pprof.tx_papel_profissional')
               ->order('prof.tx_profissional');

        $retorno = $this->fetchAll($select);

        if($isObject===false){
            $retorno = $retorno->toArray();
        }
        return $retorno;
    }

    private function _montaSelectProfissionalPapelProfissional()
    {
        $select = $this->select()
                   ->setIntegrityCheck(false);

        $select->from(array('oc'=>KT_S_OBJETO_CONTRATO),
                      array(),
                      $this->_schema);
        $select->join(array('ocpp'=>KT_A_OBJETO_CONTRATO_PAPEL_PROF),
                      '(oc.cd_objeto                = ocpp.cd_objeto)',
                      array(),
                      $this->_schema);
        $select->join(array('pprof'=>KT_B_PAPEL_PROFISSIONAL),
                      '(pprof.cd_papel_profissional = ocpp.cd_papel_profissional)',
                      array('cd_papel_profissional','tx_papel_profissional'),
                      $this->_schema);
        $select->join(array('pproj'=>$this->_name),
                      '(pprof.cd_papel_profissional = pproj.cd_papel_profissional)',
                      array(),
                      $this->_schema);
        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(pproj.cd_profissional = prof.cd_profissional)',
                      array('cd_profissional',
                            'tx_profissional'),
                      $this->_schema);

        return $select;

//    SELECT
//      prof.cd_profissional,
//      prof.tx_profissional,
//      pprof.cd_papel_profissional,
//      pprof.tx_papel_profissional
//    FROM
//    	oasis_hom.s_objeto_contrato as oc
//    JOIN
//    	oasis_hom.b_papel_profissional as pprof
//    ON
//    	(oc.cd_objeto = pprof.cd_objeto)
//    JOIN
//    	oasis_hom.a_profissional_projeto as pproj
//    ON
//    	(pprof.cd_papel_profissional = pproj.cd_papel_profissional)
//    JOIN
//    	oasis_hom.s_profissional as prof
//    ON
//    	(pproj.cd_profissional = prof.cd_profissional)
//    WHERE
//    	oc.cd_objeto = 14
//    AND
//    	pproj.cd_projeto = 186
    }

    /**
     *
     * @param int $cd_contrato
     * @param int $cd_projeto
     * @param bool $comSelecione
     * @return array
     */
    public function getProfissionalAlocadoProjetoDuranteAExecucao($cd_contrato, $cd_projeto, $comSelecione=false)
    {
        $select = $this->_montaSelectProfissionalPapelProfissional();

        $select->distinct();

        $select->where('oc.cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE)
               ->where('pproj.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
        //adiciona ordenação
        $select->order('prof.tx_profissional');

        $arrRetorno = array();
        if($comSelecione === true){
            $arrRetorno[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        }
        foreach($this->fetchAll($select) as $profissional){
            $arrRetorno[$profissional->cd_profissional] = $profissional->tx_profissional;
        }
        return $arrRetorno;
    }
}