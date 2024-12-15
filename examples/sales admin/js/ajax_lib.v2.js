/*
ajax objects
	fieldFilter
	dropdownLoader
	tableLoader
author Francisco Caserio - francisco.caserio@gmail.com
versao 4.50 - 12/01/2023
*/
release_submit = 1;
function fieldFilter(holder, field_id, xmlpath, id, label) {
	this.setHolder(holder);
	this.field_id = this.basename = field_id;
	this.xmlpath = xmlpath;
	this.tags = {
		id: (id ? id : "id"),
		label: (label ? label : "text"),
	};
	this.default_value = "";
	this.default_label = "";
	this.group_label = "";
	this.chooser = 1;
	this.readonly = 0;
	this.ck_collection = 0;
	this.ck_mail = 0;
	this.ck_condition = 0;
	this.p = -1;
	this.keyword_limit = 3;
	this.res = []; // must be set before getFilter to preserve each result in case of cloning
	this.xtra_field = [];
	this.xtra_action = [];
	this.reset_action = [];
	this.load_action = [];
	this.prop = {};
	this.color_ref = [];
	this.bgcolor_ref = [];
	this.cfg = {};
	this.className = "formpeq";
	this.keyword = "pchave";
	this.language = "pt";
	this.loadDefaults();
}
fieldFilter.prototype.loadDefaults = function(fn) {
	var th = this;
	this.ck_load_defaults = false;
	var scripts = document.getElementsByTagName("script");
	for (var i=0; i<scripts.length; i++) {
		src = scripts[i].src;
		if (src.indexOf("ajax_lib.v2.js") > 0) {
			var dir = src.substring(0, src.lastIndexOf('/'));
			var js = document.createElement("script");
			js.type = "text/javascript";
			js.src = dir + "/ajax_lib_defaults.js";
			js.onload = function() { 
				if (typeof getFieldFilterDefaults == "function") { 
					var cfg = getFieldFilterDefaults();
					for (var ix in cfg) {
						console.log(ix, cfg[ix])
						th[ix] = cfg[ix];
					}
					if (fn) fn();
					// apply class
					if (cfg.className) {
						var f = th.holder.querySelectorAll("input[type=text], select");
						for (var i=0; i<f.length; i++) f[i].className = cfg.className;
					}
					if (cfg.classNameBtn) {
						var f = th.holder.querySelectorAll("input[type=button]");
						for (var i=0; i<f.length; i++) f[i].className = cfg.classNameBtn;
					}
					th.ck_load_defaults = true;
				}
			};
			document.getElementsByTagName('head')[0].appendChild(js);	
			break;
		}
	}
}
fieldFilter.prototype.getFieldId = function() {
	return this.field_id;
}
fieldFilter.prototype.getBaseName = function() {
	return this.basename;
}
fieldFilter.prototype.getHolder = function() {
	return this.holder;
}
fieldFilter.prototype.getSearchField = function() {
	return this.filterElm;
}
fieldFilter.prototype.getSelectField = function() {
	return this.selectElm;
}
fieldFilter.prototype.getField = function() {
	return this.hiddenElm;
}
fieldFilter.prototype.getSearchButton = function() {
	return this.filterBtn;
}
fieldFilter.prototype.getResetButton = function() {
	return this.resetBtn;
}
fieldFilter.prototype.getSelRes = function(p) {
	if (typeof p == "undefined") var p = this.p;
	return this.res[p] ? this.res[p] : false;
}
fieldFilter.prototype.getResponse = function() {
	return this.responseXML;
}
fieldFilter.prototype.getLabel = function() {
	var f = this.getSelectField();
	if (f[f.selectedIndex]) return f[f.selectedIndex].text;
}
fieldFilter.prototype.getVal = function() {
	return this.hiddenElm.value;
}
fieldFilter.prototype.getCfg = function() {
	return this.cfg;
}
fieldFilter.prototype.setHolder = function(holder) {
	if (typeof holder == "string")
		this.holder = document.getElementById(holder);
	else
		this.holder = holder;
	if (!this.holder.className)
		this.holder.className = "fieldFilter";
	else if (this.holder.className.indexOf("fieldFilter") < 0)
		this.holder.className += " fieldFilter";
}
fieldFilter.prototype.setLanguage = function(val) {
	this.language = val;
}
fieldFilter.prototype.setFieldId = function(str) {
	this.field_id = str;
	this.setBaseName(str);
}
fieldFilter.prototype.setBaseName = function(str) {
	this.basename = str;
}
fieldFilter.prototype.setIdTag = function(tag) {
	this.tags.id = tag;
}
fieldFilter.prototype.setLabelTag = function(tag) {
	this.tags.label = tag;
}
fieldFilter.prototype.setTags = function(tags) {
	this.tags = tags;
}
fieldFilter.prototype.setDefault = function(id, label, ck_actions) {
	this.default_value = id;
	this.default_label = label;
	this.default_actions = ck_actions;
}
fieldFilter.prototype.setUrl = function(url) {
	this.xmlpath = url;
}
fieldFilter.prototype.setXmlPath = function(url) {
	this.setUrl(url);
}
fieldFilter.prototype.setPlaceHolder = function(str) {
	this.placeholder = str;
}
fieldFilter.prototype.removeChooser = function() {
	this.chooser = 0;
}
fieldFilter.prototype.keepChooser = function() {
	this.chooser = 2;
}
fieldFilter.prototype.setReadOnly = function(ck) {
	if (typeof ck == "undefined") var ck = true;
	this.readonly = ck;
	if (this.resetBtn) this.resetBtn.disabled = true;
}
fieldFilter.prototype.setKeywordLimit = function(val) {
	this.keyword_limit = val;
}
fieldFilter.prototype.setCondition = function(rule,msg) {
	this.ck_condition = 1;
	this.condition = rule;
	this.condition_msg = msg;
}
fieldFilter.prototype.useCollection = function() { // habilita muitipla selecao
	this.ck_collection = 1;
}
fieldFilter.prototype.setGroup = function(label) {
	this.group_label = label;
}
fieldFilter.prototype.addProp = function(prop,val,cmd) {
	this.prop[prop] = {
		val: val,
		cmd: cmd
	};
}
/* Depracated 05/12/2023
fieldFilter.prototype.chColor = function(color,field,condition,val) {
	this.color_ref[this.color_ref.length] = {
		color: color,
		field: field,
		condition: condition,
		val: val
	};
}
fieldFilter.prototype.chBgColor = function(color,field,condition,val) {
	this.bgcolor_ref[this.bgcolor_ref.length] = {
		color: color,
		field: field,
		condition: condition,
		val: val
	};
}
*/
fieldFilter.prototype.addField = function(field, xml_label) {
	if (!xml_label) xml_label = field;
	this.xtra_field.push({
		field: field,
		xml_label: xml_label
	});
}
fieldFilter.prototype.addAction = function(action) { // function(p,r) p = pos; r = res;
	var ix = this.xtra_action.length;
	var ck = 0;
	for (var i=0; i<this.xtra_action.length; i++) {
		if (this.xtra_action[i] == action) { var ck = 1; break; }
	}
	if (ck == 0) {
		this.xtra_action[ix] = action;
	}
}
fieldFilter.prototype.removeActions = function() {
	this.xtra_action = [];
}
fieldFilter.prototype.addResetAction = function(cmd) {
	this.reset_action.push(cmd);
}
fieldFilter.prototype.addLoadAction = function(cmd) { // function(p) p = pos
	this.load_action.push(cmd);
}
fieldFilter.prototype.ckMail = function(action) {
	this.ck_mail = 1;
}
fieldFilter.prototype.register = function(cfg) {
	this.cfg = cfg;
	if (typeof _globals == "undefined") _globals = {};
	if (!_globals._builders) _globals._builders = [];
	this.holder.setAttribute("builderId", _globals._builders.length);
	_globals._builders.push(this);
}
fieldFilter.prototype.build = function(cfg,arg2) { // function(size,width) {
	var th = this;
	if (typeof cfg != "object") {
		var arg = cfg;
		var cfg = {};
		if (arg)  cfg.filter = { size: arg }
		if (arg2) cfg.select = { style: "max-width:" + arg2 + "px" }
	}
	this.register(cfg); // log for cloning
	for (var elm of this.holder.childNodes) this.holder.removeChild(elm);
	var clone = this.holder.getAttribute("clone");
	var table = document.createElement("DIV");
	table.style.display = "table-row";
	var td_fields = document.createElement("DIV");
	td_fields.style.display = "table-cell";
	td_fields.style.padding = 0;
	table.appendChild(td_fields);
	// input field
	this.filterElm = document.createElement("INPUT");
	this.filterElm.type = "text";
	this.filterElm.id = this.filterElm.name = "nome" + th.field_id;
	this.filterElm.value = this.default_label;
	if (clone) this.filterElm.setAttribute("clone", clone);
	if (cfg.style) this.filterElm.style = cfg.style;
	if (cfg.filter) for (var ix in cfg.filter) this.filterElm[ix] = cfg.filter[ix];
	if (this.default_value != "") this.filterElm.style.display = "none";
	// if (cfg.size) this.filterElm.size = cfg.size;
	if (this.className) this.filterElm.className = th.className;
	if (this.placeholder) this.filterElm.placeholder = th.placeholder;
	if (!this.readonly) {
		var ie = navigator.userAgent.indexOf('MSIE') != -1 ? true : false;
		if (ie) {
			this.filterElm.onkeydown = function() {
				// th.getFilter(event, this.id.replace("nome"+th.basename,""));
				th.getFilter(event, this.getAttribute("clone")); // since fev/23 addClone uses setAttribute("clone",?)
			}
			this.filterElm.onkeypress = function() {
				return disableEnterKey(event);
			}
		} else {
			this.filterElm.onkeydown = function(event) {
				// th.getFilter(event, this.id.replace("nome"+th.basename,""));
				th.getFilter(event, this.getAttribute("clone")); // since fev/23 addClone() uses setAttribute("clone",?)
			}
			this.filterElm.onkeypress = function(event) {
				return disableEnterKey(event);
			}
		}
	} else {
		this.filterElm.readOnly = true;
	}
	this.filterElm.onblur = function() {
		release_submit = 1
	}
	this.filterElm.onfocus = function() {
		temp = th.value; release_submit = 0;
	}
	td_fields.appendChild(this.filterElm);
	// dropdown field
	this.selectElm = document.createElement("SELECT");
	this.selectElm.id = this.selectElm.name = "list" + th.field_id;
	if (clone) this.selectElm.setAttribute("clone", clone);
	if (this.className) this.selectElm.className = th.className;
	if (cfg.style) this.selectElm.style = cfg.style;
	if (cfg.select) for (var ix in cfg.select) this.selectElm[ix] = cfg.select[ix];
	if (this.default_value == "") this.selectElm.style.display = "none";
	// if (cfg.width) this.selectElm.style.maxWidth = cfg.width;
	this.selectElm.onchange = function() {
		// var pos = this.id.replace("list" + th.basename, "");
		var pos = this.getAttribute("clone"); // since fev/23 addClone() uses setAttribute("clone",?)
		if (typeof pos == "undefined" || pos === null) var pos = "";
		th.p = this.selectedIndex;
		if (th.has_chooser) th.p--;
		th.hiddenElm.value = this.value;
		th.filterElm.value = this[this.selectedIndex].text;
		th.setXtraField(pos);
		th.applyAction(pos);
	}
	if (this.default_value != "") {
		var oOption = document.createElement("OPTION");
		this.selectElm.options.add(oOption); 
		oOption.value = this.default_value;
		oOption.text = this.default_label;
		this.selectElm.title = this.default_label;
	}
	td_fields.appendChild(this.selectElm);
	// hidden
	this.hiddenElm = document.createElement("INPUT");
	this.hiddenElm.type = "hidden";
	this.hiddenElm.id = this.hiddenElm.name = th.field_id;
	this.hiddenElm.value = this.default_value;
	if (clone) this.hiddenElm.setAttribute("clone", clone);
	td_fields.appendChild(this.hiddenElm);
	// restaura campos caso submit nao se completou sucesso
	td_fields.onmouseenter = function() {
		th.filterElm.disabled = false;
		th.selectElm.disabled = false;
	}
	// buttons
	if (!this.readonly) {
		var td_buttons = document.createElement("DIV");
		td_buttons.style.display = "table-cell";
		td_buttons.style.padding = 0;
		table.appendChild(td_buttons);
		/*
		var td_buttons = document.createElement("TD");
		td_buttons.vAlign = "top";
		td_buttons.style.padding = 0;
		tr.appendChild(td_buttons);
		*/
		// submit
		this.filterBtn = document.createElement("INPUT");
		this.filterBtn.type = "button";
		this.filterBtn.id = this.filterBtn.name = "filtro" + this.field_id;
		this.filterBtn.value = this.getText("but_submit");
		if (clone) this.filterBtn.setAttribute("clone", clone);
		if (this.classNameBtn) this.filterBtn.className = this.classNameBtn;
		if (this.default_value != "") this.filterBtn.style.display = "none";
		this.filterBtn.onclick = function() {
			th.getFilter(false, this.getAttribute("clone"));
		}
		td_buttons.appendChild(this.filterBtn);
		// reset
		this.resetBtn = document.createElement("INPUT");
		this.resetBtn.type = "button";
		this.resetBtn.id = this.resetBtn.name = "reset" + this.field_id;
		this.resetBtn.value = this.getText("but_reset");
		if (this.classNameBtn) this.resetBtn.className = this.classNameBtn;
		if (this.default_value == "") this.resetBtn.style.display = "none";
		this.resetBtn.onclick = function() {
			th.reset(this.id.replace("reset"+th.basename,""),{focus:true});
		};
		td_buttons.appendChild(this.resetBtn);
	}
	if (this.ck_collection == 1) {
		// add td
		var td_add = document.createElement("DIV");
		td_add.style.display = "table-cell";
		td_add.style.padding = 0;
		table.appendChild(td_add);
		// add button
		this.addBtn = document.createElement("INPUT");
		this.addBtn.type = "button";
		this.addBtn.id = this.addBtn.name = "add" + this.field_id;
		this.addBtn.value = " --> ";
		if (this.classNameBtn) this.addBtn.className = this.classNameBtn;
		this.addBtn.style.display = "none";
		this.addBtn.onclick = function() {
			th.addItem(this.id.replace("add"+th.basename,""));
		};
		td_add.appendChild(this.addBtn);
		// collection td
		var td_col = document.createElement("DIV");
		td_col.style.display = "table-cell";
		td_col.style.padding = 0;
		table.appendChild(td_col);
		// add collection
		this.collection = document.createElement("SELECT");
		this.collection.id = this.collection.name = "collection" + this.field_id;
		if (this.className) this.collection.className = this.className;
		this.collection.size = 2;
		this.collection.onchange = function() {
			rh.remove.style.display = "";
		};
		this.collection.style.display = "none";
		td_col.appendChild(this.collection);
		// remove button
		this.remove = document.createElement("INPUT");
		this.remove.type = "button";
		this.remove.id = this.remove.name = "remove" + this.field_id;
		this.remove.value = this.getText("but_remove");
		if (this.classNameBtn) this.remove.className = this.classNameBtn;
		this.remove.style.display = "none";
		this.remove.onclick = function() {
			th.removeItem(this.id.replace("remove"+th.basename,""));
		};
		td_col.appendChild(this.remove);
	}
	this.holder.appendChild(table);
	if (this.filterElm.form) {
		this.filterElm.form.addEventListener('submit', function () {
			th.filterElm.disabled = true;
			th.selectElm.disabled = true;
		});
	}
	if (this.default_value != "" && this.default_actions) {
		var pos = this.field_id.replace(this.basename, "");
		window.addEventListener('load', function() {
			th.applyAction(pos);
		}, false);
	}
	if (cfg.action) cfg.action();
}
fieldFilter.prototype.selectDefault = function(ix,label,pos,promptActions) {
	if (parseFloat(pos) == pos) { // numeric pos
		if (!this.holder.id) alert("SelectDefault() not compatible when holder has no Id");
		for (var elm of document.getElementsByClassName("fieldFilter")) {
			if (elm.id == this.holder.id + pos) {
				var th = getBuilder(elm.getAttribute('builderId'));
				break;
			}
		}
	} else {
		var th = this;
	}
	th.hiddenElm.value = ix;
	var oOption = document.createElement("OPTION");
	th.selectElm.options.add(oOption); 
	oOption.value = ix;
	oOption.text = label;
	oOption.selected = true;
	th.selectElm.style.display = "";
	th.selectElm.title = label;
	th.filterElm.style.display = "none";
	if (!th.readonly) {
		th.resetBtn.style.display = "";
		th.filterBtn.style.display = "none";
	}
	if (promptActions) this.applyAction(pos);
	this.setDefault(ix,label);
}
fieldFilter.prototype.getFilter = function(ev,pos,params) {
	var th = this;
	if (typeof pos == "undefined" || pos === null) var pos = "";
	if (!ev || ev.keyCode == 13) {
		var filter_str = this.filterElm ? this.filterElm.value : "";
		var ck_chooser = this.chooser;
		if (ck_chooser == 2)
			var ck_chooser = 1; // Allways show chooser
		else if (filter_str != "") 
			var ck_chooser = 0;
		if (ev && document.all) ev.keyCode = 9; // change enter behaviour for IE
		var ER = new RegExp("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]{2,64}(\.[a-z0-9-]{2,64})*\.[a-z]{2,4}$");
		if (this.ck_condition == 1 && !eval(this.condition)) {
			alert(this.condition_msg);
		} else if ((filter_str.length >= this.keyword_limit && (this.ck_mail == 0 || ER.test(filter_str))) || params) {
			// limpa lista dropdown
			if (this.group_label != "") {
				while (this.selectElm.getElementsByTagName("OPTGROUP").length > 0) {
					var remove = this.selectElm.getElementsByTagName("OPTGROUP")[0];
					remove.parentNode.removeChild(remove);
				}
			} 
			for (var i=this.selectElm.length-1; i>= 0; i--) {
				this.selectElm.remove(i);
			}
			// AJAX request
			var xhr = new XMLHttpRequest();
			if (typeof this.xmlpath == "function")
				var xmlpath = this.xmlpath(pos);
			else
				var xmlpath = parseUrl(this.xmlpath, pos);
			if (params) {
				if (typeof params == "object") {
					var str = "";
					for (var ix in params) str += (str != "" ? "&" : "") + ix + "=" + params[ix];
					var params = str;
				}
			} else {
				var params = this.keyword + "=" + filter_str;
			}
			var url = xmlpath + (xmlpath.indexOf("?")>=0?"&":"?") + params;
			//console.log(url)
			//window.open(url,'_blank')
			xhr.open('POST', url, true);
			xhr.onreadystatechange = function() {
				//if (xhr.readyState == 4 && xhr.status == 200) {
				if (xhr.readyState == 4) {
					/* if (xhr.responseXML == null &&
						confirm(th.getText("bug_msg"))) 
						alert(xhr.responseText); */
					th.responseXML = xhr.responseXML;
					var res = xhr.responseXML.getElementsByTagName('data');
					if (ck_chooser == 1) {
						var oOption = document.createElement("OPTION");
						th.selectElm.options.add(oOption); 
						oOption.value = "";
						oOption.text = "-- " + th.getText("chooser") + " --";
						th.has_chooser = 1;
					} else {
						th.has_chooser = 0;
						th.p = 0;
					}
					if (res.length > 0) {
						var tag_id = th.tags.id ? th.tags.id : "id";
						var tag_label = th.tags.label ? th.tags.label : "text";
						for (var i=0; i<res.length; i++) {
							var id = "";
							for (var tag of (typeof tag_id == "object" ? tag_id : [tag_id])) {
								id += (id != "" ? ";" : "") + getNodeValue(res[i], tag);
							}
							var label = "";
							for (var tag of (typeof tag_label == "object" ? tag_label : [tag_label])) {
								label += (label != "" ? " - " : "") + getNodeValue(res[i], tag);
							}
							if (ck_chooser == 0 && i == 0) {
								if (th.ck_collection == 0) th.hiddenElm.value = id;
								th.filterElm.value = label;
							}
							th.res[i] = res[i]; // register result to use on applyAction
							var oOption = document.createElement("OPTION");
							if (th.group_label != "") {
								if (i == 0 || getNodeValue(res[i], th.group_label) != getNodeValue(res[i-1], th.group_label)) {
									var oGroup = document.createElement("OPTGROUP");
									oGroup.label = getNodeValue(res[i], th.group_label);
									th.selectElm.appendChild(oGroup);
								}
								oGroup.appendChild(oOption);
							} else {
								th.selectElm.options.add(oOption); // add before set attributes (IE Compatible)
							}
							if (id == th.default_value) {
								oOption.selected = true;
								th.hiddenElm.value = id;
								th.p = i;
							}
							oOption.value = id;
							oOption.text = label;
							oOption.title = label;
							for (var prop in th.prop) {
								if (!th.prop[prop].cmd || th.prop[prop].cmd(res[i])) oOption.style[prop] = th.prop[prop].val;
							}
							/* Depracated 05/12/2023
							for (var j=0; j<th.color_ref.length; j++) {
								if (eval("'" + getNodeValue(res[i], th.color_ref[j]["field"]) + "'" + th.color_ref[j]["condition"] + "'" + th.color_ref[j]["val"] + "'")) oOption.style.color = th.color_ref[j]["color"];
							}
							for (var j=0; j<th.bgcolor_ref.length; j++) {
								if (eval("'" + getNodeValue(res[i], th.bgcolor_ref[j]["field"]) + "'" + th.bgcolor_ref[j]["condition"] + "'" + th.bgcolor_ref[j]["val"] + "'")) oOption.style.backgroundColor = th.bgcolor_ref[j]["color"];
							} */
						}
						th.setXtraField(pos);
						if (th.ck_collection == 1) {
							th.addBtn.style.display = "";
							th.collection.style.display = "";
						}
						for (var cmd of th.load_action) {
							cmd(res);
						}
					} else if (res.length == 0) {
						var oOption = document.createElement("OPTION");
						if (th.ck_collection == 0) th.hiddenElm.value = "";
						th.selectElm.options.add(oOption); // add before set attributes (IE Compatible)
						oOption.value = "";
						oOption.text = th.getText("no_options") + (filter_str != "" ? ": " + filter_str : "");
					}
					if (ck_chooser == 0 || th.default_value != "") th.applyAction(pos);
				}
			} // function()
			xhr.send(null);
			// end AJAX request
			this.selectElm.style.display = '';
			this.resetBtn.style.display = '';
			this.filterElm.style.display = 'none';
			this.filterBtn.style.display = 'none';
		} else if (filter_str.length < this.keyword_limit) {
			alert(this.getText("limit_ini") + " " + this.keyword_limit + " " + this.getText("limit_end"));
		}
	}
}
fieldFilter.prototype.setXtraField = function(pos) {
	if (typeof pos == "undefined" || pos === null) var pos = "";
	var form = this.filterElm.form;
	for (var f of this.xtra_field) {
		if (form[f.field+pos])
			var field = form[f.field+pos];
		else if (document.getElementById(f.field+pos))
			var field = document.getElementById(f.field+pos);
		if (typeof field == "object" && !(field instanceof HTMLElement))
			var field = field[getElmIndex(this.filterElm)];
		if (typeof field != "undefined") {
			if (this.res[this.p]) {
				if (typeof f.xml_label == "function") {
					var val = f.xml_label(this.res[this.p]);
				} else {
					var val = getNodeValue(this.res[this.p], f.xml_label);
				}
			} else {
				var val = "";
			}
			if (field.type && field.type.toUpperCase() == "CHECKBOX") {
				field.checked = (val == 1 ? true : false);
			} else if (field.tagName.toUpperCase() == "INPUT" || field.tagName.toUpperCase() == "TEXTAREA" || field.tagName.toUpperCase() == "SELECT") {
				field.value = val;
			} else {
				field.innerHTML = val;
			}
		}
	} 
}
fieldFilter.prototype.applyAction = function(pos) {
	for (var cmd of this.xtra_action) {
		if (typeof cmd == "function") {
			cmd(pos, this.getSelRes(this.p));
		} else if (typeof cmd == "string") {
			eval(cmd);
		}
	}
}
fieldFilter.prototype.resetFilter = function(pos,params) {
	this.reset(pos,params);
}
fieldFilter.prototype.reset = function(pos,params) {
	if (typeof pos == "undefined" || pos === null) var pos = "";
	if (typeof params == "undefined") var params = [];
	if (typeof pos == "object") { // case params declared on first argument
		var params = pos;
		var pos = "";
	}
	var ck_focus = "focus" in params ? params.focus : false;
	var cascade = "cascade" in params ? params.cascade : true;
	var except = "except" in params ? params.except : [];
	this.p = -1;
	if (this.ck_collection == 0) this.hiddenElm.value = '';
	this.filterElm.value = "";
	this.filterElm.style.display = "";
	if (ck_focus) this.filterElm.focus();
	this.selectElm.style.display = "none";
	this.selectElm.title = "";
	this.filterBtn.style.display = "";
	this.resetBtn.style.display = "none";
	this.id = this.field_id + pos; // faking this.id for field id (not class id)
	if (!cascade) return;
	for (var cmd of this.reset_action) {
		cmd(pos);
	}
	var form = this.filterElm.form;
	for (var f of this.xtra_field) {
		if (form[f.field+pos])
			var field = form[f.field+pos];
		else if (document.getElementById(f.field+pos))
			var field = document.getElementById(f.field+pos);
		if (typeof field == "object" && !(field instanceof HTMLElement))
			var field = field[getElmIndex(this.filterElm)];
		if (except.indexOf(f.field) < 0) {
			var val = this.res[this.p] ? getNodeValue(this.res[this.p], f["xml_label"]) : "";
			if (field.type && field.type.toUpperCase() == "CHECKBOX") {
				field.checked = false;
			} else if (field.tagName.toUpperCase() == "INPUT" || field.tagName.toUpperCase() == "TEXTAREA") {
				field.value = field.defaultValue;
			} else if (field.tagName.toUpperCase() == "SELECT") {
				field.value = "";
			} else {
				field.innerHTML = "";
			}
		}
	} 
}
fieldFilter.prototype.addItem = function(pos) {
	var val = this.selectElm.value;
	var label = this.selectElm[this.selectElm.selectedIndex].text;
	this.collection.style.display = "";
	var ck = 0;
	for (var i=0; i<this.collection.length; i++) {
		if (this.collection[i].value == val) {
			ck = 1;
			break;
		}
	}
	if (ck == 1) {
		alert(this.getText("item_exists") + ": " + label);
	} else {
		var oOption = document.createElement("OPTION");
		this.collection.options.add(oOption); // add before set attributes (IE Compatible)
		oOption.text = label; 
		oOption.value = val; 
		if (this.collection.length >= 2) 
			this.collection.size = this.collection.length;
		this.setList(pos);
		this.reset(pos,{focus:true});
	}
}
fieldFilter.prototype.removeItem = function(pos) {
	for (var i=this.collection.length-1; i>=0; i--) {
		if (this.collection[i].selected) {
			this.collection.remove(i);
		}
	}
	if (this.collection.length >= 2) 
		this.collection.size = this.collection.length;
	this.setList(pos);
}
fieldFilter.prototype.setList = function(pos) {
	var str = "";
	for (var i=0; i<this.collection.length; i++) {
		str += (i>0?";":"") + this.collection[i].value;
	}
	this.hiddenElm.value = str;
}
fieldFilter.prototype.getText = function(val) {
	if (this.language == "pt") return this.getPtText(val);
	if (this.language == "en") return this.getEnText(val);
	if (this.language == "es") return this.getEsText(val);
}
fieldFilter.prototype.getPtText = function(val) {
	var ref = {};
	ref.chooser = "Escolha";
	ref.bug_msg = "Foi detectado um erro.\nClique OK para mais informações";
	ref.limit_ini = "Digite ao menos";
	ref.limit_end = "caracteres para filtrar a lista";
	ref.but_submit = "Filtrar";
	ref.but_reset = "Limpar";
	ref.but_remove = "Excluir";
	ref.no_options = "Não consta";
	ref.item_exists = "Item já incluído";
	return ref[val];
}
fieldFilter.prototype.getEsText = function(val) {
	var ref = {};
	ref.chooser = "Elija";
	ref.bug_msg = "Se detectó un error.\nClick OK para más informaciones";
	ref.limit_ini = "Ingrese al menos";
	ref.limit_end = "caracteres para filtrar el listado";
	ref.but_submit = "Filtrar";
	ref.but_reset = "Limpiar";
	ref.but_remove = "Quitar";
	ref.no_options = "Sin resultados";
	ref.item_exists = "Item ya incluido";
	return ref[val];
}
fieldFilter.prototype.getEnText = function(val) {
	var ref = {};
	ref.chooser = "Choose";
	ref.bug_msg = "Error detected.\nClick OK for more information";
	ref.limit_ini = "At least";
	ref.limit_end = "characters needed";
	ref.but_submit = "Filter";
	ref.but_reset = "Clear";
	ref.but_remove = "Remove";
	ref.no_options = "No results";
	ref.item_exists = "Item already included";
	return ref[val];
}
function callLoader(fieldId, str, chooser, action) {
	var loader = getLoader(fieldId);
	if (loader) {
		if (str) 	loader.filterElm.value = str;
		if (action) loader.addAction(action);
		if (chooser)loader.keepChooser();
		if (loader.filterElm.value != "") {
			var pos = fieldId.replace(loader.basename,"");
			loader.getFilter(false, pos);
		}
	}
}
function getLoader(elm) {
	if (typeof elm == "string") var elm = document.getElementById(elm);
	while (!elm.getAttribute("builderId")) {
		var elm = elm.parentNode;
	}
	if (elm.getAttribute("builderId")) {
		var builderId = elm.getAttribute("builderId");
		return _globals._builders[builderId];
	}
}
function lockFilter(fieldId, val, label) {
	var loader = getLoader(fieldId);
	if (loader) {
		if (val) {
			loader.hiddenElm.value = val;
			loader.filterElm.value = label;
		}
		loader.filterElm.readOnly = true;
		loader.filterBtn.disabled = true;
		loader.resetBtn.disabled = true;
	}
}

