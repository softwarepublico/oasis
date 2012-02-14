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

class ParecerTecnicoParcelaController extends Base_Controller_Action
{
	public function init()
	{
		parent::init();
	}

	public function indexAction()
	{
		$this->_helper->layout->disableLayout();

		$params = $this->_request->getPost();

		//*** ITEM PARECER TECNICO PARCELA
		//Busca lista de itens de parecer técnico
		//para uso nas modais Parecer Técnico
		$itemParecerTecnicoParcela      = new ItemParecerTecnico($this->_request->getControllerName());
		// Utiliza o objeto select para definir um order by da consulta
		$selectItemParecerTecnicoParcela = $itemParecerTecnicoParcela->select();
		$selectItemParecerTecnicoParcela->where('st_parcela is not null');

		// Recupera os dados e armazena em um array
		$resItemParecerTecnicoParcela    = $itemParecerTecnicoParcela->fetchAll($selectItemParecerTecnicoParcela);

		//  Associa este array com um atributo da camada de visao
		$this->view->listaItemParecerTecnicoParcela = $resItemParecerTecnicoParcela;

		$this->view->cd_projeto			= $params['cd_projeto'		];
		$this->view->cd_proposta		= $params['cd_proposta'		];
		$this->view->cd_parcela			= $params['cd_parcela'		];
		$this->view->ni_parcela			= $params['ni_parcela'		];
		$this->view->st_pendente		= $params['st_pendente'		];
		$this->view->tx_sigla_projeto	= $params['tx_sigla_projeto'];
	}

	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;

		$post = $this->_request->getPost();

				//atualiza os campos de parecer técnico da parcela
		//na tabela s_processamento_parcela, com st_ativo = 'S'
		//Quando o parecer é negativo, o processo é diferente do parecer negativo da proposta
		//No parecer negativo de proposta, o registro ativo (st_ativo = 'S') simplesmente
		//se torna igual a null
		//No parecer técnico negativo de parcela, os campos de parecer ténico da parcela são gravados
		//no registro atualmente ativo, este registro é atualizado para st_ativo = null
		//e duplicado com novo código sequencial e com dados preenchidos
		//até o ponto de autorização de parcela, fazendo com que a parcela apareça novamente
		//para ser fechada na tela de Execução de Proposta
		if ($erros === false){
			$erros = $this->atualizaParecerTecnicoParcela($post, $cd_processamento_parcela);
		}

		//grava a avaliação do parecer técnico na tabela a_parecer_tecnico_parcela
		//para cada item de parecer técnico avaliado
		if ($erros === false){
			$erros = $this->gravaParecerTecnicoParcela($post, $cd_processamento_parcela);
		}

