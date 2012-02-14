function showToolTip( msg, objJquery, ojbModal ){
    var top   = objJquery.offset().top; 
    var left  = objJquery.offset().left;

    $(".toolTip").remove();

	if(ojbModal){
	    var str   = '<p class="toolTip"  style="top:'+(top - 50 -55)+'px; left:'+(left + 10 - 340)+'px;">';
	        str  += '    <span> '+msg+'</span>';
	        str  += '</p>';
   		ojbModal.append(str);
   	} else {
	    var str   = '<p class="toolTip"  style="top:'+(top - 50)+'px; left:'+(left + 10)+'px;">';
	        str  += '    <span> '+msg+'</span>';
	        str  += '</p>';
   		$(document.body).append(str);
	    var targetOffset = objJquery.offset().top;
	    $('html,body').animate({scrollTop: targetOffset-70}, 500);
   	}

    objJquery.bind('change', function(){ removeTollTip(); });
    var remove = window.setTimeout('removeTollTip()',10000);
}

function removeTollTip(){
	$(".toolTip").hide('slow');
}

function estaVazio(objJquery){
    
}

function validaForm( container, comAlert ){

	removeTollTip();
    var retorno   = true;
    var comAlert  = (comAlert)?comAlert:false;
    var container = (!container)?'':container;

    var msgCampoObrigatorio = i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO;


    $(container+" label.required").each(function(i){
        //Input Select
        var idSelect          = this.getAttribute("for");

        var elemSelect        = $("select#" + this.getAttribute("for") );
        //Input File
        var elemInputFile     = $("input[type=file]#" + this.getAttribute("for") );
        //Input Text
        var elemInputText     = $("input[type=text]#" + this.getAttribute("for") );
        //Input Password
        var elemInputPassword = $("input[type=password]#" + this.getAttribute("for") );
        //Input Password
        var elemInputRadio    = $("input[type=radio]#" + this.getAttribute("for") );
        //Input Textarea
        var elemTextarea      = $("textarea#" + this.getAttribute("for"));

        if (retorno) {
            if(elemSelect.val() != undefined){
            	var possui = 'N';
                $("#"+idSelect+" option").each(function() {
                    if($(this).val() == '-1'){
                       possui = "S";
                       return false;
                    }
                });
            	if((elemSelect.val() == '' || elemSelect.val() == '-1') && possui == 'S'){
            		if( comAlert ){
                        alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO_VARIAVEL, new Array($(this).text())), 2, function(){elemSelect.focus()});
                    } else {
                        showToolTip(msgCampoObrigatorio, elemSelect);
                    }
                    retorno = false;
            	} else if((elemSelect.val() == '' || elemSelect.val() == '0') && possui == 'N' ){


            		if( comAlert ){
                        alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO_VARIAVEL, new Array($(this).text())), 2, function(){elemSelect.focus()});
                    } else {
            		  showToolTip(msgCampoObrigatorio, elemSelect);
                    }
                    retorno = false
            	}
            } else if(elemSelect.attr('multiple') == true){
            	if(elemSelect.val() == null){
            		if( comAlert ){
                        alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO_VARIAVEL, new Array($(this).text())),2,function(){elemSelect.focus()});
                    } else {
            		  showToolTip(msgCampoObrigatorio, elemSelect);
                    }
                    retorno = false
            	}
			} else if( elemInputText.val() != undefined ){
                if (elemInputText.val() == '') {
            		if( comAlert ){
                        alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO_VARIAVEL, new Array($(this).text())),2,function(){elemInputText.focus()});
                    } else {
                        showToolTip(msgCampoObrigatorio, elemInputText);
                    }
                    retorno = false;
                }
            } else if( elemInputPassword.val() != undefined ){
                if (elemInputPassword.val() == '') {
            		if( comAlert ){
                        alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO_VARIAVEL, new Array($(this).text())),2,function(){elemInputPassword.focus()});
                    } else {
                        showToolTip(msgCampoObrigatorio, elemInputPassword);
                    }
                    retorno = false;
                }
            } else if( elemInputRadio.val() != undefined ){
				//pega pelo nome pois os ids podem ser diferentes para um mesmo grupo
                if ( $("input[name="+elemInputRadio.attr('name')+"]:checked").val() == undefined ) {
            		alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO_VARIAVEL, new Array($(this).text())),3);
                    retorno = false;
                }
            } else if( elemInputFile.val() != undefined ){
                if (elemInputFile.val() == '') {
            		if( comAlert ){
                        alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO_VARIAVEL, new Array($(this).text())),2,function(){elemInputFile.focus()});
                    } else {
                        showToolTip(msgCampoObrigatorio, elemInputFile);
                    }
                    retorno = false;
                }
            } else if( elemTextarea != undefined ){
                if ($.trim(elemTextarea.val()) == '') {
                    var elemEditor = this.getAttribute("editor");
                    var idTextarea = elemTextarea.attr('id');
					
					if(elemEditor != null){
                		if( comAlert ){
                            alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO_VARIAVEL, new Array($(this).text())),2);
                        } else {
	                       showToolTip(msgCampoObrigatorio, $("#"+idTextarea+"_"+elemEditor));
                        }
					} else {
                		if( comAlert ){
                            alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO_VARIAVEL, new Array($(this).text())),2);
                        } else {
                            showToolTip(msgCampoObrigatorio, $("#"+idTextarea));
                        }
					}
                    retorno = false;
                }
            }
        }
    });
    return retorno;
}