/* dropdownLoader
new dropdownLoader(fieldElm, xmlpath, id, label)
new dropdownLoader(fieldElm, xmlpath, { id:id, label:label, tag:tagName })
*/
function dropdownLoader(fieldElm, xmlpath, cfg, label) {
	th = this;
	this.fieldElm = fieldElm;
	this.xmlpath = xmlpath;
	if (typeof cfg == "object") {
		var id = cfg.id;
		var label = cfg.label;
		if (cfg.tag) this.tagName = cfg.tag;
	} else {
		var id = cfg;
	}
	this.tags = {
		id: (id ? id : "id"),
		label: (label ? label : "text"),
	};
	if (!this.tagName) this.tagName = "data";
	this.info = {}; // must be set outside getFilter to preserve each result in case of cloning
	this.res = []; // must be set outside getFilter to preserve each result in case of cloning
	this.load_action = [];
	this.xtra_action = [];
	this.xtra_label = [];
	this.xtra_field = [];
	this.prop = {};
	this.color_ref = [];
	this.bgcolor_ref = [];
	this.default_value = false;
	this.default_text = false;
	this.group_label = "";
	this.ck_prompt = 1;
	this.ck_prompt_reset = 0;
	this.chooser = 1;
	this.preserve_chooser = 0;
	this.ck_onchange = 0;
	this.ck_accents = 1;
	this.str_limit = false;
	this.language = "pt";
	this.hash = makeId(32);
	this.loadDefaults();
}
dropdownLoader.prototype.loadDefaults = function() {
	var th = this;
	this.ck_load_defaults = false;
	var scripts = document.getElementsByTagName("script");
	for (var i=0; i<scripts.length; i++) {
		src = scripts[i].src;
		if (src.indexOf("ajax_lib.v2.js") > 0) {
			var dir = src.substring(0, src.lastIndexOf('/'));
			var js = document.createElement("script");
			js.type = "text/javascript";
			js.src = dir + "/ajax_lib_defaults.js";
			//js.onreadystatechange = function() { th.loadDefaults(); };
			js.onload = function() { 
				if (typeof getDropdownLoaderDefaults == 'function') { 
					var cfg = getDropdownLoaderDefaults();
					for (var ix in cfg) {
						th[ix] = cfg[ix];
						// console.log(ix, cfg[ix])
					}
					th.ck_load_defaults = true;
				}
			};
			document.getElementsByTagName('head')[0].appendChild(js);	
			break;
		}
	}
}
dropdownLoader.prototype.setIdTag = function(tag) {
	this.tags.id = tag;
}
dropdownLoader.prototype.setLabelTag = function(tag) {
	this.tags.label = tag;
}
dropdownLoader.prototype.setTags = function(tags) {
	this.tags = tags;
}
dropdownLoader.prototype.setTagName = function(tag) {
	this.tagName = tag;
}
dropdownLoader.prototype.setLanguage = function(val) {
	this.language = val;
}
dropdownLoader.prototype.setDefault = function(id, required) {
	this.default_value = id;
	this.default_required = required;
}
dropdownLoader.prototype.setDefaultText = function(str) {
	this.default_text = str;
}
dropdownLoader.prototype.setUrl = function(url) {
	this.xmlpath_bak = this.xmlpath;
	this.xmlpath = url;
}
dropdownLoader.prototype.setXmlPath = function(url) {
	this.setUrl(url);
}
dropdownLoader.prototype.restoreUrl = function() {
	this.xmlpath = this.xmlpath_bak;
}
dropdownLoader.prototype.restoreXmlPath = function() {
	this.restoreUrl();
}
dropdownLoader.prototype.addUrlParam = function(param,val) {
	this.xmlpath += "&" + param + "=" + (val ? val : "["+param+"]");
}
dropdownLoader.prototype.addAction = function(action) { // function(p,r,e) p = pos; r = res; e = event
	var ck = 0;
	for (var i=0; i<this.xtra_action.length; i++) {
		if (this.xtra_action[i] == action) { var ck = 1; break; }
	}
	if (ck == 0) this.xtra_action.push(action);
}
dropdownLoader.prototype.addLoadAction = function(cmd) { // function(p) p = pos
	var ck = 0;
	for (var i=0; i<this.load_action.length; i++) {
		if (this.load_action[i] == cmd) { var ck = 1; break; }
	}
	if (ck == 0) this.load_action.push(cmd);
}
dropdownLoader.prototype.addProp = function(prop,val,cmd) {
	this.prop[prop] = {
		val: val,
		cmd: cmd
	};
}
/* Depracated 05/12/2023
dropdownLoader.prototype.chColor = function(color,field,condition,val) {
	this.color_ref[this.color_ref.length] = {
		color: color,
		field: field,
		condition: condition,
		val: val
	};
}
dropdownLoader.prototype.chBgColor = function(color,field,condition,val) {
	this.bgcolor_ref[this.bgcolor_ref.length] = {
		color: color,
		field: field,
		condition: condition,
		val: val
	};
}
*/
dropdownLoader.prototype.removeActions = function() {
	this.xtra_action = [];
}
/* Depracated
dropdownLoader.prototype.addLabel = function(label, xml_label, field) {
	if (!xml_label) xml_label = label;
	var ix = this.xtra_label.length;
	this.xtra_label[ix] = {};
	this.xtra_label[ix]["label"] = label;
	this.xtra_label[ix]["xml_label"] = xml_label ? xml_label : label;
	this.xtra_label[ix]["field"] = field;
}
*/
dropdownLoader.prototype.removePrompt = function() {
	this.ck_prompt = 0;
}
dropdownLoader.prototype.setStrLimit = function(val) {
	this.str_limit = val;
}
dropdownLoader.prototype.removeResetPrompt = function() {
	this.ck_prompt_reset = 0;
}
dropdownLoader.prototype.removeChooser = function() {
	this.chooser = 0;
}
dropdownLoader.prototype.preserveChooser = function() {
	this.preserve_chooser = 1;
}
dropdownLoader.prototype.setGroup = function(label) {
	this.group_label = label;
}
dropdownLoader.prototype.setValue = function(val,pos) {
	for (var field of this.getFieldRef(pos)) {
		field.value = val;
	}
	this.applyAction(false,pos);
}
dropdownLoader.prototype.addField = function(field, xml_label) {
	if (!xml_label) xml_label = field;
	this.xtra_field.push({ 
		field: field, 
		xml_label: xml_label 
	});
}
dropdownLoader.prototype.selectDefault = function(ix,label,pos) {
	if (typeof pos == "undefined" || pos === null) var pos = "";
	var oOption = document.createElement("OPTION");
	for (var field of this.getFieldRef(pos)) {
		var oOption = document.createElement("OPTION");
		field.options.add(oOption); 
		oOption.value = ix;
		oOption.text = label;
		oOption.selected = true;
	}
	this.setDefault(ix);
}
dropdownLoader.prototype.register = function(field) {
	if (typeof _globals == "undefined") _globals = {};
	if (!_globals._builders) _globals._builders = [];
	field.setAttribute("builderId", _globals._builders.length);
	_globals._builders.push(this);
}
dropdownLoader.prototype.preventActions = function() {
	this.ck_preventActions = true;
}
dropdownLoader.prototype.load = function(val,pos,selected) {
	var th = this;
	// console.log("Load " + this.fieldElm)
	// limpa dropdown de destino
	var fields = this.getFieldRef(pos);
	for (var field of fields) {
		if (field.value != "" && 
			this.ck_prompt_reset == 1 &&
			!confirm(this.getText("change_msg"))) {
			field.form.reset();
			return false;
		}
		this.register(field);
	}
	// AJAX request
	if (val instanceof HTMLElement) {
		var src = val;
		var val = false;
	} else {
		var src = false;
	}
	var xmlpath = parseUrl(this.xmlpath, pos, src, true);
	if (xmlpath.hasValue) {
		var xmlpath = xmlpath.parsed;
	} else {
		this.reset(pos, true);
		return false;
	}
	var url = xmlpath + (this.xmlpath.indexOf("?")>=0?"&":"?") + "id_ref=" + (val ? escape(val) : "");
	if (this.ck_accents == 0) url = url.removeAccents();
	if (fields.length > 0 && fields[0].value != "")
		var sel = fields[0].value;
	else if (selected)
		var sel = selected;
	else
		var sel = false;
	this.reset(pos);
	var xhr =  new XMLHttpRequest();
	xhr.open('POST', url, true);
	xhr.onreadystatechange = function() {
		//if (xhr.readyState == 4 && xhr.status == 200) {
		if (xhr.readyState == 4) {
			/* if (xhr.responseXML == null &&
				confirm(th.getText("bug_msg"))) 
				alert(xhr.responseText); */
			// reset loading
			th.ck_loaded = 1;
			th.reset(pos); // reset again to remove loading msg
			var res = th.res = xhr.responseXML.getElementsByTagName(th.tagName);
			var r = {}
			for (var i=0; i<res.length; i++) {
				r[th.getId(res[i])] = res[i];
			}
			th.info = r;
			for (var field of th.getFieldRef(pos)) {
				for (var i=0; i<res.length; i++) {
					var id = th.getId(res[i]);
					var str = th.getLabel(res[i]);
					var level = getNodeValue(res[i], 'level');
					var oOption = document.createElement("OPTION");
					if (th.group_label) {
						var ckGrp = 0;
						if (i == 0) {
							var ckGrp = 1;
						} else {
							for (var ix of typeof th.group_label == "object" ? th.group_label : [th.group_label]) {
								if (getNodeValue(res[i], ix) != getNodeValue(res[i-1], ix)) var ckGrp = 1;
							}
						}
						if (ckGrp) {
							var grp = "";
							for (var ix of typeof th.group_label == "object" ? th.group_label : [th.group_label]) {
								if (getNodeValue(res[i], ix) != "") grp += (grp ? "/" : "") + getNodeValue(res[i], ix);
							}
							var oGroup = document.createElement("OPTGROUP");
							oGroup.label = grp;
							field.appendChild(oGroup);
						}
						oGroup.appendChild(oOption);
					} else if (field.tagName.toUpperCase() == "DATALIST") {
						field.appendChild(oOption);
					} else {
						field.options.add(oOption);
					}
					oOption.text = field.style.width || th.str_limit == false ? str : str.substring(0,th.str_limit) + (str.length > th.str_limit ? "..." : "");
					oOption.value = id;
					oOption.title = str;
					if (id == sel) {
						var ix_default = id;
						oOption.selected = true;
					} else if (typeof th.default_value == "function" && th.default_value(res[i])) {
						var ix_default = id;
						oOption.selected = true;
					} else if (typeof th.default_value == "object" && th.default_value != null && th.default_value.indexOf(id) >= 0) {
						var ix_default = id;
						oOption.selected = true;
					} else if (th.default_value && id == th.default_value) {
						var ix_default = id;
						oOption.selected = true;
					} else if (th.default_text && str == th.default_text) {
						var ix_default = id;
						oOption.selected = true;
					} else if (th.ck_prompt == 0 && res.length == 1 && i == 0) {
						oOption.selected = true;
					}
					if (level != "") {
						oOption.style.paddingLeft = level * 20;
						var ref = ["F","E","D","C","B","A",0,8,7,6,5,4,3,2,1];
						if (level == "0") 
							oOption.style.borderTop = "1px solid black";
						else
							oOption.style.backgroundColor = "#" + ref[level] + ref[level] + ref[level] + ref[level] + ref[level] + ref[level];
					}
					for (var prop in th.prop) {
						if (!th.prop[prop].cmd || th.prop[prop].cmd(res[i])) oOption.style[prop] = th.prop[prop].val;
					}
					/* Depracated 05/12/2023
					for (var j=0; j<th.color_ref.length; j++) {
						if (eval("'" + getNodeValue(res[i], th.color_ref[j]["field"]) + "'" + th.color_ref[j]["condition"] + "'" + th.color_ref[j]["val"] + "'")) oOption.style.color = th.color_ref[j]["color"];
					}
					for (var j=0; j<th.bgcolor_ref.length; j++) {
						if (eval("'" + getNodeValue(res[i], th.bgcolor_ref[j]["field"]) + "'" + th.bgcolor_ref[j]["condition"] + "'" + th.bgcolor_ref[j]["val"] + "'")) oOption.style.backgroundColor = th.bgcolor_ref[j]["color"];
					} */
				}
				if (th.default_value && th.default_required && field.value == "") {
					var oOption = document.createElement("OPTION");
					field.options.add(oOption); // IE Compatible
					oOption.text = th.getText("invalid") + ": " + th.default_value;
					oOption.value = th.default_value;
					oOption.selected = true;
				}
				if (!field.hasAttribute("loadersetup") || field.getAttribute("loadersetup") != th.hash) {
					var _field_id = field.id;
					if (typeof _globals == "undefined") _globals = {};
					if (field.hasAttribute("loadersetup")) field.removeEventListener("change", _globals[field.getAttribute("loadersetup")]);
					_globals[th.hash] = function(e) {
						var _p = (typeof pos == "undefined" ? this.id.replace(_field_id,"") : pos);
						th.setXtraField(this.value,_p);
						th.applyAction(e,_p); 
					};
					field.addEventListener("change", _globals[th.hash]);
					field.setAttribute("loadersetup", th.hash);
				}
			}
			if (th.ck_preventActions) {
				th.ck_preventActions = false;
				return;
			};
			if (th.default_value) {
				th.setXtraField(ix_default);
				th.applyAction(false, pos);
			} else if (th.chooser == 0) {
				th.setXtraField(th.getId(res[0]));
				th.applyAction(false, pos);
			}
			for (var cmd of th.load_action) {
				cmd(pos,res,th);
			}
		}
	}
	this.ck_loaded = 0;
	this.setLoading();
	xhr.send(null);
}
dropdownLoader.prototype.getLabel = function(r) {
	if (typeof this.tags.label == "function") {
		return this.tags.label(r);
	} else if (typeof this.tags.label == "object") {
		var ret = "";
		for (var ix of this.tags.label) ret += (ret != "" ? " - " : "") + getNodeValue(r, ix)
		return ret;
	} else {
		return getNodeValue(r, this.tags.label);
	}
}
dropdownLoader.prototype.getId = function(r) {
	if (typeof this.tags.id == "function") {
		return this.tags.id(r);
	} else if (typeof this.tags.id == "object") {
		var ret = "";
		for (var ix of this.tags.id) ret += (ret != "" ? ";" : "") + getNodeValue(r, ix)
		return ret;
	} else {
		return getNodeValue(r, this.tags.id);
	}
}
dropdownLoader.prototype.getRes = function() {
	return this.res;
}
dropdownLoader.prototype.getSelRes = function(p) {
	return this.res[p] ? this.res[p] : false;
}
dropdownLoader.prototype.getFieldId = function() {
	return this.fieldElm;
}
dropdownLoader.prototype.goTo = function(fieldIx,pos) {
	if (typeof pos == "undefined" || pos === null) var pos = "";
	for (var field of this.getFieldRef(pos)) {
		field.selectedIndex = fieldIx;
	}
	this.setXtraField(field.value);
	this.applyAction();
}
dropdownLoader.prototype.setupChange = function(field) {
	var th = this;
	var _field_id = field.id;
	field.addEventListener("change", function(e) {
		var _p = (typeof pos == "undefined" ? this.id.replace(_field_id,"") : pos);
		th.setXtraField(this.value,_p);
		th.applyAction(e,_p); 
	});
}
dropdownLoader.prototype.setXtraField = function(id,pos) {
	if (typeof pos == "undefined" || pos === null) var pos = "";
	var srcField = this.getFieldRef(pos)[0];
	for (var f of this.xtra_field) {
		if (srcField.form[f.field+pos])
			var field = srcField.form[f.field+pos];
		else if (document.getElementById(f.field+pos))
			var field = document.getElementById(f.field+pos);
		else 
			var field = false;
		if (typeof field == "object" && !(field instanceof HTMLElement))
			var field = field[getElmIndex(srcField)];
		if (field) {
			if (typeof f.xml_label == "function") {
				var val = f.xml_label(this.info[id]);
			} else {
				var val = getNodeValue(this.info[id], f.xml_label);
			}
			// console.log(id + "-" + f.field + " = " + val)
			var tag = field.tagName.toUpperCase();
			if (tag == "INPUT" && field.type.toUpperCase() == "CHECKBOX") {
				field.checked = (val == 1 ? true : false);
			} else if (tag == "INPUT" && field.maxLength && field.maxLength > 0) {
				field.value = val.substr(0,field.maxLength)
			} else if (tag == "INPUT" || tag == "SELECT") {
				field.value = val;
			} else {
				field.innerHTML = val;
			}
		}
	} 
}
dropdownLoader.prototype.applyAction = function(e,pos) {
	if (typeof pos == "undefined" || pos === null) var pos = "";
	for (var cmd of this.xtra_action) {
		if (typeof cmd == "function") {
			if (e) {
				cmd(pos, this.getSelected(e.target), e);
			} else {
				for (var ref of this.getFieldRef(pos)) {
					cmd(pos, this.getSelected(ref));
				}
			}
		} else if (typeof cmd == "string") {
			eval(cmd);
		}
	}
}
dropdownLoader.prototype.getSelected = function(elm) {
	if (elm.multiple) {
		var r = [];
		for (var opt of elm) {
			if (opt.selected && this.info[opt.value]) r.push(this.info[opt.value]);
		}
		return r;
	} else if (this.info[elm.value]) {
		return this.info[elm.value];
	}
}
dropdownLoader.prototype.reset = function(pos, reset_chooser) {
	if (!this.chooserBak) this.chooserBak = {};
	var c = 0;
	for (var field of this.getFieldRef(pos)) {
		//if (this.default_value == "") this.setDefault(field.value, true);
		if (this.group_label != "") {
			while (field.getElementsByTagName("OPTGROUP").length > 0) {
				var remove = field.getElementsByTagName("OPTGROUP")[0];
				remove.parentNode.removeChild(remove);
			}
		}
		if (field.tagName.toUpperCase() == "DATALIST") {
			while (field.firstChild) {
				field.removeChild(field.firstChild);
			}
		} else {
			if (!this.chooserBak[c] && field.length > 0 && field[0].value == "") this.chooserBak[c] = field[0].text;
			for (var i=field.length-1; i>=0; i--) {
				if (this.preserve_chooser && i == 0) break;
				field.remove(i);
			}
		}
		if (field.size <= 1 &&
			this.chooser == 1 &&
			this.preserve_chooser == 0 &&
			!field.multiple) {
			var oOption = document.createElement("OPTION");
			if (reset_chooser && this.chooserBak[c])
				oOption.text = this.chooserBak[c];
			else
				oOption.text = "-- " + this.getText("chooser") + " --";
			oOption.value = "";
			if (!field.options) alert(field)
			field.options.add(oOption); // IE Compatible
		}
		c++;
	}
}
dropdownLoader.prototype.setLoading = function(pos) {
	var th = this;
	for (var field of this.getFieldRef(pos)) {
		if (field.length == 0) {
			var oOption = document.createElement("OPTION");
			oOption.text = "-- " + this.getText("loading") + " --";
			oOption.value = "";
			field.options.add(oOption); // IE Compatible
			this.loaded_pos = 0;
		}
		if (this.ck_loaded == 0) {
			var colors = ["000000","111111","222222","333333","444444","555555","666666","777777","888888","999999","AAAAAA","BBBBBB","CCCCCC","DDDDDD","EEEEEE"];
			field.style.color = colors[this.loaded_pos];
			this.loaded_pos++; if (this.loaded_pos == colors.length) this.loaded_pos = 0;
			setTimeout(function() { th.setLoading(pos) },100);
		} else {
			field.style.removeProperty("color");
		}
	}
}
dropdownLoader.prototype.getFieldRef = function(pos) {
	if (typeof pos == "undefined" || pos === null) var pos = "";
	if (typeof this.fieldElm == "function") {
		var res_fn = this.fieldElm();
		var ref = [];
		for (var i=0; i<res_fn.length; i++) {
			if (typeof res_fn[i] == "object")
				ref.push(res_fn[i]);
			else if (document.getElementById(res_fn[i])) 
				ref.push(document.getElementById(res_fn[i]));
		}
		return ref;
	} else if (Array.isArray(this.fieldElm)) {
		var ref = [];
		for (var item of this.fieldElm) {
			if (item instanceof HTMLElement)
				ref.push(item);
			else if (document.getElementById(item)) 
				ref.push(document.getElementById(item));
		}
		return ref;
	} else if (this.fieldElm instanceof HTMLElement && pos.toString() != "") {
		return [ document.getElementById(this.fieldElm.id + pos) ];
	} else if (this.fieldElm instanceof HTMLElement) {
		return [ this.fieldElm ];
	} else if (this.fieldElm.indexOf("[]") > 0) {
		return document.getElementsByName(this.fieldElm);
	} else if (pos.toString() != "") {
		return [ document.getElementById(this.fieldElm + pos) ];
	} else {
		return [ document.getElementById(this.fieldElm) ];
	}
}
dropdownLoader.prototype.getText = function(val) {
	if (this.language == "pt") return this.getPtText(val);
	if (this.language == "en") return this.getEnText(val);
	if (this.language == "es") return this.getEsText(val);
}
dropdownLoader.prototype.getPtText = function(val) {
	var ref = {};
	ref.chooser = "Escolha";
	ref.loading = "Carregando";
	ref.bug_msg = "Foi detectado um erro.\nClique OK para mais informações";
	ref.change_msg = "Esta alteração irá alterar a lista de opções de um campo que já está preenchido. Deseja prosseguir?";
	ref.invalid = "Código não reconhecido";
	return ref[val];
}
dropdownLoader.prototype.getEnText = function(val) {
	var ref = {};
	ref.chooser = "Choose";
	ref.loading = "Loading";
	ref.bug_msg = "Error detected.\nClick OK for more information";
	ref.change_msg = "Operation will change other form fields. Confirm?";
	ref.invalid = "Code not found";
	return ref[val];
}
dropdownLoader.prototype.getEsText = function(val) {
	var ref = {};
	ref.chooser = "Elija";
	ref.loading = "Cargando";
	ref.bug_msg = "Se detectó un error.\nClick OK para más informaciones";
	ref.change_msg = "Esta elección va recargar el listado de opciones de un campo que ya está cargado. Desea continuar?";
	ref.invalid = "Código no reconocido";
	return ref[val];
}

