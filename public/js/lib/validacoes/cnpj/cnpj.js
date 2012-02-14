function cnpjValidade(campoObj)
{
	var retorno = true;
	if(campoObj.val() == "00.000.000/0000-00"){
		retorno = false;
	} else {
		if(campoObj.val() != ""){
			var df, resto, dac = "";
			
			cnpj = campoObj.val();
			cnpj = soNumericos(cnpj);
			if (cnpj.length != 14) 
				retorno = false;
		
			df = 5*cnpj.charAt(0)+4*cnpj.charAt(1)+3*cnpj.charAt(2)+2*cnpj.charAt(3)+9*cnpj.charAt(4)+8*cnpj.charAt(5)+7*cnpj.charAt(6)+6*cnpj.charAt(7)+5*cnpj.charAt(8)+4*cnpj.charAt(9)+3*cnpj.charAt(10)+2*cnpj.charAt(11);
			resto = df % 11;
			dac += ( (resto <= 1) ? 0 : (11-resto) );
			df = 6*cnpj.charAt(0)+5*cnpj.charAt(1)+4*cnpj.charAt(2)+3*cnpj.charAt(3)+2*cnpj.charAt(4)+9*cnpj.charAt(5)+8*cnpj.charAt(6)+7*cnpj.charAt(7)+6*cnpj.charAt(8)+5*cnpj.charAt(9)+4*cnpj.charAt(10)+3*cnpj.charAt(11)+2*parseInt(dac);
			resto = df % 11;
			dac += ( (resto <= 1) ? 0 : (11-resto) );
		
			retorno = (dac == cnpj.substring(cnpj.length-2,cnpj.length));
		} else {
		    retorno = false;
		}
	}
	if (!retorno){
		alertMsg(i18n.L_VIEW_SCRIPT_CNPJ_INVALIDO);
		campoObj.focus();
		return false;
	}
	
}

function soNumericos(num)
{
	num = "" + num;
	if (!num) return "";

	var resultado = "";
	for (i=0; i<num.length; i++)
	{
		digito = num.substring(i,i+1);
		if ("0123456789".indexOf(digito) != -1)
		resultado += digito;
	}
	return resultado;
}