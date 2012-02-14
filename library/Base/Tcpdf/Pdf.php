<?php
include_once ('TCPDF/tcpdf.php');

class Base_Tcpdf_Pdf extends TCPDF
{
	/** 
     * Atributo 
     * Texto com os parametros do relatório
     *
     * @var STRING
     */
    public $parametrosRelatorio;
    private $headerLogo;
    private $whiteLogo;
	//utilização em html
	const FILL_TITLE_TABLE = "#F0F0F0";
	const FILL_ROW_TABLE = "#F0F8FF";
	
	//rgb utilização em php linhas (azul)
	const R_FILL = 240;
	const G_FILL = 248;
	const B_FILL = 255;

	//o cinza utilizado nos cabeçalhos é rgb(240,240,240)
	

	public function __construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false) {
		$this->headerLogo = ($orientation == "P") ? K_HEADER_LOGO_RETRATO : K_HEADER_LOGO_PAISAGEM;
		$this->whiteLogo  = ($orientation == "P") ? 180 : 267;
		parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache);
	}
	
	public function iniciaRelatorio($nomeRelatorio = "", $chavesKeywords = "")
	{
		// set document information
		$this->SetCreator(K_CREATOR_SYSTEM);
		$this->SetAuthor(K_CREATOR_SYSTEM);
		$this->SetTitle(K_CREATOR_SYSTEM.' - '.K_TITLE_SYSTEM);
		$this->SetSubject($nomeRelatorio);

        if($chavesKeywords != "" && is_array($chavesKeywords)){
            $chavesKeywords = implode($chavesKeywords, ', ');
        }
		$this->SetKeywords($chavesKeywords);
		
		// set default header data
		$this->SetHeaderData($this->headerLogo,$this->whiteLogo, K_HEADER_COORDENACAO, $nomeRelatorio);
		// set header and footer fonts
		$this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		
		//set margins
		$this->SetMargins(PDF_MARGIN_LEFT, K_HEADER_TOP, PDF_MARGIN_RIGHT);
		$this->SetHeaderMargin(PDF_MARGIN_HEADER);
		$this->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		//set auto page breaks
		$this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		//set image scale factor
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	}
	
	public function Header() 
	{
		$ormargins = $this->getOriginalMargins();
		$headerfont = $this->getHeaderFont();
		$headerdata = $this->getHeaderData();
		
		if (($headerdata['logo']) AND ($headerdata['logo'] != K_BLANK_IMAGE)) {
			$this->Image(K_PATH_IMAGEM.$headerdata['logo'], $this->GetX(), $this->getHeaderMargin(), $headerdata['logo_width'], 10);
			$imgy = $this->getImageRBY();
		} else {
		  $imgy = $this->GetY();
		}
		$cell_height = round(($this->getCellHeightRatio() * $headerfont[2]) / $this->getScaleFactor(), 2);
		// set starting margin for text data cell
		if ($this->getRTL()) {
		  $header_y = $imgy * 1.1;
		} else {
		  $header_y = $imgy * 1.1;
		}
		$this->SetTextColor(0, 0, 0);
		// header title
		$this->SetFont($headerfont[0], '', 8);
		$this->SetY($header_y);
		$this->Cell(0, $cell_height, $headerdata['title'], 0, 1, 'C', 0, '', 0);
		// header title
		$this->SetFont($headerfont[0], '', 8);
		$this->SetY($header_y);
		$this->Cell(0, $cell_height, $headerdata['title'], 0, 1, 'C', 0, '', 0);
		// header title
		$this->SetFont($headerfont[0], '', 8);
		$this->SetY($header_y * 1.2);
		$this->Cell(0, $cell_height, '', 0, 1, 'C', 0, '', 0);
		// header string
		$this->SetFont($headerfont[0], 'B', $headerfont[2]+2);
		$this->SetY($header_y * 1.4);
		$this->MultiCell(0, $cell_height, $headerdata['string'], 0, 'C', 0, 1, '', '', true, 0, false);
		
		$this->SetY($header_y * 1.5);
		$this->SetFont($headerfont[0], '', 8);
		$this->MultiCell(0, $cell_height, date('d/m/Y H:i'), 0, 'R', 0, 1, '', '', true, 0, false);
		            
		$this->SetX(($header_y * 1.4 / $this->getScaleFactor()) + max($imgy, $this->GetY()));
		if ($this->getRTL()) {
		$this->SetX($ormargins['right']);
		
		} else {
		$this->SetX($ormargins['left']);
		}
		$this->Cell(0, 0, '', 'T', 0, 'C');
	}
	
   /**
         * This method is used to render the page footer. 
         * It is automatically called by AddPage() and could be overwritten in your own inherited class.
         */
    public function Footer() {   
            $cur_y = $this->GetY();
            $ormargins = $this->getOriginalMargins();
            $this->SetTextColor(0, 0, 0);           
            //set style for cell border
            $line_width = 0.85 / $this->getScaleFactor();
            $this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
            //print document barcode
            $barcode = $this->getBarcode();
            if (!empty($barcode)) {
                $this->Ln();
                $barcode_width = round(($this->getPageWidth() - $ormargins['left'] - $ormargins['right'])/3);
                $this->write1DBarcode($barcode, 'C128B', $this->GetX(), $cur_y + $line_width, $barcode_width, (($this->getFooterMargin() / 3) - $line_width), 0.3, '', ''); 
            }
            if (empty($this->pagegroups)) {
                $pagenumtxt = $this->l['w_page'].' '.$this->getAliasNumPage().' / '.$this->getAliasNbPages();
            } else {
                $pagenumtxt = $this->l['w_page'].' '.$this->getPageNumGroupAlias().' / '.$this->getPageGroupAlias();
            }       
            $this->SetY($cur_y);
            //Print page number
            if ($this->getRTL()) {
                $this->SetX($ormargins['right']);
                $this->Cell(0, 0, Base_Util::getTranslator('L_VIEW_FONTE'). ": ".K_CREATOR_SYSTEM.' - '.K_TITLE_SYSTEM, 'T', 0, 'R');
                $this->Cell(0, 0, $pagenumtxt, 'T', 0, 'L');
            } else {
                $this->SetX($ormargins['left']);
                $this->Cell(0, 0, Base_Util::getTranslator('L_VIEW_FONTE').": ".K_CREATOR_SYSTEM.' - '.K_TITLE_SYSTEM, 'T', 0, 'L');
                $this->Cell(0, 0, $pagenumtxt, 'T', 0, 'R');
            }
        }
            
    /**
     * Método que gera o script que vai exibir 
     * se não retornar registro no relatório...
     *
     * @return STRING $script
     */
    public function relatorioSemDados()
    {
        $script  = '<h1>Não foi possível retornar dados contendo todos os termos de <br />';
        $script .= 'sua consulta - <i>'. utf8_decode( $this->getParametrosRelatorio() ) .'</i><br />';
        $script .= 'Selecione outros valores para gerar o relatório.</h1>';
        $script .= '<script language="JavaScript">';
        $script .= '$("document").ready( function(){';
        $script .= '$(".breadCrumbs").addClass("notVisible");';
        $script .= '$("#cabeca ul, #cabeca p").addClass("notVisible");';
        $script .= 'alertMsg("Relatório sem registro.");';
        $script .= 'window.close();';
        $script .= '});';
        $script .= '</script>';
        return $script;
    }
    
    /**
     * 
     * utilização no retorno:
     * 
     * $html = $objPdf->semRegistroParaConsulta();
     * $objPdf->writeHTML($html,true, 0, true, 0);
     * $objPdf->Output('relatorio_etapa_atividade.pdf', 'I');
	 *
     * @@param <string> tipo da pagina: P (Portrait) (default) or L (Landscape)
     * @return string
     */
    public function semRegistroParaConsulta($tipoPagina = 'P')
    {
		$width = ($tipoPagina == 'P') ? 508 : 756;

		$html  = '<table width="100%" cellpadding="5" cellspacing="0" bordercolor="#CCCCCC" border="1">';
		$html .= '<tr>';
		$html .= ' <td bgcolor="'.self::FILL_TITLE_TABLE.'" width="'.$width.'px" style="text-align:center;"><b>'.Base_Util::getTranslator('L_MSG_ALERT_SEM_REGISTRO_CONSULTA').'</b></td>';
		$html .= '</tr>';
		$html .= '</table>';
		
		return $html;
    }
    
    /**
     * Getter do atributo $parametrosRelatorio
     *
     * @return $this->parametrosRelatorio
     */
    public function getParametrosRelatorio()
    {
        return $this->parametrosRelatorio;
    }
    /**
     * Setter do atributo $titulo
     *
     * @param STRING $parametrosRelatorio
     * @return VOID
     */
    public function setParametrosRelatorio( $valor )
    {
        $this->parametrosRelatorio = $valor;
    }
    
    public function writeQRCcode($code, $level='L', $x='', $y='', $w=40, $h=40, $style='', $align='')
    {
        if ($this->empty_string($code)) {
            return;
        }
        require_once('TCPDF/qrcode.php');
        // save current graphic settings
        $gvars = $this->getGraphicVars();
        // create new barcode object
        $barcodeobj = new TCPDFQRcode($code, $level);
        $arrcode = $barcodeobj->getBarcodeArray();
        if ($arrcode === false) {
            $this->Error('Error in QRCode string');
        }
        // set default values
        if (!isset($style['padding'])) {
            $style['padding'] = 0;
        }
        if (!isset($style['fgcolor'])) {
            $style['fgcolor'] = array(0,0,0); // default black
        }
        if (!isset($style['bgcolor'])) {
            $style['bgcolor'] = false; // default transparent
        }
        if (!isset($style['border'])) {
            $style['border'] = false;
        }
        // set foreground color
        $this->SetDrawColorArray($style['fgcolor']);
        if ($this->empty_string($x)) {
            $x = $this->GetX();
        }
        if ($this->rtl) {
            $x = $this->w - $x;
        }
        if ($this->empty_string($y)) {
            $y = $this->GetY();
        }
        if ($this->empty_string($w) OR ($w <= 0)) {
            if ($this->rtl) {
                $w = $x - $this->lMargin;
            } else {
                $w = $this->w - $this->rMargin - $x;
            }
        }
        if ($this->empty_string($h) OR ($h <= 0)) {
            // 2d barcodes are square by default
            $h = $w;
        }
        if ($this->checkPageBreak($h)) {
            $y = $this->y;
        }
        // calculate barcode size (excluding padding)
        $bw = $w - (2 * $style['padding']);
        $bh = $h - (2 * $style['padding']);
        // calculate starting coordinates
        if ($this->rtl) {
            $xpos = $x - $w;
        } else {
            $xpos = $x;
        }
        $xpos += $style['padding'];
        $ypos = $y + $style['padding'];
        // barcode is always printed in LTR direction
        $tempRTL = $this->rtl;
        $this->rtl = false;
        // print background color
        if ($style['bgcolor']) {
            $this->Rect($x, $y, $w, $h, $style['border'] ? 'DF' : 'F', '', $style['bgcolor']);
        } elseif ($style['border']) {
            $this->Rect($x, $y, $w, $h, 'D');
        }

        // print barcode cells
        if ($arrcode !== false) {
            $rows = $arrcode['num_rows'];
            $cols = $arrcode['num_cols'];
            // calculate dimension of single barcode cell
            $cw = $bw / $cols;
            $ch = $bh / $rows;
            // for each row
            for ($r = 0; $r < $rows; ++$r) {
                $xr = $xpos;
                // for each column
                for ($c = 0; $c < $cols; ++$c) {
                    if ($arrcode['bcode'][$r][$c] == 1) {
                        // draw a single barcode cell
                        $this->Rect($xr, $ypos, $cw, $ch, 'F', array(), $style['fgcolor']);
                    }
                    $xr += $cw;
                }
                $ypos += $ch;
            }
        }
        // restore original direction
        $this->rtl = $tempRTL;
        // restore previous settings
        $this->setGraphicVars($gvars);
        // set bottomcoordinates
        $this->img_rb_y = $y + $h;
        if ($this->rtl) {
            // set left side coordinate
            $this->img_rb_x = ($this->w - $x - $w);
        } else {
            // set right side coordinate
            $this->img_rb_x = $x + $w;
        }
        // set pointer to align the successive text/objects
        switch($align) {
            case 'T':{
                $this->y = $y;
                $this->x = $this->img_rb_x;
                break;
            }
            case 'M':{
                $this->y = $y + round($h/2);
                $this->x = $this->img_rb_x;
                break;
            }
            case 'B':{
                $this->y = $this->img_rb_y;
                $this->x = $this->img_rb_x;
                break;
            }
            case 'N':{
                $this->SetY($this->img_rb_y);
                break;
            }
            default:{
                break;
            }
        }
    }
}