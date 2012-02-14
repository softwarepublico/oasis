<?php
class Base_View_Helper_Datediff
{
	public function datediff($date1, $date2, $function = "dateDifference")
	{
		if($function == "dateDifference"){
			return $this->$function($date1, $date2);
		} else {
			$this->$function($date1, $date2);
		}
	}
	
	private function dateDifference($date1, $date2)
	{
		$obj = new Util_Datediff($date1, $date2);
		
		return $obj->datediff();
	}
	
	/**
	 * Método que defini um campo data e hora para o sistema com 
	 * validação e componete de calendario.
	 * 
	 * @author Wunilberto Melo
	 * @since 17/10/2008
	 * @param text $dataInicio  Defini a data inicio
	 * @param text $dataFim     Defini a segunda data
	 * @return text $botaoData Retorna o script na tela para fazer as datas 
	 */
	public function comparaDataInicioFim($dataInicio, $dataFim)
	{
		echo " <script>
					$(document).ready(function(){
						$('#{$dataFim}').bind('blur', function(){
							removeTollTip();						
							validaData{$dataFim}();
						});
					});
					function validaData{$dataFim}()
					{
						var nomeInicio = $('#{$dataInicio}').val();
						var nomeFim    = $('#{$dataFim}').val();
						if(nomeInicio && nomeFim){
							var dataFim = parseInt(nomeFim.split( \"/\" )[2].toString()+nomeFim.split( \"/\" )[1].toString()+nomeFim.split( \"/\" )[0].toString()); 
							var dataInicio = parseInt( nomeInicio.split( \"/\" )[2].toString()+nomeInicio.split( \"/\" )[1].toString()+nomeInicio.split( \"/\" )[0].toString()); 

							if(dataFim < dataInicio){
								showToolTip('".Base_Util::getTranslator('L_VIEW_DATA_FINAL_MENOR_INICIO')."',$('#{$dataFim}'));
								//$('#{$dataFim}').focus();
								//$('#{$dataFim}').select();
								return false;
	  						} 
						}
						return true;
					}
				</script>";
	}
}