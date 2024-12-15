/*
clone functions
author Francisco Caserio - francisco.caserio@gmail.com
versao 2.1 - 06/09/2021
*/
function addClone(id) {
	if (typeof _globals == "undefined") _globals = {};
	// check form length
	if (document.getElementById("count_" + id))
		var count = document.getElementById("count_" + id).value;
	else
		var count = document.formulario["count_" + id].value;
	// Create new object
	var cloned = getRoot(id).cloneNode(true);
	_globals.cloned = cloned;
	cloned.id = id + count; // unset original span name
	cloned.style.display = ""; // unset original display:none style
	if (cloned.querySelector("#titulo_" + id)) cloned.querySelector("#titulo_" + id).style.display = count == 0 ? "" : "none";
	for (var elm of cloned.querySelectorAll("SELECT")) elm.value = document.getElementById(elm.id).value; // option.selected not cloned
	setNewIds(cloned, count);
	// reset script otherwise Safari execute again SCRIPT commands on clone
	var _script = cloned.getElementsByTagName("SCRIPT");
	for (var i=0; i<_script.length; i++) {
		_script[i].parentNode.removeChild(_script[i]);
	}
	// Attach cloned to HTML
	var insertHere = getPlace(id);
	if (insertHere) {
		var holder = insertHere.parentNode;
		holder.insertBefore(cloned, insertHere);
	} else {
		var holder = getRoot(id).parentNode;
		holder.appendChild(cloned);
	}
	count++;
	if (holder.tagName == "TBODY" && holder.parentNode.getElementsByTagName("TBODY").length == 1) holder.style.display = "";
	if (document.getElementById("titulo_" + id))
		document.getElementById("titulo_" + id).style.display = "";
	if (document.getElementById("count_" + id))
		document.getElementById("count_" + id).value = count;
	else
		document.formulario["count_" + id].value = count;
	// cls_form compatility; remove class that marks entity root
	remClass(cloned,'form-entity-root');
	return cloned;
}
function removeClones(id) {
	while (document.getElementById(id+(document.getElementById("count_"+id).value-1))) {
		removeClone(id, document.getElementById("count_"+id).value-1,false,true);
	}
}

trava_delete_aux = {};
function removeClone(id,ix,field_row_id,noprompt) {
	if (!field_row_id) field_row_id = "id_" + id;
	if (document.getElementById(field_row_id + ix))
		var row_id = document.getElementById(field_row_id + ix).value;
	else
		var row_id = 0;
	if (row_id == 0 || noprompt) {
		execRemoveClone(id,ix);
	} else {
		if (trava_delete_aux[id] && 
			trava_delete_aux[id][row_id] && 
			trava_delete_aux[id][row_id] == 1) {
			alert("O registro de " + id + " não pode ser excluído")
		} else if (confirm("Deseja realmente excluir este registro?")) {
			if (document.getElementById("delete_list_" + id))
				document.getElementById("delete_list_" + id).value += "," + row_id;
			else if (document.formulario["delete_list_" + id])
				document.formulario["delete_list_" + id].value += "," + row_id;
			execRemoveClone(id,ix);
		}
	}
}

