<?php
class Base_View_Helper_Script extends Zend_View_Helper_FormText {

    private $_viewRenderer;
    private $_arrScriptFile;

    public function script($script, Array $arrScriptFile = array()) {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $this->_viewRenderer  = $viewRenderer;
        $this->_arrScriptFile = $arrScriptFile;

        $this->$script();
        return $this->_viewRenderer->view->headScript();
    }

    protected function autocomplete() {
        //CSS do autocomplete
        $this->_viewRenderer->view->headLink()->appendStylesheet($this->_viewRenderer->view->baseUrl().'/public/js/lib/jquery/autocomplete/jquery.autocomplete.css','screen, projection');
        //JS do autocomplete
        $this->_viewRenderer->view->headScript()->appendFile($this->_viewRenderer->view->baseUrl().'/public/js/lib/jquery/autocomplete/jquery.autocomplete.min.js','text/javascript',array('language'=>'javascript'));
    }
    
    protected function grid() {
        //CSS da GRID
        $this->_viewRenderer->view->headLink()->appendStylesheet($this->_viewRenderer->view->baseUrl().'/public/js/lib/jquery/tablesorter/themes/blue/style.css','screen, projection');
        //JS da GRID
        $this->_viewRenderer->view->headScript()->appendFile($this->_viewRenderer->view->baseUrl().'/public/js/lib/jquery/tablesorter/jquery.tablesorter.js','text/javascript',array('language'=>'javascript'));
        $this->_viewRenderer->view->headScript()->appendFile($this->_viewRenderer->view->baseUrl().'/public/js/lib/jquery/tablesorter/jquery.tablesorter.pager.js','text/javascript',array('language'=>'javascript'));
        $this->_viewRenderer->view->headScript()->appendFile($this->_viewRenderer->view->baseUrl().'/public/js/lib/jquery/tablesorter/jquery.metadata.js','text/javascript',array('language'=>'javascript'));
    }

    protected function mascaraDinheiro() {
        $this->_viewRenderer->view->headScript()->appendFile($this->_viewRenderer->view->baseUrl().'/public/js/lib/jquery/maskMoney/jquery.maskMoney.0.2.js','text/javascript',array('language'=>'javascript'));
    }

    protected function mascara() {
        $this->_viewRenderer->view->headScript()->appendFile($this->_viewRenderer->view->baseUrl().'/public/js/lib/jquery/jqMask/jquery.maskedinput.js','text/javascript',array('language'=>'javascript'));
    }

    protected function tab() {
    /**
     *      Exemplo de como era
     *      loadCSS('{$this->objBaseUrl->baseUrl()}/public/js/lib/jquery/tabs/jquery.tabs.css','print, projection, screen','');
            loadCSS('{$this->objBaseUrl->baseUrl()}/public/js/lib/jquery/tabs/jquery.tabs-ie.css','print, projection, screen','ie');
            $script = " loadScript('{$this->objBaseUrl->baseUrl()}/public/js/lib/jquery/tabs/jquery.tabs.pack.js');
                        loadScript('{$this->objBaseUrl->baseUrl()}/public/js/lib/jquery/tabs/jquery.history_remote.pack.js'); ";
     */
        $this->_viewRenderer->view->headLink()->appendStylesheet($this->_viewRenderer->view->baseUrl().'/public/js/lib/jquery/tabs/jquery.tabs.css','screen, projection');
        $this->_viewRenderer->view->headScript()->appendFile($this->_viewRenderer->view->baseUrl().'/public/js/lib/jquery/tabs/jquery.tabs.pack.js','text/javascript',array('language'=>'javascript'));
        $this->_viewRenderer->view->headScript()->appendFile($this->_viewRenderer->view->baseUrl().'/public/js/lib/jquery/tabs/jquery.history_remote.pack.js','text/javascript',array('language'=>'javascript'));
    }

    protected function calendario() {
        //CSS da calendario
        $this->_viewRenderer->view->headLink()->appendStylesheet($this->_viewRenderer->view->baseUrl().'/public/js/lib/jquery/calendar/css/datepicker.css','screen, projection');
        //JS da calendario
        $this->_viewRenderer->view->headScript()->appendFile($this->_viewRenderer->view->baseUrl().'/public/js/lib/jquery/calendar/datepicker.js','text/javascript',array('language'=>'javascript'));
    }

