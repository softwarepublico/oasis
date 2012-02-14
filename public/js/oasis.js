$(document).ready(function(){
    $('.buttonBarMiddle').append( $('.buttonBar') );
    if($('.buttonBar').length == 0){
        $('.buttonBarLeft').css('visibility','hidden');
    }
    if(!welcome){
        removeWelcome();
    }
   
    $('ul#breadCrumbs li'            ).addClass('inativo');
    $('ul#breadCrumbs li:last-child' ).removeClass('inativo').addClass('inativoUltimo');
    $('ul#breadCrumbs li:first-child').removeClass('inativo').addClass('ativo');
    $('ul#breadCrumbs li:only-child' ).removeClass('inativoUltimo ativo').addClass('ativoUnico');

    $('ul#menuContainer li:last-child').addClass('comeco');
    $('ul#menuContainer li:first-child').addClass('final');
});
		
/**
 * Carrega dinamicamente um LINK SHORTCUT ICON
 * @param  STRING srcShortcutIcon
 * @author Sósthenes
 */
function loadShortcutIcon( srcShortcutIcon ){
    $('head').append(
        $( document.createElement('link') ).attr({
            rel: 'SHORTCUT ICON',
            href: srcShortcutIcon
        })
        );
}

/**
 * Carrega dinamicamente um script JAVASCRIPT
 * 
 * @param  STRING srcScript
 * @author Sósthenes
 */
function loadScript( srcScript ){
    $('head').append(
        $( document.createElement('script') ).attr({
            type: 'text/javascript',
            src: srcScript
        })
        );
}

/**
 * Carrega dinamicamente um LINK CSS
 * 
 * @param  STRING srcCSS
 * @param  STRING mediaCSS
 * @param  STRING browser
 * @author Sósthenes
 * @Alterado Wunilberto
 */
function loadCSS( srcCSS, mediaCSS, browser ){
    if(browser == 'ie'){
        if($.browser.msie){
            $('head').append(
                $( document.createElement('link') ).attr({
                    rel: 'stylesheet',
                    type: 'text/css',
                    media: mediaCSS || 'screen,projection',
                    href: srcCSS
                })
                );
        }
    } else {
        $('head').append(
            $( document.createElement('link') ).attr({
                rel: 'stylesheet',
                type: 'text/css',
                media: mediaCSS || 'screen,projection',
                href: srcCSS
            })
            );
    }
}

function mudaFonte( tam ){
    var tamanho;
    switch( tam ){
        case 'pequena':
            tamanho = '8';
            break;
        case 'normal' :
            tamanho = '10';
            break;
        case 'grande' :
            tamanho = '12';
            break;
    }
    tamanho += 'px';
    $('body').css('font-size',tamanho);
}

/**
 * Função que abre o popup do relatório...
 * 
 * @param STRING path
 * @param STRING width
 * @param STRING height
 */
function openPopup( path, width, height, scrollbars) {
    var w = (width)?width:'800';
    var h = (height)?height:'600';
    var s = (scrollbars)?scrollbars:'no';
    var jan = window.open( path, "JANELA", "width = "+w+", height= "+h+", directories=no, channelmode=no, fullscreen=no, location=no, menubar=no, resizable=no, scrollbars="+s+", status=no, titlebar=no, toolbar=no");
    jan.focus();
}

/**
 * Função chama o popup do relatório...
 * 
 * @param OBJQUERY form   objQuery do form
 * @param STRING   width  se não passar ele assume o valor de 800px
 * @param STRING   height se não passar ele assume o valor de 600px
 */
function gerarRelatorio( form, action, width, height, scrollbars ){
    var w = (width)?width:'800';
    var h = (height)?height:'600';
    var s = (scrollbars)?scrollbars:'no';

    if(form){
        form.attr({
            'action':action,
            'method':'post',
            'target':'new'
        });
    }
    openPopup( action, w, h, s);
    if(form){
        form.attr('target', 'JANELA');
        form.submit();
    }
}

/**
 * Função que remove as informações de recepção do usuário logado
 */
function removeWelcome(){
    $('#nomeUsuario').remove();
}

/*
 *	Ajax para data dinamica do sistema para atualizar data e hora
 */
