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

class Tabela extends Base_Db_Table_Abstract
{
	protected $_name       = KT_S_TABELA;
	protected $_primary    = array('cd_projeto','tx_tabela');
	protected $_schema     = K_SCHEMA;
	protected $_sequence   = false;

	public function getTabela($cd_projeto,$tx_tabela)
	{
		$select = $this->select()
                       ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                       ->where('tx_tabela  = ?', $tx_tabela);

		return  $this->fetchAll($select)->toArray();
	}
	
	public function insertTable(array $arrTabela)
	{
        $select = $this->select()
                       ->where('cd_projeto = ?', $arrTabela['cd_projeto'], Zend_Db::INT_TYPE)
                       ->where('tx_tabela  = ?', $arrTabela['tx_tabela']);

		$arrDados = $this->fetchAll($select)->toArray();

		if(count($arrDados) == 0){
			return $this->salvarTabela($arrTabela);
		} else {
			return $this->alterarTabela($arrTabela);
		}
	}

	private function salvarTabela(array $arrTabela)
	{
		$novo = $this->createRow();
		$novo->tx_tabela    = $arrTabela['tx_tabela'];
		$novo->cd_projeto   = $arrTabela['cd_projeto'];
		$novo->tx_descricao = $arrTabela['tx_descricao'];
		
		if($novo->save()){
			return true;
		} else {
			return false;
		}
	}
	
	private function alterarTabela(array $arrTabela)
	{
		$where = "tx_tabela = '{$arrTabela['tx_tabela']}' and cd_projeto = {$arrTabela['cd_projeto']}";
		if($this->update($arrTabela,$where)){
			return true;
		} else {
			return false;
		}
	}
	
}