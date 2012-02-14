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

class GerenciamentoTesteCasoUsoController extends Base_Controller_Action
{

	public function init()
	{
		parent::init();
		$this->view->permissao_casoDeUso             = (ChecaPermissao::possuiPermissao('gerenciamento-teste-caso-uso') === true)? 1:0;
        $this->view->permissao_casoDeUso_analise     = (ChecaPermissao::possuiPermissao('gerenciamento-teste-caso-uso-analise') === true)? 1:0;
        $this->view->permissao_casoDeUso_solucao     = (ChecaPermissao::possuiPermissao('gerenciamento-teste-caso-uso-solucao') === true)? 1:0;
        $this->view->permissao_casoDeUso_homologacao = (ChecaPermissao::possuiPermissao('gerenciamento-teste-caso-uso-homologacao') === true)? 1:0;
	}

	public function indexAction()
	{
		 $this->initView();
	}

	public function gridGerenciamentoTesteCasoUsoAction()
	{
        $this->_helper->layout->disableLayout();
        $cd_projeto = $this->_request->getPost('cd_projeto',null);
        $cd_modulo  = $this->_request->getPost('cd_modulo',null);
        if( is_null($cd_projeto) ){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
        }
        $casoDeUso          = new CasoDeUso($this->_request->getControllerName());
        $itemTesteCasoDeUso = new ItemTesteCasoDeUso($this->_request->getControllerName());
        $arrCasoDeUso    = $casoDeUso->pesquisaCasoDeUsoProjeto($cd_projeto,$cd_modulo,'S');
        $this->view->res = $itemTesteCasoDeUso->getGridItemTesteCasoDeUso($arrCasoDeUso);
	}
}