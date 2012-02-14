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

class ProcessamentoParcela extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_PROCESSAMENTO_PARCELA;
	protected $_primary  = array('cd_processamento_parcela',
								 'cd_proposta',
								 'cd_projeto',
								 'cd_parcela');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function atualizaProcessamentoParcela($cd_projeto, $cd_proposta, $cd_parcela, $addRow)
	{
		$erros = false;

		$selectProcessamentoParcela = $this->select();
		$whereProcessamentoParcela  = "cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta} and cd_parcela = {$cd_parcela} and st_ativo = 'S'";
		$selectProcessamentoParcela->where($whereProcessamentoParcela);
		$rowProcessamentoParcela    = $this->fetchRow($selectProcessamentoParcela);

		if (!is_null($rowProcessamentoParcela))
		{
			if (!$this->update($addRow, $whereProcessamentoParcela))
			{
				$erros = true;
			}
		}

		return $erros;
	}

	public function insereProcessamentoParcelaAvaliacaoNegativa($cd_projeto, $cd_proposta, $cd_parcela, $oldRow)
	{
		$erros = false;

		$newRowProcessamentoParcela = $this->createRow();

		$newRowProcessamentoParcela->cd_processamento_parcela            = $this->getNextValueOfField('cd_processamento_parcela');
		$newRowProcessamentoParcela->cd_proposta                         = $cd_proposta;
		$newRowProcessamentoParcela->cd_projeto                          = $cd_projeto;
		$newRowProcessamentoParcela->cd_parcela                          = $cd_parcela;
		$newRowProcessamentoParcela->cd_objeto_execucao                  = $oldRow->cd_objeto_execucao;
		$newRowProcessamentoParcela->ni_ano_solicitacao_execucao         = $oldRow->ni_ano_solicitacao_execucao;
		$newRowProcessamentoParcela->ni_solicitacao_execucao             = $oldRow->ni_solicitacao_execucao;
		$newRowProcessamentoParcela->st_autorizacao_parcela              = $oldRow->st_autorizacao_parcela;
		$newRowProcessamentoParcela->dt_autorizacao_parcela              = $oldRow->dt_autorizacao_parcela;
		$newRowProcessamentoParcela->cd_prof_autorizacao_parcela = $oldRow->cd_prof_autorizacao_parcela;
		$newRowProcessamentoParcela->st_ativo                            = 'S';
		
		if (!$newRowProcessamentoParcela->save()){
			$erros = true;
		}
		
		return $erros;
	}
}