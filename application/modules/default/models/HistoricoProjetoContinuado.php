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

class HistoricoProjetoContinuado extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_HISTORICO_PROJETO_CONTINUADO;
	protected $_primary  = array('cd_historico_proj_continuado', 'cd_objeto', 'cd_projeto_continuado', 'cd_modulo_continuado', 'cd_etapa', 'cd_atividade');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function salvarHistoricoProjetoContinuado(array $arrDados)
	{
		$novo                                 = $this->createRow();
		$novo->cd_historico_proj_continuado	  = $this->getNextValueOfField('cd_historico_proj_continuado');
		$novo->cd_objeto                      = $arrDados['cd_objeto_historico_continuado'];
		$novo->cd_profissional                = $arrDados['cd_profissional_historico_projeto_continuado'];
		$novo->cd_projeto_continuado          = $arrDados['cd_projeto_continuado_historico_continuado'];
		$novo->cd_modulo_continuado           = $arrDados['cd_modulo_continuado_historico_continuado'];
		$novo->cd_etapa                       = $arrDados['cd_etapa'];
		$novo->cd_atividade                   = $arrDados['cd_atividade'];
		$novo->dt_inicio_hist_proj_continuado = (!empty ($arrDados['dt_inicio_hist_proj_continuado'])) ? $arrDados['dt_inicio_hist_proj_continuado'] : null;
		$novo->dt_fim_hist_projeto_continuado = (!empty ($arrDados['dt_fim_hist_projeto_continuado'])) ? $arrDados['dt_fim_hist_projeto_continuado'] : null;
		$novo->tx_hist_projeto_continuado     = $arrDados['tx_hist_projeto_continuado'];
		
		if($novo->save()){
			return true;
		} else {
			return false;
		}
	}
	
	public function alterarHistoricoProjetoContinuado(array $arrDados)
	{
		$arrUpdate['cd_historico_proj_continuado']	  = $arrDados['cd_historico_proj_continuado'];
		$arrUpdate['cd_profissional']			   	  = $arrDados['cd_profissional_historico_projeto_continuado'];
		$arrUpdate['cd_objeto']                       = $arrDados['cd_objeto_historico_continuado'];
		$arrUpdate['cd_projeto_continuado']           = $arrDados['cd_projeto_continuado_historico_continuado'];
		$arrUpdate['cd_modulo_continuado']            = $arrDados['cd_modulo_continuado_historico_continuado'];
		$arrUpdate['cd_etapa']            			  = $arrDados['cd_etapa'];
		$arrUpdate['cd_atividade']            		  = $arrDados['cd_atividade'];
		$arrUpdate['dt_inicio_hist_proj_continuado']  = (!empty ($arrDados['dt_inicio_hist_proj_continuado'])) ? $arrDados['dt_inicio_hist_proj_continuado'] : null;
		$arrUpdate['dt_fim_hist_projeto_continuado']  = (!empty ($arrDados['dt_fim_hist_projeto_continuado'])) ? $arrDados['dt_fim_hist_projeto_continuado'] : null;
		$arrUpdate['tx_hist_projeto_continuado']      = $arrDados['tx_hist_projeto_continuado'];

		if($this->update($arrUpdate,array('cd_historico_proj_continuado = ?'=>$arrDados['cd_historico_proj_continuado']))){
			return true;
		} else {
			return false;
		}
	}

	public function getDadosHistoricoProjetoContinuado($cd_historico_proj_continuado = null, $cd_objeto = null)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('hpc'=>$this->_name),
                      array('cd_historico_proj_continuado',
                            'tx_hist_projeto_continuado',
                            'cd_objeto',
                            'cd_projeto_continuado',
                            'cd_modulo_continuado',
                            'cd_atividade',
                            'cd_etapa',
                            'dt_inicio_hist_proj_continuado',
                            'dt_fim_hist_projeto_continuado'),
                      $this->_schema);
        $select->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                      '(hpc.cd_objeto = oc.cd_objeto)',
                      'tx_objeto',
                      $this->_schema);
        $select->join(array('pc'=>KT_S_PROJETO_CONTINUADO),
                      '(hpc.cd_projeto_continuado = pc.cd_projeto_continuado)',
                      'tx_projeto_continuado',
                      $this->_schema);
        $select->join(array('mc'=>KT_S_MODULO_CONTINUADO),
                      '(hpc.cd_modulo_continuado = mc.cd_modulo_continuado)',
                      'tx_modulo_continuado',
                      $this->_schema);
        $select->join(array('e'=>KT_B_ETAPA),
                      '(hpc.cd_etapa = e.cd_etapa)',
                      'tx_etapa',
                      $this->_schema);
        $select->join(array('a'=>KT_B_ATIVIDADE),
                      '(hpc.cd_atividade = a.cd_atividade)',
                      'tx_atividade',
                      $this->_schema);
        $select->order('tx_projeto_continuado');

		if(!is_null($cd_historico_proj_continuado)){
			$select->where('hpc.cd_historico_proj_continuado = ?',$cd_historico_proj_continuado);
		}
		if(!is_null($cd_objeto)){
			$select->where('hpc.cd_objeto = ?',$cd_objeto);
		}

		return $this->fetchAll($select)->toArray();
	}
}