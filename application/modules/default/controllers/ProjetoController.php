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

class ProjetoController extends Base_Controller_Action
{
	private $projeto;

	public function init()
	{
		parent::init();
		$this->projeto = new Projeto($this->_request->getControllerName());
	}

	public function indexAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				$tx_contexto_geral_projeto = str_ireplace("<o:p></o:p>", "", $formData['tx_contexto_geral_projeto']);
	            $tx_escopo_projeto         = str_ireplace("<o:p></o:p>", "", $formData['tx_escopo_projeto']);
					
				$novo 				             = $this->projeto->createRow();
				$novo->cd_projeto	             = $this->projeto->getNextValueOfField('cd_projeto');
				$novo->tx_projeto                = $this->toUpper($formData['tx_projeto']);
				$novo->tx_sigla_projeto          = $this->toUpper($formData['tx_sigla_projeto']);
				$novo->tx_contexto_geral_projeto = $tx_contexto_geral_projeto;
				$novo->tx_escopo_projeto         = $tx_escopo_projeto;
				$novo->cd_unidade                = (int)$formData['cd_unidade'];
				$novo->tx_gestor_projeto         = $this->toUpper($formData['tx_gestor_projeto']);
				$novo->tx_obs_projeto            = $formData['tx_obs_projeto'];
				$novo->st_impacto_projeto        = $formData['st_impacto_projeto'];
				$novo->st_prioridade_projeto     = $formData['st_prioridade_projeto'];
				$novo->cd_profissional_gerente   = (int)$formData['cd_profissional_gerente'];
				$novo->cd_status                 = (int)$formData['cd_status'];
				$novo->tx_publico_alcancado      = $this->toUpper($formData['tx_publico_alcancado']);
				$novo->ni_mes_inicio_previsto    = $formData['ni_mes_inicio_previsto'];
				$novo->ni_ano_inicio_previsto    = $formData['ni_ano_inicio_previsto'];
				$novo->ni_mes_termino_previsto   = $formData['ni_mes_termino_previsto'];
				$novo->ni_ano_termino_previsto   = $formData['ni_ano_termino_previsto'];
				$novo->tx_co_gestor_projeto      = $this->toUpper($formData['tx_co_gestor_projeto']);

				if ($novo->save()) {
					echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
				} else {
					echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
				}

			} else {
				echo Base_Util::getTranslator('L_MSG_ALERT_VARIFICA_INFORMAÇOES');
			}
		}
	}

	public function editarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$form = new ProjetoForm();
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
			
	            $cd_projeto = (int)$this->_request->getParam('cd_projeto');
	
	            $tx_contexto_geral_projeto = str_ireplace("<o:p></o:p>", "", $formData['tx_contexto_geral_projeto']);
	            $tx_escopo_projeto         = str_ireplace("<o:p></o:p>", "", $formData['tx_escopo_projeto']);

				$upRow = array();
				$upRow['tx_projeto']                = $this->toUpper($formData['tx_projeto']);
				$upRow['tx_sigla_projeto']          = $this->toUpper($formData['tx_sigla_projeto']);
				$upRow['tx_contexto_geral_projeto'] = $tx_contexto_geral_projeto;
				$upRow['tx_escopo_projeto']         = $tx_escopo_projeto;
				$upRow['cd_unidade']                = $formData['cd_unidade'];
				$upRow['tx_gestor_projeto']         = $this->toUpper($formData['tx_gestor_projeto']);
				$upRow['cd_profissional_gerente']   = $formData['cd_profissional_gerente'];
				$upRow['cd_status']                 = $formData['cd_status'];
				$upRow['tx_obs_projeto']            = $formData['tx_obs_projeto'];
				$upRow['st_impacto_projeto']        = $formData['st_impacto_projeto'];
				$upRow['st_prioridade_projeto']     = $formData['st_prioridade_projeto'];
				$upRow['cd_projeto']                = $formData['cd_projeto'];
				$upRow['tx_publico_alcancado']      = $this->toUpper($formData['tx_publico_alcancado']);
				$upRow['ni_mes_inicio_previsto']    = $formData['ni_mes_inicio_previsto'];
				$upRow['ni_ano_inicio_previsto']    = $formData['ni_ano_inicio_previsto'];
				$upRow['ni_mes_termino_previsto']   = $formData['ni_mes_termino_previsto'];
				$upRow['ni_ano_termino_previsto']   = $formData['ni_ano_termino_previsto'];
				$upRow['tx_co_gestor_projeto']      = $this->toUpper($formData['tx_co_gestor_projeto']);

				$where = "cd_projeto={$cd_projeto}";

				if ($this->projeto->update($upRow, $where)) {
					
					$cd_proposta = (int)$this->_request->getParam('cd_proposta',0);
					if ($cd_proposta != 0){
						$proposta               = new Proposta($this->_request->getControllerName());
						$addRow                 = array();
						$addRow['st_descricao'] = '1';
						$erros                  = $proposta->atualizaProposta($cd_projeto, $cd_proposta, $addRow);
						
						if ($erros === false){
							echo Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
						}
					}
					else{
						echo Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
					}
					
				} else {
					echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
				}
			} else {
				echo Base_Util::getTranslator('L_MSG_ALERT_VARIFICA_INFORMAÇOES');
			}
		}  else {
			$excluir = new Base_Form_Element_Button('bt_excluir');
			$excluir->setAttrib('id', 'bt_excluir');
			$excluir->setAttrib('class', 'vermelho buttonBar');
			$excluir->setLabel(Base_Util::getTranslator('L_BTN_EXCLUIR'));
			$form->addElement($excluir);

			$cd_projeto= (int)$this->_request->getParam('cd_projeto', 0);
			if ($cd_projeto > 0) {
				$projeto  = new Projeto($this->_request->getControllerName());
				$row   = $projeto->fetchRow("cd_projeto=$cd_projeto");
				$row->tx_contexto_geral_projeto = str_ireplace('\"','"',$row->tx_contexto_geral_projeto);
				$row->tx_escopo_projeto         = str_ireplace('\"','"',$row->tx_escopo_projeto);
				$form->populate($row->toArray());
			}
		}
	}

	public function excluirAction()
	{

		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto = (int)$this->_request->getParam('cd_projeto', 0);
		if ($this->projeto->delete("cd_projeto=$cd_projeto")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}

	public function listarAction()
	{
		$res = $this->projeto->fetchAll();
		$this->view->res  = $res;
	}
}