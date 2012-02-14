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

class DemandaProfissional extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_DEMANDA_PROFISSIONAL;
	protected $_primary  = array('cd_demanda', 'cd_profissional');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getDadosDemandaProfissional($cd_demanda = null, $cd_profissional = null)
	{
		$select = $this->select();

		if(!is_null($cd_demanda)){
			$select->where("cd_demanda = ?",$cd_demanda, Zend_Db::INT_TYPE);
		} 
		if(!is_null($cd_profissional)){
			$select->where("cd_profissional = ?", $cd_profissional, Zend_Db::INT_TYPE);
		}

		return $this->fetchAll($select)->toArray();
	}
	
	public function getProfissionalAssociadoDemanda($cd_objeto, $cd_demanda)
	{
        $select = $this->select()->setIntegrityCheck(false);
        
        $select->from(array('prof'=>KT_S_PROFISSIONAL),
                      array('cd_profissional','tx_profissional'),
                      $this->_schema);
        $select->join(array('poc'=>KT_A_PROFISSIONAL_OBJETO_CONTRATO),
                      '(prof.cd_profissional = poc.cd_profissional)',
                      array(),
                      $this->_schema);
        $select->joinLeft(array('dp'=>$this->select()
                                           ->setIntegrityCheck(false)
                                           ->from(KT_A_DEMANDA_PROFISSIONAL,
                                                  array('cd_profissional','cd_demanda'),
                                                  $this->_schema)
                                           ->where('cd_demanda = ?', $cd_demanda, Zend_Db::INT_TYPE)),
                          '(prof.cd_profissional = dp.cd_profissional)',
                          array());
        $select->where('poc.cd_objeto = ?',$cd_objeto, Zend_Db::INT_TYPE);
        $select->where('dp.cd_profissional IS NOT NULL');
        $select->where('cd_perfil IN (?)', array(K_CODIGO_PERFIL_GERENTE_PROJETO, K_CODIGO_PERFIL_TECNICO));

		return $this->fetchAll($select)->toArray();
	}

	public function validaDados($cd_demanda, $arrDados)
	{
		$error = false;
		
		$where            = "cd_demanda = {$cd_demanda} and cd_profissional = {$arrDados['cd_profissional']}";
		$rowProfissional  = $this->fetchAll($where)->toArray();

		if(count($rowProfissional) == 0){
			$error = $this->salvarProfissionalDemanda($cd_demanda, $arrDados);
		}
		
		return $error;
	}
	
	protected function salvarProfissionalDemanda($cd_demanda, $arrDados)
	{
		$error = false;
		
		$novo 				           = $this->createRow();
		$novo->cd_demanda              = $cd_demanda;
		$novo->cd_profissional         = $arrDados['cd_profissional'];
		$novo->dt_demanda_profissional = date('Y-m-d H:i:s');
		
		if(!$novo->save()){
			$error = true;
			break; 
		}
		return $error;
	}
	
	protected function alterarProfissionalDemanda($cd_demanda, $arrDados)
	{
		$error = false;

		$where = "cd_demanda = {$cd_demanda} and cd_profissional = {$arrDados['cd_profissional']}";		
		
		return $error;
	}

	public function getProfissionalDemanda($cd_demanda, $cd_objeto, $designado = null, $tipo = 1)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('prof'=>KT_S_PROFISSIONAL),
                      array('cd_profissional','tx_profissional','tx_nome_conhecido'),
                      $this->_schema);

		$select->joinLeft(array('dados'=>$this->select()
                                              ->setIntegrityCheck(false)
                                              ->from(KT_A_DEMANDA_PROFISSIONAL,
                                                     array('cd_profissional','st_fechamento_demanda'),
                                                     $this->_schema)
                                              ->where('cd_demanda = ?',$cd_demanda, Zend_Db::INT_TYPE)),
                          '(prof.cd_profissional = dados.cd_profissional)',
                          'st_fechamento_demanda');
		$select->join(array('poc'=>KT_A_PROFISSIONAL_OBJETO_CONTRATO),
                      '(prof.cd_profissional = poc.cd_profissional)',
                      array(),
                      $this->_schema);
        $select->where('poc.cd_objeto = ?',$cd_objeto,Zend_Db::INT_TYPE);
        $select->where('prof.cd_perfil in (?)', array(K_CODIGO_PERFIL_GERENTE_PROJETO, K_CODIGO_PERFIL_TECNICO));
					
        $select->order('prof.tx_profissional');

		if($designado == "S")
            $select->where('dados.cd_profissional IS NOT NULL');
		else
            $select->where('dados.cd_profissional IS NULL');

		$arrDados = $this->fetchAll($select)->toArray();

        $arrRetorno = array();
		if(count($arrDados) > 0){
			foreach($arrDados as $key=>$value){
				if($tipo == 1){
					$arrRetorno[$value['cd_profissional']] = $value['tx_profissional']; 
				} elseif($tipo == 2){
					$arrRetorno[$value['cd_profissional']] = $value['cd_profissional']; 
				} elseif($tipo == 3) {
					$arrRetorno[$value['cd_profissional']] = $value['tx_nome_conhecido']; 
				} elseif($tipo == 4) {
					$arrRetorno[$value['cd_profissional']] = $value['st_fechamento_demanda']; 
				}
			}
		}
		return $arrRetorno;
	}

	public function getSituacaoDemandaProfissional($cd_demanda)
	{
		$situacaoDemanda = "F";
		$select = $this->select()
                       ->where("cd_demanda = ?", $cd_demanda, Zend_Db::INT_TYPE);
                       
		$arrProfissional =  $this->fetchAll($select)->toArray();
		foreach($arrProfissional as $key=>$value){
			if(is_null($value['st_fechamento_demanda'])){
				$situacaoDemanda = "A";
				break;
			}
		}
		return $situacaoDemanda;
	}
	
	public function atualizaDemanda($cd_demanda, $addRow)
	{
		$erros = false;
		
		$where = array(
                 'cd_demanda = ?'      => $cd_demanda,
                 'cd_profissional = ?' => $addRow['cd_profissional']
            );

		$rowDemanda = $this->fetchRow($where);
		if (!is_null($rowDemanda)){
			if (!$this->update($addRow, $where)){
				$erros = true;
			}
		}
		return $erros;
	}
	
	public function getDadosDemandaProfissionalGrid($cd_demanda)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('dem'=>KT_S_DEMANDA),
                      'cd_demanda',
                      $this->_schema);
        $select->join(array('dp'=>$this->_name),
                      '(dem.cd_demanda = dp.cd_demanda)',
                      array('dt_demanda_profissional','st_fechamento_demanda'),
                      $this->_schema);
        $select->join(array('dpns'=>KT_A_DEMANDA_PROF_NIVEL_SERVICO),
                      '(dem.cd_demanda = dpns.cd_demanda) AND (dp.cd_profissional = dpns.cd_profissional)',
                      array('tx_obs_nivel_servico',
                            'st_fechamento_nivel_servico',
                            'dt_demanda_nivel_servico',
                            'st_nova_obs_nivel_servico',
                            'tx_nova_obs_nivel_servico'),
                      $this->_schema);
        $select->join(array('ns'=>KT_B_NIVEL_SERVICO),
                      '(dpns.cd_nivel_servico = ns.cd_nivel_servico)',
                      array('cd_nivel_servico','tx_nivel_servico'),
                      $this->_schema);
        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(dp.cd_profissional = prof.cd_profissional)',
                      array('cd_profissional','tx_profissional','tx_nome_conhecido'),
                      $this->_schema);
        $select->where('dem.cd_demanda = ?',$cd_demanda,Zend_Db::INT_TYPE);
        $select->order('dpns.dt_demanda_nivel_servico');

		return $this->fetchAll($select)->toArray();
	}

	public function getDemandaPorProfissional( $cd_objeto, $mesAno)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('dp'=>$this->_name),
                      array('total'=>new Zend_Db_Expr('COUNT(dp.cd_demanda)')),
                      $this->_schema);
        $select->join(array('d'=>KT_S_DEMANDA),
                    '(d.cd_demanda = dp.cd_demanda)',
                    array(),
                    $this->_schema);
        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                    '(dp.cd_profissional = prof.cd_profissional)',
                    'tx_nome_conhecido',
                    $this->_schema);
        $select->where(new Zend_Db_Expr("{$this->to_char('dp.dt_demanda_profissional', 'MM/YYYY')} = '{$mesAno}'"));
        $select->where('cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);
        $select->group('tx_nome_conhecido');
        $select->order('total DESC');

        return $this->fetchAll($select)->toArray();
	}
    
    public function getQtdDemandasProfissional($cd_profissional)
    {

        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('demprof'=>KT_A_DEMANDA_PROFISSIONAL),
                      array('count(1) as demandaAberta'),
                      $this->_schema);
        $select->where('cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);             
        $select->where('st_fechamento_demanda IS NULL');  
        
        $demandaAberta = $this->fetchrow($select)->toArray();
      
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('demprof'=>KT_A_DEMANDA_PROFISSIONAL),
                      array('count(1) as demandaFechada'),
                      $this->_schema);
        $select->where('cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE);             
        $select->where('st_fechamento_demanda IS NOT NULL');  
        $select->where('dt_fechamento_demanda::date > now()::date - 30');  
        $select->where('dt_fechamento_demanda::date <= now()::date');  
       
        $demandaFechada = $this->fetchrow($select)->toArray();

        $demandaProfissional = $demandaAberta + $demandaFechada;
        
      return $demandaProfissional;
    }
}