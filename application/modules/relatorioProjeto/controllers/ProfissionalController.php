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

class RelatorioProjeto_ProfissionalController extends Base_Controller_Action
{
    private $_objPdf;
    private $tipo_relatorio;
    private $tx_objeto;
    private $arrProfissional;
    
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_PROFISSIONAL'));

        $objContrato = new ObjetoContrato($this->_request->getControllerName());
        $this->view->arrComboObjeto = $objContrato->getObjetoContratoAtivo(null,true,false,true);
    }

    public function generateAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        
        $objRelProfissional = new RelatorioProjetoProfissional();

        $this->tipo_relatorio  = $this->_request->getParam('tipo_relatorio');
        $cd_objeto             = $this->_request->getParam('cd_objeto');
        $this->tx_objeto       = $this->_request->getParam('tx_objeto');
        $this->arrProfissional = $objRelProfissional->relDadosProfissional($cd_objeto);

        //verifica o tipo do relatório para montar o PDF
        $orientation = ($this->tipo_relatorio == "C")?"L":"P";
        //criando o objeto
        $this->_objPdf = new Base_Tcpdf_Pdf($orientation);

        //Inicializa Relatórios
        $this->_objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_PROFISSIONAL'), K_CREATOR_SYSTEM.', '.
                                                                                            Base_Util::getTranslator('L_TIT_REL_PROFISSIONAL').', '.
                                                                                            Base_Util::getTranslator('L_VIEW_PROFISSIONAL'));
        $this->_objPdf->SetDisplayMode("real");

        // add a page
        $this->_objPdf->AddPage();
        $this->_objPdf->SetFont('', 'B',9);
        $this->_objPdf->Cell(12,6, Base_Util::getTranslator('L_VIEW_OBJETO').":", '', 0, 'L');
        $this->_objPdf->SetFont('', '',9);
        $this->_objPdf->Cell(170,6, $this->tx_objeto, '', 0, 'L');
        $this->_objPdf->ln(7);

        if($this->tipo_relatorio == "C"){
            //Dados para o cabeçalho do Relatório
            $arrHeader = array(Base_Util::getTranslator('L_VIEW_NOME'),
                               Base_Util::getTranslator('L_VIEW_CARGO'),
                               Base_Util::getTranslator('L_VIEW_FONE_RESIDENCIA'),
                               Base_Util::getTranslator('L_VIEW_FONE_CELULAR'),
                               Base_Util::getTranslator('L_VIEW_RAMAL'),
                               Base_Util::getTranslator('L_VIEW_EMAIL_PESSOAL'),
                               Base_Util::getTranslator('L_VIEW_NASCIMENTO')
                              );
            //Tamanho do dados no cabeçalho do relatório
            $arrHeaderTam = array(80,45,25,25,15,57,20);
            $this->_montaRelatorioCompleto($arrHeader,$arrHeaderTam,$this->arrProfissional);
        } else {
            //Dados para o cabeçalho do Relatório
            $arrHeader = array(Base_Util::getTranslator('L_VIEW_NOME'),
                               Base_Util::getTranslator('L_VIEW_RAMAL'),
                               Base_Util::getTranslator('L_VIEW_FONE_RESIDENCIA'),
                               Base_Util::getTranslator('L_VIEW_FONE_CELULAR'),
                               Base_Util::getTranslator('L_VIEW_NASCIMENTO')
                              );

            //Tamanho do dados no cabeçalho do relatório
            $arrHeaderTam = array(60,30,30,30,30);
            $this->_montaRelatorioResumido($arrHeader,$arrHeaderTam,$this->arrProfissional);
        }

        //Close and output PDF document
        $this->_objPdf->Output('relatorio_projeto_profissional.pdf', 'I');
    }
    
    private function _montaRelatorioResumido($arrHeader, $arrHeaderTam,$arrProfissional)
    {
            // Colors, line width and bold font
        $this->_objPdf->SetDrawColor(50);
        $this->_objPdf->SetFillColor(240,240,240);
        $this->_objPdf->SetTextColor(0);
        $this->_objPdf->SetLineWidth(0.3);
        $this->_objPdf->SetFont('', 'B',9);
        // Header
        $w = $arrHeaderTam;
        for($i = 0; $i < count($arrHeader); $i++)
        $this->_objPdf->Cell($w[$i], 7, $arrHeader[$i], 1, 0, 'C', 1);
        $this->_objPdf->Ln();
        // Color and font restoration
        $this->_objPdf->SetFillColor(240, 248, 255);
        $this->_objPdf->SetTextColor(0);
        $this->_objPdf->SetFont('','',8);
        // Data
        $fill = 0;

        foreach($arrProfissional as $row){
            $tx_profissional = "-";
            if(trim($row['tx_nome_conhecido'])){
                $strTam = "";
                if(strlen($row['tx_nome_conhecido']) >= 60){
                    $strTam = "...";
                    $tx_profissional = substr($row['tx_nome_conhecido'],0,100).$strTam;
                } else {
                    $tx_profissional = $row['tx_nome_conhecido']; 
                }
            }

            $this->_objPdf->Cell($w[0],6, $tx_profissional, 'LR', 0, 'L', $fill);
            $this->_objPdf->Cell($w[1],6, (trim($row['tx_ramal_profissional']))?$row['tx_ramal_profissional']:"-", 'LR', 0, 'C', $fill);
            $this->_objPdf->Cell($w[2],6, (trim($row['tx_telefone_residencial']))?$row['tx_telefone_residencial']:"-", 'LR', 0, 'C', $fill);
            $this->_objPdf->Cell($w[3],6, (trim($row['tx_celular_profissional']))?$row['tx_celular_profissional']:"-", 'LR', 0, 'C', $fill);
            $this->_objPdf->Cell($w[4],6, $this->_conveteDataNascimento($row['dt_nascimento']), 'LR', 0, 'C', $fill);
            $this->_objPdf->Ln();
            $fill=!$fill;
        }
        $this->_objPdf->Cell(180, 6, "", 'T', 0, 'C');
    }

    private function _montaRelatorioCompleto($arrHeader, $arrHeaderTam,$arrProfissional)
    {
        // Colors, line width and bold font
        $this->_objPdf->SetDrawColor(50);
        $this->_objPdf->SetFillColor(240,240,240);
        $this->_objPdf->SetTextColor(0);
        $this->_objPdf->SetLineWidth(0.3);
        $this->_objPdf->SetFont('', 'B',9);
        // Header
        $w = $arrHeaderTam;
        for($i = 0; $i < count($arrHeader); $i++)
        $this->_objPdf->Cell($w[$i], 7, $arrHeader[$i], 1, 0, 'C', 1);
        $this->_objPdf->Ln();
        // Color and font restoration
        $this->_objPdf->SetFillColor(240, 248, 255);
        $this->_objPdf->SetTextColor(0);
        $this->_objPdf->SetFont('','',8);
        // Data
        $fill = 0;

        foreach($arrProfissional as $row){
            $tx_profissional = "-";
            if(trim($row['tx_profissional'])){
                $strTam = "";
                if(strlen($row['tx_profissional']) >= 60){
                    $strTam = "...";
                    $tx_profissional = substr($row['tx_profissional'],0,100).$strTam;
                } else {
                    $tx_profissional = $row['tx_profissional'];
                }
            }
            if(trim($row['tx_email_pessoal'])){
                $tx_email_pessoal = $row['tx_email_pessoal'];
                $align = "L";
            } else {
                $tx_email_pessoal = "-";
                $align = "C";
            }

            $this->_objPdf->Cell($w[0],6, $tx_profissional, 'LR', 0, 'L', $fill);
            $this->_objPdf->Cell($w[1],6, (trim($row['tx_perfil_profissional']))?$row['tx_perfil_profissional']:"-", 'LR', 0, 'C', $fill);
            $this->_objPdf->Cell($w[2],6, (trim($row['tx_telefone_residencial']))?$row['tx_telefone_residencial']:"-", 'LR', 0, 'C', $fill);
            $this->_objPdf->Cell($w[3],6, (trim($row['tx_celular_profissional']))?$row['tx_celular_profissional']:"-", 'LR', 0, 'C', $fill);
            $this->_objPdf->Cell($w[4],6, (trim($row['tx_ramal_profissional']))?$row['tx_ramal_profissional']:"-", 'LR', 0, 'C', $fill);
            $this->_objPdf->Cell($w[5],6, $tx_email_pessoal, 'LR', 0, $align, $fill);
            $this->_objPdf->Cell($w[6],6, $this->_conveteDataNascimento($row['dt_nascimento']), 'LR', 0, 'C', $fill);
            $this->_objPdf->Ln();
            $fill=!$fill;
        }
        $this->_objPdf->Cell(265, 6, "", 'T', 0, 'C');
    }

    private function _conveteDataNascimento($data)
    {
        $dataFormat = '-';

        if(trim($data)){
            $arrData = explode("-", $data);
            $dataFormat = $arrData[2].'/'.$arrData[1];
        }
        return $dataFormat;
    }
}