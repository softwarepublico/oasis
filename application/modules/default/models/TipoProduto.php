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

class TipoProduto extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_TIPO_PRODUTO;
	protected $_primary  = 'cd_tipo_produto';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getDadosTipoProduto($cd_definicao_metrica = null, $cd_tipo_produto = null)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('tp'=>$this->_name),
                              array('cd_tipo_produto',
                                    'tx_tipo_produto',
                                    'ni_ordem_tipo_produto',
                                    'cd_definicao_metrica'),
                              $this->_schema)
                       ->join(array('me'=>KT_B_DEFINICAO_METRICA),
                              '(tp.cd_definicao_metrica = me.cd_definicao_metrica)',
                              'tx_nome_metrica',
                              $this->_schema)
                       ->order('tp.ni_ordem_tipo_produto');

		if(!is_null($cd_definicao_metrica)){
			$select->where('tp.cd_definicao_metrica = ?', $cd_definicao_metrica, Zend_Db::INT_TYPE);
		}
		if(!is_null($cd_tipo_produto)){
			$select->where('tp.cd_tipo_produto = ?', $cd_tipo_produto, Zend_Db::INT_TYPE);
		}

        return $this->fetchAll($select);
	}
	
	public function getTipoProduto($cd_contrato, $comSelecione = false)
	{
        $objTable = new ContratoDefinicaoMetrica();

        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(array('tp'=>$this->_name),
                              array('cd_tipo_produto',
                                    'tx_tipo_produto'),
                              $this->_schema)
                       ->join(array('cm'=>$objTable->select()
                                                   ->from($objTable,'cd_definicao_metrica')
                                                   ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE)),
                              '(tp.cd_definicao_metrica = cm.cd_definicao_metrica)',
                              array())
                       ->order('ni_ordem_tipo_produto');

        $res = $this->fetchAll($select);

		if ($comSelecione === true) {
			$arrTipoProduto[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		foreach ($res as  $valor) {
			$arrTipoProduto[$valor["cd_tipo_produto"]] = $valor["tx_tipo_produto"];
		}

		return $arrTipoProduto;
	}
	
	public function salvarTipoProduto(array $arrDados)
	{
		$novo                        = $this->createRow();
		$novo->cd_tipo_produto 	     = $this->getNextValueOfField('cd_tipo_produto');
		$novo->cd_definicao_metrica  = $arrDados['cd_definicao_metrica_tipo_produto'];
		$novo->tx_tipo_produto       = $arrDados['tx_tipo_produto'];
		$novo->ni_ordem_tipo_produto = $arrDados['ni_ordem_tipo_produto'];
		
		if($novo->save()){
			return true;
		} else {
			return false;
		}
	}
	
	public function alterarTipoProduto(array $arrDados)
	{
		$where = "cd_tipo_produto = {$arrDados['cd_tipo_produto']}";
		unset ($arrDados['cd_definicao_metrica_tipo_produto']);
		if($this->update($arrDados,$where)){
			return true;
		} else {
			return false;
		}
	}
}