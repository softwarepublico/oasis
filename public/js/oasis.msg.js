/*** Functions para Mensagens do sistema***/
/**
 * Função que substitui o ALERT padrão do JS.
 *
 * @param STRING msg ( mensagem do alert ) 
 * @param INEGER tipo ( 1: verde, 2: amarelo, 3: vermelho )
 * @author Wunilberto
 */
function alertMsg(msg, tipo, actionButton, height, width)
{
    var strTipo;
    var strClass;
    switch( tipo ){
        case 1:
            strTipo = i18n.L_VIEW_SCRIPT_SUCESSO;
            strClass = "ui-icon ui-icon-circle-check";
        break;
        case 2:
            strTipo = i18n.L_VIEW_SCRIPT_AVISO;
            strClass = "ui-icon ui-icon-alert";
        break;
        case 3:
            strTipo = i18n.L_VIEW_SCRIPT_ERRO;
            strClass = "ui-icon ui-icon-circle-close";
        break;
        default:
            strTipo = i18n.L_VIEW_SCRIPT_AVISO;
        	strClass = "ui-icon ui-icon-alert";
        break;
    }
    //Incluindo o Html Para montar o dialog
    var strHtml = "<div id=\"oasisDialogMsg\" title=\""+strTipo+"\" style=\"display:none;\">";
    strHtml += "	  <p>";
    strHtml += "	  	<span id=\"imgDialogMsg\"  style=\"float:left; margin:0 7px 50px 0;\"></span>";
    strHtml += 			msg;
    strHtml += "     </p>";
    strHtml += "</div>";
    $("#oasisDialogMsg").remove();
    $('body').prepend(strHtml);
	
    $("#imgDialogMsg").attr('class',strClass);
    
    height = (height)? height : 'auto';
	width  = (width) ? width  : 300;
	
	$("#oasisDialogMsg").dialog({
		bgiframe: true,
		shadow: true,
		height: height,
		width: width,
		modal: true,
		buttons: {
			"Ok": function() {
				$(this).dialog('close');
				$(this).remove();
				if(actionButton != ''){
					if($.isFunction(actionButton)){
                        setTimeout(actionButton, 100);
                    }else{
                        setTimeout('eval('+ actionButton +');', 100);
                    }
				}
			}
		}
	});
}

/**
 * Função que substitui o CONFIRM padrão do JS.
 *
 * @param STRING  msg  ( mensagem do alert ) 
 * @param BOOLEAN funcaoSim ( função se TRUE )
 * @param BOOLEAN funcaoNao ( função se FALSE )
 * @param STRING  title  ( texto do titulo ) 
 * @param INT  height  ( altura do dialog ) 
 * @param INT  width  ( comprimento do dialog )
 */
function confirmMsg(msg, funcaoSim, funcaoNao, title, height, width)
{
	title = (title != undefined ) ? title : i18n.L_VIEW_SCRIPT_CONFIRMACAO;
    
    var strHtml = "<div id=\"oasisDialogMsg\" title=\""+title+"\" style=\"display:none;\">";
	strHtml += "	  <p>";
	strHtml += "	  	<span class=\"ui-icon ui-icon-alert\" style=\"float:left; margin:0 7px 20px 0;\"></span>";
	strHtml += 			msg;
	strHtml += "     </p>";
	strHtml += "</div>";
	$("#oasisDialogMsg").remove();
	$('body').prepend(strHtml);

	height = (height != 'undefined')? height : 'auto';
	width  = (width  != 'undefined')? width  : 280;

    //configura os botoes do dialog com a internacionalização
    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_SIM+'": '+function(){
                                                                $(this).dialog('close');
                                                                $(this).remove();
                                                                if(funcaoSim != ""){
                                                                    if($.isFunction(funcaoSim)){
                                                                        setTimeout(funcaoSim, 100);
                                                                    }else{
                                                                        setTimeout('eval('+ funcaoSim +');', 100);
                                                                    }
                                                                }
                                                            }+', "'+i18n.L_VIEW_SCRIPT_BTN_NAO+'": '+function(){
                                                                                                        $(this).dialog('close');
                                                                                                        $(this).remove();
                                                                                                        if(funcaoNao != ""){
                                                                                                            if($.isFunction(funcaoNao)){
                                                                                                                setTimeout(funcaoNao, 100);
                                                                                                            }else{
                                                                                                                setTimeout('eval('+ funcaoNao +');', 100);
                                                                                                            }
                                                                                                        }
                                                                                                    }+'};');


	$("#oasisDialogMsg").dialog({
		modal: true,
		bgiframe: true,
		shadow: true,
		height: height,
		width: width,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},
        buttons: buttons
	});
}

/**
 * coloca na tela uma mensagem enquanto carrega o AJAX...
 */
function ajaxLoading( msg ){
	//Remove caixas de dialog do sistema
	$("#oasisDialogMsg").remove();
	$('#oasisCarregandoDialog').remove();
	
	var strHtml = '<div id="oasisCarregandoDialog" class="oasis_carregando" title="'+msg+'" style=\"display: none;\"></div>';
	$('body').prepend(strHtml);
	
	$("#oasisCarregandoDialog").dialog({
		draggable: false,
		closeOnEscape: false,
		minHeight: 60,
		minWidth: 150,
		modal: true,
		resizable: false,
		shadow: true
	});
	$('#ui-dialog-title-oasisCarregandoDialog').next().remove();
}

/**
 * depois que o AJAX carregou...
 */
function ajaxLoaded(){
	$('#oasisCarregandoDialog').dialog('close');
    $('#oasisCarregandoDialog').remove();
}

/**
* Para checar se o CapsLock ta ativado
* se sim retorna o showMsg com o aviso no elemento informado
*
* exemplo: onkeypress="checarCapsLock(event,$(this))"
*/
function checarCapsLock(ev,objQuery) {
	removeTollTip();
	var e = ev || window.event;
	var codigo_tecla = e.charCode||e.keyCode||e.which;
	var tecla_shift = e.shiftKey?e.shiftKey:((codigo_tecla == 16)?true:false);
	
	if(((codigo_tecla >= 65 && codigo_tecla <= 90) && !tecla_shift) || ((codigo_tecla >= 97 && codigo_tecla <= 122) && tecla_shift)) {
		showToolTip(i18n.L_VIEW_SCRIPT_CAPS_LOCK_ATIVO, objQuery);
	}
}