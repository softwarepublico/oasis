<?php
class Base_View_Helper_EditorHtml extends Zend_View_Helper_FormTextarea
{
	/**
     * Método que defini um campo data para o sistema com 
     * validação e componete de calendario.
     * 
     * @param text $name    Defini nome e Id no campo do input.
     * @param text $value   Defini valor para o campo.
     * @param Text $attribs Defini os atributos para o input.
     * @return text $botaoData Retorno todo o input montado para a data
     */
    public function editorHtml($name, $value = null, $attribs = null)
    {
        if (!is_array($attribs)) {
            $attribs = array();
        } else {
        	if(array_key_exists('editor',$attribs)){
	        	$numero = $attribs['editor'];
	        	unset($attribs['editor']);
        	} else {
        		$numero = 805368827;
        	}
        }
        
        $strScript = $this->jwysiwyg($numero);
        $script = " <script type=\"text/javascript\">
	        $(function(){
	        	$('.fullOut').hide();
	            $('#{$name}').wysiwyg();
	        });
	        $('#{$name}').wysiwyg({
	            controls : {
	                {$strScript}
				}
	        });
        </script>";
        echo $script; 
                
        $editor = $this->formTextarea($name, $value, $attribs);
        return $editor; 
    }
    
    private function jwysiwyg($numero)
    {
    	$strEditor = "";
    	
		$arrEditor['1']          = "bold : { visible : true },";                     
		$arrEditor['2']          = "italic: { visible : true },";                   
		$arrEditor['4']          = "strikeThrough: { visible : true },";            
		$arrEditor['8']          = "underline: { visible : true },";                
		$arrEditor['16']         = "justifyLeft: { visible : true },";              
		$arrEditor['32']         = "justifyCenter: { visible : true },";            
		$arrEditor['64']         = "justifyRight: { visible : true },";             
		$arrEditor['128']        = "justifyFull: { visible : true },";              
		$arrEditor['256']        = "indent: { visible : true },";                   
		$arrEditor['512']        = "outdent: { visible : true },";                  
		$arrEditor['1024']       = "subscript: { visible : true },";                
		$arrEditor['2048']       = "superscript: { visible : true },";              
		$arrEditor['4096']       = "undo: { visible : true },";                     
		$arrEditor['8192']       = "redo: { visible : true },";                     
		$arrEditor['16384']      = "insertOrderedList: { visible : true },";        
		$arrEditor['32768']      = "insertUnorderedList: { visible : true },";      
		$arrEditor['65536']      = "insertHorizontalRule: { visible : true },";    
		$arrEditor['131072']     = "createLink: { visible : true },";              
		$arrEditor['262144']     = "insertImage: { visible : true },";             
		$arrEditor['524288']     = "h1mozilla: { visible : true },";               
		$arrEditor['1048576']    = "h2mozilla: { visible : true },";               
		$arrEditor['2097152']    = "h3mozilla: { visible : true },";               
		$arrEditor['4194304']    = "h1: { visible : true },";                      
		$arrEditor['8388608']    = "h2: { visible : true },";                      
		$arrEditor['16777216']   = "h3: { visible : true },";                      
		$arrEditor['33554432']   = "cut: { visible : true },";                     
		$arrEditor['67108864']   = "copy: { visible : true },";                    
		$arrEditor['134217728']  = "paste: { visible : true },";                   
		$arrEditor['268435456']  = "increaseFontSize: { visible : true },";        
		$arrEditor['536870912']  = "decreaseFontSize: { visible : true },";        
		$arrEditor['1073741824'] = "html: { visible : true },";                    
		$arrEditor['2147483648'] = "removeFormat: { visible : true },";
		$arrEditor['4294967296'] = "fullIn: { visible : true },";
		$arrEditor['8589934592'] = "fullOut: { visible : true },";
		
		foreach($arrEditor as $key=>$value){
			if(($numero & $key) == $key){
			     $strEditor .= $arrEditor[$key];
			} 
		}
		
		$strEditor = substr($strEditor,0,-1);
		return $strEditor;
    } 
}