// tableLoader
function tableLoader(anchor, xmlpath, params) {
	//this.obj_id = obj_id;
	if (anchor instanceof HTMLElement)
		this.anchor = anchor;
	else if (typeof anchor == "string" && document.getElementById(anchor))
		this.anchor = document.getElementById(anchor);
	else 
		alert("Invalid anchor " + anchor);
	this.xmlpath = xmlpath;
	this.params = params;
	this.link = false;
	this.link_target = false;
	this.countElm = false;
	this.res = [];
	this.rows = [];
	this.xtra_action = [];
	this.reset_action = [];
	this.labels = [];
	this.links = [];
	this.rowProp = {};
	this.ref = [];
	this.xtra_ref = [];
	this.total = -1;
	this.separator = 0;
	this.ck_accents = 1;
	this.xml_tag = "data";
	this.css = null;
	this.loadDefaults();
}
tableLoader.prototype.loadDefaults = function(val) {
	var th = this;
	this.ck_load_defaults = false;
	var scripts = document.getElementsByTagName("script");
	for (var i=0; i<scripts.length; i++) {
		src = scripts[i].src;
		if (src.indexOf("ajax_lib.v2.js") > 0) {
			var dir = src.substring(0, src.lastIndexOf('/'));
			var js = document.createElement("script");
			js.type = "text/javascript";
			js.src = dir + "/ajax_lib_defaults.js";
			//js.onreadystatechange = function() { th.loadDefaults(); };
			js.onload = function() { 
				if (typeof getTableLoaderDefaults == 'function') { 
					var cfg = getTableLoaderDefaults();
					for (var ix in cfg) {
						th[ix] = cfg[ix];
					}
					th.ck_load_defaults = true;
				}
			};
			document.getElementsByTagName('head')[0].appendChild(js);	
			break;
		}
	}
}
tableLoader.prototype.setXmlGrp = function(tag) {
	this.xml_tag = tag;
}
tableLoader.prototype.setClass = function(className) {
	this.className = className;
}
tableLoader.prototype.registerTotal = function(id) {
	this.countElm = id;
}
tableLoader.prototype.addSeparator = function(height) {
	this.separator = height;
}
tableLoader.prototype.addLabel = function(xml_label, align) {
	var ix = this.labels.length;
	this.labels.push({
		xml_label: xml_label,
		align: align,
		prop: {},
		ck_numeric: 0
	});
}
tableLoader.prototype.addLabelProp = function(prop, val, args) {
	var ix = this.labels.length - 1;
	if (typeof args === "number" || typeof args === "string") var args = [ args ];
	this.labels[ix].prop[prop] = { val:val, args:args };
}
tableLoader.prototype.setLabelNumeric = function(currency, dec, sep_dec, sep_mil) {
	var ix = this.labels.length - 1;
	this.labels[ix]["ck_numeric"] = 1;
	this.labels[ix]["num_currency"] = currency ? currency : "";
	this.labels[ix]["num_dec"] = dec ? dec : 2;
	this.labels[ix]["num_sep_dec"] = sep_dec ? sep_dec : ",";
	this.labels[ix]["num_sep_mil"] = sep_mil ? sep_mil : ".";
	this.labels[ix]["align"] = "right";
}
tableLoader.prototype.setLabelParser = function(fn) {
	var ix = this.labels.length - 1;
	this.labels[ix]["format"] = fn;
}
tableLoader.prototype.setLabelFormat = function(fn) {
	this.setLabelParser(fn);
}
tableLoader.prototype.setLabelClass = function(className) {
	var ix = this.labels.length - 1;
	this.labels[ix]["className"] = className;
}
tableLoader.prototype.setLabelLink = function(url, target, className, condition) {
	var ix = this.labels.length - 1;
	this.labels[ix]["link"] = { url:url, target:target, className:className, condition:condition };
}
tableLoader.prototype.setLabelAlignment = function(align) {
	this.setLabelAlign(align);
}
tableLoader.prototype.setLabelAlign = function(align) {
	var ix = this.labels.length - 1;
	this.labels[ix]["align"] = align;
}
tableLoader.prototype.setLabelValign = function(align) {
	var ix = this.labels.length - 1;
	this.labels[ix]["valign"] = align;
}
tableLoader.prototype.setLabelComment = function(comment, pos) {
	if (!pos || pos === false) pos = "R";
	var ix = this.labels.length - 1;
	this.labels[ix]["comment"] = comment;
	this.labels[ix]["comment_pos"] = pos;
}
tableLoader.prototype.addField = function(params, type, label, field) {
	if (typeof params == "object") {
		var field = params.field;
		var type = params.type;
		var label = params.label ? params.label : false;
		var value = params.value ? params.value : params.field.replace("[]","");
	} else {
		var value = params;
		if (!field) var field = value;
	}
	this.labels.push({ 
		field: field,
		xml_field_value: value,
		type: type,
		field_label: label,
		prop: {},
		align: "left",
		field_prop: {},
		replace: false
	});
}
tableLoader.prototype.setFieldName = function(field) {
	var ix = this.labels.length - 1;
	this.labels[ix].field = field;
}
tableLoader.prototype.addFieldProp = function(prop, val) {
	var ix = this.labels.length - 1;
	this.labels[ix].field_prop[prop] = (val ? val : false);
}
tableLoader.prototype.setFieldList = function(list) {
	var ix = this.labels.length - 1;
	this.labels[ix]["list"] = list;
}
tableLoader.prototype.setLink = function(url, target, style) {
	this.link = url;
	this.link_target = target ? target : "_self";
	this.link_style = style ? style : { backgroundColor: "#FFFFAA" };
}
tableLoader.prototype.addLink = function(img, url, target) {
	this.links.push({
		img: img,
		url: url,
		target: target
	});
	//for (var ix in this.labels) this.labels[ix]["valign"] = "middle";
}
tableLoader.prototype.setLinkCondition = function(cmd) {
	var ix = this.links.length-1;
	this.links[ix].condition = cmd;
}
tableLoader.prototype.addRowProp = function(prop, val, condition) {
	this.rowProp[prop] = { val: val, condition: condition };
}
tableLoader.prototype.addAction = function(cmd) {
	if (this.xtra_action.indexOf(cmd) < 0) this.xtra_action.push(cmd);
}
tableLoader.prototype.removeActions = function() {
	this.xtra_action = [];
}
tableLoader.prototype.addResetAction = function(cmd) {
	if (this.reset_action.indexOf(cmd) < 0) this.reset_action.push(cmd);
}
tableLoader.prototype.initBuild = function(ck_append) {
	if (this.anchor.tagName == "TABLE") {
		this.tbl_pos = -1; // -1 = at the en of the table
		this.tbody = this.anchor.getElementsByTagName("TBODY")[0];
		if (!this.tbody) this.tbody = this.anchor.appendChild(document.createElement("TBODY"));
	} else if (this.anchor.tagName == "TBODY") {
		this.tbl_pos = -1; // -1 = at the en of the table
		this.tbody = this.anchor;
	} else if (this.anchor.tagName == "TR") {
		if (this.countElm) var countElm = typeof this.countElm == "object" ? this.countElm : document.getElementById(this.countElm);
		if (ck_append && countElm.value > 0)
			this.tbl_pos = document.getElementById(this.anchor.id + "-" + (countElm.value-1)).rowIndex + 1;
		else
			this.tbl_pos = this.anchor.rowIndex + 1;
		this.tbody = this.anchor.parentNode;
	}
}
tableLoader.prototype.addRow = function(res) {
	this.initBuild();
	var c = this.res.length;
	this.res[c] = res;
	this.buildRow(res, c);
}
tableLoader.prototype.load = function(ck_load, ck_append) {
	var th = this;
	// limpa dropdown de destino
	if (!this.anchor) return false;
	if (document.getElementById(this.anchor.id+"loading")) {
		return false;
	} else if (document.getElementById(this.anchor.id+"-0") && !ck_append) {
		this.reset();
		if (!ck_load) return false;
	}
	if (!ck_append) this.reset();
	// add loading warning
	this.initBuild(ck_append);
	this.loaderRow = this.tbody.insertRow(this.tbl_pos);
	this.loaderRow.id = this.anchor.id + "loading";
	var cell = document.createElement("TD");
	cell.appendChild(document.createTextNode("Carregando..."));
	cell.style.textDecoration = "blink";
	cell.style.textAlign = "center";
	cell.colSpan = this.labels.length;
	cell.className = this.className;
	this.loaderRow.appendChild(cell);
	// AJAX request
	var xhr =  new XMLHttpRequest();
	var url = parseUrl(this.xmlpath);
	if (this.ck_accents == 0) url = url.removeAccents();
	//window.open(url,"_blank");
	xhr.open('POST', url, true);
	if (this.params) xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function() {
		//if (xhr.readyState == 4 && xhr.status == 200) {
		if (xhr.readyState == 4) {
			/* if (xhr.responseXML == null &&
				confirm("Foi detectado um erro.\nClique OK para mais informações")) 
				alert(xhr.responseText); */
			// add lines
			if (xhr.responseXML) {
				th.xml = xhr.responseXML;
				th.res = xhr.responseXML.getElementsByTagName(th.xml_tag);
			} else {
				th.res = JSON.parse(xhr.response).res;
			}
			if (th.countElm) var countElm = typeof th.countElm == "object" ? th.countElm : document.getElementById(th.countElm);
			th.total = ck_append ? parseFloat(countElm.value) : 0;
			for (var i=0; i<th.res.length; i++) {
				th.buildRow(th.res[i], th.total);
				th.total++;
			}
			// register total 
			if (th.countElm) countElm.value = th.total;
			// remove loading warning
			th.loaderRow.parentNode.removeChild(th.loaderRow);
			// apply actions
			for (var i=0; i<th.xtra_action.length; i++) {
				var action = th.xtra_action[i];
				if (typeof action == "function") {
					action(th.res, xhr.responseXML ? xhr.responseXML : xhr.response);
				} else {
					eval(action);
				}
			}
		}
	}
	xhr.send(this.params ? this.params : null);
}
tableLoader.prototype.buildRow = function(res,i) {
	var th = this;
	if (this.separator > 0) {
		//var row = this.tbody.insertRow(this.tbl_pos); 
		var row = document.createElement("TR");
		this.tbody.insertBefore(row, this.loaderRow);
		if (this.tbl_pos >= 0) this.tbl_pos++;
		row.id = this.anchor.id + "-sep" + i;
		var cell = document.createElement("TD");
		cell.style.height = this.separator;
		row.appendChild(cell);
	}
	//var row = this.tbody.insertRow(this.tbl_pos);
	var row = document.createElement("TR");
	this.rows.push(row);
	this.tbody.insertBefore(row, this.loaderRow);
	if (this.tbl_pos >= 0) this.tbl_pos++;
	row.id = this.anchor.id + "-" + i;
	row.className = this.className;
	for (key in this.rowProp) {
		var prop = this.rowProp[key];
		var val = prop.val;
		if (!prop.condition || prop.condition(res)) {
			this.setElmProp(row, key, val, res);
		}
	}
	if (this.link) {
		row.style.cursor = "pointer";
		row.onmouseover = function(e) {
			if (e.target.tagName != "TD") return;
			var tds = this.getElementsByTagName("TD");
			for (var j=0; j<tds.length; j++) {
				for (var ix in th.link_style) {
					tds[j].style[ix] = th.link_style[ix];
				}
			}
		}
		row.onmouseout = function(e) {
			if (e.target.tagName != "TD") return;
			var tds = this.getElementsByTagName("TD");
			for (var j=0; j<tds.length; j++) {
				for (var ix in th.link_style) {
					tds[j].style[ix] = null;
				}
			}
		}
		row.onclick = function(e) {
			if (e.target.tagName != "TD") return;
			if (typeof th.link == "function") {
				th.link(res);
			} else {
				var url = th.link;
				var ix = this.id.replace(th.anchor.id + "-", "");
				var match = url.match(/\[([\(\)_A-Za-z0-9-]+)\]/g);
				if (match != null) {
					for (var m of match) {
						var tag = m.substring(1, m.length-1);
						var url = url.replace(m, getResVal(res, tag));
					}
				}
				window.open(url, e.ctrlKey ? "_blank" : th.link_target);
			}
		}
	}
	// cells
	var col = 0;
	for (var labelObj of this.labels) {
		var ck_new_td = 1;
		if (labelObj.field) {
			var _type = labelObj.type;
			if (labelObj.field.substr(-2) == "[]") {
				var _id = labelObj.field.replace("[]","") + i;
				var _name = labelObj.field;
			} else {
				var _id = labelObj.field + i;
				var _name = labelObj.field + i;
			}
			if (typeof labelObj.xml_field_value == "function") {
				var _value = labelObj.xml_field_value(res);
			} else {
				var _value = getResVal(res, labelObj.xml_field_value);
			}
			var _prop = labelObj.field_prop;
			var _list = labelObj.list;
			var _label = labelObj.field_label;
			if (_type == "hidden" && col > 0) var ck_new_td = 0;
		}
		if (ck_new_td == 1) {
			var cell = document.createElement("TD");
			row.appendChild(cell);
			// alignment
			if (labelObj.align) cell.style.textAlign = labelObj.align;
			if (labelObj.valign) cell.style.verticalAlign = labelObj.valign;
			// style
			if (labelObj.className) cell.className = labelObj.className;
			for (key in labelObj.prop) {
				var prop = labelObj.prop[key];
				this.setElmProp(cell, key, prop.val, res, getResVal(res, labelObj.xml_label), prop.args);
			};
		}
		// comment before
		if (labelObj.comment && labelObj.comment_pos == "L")
			cell.appendChild(document.createTextNode(labelObj.comment + " "));
		if (labelObj.label) {
			cell.appendChild(document.createTextNode(labelObj.label));
		} else if (labelObj.xml_label) {
			// label
			if (typeof labelObj.xml_label == "function") {
				var val = labelObj.xml_label(res,i);
				if (val instanceof Element)
					cell.appendChild(val);
				else 
					cell.appendChild(document.createTextNode(val));
			} else {
				var val = getResVal(res, labelObj.xml_label);
				if (labelObj.format) {
					val = labelObj.format(val,res);
					cell.appendChild(document.createTextNode(val));
				} else if (labelObj.ck_numeric == 1) {
					val = labelObj.num_currency + " " + formatnum(val, labelObj.num_dec, labelObj.num_sep_dec, labelObj.num_sep_mil);
					cell.appendChild(document.createTextNode(val));
				} else {
					var c = 0;
					for (var part of val.split("\n")) {
						if (c > 0) cell.appendChild(document.createElement("BR"));
						cell.appendChild(document.createTextNode(part));
						c++;
					}
					// parseText(cell) em desenvolvimento
				}
			}
			if (labelObj.link) {
				var ref = labelObj.link;
				if (!ref.condition || ref.condition(val)) {
					var a = document.createElement("A");
					if (typeof ref.url == "function") {
						var url = ref.url(res);
					} else {
						var url = ref.url;
						var match = url.match(/\[([\(\)_A-Za-z0-9-]+)\]/g);
						if (match != null) {
							for (var m of match) {
								var ix = m.substring(1, m.length-1);
								var url = url.replace(m, getResVal(res, ix));
							}
						}
					}
					a.href = url;
					a.target = ref.target;
					if (ref.className) a.className = ref.className;
					a.appendChild(cell.firstChild);
					cell.appendChild(a);
				}
			}
		} else if (labelObj.field) {
			if (_type == "date-text") {
				if (_value.indexOf("/") >= 0) {
					var temp = _value.split("/");
					if (temp.length == 3) {
						var d = temp[0]; var m = temp[1]; var y = temp[2];
					} else {
						var d = ""; var m = ""; var y = "";
					}
				} else if (_value.indexOf("-") >= 0) {
					var temp = _value.split("-");
					if (temp.length == 3) {
						var d = temp[2]; var m = temp[1]; var y = temp[0];
					} else {
						var d = ""; var m = ""; var y = "";
					}
				} else {
					var d = ""; var m = ""; var y = "";
				}
				var field = getField({ type: "text", id: "dia_" + _id, name: "dia_" + _name, val: d, prop: _prop, res: res });
				field.size = field.maxLength = 2;
				field.onfocus = function() { recvalue(this); };
				field.onkeyup = function() { gotofield(this.form,this); };
				cell.appendChild(field);
				cell.appendChild(document.createTextNode(" / "));
				var field = getField({ type: "text", id: "mes_" + _id, name: "mes_" + _name, val: m, prop: _prop, res: res });
				field.size = field.maxLength = 2;
				field.onfocus = function() { recvalue(this); };
				field.onkeyup = function() { gotofield(this.form,this); };
				cell.appendChild(field);
				cell.appendChild(document.createTextNode(" / "));
				var field = getField({ type: "text", id: "ano_" + _id, name: "ano_" + _name, val: y, prop: _prop, res: res });
				field.size = field.maxLength = 4;
				cell.appendChild(field);
				cell.appendChild(document.createTextNode(" "));
				var span = document.createElement("SPAN");
				span.id = "cal" + _id;
				cell.appendChild(span);
				addCalendar({ obj:span, id:"_"+_id});
				/*if (typeof getCalendar == "function") {
					cell.appendChild(document.createTextNode(" "));
					var span = document.createElement("SPAN");
					span.innerHTML = getCalendar("_"+_id);
					cell.appendChild(span);
				}*/
				cell.noWrap = true;
			} else if (_type == "checkbox") {
				var label = document.createElement("LABEL");
				cell.appendChild(label);
				var field = getField({ type: _type, id: _id, name: _name, val: _value, prop: _prop, res: res });
				label.appendChild(field);
			} else if (_type == "radio_checkbox") {
				if (!_prop["onclick"]) _prop["onclick"] = "";
				_prop["onclick"] = (_prop["onclick"] != "" ? ";" : "") + _id + ".value=this.checked?this.value:'';";
				for (item in labelObj.list) {
					var _onclick = _prop["onclick"]; // backup de prop antes de incorporar acoes
					for (item_review in labelObj.list) {
						if (item != item_review) {
							_prop["onclick"] += labelObj.field + item_review + i + ".checked=false;";
						}
					}
					var field = getField({ type: "checkbox", id: labelObj.field + item + i, val: item, prop: _prop, res: res });
					_prop["onclick"] = _onclick; // restaura prop ao estado antes de incorporar acoes
					cell.appendChild(field);
					cell.appendChild(document.createTextNode(labelObj.list[item]));
				}
				var field = getField({ type: "hidden", id: _id, name: _name });
				cell.appendChild(field);
			} else {
				var field = getField({ type: _type, id: _id, name: _name, val: _value, prop: _prop, label:_label, list:_list, res: res });
				cell.appendChild(field);
			}
			if (_label && _type != "select") {
				var holder = _type == "checkbox" ? label : cell;
				// comment before
				if (labelObj.comment && labelObj.comment_pos == "L")
					holder.appendChild(document.createTextNode(labelObj.comment));
				// label
				if (getResVal(res, _label) != "")
					holder.appendChild(document.createTextNode(getResVal(res, _label)));
				else
					holder.appendChild(document.createTextNode(_label));
				// comment after
				if (labelObj.comment && labelObj.comment_pos == "R")
					holder.appendChild(document.createTextNode(labelObj.comment));
			}
		}
		// comment after
		if (labelObj.comment && labelObj.comment_pos == "R")
			cell.appendChild(document.createTextNode(" " + labelObj.comment));
		col++;
	}
	for (var l of this.links) {
		var cell = document.createElement("TD");
		cell.style.width = 0;
		row.appendChild(cell);
		var url = l.url;
		if (typeof url == "function" && l.target) var url = url(res);
		if (url && (!l.condition || l.condition(res))) {
			var a = document.createElement("A");
			if (typeof url == "function") {
				a.onclick = function() { url(res); };
				a.href = "javascript:void(null)";
			} else {
				var matches = url.match(/\[([\(\)_A-Za-z0-9-]+)\]/g);
				if (matches != null) {
					for (var match of matches) {
						var ix = match.substring(1, match.length-1);
						var url = url.replace(match, getResVal(res, ix));
					}
				}
				a.href = url;
				if (l.target) a.target = l.target;
			}
			cell.appendChild(a);
			var img = document.createElement("IMG");
			img.src = l.img;
			a.appendChild(img);
		} else {
			var img = document.createElement("IMG");
			img.src = l.img;
			img.style.opacity = .2;
			cell.appendChild(img);
		}
	}
}
tableLoader.prototype.setElmProp = function(elm, key, val, res, cellValue, args) {
	if (typeof val == "function" && key.substr(0,2) != "on") { // onclick, onmouseover, etc
		if (args)
			var val = val.apply(this, [res, cellValue].concat(args));
		else
			var val = val(res, cellValue);
	} else if (typeof val == "string" && val.substring(0,1) == "[" && val.substring(val.length-1) == "]") {
		var val = getResVal(res, val.substring(1,val.length-1));
	}
	var parts = key.split(".");
	if (typeof val == "function") {
		elm[key] = function(e) { val(this,res,e) };
	} else if (parts[0] == "style") {
		elm.style[parts[1]] = val;
	} else {
		elm.setAttribute(key,val);
	}
}
tableLoader.prototype.reset = function() {
	var tr_id = this.anchor.id;
	while (document.getElementById(tr_id+"-0")) {
		if (tr_id != this.anchor.id) { // recursive delete
			var c = 0;
			while (document.getElementById(tr_id+"-0")) {
				this.removeRows(tr_id);
				c++;
				tr_id = tr_id.substr(0,tr_id.lastIndexOf("-"))+"-"+c; // replace counter
			}
			tr_id = tr_id.substr(0,tr_id.lastIndexOf("-"))+"-0"; // reset counter
		} else {
			this.removeRows(tr_id);
		}
		tr_id += "-0";
	}
	for (var i=0; i<this.reset_action.length; i++) {
		this.reset_action[i]();
	}
	if (this.countElm) {
		var countElm = typeof this.countElm == "object" ? this.countElm : document.getElementById(this.countElm);
		countElm.value = 0;
	}
	this.rows = [];
	this.total = -1;
}
tableLoader.prototype.getTotal = function() {
	return this.total;
}
tableLoader.prototype.getSum = function(col, condition) {
	var sum = 0;
	for (var r of this.res) {
		if (!condition || condition(r)) sum += parseFloat(getResVal(r, col));
	}
	return sum;
}
tableLoader.prototype.getRows = function() {
	return this.rows;
}
tableLoader.prototype.getXml = function() {
	return this.xml;
}
tableLoader.prototype.removeRows = function(tr_id) {
	c = 0;
	while (document.getElementById(tr_id+"-"+c)) {
		if (this.separator > 0) {
			var remove = document.getElementById(tr_id+"-sep"+c);
			remove.parentNode.removeChild(remove);
		}
		var remove = document.getElementById(tr_id+"-"+c);
		remove.parentNode.removeChild(remove);
		c++;
	}
}

