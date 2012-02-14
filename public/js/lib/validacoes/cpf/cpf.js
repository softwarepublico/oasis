//Valida CPF
//EX:  onChange="return ValidaCpf(this)"
function cpfValidade(objectCampo)
{
	var i;
    var msgInvalido = i18n.L_VIEW_SCRIPT_CPF_INVALIDO;

	s = objectCampo.val();
	if (!s){ return; }

	s = s.replace('.','');
	s = s.replace('.','');
	s = s.replace('-','');

	var c = s.substr(0,9);
    if (c == "000000000" || c == "111111111" || c == "222222222" || 
    c == "333333333" || c == "444444444" || c == "555555555" || c == "666666666" || 
    c == "777777777" || c == "888888888" || c == "999999999")
    {
   		alertMsg(msgInvalido);
		objectCampo.focus();
		objectCampo.select();
		return false;
	}

	var dv = s.substr(9,2);
	var d1 = 0;
	for (i = 0; i < 9; i++)
	{
		d1 += c.charAt(i)*(10-i);
	}

	if (d1 == 0){
		alertMsg(msgInvalido);
		return false;
	}

	d1 = 11 - (d1 % 11);
	if (d1 > 9) d1 = 0;
	if (dv.charAt(0) != d1)
	{
		alertMsg(msgInvalido);
		return false;
	}

	d1 *= 2;
	for (i = 0; i < 9; i++)
	{
		d1 += c.charAt(i)*(11-i);
	}
	d1 = 11 - (d1 % 11);
	if (d1 > 9) d1 = 0;
	if (dv.charAt(1) != d1)
	{
		alertMsg(msgInvalido);
		return false;
	}
	return true;
}