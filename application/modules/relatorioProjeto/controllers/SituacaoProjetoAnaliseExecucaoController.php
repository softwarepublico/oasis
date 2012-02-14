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

class RelatorioProjeto_SituacaoProjetoAnaliseExecucaoController extends Base_Controller_Action
{

	private $objProposta;
	private $objRelProjeto;
	private $objProjeto;
	private $objUtil;
    private $periodoRelatorio;
    private $dadosProjeto;
    private $arrSituacao;
    private $arrAnalise;
    private $objContrato;

	public function init()
	{
		parent::init();

		$this->objProposta	 = new Proposta($this->_request->getControllerName());
		$this->objRelProjeto = new RelatorioProjetoProjeto();
		$this->objProjeto    = new Projeto($this->_request->getControllerName());
		$this->objUtil       = new Base_Controller_Action_Helper_Util();
		$this->objContrato	 = new Contrato($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_SITUACAO_PROJETO_ANALISE_EXECUCAO_PROJETO'));

		$cd_contrato = null;
		$comStatus	 = true;
		
		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		
		$this->view->arrContrato = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
	}
	
	public function pesquisaProjetoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$cd_contrato = (int) $this->_request->getParam("cd_contrato", 0);
		$arrProjetos = $this->objProposta->getProjetosExecucaoSemEncerramentoProposta( true, $cd_contrato );
		
		$options = '';
		
		foreach( $arrProjetos as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}
		
		echo $options;
	}

	public function situacaoProjetoAnaliseExecucaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();

		$cd_projeto	= $post['cd_projeto'];
		$mes		= $post['mes'];
		$ano		= $post['ano'];

        $this->periodoRelatorio = $this->objUtil->getMesRes($mes)."/".$ano;
        $this->dadosProjeto     = $this->objProjeto->getDadosProjeto( $cd_projeto );

        //procura a situação no mes/ano passados como parâmetro
        $this->arrSituacao = $this->objRelProjeto->getSituacaoAtualProjeto( $cd_projeto, $mes, $ano );

        //caso não encontra na busca anterior, procura a ultima situação cadastrada para o projeto
        if(count($this->arrSituacao) == 0 ){
            $this->arrSituacao = $this->objRelProjeto->getSituacaoUltimaProjeto( $cd_projeto );
        }

        //adiciona a descrição do mes
        foreach($this->arrSituacao as $key=>$value){
            $this->arrSituacao[$key]['periodo_situacao'] = $this->objUtil->getMesRes($value['ni_mes_situacao_projeto'])."/".$value['ni_ano_situacao_projeto'];
        }

        $this->arrAnalise = $this->objRelProjeto->getDadosAnaliseExecucaoProjeto( $cd_projeto, $mes, $ano );
		
		$this->geraRelatorio();
	}
    
	private function geraRelatorio()
	{
		//criando o objeto
		$objPdf = new Base_Tcpdf_Pdf();

		$objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_SITUACAO_PROJETO_ANALISE_EXECUCAO_PROJETO'), K_CREATOR_SYSTEM.", ".Base_Util::getTranslator('L_VIEW_ANALISE').", ".
                                                                                                                                        Base_Util::getTranslator('L_VIEW_EXECUCAO').", ".
                                                                                                                                        Base_Util::getTranslator('L_VIEW_PROJETO').", ".
                                                                                                                                        Base_Util::getTranslator('L_VIEW_EXECUCAO_PROJETO').", ".
                                                                                                                                        Base_Util::getTranslator('L_VIEW_SITUACAO_PROJETO').", ".
                                                                                                                                        Base_Util::getTranslator('L_TIT_REL_SITUACAO_PROJETO_ANALISE_EXECUCAO_PROJETO'));

		$objPdf->SetDisplayMode("real");
		$objPdf->AddPage();
		
		$w = array(180,15,165,25);

        //cabeçalho
        $objPdf->SetFont('helvetica', 'B', 8);
        $objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_PROJETO').":",'',0,'L',0);
        $objPdf->SetFont('helvetica', '', 8);
        $objPdf->Cell($w[2], 6, $this->dadosProjeto[0]['tx_sigla_projeto'],'',1,'L',0);

        $objPdf->SetFont('helvetica', 'B', 8);
        $objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_PERIODO').":",'',0,'L',0);
        $objPdf->SetFont('helvetica', '', 8);
        $objPdf->Cell($w[2], 6, $this->periodoRelatorio,'',1,'L',0);
        $objPdf->Ln(5);

        //situação
        if( count($this->arrSituacao) > 0 ){
            $objPdf->SetFont('helvetica', 'B', 8);
            $objPdf->Cell($w[1], 2, Base_Util::getTranslator('L_VIEW_SITUACAO')." ({$this->arrSituacao[0]['periodo_situacao']}):",'',1);
            $objPdf->SetFont('helvetica', '', 8);
            $objPdf->MultiCell($w[0], 6, $this->arrSituacao[0]['tx_situacao_projeto']."\n", 0, "J", 0, 1);
        }else{
            $objPdf->SetFont('helvetica', 'B', 8);
            $objPdf->Cell($w[1], 2, Base_Util::getTranslator('L_VIEW_SITUACAO').":",'',1);
            $objPdf->SetFont('helvetica', '', 8);
            $objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_MSG_ALERT_SEM_REGISTRO_SITUACAO'), "", 1);
        }

        //Analise
        $objPdf->Ln(5);
        $objPdf->SetFont('helvetica', 'B', 8);
        $objPdf->Cell($w[0], 2, Base_Util::getTranslator('L_VIEW_ANALISE_SITUACAO').":",'',1);

        if( count($this->arrAnalise) > 0 ){
            $objPdf->Ln(2);

            $strTable  = '<table cellpadding="3" cellspacing="0" bordercolor="#CCCCCC" border="1">';
            $strTable .= '<tr bgcolor="'.Base_Tcpdf_Pdf::FILL_TITLE_TABLE.'">';
            $strTable .= '  <td width="110px" style="text-align:center;"><b>'.Base_Util::getTranslator('L_VIEW_DATA').'</b></td>';
            $strTable .= '  <td width="400px" style="text-align:center;"><b>'.Base_Util::getTranslator('L_VIEW_ANALISE').'</b></td>';
            $strTable .= '</tr>';

            $fill = false;
            foreach( $this->arrAnalise as $value ) {
                if(!$fill){
                    $strTable .= '<tr>';
					$strTable .= '  <td width="110px" style="text-align:center;" >'.date('d/m/Y H:i:s', strtotime($value['dt_analise_execucao_projeto'])).'</td>';
                    $strTable .= '  <td width="400px" style="text-align:justify;">'.$value['tx_resultado_analise_execucao'].'</td>';
                    $strTable .= '</tr>';
                }else{
                    $strTable .= '<tr bgcolor="'.Base_Tcpdf_Pdf::FILL_ROW_TABLE.'">';
                    $strTable .= '  <td width="110px" style="ext-align:center;" >'.date('d/m/Y H:i:s', strtotime($value['dt_analise_execucao_projeto'])).'</td>';
                    $strTable .= '  <td width="400px" style="text-align:justify;">'.$value['tx_resultado_analise_execucao'].'</td>';
                    $strTable .= '</tr>';
                }
                $fill = !$fill;
            }
            $strTable .= '</table>';

            $objPdf->writeHTML($strTable,true, 0, true, 0);
        }else{
            $objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
        }

		//Close and output PDF document
		$objPdf->Output('relatorio_projeto_situacao_projeto_analise_execucao.pdf', 'I');
	}
}