function apresentaData(mes,ano,idData)
{
    $.ajax({
        type: "POST",
        url: systemName+"/util/atualiza-data",
        data: "mes="+mes
        +"&ano="+ano,
        success: function(retorno){
            $('#'+idData+'').html(retorno);
        }
    });
}

/*** java Script para as funções do ajuda ****/
function abreModalAjuda(paginaPhtml)
{
    openPopup(systemName+"/ajuda/index/pagina/"+paginaPhtml, '1000', false, 'yes' );
}

/**
 * Cria um Dialog.
 * Quando TOP e LEFT não forem especificados o Dialog será centralizado.
 * 
 * @param string             params.id
 * @param string             params.containerId
 * @param string             params.url 
 * @param object             params.data 
 * @param object             params.objQuery 
 * @param string             params.title
 * @param integer            params.width
 * @param integer            params.height
 * @param boolean            params.closeOnEscape
 * @param boolean            params.modal
 * @param function | string  params.actionButton
 * @param object             params.buttons
 *  
 * @return void | boolean false;
 *
 * @exemplo loadDialog({ 
 *              id       : 'testeDialog', // obrigatorio
 *              title    : 'TESTE DE DIALOG', // opcional
 *              
 *              objQuery : $('#id_do_objQuery_existente_na_tela'), // para copiar o conteudo deste elemento (SEM AJAX)
 *              // ou esses //
 *              url      : systemName + '/gerenciamento-teste-caso-uso-analise/upload-file/pGet/vGet', // retorno da requisição AJAX
 *              data     : {'pPost1':'vPost1','pPost2':'vPost2'}, // opcional
 *              buttons  : {
 *                  label1 : function(){alert('vc clicou e não vai fechar')},
 *                  label2 : function() {alert('vc clicou e vai fechar');closeDialog($(this));}
 *              } // opcional
 *          });
 *
 */
function loadDialog( params ){
    if( typeof(params)=='object' ){
        
        // ID é obrigatório
        if( params.id == undefined ){
            return false;
        }
        // se vier um OBJQUERY
        if( params.objQuery != undefined ){
            var html = params.objQuery.html();
        /**
             * @todo terminar depois
             * 
             * var temp = params.objQuery.clone();
             * params.objQuery.remove();
             */
        // se vier uma URL com ou sem DATA
        } else if( params.url != undefined ){
        
            //var dataPost = (params.data != undefined)?params.data:{};
            var html = $.ajax({
                url   : params.url,
                data  : params.data,
                //data  : postData,
                type  : 'POST',
                async : false
            }).responseText;
        
        }
    
    } else {
        return false;
    }

    // definindo o id com ID
    var id            =  params.id;
    // definindo o contentor com o CONTAINERID
    var containerId   = (params.containerId   != undefined) ? params.containerId   : false;
    // definindo o titulo com o TITLE
    var title         = (params.title         != undefined) ? params.title         : '';
    // definindo a largura com o WIDTH
    var width         = (params.width         != undefined) ? params.width         : 600;
    // definindo a altura com o LENGHT
    var height        = (params.height        != undefined) ? params.height        : 480;
    // definindo a se fecha ao precionar o ESC com o CLOSEONESCAPE
    var closeOnEscape = (params.closeOnEscape != undefined) ? params.closeOnEscape : true;
    // definindo os botões com o BUTTONS
    var modal		  = (params.modal		  != undefined) ? params.modal		   : true;
    // definindo os botões com o BUTTONS
    if(params.buttons != undefined){
        var buttons = params.buttons;
    } else {
        var buttons = {
            Ok : function() {
                $("#"+id).dialog('close');
                //$("#"+id).remove();
                if(params.actionButton != undefined){
                    if($.isFunction(params.actionButton)){
                        setTimeout(params.actionButton, 100);
                    }else{
                        setTimeout('eval('+ params.actionButton +');', 100);
                    }
                }
            }
        };
    }

    $("#"+id).remove();
    
    var strHtml  = '<div id="'+ id +'" title="'+ title +'" style="display:none;">';
    strHtml += '<span style="float:left; margin:0 7px 50px 0;"></span>';
    strHtml += html;
    strHtml += '</div>';
    
    var container = (containerId) ? 'body #'+ containerId : 'body';

    $(container).append(strHtml);
    
    $("#"+id).dialog({
        bgiframe      : true,
        shadow        : true,
        modal         : modal,
        width         : width,
        height        : height,
        closeOnEscape : closeOnEscape,
        buttons		  : buttons
    });
}