		if ($erros === true) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		} else {
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			if (K_ENVIAR_EMAIL == "S") {
				$_objMail               = new Base_Controller_Action_Helper_Mail();
				$arrInf['cd_projeto']   = $post['cd_projeto_parecer_tecnico_parcela'];
				$arrInf['cd_proposta']  = $post['cd_proposta_parecer_tecnico_parcela'];
				$arrInf['cd_parcela']   = $post['cd_parcela_parecer_tecnico_parcela'];
				$arrInf['st_msg_email'] = $_SESSION['st_msg_email'];
				$arrInf['_tx_obs']      = $_SESSION['tx_obs'];
				$arrDadosEmail          = $_objMail->setDadosMsgEmail($arrInf, $this->_request->getControllerName());
				unset ($_SESSION['st_msg_email']);
				unset ($_SESSION['tx_obs']);
			}
		}
	}

	public function atualizaParecerTecnicoParcela($post, &$cd_processamento_parcela)
	{
		$erros = false;

		$cd_projeto  = $post['cd_projeto_parecer_tecnico_parcela'];
		$cd_proposta = $post['cd_proposta_parecer_tecnico_parcela'];
		$cd_parcela  = $post['cd_parcela_parecer_tecnico_parcela'];

		$objProcessamentoParcela = new ProcessamentoParcela($this->_request->getControllerName());

		//busca o registro de processamento da parcela
		//na tabela s_processamento_parcela
		//para obter o código sequencial do registro
		//para uso na associativa a_parecer_tecnico_parcela
		//no método gravaParecerTecnicoProposta
		$rowProcessamentoParcela  = $objProcessamentoParcela->fetchRow("cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta} and cd_parcela = {$cd_parcela} and st_ativo = 'S'");
		$cd_processamento_parcela = $rowProcessamentoParcela->cd_processamento_parcela;

		//seta os campos a serem atualizados na tabela s_processamento_parcela
		$addRow = array();
		$addRow['st_parecer_tecnico_parcela']     = $post['st_parecer_tecnico_parcela'];
		$addRow['dt_parecer_tecnico_parcela']     = date('Y-m-d H:i:s');
		$addRow['tx_obs_parecer_tecnico_parcela'] = $post['tx_obs_parecer_tecnico_parcela'];
		$addRow['cd_prof_parecer_tecnico_parc']   = $_SESSION["oasis_logged"][0]["cd_profissional"];

		//se no parecer técnico anterior, a parcela foi marcada como pendente
		//e neste parecer técnico a avaliação foi positiva,
		//seta o fim da pendência e data de fim da pendência
		if ($post['st_parecer_tecnico_parcela'] == 'A' && $rowProcessamentoParcela->st_pendente == "S")
		{
			$addRow['st_pendente']      = null;
			$addRow['dt_fim_pendencia'] = date('Y-m-d H:i:s');
		}
		
		//se o parecer técnico foi negativo, indica a inativação do registro atual
		//e guarda os dados do registro atual para serem duplicados 
		//pela função insereProcessamentoParcelaAvaliacaoNegativa 
        if (K_PARECER_TECNICO_NEGATIVO_COORDENACAO == 'N') {
    		if ($post['st_parecer_tecnico_parcela'] == 'N')
        	{
                $addRow['st_ativo'] = null;
			    $oldRow             = $rowProcessamentoParcela;
            }  
		}

		//se a parcela foi avaliada como pendente, indica a pendencia do registro atual
		//e marca a data de início da pendencia
		if ($post['st_parecer_tecnico_parcela'] == 'P')
		{
			$addRow['st_pendente']         = "S";
			$addRow['dt_inicio_pendencia'] = date('Y-m-d H:i:s');
		}
		
		//grava os dados obtidos na tabela s_processamento_parcela
		$erros = $objProcessamentoParcela->atualizaProcessamentoParcela($cd_projeto, $cd_proposta, $cd_parcela, $addRow);

		//se o parecer foi negativo e o registro atualmente ativo foi atualizado,
		//deixando de ser o registro ativo da parcela,
		//insere um novo registro, que passará a ser o registro ativo da parcela
		//preenchendo os campos até o ponto de autorização de parcela
        if (K_PARECER_TECNICO_NEGATIVO_COORDENACAO=='N') {
            if ($post['st_parecer_tecnico_parcela'] == 'N' && $erros === false)
            {
                $erros = $objProcessamentoParcela->insereProcessamentoParcelaAvaliacaoNegativa($cd_projeto, $cd_proposta, $cd_parcela, $oldRow);
            }
        }
        if (K_ENVIAR_EMAIL == "S") {
            $_SESSION['st_msg_email'] = $post['st_parecer_tecnico_parcela'];
            $_SESSION['tx_obs']       = $post['tx_obs_parecer_tecnico_parcela'];
        }

		return $erros;
	}

	public function gravaParecerTecnicoParcela($post, $cd_processamento_parcela)
	{
		$erros = false;

		$objParecerTecnicoParcela = new ParecerTecnicoParcela($this->_request->getControllerName());
		$addRow = array();

		foreach($post['cd_item_parecer_tecnico'] as $cd_item_parecer_tecnico => $st_avaliacao)
		{
			if ($erros === false)
			{
				$where = "cd_projeto = {$post['cd_projeto_parecer_tecnico_parcela']} 
							and cd_proposta = {$post['cd_proposta_parecer_tecnico_parcela']} 
							and cd_parcela = {$post['cd_parcela_parecer_tecnico_parcela']} 
							and cd_item_parecer_tecnico = {$cd_item_parecer_tecnico} 
							and cd_processamento_parcela = {$cd_processamento_parcela}";
					
				if (!is_null($objParecerTecnicoParcela->fetchRow($where)))
				{
					$addRow['st_avaliacao'] = $st_avaliacao;
					
					if (!$objParecerTecnicoParcela->update($addRow, $where))
					{
						$erros = true;
					}
				}
				else
				{
					$rowParecerTecnicoParcela                            = $objParecerTecnicoParcela->createRow();
					$rowParecerTecnicoParcela->cd_item_parecer_tecnico   = $cd_item_parecer_tecnico;
					$rowParecerTecnicoParcela->cd_parcela                = $post['cd_parcela_parecer_tecnico_parcela'];
					$rowParecerTecnicoParcela->cd_proposta               = $post['cd_proposta_parecer_tecnico_parcela'];
					$rowParecerTecnicoParcela->cd_projeto                = $post['cd_projeto_parecer_tecnico_parcela'];
					$rowParecerTecnicoParcela->cd_processamento_parcela  = $cd_processamento_parcela;
					$rowParecerTecnicoParcela->st_avaliacao              = $st_avaliacao;
				
					if (!$rowParecerTecnicoParcela->save())
					{
						$erros = true;
					}
				}
			}

		}
		return $erros;
	}
}
