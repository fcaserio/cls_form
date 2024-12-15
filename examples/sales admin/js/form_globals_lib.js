// Vers„o: 1.16 - 22/09/2016

// Prototypes
String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g, '');
}
String.prototype.removeAccents = function() {
	return this.replace(/[„·‚‡]/g,"a").replace(/[ÈÍ]/g,"e").replace(/[ÌÓ]/g,"i").replace(/[ÙÛÙ]/g,"o").replace(/[˙˚]/g,"u").replace(/[Á]/g,"c").replace(/[√¡¬¿]/g,"A").replace(/[… ]/g,"E").replace(/[ÕŒ]/g,"I").replace(/[‘”‘]/g,"O").replace(/[⁄€]/g,"U").replace(/[«]/g,"C");
}
Number.prototype.pad = function(len,str,pos) {
	return this.toString().pad(len,str,pos)
}
String.prototype.pad = function(len,str,pos) {
	if (!str) str = " ";
	if (!pos) pos = "L";
	var _str = this;
	if (_str.length < len) {
		for (var i=0; i<len; i++) {
			if (pos == "L")
				_str = str + _str;
			else if (pos == "R")
				_str += str;
			if (_str.length == len) 
				break;
		}
	}
	return _str.toString();
}
String.prototype.replaceAll = function(search, replacement) {
	return this.split(search).join(replacement);
}
Date.prototype.format = function(mask) {
	var str = "";
	for (var i=0; i<mask.length; i++) {
		if (mask.substr(i,1) == "Y") 
			str += this.getFullYear().toString();
		else if (mask.substr(i,1) == "m") 
			str += (this.getMonth()+1).pad(2,'0','L');
		else if (mask.substr(i,1) == "d") 
			str += this.getDate().pad(2,'0','L');
		else if (mask.substr(i,1) == "H") 
			str += this.getHours().pad(2,'0','L');
		else if (mask.substr(i,1) == "i") 
			str += this.getMinutes().pad(2,'0','L');
		else if (mask.substr(i,1) == "s") 
			str += this.getSeconds().pad(2,'0','L');
		else
			str += mask.substr(i,1);
	}
	return str;
}
Date.prototype.toStr = function() {
	return this.toJSON().substring(0,10);
}
insertAfter = function(anchor, elm, pos) {
	if (pos) for (var i=0; i<pos; i++) var anchor = anchor.nextSibling;
	if (anchor.nextSibling)
		anchor.parentNode.insertBefore(elm, anchor.nextSibling);
	else
		anchor.parentNode.appendChild(elm);
}
insertBefore = function(anchor, elm) {
	anchor.parentNode.insertBefore(elm, anchor);
}

// Array
function inArray(needle, haystack) {
    for(var i in haystack) {
        if(haystack[i] == needle) return true;
    }
    return false;
}

// Number
function formatnum(valor, depth, sep_dec, sep_mil) {
	var valor = getVal(valor);
	if (typeof depth == "undefined") depth = 2;
	if (typeof sep_dec == "undefined") sep_dec = ".";
	if (typeof sep_mil == "undefined") sep_mil = "";
	if (String(depth).charAt(depth.length-1) == "+") {
		var depth = depth.substring(0,depth.length-1);
		var maxdec = 10;
		var dec = String(Math.round((valor - Math.floor(valor)) * Math.pow(10,maxdec)));
		for (var i=dec.length; i<maxdec; i++) dec = "0" + dec;
		for (var i=dec.length; i>=depth; i--) {
			if (dec.charAt(i) != 0) {
				depth = i+1;
				break;
			}
		}
	}
	var ck_neg = (valor < 0);
	var valor = Math.abs(valor);
	var num = String(Math.floor(valor));
	var dec = String(Math.round((valor - Math.floor(valor)) * Math.pow(10,depth)));
	if (dec == Math.pow(10,depth)) {
		num = String(parseInt(num) + 1);
		dec = String(0);
	}
	if (dec.length < depth) {
		for (var i=dec.length; i<depth; i++) dec = "0" + dec;
	}
	if (sep_mil != "") {
		var temp = num;
		var num = "";
		for (var i=temp.length; i>=0; i--) {
			num = temp.charAt(i) + num;
			if (i != 0 && i != temp.length && (temp.length - i) % 3 == 0) num = sep_mil + num;
		}
	}
	return (ck_neg ? "-" : "") + num + (depth > 0 ? sep_dec + dec : "");
}

