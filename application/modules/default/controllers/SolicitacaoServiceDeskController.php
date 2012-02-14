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

class SolicitacaoServiceDeskController extends Base_Controller_Action
{
	private $solicitacaoServiceDesk;
	private $itemInventariadoServiceDesk;
	private $form;

	public function init()
	{
		parent::init();
		$this->solicitacaoServiceDesk = new SolicitacaoServiceDesk($this->_request->getControllerName());
		$this->itemInventariadoServiceDesk = new ItemInventariado($this->_request->getControllerName());

        // Definindo o form e os displays groups
		$this->form = new SolicitacaoServiceDeskForm();
		$this->form->addDisplayGroup(array('cd_objeto', 'cd_item_inventariado'),"primeira");
//		$this->form->addDisplayGroup(array('cd_contrato', 'cd_item_inventariado'),"primeira");
		$this->form->addDisplayGroup(array('tx_solicitante', 'cd_unidade', 'tx_sala_solicitante','tx_telefone_solicitante', 'tx_solicitacao'), 'segunda');
		$this->form->addDisplayGroup(array('tx_obs_solicitacao', 'ni_solicitacao', 'ni_solicitacao', 'submit'),"terceira");
		$this->form->getDisplayGroup('segunda')->setLegend('Gestor Solicitante');
	}

	public function indexAction()
	{
		$this->form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$this->view->form = $this->form;
       
		if ($this->_request->isPost()) {
			$this->formData = $this->_request->getPost();
			if ($this->form->isValid($this->formData)) {
				$ano 						     = date("Y");
				$novo 				             = $this->solicitacaoServiceDesk->createRow();
				$novo->ni_ano_solicitacao        = $ano;
				$novo->cd_objeto                 = $this->formData['cd_objeto'];
				$novo->ni_solicitacao	         = $this->solicitacaoServiceDesk->getNewValueByObjeto($this->formData['cd_objeto'], $ano);
				$novo->dt_solicitacao            = date('Y-m-d H:i:s');
				$novo->st_solicitacao            = '6';
				$novo->tx_solicitante   	     = $this->formData['tx_solicitante'];
				$novo->tx_sala_solicitante       = $this->formData['tx_sala_solicitante'];
				$novo->tx_telefone_solicitante   = $this->formData['tx_telefone_solicitante'];
				$novo->cd_unidade                = $this->formData['cd_unidade'];
				$novo->tx_solicitacao            = $this->formData['tx_solicitacao'];
				$novo->tx_obs_solicitacao 	     = $this->formData['tx_obs_solicitacao'];
				$novo->ni_prazo_atendimento      = 1;
				$novo->cd_item_inventariado      = $this->formData['cd_item_inventariado'];                
//				$novo->cd_item_inventario        = $this->formData['cd_item_inventario'];
//				$novo->cd_inventario             = $this->formData['cd_inventario'];                

                if ($this->formData['cd_item_inventariado']!= 999999) {
                    $auxItemInventariado             = $this->itemInventariadoServiceDesk->getItemInventariado($this->formData['cd_item_inventariado'])->toarray();
                    $novo->cd_item_inventario        = $auxItemInventariado['cd_item_inventario'];
                    $novo->cd_inventario             = $auxItemInventariado['cd_inventario'];
                }else{
                    $novo->cd_item_inventario        = null;
                    $novo->cd_inventario             = null;
                }

                if ($novo->save()) {
					if (K_ENVIAR_EMAIL == 'S'){
						$_mail = new Base_Controller_Action_Helper_Mail();
						$arrDados['_solicitacao'] = "{$novo->ni_solicitacao}/$ano";
						$_mail->enviaEmail($this->formData['cd_objeto'],$this->_request->getControllerName(),$arrDados);
					}
					$this->_redirect('./solicitacao-servico-service-desk');
				} 
			} else {
				$this->form->populate($this->formData);
			}
		}
	}

