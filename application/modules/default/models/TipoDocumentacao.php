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

class TipoDocumentacao extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_TIPO_DOCUMENTACAO;
	protected $_primary  = 'cd_tipo_documentacao';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getDadosTipoDocumentacao($cd_tipo_documentacao = null)
    {
        $select = $this->select()
                       ->from($this,
                              array('cd_tipo_documentacao',
                                    'tx_tipo_documentacao',
                                    'tx_extensao_documentacao',
                                    'st_classificacao',
                                    'st_classicficacao_desc'=>new Zend_Db_Expr("CASE WHEN st_classificacao = 'R' THEN '".Base_Util::getTranslator('L_SQL_PROFISSIONAL')."'
                                                                                     WHEN st_classificacao = 'P' THEN '".Base_Util::getTranslator('L_SQL_PROJETO')."'
                                                                                     WHEN st_classificacao = 'T' THEN '".Base_Util::getTranslator('L_SQL_ITEM_TESTE')."'
                                                                                     WHEN st_classificacao = 'C' THEN '".Base_Util::getTranslator('L_SQL_COTROLE')."'
                                                                                     WHEN st_classificacao = 'D' THEN '".Base_Util::getTranslator('L_SQL_DISPONIBILIDADE_SERVICO')."'
                                                                                     WHEN st_classificacao = 'J' THEN '".Base_Util::getTranslator('L_SQL_PROJETO_CONTINUO')."'
                                                                                     WHEN st_classificacao = 'O' THEN '".Base_Util::getTranslator('L_SQL_CONTRATO')."' END")));
        $select->order('tx_extensao_documentacao');

		if(!is_null($cd_tipo_documentacao)){
            $select->where('cd_tipo_documentacao = ?', $cd_tipo_documentacao, Zend_Db::INT_TYPE);
		}
        return $this->fetchAll($select);
	}

	public function getTipoDocumentacao($tipoDocumentacao = null, $comSelecione = false)
	{
		$arrTipos = array();

		if ($comSelecione === true) {
			$arrTipos[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}

        $select = null;
		if (!is_null($tipoDocumentacao)) {
			$select = $this->select()
                           ->where('st_classificacao = ?',$tipoDocumentacao)
                           ->order("tx_tipo_documentacao");
		}

		$res = $this->fetchAll($select);

		foreach ($res as  $valor) {
			$arrTipos[$valor->cd_tipo_documentacao] = $valor->tx_tipo_documentacao;
		}

		return $arrTipos;
	}

	public function getExtensoesDocumentacao($cd_tipo_documentacao)
	{
		return $this->fetchAll(array("cd_tipo_documentacao = ?" => $cd_tipo_documentacao))->toArray();
	}

	public function salvarTipoDocumentacao(array $arrTipoDocumento)
	{
		$novo = $this->createRow();
		$novo->cd_tipo_documentacao     = $this->getNextValueOfField('cd_tipo_documentacao');
  		$novo->tx_tipo_documentacao     = $arrTipoDocumento['tx_tipo_documentacao'];
  		$novo->tx_extensao_documentacao = $arrTipoDocumento['tx_extensao_documentacao'];
  		$novo->st_classificacao         = $arrTipoDocumento['st_classificacao'];

  		if($novo->save()){
  			return true;
  		} else {
  			return false;
  		}
	}

	public function alterarTipoDocumentacao(array $arrTipoDocumentacao)
	{
		$where = "cd_tipo_documentacao = {$arrTipoDocumentacao['cd_tipo_documentacao']}";
		if($this->update($arrTipoDocumentacao, $where)){
			return true;
		} else {
			return false;
		}
	}
}