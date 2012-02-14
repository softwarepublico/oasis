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

class ItemTesteController extends Base_Controller_Action
{
	private $itemTeste;
	private $arrTipoItemTeste;

    private function setArrTipoItemteste()
    {
        $this->arrTipoItemTeste = array(
                            '0' => Base_Util ::getTranslator('L_VIEW_COMBO_SELECIONE'),
                            'C' => Base_Util::getTranslator('L_VIEW_COMBO_CASO_USO'),
                            'R' => Base_Util::getTranslator('L_VIEW_COMBO_REQUISITO'),
                            'N' => Base_Util::getTranslator('L_VIEW_COMBO_REGRA_NEGOCIO'),
                            'I' => Base_Util::getTranslator('L_VIEW_COMBO_INTERFACE')
                        );
    }

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_ITEM_TESTE'));
        $this->setArrTipoItemteste();
		$this->itemTeste = new ItemTeste($this->_request->getControllerName());
		$this->view->arrTipoItemTeste = $this->arrTipoItemTeste;
	}

	public function indexAction()
	{
		$form = new ItemTesteForm(null,$this->arrTipoItemTeste);
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$this->view->form = $form;

		if ($this->_request->isPost()) {

			$formData = $this->_request->getPost();

			if ($form->isValid($formData)) {

				$novo 			           = $this->itemTeste->createRow();
				$novo->cd_item_teste       = $this->itemTeste->getNextValueOfField('cd_item_teste');
				$novo->tx_item_teste       = $formData['tx_item_teste'];
				$novo->st_item_teste       = ($formData['st_item_teste'])?$formData['st_item_teste']:null;
				$novo->st_obrigatorio      = ($formData['st_obrigatorio'])?$formData['st_obrigatorio']:null;
				$novo->st_tipo_item_teste  = ($formData['st_tipo_item_teste'])?$formData['st_tipo_item_teste']:null;
                $novo->ni_ordem_item_teste = ($formData['ni_ordem_item_teste'])?$formData['ni_ordem_item_teste']:null;

                $ordemOk = $this->itemTeste->verifyOrdemByTipo($formData['ni_ordem_item_teste'],$formData['st_tipo_item_teste']);

                if( $ordemOk ){
                    if ($novo->save()) {
    					$this->_redirect('./item-teste');
    				} else {
                        die(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
    				}
                } else {
					$this->_redirect('./item-teste/index/erro/ordem');
                }
			} else {
				$form->populate($formData);
			}
		}
		$erro = $this->_request->getParam('erro',null);
	    $this->view->erroOrdem = (!is_null($erro)&&$erro=='ordem')?Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'):"";
	    $this->initView();
	}

	public function editarAction()
	{
		$form = new ItemTesteForm(null,$this->arrTipoItemTeste);
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));

		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				$formData['submit'];

				$cd_item_teste = (int)$form->getValue('cd_item_teste');

				$obj = $this->itemTeste->fetchRow("cd_item_teste = {$cd_item_teste}");

				$obj->tx_item_teste       =  $formData['tx_item_teste'];
				$obj->st_item_teste       = ($formData['st_item_teste'])?$formData['st_item_teste']:null;
				$obj->st_obrigatorio      = ($formData['st_obrigatorio'])?$formData['st_obrigatorio']:null;
				$obj->st_tipo_item_teste  = ($formData['st_tipo_item_teste'])?$formData['st_tipo_item_teste']:null;
				$obj->ni_ordem_item_teste = ($formData['ni_ordem_item_teste'])?$formData['ni_ordem_item_teste']:null;

                $ordemOk = $this->itemTeste->verifyOrdemByTipo($formData['ni_ordem_item_teste'],$formData['st_tipo_item_teste'],$ni_ordem_item_teste);
                $ordemOk = ($ni_ordem_item_teste==$obj->ni_ordem_item_teste)?true:false;
                if( $ordemOk ){
                    if ($obj->save()) {
                        $this->_redirect('./item-teste');
                    } else {
                        die(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
                    }
                } else {
                    $this->_redirect('./item-teste/index/erro/ordem');
                }
			} else {
				// redireciona
				$form->populate($formData);
			}
		}  else {
			$cd_item_teste= (int)$this->_request->getParam('cd_item_teste', 0);
			if ($cd_item_teste> 0) {
				$row   = $this->itemTeste->fetchRow("cd_item_teste = {$cd_item_teste}");
		        $arr = $row->toArray();
				$form->populate($arr);
			}
		}

		$this->view->form = $form;

	}

	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_item_teste = (int)$this->_request->getParam('cd_item_teste', 0);
		if ($this->itemTeste->delete("cd_item_teste = {$cd_item_teste}")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}

	public function gridItemTesteAction()
	{
		$this->_helper->layout->disableLayout();
		$st_tipo_item_teste = $this->_request->getPost('st_tipo_item_teste', 0);
		if( $st_tipo_item_teste == '0'){
            $res = $this->itemTeste->fetchAll(null,array('st_tipo_item_teste','ni_ordem_item_teste'))->toArray();
		} else {
            $res = $this->itemTeste->fetchAll("st_tipo_item_teste = '{$st_tipo_item_teste}'",'ni_ordem_item_teste')->toArray();
		}
        $this->view->res = $res;
	}
	public function getNextOrdemItemTestePorTipoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$st_tipo_item_teste = $this->_request->getPost('st_tipo_item_teste', null);
        if( !is_null($st_tipo_item_teste) ){
		  echo $this->itemTeste->getNextOrdemItemTestePorTipo($st_tipo_item_teste);
        }
	}
}