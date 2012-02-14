/**
 * WYSIWYG - jQuery plugin 0.4
 *
 * Copyright (c) 2008 Juan M Martinez
 * http://plugins.jquery.com/project/jWYSIWYG
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * $Id: $
 */
(function( $ )
{
    $.fn.document = function()
    {
        var element = this[0];

        if ( element.nodeName.toLowerCase() == 'iframe' )
            return element.contentWindow.document;
            /*
            return ( $.browser.msie )
                ? document.frames[element.id].document
                : element.contentWindow.document // contentDocument;
             */
        else
            return $(this);
    };

    $.fn.documentSelection = function()
    {
        var element = this[0];

        if ( element.contentWindow.document.selection )
            return element.contentWindow.document.selection.createRange().text;
        else
            return element.contentWindow.getSelection().toString();
    };

    $.fn.wysiwyg = function( options )
    {
        if ( arguments.length > 0 && arguments[0].constructor == String )
        {
            var action = arguments[0].toString();
            var params = [];

            for ( var i = 1; i < arguments.length; i++ )
                params[i - 1] = arguments[i];

            if ( action in Wysiwyg )
            {
                return this.each(function()
                {
                    // colacado devido erro que estava aparecendo!
                    if($.data(this,'wysiwyg')!= undefined){ 
                        $.data(this, 'wysiwyg').designMode();
                        Wysiwyg[action].apply(this, params);
                    }
                });
            }
            else return this;
        }

        var controls = {};

        /**
         * If the user set custom controls, we catch it, and merge with the
         * defaults controls later.
         */
        if ( options && options.controls )
        {
            var controls = options.controls;
            delete options.controls;
        }

        var options = $.extend({
            html : '<'+'?xml version="1.0" encoding="UTF-8"?'+'><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">STYLE_SHEET</head><body>INITIAL_CONTENT</body></html>',
            css  : {},

            debug        : false,

            autoSave     : true,  // http://code.google.com/p/jwysiwyg/issues/detail?id=11
            rmUnwantedBr : true,  // http://code.google.com/p/jwysiwyg/issues/detail?id=15
            brIE         : true,

            controls : {},
            messages : {}
        }, options);

        $.extend(options.messages, Wysiwyg.MSGS_EN);
        $.extend(options.controls, Wysiwyg.TOOLBAR);

        for ( var control in controls )
        {
            if ( control in options.controls )
                $.extend(options.controls[control], controls[control]);
            else
                options.controls[control] = controls[control];
        }

        // not break the chain
        return this.each(function()
        {
            Wysiwyg(this, options);
        });
    };

    function Wysiwyg( element, options )
    {
        return this instanceof Wysiwyg
            ? this.init(element, options)
            : new Wysiwyg(element, options);
    }

    $.extend(Wysiwyg, {
        insertImage : function( szURL, attributes )
        {
            var self = $.data(this, 'wysiwyg');

            if ( self.constructor == Wysiwyg && szURL && szURL.length > 0 )
            {
                if ( attributes )
                {
                    self.editorDoc.execCommand('insertImage', false, '#jwysiwyg#');
                    var img = self.getElementByAttributeValue('img', 'src', '#jwysiwyg#');

                    if ( img )
                    {
                        img.src = szURL;

                        for ( var attribute in attributes )
                        {
                            img.setAttribute(attribute, attributes[attribute]);
                        }
                    }
                }
                else
                {
                    self.editorDoc.execCommand('insertImage', false, szURL);
                }
            }
        },

        createLink : function( szURL )
        {
            var self = $.data(this, 'wysiwyg');

            if ( self.constructor == Wysiwyg && szURL && szURL.length > 0 )
            {
                var selection = $(self.editor).documentSelection();

                if ( selection.length > 0 )
                {
                    self.editorDoc.execCommand('unlink', false, []);
                    self.editorDoc.execCommand('createLink', false, szURL);
                }
                else if ( self.options.messages.nonSelection )
                    alert(self.options.messages.nonSelection);
            }
        },

        clear : function()
        {
            var self = $.data(this, 'wysiwyg');
                self.setContent('');
                self.saveContent();
        },
        
       /**
        * Function criada para receber o texto ou Html e incluir o valor dentro do editor. 
        * @autor: Wunilberto Melo
        * @since: 12/02/2008
        * @param: value - Texto para incluir no editor.
        */
        
        value : function(value)
        {
        	var self = $.data(this, 'wysiwyg');
                self.setContent(value);
                self.saveContent();
        },
        
        valueEditorTextarea : function()
        {
           	var self = $.data(this, 'wysiwyg');
        	var	value = self.getContent();
                self.setContent('');
                self.setContent(value);
                self.saveContent();
        },

        MSGS_EN : {
            nonSelection : 'select the text you wish to link'
        },
       /**
        * Mensagem traduzida para o português do link
        * @autor: Wunilberto Melo
        * @since: 12/02/2008
        */
        MSGS_PT_BR : {
            nonSelection : 'Selecione seu texto para o link'
        },

        TOOLBAR : {
            bold          : { visible : false, tags : ['b', 'strong'], css : { fontWeight : 'bold' }, title : 'Negrito' },
            italic        : { visible : false, tags : ['i', 'em'], css : { fontStyle : 'italic' }, title : 'Itálico' },
            strikeThrough : { visible : false, tags : ['s', 'strike'], css : { textDecoration : 'line-through' }, title : 'Tachado' },
            underline     : { visible : false, tags : ['u'], css : { textDecoration : 'underline' }, title : 'Sublinhado' },

            separator00 : { visible : false, separator : false },

            justifyLeft   : { visible : false, css : { textAlign : 'left' }, title : 'Alinhar Texto à Esquerda' },
            justifyCenter : { visible : false, tags : ['center'], css : { textAlign : 'center' }, title : 'Centralizar' },
            justifyRight  : { visible : false, css : { textAlign : 'right' }, title : 'Alinhar Texto à Direita' },
            justifyFull   : { visible : false, css : { textAlign : 'justify' }, title : 'Justificar' },

            separator01 : { visible : false, separator : false },

            indent  : { visible : false, title : 'Aumentar Recuo' },
            outdent : { visible : false, title : 'Diminuir Recuo' },

            separator02 : { visible : false, separator : true },

            subscript   : { visible : false, tags : ['sub'], title : 'subscript' },
            superscript : { visible : false, tags : ['sup'], title : 'superscript' },

            separator03 : { visible : false, separator : false },

            undo : { visible : false, title : 'Desfazer' },
            redo : { visible : false, title : 'Refazer' },

            separator04 : { visible : false, separator : false },

            insertOrderedList    : { visible : false, tags : ['ol'], title : 'Numeração' },
            insertUnorderedList  : { visible : false, tags : ['ul'], title : 'Marcadores' },
            insertHorizontalRule : { visible : false, tags : ['hr'], title : 'insertHorizontalRule' },

            separator05 : { separator : false },

            createLink : {
                visible : false,
                title   : 'Criar Link',
                exec    : function()
                {
                    var selection = $(this.editor).documentSelection();

                    if ( selection.length > 0 )
                    {
                        if ( $.browser.msie )
                            this.editorDoc.execCommand('createLink', true, null);
                        else
                        {
                            var szURL = prompt('URL', 'http://');

                            if ( szURL && szURL.length > 0 )
                            {
                                this.editorDoc.execCommand('unlink', false, []);
                                this.editorDoc.execCommand('createLink', false, szURL);
                            }
                        }
                    }
                    else if ( this.options.messages.nonSelection )
                        alert(this.options.messages.nonSelection);
                },

                tags : ['a']
            },

            insertImage : {
                visible : false,
                title   : 'Inserir Imagem',
                exec    : function()
                {
                    if ( $.browser.msie )
                        this.editorDoc.execCommand('insertImage', true, null);
                    else
                    {
                        var szURL = prompt('URL', 'http://');

                        if ( szURL && szURL.length > 0 )
                            this.editorDoc.execCommand('insertImage', false, szURL);
                    }
                },

                tags : ['img']
            },

            separator06 : { separator : false },

            h1mozilla : { visible : false && $.browser.mozilla, title : 'Título 1', className : 'h1', command : 'heading', arguments : ['h1'], tags : ['h1'] },
            h2mozilla : { visible : false && $.browser.mozilla, title : 'Título 2', className : 'h2', command : 'heading', arguments : ['h2'], tags : ['h2'] },
            h3mozilla : { visible : false && $.browser.mozilla, title : 'Título 3', className : 'h3', command : 'heading', arguments : ['h3'], tags : ['h3'] },

            h1 : { visible : false && !( $.browser.mozilla ), title : 'Título 1', className : 'h1', command : 'formatBlock', arguments : ['Heading 1'], tags : ['h1'] },
            h2 : { visible : false && !( $.browser.mozilla ), title : 'Título 2', className : 'h2', command : 'formatBlock', arguments : ['Heading 2'], tags : ['h2'] },
            h3 : { visible : false && !( $.browser.mozilla ), title : 'Título 3', className : 'h3', command : 'formatBlock', arguments : ['Heading 3'], tags : ['h3'] },

            separator07 : { visible : false, separator : true },

            cut   : { visible : false, title : 'Recortar' },
            copy  : { visible : false, title : 'Copiar' },
            paste : { visible : false, title : 'Colar' },

            separator08 : { separator : false && !( $.browser.msie ) },

            increaseFontSize : { visible : false && !( $.browser.msie ), title : 'Aumentar Texto', tags : ['big'] },
            decreaseFontSize : { visible : false && !( $.browser.msie ), title : 'Diminuir Texto', tags : ['small'] },

            separator09 : { separator : false },

            html : {
                visible : false,
                title : 'Html',
                exec    : function()
                {
                    if ( this.viewHTML )
                    {
                        this.setContent( $(this.original).val() );
                        $(this.original).hide();
                    }
                    else
                    {
                        this.saveContent();
                        $(this.original).show();
                    }
                    this.viewHTML = !( this.viewHTML );
                }
            },

            removeFormat : {
                visible : true,
                title : 'Remover Formatação',
                exec    : function()
                {
//                    alert(this.editorDoc);
//                    alert(this.editorDoc.execCommand);


                    this.editorDoc.execCommand('removeFormat', false, []);
                    this.editorDoc.execCommand('unlink', false, []);
                }
            },
            
            separator10 : { separator : true },
            
            fullIn  : { 
            	   visible : true,
            	   title : 'Maximizar Editor',
            	   exec    : function()
            	   {
            	   		var editor = $(this.original).attr('id')+"_editor";
						$("#"+editor).toggleClass('full');
						$('.fullOut').css('display','block');
						$('.fullIn').hide();
            	   }
             },
             
			fullOut : {  
			       visible : true,
			       title : 'Restaurar Editor ou aparte a tecla \'ESC\'',
				   exec : function()
				   {
					   var editor = $(this.original).attr('id')+"_editor";
				       $("#"+editor).toggleClass('full');
				       $('.fullIn').show();
				       $('.fullOut').css('display','none');
				   } 
			}
        }
    });

    $.extend(Wysiwyg.prototype,
    {
        original : null,
        options  : {},

        element  : null,
        editor   : null,

        init : function( element, options )
        {
            var self = this;

            this.editor = element;
            this.options = options || {};

            $.data(element, 'wysiwyg', this);

            var newX = element.width || element.clientWidth;
            var newY = element.height || element.clientHeight;

            if ( element.nodeName.toLowerCase() == 'textarea' )
            {
                this.original = element;
                /**
                 * Elemeno 'idTextarea' criado para receber o nome do Textarea.
                 * Este elemento será a tag id da div que o componente wysiwyg cria. 
                 * @autor: Wunilberto Melo
                 * @since: 12/02/2008
                 */
				var idTextarea = $(this.original).attr('id');

                if ( newX == 0 && element.cols )
                    newX = ( element.cols * 8 ) + 21;

                if ( newY == 0 && element.rows )
                    newY = ( element.rows * 16 ) + 16;

                var editor = this.editor = $('<iframe></iframe>').css({
                    minHeight : ( newY - 6 ).toString() + 'px',
                    width     : ( newX - 8 ).toString() + 'px'
                }).attr('id', $(element).attr('id') + 'IFrame');

                if ( $.browser.msie )
                {
                    this.editor
                        .css('height', ( newY ).toString() + 'px');

                    /**
                    var editor = $('<span></span>').css({
                        width     : ( newX - 6 ).toString() + 'px',
                        height    : ( newY - 8 ).toString() + 'px'
                    }).attr('id', $(element).attr('id') + 'IFrame');

                    editor.outerHTML = this.editor.outerHTML;
                     */
                }
            }

            var panel = this.panel = $('<ul></ul>').addClass('panel');
            
            this.appendControls();
            this.element = $('<div></div>').css({
                width : ( newX > 0 ) ? ( newX ).toString() + 'px' : '100%'
            }).addClass('wysiwyg float-l')
              /**
               * Variavel 'idTextarea' sendo atribuida na div wysiwyg
               * @autor: Wunilberto Melo
               * @since: 12/02/2008
               */
              .attr('id',idTextarea+'_editor')
              .append(panel)
              .append( $('<div><!-- --></div>').css({ clear : 'both' }) )
              .append(editor);

            $(element)
            // .css('display', 'none')
            .hide()
            .before(this.element);

            this.viewHTML = false;

            this.initialHeight = newY - 8;
            this.initialContent = $(element).text();

            this.initFrame();

            if ( this.initialContent.length == 0 )
                this.setContent('');

            if ( this.options.autoSave )
                $('form').submit(function() { self.saveContent(); });
        },

        initFrame : function()
        {
            var self = this;
            var style = '';
            /**
             * @link http://code.google.com/p/jwysiwyg/issues/detail?id=14
             */
            if ( this.options.css && this.options.css.constructor == String )
                style = '<link rel="stylesheet" type="text/css" media="screen" href="' + this.options.css + '" />';

            this.editorDoc = $(this.editor).document();
            this.editorDoc_designMode = false;

            try {
                this.editorDoc.designMode = 'on';
                this.editorDoc_designMode = true;
            } catch ( e ) {
                // Will fail on Gecko if the editor is placed in an hidden container element
                // The design mode will be set ones the editor is focused

                $(this.editorDoc).focus(function()
                {
                    self.designMode();
                });
            }

            this.editorDoc.open();
            this.editorDoc.write(
                this.options.html
                    .replace(/INITIAL_CONTENT/, this.initialContent)
                    .replace(/STYLE_SHEET/, style)
            );
            this.editorDoc.close();
            this.editorDoc.contentEditable = 'true';

            if ( $.browser.msie ){
                /**
                 * Remove the horrible border it has on IE.
                 */
                setTimeout(function() { $(self.editorDoc.body).css('border', 'none'); }, 0);
            }

            $(this.editorDoc).click(function( event ){
            	self.checkTargets( event.target ? event.target : event.srcElement);
            });

            /**
             * @link http://code.google.com/p/jwysiwyg/issues/detail?id=20
             */
            $(this.original).focus(function(){
                $(self.editorDoc.body).focus();
            });
            
           /**
	        * Verifica se o click foi fora do editor para chamar a função valueFrame. 
	        * @autor: Wunilberto Melo
	        * @since: 09/03/2009.
	        */
            $(this.editorDoc).blur(function(){
            	self.checkValueEditor();
            });
            
           /**
	        * Verifica se o click foi fora do editor para chamar a função valueFrame. 
	        * @autor: Wunilberto Melo
	        * @since: 02/04/2009.
	        */
	        $(this.editorDoc).keydown(function( event ){
	        	var e = event || window.event;
            	self.restaurarEditorEscape(e);
            });
            
            if ( this.options.autoSave )
            {
                /**
                 * @link http://code.google.com/p/jwysiwyg/issues/detail?id=11
                 */
                $(this.editorDoc).keydown(function() { self.saveContent(); })
                                 .mousedown(function() { self.saveContent(); });
            }

            if ( this.options.css )
            {
                setTimeout(function()
                {
                    if ( self.options.css.constructor == String )
                    {
                        /**
                         * $(self.editorDoc)
                         * .find('head')
                         * .append(
                         *     $('<link rel="stylesheet" type="text/css" media="screen" />')
                         *     .attr('href', self.options.css)
                         * );
                         */
                    }
                    else
                        $(self.editorDoc).find('body').css(self.options.css);
                }, 0);
            }

            $(this.editorDoc).keydown(function( event )
            {
                if ( $.browser.msie && self.options.brIE && event.keyCode == 13 )
                {
                    var rng = self.getRange();
                        rng.pasteHTML('<br />');
                        rng.collapse(false);
                        rng.select();

    				return false;
                }
            });
        },

	   /**
        * Function  
        * @autor: Wunilberto Melo
        * @since: 02/04/2009.
        */
        restaurarEditorEscape : function(event)
        {
		   var editor        = $(this.original).attr('id')+"_editor";
           var notFullScreen = $('.fullIn:visible').attr('title');
		   var keycode      = event.charCode||event.keyCode||event.which;

  		  if( keycode == 27 && notFullScreen == undefined ){ // escape, close box
			   eval($("#"+editor).toggleClass('full'));
			   $('.fullIn').show();
			   $('.fullOut').css('display','none');
		  }
        },

	   /**
        * Function criada para verificar se o editor possui informações que o textarea não tem. 
        * @autor: Wunilberto Melo
        * @since: 09/03/2009.
        */
        checkValueEditor : function(){
        	var idTextarea = $(this.original).attr('id');
        	eval($('#'+idTextarea).wysiwyg('valueEditorTextarea',''));
        },

        designMode : function(){
            if ( !( this.editorDoc_designMode ) )
            {
                try {
                    this.editorDoc.designMode = 'on';
                    this.editorDoc_designMode = true;
                } catch ( e ) {}
            }
        },

        getSelection : function(){
            return ( window.getSelection ) ? window.getSelection() : document.selection;
        },

        getRange : function(){
            var selection = this.getSelection();

            if ( !( selection ) )
                return null;

            return ( selection.rangeCount > 0 ) ? selection.getRangeAt(0) : selection.createRange();
        },

        getContent : function()
        {
           /**
            * Métodos incluidos para retirar classes e tags do html das informações
            * do texto.
            * @autor: Wunilberto Melo
            * @autor: Sosthenes Neto
            * @since: 18/12/2009.
            */
           // $( $(this.editor).document() ).find('body').find('*').not('p,br,ul,li,span,b,i').remove();
           // $( $(this.editor).document() ).find('body').find('span').removeAttr('lang,class');
           // $( $(this.editor).document() ).find('body').find('p').removeAttr('class');
            
            return $( $(this.editor).document() ).find('body').html();
        },

        setContent : function( newContent )
        {
            $( $(this.editor).document() ).find('body').html(newContent)
        },

        saveContent : function()
        {
            if ( this.original )
            {
                var content = this.getContent();

                if ( this.options.rmUnwantedBr )
                    content = ( content.substr(-4) == '<br>' ) ? content.substr(0, content.length - 4) : content;

                $(this.original).val(content);
            }
        },

        appendMenu : function( cmd, args, className, fn, title )
        {
            var self = this;
            var args = args || [];

            $('<li></li>').append(
                $('<a><!-- --></a>').addClass(className || cmd).attr('title',title)
            ).mousedown(function() {
                if ( fn ) fn.apply(self); else self.editorDoc.execCommand(cmd, false, args);
                if ( self.options.autoSave ) self.saveContent();
            }).appendTo( this.panel );
        },

        appendMenuSeparator : function()
        {
            $('<li class="separator"></li>').appendTo( this.panel );
        },

        appendControls : function()
        {
            for ( var name in this.options.controls )
            {
                var control = this.options.controls[name];

                if ( control.separator )
                {
                    if ( control.visible !== false )
                        this.appendMenuSeparator();
                }
                else if ( control.visible )
                {
                    this.appendMenu(
                        control.command || name, control.arguments || [],
                        control.className || control.command || name || 'empty', control.exec, control.title || ''
                    );
                }
            }
        },
        
        checkTargets : function( element )
        {
            for ( var name in this.options.controls )
            {
                var control = this.options.controls[name];
                var className = control.className || control.command || name || 'empty';

                $('.' + className, this.panel).removeClass('active');

                if ( control.tags )
                {
                    var elm = element;
					
                    do {
                        if ( elm.nodeType != 1 )
                            break;

                        if ( $.inArray(elm.tagName.toLowerCase(), control.tags) != -1 )
                            $('.' + className, this.panel).addClass('active');
                    } while ( elm = elm.parentNode );
                }

                if ( control.css )
                {
                    var elm = $(element);

                    do {
                        if ( elm[0].nodeType != 1 )
                            break;

                        for ( var cssProperty in control.css )
                            if ( elm.css(cssProperty).toString().toLowerCase() == control.css[cssProperty] )
                                $('.' + className, this.panel).addClass('active');
                    } while ( elm = elm.parent() );
                }
            }
        },

        getElementByAttributeValue : function( tagName, attributeName, attributeValue )
        {
            var elements = this.editorDoc.getElementsByTagName(tagName);

            for ( var i = 0; i < elements.length; i++ )
            {
                var value = elements[i].getAttribute(attributeName);

                if ( $.browser.msie )
                {
                    /** IE add full path, so I check by the last chars. */
                    value = value.substr(value.length - attributeValue.length);
                }

                if ( value == attributeValue )
                    return elements[i];
            }

            return false;
        }
    });
})(jQuery);