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

class Coluna extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_COLUNA;
	protected $_primary  = array('tx_tabela','tx_coluna','cd_projeto');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function recuperaDadosColuna($tx_tabela,$tx_coluna,$cd_projeto)
    {
        $select = $this->select()
                       ->from($this, 'tx_descricao')
                       ->where('tx_tabela  = ?', $tx_tabela)
                       ->where('tx_coluna  = ?', $tx_coluna)
                       ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
		return $this->fetchAll($select)->toArray();
	}
	
	public function insertColumn(array $arrAtributo)
    {
		if(count($arrAtributo) >= 0){
			if(array_key_exists('tx_tabela',$arrAtributo)){
				if(array_key_exists('cd_projeto',$arrAtributo)){
					if(array_key_exists('tx_coluna',$arrAtributo)){
						if($arrAtributo['tx_coluna'] != ""){
                            $where = array('tx_tabela = ?'  =>$arrAtributo['tx_tabela'],
                                           'tx_coluna = ?'  =>$arrAtributo['tx_coluna'],
                                           'cd_projeto = ?' =>$arrAtributo['cd_projeto']);
							$arrDados = $this->fetchAll($where)->toArray();
							if(count($arrDados) == 0){
								return $this->salvarColuna($arrAtributo);
							} else {
								return $this->alterarColuna($arrAtributo);
							}
						}
					}
				}
			}
		}
	}
	
	private function salvarColuna(array $arrAtributo)
    {
		$novo = $this->createRow();
		$novo->tx_tabela             = $arrAtributo['tx_tabela'];
		$novo->tx_coluna             = $arrAtributo['tx_coluna'];
		$novo->cd_projeto            = $arrAtributo['cd_projeto'];
		$novo->tx_descricao          = ($arrAtributo['tx_descricao']         ) ? $arrAtributo['tx_descricao']         :null;
		$novo->st_chave              = ($arrAtributo['st_chave']             ) ? $arrAtributo['st_chave']             :null;
		$novo->tx_tabela_referencia  = ($arrAtributo['tx_tabela_referencia'] ) ? $arrAtributo['tx_tabela_referencia'] :null;
		$novo->cd_projeto_referencia = ($arrAtributo['cd_projeto_referencia']) ? $arrAtributo['cd_projeto_referencia']:null;
		
		if($novo->save()){
			return true;
		} else {
			return false;
		}
	}
	
	private function alterarColuna(array $arrAtributo)
    {
        $where = array('tx_tabela = ?'  =>$arrAtributo['tx_tabela'],
                       'tx_coluna = ?'  =>$arrAtributo['tx_coluna'],
                       'cd_projeto = ?' =>$arrAtributo['cd_projeto']);
		          
		if($this->update($arrAtributo,$where)){
			return true;
		} else {
			return false;
		}
	}
}