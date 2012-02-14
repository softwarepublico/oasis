<?php
/**
 * Classe de e-mail
 *
 * @since 31/03/2009
 */
class Base_Mail extends Zend_Mail
{
	/**
	 * Variável que recebe as configurações do construtor.
	 *
	 * @var unknown_type
	 */
	private $arrConfig;
	
	/**
	 * E-mail do principal do oasis
	 *
	 * @var texto
	 */
	private $emailOasis = K_EMAIL_OASIS;
	
	/**
	 * Variável com o nome do Oasis.
	 *
	 * @var texto
	 */
	private $nomeOasis = K_NOME_CABECALHO_EMAIL;

    /**
     * CSS do email
     *
     * @var string
     */
    private $_style = ' /* RESET */
                        html, body, div, span, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, code, del, dfn, em, img, q, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td {margin:0;padding:0;border:0;font-weight:inherit;font-style:inherit;font-size:100%;font-family:inherit;vertical-align:baseline;}
                        body {line-height:1.5;}
                        table {border-collapse:separate;border-spacing:0;}
                        caption, th, td {text-align:left;font-weight:normal;}
                        table, td, th {vertical-align:middle;}
                        blockquote:before, blockquote:after, q:before, q:after {content:"";}
                        blockquote, q {quotes:"" "";}
                        a img {border:none;}
                        /* TYPOGRAPHY */
                        body {font-size:12px;color:#222;background:#fff;font-family:"Helvetica Neue", Arial, Helvetica, sans-serif;}
                        h1, h2, h3, h4, h5, h6 {font-weight:normal;color:#111;}
                        h1 {font-size:3em;line-height:1;margin-bottom:0.5em;}
                        h2 {font-size:2em;margin-bottom:0.75em;}
                        h3 {font-size:1.5em;line-height:1;margin-bottom:1em;}
                        h4 {font-size:1.2em;line-height:1.25;margin-bottom:1.25em;}
                        h5 {font-size:1em;font-weight:bold;margin-bottom:1.5em;}
                        h6 {font-size:1em;font-weight:bold;}
                        h1 img, h2 img, h3 img, h4 img, h5 img, h6 img {margin:0;}
                        p {margin:0 0 1.5em;}
                        p img.left {float:left;margin:1.5em 1.5em 1.5em 0;padding:0;}
                        p img.right {float:right;margin:1.5em 0 1.5em 1.5em;}
                        a:focus, a:hover {color:#000;}
                        a {color:#009;text-decoration:underline;}
                        blockquote {margin:1.5em;color:#666;font-style:italic;}
                        strong {font-weight:bold;}
                        em, dfn {font-style:italic;}
                        dfn {font-weight:bold;}
                        sup, sub {line-height:0;}
                        abbr, acronym {border-bottom:1px dotted #666;}
                        address {margin:0 0 1.5em;font-style:italic;}
                        del {color:#666;}
                        pre {margin:1.5em 0;white-space:pre;}
                        pre, code, tt {font:1em \'andale mono\', \'lucida console\', monospace;line-height:1.5;}
                        li ul, li ol {margin:0 1.5em;}
                        ul, ol {margin:0 1.5em 1.5em 1.5em;}
                        ul {list-style-type:disc;}
                        ol {list-style-type:decimal;}
                        dl {margin:0 0 1.5em 0;}
                        dl dt {font-weight:bold;}
                        dd {margin-left:1.5em;}
                        table {margin-bottom:1.4em;width:100%;}
                        th {font-weight:bold;}
                        thead th {background:#ECE9E9;}
                        th, td, caption {padding:4px;}
                        tr.even td {background:#e5ecf9;}
                        tfoot {font-style:italic;}
                        caption {background:#eee;}
                        .small {font-size:.8em;margin-bottom:1.875em;line-height:1.875em;}
                        .large {font-size:1.2em;line-height:2.5em;margin-bottom:1.25em;}
                        .hide {display:none;}
                        .quiet {color:#666;}
                        .loud {color:#000;}
                        .highlight {background:#ff0;}
                        .added {background:#060;color:#fff;}
                        .removed {background:#900;color:#fff;}
                        .first {margin-left:0;padding-left:0;}
                        .last {margin-right:0;padding-right:0;}
                        .top {margin-top:0;padding-top:0;}
                        .bottom {margin-bottom:0;padding-bottom:0;}
                        /* ESTILOS EM GERAL */
                        body{ font-family: Verdana,  Arial, sans-serif !important;}
                        table{ border:2px solid #eee; }
                        label{line-height: 22px;}
                        h1,h2,h3,h4 {color: #666666;font-weight: bold;}
                        h1 {font-size: 16px;}
                        h2 {font-size: 15px;}
                        h3 {font-size: 14px;}
                        h4,h5,h6 {font-size: 13px;}
                        .normal{font-weight: normal !important;}
                        .strong, .bold{font-weight: bold !important;}
                        .justify, div.justify>label{text-align: justify !important;}
                        .center, div.center>label{text-align: center !important;}
                        .left, div.left>label{text-align: left !important;}
                        .right, div.right>label{text-align: right !important;}';

	/**
	 * Método construtor da classe Base Mail.
	 * Passa os paramêtros da conexão do e-mail, podendo o usuário
	 * passar novas configurações
	 *
	 * @param text $host
	 * @param array $config
	 */
	public function __construct($host = K_SERVIDOR_EMAIL, Array $config = array(), $charset='UTF-8')
	{
        parent::__construct($charset);

		$this->arrConfig['nameAccont'] = (isset($config['nameAccount']))?$config['nameAccount']:K_EMAIL_OASIS;
        $this->arrConfig['port']       = (isset($config['port']))       ?$config['port']:K_PORTA_EMAIL;
		
		$objSmtp = new Zend_Mail_Transport_Smtp($host, $this->arrConfig);
		parent::setDefaultTransport($objSmtp);
   }
   
   /**
    * Método que envia um unico e-mail de destinatário para um ou varios remetente(s).
    *
    * @param array $arrRem
    * @param array $arrDest
    * @param text $text
    * @param text $assunto
    */
   public function email(array $arrRem, array $arrDest, $text = "", $assunto = "")
   {
   		if(count($arrRem) > 0){
   			//Recupera dados do Rementente
	   		$emailRemetente = array_keys($arrRem);
			$nomeRemetente  = array_values($arrRem);
   		} else {
   			$emailRemetente[0] = $this->emailOasis;
   			$nomeRemetente[0]  = $this->nomeOasis;
   		}
		$this->setFrom($emailRemetente[0],$nomeRemetente[0]);
		
		foreach($arrDest as $key=>$value){
			$this->addTo($key,$value);
		}

        $header = $this->mountHeader( $assunto );
        $body   = $text;
        $footer = $this->mountFooter();

        parent::setBodyHtml($header.$body.$footer);
//		$this->setBodyText($text);
//		$this->setSubject($assunto);
		$this->send();
   }
   
   /**
    * Método que envia e-mail de destinatário para um ou varios remetente(s) em html.
    *
    * @param array $arrRem
    * @param array $arrDest
    * @param text $text
    * @param text $assunto
    */
   public function emailHtml(array $arrRem, array $arrDest, $text = "", $assunto = "")
   {
   		if(count($arrRem) > 0){
   			//Recupera dados do Rementente
	   		$emailRemetente = array_keys($arrRem);
			$nomeRemetente  = array_values($arrRem);
   		} else {
   			$emailRemetente[0] = $this->emailOasis;
   			$nomeRemetente[0]  = $this->nomeOasis;
   		}
		$this->setFrom($emailRemetente[0],$nomeRemetente[0]);
		foreach($arrDest as $key=>$value){
			$this->addTo($key,$value);
		}
//		$this->setBodyHtml($text);
		$this->setSubject($assunto);
		$header = $this->mountHeader( $assunto );
        $body   = $text;
        $footer = $this->mountFooter();

        parent::setBodyHtml($header.$body.$footer);
		$this->send();
   }
   
   /**
    * Método que envia e-mail para varios remetentes com varios destinatarios.
    *
    * @param array $arrRem
    * @param array $arrDest
    * @param text $text
    * @param text $assunto
    */
   public function variosEmail(array $arrRem, array $arrDest, array $arrText, array $arrAssunto)
   {
   		foreach($arrRem as $chave=>$valor){
			$this->setFrom($chave,$valor);
			$i = 0;
			foreach($arrDest as $key=>$value){
				$this->addTo($key,$value);
				$this->setBodyHtml($arrText[$i]);
				$this->setSubject($arrAssunto[$i]);
				$i++;
			}
   		}
		$this->send();
   }
 
    /**
     * Método que monta o header
     *
     * @param string $subject
     * @return string
     */
    private function mountHeader($subject='')
    {
        $arrValueMsg = array('value1'=>date('d'),
                             'value2'=>Base_Util::getMes(date('n')),
                             'value3'=>date('Y'));

        $header = ' <html>
                        <head>
                            <title>'.$subject.'</title>
                            <meta http-equiv=Content-Type content="text/html; charset=UTF-8" />
                            <style type="text/css">'.$this->_style.'</style>
                        </head>
                        <body>
                            <div class="center" align="center">
                                <img src="'.SYSTEM_PATH.'/public/img/logo4.png" alt="Logo OASIS" title="OASIS" />
                                <h2>'.strtoupper(SYSTEM_NAME).'</h2>
                            <div>
                            <div class="right" align="right">
                                <label>'.Base_Util::getTranslator('L_VIEW_DATA_HORA_LOCAL', $arrValueMsg).'</label>
                            <div>
                            <div class="left" align="left">
                                <label>'.Base_Util::getTranslator('L_VIEW_ASSUNTO').':&nbsp;<b>'.$subject.'</b></label>
                            <div>
                            <hr />';
        return $header;
    }

    /**
     * Método que monta o footer
     *
     * @return string
     */
    private function mountFooter()
    {
        $initTagLabel = '<label>';
        $endTagLabel  = '</label><br />';
        $footer = "<hr />
                   <div class=\"left\" align=\"left\">
                       {$initTagLabel}".Base_Util::getTranslator('L_VIEW_FONTE').": ".strtoupper(SYSTEM_NAME).' - '.K_TITLE_SYSTEM."{$endTagLabel}
                       <br />
                   <div>
               </body>
           </html>";
        return $footer;
    }
}