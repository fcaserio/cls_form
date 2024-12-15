var res;
function menuLib(user, dt_login) {
	var th = this;
	this.user = user;
	this.dt_login = dt_login;
	this.opt = [];
	this.images = [];
	this.ck_trigger_menu = "OFF";
	this.ck_trigger_submenu = "OFF";
	this.asked_menu = "";
	this.asked_submenu = "";
	this.selected_menu = "";
	this.selected_submenu = "";
	this.prefix = "";
	this.top = 0;
	this.ck_hide_disabled = 0;
	this.ck_hide_logo = 0;
	this.ck_bodyclick = 1;
	this.ck_debug = 0;
	// this.setImage("logo", "../images/verpro.png");
	// this.setImage("logo-cliente", "../images/logo-cliente.png");
	this.setImage("bg", "../images/bg/bg.gif");
	this.setImage("box_left", "../images/bg/box_left." + (navigator.userAgent.match(/(iPad|iPhone|iPod)/i)?"gif":"png"));
	this.setImage("box_right", "../images/bg/box_right." + (navigator.userAgent.match(/(iPad|iPhone|iPod)/i)?"gif":"png"));
	this.setImage("box_bg", "../images/bg/box_bg." + (navigator.userAgent.match(/(iPad|iPhone|iPod)/i)?"gif":"png"));
	this.setImage("box_logo_left", "../images/bg/box_logo_left." + (navigator.userAgent.match(/(iPad|iPhone|iPod)/i)?"gif":"png"));
	this.setImage("box_logo_right", "../images/bg/box_logo_right." + (navigator.userAgent.match(/(iPad|iPhone|iPod)/i)?"gif":"png"));
	this.setImage("box_logo_bg", "../images/bg/box_logo_bg." + (navigator.userAgent.match(/(iPad|iPhone|iPod)/i)?"gif":"png"));
	this.setImage("icon_datetime", "../images/icon_datetime.png");
	this.setImage("arrow-down", "../images/arrow-down.png");
	// this.setImage("icon-logoff-s", "../images/icon_logoff.png");
	this.setImage("icon-generic-m", "../images/icon-generic-m.png");
	this.setImage("icon-generic-s", "../images/icon-generic-s.png");
}
menuLib.prototype.hideDisabled = function() {
	this.ck_hide_disabled = 1;
}
menuLib.prototype.hideLogo = function() {
	this.ck_hide_logo = 1;
}
menuLib.prototype.setTop = function(_top) {
	this.top = _top;
}
menuLib.prototype.setUrlCliente = function(www) {
	this.www_cliente = www;
}
menuLib.prototype.setPrefix = function(str) {
	this.prefix = str;
	for (i in this.images) {
		this.setImage(i, this.images[i]);
	}
}
menuLib.prototype.setImage = function(param, val) {
	this.images[param] = this.prefix + val;
	MM_preloadImages(val);
}
menuLib.prototype.reset = function() {
	this.opt = [];
}
menuLib.prototype.add = function(params) {
	/* params: grp, url (opcional), submenu (opcional), icon (opcional), label, alt (opcional), size */
	if (!params.size) params.size = "m";
	if (params.size == "s" && !('alt' in params)) { // params.alt considera string vazia como false
		params.alt = params.label.replace(/[^A-Z]/g,"");
		if (params.alt.length == 1) params.alt = params.label.substr(0,3);
	}
	params.icon = params.icon ? this.prefix + params.icon : false;
	this.opt.push(params);
}
menuLib.prototype.build = function(id_menu) {
	var th = this;
	this.id_menu = id_menu;
	if (document.getElementById("menu-container")) document.getElementById("menu-container").parentNode.removeChild(document.getElementById("menu-container"));
	for (var i=this.opt.length-1; i>=0; i--) {
		// remove opt when hide disabled
		if (this.ck_hide_disabled && !this.opt[i].submenu && !this.opt[i].url) {
			this.opt.splice(i,1);
		}
		// transforma submenu em link se houver apenas um submenu
		if (this.opt[i] &&
			this.opt[i].submenu && this.opt[i].submenu.opt.length == 1) { 
			this.opt[i].url = this.opt[i].submenu.opt[0].url;
			this.opt[i].submenu = false;
		}
	}
	//this.add({ grp: "Sessão" });
	//this.add({ grp: "Logo" });
	this.container = document.createElement("DIV");
	this.container.id = "menu-container";
	this.container.className = "noprint";
	this.container.style.position = "fixed";
	this.container.style.zIndex = 1000;
	this.container.style.left = 0;
	this.container.style.padding = "2px";
	this.container.style.top = this.top + "px";
	this.container.style.height = "86px"; 
	this.container.style.width = "100%"; // (navigator.userAgent.indexOf("MSIE") < 0 ? window.innerWidth : document.body.offsetWidth);
	this.container.style.background = "url(" + this.images["bg"] + ")";
	this.container.style.backgroundColor = "silver";
	this.container.style.textAlign = "left";
	//this.container.style.padding = "2px";
	document.body.appendChild(this.container);
	// container
	var div = document.createElement("DIV");
	this.container.appendChild(div);
	div.style.display = "table-cell";
	div.style.width = "100%";
	div.style.textAlign = "left";
	div.style.verticalAlign = "top";
	var tbl = document.createElement("TABLE");
	div.appendChild(tbl);
	//tbl.style.cssFloat = tbl.style.styleFloat = "left";
	tbl.cellPadding = tbl.cellSpacing = tbl.border = 0;
	tbl.style.background = "url(" + this.images["box_bg"] + ")";
	var tbody = document.createElement("TBODY");
	tbl.appendChild(tbody);
	var tr1 = document.createElement("TR");
	tbody.appendChild(tr1);
	var tr2 = document.createElement("TR");
	tbody.appendChild(tr2);
	// logo
	var td = document.createElement("TD");
	td.rowSpan = 2;
	tr1.appendChild(td);
	/*
	var img = document.createElement("IMG");
	img.src = this.images["logo"];
	img.style.cursor = "pointer";
	img.onclick = function() { window.open("../", "_self") };
	td.appendChild(img);
	*/
	// icons
	for (var i=0; i<this.opt.length; i++) {
		if (i == 0 || this.opt[i]["grp"] != this.opt[i-1]["grp"]) {
			// usa icone grande se é o unico do grupo
			if (i == this.opt.length-1 || this.opt[i]["grp"] != this.opt[i+1]["grp"]) {
				this.opt[i].size = "m";
				this.opt[i].label = this.opt[i].label.replace("\\n", "\n");
			}
			// dom grupo
			var prefix = this.opt[i]["grp"] == "Logo" ? "box_logo" : "box";
			var td = document.createElement("TD");
			td.rowSpan = 2;
			tr1.appendChild(td);
			var img = document.createElement("IMG");
			img.src = this.images[prefix + "_left"];
			td.appendChild(img);
			var td = document.createElement("TD");
			if (this.opt[i]["grp"] == "Logo") td.rowSpan = 2;
			td.style.background = "url(" + this.images[prefix + "_bg"] + ")";
			td.className = "icon_grp";
			tr1.appendChild(td);
			this.cur_container = td;
			var td = document.createElement("TD");
			td.rowSpan = 2;
			tr1.appendChild(td);
			var img = document.createElement("IMG");
			img.src = this.images[prefix + "_right"];
			td.appendChild(img);
			if (this.opt[i]["grp"] != "Logo") {
				var td = document.createElement("TD");
				td.style.textAlign = "center";
				td.style.fontFamily = "Verdana";
				td.style.fontSize = "7pt";
				td.style.color = "#3e6aaa";
				//td.style.backgroundColor = "#c1d9f0";
				td.appendChild(document.createTextNode(this.opt[i]["grp"]));
				tr2.appendChild(td);
			}
		}
		if (this.opt[i]["grp"] == "Logo") {
			var a = document.createElement("A");
			this.cur_container.appendChild(a);
			a.href = this.www_cliente; // this.prefix + "index.php";
			a.target = "_blank";
			var img = document.createElement("IMG");
			a.appendChild(img);
			img.src = this.images["logo-cliente"];
			img.style.maxWidth = "100px";
			img.style.maxHeight = "70px";
		} else {
			if (this.opt[i].size == "m" ||
				i == 0 || this.opt[i-1].size != "s" || (this.opt[i-1].size == "s" && (i-div.id.replace("icon_container",""))%3 == 0)) {
				var div = document.createElement("DIV");
				this.cur_container.appendChild(div);
				div.className = "icon_container";
				div.id = "icon_container" + i;
			}
			if (!this.ck_hide_disabled || this.opt[i].url || this.opt[i].submenu) { // debug
				if (this.opt[i].size == "m") {
					div.appendChild(this.getIcon(this.opt[i], i));
					if (this.opt[i].submenu) div.appendChild(this.getMenu(i, true));
				} else if (this.opt[i].size == "s") {
					div.appendChild(this.getSmallIcon(this.opt[i], i));
				}
			}
		}
	}
	// logo cliente
	var div = document.createElement("DIV");
	this.container.appendChild(div);
	div.style.display = "table-cell";
	div.style.paddingRight = "4px";
	if (this.ck_hide_logo == 0) {
		var tbl_logo = document.createElement("TABLE");
		div.appendChild(tbl_logo);
		tbl_logo.cellPadding = tbl_logo.cellSpacing = tbl_logo.border = 0;
		tbl_logo.style.background = "url(" + this.images["box_bg"] + ")";
		//tbl_logo.style.cssFloat = tbl_logo.style.styleFloat = "right";
		//tbl_logo.style.marginLeft = "-2px";
		var tbody = document.createElement("TBODY");
		tbl_logo.appendChild(tbody);
		var tr = document.createElement("TR");
		tbody.appendChild(tr);
		var td = document.createElement("TD");
		tr.appendChild(td);
		var img = document.createElement("IMG");
		img.src = this.images["box_logo_left"];
		td.appendChild(img);
		var td = document.createElement("TD");
		td.style.background = "url(" + this.images["box_logo_bg"] + ")";
		td.className = "icon_grp";
		tr.appendChild(td);
		var a = document.createElement("A");
		a.href = this.www_cliente; // this.prefix + "index.php";
		a.target = "_blank";
		td.appendChild(a);
		var img = document.createElement("IMG");
		a.appendChild(img);
		img.src = this.images["logo-cliente"];
		img.border = 0;
		img.style.maxWidth = "100px";
		img.style.maxHeight = "70px";
		var td = document.createElement("TD");
		tr.appendChild(td);
		var img = document.createElement("IMG");
		img.src = this.images["box_logo_right"];
		td.appendChild(img);
	}
	// ajusta a barra de navegação
	var c = 0;
	this.adjustMenu();
	//setTimeout(function() { th.adjustMenu(); }, 100);
	window.addEventListener("resize", function() { 
		if (typeof __controller != "undefined") clearTimeout(__controller);
		__controller = setTimeout(function(){ th.adjustMenu(); }, 100);
	}, false);
}
menuLib.prototype.adjustMenu = function() {
	var th = this;
	var bar_w = 0;
	if (!this.container) return;
	for (var i=0; i<this.container.childNodes.length; i++) {
		bar_w += this.container.childNodes[i].offsetWidth;
	}
	//console.log(this.id_menu + ": " + bar_w + " > " + this.container.offsetWidth + " > " + window.innerWidth)
	var icons = this.container.getElementsByClassName("icon_small");
	if (bar_w > this.container.offsetWidth) {
		for (var i=0; i<icons.length; i++) {
			icons[i].style.marginRight = "8px";
			if (icons[i].className.indexOf("disabled") < 0) {
				icons[i].onmouseover = function() {
					var ix = this.id.replace("_icon","");
					var alt = this.getElementsByClassName("alt");
					if (alt.length > 0) alt[0].innerHTML = th.opt[ix].label.replace("\\n"," ");
				}
				icons[i].onmouseout = function() {
					var ix = this.id.replace("_icon","");
					var alt = this.getElementsByClassName("alt");
					if (alt.length > 0) alt[0].innerHTML = th.opt[ix].alt;
				}
			}
			var ix = icons[i].id.replace("_icon","");
			var div = icons[i].getElementsByTagName("div")[0];
			div.innerHTML = this.opt[ix].alt;
			div.className = "alt";
		}
	} else {
		for (var i=0; i<icons.length; i++) {
			icons[i].style.marginRight = 0;
			var ix = icons[i].id.replace("_icon","");
			var div = icons[i].getElementsByTagName("div")[0];
			div.innerHTML = this.opt[ix].label.replace("\\n","<br>");
			div.className = "";
		}
	}
}
menuLib.prototype.getIcon = function(opt, ix) {
	var th = this;
	var div1 = document.createElement("DIV");
	div1.className = "icon_large";
	div1.id = "_icon" + ix;
	div1.name = opt.url ? "rov" : "rovmenu";
	if (opt.tip) div1.title = opt.tip;
	var img = document.createElement("IMG");
	//img.id = "_icon" + ix;
	img.src = opt.icon ? this.images["icon-" + opt.icon + "-m"] : this.images["icon-generic-m"];
	img.border = 0;
	var div_img = document.createElement("DIV"); // img container
	div_img.className = "img";
	div_img.appendChild(img);
	if (opt.url) {
		var a = div1.appendChild(document.createElement("A"));
		a.href = opt["url"];
		a.target = "_top";
		a.appendChild(div_img);
		a.appendChild(document.createTextNode(opt.label));
	} else if (opt.submenu) {
		div1.className += " hasmenu";
		div1.appendChild(div_img);
		div1.appendChild(document.createTextNode(opt.label));
		var arrow = document.createElement("IMG");
		arrow.src = this.images["arrow-down"];
		div1.appendChild(document.createElement("BR"));
		div1.appendChild(arrow);
	} else {
		div1.className += " disabled";
		div1.appendChild(div_img);
		div1.appendChild(document.createTextNode(opt.label));
	}
	return div1;
}
menuLib.prototype.getSmallIcon = function(opt,ix) {
	var th = this;
	var div1 = document.createElement("DIV");
	div1.className = "icon_small";
	if (ix) div1.id = "_icon" + ix;
	this.cur_container.appendChild(div1);
	if (opt.tip) div1.title = opt.tip;
	var a = div1.appendChild(document.createElement("A"));
	if (opt.url) {
		a.href = opt.url;
	} else {
		div1.className += " disabled";
	}
	var img = document.createElement("IMG");
	img.src = opt.icon ? this.images["icon-" + opt.icon + "-s"] : this.images["icon-generic-s"];
	img.border = 0;
	a.appendChild(img);
	a.appendChild(document.createTextNode(" "));
	var div = document.createElement("DIV");
	a.appendChild(div);
	div.innerHTML = opt.label.replace("\\n","<br>");
	/*var label = opt.label.split("\\n"); // line break
	for (var i=0; i<label.length; i++) {
		div.appendChild(document.createTextNode(label[i]));
		if (i < label.length-1) div.appendChild(document.createElement("BR"));
	}*/
	return div1;
}
menuLib.prototype.getMenu = function(ix, ret) {
	var th = this;
	this.selected_menu = ix;
	this.get_res_menu(ix);
	var menu = document.createElement("DIV");
	menu.className = "menu";
	var table = document.createElement("TABLE");
	table.className = "submenu";
	var tbody = document.createElement("TBODY");
	for (var i=0; i<this.res_menu.length; i++) {
		if (i > 0 && (this.res_menu[i]["grupo"].length > 0 || this.res_menu[i-1]["grupo"].length > 0)) {
			var row = tbody.appendChild(document.createElement("TR"));
			row.className = "ruler";
			var tdicon = document.createElement("TD");
			tdicon.className = "icon";
			row.appendChild(tdicon);
			var tdruler = row.appendChild(document.createElement("TD"));
			tdruler.className = "label";
			tdruler.appendChild(document.createElement("HR"));
		}
		var row = this.getRowMenu(this.res_menu[i], i);
		tbody.appendChild(row);
	}
	table.appendChild(tbody);
	menu.appendChild(table);
	return menu;
}
menuLib.prototype.get_res_menu = function(ix) {
	// build menu array
	this.res_menu = [];
	var res = this.opt[ix].submenu.opt;
	for (var i=0; i<res.length; i++) {
		var c = this.res_menu.length;
		this.res_menu[c] = [];
		this.res_menu[c].item = res[i].item;
		this.res_menu[c].url = this.prefix + res[i].url;
		this.res_menu[c]._icon = res[i]._icon;
		this.res_menu[c].level = 1;
		this.res_menu[c].grupo = [];
		if (i < res.length-1 && res[i].subitem) {
			this.res_menu[c]._icon = "submenu";
			for (var j=i; j<res.length; j++) {
				if (res[i].item != res[j].item) break;
				c_grp = this.res_menu[c].grupo.length;
				this.res_menu[c].grupo[c_grp] = [];
				this.res_menu[c].grupo[c_grp].item = res[j].subitem;
				this.res_menu[c].grupo[c_grp].url = this.prefix + res[j].url;
				this.res_menu[c].grupo[c_grp]._icon = res[j]._icon;
				this.res_menu[c].grupo[c_grp].level = 2;
			}
			i = j-1;
		}
	}
}
menuLib.prototype.getRowMenu = function(res, ix) {
	var th = this;
	var row = document.createElement("TR");
	var tdicon = document.createElement("TD");
	tdicon.className = "icon";
	var icon = document.createElement("IMG");
	icon.src = this.prefix + "../images/bullet" + res["_icon"] + ".gif";
	tdicon.appendChild(icon);
	row.appendChild(tdicon);
	var tdlink = document.createElement("TD");
	tdlink.className = "label";
	if (!res.grupo || res.grupo.length == 0) {
		var a = tdlink.appendChild(document.createElement("A"));
		a.className = "text";
		a.href = res["url"];
		a.appendChild(document.createTextNode(res.item));
	} else {
		var div = tdlink.appendChild(document.createElement("DIV"));
		div.className = "text";
		tdlink.onmouseover = function() {
			var submenu = this.querySelector(".submenu");
			if (submenu.offsetLeft + submenu.offsetWidth > document.body.offsetWidth) {
				submenu.style.marginLeft = -(this.offsetWidth + submenu.offsetWidth) + "px";
			}
		}
		div.appendChild(document.createTextNode(res.item));
		tdlink.appendChild(this.getSubMenu(ix));
	}
	row.appendChild(tdlink);
	return row;
}
menuLib.prototype.getSubMenu = function(ix) {
	var th = this;
	this.selected_submenu = ix;
	var res = this.res_menu[ix].grupo;
	var submenu = document.createElement("DIV");
	submenu.className = "submenu";
	var table = document.createElement("TABLE");
	table.className = "submenu";
	var tbody = document.createElement("TBODY");
	for (var i=0; i<res.length; i++) {
		var row = this.getRowMenu(res[i], i);
		tbody.appendChild(row);
	}
	table.appendChild(tbody);
	submenu.appendChild(table);
	return submenu;
}
// submenu class
function subMenuLib() {
	this.opt = [];
	this.prefix = "";
}
subMenuLib.prototype.add = function(params) {
	var c = this.opt.length;
	this.opt[c] = [];
	if (params.grp) {
		this.opt[c].item = params.grp;
		this.opt[c].subitem = params.label;
	} else {
		this.opt[c].item = params.label;
		this.opt[c].subitem = false;
	}
	this.opt[c].url = params.url;
	this.opt[c]._icon = params.icon;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=[];
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}