// other ajax calls
function fn_set_comment(field_id, xmlpath) {
	// AJAX request
	var xhr =  new XMLHttpRequest();
	//xhr.open('POST', xmlpath, true);
	xhr.open('POST', xmlpath, true);
	//alert(xmlpath);
	xhr.onreadystatechange = function() {
		/* if (xhr.responseXML == null &&
			confirm("Foi detectado um erro:\n\n" + this.getAllResponseHeaders() + "Clique OK para mais informações")) 
			window.open(xmlpath, '_blank'); */
		//if (xhr.readyState == 4 && xhr.status == 200) {
		if (xhr.readyState == 4) {
			var res = xhr.responseXML.getElementsByTagName('data');
			var str = "<br>";
			for (var i=0; i<res.length; i++) {
				str += getNodeValue(res[i], 'text') + "\n";
			}
			document.getElementById("comment_" + field_id).innerHTML = str;
		}
	}
	xhr.send(null);
}
// support functions
function getResVal(r,ix) {
	if (typeof ix == "function") {
		return ix(r);
	} else if (r instanceof Element) { // XML
		return getNodeValue(r,ix);
	} else { // JSON
		return r[ix] ? r[ix] : "";
	}
}
function getNodeValue(obj,tag) {
	//if (!obj.getElementsByTagName(tag)[0].firstChild) console.log(obj + ";" + tag)
	if (obj &&
		obj.getElementsByTagName(tag) && 
		obj.getElementsByTagName(tag).length > 0) {
		var str = "";
		for (var node of obj.getElementsByTagName(tag)[0].childNodes) {
			if (node.nodeType == Node.TEXT_NODE || node.nodeType == Node.CDATA_SECTION_NODE) str += node.nodeValue;
		}
		return str;
	} else
		return "";
}
function setNodeValue(obj,tag,val) {
	//if (!obj.getElementsByTagName(tag)[0].firstChild) console.log(obj + ";" + tag)
	if (obj &&
		obj.getElementsByTagName(tag) && 
		obj.getElementsByTagName(tag).length > 0) {
		while (obj.getElementsByTagName(tag)[0].hasChildNodes()) {
			obj.getElementsByTagName(tag)[0].removeChild(obj.getElementsByTagName(tag)[0].firstChild);
		}
		obj.getElementsByTagName(tag)[0].appendChild(document.createTextNode(val));
	}
}
/* Em desenvolvimento, funcao para interpretar tags dentro do texto
function parseText(elm) {
	console.log(elm.innerText)
	var match = elm.innerText.match(/<[a-z]*[^>]*>(.*?)<\/[a-z]*>/gi);
	console.log(match);
	if (match != null) {
		var range = new Range();
		range.setStart(elm, 8)
		range.setEnd(elm, 12)
		range.surroundContents(document.createElement("B"))
	}
}
*/
function disableEnterKey(e) {
	 var key;
	 if (window.event)
		  key = window.event.keyCode; //IE
	 else
		  key = e.which; //firefox
	 if (key == 13)
		  return false;
	 else
		  return true;
}
function getField(params) {
	if (params.type.toLowerCase() == "select") {
		var field = document.createElement("SELECT");
		var option = document.createElement("OPTION");
		option.value = "";
		option.text = "-- " + (params.label ? params.label : "Escolha") + " --";
		field.appendChild(option);
		for (item in params.list) {
			var option = document.createElement("OPTION");
			option.value = item;
			option.text = params.list[item];
			field.appendChild(option);
		}
	} else if (params.type.toLowerCase() == "radio") {
		var div = document.createElement("DIV");
		for (item in params.list) {
			var label = document.createElement("LABEL");
			div.appendChild(label);
			var field = document.createElement("INPUT");
			field.type = "radio";
			field.name = params.name ? params.name : params.id;
			field.value = item;
			if (field.value == params.val) field.checked = true;
			for (key in params.prop) {
				if (params.prop[key] && key.substring(0,2) == "on" && typeof params.prop[key] == "function") {
					field.addEventListener(key.substring(2), params.prop[key]);
				} else if (typeof params.prop[key] == "function") {
					field[key] = params.prop[key](params.res);
				} else {
					var parts = key.split(".");
					if (parts.length == 2)
						field[parts[0]][parts[1]] = params.prop[key];
					else
						field[key] = params.prop[key];
				}
			}
			label.appendChild(field);
			label.appendChild(document.createTextNode(params.list[item]));
		}
		return div;
	} else {
		var field = document.createElement("INPUT");
		field.type = params.type;
	}
	field.className = "formpeq";
	field.id = params.id;
	field.name = params.name ? params.name : params.id;
	field.value = params.val ? params.val : "";
	for (key in params.prop) {
		if (params.prop[key] && key.substring(0,2) == "on" && typeof params.prop[key] == "function") {
			field.addEventListener(key.substring(2), params.prop[key]);
		// } else if (params.prop[key] && key.substring(0,2) == "on") {
		//	eval("field." + key + " = function() { " + params.prop[key] + " }");
		} else if (typeof params.prop[key] == "function") {
			field[key] = params.prop[key](params.res);
		} else {
			var parts = key.split(".");
			if (parts.length == 2)
				field[parts[0]][parts[1]] = params.prop[key];
			else
				field[key] = params.prop[key];
		}
	};
	return field;
}
function parseXmlPath(url, pos, src, return_found) { // depracated, remover apos 25/07/2023
	return parseUrl(url, pos, src, return_found);
}
function parseUrl(url, pos, src, return_found) {
	if (url.indexOf("[") >= 0) {
		var ck_field = 0;
		var match = url.match(/\[\!{0,1}([\(\)\-_A-Za-z0-9,]+)(\?)*\]/g);
		if (match != null) {
			for (var m of match) {
				var ix  = m.substring(1, m.length-1);
				if (ix.substr(0,1) == "!") {
					var ix = ix.substr(1);
					var bool = false;
				} else {
					var bool = true;
				}
				var ixForm = ix.replace(/\([_A-Za-z0-9-]*\)/g,""); // remove prefix
				var matchPad = ixForm.match(/,([0-9]+)?/);
				if (matchPad) ixForm = ixForm.replace(matchPad[0],"");
				var ixEntity = ix.replace(/\(|\)/g,"");
				if (ixEntity.substr(-1) == "?") {
					ixEntity = ixEntity.substr(0,ixEntity.length-1);
					var pos = -1;
					while (document.getElementById(ixEntity+(pos+1))) {
						pos++;
					}
				}
				if (src && pos !== false && src[ixEntity+pos])
					var obj = src[ixEntity+pos];
				else if (src && src[ixForm])
					var obj = src[ixForm];
				else if (pos !== false && document.getElementById(ixEntity+pos))
					var obj = document.getElementById(ixEntity+pos);
				else if (document.getElementById(ixForm))
					var obj = document.getElementById(ixForm);
				else if (document.getElementsByName(ixForm).length > 0)
					var obj = document.getElementsByName(ixForm)[0];
				else
					var obj = false;
				if (obj) {
					if (obj.type == "checkbox") {
						var ck = obj.checked;
						if (!bool) var ck = !ck;
						var url = url.replace(m.toString(), ck ? 1 : 0);
						var ck_field = 1;
					} else if (obj.type == "radio") {
						var radios = document.getElementsByName(obj.name);
						var ck = 0;
						for (var j=0; j<radios.length; j++) {
							if (radios[j].checked) {
								var url = url.replace(m.toString(), radios[j].value);
								var ck_field = 1;
								var ck = 1;
								break;
							}
						}
						if (ck == 0) var url = url.replace(m.toString(), "");
					} else {
						var val = obj.value;
						if (matchPad) var val = val.pad(matchPad[1],'0','L')
						var url = url.replace(m.toString(), val);
						if (obj.value != "") var ck_field = 1;
					}
				}
			}
		} else {
			var ck_field = 1;
		}
	} else {
		var ck_field = 1;
	}
	var re = /[a-z_]+\(.*\)+/i;
	while (url.match(re)) {
		var cmd = url.match(re)[0];
		var url = url.replace(cmd, eval(cmd));
	}
	return return_found ? { parsed: url, hasValue: ck_field } : url;
}
function makeId(length) {
   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}
function getBuilder(ix) {
	return _globals._builders[ix];
}
function getElmIndex(src) {
	var c = 0;
	for (var elm of document.getElementsByName(src.name)) {
		if (elm == src) return c;
		c++;
	}
}