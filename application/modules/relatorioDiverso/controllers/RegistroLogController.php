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

class RelatorioDiverso_RegistroLogController extends Base_Controller_Action
{
	private $objProfissional;
	private $objLog;

	public function init()
	{
		parent::init();
		$this->objProfissional	= new Profissional($this->_request->getControllerName());
		$this->objLog			= new Log($this->_request->getControllerName());
	}	
	
	public function indexAction()
	{
		$this->view->headTitle(Base_Util::setTitle('L_TIT_REL_REGISTRO_LOG'));

		$arrProfissional	= $this->objProfissional->getProfissional(true);
		$arrProfissional[0]	= Base_Util::getTranslator('L_VIEW_TODOS');
		$this->view->arrProfissionalLog = $arrProfissional; 
		
		//recupera as tabelas do sistema
		$db 		= Zend_Registry::get('db');
		$arrTabelas = $db->listTables(K_SCHEMA);
		
		$arr[0] = Base_Util::getTranslator('L_VIEW_TODAS');
		if( count($arrTabelas) > 0 ){
			foreach($arrTabelas as $tabela){
				$arr[$tabela] = strtoupper($tabela);
			}
		}
		$this->view->arrTabelasEsquema = $arr;
		
	}

	public function registroLogAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$formData = $this->_request->getPost();
		$arrDados['cd_profissional'	] = (( (int) $formData['cmb_profissional_log']) != 0 ) ? $formData['cmb_profissional_log'] : null;
		$arrDados['dt_inicial'		] = Base_Util::converterDate($formData['dt_inicio_log'], 'DD/MM/YYYY', 'YYYY-MM-DD');
		$arrDados['dt_final'		] = Base_Util::converterDate($formData['dt_fim_log'   ], 'DD/MM/YYYY', 'YYYY-MM-DD');
		$arrDados['tx_tabela'		] = ($formData['cmb_tabela_log'] != "0" ) ? $formData['cmb_tabela_log'] : null;
		$arrDados['tx_ip'			] = ( !empty($formData['ip_log']) ) 	? $formData['ip_log'] 		  : null;
		
		$this->geraRelatorio( $this->objLog->getRegistroLog($arrDados));

	}

	private function geraRelatorio( array $arrDadosLog )
	{
		//criando o objeto
		$objPdf = new Base_Tcpdf_Pdf();

        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_REGISTRO_LOG'),
                             Base_Util::getTranslator('L_VIEW_LOG'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );

        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_REGISTRO_LOG'), $arrKeywords);
		$objPdf->SetDisplayMode("real");
		// set font
		$objPdf->AddPage();
		
		if(count($arrDadosLog) > 0){

			$arrLog = $this->preparaDadosLog($arrDadosLog);

			$objPdf->SetFillColor(240, 248, 255);
			
			$fill			= true;
			$w1				= array(20,160);
			$w2				= array(20,30);
			$w3				= array(45,135);
			$w4				= array(10,25,45,105);
			$fontType		= 'helvetica';
			$fontSize		= 8;
			$height			= 6;

			//percore os profissionais
			foreach( $arrLog as $txProf=>$dtRegistro){
				//1ª linha (PROFISSIONAL)
				$objPdf->SetFont($fontType, 'B', $fontSize);
				$objPdf->Cell(20, $height, Base_Util::getTranslator('L_VIEW_PROFISSIONAL').":",'','','R',$fill);
				$objPdf->SetFont($fontType, '', $fontSize);
				$objPdf->Cell(160, $height, $txProf,'',1,'L',$fill);

				//percorre as datas
				foreach($dtRegistro as $data=>$arrDadosLog){
					$objPdf->SetFont($fontType, 'B', $fontSize);
					$objPdf->Cell($w1[0], $height, Base_Util::getTranslator('L_VIEW_DATA').":",'','','R');
					$objPdf->SetFont($fontType, '', $fontSize);
					$objPdf->Cell($w1[1], $height, $data,'',1,'L');

					foreach($arrDadosLog as $dadosLog){
						//hora
						$objPdf->SetFont($fontType, 'B', $fontSize);
						$objPdf->Cell($w2[1], $height, Base_Util::getTranslator('L_VIEW_HORA').":",'','','R');
						$objPdf->SetFont($fontType, '', $fontSize);
						$objPdf->Cell($w2[0], $height, substr($dadosLog['dt_ocorrencia'], 11),'',1,'L');
						
						//controller
						$objPdf->SetFont($fontType, 'B', $fontSize);
						$objPdf->Cell($w3[0], $height, "Controller:",'','','R');
						$objPdf->SetFont($fontType, '', $fontSize);
						$objPdf->Cell($w3[1], $height, $dadosLog['tx_controller'],'',1,'L');

						//tabela
						$objPdf->SetFont($fontType, 'B', $fontSize);
						$objPdf->Cell($w3[0], $height, Base_Util::getTranslator('L_VIEW_TABELA').":",'','','R');
						$objPdf->SetFont($fontType, '', $fontSize);
						$objPdf->Cell($w3[1], $height, $dadosLog['tx_tabela'],'',1,'L');

						//IP
						$objPdf->SetFont($fontType, 'B', $fontSize);
						$objPdf->Cell($w4[2], $height, Base_Util::getTranslator('L_VIEW_IP').":",'','','R');
						$objPdf->SetFont($fontType, '', $fontSize);
						$objPdf->Cell($w4[1], $height, $dadosLog['tx_ip'],'','','L');

						//HOST
						$objPdf->SetFont($fontType, 'B', $fontSize);
						$objPdf->Cell($w4[0], $height, Base_Util::getTranslator('L_VIEW_HOST').":",'','','R');
						$objPdf->SetFont($fontType, '', $fontSize);
						$objPdf->Cell($w4[3], $height, $dadosLog['tx_host'],'',1,'L');

						//mensagem
						$objPdf->SetFont($fontType, 'B', $fontSize);
						$objPdf->Cell($w3[0], $height, Base_Util::getTranslator('L_VIEW_MENSAGEM').":",'','','R');
						$objPdf->SetFont($fontType, '', $fontSize);
						$objPdf->MultiCell($w3[1], $height, $dadosLog['tx_msg_log'].PHP_EOL,'','J','',1);
					}
				}
			}
		}else{
			$html = $objPdf->semRegistroParaConsulta();
			$objPdf->writeHTML($html,true, 0, true, 0);	
		}

		//Close and output PDF document
		$objPdf->Output('relatorio_registro_log.pdf', 'I');
	}

	private function preparaDadosLog( $arrDadosLog )
	{
		$arrRetorno = array();

		$x = 0;
		$cd_profissional  = '';
		$tx_profissional = '';
		$dt_ocorrencia	  = '';

		foreach( $arrDadosLog as $regLog){

			if($cd_profissional != $regLog['cd_profissional']){

				$cd_profissional = $regLog['cd_profissional'];
				$tx_profissional = $regLog['tx_profissional'];
			}

			$data = Base_Util::converterDate(substr($regLog['dt_ocorrencia'], 0, 10), 'YYYY-MM-DD', 'DD/MM/YYYY');
			if($dt_ocorrencia != $data){
				$dt_ocorrencia = $data;
			}
			$arrRetorno[$tx_profissional][$data][] = $regLog;
		}
		
		return $arrRetorno;
	}

}