function execRemoveClone(id,ix) {
	// remove clones duplicados
	if (document.getElementById(id + "_clone" + ix)) {
		while (document.getElementById(id + "_ck_child" + (parseFloat(ix)+1))) {
			execRemoveClone(id, parseFloat(ix)+1);
		}
	}
	// reseta rowspan parent
	if (document.getElementById(id + "_ck_child" + ix)) {
		for (var i=ix; i>=0; i--) {
			if (!document.getElementById(id + "_ck_child" + i)) {
				var _tds = document.getElementById(id+i).getElementsByTagName("TD");
				for (var j=0; j<_tds.length; j++) {
					if (_tds[j].rowSpan > 1) _tds[j].rowSpan--;
				}
				break;
			}
		}
	}
	// remove o item
	if (document.getElementById("count_" + id))
		var count = document.getElementById("count_" + id).value;
	else
		var count = document.formulario["count_" + id].value;
	var remove = document.getElementById(id + ix);
	var holder = remove.parentNode;
	holder.removeChild(remove);
	var fieldlist = getId(getRoot(id));
	fieldlist[fieldlist.length] = id;
	var ini = parseFloat(ix)+1;
	for (var i=ini; i<count; i++) {
		for (var j=0; j<fieldlist.length; j++) {
			var new_id = fieldlist[j] + (i-1);
			obj = document.getElementById(fieldlist[j]+i);
			if (obj) {
				obj.removeAttribute("name");
				obj.id = obj.name = new_id;
				obj.setAttribute("clone", (i-1));
			}
		}
		// look for elements not on root
		for (var elm of document.querySelector("#"+id+(i-1)).querySelectorAll("[id]:not([clone])")) {
			elm.id = elm.id.substr(0, elm.id.length - i.toString().length) + (i-1).toString();
		}
	}
	count--;
	if (document.getElementById("count_" + id))
		document.getElementById("count_" + id).value = count;
	else
		document.formulario["count_" + id].value = count;
	/* depracated 26/06/2024
	if (count == 0) {
		if (holder.tagName == "TBODY" && holder.parentNode.getElementsByTagName("TBODY").length == 1) holder.style.display = "none";
		if (document.getElementById("titulo_" + id)) document.getElementById("titulo_" + id).style.display = 'none';
	} */
}

function addCloneChild(id,ix,fields) {
	if (fields && !isArray(fields)) fields = new Array(fields);
	// reset ID
	var new_ix = parseFloat(ix) + 1;
	while (document.getElementById(id + "_ck_child" + new_ix)) {
		new_ix++;
	}
	var fieldlist = getId(getRoot(id));
	if (document.getElementById("count_" + id))
		var count = document.getElementById("count_" + id).value;
	else
		var count = document.formulario["count_" + id].value;
	for (var i=count-1; i>=new_ix; i--) {
		var tr = document.getElementById(id+i);
		tr.id = id + (i+1);
		tr.name = id + (i+1);
		for (var j=0; j<fieldlist.length; j++) {
			var new_id = fieldlist[j] + (i+1);
			obj = document.getElementById(fieldlist[j]+i);
			if (obj) {
				obj.removeAttribute("name"); // otherwise IE7 confuses ID and NAME
				obj.id = new_id;
				obj.name = new_id;
				//if (fieldlist[j] == "0documento_titulo") obj.value = document.getElementById(new_id).id;
			}
		}
	}
	// Create new object
	// var cloned = document.getElementById(id+ix).cloneNode(true);
	var cloned = getRoot(id).cloneNode(true);
	cloned.style.display = ""; // unset original display:none style
	if (fields) {
		var _inputs = cloned.getElementsByTagName("INPUT");
		var _selects = cloned.getElementsByTagName("SELECT");
		for (var i=_inputs.length-1; i>=0; i--) {
			var ck = 0;
			for (var j=0; j<fields.length; j++) {
				if (_inputs[i].name == fields[j] || _inputs[i].id == id + "_delete" || _inputs[i].id == "id_" + id) ck = 1;
			}
			if (ck == 0) {
				// set rowspan
				var obj = document.getElementById(_inputs[i].id+ix);
				while (obj.parentNode && obj.tagName != "TD") obj = obj.parentNode;
				obj.rowSpan++;
				// remove TD
				var obj = _inputs[i];
				while (obj.parentNode && obj.tagName != "TD") obj = obj.parentNode;
				obj.parentNode.removeChild(obj);
			}
		}
		for (var i=_selects.length-1; i>=0; i--) {
			var ck = 0;
			for (var j=0; j<fields.length; j++) {
				if (_selects[i].name == fields[j]) ck = 1;
			}
			if (ck == 0) {
				var obj = _selects[i];
				while (obj.parentNode && obj.tagName != "TD") obj = obj.parentNode;
				obj.parentNode.removeChild(obj);
			}
		}
	}
	// reset script otherwise Safari execute again SCRIPT commands on clone
	var _script = cloned.getElementsByTagName("SCRIPT");
	for (var i=0; i<_script.length; i++) {
		_script[i].parentNode.removeChild(_script[i]);
	}
	// reset names
	cloned.id = id + new_ix; // unset original span name
	setNewIds(cloned, new_ix);
	// Attach cloned to HTML
	if (document.getElementById(id+(new_ix+1)))
		var insertHere = document.getElementById(id+(new_ix+1));
	else
		var insertHere = getPlace(id);
	insertHere.parentNode.insertBefore(cloned,insertHere);
	if (document.getElementById("id_"+id+new_ix)) document.getElementById("id_"+id+new_ix).value = "0";
	count++;
	if (document.getElementById("count_" + id))
		document.getElementById("count_" + id).value = count;
	else
		document.formulario["count_" + id].value = count;
	// Add child marker
	var input = document.createElement("INPUT");
	input.type = "hidden";
	input.value = 1;
	input.id = id + "_ck_child" + new_ix;
	input.name = id + "_ck_child" + new_ix;
	var insertHere = document.getElementById(id + "_delete" + new_ix);
	return insertHere.parentNode.insertBefore(input,insertHere);
}

