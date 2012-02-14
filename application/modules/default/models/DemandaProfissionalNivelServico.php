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

class DemandaProfissionalNivelServico extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_DEMANDA_PROF_NIVEL_SERVICO;
	protected $_primary  = array('cd_demanda', 'cd_profissional','cd_nivel_servico');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	
	public function getDadosDemandaprofissionalNivelServico($cd_demanda,$cd_profissional)
	{
//		$sql = " select
//					dpns.cd_demanda,
//					dpns.cd_profissional,
//					dpns.cd_nivel_servico,
//					to_char(dpns.dt_fechamento_nivel_servico,'DD/MM/YYYY HH24:MI:SS') as dt_fechamento_nivel_servico,
//					dpns.st_fechamento_nivel_servico,
//					dpns.st_fechamento_gerente,
//					to_char(dpns.dt_fechamento_gerente,'DD/MM/YYYY HH24:MI:SS') as dt_fechamento_gerente,
//					to_char(dpns.dt_leitura_nivel_servico,'DD/MM/YYYY HH24:MI:SS') as dt_leitura_nivel_servico,
//					to_char(dpns.dt_demanda_nivel_servico, 'DD/MM/YYYY HH24:MI:SS') as dt_demanda_nivel_servico,
//					dpns.tx_motivo_fechamento,
//					dpns.tx_obs_nivel_servico,
//					to_char(dpns.dt_justificativa_nivel_servico,'DD/MM/YYYY HH24:MI:SS') as dt_justificativa_nivel_servico,
//					dpns.tx_justificativa_nivel_servico,
//					ns.tx_nivel_servico
//				from
//					{$this->_schema}.".KT_A_DEMANDA_PROF_NIVEL_SERVICO." as dpns
//				join
//					{$this->_schema}.".KT_B_NIVEL_SERVICO." as ns
//				on
//					dpns.cd_nivel_servico = ns.cd_nivel_servico
//				where
//					cd_demanda = {$cd_demanda} and cd_profissional = {$cd_profissional} ";

        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('dpns'=>$this->_name),
                      array('*'),
                      $this->_schema);
        $select->join(array('ns'=>KT_B_NIVEL_SERVICO),
                      '(dpns.cd_nivel_servico = ns.cd_nivel_servico)',
                      'tx_nivel_servico',
                      $this->_schema);
        $select->where('cd_demanda      = ?', $cd_demanda, Zend_Db::INT_TYPE);
        $select->where('cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);

        return $this->fetchAll($select)->toArray();
	}

	public function validaDados($cd_demanda, array $arrDados)
	{
		$error = false;
		$where = " cd_demanda = {$cd_demanda} 
		           and cd_profissional = {$arrDados['cd_profissional']} 
		           and cd_nivel_servico = {$arrDados['cd_nivel_servico']} ";
		$nivelServico = $this->fetchAll($where);

		if(!$nivelServico->valid())
			$error = $this->salvarDemandaProfissionalNivelServico($cd_demanda, $arrDados);           
		else
			$error = $this->alterarDemandaProfissionalNivelServico($cd_demanda, $arrDados);

		return $error;
	}
	
	protected function salvarDemandaProfissionalNivelServico($cd_demanda, array $arrNivelServico)
	{
		$error = false;
		
		$novo 				            = $this->createRow();
		$novo->cd_demanda               = $cd_demanda;
		$novo->cd_profissional          = $arrNivelServico['cd_profissional'];
		$novo->cd_nivel_servico         = $arrNivelServico['cd_nivel_servico'];
		$novo->tx_obs_nivel_servico     = ($arrNivelServico['tx_obs_nivel_servico'])?$arrNivelServico['tx_obs_nivel_servico']:null;
		$novo->dt_demanda_nivel_servico = null;
		
		if(!$novo->save()){
			$error = true;
			break; 
		}
		
		return $error;
	}
	
	protected function alterarDemandaProfissionalNivelServico($cd_demanda, array $arrNivelServico)
	{
		 $error = false;
		 
		 $where = " cd_demanda = {$cd_demanda} 
		           and cd_profissional = {$arrNivelServico['cd_profissional']} 
		           and cd_nivel_servico = {$arrNivelServico['cd_nivel_servico']} ";

		 $arrUpdate['tx_obs_nivel_servico']     = ($arrNivelServico['tx_obs_nivel_servico'])?$arrNivelServico['tx_obs_nivel_servico']:null;
		 
		 if(!$this->update($arrUpdate,$where)){
		 	$error = true;          
		 }
		           
		 return $error;          
	}

	public function getDemandaNivelServico($cd_demanda, $cd_objeto, $designado = "S", $tipo = 1)
	{
		$select = $this->select()->setIntegrityCheck(false);

        $select->from(array('ns'=>KT_B_NIVEL_SERVICO),
                      array('tx_nivel_servico','cd_objeto','cd_nivel_servico'),
                      $this->_schema);
        $select->joinLeft(array('dados'=>$this->select()
                                              ->setIntegrityCheck(false)
                                              ->from($this,
                                                     array('cd_demanda','cd_profissional','cd_nivel_servico','st_fechamento_nivel_servico'))
                                              ->where('cd_demanda = ?', $cd_demanda, Zend_Db::INT_TYPE)
                               ),
                          '(ns.cd_nivel_servico = dados.cd_nivel_servico)',
                          array('cd_demanda','st_fechamento_nivel_servico'));
        $select->where('ns.cd_objeto = ?',$cd_objeto, Zend_Db::INT_TYPE);
        $select->order('ns.tx_nivel_servico');

        /*
            COMENTADO EM 04-05-10 DEVIDO À PROPOSTA DE ALTERAÇÃO NO COMBO DE NIVEL
            DE SERVIÇO DA TELA DE "DESIGNAR PROFISSIONAL" PARA POSSIBILITAR A DESIGNAÇÃO
            DE UM MESMO NIVEL DE SERVIÇO PARA MAIS DE UMA PESSOA
         */
//		if($designado == "S")
//            $select->where('dados.cd_nivel_servico IS NOT NULL');
//		else
//            $select->where('dados.cd_nivel_servico IS NULL');

		$rowSet = $this->fetchAll($select);

        $arrRetorno = array();
		if($rowSet->valid()){
			foreach($rowSet as $row){
				if($tipo == 1){
					$arrRetorno[$row->cd_nivel_servico] = $row->cd_nivel_servico;
				} elseif($tipo == 2){
					$arrRetorno[$row->cd_nivel_servico] = $row->tx_nivel_servico;
				}
			}
		}
		return $arrRetorno;
	}
	
	public function atualizaDemandaNivelServico($cd_demanda, $cd_profissional, $cd_nivel_servico, $addRow)
	{
		$erros = false;
		
		$where = array(
                'cd_demanda = ?'       => $cd_demanda,
                'cd_profissional = ?'  => $cd_profissional,
                'cd_nivel_servico = ?' =>$cd_nivel_servico
            );
					   
		$rowDemandaNivelServico = $this->fetchRow($where);
		
		if (!is_null($rowDemandaNivelServico)){
			if (!$this->update($addRow, $where)){
				$erros = true;
			}
		}
		return $erros;
	}
	
	public function getSituacaoDemandaNivelServico($cd_demanda, $cd_profissional)
	{
		$situacaoDemanda = "F";
		$select          = $this->select()
                                ->where("cd_demanda      = ?", $cd_demanda, Zend_Db::INT_TYPE)
                                ->where("cd_profissional = ?", $cd_profissional, Zend_Db::INT_TYPE);
		$arrDados        = $this->fetchAll($select)->toArray();
		
		foreach($arrDados as $key=>$value){
			if(is_null($value['st_fechamento_nivel_servico'])){
				$situacaoDemanda = "A";
				break;
			}
		}
		return $situacaoDemanda;
	}

    /**
     * Método para recuperar as observações de uma demanda quando ela precisa
     * ser reencaminhada
     * 
     * @param integer $cd_demanda
     * @return Zend_Db_Table_Rowset
     */
    public function getObservacoesDemanda($cd_demanda)
    {
        $select = $this->select()
                       ->from($this, 'tx_obs_nivel_servico')
                       ->where('cd_demanda = ?', $cd_demanda, Zend_Db::INT_TYPE)
                       ->where('st_fechamento_nivel_servico IS NOT NULL');

        return $this->fetchAll($select);
    }


    public function atualizaObservacaoNivelServico(array $arrUpdate, array $arrWhere)
    {
        if(!$this->update($arrUpdate, $arrWhere)){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
        }
    }

    public function getMaxPrazoExecucaoNivelServico($cd_demanda, $cd_nivel_servico = null)
    {
        $subSelect = $this->select()->setIntegrityCheck(false);
        $subSelect->from(array('dpns'=>$this->_name), 
                array('cd_nivel_servico'),
                $this->_schema);
        $subSelect->join(array('ns'=>KT_B_NIVEL_SERVICO),
                '(dpns.cd_nivel_servico = ns.cd_nivel_servico)',
                array('ni_horas_prazo_execucao'),
                $this->_schema);
        $subSelect->where('cd_demanda = ?', $cd_demanda, Zend_Db::INT_TYPE);

        if (!is_null($cd_nivel_servico)) {
            $subSelect->where('dpns.cd_nivel_servico = ?', $cd_nivel_servico, Zend_Db::INT_TYPE);
        }

        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('t'=>$subSelect), 'max(ni_horas_prazo_execucao) as max');
        
        $arr    = $this->fetchRow($select)->toArray();
        return $arr['max'];
    }
}