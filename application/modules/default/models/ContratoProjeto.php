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

class ContratoProjeto extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_A_CONTRATO_PROJETO;
	protected $_primary  = array('cd_contrato', 'cd_projeto');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    public function pesquisaProjetoNoContrato( $cd_contrato )
    {
        $select = $this->_montaSelectPesquisaProjetoContrato($cd_contrato);
        $select->where('cp.cd_projeto IS NOT NULL');

        return $this->fetchAll($select)->toArray();
    }

    public function pesquisaProjetoForaDoContrato( $cd_contrato )
    {
        $select = $this->_montaSelectPesquisaProjetoContrato($cd_contrato);
        $select->where('cp.cd_projeto IS NULL');

        return $this->fetchAll($select)->toArray();
    }

    private function _montaSelectPesquisaProjetoContrato($cd_contrato)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('pr'=>KT_S_PROJETO),
                      array('cd_projeto',
                            'tx_sigla_projeto'),
                      $this->_schema);
        $select->joinLeft(array('cp'=>$this->select()
                                           ->from($this,'cd_projeto')
                                           ->where('cd_contrato = ?',$cd_contrato, Zend_Db::INT_TYPE)),
                          '(cp.cd_projeto = pr.cd_projeto)',
                          array());
        $select->order('tx_sigla_projeto');
        
        return $select;
    }
    
    public function listaProjetosContrato($cd_contrato, $comSelecione=false, $comTodos=false)
    {
    	$arrProjetosContrato = $this->pesquisaProjetoNoContrato($cd_contrato);
    	$arrProjetos         = array();
    	
    	if ($comSelecione === true && $comTodos === true) {
			$arrProjetos[-1] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
			$arrProjetos[0] = Base_Util::getTranslator('L_VIEW_COMBO_TODOS');
		} else if($comSelecione === true){
			$arrProjetos[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
    	
    	foreach ($arrProjetosContrato as $projeto){
    		$arrProjetos[$projeto["cd_projeto"]] = $projeto["tx_sigla_projeto"];
    	}

		return $arrProjetos;
    }

	public function getContratosExecucaoProjeto($cd_projeto)
	{
		$select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('cp'=>$this->_name),
                      array('cp.cd_contrato'),
                      $this->_schema);
        $select->join(array('con'=>KT_S_CONTRATO),
                      '(cp.cd_contrato = con.cd_contrato)',
                      'con.tx_numero_contrato',
                      $this->_schema);
        $select->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                      '(oc.cd_contrato = con.cd_contrato)',
                      'oc.tx_objeto',
                      $this->_schema);
		$select->where('cp.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
        $select->order('con.dt_inicio_contrato');

        return $this->fetchAll($select)->toArray();
	}
}