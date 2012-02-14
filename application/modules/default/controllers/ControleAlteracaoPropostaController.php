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

class ControleAlteracaoPropostaController extends Base_Controller_Action
{

	private $_objContratoDefinicaoMetrica;
	private $_objProposta;

	public function init()
	{
		parent::init();
		$this->_objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());
		$this->_objProposta                 = new Proposta($this->_request->getControllerName());
	}

	public function indexAction()
	{}

	public function pesquisaPropostasAlteracaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_projeto = $this->_request->getParam('cd_projeto');

		$this->view->arrayMeses               = Base_Util::getMes();
		$this->view->res                      = $this->_objProposta->getPropostasAlteracao($cd_projeto);
		$this->arrDadosMetricaPadrao	      = $this->_objContratoDefinicaoMetrica->getSiglaUnidadeMetricaPadraoContratoAtivoProjeto($cd_projeto);
		$this->view->tx_sigla_unidade_metrica = (count($this->arrDadosMetricaPadrao)>0)? $this->arrDadosMetricaPadrao["tx_sigla_unidade_metrica"] : Base_Util::getTranslator('L_VIEW_UNIDADES_METRICA');

		echo $this->view->render('controle-alteracao-proposta/pesquisa-alteracao-proposta.phtml');
	}
}