function closeDialog( idDialog ){
    //  acao de fechar padrao do Dialog	 //
    beforeCloseDialog();                 //
    $('#'+idDialog).dialog('close');     //
    afterCloseDialog();                  //
///////////////////////////////////////////
}

function beforeCloseDialog(){ 
    return true;
}

function  afterCloseDialog(){ 
    return true;
}

/**
 * Função para verificar se o usuário ira sair da pagina
 */
function goodbye(e) {
    if(!e) e = window.event;
    //e.cancelBubble is supported by IE - this will kill the bubbling process.
    e.cancelBubble = true;
    //    e.returnValue = 'Vc pode perder tudo kkkk <- isso e só um teste'; //This is displayed on the dialog

    //e.stopPropagation works in Firefox.
    if (e.stopPropagation) {
        e.stopPropagation();
        e.preventDefault();
    }
}

/**
 * Função que executa um redirect com parametros por GET ou por POST
 *
 * @param string controllerAction
 * @param mixed  params Json
 * @param string type
 * @return mixed
 */
function redirect(controllerAction,params,type){
    type = !isset(type)?'get':'post';
    var url = '';

    if( controllerAction === false ){
        return false;
    }
    if( !isset(controllerAction) ){
        return false;
    }
    if( controllerAction === '' ){
        return window.location = url;
    }

    if( type === 'get' ){
        params = !isset(params)?'':params;
        var parameters = '';
        if( typeof(params)=='object' ){
            if( isset(params.jquery)){
                parameters = '?'+params.serialize();
            } else {
                for( var i in params ){
                    if( isset(params.i) ){
                        parameters += '/' + i + '/' + params.i;
                    } else {
                        parameters += '/' + i + '/' + params[i];
                    }
                }
            }
        } else {
            params = (params != '')? '/' + params:'';
            parameters = params;
        }
        if( parameters != '' ){
            var arrControllerAction = new Array();
            arrControllerAction = controllerAction.split('/');
            if( !isset(arrControllerAction[1]) ){
                controllerAction += '/index';
            } else if( arrControllerAction[1] == '' ){
                controllerAction += 'index';
            }
        }
        url = systemName + '/' + controllerAction + parameters;
        return window.location = url;
    } else {
        url = systemName + '/' + controllerAction;
        var formRedirect = mountHiddenForm('formRedirect',url,params);
        return formRedirect.submit();
    }
}

/**
 * Função que monta um form com campos ocutos
 *
 * @param string id
 * @param string action
 * @param mixed data
 * @param string container
 * @return object jQuery
 */
function mountHiddenForm(id,action,data,container){
    container = isset(container)?container:'body';
    var formXhtml  = '<form action="'+action+'" method="post" id="'+id+'">';
    $('#'+id).remove();
    if( typeof(data)=='object' ){
        if( isset(data.jquery)){
            data.each(function(i,elem){
                formXhtml += '<input type="hidden"';
                formXhtml += '       id="'+$(this).attr('id')+'"';
                formXhtml += '       name="'+$(this).attr('name')+'"';
                formXhtml += '       value="'+$(this).val()+'"';
                formXhtml += '/>';
            });
        } else {
            var value = '';
            for( var i in data ){
                formXhtml += '<input type="hidden"';
                formXhtml += '       id="'+i+'"';
                formXhtml += '       name="'+i+'"';
                if( isset(data.i) ){
                    value = data.i;
                    formXhtml += ' value="'+value+'"';
                } else {
                    value = (!isset(data[i]))?'':data[i];
                    formXhtml += ' value="'+value+'"';
                }
                formXhtml += '/>';
            }
        }
    }
    formXhtml += '</form>';
    $(container).append(formXhtml);
    return $(container+' #'+id);
}

function isset(something){
    return something!==undefined;
}

/**
 * retorna a internacionalização de uma string com variáveis.
 *
 * @param string str || Exemplo "O valor %0% é maior que o valor %1%" || quantidade de atributos %x%
 * @param array arrValue || arrValue[0] = 10; arrValue[1] = 5 || neste caso substituirá '%0%' por 10 e '%1%' por 5
 *
 */
