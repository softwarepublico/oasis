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

class JustificativaDemandaController extends Base_Controller_Action
{
	public function indexAction()
	{
		$this->_helper->layout->disableLayout();

		$params = $this->_request->getPost();

		$this->view->cd_demanda			= $params['cd_demanda'];
		$this->view->cd_nivel_servico	= $params['cd_nivel_servico'];
		$this->view->dt_demanda			= $params['dt_demanda'];
	}
	
	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$erros = false;
		
		$post = $this->_request->getPost();	
		$cd_demanda                         = $post['cd_demanda_justificativa_demanda'];
		$cd_nivel_servico                   = $post['cd_nivel_servico_justificativa_demanda'];
		$cd_profissional                    = $_SESSION['oasis_logged'][0]['cd_profissional'];
		
		$objDemandaNivelProfissional = new DemandaProfissionalNivelServico($this->_request->getControllerName());
		
		$addRow = array();
		$addRow['tx_justificativa_nivel_servico'] = $post['tx_justificativa_demanda'];
		$addRow['dt_justificativa_nivel_servico'] = date('Y-m-d H:i:s');
		
		
		$erros = $objDemandaNivelProfissional->atualizaDemandaNivelServico($cd_demanda, $cd_profissional, $cd_nivel_servico, $addRow);
		if ($erros === false) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
	}
}