    protected function validaData() {
        $this->_viewRenderer->view->headScript()->appendFile($this->_viewRenderer->view->baseUrl().'/public/js/lib/validacoes/data/date.js','text/javascript',array('language'=>'javascript'));
    }

    protected function validaCNPJ() {
        $this->_viewRenderer->view->headScript()->appendFile($this->_viewRenderer->view->baseUrl().'/public/js/lib/validacoes/cnpj/cnpj.js','text/javascript',array('language'=>'javascript'));
    }

    protected function validaCPF() {
        $this->_viewRenderer->view->headScript()->appendFile($this->_viewRenderer->view->baseUrl().'/public/js/lib/validacoes/cpf/cpf.js','text/javascript',array('language'=>'javascript'));
    }

    protected function validaForm() {
        $this->_viewRenderer->view->headScript()->appendFile($this->_viewRenderer->view->baseUrl().'/public/js/lib/validacoes/form/validacaoForm.js','text/javascript',array('language'=>'javascript'));
    }

    protected function editorHtml() {
        //CSS da editorHtml
        $this->_viewRenderer->view->headLink()->appendStylesheet($this->_viewRenderer->view->baseUrl().'/public/js/lib/jquery/jwysiwyg/jquery.wysiwyg.css','screen, projection');
        //JS da editorHtml
        $this->_viewRenderer->view->headScript()->appendFile($this->_viewRenderer->view->baseUrl().'/public/js/lib/jquery/jwysiwyg/jquery.wysiwyg.js','text/javascript',array('language'=>'javascript'));
    }

    protected function numero() {
        $this->_viewRenderer->view->headScript()->appendFile($this->_viewRenderer->view->baseUrl().'/public/js/lib/utils/numeros.js','text/javascript',array('language'=>'javascript'));
    }

    protected function ganttChart() {
        //CSS da ganttChart
        $this->_viewRenderer->view->headLink()->appendStylesheet($this->_viewRenderer->view->baseUrl().'/public/js/lib/ganttChart/ganttChart.css','screen, projection');
        //JS da ganttChart
        $this->_viewRenderer->view->headScript()->appendFile($this->_viewRenderer->view->baseUrl().'/public/js/lib/ganttChart/ganttChart.js','text/javascript',array('language'=>'javascript'));
    }

    protected function treeview()
    {
        //CSS da ganttChart
        $this->_viewRenderer->view->headLink()->appendStylesheet($this->_viewRenderer->view->baseUrl().'/public/js/lib/jquery/treeview/jquery.treeview.css','screen, projection');
        //JS da ganttChart
        $this->_viewRenderer->view->headScript()->appendFile($this->_viewRenderer->view->baseUrl().'/public/js/lib/jquery/treeview/jquery.treeview.js','text/javascript',array('language'=>'javascript'));
        $this->_viewRenderer->view->headScript()->appendFile($this->_viewRenderer->view->baseUrl().'/public/js/lib/jquery/jquery.cookie.js','text/javascript',array('language'=>'javascript'));
    }

    protected function uploadAjax() {
        $this->_viewRenderer->view->headScript()->appendFile($this->_viewRenderer->view->baseUrl().'/public/js/lib/jquery/upload/ajaxfileupload.js','text/javascript',array('language'=>'javascript'));
    }
    
    protected function scriptFile()
    {
        $strUrl = "";
        if(count($this->_arrScriptFile)){
            foreach($this->_arrScriptFile as $module=>$file){
                if(is_int($module)){
                    $strUrl = $this->_viewRenderer->view->baseUrl()."/public/js/modules/".$this->_viewRenderer->getRequest()->getModuleName().$file;
                } else {
                    $strUrl = $this->_viewRenderer->view->baseUrl()."/public/js/modules/".$module.$file;
                }

                $this->view->headScript()->appendFile($strUrl,'text/javascript',array('language'=>'javascript'));
            }
        }
    }
}