function getTranslaterJsComVariaveis(str, arrValue)
{
    for( var i in arrValue){
        str = str.replace("%"+i+"%", arrValue[i]);
    }
    return str;
}

function getDate()
{
    var objDt = new Date();
    var dt    = '';
    dt       += ((objDt.getDate()     ) < 10)?'0'+( objDt.getDate() ):(objDt.getDate() );
    dt       += '/'
    dt       += ((objDt.getMonth() + 1) < 10)?'0'+( objDt.getMonth() + 1):(objDt.getMonth() + 1);
    dt       += '/'
    dt       += objDt.getFullYear();

    return dt;
}

function getTime()
{
    var objDt    = new Date();
    var hour     = ((objDt.getHours()   ) < 10)?'0'+( objDt.getHours()   ):(objDt.getHours()   ); // 0-23
    var min      = ((objDt.getMinutes() ) < 10)?'0'+( objDt.getMinutes() ):(objDt.getMinutes() ); // 0-59
    var sec      = ((objDt.getSeconds() ) < 10)?'0'+( objDt.getSeconds() ):(objDt.getSeconds() ); // 0-59
    var str_hour = hour + ':' + min + ':' + sec;

    return str_hour;
}

function getDateTime()
{
    var d = getDate();
    var t = getTime();
	
    return d + ' ' + t;
}

function ajaxFileUpload( options ){
    
    options = isset(options)?options:{};

    var url             = ( isset(options.url          ) )? options.url           : '';
    var inputFile       = ( isset(options.inputFile    ) )? options.inputFile     : '';
    var data            = ( isset(options.data         ) )? options.data          : '';
    var extension       = ( isset(options.extension    ) )? options.extension     : '';
    var fileSize        = ( isset(options.fileSize     ) )? options.fileSize      : '1048576';
    var uploadUp        = ( isset(options.uploadUp     ) )? options.uploadUp      : '';
    var uploadTmp       = ( isset(options.uploadTmp    ) )? options.uploadTmp     : '';
    var uploadDir       = ( isset(options.uploadDir    ) )? options.uploadDir     : '';
    var imgLoader       = ( isset(options.imgLoader    ) )? options.imgLoader     : '';
    var callbackSuccess = ( isset(options.callback     ) )? options.callback      : function(){};
    var callbackError   = ( isset(options.callbackError) )? options.callbackError : callbackSuccess;

    if(!url){
        alertMsg('A url para upload nao foi informada');
        return false;
    }
    if(!inputFile){
        alertMsg('O input file nao foi informado');
        return false;
    }

    if( data !== null ){
        if( !data ){
            data = '';
            $(':input').not(':disabled,:button').each(function(){
                data += $(this).serialize() + '&' ;
            });
        }else{
            data = data;
        }
        data = ( !isset(data.jquery) ) ? data : data.serialize();
        //coloca data para o padrao de GET da zend
        var dataSerialize = data.replace(/=/g,'/');
        dataSerialize = dataSerialize.replace(/&/g,'/');
        
        url += '/'+ dataSerialize;
    }
    
    if(imgLoader != '' && imgLoader.jquery ){
        if(imgLoader.length == 1){
            imgLoader.ajaxStart(function(){$(this).show();})
                     .ajaxComplete(function(){$(this).hide();})
                     .ajaxStop(function(){$(this).unbind('ajaxStart');});
        }
    }

    //starting setting some animation when the ajax starts and completes\n
    $.ajaxFileUpload({
        url          : url,
        secureuri    : false,
        fileElementId: inputFile.attr('id'),
        fileName     : inputFile.attr('id'),
        extension    : extension,
        fileSize     : fileSize,
        upload_up    : uploadUp,
        upload_tmp   : uploadTmp,
        upload_dir   : uploadDir,
        dataType     : 'json',
        success      : function(retorno){
            if(retorno.error == false){
                alertMsg(retorno.msg, retorno.typeMsg, callbackSuccess);
            }else{
                alertMsg(retorno.msg, retorno.typeMsg, callbackError);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            alertMsg(XMLHttpRequest.responseText, 3, callbackError);
        }
    });

    return true;
}
