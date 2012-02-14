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

class UtilController extends Base_Controller_Action 
{
	private $objUtil;
	
	public function init()
	{
		parent::init();
		$this->objUtil = new Base_Controller_Action_Helper_Util();	
	}
	
	public function atualizaDataAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$mes = (int)$this->_request->getParam('mes');
		$ano = (int)$this->_request->getParam('ano');
		
		$mes = ($mes == 0)?date('n'):$mes;
		$ano = ($ano == 0)?date('Y'):$ano;
		
		$descMes = $this->objUtil->getMes($mes);
		echo "{$descMes}/{$ano}";
	}

	public function datediff($dataHora1, $dataHora2)
	{
		$dia1 = substr($dataHora1,8,2);
		$mes1 = substr($dataHora1,5,2);
		$ano1 = substr($dataHora1,0,4);
		$hor1 = substr($dataHora1,11,2);
		$min1 = substr($dataHora1,14,2);
		$seg1 = substr($dataHora1,17,2);

		$dia2 = substr($dataHora2,8,2);
		$mes2 = substr($dataHora2,5,2);
		$ano2 = substr($dataHora2,0,4);
		$hor2 = substr($dataHora2,11,2);
		$min2 = substr($dataHora2,14,2);
		$seg2 = substr($dataHora2,17,2);


		$time1 = mktime($hor1,$min1,$seg1,$mes1,$dia1,$ano1);
		$time2 = mktime($hor2,$min2,$seg2,$mes2,$dia2,$ano2);

		$timeEmMinutos = round( ($time2 - $time1)/60 , 0 );
		
		return $timeEmMinutos;
	}
}