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

class LogController extends Base_Controller_Action
{
	private $objTableLog;
		
	public function init()
	{
		parent::init();
		$this->objTableLog	= new Log($this->_request->getControllerName());
	}

	public function indexAction(){$this->initView();}

	/**
	 * Valida se o período é maior que 6 meses
	 * @return boolean
	 */
	public function validaPeriodoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$horaInical = " 00:00:00";
		$horaFinal	= " 23:59:59";
		
		$arrPost = $this->_request->getPost();
		
		$arrDtInicial = explode("/",trim($arrPost['dt_inicial']));
		$dt_inicial	  = trim($arrDtInicial[2]).'-'.trim($arrDtInicial[1]).'-'.trim($arrDtInicial[0]);

		$arrDtFinal   = explode("/",trim($arrPost['dt_final']));
		$dt_final	  = trim($arrDtFinal[2]).'-'.trim($arrDtFinal[1]).'-'.trim($arrDtFinal[0]);
		
		$objUtil	= new Util_Datediff($dt_inicial.$horaInical, $dt_final.$horaFinal, 'm');
		
		if( $objUtil->datediff() > 6 ){
			echo false;
		}else{
			echo true;
		}
	
	}
	
	public function gridLogAction()
	{
		$this->_helper->layout->disableLayout();
		
		$formData = $this->_request->getPost();

		$arrDados['cd_profissional'	] = (( (int) $formData['cmb_profissional_log']) != 0 ) ? $formData['cmb_profissional_log'] : null;
		$arrDados['dt_inicial'		] = Base_Util::converterDate($formData['dt_inicio_log'], 'DD/MM/YYYY', 'YYYY-MM-DD');
		$arrDados['dt_final'		] = Base_Util::converterDate($formData['dt_fim_log'   ], 'DD/MM/YYYY', 'YYYY-MM-DD');
		$arrDados['tx_tabela'		] = ($formData['cmb_tabela_log'] != "0" ) ? $formData['cmb_tabela_log'] : null;
		$arrDados['tx_ip'			] = ( !empty($formData['ip_log']) ) 	? $formData['ip_log'] 		  : null;
		
		$this->view->res = $this->objTableLog->getRegistroLog($arrDados);
	}
}