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

class HistoricoProjetoContinuadoController extends Base_Controller_Action
{
	private $historicoProjetoContinuado;

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_HISTORICO_PROJETO_CONTINUADO'));
		$this->historicoProjetoContinuado = new HistoricoProjetoContinuado($this->_request->getControllerName());
	}

	public function indexAction()
	{}

	public function salvarHistoricoProjetoContinuadoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();

        $arrDados['dt_inicio_hist_proj_continuado'] = Base_Util::converterDate($arrDados['dt_inicio_hist_proj_continuado'], 'DD/MM/YYYY', 'YYYY-MM-DD');
        $arrDados['dt_fim_hist_projeto_continuado'] = Base_Util::converterDate($arrDados['dt_fim_hist_projeto_continuado'], 'DD/MM/YYYY', 'YYYY-MM-DD');

		$return = $this->historicoProjetoContinuado->salvarHistoricoProjetoContinuado($arrDados);
		$msg = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'):Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		echo $msg;		
	}
	
	public function alterarHistoricoProjetoContinuadoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();
		
        $arrDados['dt_inicio_hist_proj_continuado'] = Base_Util::converterDate($arrDados['dt_inicio_hist_proj_continuado'], 'DD/MM/YYYY', 'YYYY-MM-DD');
        $arrDados['dt_fim_hist_projeto_continuado'] = Base_Util::converterDate($arrDados['dt_fim_hist_projeto_continuado'], 'DD/MM/YYYY', 'YYYY-MM-DD');
        
		$return = $this->historicoProjetoContinuado->alterarHistoricoProjetoContinuado($arrDados);
		$msg = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO'):Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		echo $msg;		
	}

	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_historico_proj_continuado = (int)$this->_request->getParam('cd_historico_proj_continuado', 0);
		if ($this->historicoProjetoContinuado->delete("cd_historico_proj_continuado=$cd_historico_proj_continuado")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	} 
	
	public function recuperaHistoricoProjetoContinuadoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_historico_proj_continuado = (int)$this->_request->getParam('cd_historico_proj_continuado', 0);
		
		$arrDados = $this->historicoProjetoContinuado->getDadosHistoricoProjetoContinuado($cd_historico_proj_continuado);

		foreach($arrDados as $key=>$value){

			$arrDados[$key]['tx_hist_projeto_continuado'    ] = str_ireplace('\"','"',$value['tx_hist_projeto_continuado']);
			$arrDados[$key]['dt_inicio_hist_proj_continuado'] = Base_Util::converterDate($value['dt_inicio_hist_proj_continuado'], 'YYYY-MM-DD', 'DD/MM/YYYY');
			$arrDados[$key]['dt_fim_hist_projeto_continuado'] = Base_Util::converterDate($value['dt_fim_hist_projeto_continuado'], 'YYYY-MM-DD', 'DD/MM/YYYY');
		}
		echo Zend_Json::encode($arrDados);
	}
	
	public function gridHistoricoProjetoContinuadoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_objeto = (int)$this->_request->getParam('cd_objeto', 0);
		$arrDados = $this->historicoProjetoContinuado->getDadosHistoricoProjetoContinuado(null, $cd_objeto);
		foreach($arrDados as $key=>$value){
			$arrDados[$key]['tx_hist_projeto_continuado'] = strip_tags($value['tx_hist_projeto_continuado']);
		}
		$this->view->res = $arrDados;
	}
}