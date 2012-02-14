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

class GrupoFatorItem extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_ITEM_GRUPO_FATOR;
	protected $_primary  = 'cd_item_grupo_fator';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getGrupoFatorItem($cd_grupo_fator = null, $cd_item_grupo_fator = null)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('bfi'=>$this->_name),
                      array('cd_item_grupo_fator','cd_grupo_fator','tx_item_grupo_fator','ni_ordem_item_grupo_fator'),
                      $this->_schema);
        $select->join(array('gf'=>KT_B_GRUPO_FATOR),
                      '(bfi.cd_grupo_fator = gf.cd_grupo_fator)',
                      'tx_grupo_fator',
                      $this->_schema);
        $select->order(array('bfi.ni_ordem_item_grupo_fator','bfi.tx_item_grupo_fator'));

		if(!is_null($cd_grupo_fator))
			$select->where("bfi.cd_grupo_fator = ?", $cd_grupo_fator, Zend_Db::INT_TYPE);
		if(!is_null($cd_item_grupo_fator))
			$select->where("bfi.cd_item_grupo_fator = ?", $cd_item_grupo_fator, Zend_Db::INT_TYPE);

		return $this->fetchAll($select)->toArray();
	}
	
	public function salvarGrupoFatorItem(array $arrDados)
	{
		$novo = $this->createRow();
		$novo->cd_item_grupo_fator       = $this->getNextValueOfField('cd_item_grupo_fator');
  		$novo->cd_grupo_fator            = $arrDados['cd_grupo_fator'];
  		$novo->tx_item_grupo_fator       = $arrDados['tx_item_grupo_fator'];
  		$novo->ni_ordem_item_grupo_fator = $arrDados['ni_ordem_item_grupo_fator'];
  		
  		if($novo->save()){
  			return true;
  		} else {
  			return false;
  		}
	}
	
	public function alterarGrupoFatorItem(array $arrDados)
	{
		if($this->update($arrDados, array('cd_item_grupo_fator = ?'=>$arrDados['cd_item_grupo_fator']))){
			return true;
		} else {
			return false;
		}
	}
}