function getVal(val) {
	var objRegExp = /([0-9]+\.)*[0-9]+\,[0-9]+/;
	if (val == "")
		return 0;
	else if (objRegExp.test(val))
		return parseFloat(val.replaceAll(".","").replace(",","."));
	else
		return parseFloat(val);
}

/* Depracated => use parseHour(val*60)
function formathour(val) { 
	val = Math.round(val*100)/100;
	var comp = Math.round((val-Math.floor(val))*60)
	return "" + Math.floor(val) + ":" + (comp < 10 ? "0" : "") + comp;
}
*/

function parseHour(min) {
	if (min !== "")
		return Math.floor(min/60) + ":" + (min%60).toString().pad(2,'0','L');
	else
		return "";
}

function formatStr(val,mask) {
	var ret = "", len = 0;
	for (var part of mask) {
		if (typeof part == "number") {
			ret += val.substr(len,part);
			len += part;
		} else {
			ret += part;
		}
	}
	return ret;
};

// Clipboard
function copyToClip(str) {
	function listener(e) {
		e.clipboardData.setData("text/html", str);
		e.clipboardData.setData("text/plain", str);
		e.preventDefault();
	}
	document.addEventListener("copy", listener);
	document.execCommand("copy");
	document.removeEventListener("copy", listener);
};

// Form
function fnValida(field,universo,find,replace) {
	fn_valida(field,universo,find,replace);
}
function fn_valida(field,universo,find,replace) {
	var val = "";
	for (var i=0; i<field.value.length; i++) {
		if (find && find == field.value.charAt(i)) {
			val += replace;
		} else {
			var ck = 0;
			for (var j=0; j<universo.length; j++) {
				if (field.value.charAt(i) == universo.charAt(j)) { var ck = 1; break; }
			}
			if (ck == 1) val += field.value.charAt(i);
		}
	}
	if (find == "," && replace == ".") { // remove separador de milhar
		while (val.indexOf(replace) != val.lastIndexOf(replace) && val.indexOf(replace) > -1) {
			val = val.replace(replace, "");
		}
	}
	field.value = val;
}
function uncheckOther(src) {
	for (var f of src.form[src.name]) if (f != src) f.checked = false;
}
function ckdata(dia,mes,ano) {
	if (dia == "" || mes == "" || ano == "") {
		return false;
	} else if (parseFloat(dia) == 0 || parseFloat(mes) == 0 || parseFloat(ano) == 0) {
		return false;
	} else if (!cknum(dia) || !cknum(mes) || !cknum(ano)) {
		return false;
	} else if (parseFloat(ano) < 1900) {
		return false;
	} else {
		var tammes = { 1:31, 2:(ano%4==0?29:28), 3:31, 4:30, 5:31, 6:30, 7:31, 8:31, 9:30, 10:31, 11:30, 12:31 };
		if (parseFloat(mes) > 12 || parseFloat(dia) > tammes[parseFloat(mes)]) {
			return false;
		} else {
			return true;
		}
	}
}
function cknum(valor) {
	universo = "1234567890";
	tamvalor = valor.length;
	tamuniverso = universo.length;
	flag = (valor == "" ? 0 : 1);
	for (var i=0; i<tamvalor; i++) {
		var ck = 0;
		for (var j=0;j<tamuniverso;j++) {
			if (valor.charAt(i) == universo.charAt(j)) ck = 1;
		}
		if (ck == 0) flag = 0;
	} 
	return flag;
}
function getRadioValue(obj) {
	if (isString(obj)) obj = document.formulario[obj];
	var val = "";
	if (obj.length) {
		for (var j=0; j<obj.length; j++) {
			if (obj[j].checked) {
				val = obj[j].value;
				break;
			}
		}
		return val;
	} else if (obj.checked) {
		return obj.value;
	} else {
		return "";
	}
}
function ckFileLimit(form, form_limit, file_limit) {
	var msg = "";
	var tot = 0;
	for (var i=0; i<form.length; i++) {
		if (form[i].type == "file") {
			for (var j=0; j<form[i].files.length; j++) {
				if (form[i].files[j].size > parseInt(file_limit)*1024*1024) msg += "O arquivo " + form[i].files[j].name + " (" + formatnum(form[i].files[j].size/1024/1024,2,',','.') + "Mb) excede o limite m·ximo permitido de " + parseInt(file_limit) + "Mb\n";
				tot += form[i].files[j].size;
			}
		}
	}
	if (tot > parseInt(form_limit)*1024*1024) msg += "O total dos arquivos anexados (" + formatnum(tot/1024/1024,2,',','.') + "Mb) excede o limite m·ximo permitido de " + parseInt(form_limit) + "Mb\n";
	return msg;
}
function toggleMultiple(elm,cfg) {
	if (typeof elm == "string") var elm = document.getElementById(elm);
	if (typeof cfg == "undefined") var cfg = {};
	if (elm.multiple) {
		elm.multiple = false;
		elm.style.removeProperty("resize");
		elm.style.removeProperty("width");
		elm.style.removeProperty("height");
		elm.size = 1;
		if (elm.name.substr(-2) == "[]") elm.name = elm.name.substr(0,-2);
		if (elm[0].value == "") elm[0].style.display = "";
	} else {
		elm.multiple = true;
		elm.style.resize = "both";
		elm.size = cfg.size ? cfg.size : 3;
		if (elm.name.substr(-2) != "[]") elm.name += "[]";
		if (elm[0].value == "") elm[0].style.display = "none";
		for (var opt of elm.options) opt.selected = false;
	}
	if (cfg.hide) {
		if (!Array.isArray(cfg.hide)) cfg.hide = [ cfg.hide ];
		for (var hideElm of cfg.hide) {
			if (typeof hideElm == "string") var hideElm = document.getElementById(hideElm);
			hideElm.style.display = elm.multiple ? "none" : "";
			if (hideElm.value && elm.multiple) hideElm.value = "";
		}
	}
}

