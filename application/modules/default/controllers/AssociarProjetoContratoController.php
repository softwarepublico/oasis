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

class AssociarProjetoContratoController extends Base_Controller_Action
{
    private $objContrato;
    private $objContratoProjeto;

	public function init()
	{
		parent::init();
        $this->objContrato        = new Contrato($this->_request->getControllerName());
        $this->objContratoProjeto = new ContratoProjeto($this->_request->getControllerName());
	}

	public function indexAction()
	{
        //$this->view->arrContrato = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P');
    }

    public function pesquisaProjetoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_contrato    = $this->_request->getParam("cd_contrato", 0);
        //recupera os dados do contrato
        $dadosContrato  = $this->objContrato->find($cd_contrato)->current()->toArray();
        //verifica o status do contrato
        $statusContrato = $dadosContrato['st_contrato'];

        if ($cd_contrato == 0) {
			echo '';
		} else {

            $projetosForaDoContrato = $this->objContratoProjeto->pesquisaProjetoForaDoContrato($cd_contrato);
            $projetosNoContrato     = $this->objContratoProjeto->pesquisaProjetoNoContrato($cd_contrato);

			$arr1 = "";

			foreach ($projetosForaDoContrato as $fora) {
				$arr1 .= "<option value=\"{$fora['cd_projeto']}\">{$fora['tx_sigla_projeto']}</option>";
			}

			$arr2 = "";
			foreach ($projetosNoContrato as $no) {
				$arr2 .= "<option value=\"{$no['cd_projeto']}\">{$no['tx_sigla_projeto']}</option>";
			}

			$retorno = array($arr1, $arr2, $statusContrato);
			echo Zend_Json_Encoder::encode($retorno);
		}
    }

    public function associaProjetoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();

		$cd_contrato = $post['cd_contrato'];
		$projetos    = Zend_Json_Decoder::decode($post['projetos']);

		foreach ($projetos as $projeto) {

			$novo = $this->objContratoProjeto->createRow();
			$novo->cd_contrato = $cd_contrato;
			$novo->cd_projeto  = $projeto;
			$novo->save();
		}
    }
    
    public function desassociaProjetoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();

		$cd_contrato = $post['cd_contrato'];
		$projetos    = Zend_Json_Decoder::decode($post['projetos']);

		foreach ($projetos as $projeto) {
			$where = "cd_contrato = {$cd_contrato} and cd_projeto = {$projeto}";
			$this->objContratoProjeto->delete( $where );
		}
	}
	
	public function pesquisaProjetoContratoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$cd_contrato = (int) $this->_request->getParam("cd_contrato", 0);
		$arrProjetos = $this->objContratoProjeto->listaProjetosContrato($cd_contrato, true);
		
		$options = '';
		
		foreach( $arrProjetos as $key=>$value){
		$options .= "<option value=\"{$key}\">{$value}</option>";
		}
		
		echo $options;
	}

}