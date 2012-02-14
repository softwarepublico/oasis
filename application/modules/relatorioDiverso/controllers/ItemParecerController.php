<?php
/**
 * @Copyright Copyright 2011 Hudson Carrano Filho, Brasil.
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

class RelatorioDiverso_ItemParecerController extends Base_Controller_Action
{
    private $_objPdf;
    private $tipo_relatorio;
    private $arrItemParecer;
    
    public function init()
    {
        parent::init();
        $this->_objRelItemParecer = new RelatorioDiversoItemParecer();
        $this->_objPdf            = new Base_Tcpdf_Pdf();
        
    }

    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_ITEM_PARECER_TECNICO'));
    }

    public function itemParecerAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
    
        $tipo                 = $this->_request->getPost('tipo_item');
        $this->arrItemParecer = $this->_objRelItemParecer->getDadosItemParecer($tipo);

		$this->_geraRelatorio($tipo);
	}
    
    
    
//    public function generateAction()
    public function _geraRelatorio($tipo)
    {

        $texto_tipo = '';
        switch ($tipo){
            
            case 'P': $texto_tipo = ' - '.Base_Util::getTranslator('L_VIEW_PROPOSTA');
                      break;
            case 'C': $texto_tipo = ' - '.Base_Util::getTranslator('L_VIEW_PARCELA');
                      break;     
        }
        
        
        //verifica o tipo do relatório para montar o PDF
        $orientation = ($this->tipo_relatorio == "C")?"L":"P";
        //criando o objeto
        $this->_objPdf = new Base_Tcpdf_Pdf($orientation);

        //Inicializa Relatórios
        $this->_objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_ITEM_PARECER_TECNICO').$texto_tipo);
        $this->_objPdf->SetDisplayMode("real");

        // add a page
        $this->_objPdf->AddPage();

        //Dados para o cabeçalho do Relatório
        $arrHeader = array(Base_Util::getTranslator('L_VIEW_ITEM_PARECER'),
                           Base_Util::getTranslator('L_VIEW_DESCRICAO'),
                           Base_Util::getTranslator('L_VIEW_PROPOSTA'),
                           Base_Util::getTranslator('L_VIEW_PARCELA'),
                          );

        //Tamanho do dados no cabeçalho do relatório
        $arrHeaderTam = array(60,80,20,20);
        $this->_montaRelatorio($arrHeader,$arrHeaderTam,$this->arrItemParecer);


        //Close and output PDF document
        $this->_objPdf->Output('relatorio_projeto_profissional.pdf', 'I');
    }
    
    private function _montaRelatorio($arrHeader, $arrHeaderTam,$arrItemParecer)
    {
            // Colors, line width and bold font
        $this->_objPdf->SetDrawColor(50);
        $this->_objPdf->SetFillColor(240,240,240);
        $this->_objPdf->SetTextColor(0);
        $this->_objPdf->SetLineWidth(0.3);
        $this->_objPdf->SetFont('', 'B',9);

        // Header
        $fill = 0;
        $strTable = "";
        $strTable .= '<table cellpadding="3" cellspacing="0" bordercolor="#CCCCCC" border="1">';
        $strTable .= "<tr bgcolor= '#ff0000' >";
        $strTable .= '  <td width="130px" style="text-align:center;background-color:lightgrey;"><b>'.$arrHeader[0].'</b></td>';
        $strTable .= '  <td width="230px" style="text-align:center;background-color:lightgrey;"><b>'.$arrHeader[1].'</b></td>';
        $strTable .= '  <td width="75px" style="text-align:center;background-color:lightgrey;"><b>'.$arrHeader[2].'</b></td>';
        $strTable .= '  <td width="75px" style="text-align:center;background-color:lightgrey;"><b>'.$arrHeader[3].'</b></td>';
        $strTable .= '</tr>';

        // Data
       
       foreach($arrItemParecer as $row){
            
            $strTable .= "<tr bgcolor= '#ff0000' >";
            $strTable .= '  <td width="130px" style="text-align:left;">'.trim($row['tx_item_parecer_tecnico']).'</td>';
            $strTable .= '  <td width="230px" style="text-align:left;">'.trim($row['tx_descricao']).'</td>';
            $strTable .= '  <td width="75px" style="text-align:center;">'.trim($row['st_proposta']).'</td>';
            $strTable .= '  <td width="75px" style="text-align:center;">'.trim($row['st_parcela']).'</td>';
            $strTable .= '</tr>';

            
            $fill=!$fill;
            
            
            
            
        }
        $strTable .= '</table>';
        $strTable .= '<br>';
        
        $this->_objPdf->writeHTML($strTable,true,0, true, 0);
        
        $this->_objPdf->Cell(180, 6, "", 'T', 0, 'C');
    }


   
}