	public function editarAction()
	{
		$this->form->submit->setLabel(Base_Util::getTranslator('L_BTN_ALTERAR'));

        
        if ($this->_request->isPost()) {
			$this->formData = $this->_request->getPost();
            
			if ($this->form->isValid($this->formData)) {
				$this->formData['submit'];
				
				$obj = $this->solicitacaoServiceDesk->fetchRow(array(
                    'ni_solicitacao = ?' =>$this->form->getValue('ni_solicitacao'),
                    'ni_ano_solicitacao = ?' => $this->form->getValue('ni_ano_solicitacao'),
                    'cd_objeto = ?' => $this->form->getValue('cd_objeto')
                ));
                
                if ($this->formData['cd_item_inventariado']!= 999999) {
                    $auxItemInventariado                   = $this->itemInventariadoServiceDesk->getItemInventariado($this->formData['cd_item_inventariado'])->toarray();
    				$this->formData['cd_item_inventario']  = $auxItemInventariado['cd_item_inventario'];
        			$this->formData['cd_inventario']       = $auxItemInventariado['cd_inventario'];
                }else{
    				$this->formData['cd_item_inventario']  = null;
        			$this->formData['cd_inventario']       = null;
                }
                
                
                $obj->setFromArray($this->formData);
				$obj->save();
				$this->_redirect("./solicitacao-servico-service-desk");

			} else {
				// redireciona
				$this->form->populate($this->formData);
			}
		}  else {
			$excluir = new Zend_Form_Element_Button('bt_excluir');
			$excluir->setAttrib('id', 'bt_excluir');
			$excluir->setAttrib('class', 'vermelho buttonBar');
			$excluir->setLabel(Base_Util::getTranslator('L_BTN_EXCLUIR'));
		    $this->form->addElement($excluir);

			$ni_solicitacao      = (int)$this->_request->getParam('ni_solicitacao', 0);
			$ni_ano_solicitacao  = (int)$this->_request->getParam('ni_ano_solicitacao', 0);
			$cd_objeto           = (int)$this->_request->getParam('cd_objeto', 0);
			if ($ni_solicitacao > 0 && $ni_ano_solicitacao > 0 && $cd_objeto > 0) {
				$solicitacaoServiceDesk  = new SolicitacaoServiceDesk($this->_request->getControllerName());
                
                $where = array(
                    'ni_solicitacao = ?' =>$ni_solicitacao,
                    'ni_ano_solicitacao = ?' => $ni_ano_solicitacao,
                    'cd_objeto = ?' => $cd_objeto 
                );
                
				$row   = $solicitacaoServiceDesk->fetchRow($where);
                
				$row->tx_solicitacao = str_ireplace('\"','"',$row->tx_solicitacao);
                
                $this->form->populate($row->toArray());
				// Popula o combo de objeto somente com o objeto selecionado
				$objetoContrato    = new ObjetoContrato($this->_request->getControllerName());
				$arrObjContrato    = $objetoContrato->getObjeto($cd_objeto);
                
				$this->form->cd_objeto->addMultiOptions($arrObjContrato);

                if ($row['cd_item_inventariado']!= 999999) {
                    $itemInventariado = new ItemInventariado($this->_request->getControllerName()); 
                    $arrItemInventariado = $itemInventariado->comboItemInventariado($row['cd_inventario'],$row['cd_item_inventario']);
                }else{
                    $arrItemInventariado = array('999999'=>Base_Util::getTranslator('L_VIEW_COMBO_NAO_ESPECIFICADO'));
                }
                $this->form->cd_item_inventariado->addMultiOptions($arrItemInventariado);
			}
		}
		$this->view->form = $this->form;
	}

	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
        $where = array(
            "ni_solicitacao     = ?" =>(int)$this->_request->getParam('ni_solicitacao', 0),
            "ni_ano_solicitacao = ?" =>(int)$this->_request->getParam('ni_ano_solicitacao', 0),
            "cd_objeto          = ?" =>(int)$this->_request->getParam('cd_objeto', 0)
        );
        
		if ($this->solicitacaoServiceDesk->delete($where)) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
	
	public function pesquisaTipoSolicitacaoServiceDeskAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_objeto          = (int)$this->_request->getParam('cd_objeto', 0);
		$arrTipoSolicitacao = $this->solicitacaoServiceDesk->getTipoSolicitacao($cd_objeto);
		$strOptions         = "<option value=\"\" label=\"".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE').">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";

		foreach ($arrTipoSolicitacao as $codigo => $texto)
			$strOptions .= "<option value=\"{$codigo}\" label=\"{$texto}\">{$texto}</option>";

		echo $strOptions;
	}
    
	public function pesquisaItemInventariadoServiceDeskAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_objeto                      = (int)$this->_request->getPost('cd_objeto', 0);
        $itemInventariado               = new ItemInventariado($this->_request->getControllerName());
        $arrItemInventariadoServiceDesk = $itemInventariado->getItensInventariadoDemandaDados($cd_objeto, false);

        $strOptions = "<option value=\"\" label=\"".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."\" >".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
		$strOptions .= "<option value=\"999999\" label=\"".Base_Util::getTranslator('L_VIEW_COMBO_NAO_ESPECIFICADO')."\">".Base_Util::getTranslator('L_VIEW_COMBO_NAO_ESPECIFICADO')."</option>";

		foreach ($arrItemInventariadoServiceDesk as $codigo => $texto)
			$strOptions .= "<option value=\"{$codigo}\" label=\"{$texto}\"  >{$texto}</option>";

		echo $strOptions;
	}    
}
