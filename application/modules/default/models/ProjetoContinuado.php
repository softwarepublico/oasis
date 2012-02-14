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

class ProjetoContinuado extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_PROJETO_CONTINUADO;
	protected $_primary  = array('cd_projeto_continuado','cd_objeto');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getProjetoContinuado($cd_objeto = null, $comSelecione = false)
	{
        $select = null;
		if (!is_null($cd_objeto)) {
			$select = $this->select()
                           ->where("cd_objeto = ?", $cd_objeto, Zend_Db::INT_TYPE)
                           ->order("tx_projeto_continuado");
		}
		
		$rowSet = $this->fetchAll($select);
		
		$arrProjetoContinuados = array();
		if ($comSelecione === true) {
			$arrProjetoContinuados[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
        if($rowSet->valid()){
            foreach ($rowSet as  $row) {
                $arrProjetoContinuados[$row->cd_projeto_continuado] = $row->tx_projeto_continuado;
            }
        }
		return $arrProjetoContinuados;
	}

	public function salvarProjetoContinuado(array $arrProjetoContinuado)
	{
		$novo 				             		= $this->createRow();
		$novo->cd_projeto_continuado	        = $this->getNextValueOfField('cd_projeto_continuado');
		$novo->cd_objeto                        = $arrProjetoContinuado['cd_objeto_projeto_continuado'];
		$novo->tx_projeto_continuado            = $arrProjetoContinuado['tx_projeto_continuado'];
		$novo->tx_objetivo_projeto_continuado   = $arrProjetoContinuado['tx_objetivo_projeto_continuado'];
		$novo->tx_obs_projeto_continuado        = $arrProjetoContinuado['tx_obs_projeto_continuado'];
		$novo->st_prioridade_proj_continuado = $arrProjetoContinuado['st_prioridade_proj_continuado'];
		if ($novo->save()) {
			return true;
		} else {
			return false;
		}
	}
	
	public function alterarProjetoContinuado(array $arrProjetoContinuado)
	{
		$arrUpdate['cd_projeto_continuado']	           = $arrProjetoContinuado['cd_projeto_continuado'];
		$arrUpdate['tx_projeto_continuado']            = $arrProjetoContinuado['tx_projeto_continuado'];
		$arrUpdate['cd_objeto']                        = $arrProjetoContinuado['cd_objeto_projeto_continuado'];
		$arrUpdate['tx_objetivo_projeto_continuado']   = $arrProjetoContinuado['tx_objetivo_projeto_continuado'];
		$arrUpdate['tx_obs_projeto_continuado']        = $arrProjetoContinuado['tx_obs_projeto_continuado'];
		$arrUpdate['st_prioridade_proj_continuado'] = $arrProjetoContinuado['st_prioridade_proj_continuado'];
		$where = "cd_projeto_continuado = {$arrProjetoContinuado['cd_projeto_continuado']}";
		
		if ($this->update($arrUpdate,$where)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getDadosProjetoContinuado($cd_projeto_continuado = null, $cd_objeto = null)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('pc'=>$this->_name),
                      array('pc.cd_projeto_continuado',
                            'cd_objeto',
                            'tx_projeto_continuado',
                            'tx_objetivo_projeto_continuado',
                            'tx_obs_projeto_continuado',
                            'st_prioridade_proj_continuado',
                            'desc_prioridade_projeto_continuado'=>new Zend_Db_Expr("case when pc.st_prioridade_proj_continuado = 'A' then '".Base_Util::getTranslator('L_SQL_ALTISSIMA')."'
                                                                                         when pc.st_prioridade_proj_continuado = 'L' then '".Base_Util::getTranslator('L_SQL_ALTA')."'
                                                                                         when pc.st_prioridade_proj_continuado = 'M' then '".Base_Util::getTranslator('L_SQL_MEDIA')."'
                                                                                         when pc.st_prioridade_proj_continuado = 'B' then '".Base_Util::getTranslator('L_SQL_BAIXA')."'
                                                                                    end")),
                      $this->_schema);
        $select->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                      '(pc.cd_objeto = oc.cd_objeto)',
                      'tx_objeto',
                      $this->_schema);

		if(!is_null($cd_projeto_continuado)){
            $select->where('pc.cd_projeto_continuado = ?', $cd_projeto_continuado, Zend_Db::INT_TYPE);
		} else if(!is_null($cd_objeto)){
            $select->where('pc.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);
		}
        return $this->fetchAll($select)->toArray();
	}
}