// Date
function recvalue(curF) {
	tempvalue = curF.value
}

function gotofield(curF) {
	if (!curF.type) var curF = arguments[1]; // compatibility to previous version gotofield(form,curF)
	if (curF.value != tempvalue &&
		curF.maxLength == curF.value.length) {
		var elms = Object.values(curF.form.elements);
		for (var i=elms.indexOf(curF)+1; i<elms.length; i++) {
			if (elms[i].type != "hidden" && !elms[i].disabled && isVisible(elms[i])) {
				elms[i].focus();
				if (elms[i].type == "text" && elms[i].value != "") elms[i].select();
				break;
			}
		}
	}
	tempvalue = curF.value
}

function isVisible(obj) {
	if (obj.style.display == "none" || obj.style.visibility == "hidden") {
		return false;
	} else if (obj.parentNode && obj.parentNode.tagName != "BODY" && obj.parentNode.tagName != "HTML") {
		return isVisible(obj.parentNode);
	} else {
		return true;
	}
}

function isString(a) {
    return typeof a == 'string';
}

function findPos(obj) {
	var curleft = obj.offsetLeft;
	var curtop = obj.offsetTop;
	if (obj.offsetParent) {
		while (obj = obj.offsetParent) {
			curleft += obj.offsetLeft;
			curtop += obj.offsetTop;
		}
	}
	return [curleft,curtop];
}

// display
function toggleDisplay(elm,holder) {
	for (var e of elm instanceof HTMLElement ? [ elm ] : document.querySelectorAll(elm)) {
		if (e.style.display == "none") {
			e.style.display = "";
			if (holder) holder.innerHTML = "[-]";
		} else {
			e.style.display = "none";
			if (holder) holder.innerHTML = "[+]";
		}
	}
	return;
	if (typeof elm == "string") var elm = document.getElementById(elm);
	if (elm.style.display == "none") {
		elm.style.display = "";
		if (holder) holder.innerHTML = "[-]";
	} else {
		elm.style.display = "none";
		if (holder) holder.innerHTML = "[+]";
	}
}
function showElm(elm) {
	displayElm(elm,true);
}
function hideElm(elm) {
	displayElm(elm,false);
}
function displayElm(elm,show) {
	for (var e of elm instanceof HTMLElement ? [ elm ] : document.querySelectorAll(elm)) {
		e.style.display = show ? "" : "none";
	}
}
function markTr(tr,color) {
	var tds = tr.getElementsByTagName('TD');
	for (var i=0; i<tds.length; i++) {
		if (color)
			tds[i].style.color = color;
		else if (tds[i].style.removeProperty)
			tds[i].style.removeProperty('color');
		else if (tds[i].style.removeAttribute)
			tds[i].style.removeAttribute('color');
	}
}