function getRoot(id) {
	if (document.getElementById("readroot_" + id))
		return document.getElementById("readroot_" + id);
	else if (document.getElementById(id))
		return document.getElementById(id);
}

function getPlace(id) {
	if (document.getElementById("tableaux_" + id))
		return document.getElementById("tableaux_" + id);
	else if (document.getElementById("anchor_" + id))
		return document.getElementById("anchor_" + id);
	else if (document.getElementById(id))
		return document.getElementById(id);
}

function getId(obj) {
	var list = [];
	for (var node of obj.childNodes) {
		if (node.id) 
			list.push(node.id);
		if (node.hasChildNodes()) {
			for (var item of getId(node)) {
				list.push(item);
			}
		}
	}
	return list;
}

function getObjElementById(obj, id) {
	for (var node of obj.childNodes) {
		if (node.id == id) return node;
		if (node.hasChildNodes()) {
			var r = getObjElementById(node, id);
			if (r) return r;
		}
	}
}

function setNewIds(obj, ix, rem_sufix) {
	for (var node of obj.childNodes) {
		if (node.id) {
			var id = node.id;
			// copy handlers
			if (document.getElementById(id).onmouseover) node.onmouseover = document.getElementById(id).onmouseover;
			if (document.getElementById(id).onmouseout) node.onmouseout = document.getElementById(id).onmouseout;
			if (document.getElementById(id).onkeypress) node.onkeypress = document.getElementById(id).onkeypress;
			if (document.getElementById(id).onkeydown) node.onkeydown = document.getElementById(id).onkeydown;
			if (document.getElementById(id).onchange) node.onchange = document.getElementById(id).onchange;
			if (document.getElementById(id).onkeyup) node.onkeyup = document.getElementById(id).onkeyup;
			if (document.getElementById(id).onclick) node.onclick = document.getElementById(id).onclick;
			if (document.getElementById(id).onfocus) node.onfocus = document.getElementById(id).onfocus;
			if (document.getElementById(id).onblur) node.onblur = document.getElementById(id).onblur;
			// copy eventListeners added on dropdownLoader
			var evs = document.getElementById(id).getEventListeners();
			for (var j=0; j<evs.length; j++) {
				node.addEventListener(evs[j].type, evs[j].listener);
			}
			// sufix
			if (rem_sufix) {
				var suf_length = rem_sufix.toString().length;
				if (node.id.substr(-suf_length) == rem_sufix) node.id = node.id.substr(0,node.id.length-suf_length);
				if (node.name && node.name.substr(-suf_length) == rem_sufix) node.name = node.name.substr(0,node.name.length-suf_length);
			}
			// resetting id
			node.id += ix;
			node.setAttribute("clone", ix);
		} else if (node.className == "fieldFilter") {
			node.setAttribute("clone", ix);
		}
		if (node.name) {
			if (node.type && 
				node.type == "select-multiple") {
				node.name = node.name.replace("[]","") + ix + "[]";
			} else {
				node.name += ix;
			}
			node.setAttribute("clone", ix);
		}
		if (node.hasChildNodes()) {
			setNewIds(node, ix, rem_sufix);
		}
		// rewrite fieldFilter
		if (node.className && node.className.indexOf("fieldFilter") >= 0 && typeof _globals == "object" && _globals._builders) {
			var srcLoader = _globals._builders[node.getAttribute("builderId")];
			if (srcLoader) {
				var loader = Object.assign(Object.create(Object.getPrototypeOf(srcLoader)), srcLoader); // clone to new instance
				loader.setHolder(node);
				loader.setFieldId(srcLoader.getBaseName() + ix);
				loader.setBaseName(srcLoader.getBaseName());
				loader.build(srcLoader.getCfg());
			}
		}
	}
	return obj;
}

