<?php 
session_start();
header("Content-type: application/javascript");
// include_once dirname(__FILE__) . "/inc/fn_globals.php"; 
?>
function getCookie(cookiename) {
  // Get name followed by anything except a semicolon
  var cookiestring=RegExp(""+cookiename+"[^;]+").exec(document.cookie);
  // Return everything after the equal sign
  return unescape(!!cookiestring ? cookiestring.toString().replace(/^[^=]+./,"") : "");
}
function menuLoader() {
	var th = this;
	this.menu = new menuLib;
	this.menu.hideLogo();
	// this.menu.setUrlCliente("http://www.adm.com.br");
	// this.menu.setTop(20);
	var link = document.createElement("link");
	link.rel = "STYLESHEET";
	link.type = "text/css";
	link.href = "../css/indice.css";
	document.head.appendChild(link);
	link.onload = function() {
		th.build(); return;
	}();
}
menuLoader.prototype.build = function(id) {
	var th = this;
	// cookie
	var now = new Date();
	var time = now.getTime() + (3600 * 1000);
	now.setTime(time);
	document.cookie = 'menuopt=' + id + '; expires=' + now.toUTCString() + '; path=/';
	// menu
	this.menu.reset();
	var grpId = "Sales";
	var submenu = new subMenuLib;		
	submenu.add({ label:"Add", url:"customers.php?modo=insert", icon:"insert" });
	submenu.add({ label:"Update", url:"customers.php?modo=update&step=0", icon:"update" });
	submenu.add({ label:"Delete", url:"customers.php?modo=delete&step=0", icon:"delete" });
	this.menu.add({ grp:grpId, submenu:submenu, label:"Customers" });
	var submenu = new subMenuLib;		
	submenu.add({ label:"Add", url:"staff.php?modo=insert", icon:"insert" });
	submenu.add({ label:"Update", url:"staff.php?modo=update&step=0", icon:"update" });
	submenu.add({ label:"Delete", url:"staff.php?modo=delete&step=0", icon:"delete" });
	this.menu.add({ grp:grpId, submenu:submenu, label:"Staff" });
	var submenu = new subMenuLib;		
	submenu.add({ label:"Add", url:"stores.php?modo=insert", icon:"insert" });
	submenu.add({ label:"Update", url:"stores.php?modo=update&step=0", icon:"update" });
	submenu.add({ label:"Delete", url:"stores.php?modo=delete&step=0", icon:"delete" });
	this.menu.add({ grp:grpId, submenu:submenu, label:"Stores" });

	var grpId = "Production";
	var submenu = new subMenuLib;		
	submenu.add({ label:"Add", url:"products.php?modo=insert", icon:"insert" });
	submenu.add({ label:"Update", url:"products.php?modo=update&step=0", icon:"update" });
	submenu.add({ label:"Delete", url:"products.php?modo=delete&step=0", icon:"delete" });
	this.menu.add({ grp:grpId, submenu:submenu, label:"Products" });
	var submenu = new subMenuLib;		
	submenu.add({ label:"Add", url:"categories.php?modo=insert", icon:"insert" });
	submenu.add({ label:"Update", url:"categories.php?modo=update", icon:"update" });
	submenu.add({ label:"Delete", url:"categories.php?modo=delete", icon:"delete" });
	this.menu.add({ grp:grpId, submenu:submenu, label:"Categories" });
	var submenu = new subMenuLib;		
	submenu.add({ label:"Add", url:"brands.php?modo=insert", icon:"insert" });
	submenu.add({ label:"Update", url:"brands.php?modo=update", icon:"update" });
	submenu.add({ label:"Delete", url:"brands.php?modo=delete", icon:"delete" });
	this.menu.add({ grp:grpId, submenu:submenu, label:"Brands" });

	setTimeout(function() { th.menu.build(id); }, 100);
}
__navbar = new menuLoader;