// uppercase conversion
function configInputToUpper(obj) {
	if (document.all)
		obj.onkeypress = inputToUpperIE;
	else
		obj.addEventListener("keyup",inputToUpperFF,true);
}
function inputToUpperIE() {
	key = window.event.keyCode;
	if ((key > 0x60) && (key < 0x7B))
	window.event.keyCode = key-0x20;
}
function inputToUpperFF(e) {
	var obj = e.currentTarget;
	var sel = obj.selectionStart;
	obj.value = obj.value.toUpperCase();
	obj.setSelectionRange(sel,sel);
}

// css
function addClass(elm, _class) {
	if (!elm.className)
		elm.className = _class;
	else if (elm.className.indexOf(_class) < 0)
		elm.className += " " + _class;
}
function remClass(elm, _class) {
	if (elm.className.indexOf(_class) >= 0)
		elm.className = elm.className.replace(_class, "").trim();
}
function hasClass(elm, _class) {
	if (!elm.className)
		return false;
	else 
		return !(elm.className.indexOf(_class) < 0);
}

function getCssProperty(tag, prop) {
	var mysheet = document.styleSheets[0];
	var myrules = mysheet.cssRules ? mysheet.cssRules : mysheet.rules;
	var ck = 0;
	for (i=0; i<myrules.length; i++) {
		if (myrules[i].selectorText.toLowerCase() == tag) {
			var css = myrules[i];
			var ck = 1;
			break;
		}
	}
	if (ck == 1) return css.style[prop];
}

function markTr(tr,color) {
	var tds = tr.getElementsByTagName('TD');
	for (var i=0; i<tds.length; i++) {
		if (color)
			tds[i].style.color = color;
		else if (tds[i].style.removeProperty)
			tds[i].style.removeProperty('color');
		else if (tds[i].style.removeAttribute)
			tds[i].style.removeAttribute('color');
	}
}

// get form used by user
function getFormData(cmd) {
	// AJAX request
	var xhr = new XMLHttpRequest();
	var url = "../ajax/get_form_data.xml.php?path=" + window.location.pathname;
	//window.open(url,'_blank')
	xhr.open("GET", url, true);
	xhr.onreadystatechange = function() {
		//if (xhr.readyState == 4 && xhr.status == 200) {
		if (xhr.readyState == 4) {
			/* if (xhr.responseXML == null &&
				confirm(th.getText("bug_msg"))) 
				window.open(url, '_blank'); */
			var form = document.formulario ? document.formulario : document.forms[0];
			var res = xhr.responseXML.getElementsByTagName("res");
			var r = {}, hid = {}, cb = [];
			for (var i=0; i<res.length; i++) {
				var ix = getNodeValue(res[i], "field");
				var val = getNodeValue(res[i], "val");
				r[ix] = val;
				if (form[ix]) {
					if (form[ix].type == "checkbox") {
						form[ix].checked = true;
						cb.push(ix);
					} else {
						if (form[ix].type == "hidden") hid[ix] = form[ix].value;
						form[ix].value = val;
					}
				}
			}
			//console.log(res)
			if (res.length > 0) {
				// desmarca os checkbox que o usuario nao tenha submetido na consulta original
				var objs = document.getElementsByTagName("INPUT");
				for (var i=0; i<objs.length; i++) {
					if (objs[i].type == "checkbox" && cb.indexOf(objs[i].name) < 0) objs[i].checked = false;
				}
				// cria botao de reset
				var input = document.createElement("input");
				input.type = "reset";
				input.value = "Restaurar Padr„o";
				input.onclick = function() {
					for (var ix in hid) form[ix].value = hid[ix]; // reset() afeta campo hidden
				}
				form.ok.parentNode.insertBefore(input, form.ok);
				form.ok.parentNode.insertBefore(document.createTextNode(" "), form.ok);
				addClass(form.ok.parentNode, "btngroup");
			}
			if (typeof cmd == "function") cmd(r);
		}
	} // function()
	xhr.send(null);
}