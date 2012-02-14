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

class GerenciarProjetosSolicitacaoDetalhesController extends Base_Controller_Action
{
	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle(L_TIT_SOLICITACOES_DE_SERVICO_DETALHE));
	}

	public function indexAction()
	{
		$cd_objeto          = (int)$this->_request->getParam('cd_objeto', 0);
		$ni_solicitacao     = (int)$this->_request->getParam('ni_solicitacao', 0);
		$ni_ano_solicitacao = (int)$this->_request->getParam('ni_ano_solicitacao', 0);

		// Consulta Solicitacao
		$solicitacao 	= new Solicitacao($this->_request->getControllerName());
		$resSolicitacao = $solicitacao->getSolicitacao($cd_objeto, $ni_solicitacao, $ni_ano_solicitacao);
		
		$this->view->solicitacao = $resSolicitacao;
	}
}