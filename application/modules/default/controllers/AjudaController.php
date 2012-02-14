
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

class AjudaController extends Base_Controller_Action
{
	public function init()
	{
		parent::init();
	}

	public function indexAction()
	{
		$this->_helper->layout->disableLayout();

		$pagina = $this->_request->getParam('pagina');

        $this->view->headTitle(Base_Util::setTitle('L_TIT_AJUDA'));
        $this->view->pagina			= $pagina;

		if($pagina !== 'index'){
			$menu					= new Menu($this->_request->getControllerName());
			$this->view->dataMenu	= $menu->getMenuProfissional( $_SESSION['oasis_logged'][0]['cd_profissional'] );
		}
	}

	public function abrePaginaAjudaAction()
    {
    	$this->_helper->layout->disableLayout();
		$paginaPhtml  = $this->_request->getParam('paginaPhtml');

		$barra		  = DIRECTORY_SEPARATOR;
		//cria o diretorio que contem o arquivo de ajuda

		$pathArqAjuda  = Base_Util::baseUrlModule('default', 'views');
        $pathArqAjuda .= "scripts"   .$barra .
                         "ajuda"     .$barra .
                         $paginaPhtml.".phtml";

		//verifica se existe o arquivo de ajuda para a tela
		if(!file_exists($pathArqAjuda)){
			$render = "ajuda/indisponivel.phtml";
		}else{
			$render = "ajuda/{$paginaPhtml}.phtml";
		}
		die ($this->view->render($render));
    }
}