function isArray(o){
	return(typeof(o.length)=="undefined")?false:true;
}

// http://stackoverflow.com/questions/9046741/get-event-listeners-attached-to-node-using-addeventlistener
var ListenerTracker = new function(){
    var is_active=false;
    // listener tracking datas
    var _elements_  =[];
    var _listeners_ =[];
    this.init=function(){
        if(!is_active){//avoid duplicate call
            intercep_events_listeners();
        }
        is_active=true;
    };
    // register individual element an returns its corresponding listeners
    var register_element=function(element){
        if(_elements_.indexOf(element)==-1){
            // NB : split by useCapture to make listener easier to find when removing
            var elt_listeners=[{/*useCapture=false*/},{/*useCapture=true*/}];
            _elements_.push(element);
            _listeners_.push(elt_listeners);
        }
        return _listeners_[_elements_.indexOf(element)];
    };
    var intercep_events_listeners = function(){
        // backup overrided methods
        var _super_={
            "addEventListener"      : HTMLElement.prototype.addEventListener,
            "removeEventListener"   : HTMLElement.prototype.removeEventListener
        };

        Element.prototype["addEventListener"]=function(type, listener, useCapture){
            var listeners=register_element(this);
            // add event before to avoid registering if an error is thrown
            _super_["addEventListener"].apply(this,arguments);
            // adapt to 'elt_listeners' index
            useCapture=useCapture?1:0;

            if(!listeners[useCapture][type])listeners[useCapture][type]=[];
            listeners[useCapture][type].push(listener);
        };
        Element.prototype["removeEventListener"]=function(type, listener, useCapture){
            var listeners=register_element(this);
            // add event before to avoid registering if an error is thrown
            _super_["removeEventListener"].apply(this,arguments);
            // adapt to 'elt_listeners' index
            useCapture=useCapture?1:0;
            if(!listeners[useCapture][type])return;
            var lid = listeners[useCapture][type].indexOf(listener);
            if(lid>-1)listeners[useCapture][type].splice(lid,1);
        };
        Element.prototype["getEventListeners"]=function(type){
            var listeners=register_element(this);
            // convert to listener datas list
            var result=[];
            for(var useCapture=0,list;list=listeners[useCapture];useCapture++){
                if(typeof(type)=="string"){// filtered by type
                    if(list[type]){
                        for(var id in list[type]){
                            result.push({"type":type,"listener":list[type][id],"useCapture":!!useCapture});
                        }
                    }
                }else{// all
                    for(var _type in list){
                        for(var id in list[_type]){
                            result.push({"type":_type,"listener":list[_type][id],"useCapture":!!useCapture});
                        }
                    }
                }
            }
            return result;
        };
    };
}();
ListenerTracker.init();