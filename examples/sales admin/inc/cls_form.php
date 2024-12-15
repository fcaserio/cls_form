<?php
/**
 * Class cls_form - form admin builder
 * @author Francisco Caserio - francisco.caserio@gmail.com
 * @version 3.22 - 04/03/2022
 */
/* 
 check ISO-8859-1: áéíóúâêôàãõüçÁÉÍÓÚÂÊÔÀÃÕÜÇ°ª
*/
class cls_form {
	public function __construct($db = "MSSQL", $cls_report = false) {
		$this->db = strtoupper($db);
		$this->cls_report = $cls_report;
		// flags
		$this->table = false;
		$this->list_form = false;
		$this->add_print = 0;
		$this->ck_active = 0;
		$this->ck_collapse = 0;
		$this->ck_recursive = 0;
		$this->ck_csv = 0;
		$this->ck_xls = 0;
		$this->ck_global_form = 0;
		$this->ck_multiple_entry = 0;
		$this->ck_notification = 0;
		$this->ck_flag_mode = 0;
		$this->ck_display_title = 1;
		$this->ck_repeat_row = false;
		$this->ck_return_pk = 0;
		$this->ck_unlock = 0;
		$this->ck_mask = -1;
		$this->ck_redirect = 1;
		$this->ck_readonly = 0;
		$this->ck_verify_email = 0;
		$this->ck_nowrap_title = 0;
		$this->ck_file = 0;
		$this->ck_header = 1;
		$this->ck_reorder = 0;
		$this->unique_ix = 0;
		$this->repeat_insert = 1;
		// containers
		$this->ref = [];
		$this->ref_filter = [];
		$this->ref_list = [];
		$this->ref_tbl_val = [];
		$this->label = [];
		$this->list_row = [];
		$this->group_key = [];
		$this->order = [];
		$this->group = [];
		$this->insert_val = [];
		$this->graph = [];
		$this->ent_1XN = [];
		$this->ent_NXN = [];
		$this->condition = [];
		$this->related = [];
		$this->sql_cmd = [];
		$this->dependency = [];
		$this->delete_cascade = [];
		$this->update_cascade = [];
		$this->form_rule = [];
		$this->js_code = [];
		$this->header = [];
		$this->hdr_total = [];
		$this->tab_ref = [];
		$this->link_ref = [];
		$this->color_ref = [];
		$this->publisher_ref = [];
		$this->flag_mode_action = [];
		$this->title_opt = [];
		$this->subtotal = [];
		/* depracated, using set_label_edit_mode()
		$this->flag_mode_ref = [];
		*/
		$this->onload_action = array("form" => [], "filter" => [], "list" => []);
		$this->onsubmit_action = [];
		$this->ck_new_ref = [];
		$this->list_param = [];
		$this->js_ref = [];
		$this->css_ref = [];
		$this->cssStyle = [];
		// document linkage
		$this->document_name_list = "";
		$this->document_name_form = "";
		$this->document_name_xml = "";
		// templates indicators
		$this->tpl_form = "";
		$this->tpl_filter = "";
		$this->tpl_prompt = "";
		$this->tpl_return_insert_pk = "";
		$this->tpl_return_update_pk = "";
		// constants managed by methods
		$this->pk = "id";
		$this->pageby = false;
		$this->redirect_str = false;
		$this->field_group = false;
		$this->list_title_mode = "H";
		$this->list_modo = "auto";
		$this->total_label = "Total";
		$this->keyword = "keyword";
		// constants managed by config
		$this->use_sysdoc = 0;
		$this->use_translator = 0;
		$this->use_CalendarPopup = 0;
		$this->use_print_close = 0;
		$this->use_print_button = 0;
		$this->use_print_back = 0;
		$this->use_list_filter = 0;
		$this->use_list_header_separator = 1;
		$this->use_uppercase = 0;
		$this->pref_field_date = "text";
		$this->use_required_position = "right";
		$this->inactive_color = "#FFFFAA";
		$this->hour_format = "30m";
		$this->idioma = "pt";
		$this->use_required_position = "right";
		$this->setup_complete = "linked";
		$this->password_encrypt_method = "md5";
		$this->css_path = "";
		$this->css_xls_path = "";
		$this->img_dir = "";
		$this->js_dir = "";
		// table
		$this->form_width = 980;
		$this->list_width = 980;
		$this->cellspacing = 3;
		$this->cellpadding = 5;
		$this->button_align = "right";
		// images
		$this->img_required = "required";
		$this->list_bullet = "";
		$this->img_lock = "iconelock.gif";
		$this->img_unlock = "iconeunlock.gif";
		$this->img_print = "imprimir.gif";
		$this->img_xls = "icon_xls.gif";
		$this->img_anexo = "icon_anexo.png";
		$this->img_calendar_popup = "icon_calendario.png";
		$this->img_collapse = "icon_collapse.gif";
		$this->img_uncollapse = "icon_uncollapse.gif";
		// css
		$this->css_form = "";
		$this->css_formpeq = "";
		$this->css_table = "";
		$this->css_table_print = "";
		$this->css_title_pg = "";
		$this->css_confirm = "";
		$this->css_link_general = "";
		$this->css_label = "";
		$this->css_text = "";
		$this->css_label_print = "";
		$this->css_text_print = "";
		$this->css_label_entity = "";
		$this->css_text_entity = "";
		$this->css_required = "";
		$this->css_list_header = "";
		$this->css_list_group = "";
		$this->css_list_title = "";
		$this->css_list_text = "";
		$this->css_list_separator = "";
		$this->css_form_separator = "";
		$this->css_edit_list = "";
		$this->css_page_by = "";
		$this->css_button = "";
		$this->css_button_remove = "";
		$this->css_print_option = "";
		$this->css_tab = "";
		$this->css_tab_selected = "";
		$this->debug = 0;
		// config custom
		include dirname(__FILE__) . "/cls_form.config.php";
		if (!isset($this->css_debug)) $this->css_debug = $this->css_text;
		// setup required
		if (is_file("$this->img_dir/$this->img_required") || 
			is_file($_SERVER["DOCUMENT_ROOT"]."$this->img_dir/$this->img_required"))
			$this->str_required = "<img src=\"$this->img_dir/$this->img_required\" border=0 align=absmiddle>";
		else
			$this->str_required = $this->img_required;
		// setup javascript libs
		$this->add_js("ajax_lib.v2.js", true);
		$this->add_js("form_globals_lib.js", true);
		$this->add_js("fn_table_aux.js", true);
		if ($this->use_CalendarPopup == 1) {
			$this->add_js("Calendar/CalendarPopup.js", true);
			$this->add_js("Calendar/PopupWindow.js", true);
			$this->add_js("Calendar/AnchorPosition.js", true);
			$this->add_js("Calendar/date.js", true);
		}
		// Bullet list
		if (is_file("$this->img_dir/$this->list_bullet")) $this->list_bullet = "<img src=\"$this->img_dir/$this->list_bullet\" border=0>";
	}
	public function set_debug() {
		$this->debug = 1;
	}
	function get_debug_pos() {
		return $this->debug_pos;
	}
	function set_debug_pos($pos,$inc=false) {
		if ($inc && !empty($this->debug_pos))
			$this->debug_pos += $pos;
		else
			$this->debug_pos = $pos;
	}
	private function show_debug($qry, $label = "?", $pos = "fixed", &$ret=false) {
		if (!empty($_REQUEST["ck_xml"]) || !empty($_REQUEST["ck_csv"])) return false;
		$style_str = "position:$pos;";
		if (!isset($this->debug_pos)) $this->debug_pos = 0;
		if (!isset($this->debug_count)) $this->debug_count = 0;
		if ($pos == "absolute" || $pos == "fixed") $style_str .= "left:" . $this->debug_pos . "px;top:0px;";
		$str  = "<a style=\"$style_str\" class=\"$this->css_debug\" href=\"javascript:void(null)\" onclick=\"document.getElementById('_debug$this->debug_count').style.display=document.getElementById('_debug$this->debug_count').style.display==''?'none':'';this.style.fontWeight=document.getElementById('_debug$this->debug_count').style.display==''?'bold':'normal';\">[$label]</a>\n";
		$str .= "<pre id=\"_debug$this->debug_count\" style=\"display:none\">$qry</pre>\n";
		if ($ret !== false)
			$ret .= $str;
		else
			echo $str;
		if ($pos == "absolute" || $pos == "fixed") $this->debug_pos+=20;
		$this->debug_count++; 
	}
	public function setTimeLimit($t) {
		$this->time_limit = $t;
	}
	public function set_title($title) {
		$this->title = $title;
	}
	public function add_title_option($label, $url, $icon) {
		$this->title_opt[] = [
			"label" => $label,
			"url" => $url,
			"icon" => $icon
		];
	}
	public function set_uppercase($ck) {
		$this->use_uppercase = $ck;
	}
	public function set_form_width($w) {
		$this->form_width = $w;
	}
	public function set_list_width($w) {
		$this->list_width = $w;
	}
	public function set_nowrap_title() {
		$this->ck_nowrap_title = 1;
	}
	public function set_css($css, $class) {
		if ((substr($css,0,3) != "css" && substr($css,0,4) != "cell") ||
			!isset($this->$css)) echo "INVALID CSS";
		else
			$this->$css = $class;
	}
	public function set_template($scope, $tpl) {
		if ($scope == "form")
			$this->tpl_form = $tpl;
		else if ($scope == "filter")
			$this->tpl_filter = $tpl;
		else if ($scope == "prompt")
			$this->tpl_prompt = $tpl;
	}
	public function add_template_value($marker, $val) {
		$this->tpl_val[$marker] = $val;
	}
	public function pageby($num,$method="POST") {
		$this->pageby = $num;
		$this->pageby_method = $method;
		if (!isset($this->pageby_pos)) 
			$this->pageby_pos = "U";
		if (!isset($this->pageby_align)) 
			$this->pageby_align = "right";
	}
	public function display_title($ck) {
		$this->ck_display_title = $ck;
	}
	public function set_list_url($value, $field=null, $op = "==", $val = "") {
		$this->set_document_name($value, "list", $field, $op, $val);
	}
	public function set_document_name($value, $scope="list", $field="", $op="==", $val="") {
		if (strtolower($scope) == "list") {
			$this->document_name_list = $value;
			if (!empty($field)) $this->set_url_condition($field, $op, $val);
		} else if (strtolower($scope) == "form") {
			$this->document_name_form = $value;
		} else if (strtolower($scope) == "xml") {
			$this->document_name_xml = $value;
			for ($i=0; $i<count($this->ref); $i++) {
				if (isset($this->ref[$i]["prop"]["onchange"]) &&
					strpos($this->ref[$i]["prop"]["onchange"], "?ck_xml=1") !== false) {
					$this->ref[$i]["prop"]["onchange"] = str_replace("?ck_xml=1", "$this->document_name_xml?ck_xml=1", $this->ref[$i]["prop"]["onchange"]);
				}
			}
			for ($i=0; $i<count($this->ref_filter); $i++) {
				if (isset($this->ref_filter[$i]["prop"]["onchange"]) &&
					strpos($this->ref_filter[$i]["prop"]["onchange"], "?ck_xml=1") !== false) {
					$this->ref_filter[$i]["prop"]["onchange"] = str_replace("?ck_xml=1", "$this->document_name_xml?ck_xml=1", $this->ref_filter[$i]["prop"]["onchange"]);
				}
			}
		}
	}
	public function set_url_condition($field, $op="", $val="") {
		if (!($field instanceof Closure) && $this->list_modo != "custom") {
			$ck = 0;
			for ($i=0; $i<count($this->label); $i++) {
				if ($this->getFieldLbl($this->label[$i]["field"]) == $field) {
					$ck = 1; 
				} else if ($this->getFieldIx($this->label[$i]["field"]) == $field) {
					$field = $this->getFieldLbl($this->label[$i]["field"]);
					$ck = 1;
				}
			}
			if ($ck == 0) {
				$this->add_label("$field AS ck_link");
				$field = "ck_link";
			}
		}
		$this->list_url_condition = ["field" => $field, "op" => $op, "val" => $val];
	}
	public function set_redirect($value, $msg=false, $align=false) {
		if (!$value) $this->ck_redirect = 0;
		$this->redirect_str = $value;
		$this->confirm_msg = $msg;
		$this->confirm_align = $align;
	}
	public function set_readonly($value=1) {
		$this->ck_readonly = $value;
	}
	public function set_css_path($css_path) {
		$this->css_path = $css_path;
	}
	public function set_img_dir($img_dir) {
		$this->img_dir = $img_dir;
	}
	public function set_js_dir($js_dir) {
		$this->js_dir = $js_dir;
	}
	public function set_entity($table, $ent, $adm=true) {
		if ($this->use_translator == 1) $ent = $this->translator->get($ent);
		$this->table = $table;
		$this->ent = $ent;
		$this->adm = $adm;
	}
	public function set_msg_insert($msg) {
		$this->lang_confirm_insert = $msg;
		$this->ck_redirect = 0;
	}
	public function add_onload_action($action, $scope="form") {
		$ck_exists = 0;
		for ($i=0; $i<count($this->onload_action[$scope]); $i++) {
			if ($this->onload_action[$scope][$i] == $action) {
				$ck_exists = 1;
				break;
			}
		}
		if ($ck_exists == 0) {
			$c = count($this->onload_action[$scope]);
			$this->onload_action[$scope][$c] = $action;
		}
	}
	public function add_onsubmit_action($action) {
		$ck_exists = 0;
		for ($i=0; $i<count($this->onsubmit_action); $i++) {
			if ($this->onsubmit_action[$i] == $action) {
				$ck_exists = 1;
				break;
			}
		}
		if ($ck_exists == 0) {
			$c = count($this->onsubmit_action);
			$this->onsubmit_action[$c] = $action;
		}
	}
	public function add_res_json($fields) {
		$this->res_json = $fields;
	}
	public function add_print_option() {
		$this->add_print = 1;
	}
	public function add_filter($field, $label, $type=null, $ck_qry=1) {
		if ($type == "filteredtext") $type = "fieldfilter";
		if ($this->use_translator == 1) $label = $this->translator->get($label);
		$c = count($this->ref_filter); // new index
		$obj = new cls_field("filter", $this, $field, $label, $type, 0, $ck_qry, $c);
		$this->filter_objs[$c] = $obj;
		$this->ref_filter[$c] = array(
			"object" => $obj, 
			"field" => $field, 
			//"qry_field" => $this->getFieldIx($field), 
			"label" => $label, 
			"type" => $type, 
			"ck_req" => 0, 
			"ck_qry" => $ck_qry, 
			"ck_hidden" => 0, 
			"ck_separator" => 0, 
			"ck_log" => 0,
			"tab_id" => 0, 
			"field_group" => $this->field_group, 
			"prop" => []);
		if ($type == "fieldfilter") {
			$this->add_js("ajax_lib.v2.js", true);
		}
		/* else if ($type == "checkbox") {
			// $this->set_filter_comment($label);
		} */
		return $obj;
	}
	public function set_filter_options($opt) {
		$c_ref = count($this->ref_filter)-1;
		$this->ref_filter[$c_ref]["object"]->set_options($opt);
		if (isset($opt["ajax"])) {
			$this->ref_filter[$c_ref]["ajaxqry"] = $opt["ajax"]["qry"];
			$this->ref_filter[$c_ref]["ajaxid"] = $opt["ajax"]["id"];
			$this->ref_filter[$c_ref]["ajaxlabel"] = $opt["ajax"]["label"];
			$this->ref_filter[$c_ref]["ajaxfilter"] = isset($opt["ajax"]["filter"]) ? $opt["ajax"]["filter"] : $opt["ajax"]["label"];
			$this->ref_filter[$c_ref]["ajaxaction"] = isset($opt["ajax"]["action"]) ? $opt["ajax"]["action"] : [];
			$this->ref_filter[$c_ref]["ajax_xtrafield"] = isset($opt["ajax"]["xtrafield"]) ? $opt["ajax"]["xtrafield"] : [];
		}
		if (isset($opt["ck_hidden"])) {
			$this->ref_filter[$c_ref]["ck_hidden"] = $opt["ck_hidden"];
		}
	}
	public function add_filter_keyword($field, $label) {
		$this->add_filter("keyword", "Palavra-chave", "findtext");
		$c = count($this->ref_filter)-1; // existing index
		$this->ref_filter[$c]["keyword_ref"] = $field;
		if (is_array($field)) {
			$str = "<br>";
			for ($i=0; $i<count($field); $i++) {
				if (strpos($field[$i], ".") > 0)
					$field_name = substr($field[$i], strpos($field[$i], ".")+1);
				else
					$field_name = $field[$i];
				$label_name = $label[$i];
				$str .= "<label><input type=checkbox name=ck_$field_name id=ck_$field_name value=1 CHECKED>$label_name</label>\n";
			}
		}
		$this->set_filter_comment($str);
	}
	public function add_filter_prop($prop, $value=null, $ix=null) {
		$c_ref = is_numeric($ix) ? $ix : count($this->ref_filter)-1;
		if (isset($this->ref_filter[$c_ref]["prop"][$prop]))
			$this->ref_filter[$c_ref]["prop"][$prop] .= ";" . $value;
		else
			$this->ref_filter[$c_ref]["prop"][$prop] = $value;
	}
	public function set_filter_default($value) {
		$c_ref = count($this->ref_filter)-1;
		$this->ref_filter[$c_ref]["object"]->set_default($value);
		$this->ref_filter[$c_ref]["default"] = $value;
	}
	public function set_filter_comment($value,$pos="after") {
		$c_ref = count($this->ref_filter)-1;
		$this->ref_filter[$c_ref]["object"]->set_comment($value,$pos);
		if ($pos == "after")
			$this->ref_filter[$c_ref]["comment"] = $value;
		if ($pos == "before")
			$this->ref_filter[$c_ref]["comment_before"] = $value;
	}
	public function set_filter_hidden($field = null, $val = "", $condition = "==") {
		if ((string)$val == "==" || (string)$val == "!=" || (string)$val == ">" || (string)$val == "<" || (string)$val == ">=" || (string)$val == "<=") {
			list($val,$condition) = [ $condition, $val ];
		}
		$this->set_filter_options([ "ck_hidden" => [ "field" => $field, "op" => $condition, "val" => $val ] ]);
	}
	public function set_filter_qry($qry, $ix, $label, $group_label = null, $xtra_fields = null) {
		$c_ref = count($this->ref_filter)-1;
		$this->ref_filter[$c_ref]["object"]->set_qry($qry, $ix, $label, $group_label, $xtra_fields);
		$this->ref_filter[$c_ref]["qry"] = [
			"sql" => $qry, 
			"ix" => $ix, 
			"label" => $label, 
			"group_label" => $group_label,
			"xtra_fields" => $xtra_fields
		];
		$this->ref_filter[$c_ref]["ck_new_entry"] = 0;
		$this->ref_filter[$c_ref]["ajax"] = $this->ck_ajax_qry($qry, "filter");
	}
	public function set_filter_ajaxpath($url, $filter=[]) {
		$c_ref = count($this->ref_filter)-1;
		$this->ref_filter[$c_ref]["object"]->set_ajax_path($url, $filter, $this->ref_filter);
		$this->ref_filter[$c_ref]["ajaxpath"] = $url;
		$this->ref_filter[$c_ref]["ajaxfilter"] = $filter;
		$this->ref_filter[$c_ref]["ajaxaction"] = [];
		$this->ref_filter[$c_ref]["ajax_xtrafield"] = [];
	}
	public function set_filter_ajaxaction($action, $modo="S", $ix=null) {
		$this->add_filter_ajaxaction($action, $modo, $ix);
	}
	public function add_filter_ajaxaction($action, $modo="S", $ix=null) {
		// $modo => S = SELECT, R = RESET
		$c_ref = is_numeric($ix) ? $ix : count($this->ref_filter)-1;
		$this->ref_filter[$c_ref]["object"]->add_ajax_action($action, $modo);
		if (!isset($this->ref_filter[$c_ref]["ajaxaction"])) $this->ref_filter[$c_ref]["ajaxaction"] = [];
		$c = count($this->ref_filter[$c_ref]["ajaxaction"]);
		$this->ref_filter[$c_ref]["ajaxaction"][$c] = array("action" => $action, "modo" => $modo);
	}
	public function add_filter_ajax_xtrafield($field, $xml_label, $ix=null) {
		$c_ref = is_numeric($ix) ? $ix : count($this->ref_filter)-1;
		$this->ref_filter[$c_ref]["object"]->add_ajax_field($field, $xml_label);
		if (!isset($this->ref_filter[$c_ref]["ajax_xtrafield"])) $this->ref_filter[$c_ref]["ajax_xtrafield"] = [];
		$c = count($this->ref_filter[$c_ref]["ajax_xtrafield"]);
		$this->ref_filter[$c_ref]["ajax_xtrafield"][$c] = array("field" => $field, "xml_label" => $xml_label);
	}
	public function set_filter_mask($value, $write_mask=0, $unlock_mask=0) {
		$c_ref = count($this->ref_filter)-1;
		$this->ref_filter[$c_ref]["object"]->set_mask($value, $write_mask, $unlock_mask);
		$this->ref_filter[$c_ref]["mask"] = $value;
		$this->ref_filter[$c_ref]["write_mask"] = $write_mask;
		$this->ref_filter[$c_ref]["unlock_mask"] = $unlock_mask;
		if ($unlock_mask == 1) $this->ck_unlock = 1;
		$this->add_filter_prop("onfocus", "recvalue(this)");
		$this->add_filter_prop("onkeyup", "gotofield(this)");
	}
	public function set_filter_list($list, $ck_break=false) {
		$c_ref = count($this->ref_filter)-1;
		$this->ref_filter[$c_ref]["object"]->set_list($list, $ck_break);
		$this->ref_filter[$c_ref]["list"] = array(
			"vals" => $list,
			"ck_break" => $ck_break,
			"ck_new_entry" => 0
		);
	}
	public function set_filter_ref_query($sql, $label, $function="nc_query") {
		$c_ref = count($this->ref_filter)-1;
		$this->ref_filter[$c_ref]["object"]->set_ref_query($sql, $label, $function);
		$this->ref_filter[$c_ref]["ref_qry"] = $sql;
		$this->ref_filter[$c_ref]["ref_qry_label"] = $label;
	}
	public function return_pk($use = "I", $entity = "default", $field = [], $url = []) {
		$this->add_js("drag_lib.js", true);
		$this->add_js("popup_lib.js", true);
		$this->ck_return_pk = 1;
		$this->ck_return_use = $use;
		$this->ck_return_entity = $entity;
		$this->ck_return_field = is_array($field) ? $field : array($field);
		$this->ck_return_url = $url;
	}
	public function add_entity($ent, $table, $key_field, $rel = "1XN", $xtra_key=[]) {
		if ($this->use_translator == 1) $ent = $this->translator->get($ent);
		$c = count($this->ref); // new index
		$obj = new cls_entity($this, $ent, $table, $key_field, $rel, $c);
		$tab_id = count($this->tab_ref)-1;
		$this->form_objs[$c] = $obj;
		$this->ref[$c] = array(
			"object" => $obj, 
			"type" => "entity", 
			"label" => $ent, 
			"table" => $table, 
			"prefix" => ($tab_id >= 0 ? $this->tab_ref[$tab_id]["table"] . "_" : "") . $c . $table, 
			"key_field" => $key_field, 
			"key_condition" => "", 
			"xtra_key" => $xtra_key, 
			"rel" => $rel, 
			"path" => null, 
			"entity_cols" => 1, 
			"ck_collapse"=> 0,
			"entity_layout" => "H", 
			"ck_hidden" => 0,
			"ck_edicao_global" => 0, 
			"ck_multiple_entry" => 0, 
			"ck_separator" => 0,
			"ck_dependent" => 1,
			"req_dependency" => $tab_id >= 0 ? $this->tab_ref[$tab_id]["req_dependency"] : [], 
			"ck_history" => 0,
			"ck_user" => 0,
			"tab_id" => $tab_id, 
			"field_group" => $this->field_group,
			"field" => [],
			"pk" => "id");
		if ($rel == "1XN") $this->ent_1XN[] = $c;
		if ($rel == "NXN") $this->ent_NXN[] = $c;
		return $obj;
	}
	public function set_entity_prefix($str) {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["object"]->set_prefix($str); 
		$this->ref[$c]["prefix"] = $str; 
	}
	public function set_entity_required() {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["object"]->set_required();
	}
	public function set_entity_pk($pk) {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["object"]->set_pk($pk); 
		$this->ref[$c]["pk"] = $pk; 
	}
	/* Depracated 19/09/2023
	public function set_entity_history($dt_field, $key_field) {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["object"]->set_history($dt_field, $key_field); 
		$this->ref[$c]["ck_history"] = 1;
		$this->ref[$c]["history_dt_field"] = $dt_field;
		$this->ref[$c]["history_key_field"] = $key_field;
	}
	*/
	public function set_entity_user($field, $val) {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["object"]->set_user($field, $val); 
		$this->ref[$c]["ck_user"] = 1;
		$this->ref[$c]["user_field"] = $field;
		$this->ref[$c]["user_val"] = $val;
	}
	public function add_entity_condition($sql) {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["object"]->add_condition($sql); 
		$this->ref[$c]["key_condition"] .= ($this->ref[$c]["key_condition"] != "" ? " AND\n\t\t" : "") . "($sql)"; 
		$this->ref[$c]["ignore_fk"] = 0;
	}
	public function set_entity_condition($sql, $ignore_fk=1) {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["object"]->set_condition($sql, $ignore_fk); 
		$this->ref[$c]["key_condition"] = $sql; 
		$this->ref[$c]["ignore_fk"] = $ignore_fk; 
	}
	public function set_entity_html($path) {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["object"]->set_html($path); 
		$this->ref[$c]["path"] = $path; 
	}
	public function set_entity_multiple_entry() {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["ck_multiple_entry"] = 1; 
		$this->ck_multiple_entry = 1; 
		$this->ref[$c]["object"]->set_multiple_entry();
	}
	public function add_entity_global_form($ref=[]) {
		$c = count($this->ref)-1; // existing index
		if (!is_array($ref)) $ref = array($ref);
		$this->ref[$c]["ck_edicao_global"] = 1; 
		$this->ref[$c]["edicao_global_ref"] = $ref; 
		$this->ck_global_form = 1; 
		$this->ref[$c]["object"]->add_global_form($ref);
	}
	public function add_entity_related($condition) {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["object"]->add_related($condition); 
		if (!isset($this->ref[$c]["related"])) $this->ref[$c]["related"] = [];
		$this->ref[$c]["related"][count($this->ref[$c]["related"])] = $condition; 
	}
	/*
	public function set_entity_origem($join_column, $table, $id, $label) {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["entity_join"] = $join_column;
		$this->ref[$c]["entity_table"] = $table;
		$this->ref[$c]["entity_label"] = $label;
		$this->ref[$c]["entity_id"] = $id;
	}
	public function set_entity_entity_group($join_column, $table, $id, $label) {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["group_join"] = $join_column;
		$this->ref[$c]["group_table"] = $table;
		$this->ref[$c]["group_label"] = $label;
		$this->ref[$c]["group_id"] = $id;
	}
	*/
	public function set_entity_origem($qry, $label, $ck_dependent=1) {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["object"]->set_origem($qry, $label, $ck_dependent);
		$this->ref[$c]["entity_qry"] = $qry;
		$this->ref[$c]["entity_label"] = $label;
		$this->ref[$c]["entity_group"] = "";
		$this->set_entity_dependent($ck_dependent);
	}
	public function set_entity_update($qry) {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["object"]->set_update($qry);
		$this->ref[$c]["entity_update_qry"] = $qry;
	}
	public function set_entity_order($sql) {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["object"]->set_order($sql);
		$this->ref[$c]["order"] = $sql;
	}
	public function set_entity_dependent($ck_dependent=1) {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["object"]->set_dependent($ck_dependent); 
	}
	public function set_entity_group($group,$ck_split_selected=1) {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["object"]->set_group($group,$ck_split_selected);
		$this->ref[$c]["entity_group"] = $group;
		$this->ref[$c]["ck_split_selected"] = $ck_split_selected;
	}
	public function set_entity_cols($cols) {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["object"]->set_cols($cols);
		$this->ref[$c]["entity_cols"] = $cols;
	}
	public function set_entity_collapse($ck=1) {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["object"]->set_collapse($ck);
		$this->ref[$c]["ck_collapse"] = $ck;
	}
	public function set_entity_layout($layout) {
		// V = Vertical, H = Horizontal
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["entity_layout"] = $layout;
		$this->ref[$c]["object"]->set_layout($layout); 
	}
	public function enable_entity_duplicate($fields, $parent_field) { // Depracated
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["entity_duplicate_fields"] = $fields;
		$this->ref[$c]["entity_duplicate_parent"] = $parent_field;
	}
	public function enable_entity_ckall() {
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["entity_ckall"] = true;
	}
	private function get_entity_duplicate_str($i, $prefix) {
		$str_fields = "";
		for ($j=0; $j<count($this->ref[$i]["entity_duplicate_fields"]); $j++) {
			for ($k=0; $k<count($this->ref[$i]["field"]); $k++) {
				$field = $this->ref[$i]["object"]->field[$k]->field;
				$type = $this->ref[$i]["object"]->field[$k]->type;
				if ($field == $this->ref[$i]["entity_duplicate_fields"][$j]) {
					if ($type == "hour") {
						$str_fields .= ($str_fields != "" ? "," : "") . "'hor_{$prefix}_{$field}', 'min_{$prefix}_{$field}'";
					} else {
						$str_fields .= ($str_fields != "" ? "," : "") . "'{$prefix}_{$field}'";
					}
					break;
				}
			}
		}
		return $str_fields;
	}
	public function set_key($pk, $label=false) {
		$this->pk = $pk;
		if (is_array($this->pk)) {
			$this->list_param = [];
			foreach ($this->pk as $pk) {
				$this->list_param[$pk] = "GETFROMQRY";
			}
		} else {
			$this->list_param[$pk] = "GETFROMQRY";
		}
		if (!empty($label)) {
			$this->label[0] = [
				"field" => $label,
				"label" => $label,
				"orderby" => false,
				"mode" => "default",
				"alignment" => "left",
				"type" => "",
				"nowrap" => 0,
				"ck_update" => 0,
				"ck_hidden" => 0
			];
		}
	}
	public function set_list_form($buttons, $condition=false) {
		$this->list_form_trigger = $buttons;
		$this->list_form_condition = $condition;
		$trace = debug_backtrace();
		// foreach ($trace as $t) echo $t["class"]."->".$t["function"]."()<br>";
		// if (count($trace) == 1) {
		if (end($trace)["function"] == "set_list_form") { // if set_list_form() was called directly from user uses custom mode
			$this->list_form = "custom";
			$this->add_form_rule($condition="function () { var ck = 0; for (var i=0; i<form['id[]'].length; i++) { if (form['id[]'][i].checked) var ck = 1; } return ck }() == 0", $msg="Nenhum Registro Selecionado", $scope="list");
		} else {
			$this->list_form = "default";
		}
	}
	public function add_label($field, $label = false, $orderby = null, $repeat = 0) {
		if ($this->use_translator == 1) $label = $this->translator->get($label);
		//$this->set_order($orderby != null ? $orderby : $this->getFieldIx($field));
		/* if ($field instanceof Closure) {
			$parser = $field;
			$field = "";
		} */
		$this->label[] = [
			"field" => $field,
			"label" => $label,
			"orderby" => $orderby,
			"mode" => "default",
			"alignment" => "left",
			"label_prop" => [],
			"type" => "",
			"total" => 0,
			"nowrap" => 0,
			"ck_update" => 0,
			"ck_update_global" => 0,
			"ck_hidden" => $label ? 0 : 1,
			"len" => 0
		];
		if (!empty($parser)) {
			$this->set_label_parser($parser);
		}
		if ($repeat == 1) {
			$this->set_label_repeat();
		}
	}
	public function set_label_title($title) {
		$c = count($this->label)-1; // existing index
		$this->label[$c]["title"] = $title;
	}
	public function add_label_prop($prop, $value="") {
		$c = count($this->label)-1; // existing index
		if (isset($this->label[$c]["label_prop"][$prop]))
			$this->label[$c]["label_prop"][$prop] .= ";" . $value;
		else
			$this->label[$c]["label_prop"][$prop] = $value;
	}
	public function add_label_holder($tag="div", $prop=[]) {
		$c = count($this->label)-1; // existing index
		$this->label[$c]["holder"] = ["tag" => $tag, "prop" => $prop];
	}
	public function set_label_hidden() {
		$c = count($this->label)-1; // existing index
		$this->label[$c]["ck_hidden"] = 1;
	}
	public function set_label_sum($depth=2, $un="", $align=false) {
		$c = count($this->label)-1; // existing index
		if (!$align) $align = $this->label[$c]["alignment"];
		$this->label[$c]["ck_show_total"] = 1;
		$this->set_label_numeric($depth, $un, $align);
	}
	public function set_label_count($align="center") { // Not implemented
		$c = count($this->label)-1; // existing index
		if (!$align) $align = $this->label[$c]["alignment"];
		$this->label[$c]["ck_show_count"] = 1;
	}
	public function set_label_numeric($depth=2, $un="", $align="right") {
		$c = count($this->label)-1; // existing index
		$this->label[$c]["mode"] = "NUM";
		$this->label[$c]["depth"] = $depth;
		$this->label[$c]["un"] = $un;
		$this->label[$c]["total"] = 0;
		$this->label[$c]["nowrap"] = 1;
		$this->label[$c]["alignment"] = $align;
	}
	public function set_label_repeat($params=[true]) {
		$c = count($this->label)-1; // existing index
		$this->label[$c]["repeat"] = $params;
		$this->ck_repeat_row = "R";
		if (!empty($params["sum"]) && empty($this->ck_csv)) {
			$this->ckGrp[$c+1] = false;
			$this->ixGrp[$c+1] = $params["sum"];
			$this->ixSum[$c+1] = $this->getFieldLbl($this->label[$c]["field"]);
			$align = $this->label[$c]["alignment"];
			$this->add_label(null, "&nbsp;");
			$this->set_label_alignment($align);
			$this->set_label_repeat(["total"=>true]);
			$this->add_label_prop("style", "font-weight:bold");
			$this->set_label_parser(function($str,$r,$i,$pLbl) {
				$ixGrp = $this->ixGrp[$pLbl];
				$ixSum = $this->ixSum[$pLbl];
				if ($r[$ixGrp] != $this->ckGrp[$pLbl]) {
					$this->totLbl[$pLbl] = 0;
					$this->ckGrp[$pLbl] = $r[$ixGrp];
				}
				$this->totLbl[$pLbl] += $r[$ixSum];
				if (!empty($this->totLbl[$pLbl]) && ($i == count($this->res_list)-1 || $r[$ixGrp] != $this->res_list[$i+1][$ixGrp])) return number_format($this->totLbl[$pLbl],2,",",".");
			});
		}
	}
	public function set_label_group() {
		$c = count($this->label)-1; // existing index
		$this->label[$c]["norepeat"] = 1;
		$this->ck_repeat_row = "G";
	}
	public function set_label_id($id) {
		$c = count($this->label)-1; // existing index
		$this->label[$c]["tagId"] = $id;
	}
	public function set_label_parser($parser) {
		$c = count($this->label)-1; // existing index
		$this->label[$c]["parser"] = $parser;
	}
	public function set_label_length($len, $ck_remove_tags = 0) {
		$c = count($this->label)-1; // existing index
		$this->label[$c]["len"] = $len;
		$this->label[$c]["ck_remove_tags"] = $ck_remove_tags;
	}
	public function set_label_flag_mode($edit=1) {
		$c = count($this->label)-1; // existing index
		$this->label[$c]["mode"] = "flag";
		$this->label[$c]["alignment"] = "center";
		//$this->label[$c]["ck_update"] = "0";
		if ($edit == 1) $this->set_label_edit_mode("checkbox");
	}
	public function set_label_edit_mode($type, $update_key=false, $table=false, $global=false) {
		$this->set_list_form([$this->lang_button_label => ""]);
		$c = count($this->label)-1; // existing index
		$c_field = count($this->ref_list); // new index
		$obj = new cls_field("label", $this, $this->label[$c]["field"], $this->label[$c]["label"], $type); // , 0, 1, $c_field);
		$this->ref_list[$c_field] = $obj;
		//$this->ref_list[$c_field]["type"] = $type;
		//if ($type == "checkbox") $this->label[$c]["alignment"] = "center";
		$this->label[$c]["object_id"] = $c_field;
		$this->label[$c]["ck_update"] = 1;
		$this->label[$c]["nowrap"] = 1;
		$this->label[$c]["type"] = $type;
		$this->label[$c]["update_key"] = !empty($update_key) ? $update_key : $this->pk;
		$this->label[$c]["table"] = !empty($table) ? $table : $this->table;
		$this->label[$c]["prop"] = [];
		$this->label[$c]["xtra_field"] = [];
		$this->label[$c]["alignment"] = "left";
		$this->label[$c]["ck_req"] = 0;
		$this->label[$c]["ck_log"] = 0;
		if ($type == "date" || $type == "datetime" || $type == "daterange" || $type == "month") {
			$this->add_label_field_prop("onfocus", "recvalue(this)");
			$this->add_label_field_prop("onkeyup", "gotofield(this)");
		}
		if ($type == "hidden") {
			$this->set_label_hidden();
		}
		$this->ck_flag_mode = 1;
		if ($global) $this->set_label_edit_mode_global();
		return $obj;
	}
	public function set_label_edit_mode_global() {
		$c = count($this->label)-1; // existing index
		$this->label[$c]["ck_update_global"] = "1";
		$this->ck_flag_mode == 1;
		$this->add_js("fn_findpos.js", true);
	}
	public function add_label_field_prop($prop, $value=null) {
		$c = count($this->label)-1;
		$object_id = $this->label[$c]["object_id"];
		$this->ref_list[$object_id]->add_prop($prop, $value);
		preg_match("[function ([a-zA-Z0-9_]+)\(([a-zA-Z0-9_]+)\)]", $value, $match);
		if (count($match) > 0 && ($match[2] == "" || $match[2] == "rs" || $match[2] == "res")) {
			$this->label[$c]["prop_condition"][$prop] = $match[1];
			$value = null;
		}
		if (isset($this->label[$c]["prop"][$prop]))
			$this->label[$c]["prop"][$prop] .= ";" . $value;
		else
			$this->label[$c]["prop"][$prop] = $value;
	}
	public function add_label_field_xtra($field, $val, $condition=false) {
		$c = count($this->label)-1;
		$this->label[$c]["xtra_field"][] = array("field" => $field, "val" => $val, "condition" => $condition);
	}
	public function set_label_field_comment($value,$pos="after") {
		$c = count($this->label)-1; // existing index
		$object_id = $this->label[$c]["object_id"];
		$this->ref_list[$object_id]->set_comment($value,$pos);
		if ($pos == "after")
			$this->label[$c]["comment"] = $value;
		if ($pos == "before")
			$this->label[$c]["comment_before"] = $value;
	}
	public function set_label_field_qry($qry, $ix, $label, $group_label = null, $xtra_fields = null) {
		$c = count($this->label)-1; // existing index
		$object_id = $this->label[$c]["object_id"];
		$this->ref_list[$object_id]->set_qry($qry, $ix, $label, $group_label, $xtra_fields);
		$this->label[$c]["qry"] = array(
			"sql" => $qry, 
			"ix" => $ix, 
			"label" => $label, 
			"group_label" => $group_label,
			"xtra_fields" => $xtra_fields,
			"ck_new_entry" => 0,
			"ajax" => 0
		);
		$this->label[$c]["ajax"] = 0;
	}
	public function set_label_field_list($list, $ck_break=false) {
		$c = count($this->label)-1; // existing index
		$object_id = $this->label[$c]["object_id"];
		$this->ref_list[$object_id]->set_list($list, $ck_break);
		$this->label[$c]["list"] = array(
			"vals" => $list, 
			"ck_break" => $ck_break, 
			"ck_new_entry" => 0
		);
	}
	public function set_label_field_options($opt, $c_ref=false) {
		if (!$c_ref) $c_ref = count($this->label)-1; // existing index
		$object_id = $this->label[$c_ref]["object_id"];
		$this->ref_list[$object_id]->set_options($opt);
		if (isset($opt["ck_readonly"])) {
			$this->label[$c_ref]["ck_readonly"] = $opt["ck_readonly"];
		}
		if (isset($opt["ck_disabled"])) {
			$this->label[$c_ref]["ck_disabled"] = $opt["ck_disabled"];
		}
	}
	public function add_list_col($col) {
		$this->add_label($col);
		$this->set_label_hidden();
	}
	public function add_row_prop($prop, $value, $condition=false) {
		$this->row_prop[$prop] = [ "val" => $value, "condition" => $condition ];
	}
	public function add_list_row($field, $ck_hidden=0) {
		$this->list_row[] = [
			"field" => $field,
			"ck_hidden" => $ck_hidden,
			"ck_update" => 0,
			"prop" => []
		];
	}
	public function set_list_row_edit_mode($type, $label=false, $update_key=false, $table=false) {
		$c = count($this->list_row)-1; // existing index
		$c_field = count($this->ref_list); // new index
		$obj = new cls_field("label", $this, $this->list_row[$c]["field"], "", $type); // , 0, 0, $c_field);
		if (!empty($label)) $obj->add_prop("placeholder", $label);
		$this->ref_list[$c_field] = $obj;
		$this->list_row[$c]["object_id"] = $c_field;
		$this->list_row[$c]["ck_update"] = "1";
		$this->list_row[$c]["nowrap"] = "1";
		$this->list_row[$c]["type"] = $type;
		$this->list_row[$c]["label"] = $label;
		$this->list_row[$c]["update_key"] = !empty($update_key) ? $update_key : $this->pk;
		$this->list_row[$c]["table"] = !empty($table) ? $table : $this->table;
		$this->list_row[$c]["prop"] = [];
		$this->list_row[$c]["xtra_field"] = [];
		$this->list_row[$c]["ck_req"] = 0;
		$this->list_row[$c]["ck_bak"] = 1;
		$this->list_row[$c]["ck_log"] = 0;
		$this->list_row[$c]["ck_collapse"] = 1;
		$this->list_row[$c]["comment"] = "";
		$this->list_row[$c]["comment_before"] = "";
		$this->ck_flag_mode = 1;
		return $obj;
	}
	public function set_list_row_field_comment($value,$pos="after") {
		$c = count($this->list_row)-1; // existing index
		$object_id = $this->list_row[$c]["object_id"];
		$this->ref_list[$object_id]->set_comment($value,$pos);
		if ($pos == "after")
			$this->list_row[$c]["comment"] = $value;
		if ($pos == "before")
			$this->list_row[$c]["comment_before"] = $value;
	}
	public function add_list_row_field_prop($prop, $value=null, $ix=null) {
		$c = count($this->list_row)-1; // existing index
		$object_id = $this->list_row[$c]["object_id"];
		$this->ref_list[$object_id]->add_prop($prop, $value);
		if (isset($this->list_row[$c]["prop"][$prop]))
			$this->list_row[$c]["prop"][$prop] .= ";" . $value;
		else
			$this->list_row[$c]["prop"][$prop] = $value;
	}
	public function enable_list_row_field_log($signature) {
		$c = count($this->list_row)-1; // existing index
		$object_id = $this->list_row[$c]["object_id"];
		$this->ref_list[$object_id]->enable_log($signature);
		$this->list_row[$c]["ck_log"] = 1;
		$this->list_row[$c]["log_signature"] = $signature;
	}
	public function set_list_row_field_hidden($field = null, $val = "", $condition = "==") {
		if ((string)$val == "==" || (string)$val == "!=" || (string)$val == ">" || (string)$val == "<" || (string)$val == ">=" || (string)$val == "<=") {
			list($val,$condition) = array($condition,$val);
		}
		$this->set_list_row_options(array("ck_hidden" => array("field" => $field, "op" => $condition, "val" => $val)));
	}
	public function set_list_row_options($opt) {
		$c = count($this->list_row)-1;
		$object_id = $this->list_row[$c]["object_id"];
		$this->ref_list[$object_id]->set_options($opt);
		if (isset($opt["ck_hidden"])) {
			$this->list_row[$c]["ck_hidden"] = $opt["ck_hidden"];
		}
	}
	public function set_order($field, $direction = "ASC", $pos = "END") {
		$c = count($this->order); // new index
		$this->order[$c] = array(
			"field" => $field,
			"direction" => $direction
		);
	}
	public function set_group($field) {
		$this->group[] = [ "field" => $field ];
	}
	public function select_distinct() {
		$this->ck_distinct = 1;
	}
	public function add_js($str, $basedir=false) {
		if ($basedir) $str = "$this->js_dir/$str";
		$ck_exists = 0;
		foreach ($this->js_ref as $js) {
			if ($js == $str) {
				$ck_exists = 1;
				break;
			}
		}
		if ($ck_exists == 0) $this->js_ref[] = $str; 
	}
	public function add_css($str) {
		$ck_exists = 0;
		for ($i=0; $i<count($this->css_ref); $i++) {
			if ($this->css_ref[$i] == $str) {
				$ck_exists = 1;
				break;
			}
		}
		if ($ck_exists == 0) {
			$c = count($this->css_ref); // new index
			$this->css_ref[$c] = $str; 
		}
	}
	public function add_style($str, $scope="form") {
		$this->cssStyle[$scope][] = $str; 
	}
	public function add_link($url, $params, $holder="", $popup=0, $target="_self") {
		if (!is_array($params)) {
			$params = array(
				"label" => $params,
				"holder" => $holder,
				"target" => $popup == 1 ? "_blank" : "_self"
			);
		}
		// params holder (url), col (coluna que recebe o link), target
		if (isset($params["thumb"])) $params["holder"] = $params["thumb"];
		$name = isset($params["label"]) ? $params["label"] : "";
		$holder = isset($params["holder"]) ? $params["holder"] : (isset($params["col"]) ? $params["col"] : null);
		$target = isset($params["target"]) ? $params["target"] : "_self";
		$explode = isset($params["explode"]) ? $params["explode"] : false;
		$onclick = isset($params["onclick"]) ? $params["onclick"] : false;
		$condition = isset($params["condition"]) ? $params["condition"] : false;
		$this->ck_label_link = -1;
		for ($i=0; $i<count($this->label); $i++) {
			if ($this->getFieldLbl($holder) == $this->getFieldLbl($this->label[$i]["field"])) {
				$this->label[$i]["link"][] = [
					"url" => $url,
					"name" => $name,
					"holder" => $holder,
					"explode" => $explode,
					"target" => $target,
					"onclick" => $onclick,
					"condition" => $condition
				];
				$this->ck_label_link = $i;
				break;
			}
		}
		if ($this->ck_label_link < 0) {
			$conditions = [];
			if ($condition) $conditions[] = $condition;
			$this->link_ref[] = [
				"url" => $url,
				"name" => $name,
				"holder" => $holder,
				"target" => $target,
				"onclick" => $onclick,
				"id" => isset($params["id"]) ? $params["id"] : false,
				"maxsize" => isset($params["maxsize"]) ? $params["maxsize"] : 0,
				"style" => isset($params["style"]) ? $params["style"] : "",
				"conditions" => $conditions
			];
		}
	}
	public function add_link_param($param, $op, $val) { // to be depracated; usar set_link_condition()
		if ($this->ck_label_link >= 0) {
			//print_r([ "field" => $param, "op" => $op, "val" => $val ]);
			$c = count($this->label[$this->ck_label_link]["link"])-1; // existing index
			$this->label[$this->ck_label_link]["link"][$c]["param"] = [ "col" => $param, "op" => $op, "val" => $val ];
		} else {
			$c = count($this->link_ref)-1; // existing index
			$c_ref = count($this->link_ref[$c]["conditions"]); // new index
			$this->link_ref[$c]["conditions"][$c_ref]["param"] = $param;
			$this->link_ref[$c]["conditions"][$c_ref]["op"] = $op;
			$this->link_ref[$c]["conditions"][$c_ref]["val"] = $val;
		}
	}
	public function add_link_condition($cmd) { // depracated, remove after 20/10/2023
		$this->set_link_condition($cmd);
	}
	public function set_link_condition($cmd) {
		if ($this->ck_label_link >= 0) {
			$c = count($this->label[$this->ck_label_link]["link"])-1; // existing index
			$this->label[$this->ck_label_link]["link"][$c]["condition"] = $cmd;
		} else {
			$c = count($this->link_ref)-1; // existing index
			$this->link_ref[$c]["conditions"][] = $cmd;
		}
	}
	public function set_link_repeat() {
		$this->ck_repeat_link = 1;
	}
	public function add_color_param($param, $op, $val, $color) {
		$c = count($this->color_ref); // new index
		if (substr($color,0,1) == "#") $color = substr($color,1);
		$this->color_ref[$c]["color"] = $color;
		$this->color_ref[$c]["param"] = [ "col" => $param, "op" => $op, "val" => $val ];
	}
	public function add_header($hdr, $src=false) {
		$this->header[] = $src ? [$hdr, $src] : $hdr;
	}
	public function add_header_total($hdr) {
		$this->hdr_total[] = $hdr;
	}
	public function remove_header() {
		$this->ck_header = 0;
	}
	public function set_total_label($label, $field=false) {
		$this->total_label = $label;
		$this->total_field = $field;
	}
	public function set_total_sum($field) {
		$this->total_sum = $field;
	}
	public function display_total() {
		$this->ck_display_total = true;
	}
	public function add_graph($fields, $type) {
		$c = count($this->graph); // new index
		$this->graph[$c] = array(
			"fields" => is_array($fields) ? $fields : array($fields),
			"type" => $type
		);
	}
	public function set_label_center() {
		$this->set_label_alignment("center");
	}
	public function set_label_left() {
		$this->set_label_alignment("left");
	}
	public function set_label_right() {
		$this->set_label_alignment("right");
	}
	public function set_label_alignment($val) {
		$c = count($this->label)-1; // existing index
		$this->label[$c]["alignment"] = $val;
	}
	public function set_label_nowrap() {
		$c = count($this->label)-1; // existing index
		$this->label[$c]["nowrap"] = 1;
	}
	public function add_subtotal($field, $label, $val) {
		$this->subtotal[$field][] = [ "label" => $label, "val" => $val ];
	}
	public function add_group_key($field, $order=null, $direction="ASC") {
		$c = count($this->group_key);
		$this->group_key[$c]["field"] = $field;
		$this->group_key[$c]["order"] = $order == null ? "grp$c" : $order;
		$this->group_key[$c]["direction"] = $direction;
	}
	public function set_group_color($field) {
		$c = count($this->group_key)-1;
		$this->group_key[$c]["color"] = $field;
	}
	public function set_recursive_mode($ix_hierarquia, $levels=false) {
		if ($this->db == "MYSQL" && !$levels) exit("\$levels must be declared when method is used with MySQL");
		$this->ck_recursive = $this->db == "MYSQL" ? $levels : 1;
		$this->recursive_index = $ix_hierarquia;
	}
	/* depracated
	public function set_csv_export($ref) {
		$this->ck_csv = 1;
		$this->csv_export_ref = $ref;
	} */
	public function use_csv_option($ck=1) {
		$this->ck_csv = $ck;
	}
	public function use_xls_option($ck=1) {
		$this->ck_xls = $ck;
		$this->add_js("base64.js", true);
	}
	public function use_reorder() {
		$this->ck_reorder = 1;
		$this->add_js("table_reorder_lib.js", true);
	}
	public function add_condition($str) {
		$this->condition[] = $str;
	}
	public function add_related($join, $use="LC") { // L = List, F = Form, C = Count (for cls_report only)
		$this->related[] = [
			"sql" => $join,
			"use" => $use,
		];
	}
	public function add_sql_cmd($sql, $use) {
		$this->sql_cmd[] = [
			"sql" => $sql,
			"use" => $use
		];
	}
	public function add_dependency($table, $ix, $label = null) {
		$this->dependency[] = [
			"table" => $table,
			"ix" => $ix,
			"label" => $label != null ? $label : $table
		];
	}
	public function add_delete_cascade($table, $ix) {
		$this->delete_cascade[] = [
			"table" => $table,
			"ix" => $ix
		];
	}
	public function add_update_cascade($table, $ix) {
		$this->update_cascade[] = [
			"table" => $table,
			"ix" => $ix
		];
	}
	public function set_groupbyactive($field = false) {
		if (!$field) {
			$temp = explode(" ", $this->table);
			$qry_table = $temp[count($temp)-1];
			$field = "$qry_table.active";
		}
		$this->set_groupby_active_flag($field);
	}
	public function set_groupbyativo($field = false) {
		if (!$field) {
			$temp = explode(" ", $this->table);
			$qry_table = $temp[count($temp)-1];
			$field = "$qry_table.ativo";
		}
		$this->set_groupby_active_flag($field);
	}
	public function set_groupby_active_flag($field) {
		$this->ck_active = 1; 
		$this->active_field = $field; 
	}
	public function set_collapse() {
		$this->ck_collapse = 1; 
	}
	public function set_list_res($res) {
		$this->res_list = $res;
		$this->list_modo = "custom";
	}
	public function set_list_qry($qry) {
		if ($this->debug == 1) $this->show_debug($qry);
		$this->list_qry = $qry;
		$this->set_list_res(nc_query($qry));
	}
	public function add_tab($label, $table=false, $key=false) {
		if ($this->use_translator == 1) $label = $this->translator->get($label);
		$obj = new cls_tab($label, $table, $key);
		$this->tab_ref[] = [
			"object" => $obj,
			"label" => $label,
			"table" => $table,
			"key" => $key,
			"req_dependency" => [],
			"actions" => []
		];
		if (!empty($table)) {
			if (strpos($key,".") === false) {
				$this->add_related("LEFT JOIN $table ON $table.$key = $this->table.$this->pk", $use="FU");
				$this->add_delete_cascade($table, $key);
			} else {
				$this->add_related("LEFT JOIN $table ON $table.id = $key", $use="FU");
			}
		}
		return $obj;
	}
	public function config_tab_req($field="tab") {
		$c_ref = count($this->tab_ref)-1; // existing index
		$this->tab_ref[$c_ref]["req_dependency"][count($this->tab_ref[$c_ref]["req_dependency"])] = $field;
	}
	public function add_tab_action($action) {
		$c_ref = count($this->tab_ref)-1; // existing index
		$this->tab_ref[$c_ref]["actions"][count($this->tab_ref[$c_ref]["actions"])] = $action;
	}
	public function add_field($field, $label, $type, $ck_req=0, $ck_qry=1) {
		if ($type == "filteredtext") $type = "fieldfilter";
		if ($this->use_translator == 1) $label = $this->translator->get($label);
		$c = count($this->ref); // new index
		$obj = new cls_field("field", $this, $field, $label, $type, $ck_req, $ck_qry, $c);
		$tab_id = count($this->tab_ref)-1;
		if ($tab_id >= 0 && $this->tab_ref[$tab_id]["label"] == false) $tab_id = -1;
		if ($tab_id >= 0 && $this->tab_ref[$tab_id]["object"]->ck_readonly) $obj->set_readonly();
		$this->form_objs[$c] = $obj;
		$this->ref[$c] = array(
			"object" => $obj, 
			"field" => $field, 
			"label" => $label, 
			"type" => $type, 
			"ck_req" => $ck_req, 
			"req_dependency" => $ck_req == 1 && $tab_id >= 0 ? $this->tab_ref[$tab_id]["req_dependency"] : [], 
			"ck_qry" => $ck_qry, 
			"prop" => [],
			"field_group" => $this->field_group, 
			"ck_hidden" => 0,
			"ck_separator" => 0, 
			"ck_bak" => 0,
			"ck_log" => 0,
			"ck_readonly" => 0,
			"file_name_mask" => "SELF",
			"tab_id" => $tab_id);
		if ($this->use_translator == 1) $obj->set_translator($this->translator);
		if ($this->use_uppercase) $obj->set_uppercase();
		if ($type == "color") {
			$this->add_js("fn_buildcolortable.js", true);
		} else if ($type == "publisher") {
			$this->publisher_ref[count($this->publisher_ref)] = $field;
			$this->ref[$c]["publisher_img_action"] = "$this->js_dir/publisher_lib.img.php";
			$this->ref[$c]["publisher_img_dir"] = "imagens/";
			$this->add_js("publisher_lib.js", true);
			$this->add_onload_action("_initPublisher()");
		} else if ($type == "fieldfilter") {
			$this->add_js("ajax_lib.v2.js", true);
		} else if ($type == "fieldlist") {
			$this->add_js("fieldlist.js", true);
			$this->add_js("drag_lib.js", true);
		} else if ($type == "password" && $ck_qry==1) {
			$this->add_field("confirm_$field", "$this->lang_password_confirm $label", "password", $ck_req, $ck_qry=0);
			$this->add_form_rule("form.$field.value != \"\" && form.$field.value != form.confirm_$field.value", "$this->lang_js_field \\\"$label\\\" $this->lang_js_password");
		} else if ($type == "file") {
			$this->ref[$c]["ck_file_link"] = true;
		}
		return $obj;
	}
	public function get_field_def($field, $ref=false) {
		if (!$ref) {
			if ($this->builder_ref == "form") $ref = $this->ref;
			if ($this->builder_ref == "filter") $ref = $this->ref_filter ? $this->ref_filter : $this->ref;
		}
		if ($ref instanceof cls_entity) $ref = $ref->field;
		foreach ($ref as $obj) {
			if (is_array($obj)) $obj = $obj["object"];
			if ($obj instanceof cls_field && $this->getFieldLbl($obj->field) == $field) return $obj;
		}
	}
	public function set_field_options($opt, $c_ref=false) {
		$ck_c_ref = $c_ref;
		if (!$c_ref) $c_ref = count($this->ref)-1;
		if (!$ck_c_ref && $this->ref[$c_ref]["type"] == "password") $this->set_field_options($opt, $c_ref-1);
		$this->ref[$c_ref]["object"]->set_options($opt);
		if (isset($opt["ajax"])) {
			$this->ref[$c_ref]["ajaxqry"] = $opt["ajax"]["qry"];
			$this->ref[$c_ref]["ajaxid"] = $opt["ajax"]["id"];
			$this->ref[$c_ref]["ajaxlabel"] = $opt["ajax"]["label"];
			$this->ref[$c_ref]["ajaxfilter"] = isset($opt["ajax"]["filter"]) ? $opt["ajax"]["filter"] : $opt["ajax"]["label"];
			$this->ref[$c_ref]["ajaxaction"] = isset($opt["ajax"]["action"]) ? $opt["ajax"]["action"] : [];
			$this->ref[$c_ref]["ajax_xtrafield"] = isset($opt["ajax"]["xtrafield"]) ? $opt["ajax"]["xtrafield"] : [];
		}
		if (isset($opt["new_entry"])) {
			$this->ref[$c_ref]["new_entry"] = $opt["new_entry"];
			$field = $this->ref[$c_ref]["field"];
			$this->add_entity_field_prop("onchange", "document.getElementById(this.id.replace('$field','{$field}_entry')).style.display=this.value=='NEW'?'':'none'");
		}
		if (isset($opt["entity"])) {
			$this->ref[$c_ref]["entity"] = $opt["entity"];
		}
		if (isset($opt["ck_req"])) {
			$this->ref[$c_ref]["ck_req"] = $opt["ck_req"];
		}
		if (isset($opt["ck_readonly"])) {
			$this->ref[$c_ref]["ck_readonly"] = $opt["ck_readonly"];
		}
		if (isset($opt["ck_disabled"])) {
			$this->ref[$c_ref]["ck_disabled"] = $opt["ck_disabled"];
		}
		if (isset($opt["ck_hidden"])) {
			$this->ref[$c_ref]["ck_hidden"] = $opt["ck_hidden"];
		}
	}
	public function set_field_mask($value, $write_mask=0, $unlock_mask=0) {
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_mask($value, $write_mask, $unlock_mask);
		$this->ref[$c_ref]["mask"] = $value;
		$this->ref[$c_ref]["write_mask"] = $write_mask;
		$this->ref[$c_ref]["unlock_mask"] = $unlock_mask;
		if ($unlock_mask == 1) $this->ck_unlock = 1;
		$this->add_field_prop("onfocus", "recvalue(this)");
		$this->add_field_prop("onkeyup", "gotofield(this)");
	}
	public function set_field_default($value) {
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_default($value);
		$this->ref[$c_ref]["default"] = $value;
	}
	public function ck_field_email() {
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->ck_email();
		$this->ck_verify_email = 1;
	}
	public function set_field_uppercase_disable() {
		$c_ref = count($this->ref)-1;
		if (isset($this->ref[$c_ref]["prop"]["style"]) &&
			strpos($this->ref[$c_ref]["prop"]["style"], "text-transform:uppercase;") !== false) {
			$this->ref[$c_ref]["prop"]["style"] = str_replace("text-transform:uppercase;","",$this->ref[$c_ref]["prop"]["style"]);
		}
		if (isset($this->ref[$c_ref]["prop"]["onblur"]) &&
			strpos($this->ref[$c_ref]["prop"]["onblur"], "this.value=this.value.toUpperCase();") !== false) {
			$this->ref[$c_ref]["prop"]["onblur"] = str_replace("this.value=this.value.toUpperCase();","",$this->ref[$c_ref]["prop"]["onblur"]);
		}
	}
	public function add_field_bak() {
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->add_bak();
		$this->ref[$c_ref]["ck_bak"] = 1;
	}
	public function set_field_comment($value,$pos="after") {
		if ($this->use_translator == 1) $value = $this->translator->get($value);
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_comment($value,$pos);
		if ($pos == "after")
			$this->ref[$c_ref]["comment"] = $value;
		if ($pos == "before")
			$this->ref[$c_ref]["comment_before"] = $value;
	}
	public function set_field_hidden($field = null, $val = "", $condition = "==") {
		if ((string)$val == "==" || (string)$val == "!=" || (string)$val == ">" || (string)$val == "<" || (string)$val == ">=" || (string)$val == "<=") {
			list($val,$condition) = array($condition,$val);
		}
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_hidden($field, $condition, $val);
	}
	public function set_field_required($field = null, $val = "", $condition = "==") {
		if ((string)$val == "==" || (string)$val == "!=" || (string)$val == ">" || (string)$val == "<" || (string)$val == ">=" || (string)$val == "<=") {
			list($val,$condition) = array($condition,$val);
		}
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_required($field, $condition, $val);
	}
	public function set_field_ajaxtable($table, $id="id", $label="nome", $filter=null) {
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_ajaxtable($table, $id, $label, $filter);
		$this->ref[$c_ref]["ajaxtable"] = $table;
		$this->ref[$c_ref]["ajaxid"] = $id;
		$this->ref[$c_ref]["ajaxlabel"] = $label;
		$this->ref[$c_ref]["ajaxfilter"] = $filter == null ? $label : $filter;
		$this->ref[$c_ref]["ajaxcondition"] = "";
		if (!isset($this->ref[$c_ref]["ajaxaction"])) 
			$this->ref[$c_ref]["ajaxaction"] = [];
		$this->ref[$c_ref]["ajax_xtrafield"] = [];
	}
	public function set_field_ajaxqry($qry, $id="id", $label="nome", $filter=null) {
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_ajaxqry($qry, $id, $label, $filter);
		$this->ref[$c_ref]["ajaxqry"] = $qry;
		$this->ref[$c_ref]["ajaxid"] = $id;
		$this->ref[$c_ref]["ajaxlabel"] = $label;
		$this->ref[$c_ref]["ajaxfilter"] = $filter == null ? $label : $filter;
		$this->ref[$c_ref]["ajaxaction"] = [];
		$this->ref[$c_ref]["ajax_xtrafield"] = [];
	}
	public function set_field_ajaxcondition($condition) {
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_ajaxcondition($condition);
		$this->ref[$c_ref]["ajaxcondition"] = $condition;
	}
	public function set_field_ajaxpath($url, $tags=[], $ref=false) {
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_ajax_path($url, $tags, $ref);
	}
	public function set_field_ajax_group($group) {
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_ajax_group($group);
		$this->ref[$c_ref]["ajaxgroup"] = $group;
	}
	public function set_field_ajaxaction($action, $modo="S", $ix=null) {
		$this->add_field_ajaxaction($action, $modo, $ix);
	}
	public function add_field_ajaxaction($action, $modo="S", $ix=null) {
		// $modo => S = SELECT, R = RESET
		$c_ref = is_numeric($ix) ? $ix : count($this->ref)-1;
		$this->ref[$c_ref]["object"]->add_ajax_action($action, $modo);
		if (!isset($this->ref[$c_ref]["ajaxaction"])) $this->ref[$c_ref]["ajaxaction"] = [];
		foreach ($this->ref[$c_ref]["ajaxaction"] as $_ref) {
			if ($_ref["action"] == $action && $_ref["modo"] == $modo) return false;
		} 
		$c = count($this->ref[$c_ref]["ajaxaction"]);
		$this->ref[$c_ref]["ajaxaction"][$c] = array("action" => $action, "modo" => $modo);
	}
	public function add_field_ajax_xtrafield($field, $xml_label=false, $ix=null) {
		if (!$xml_label) $xml_label = $field;
		$c_ref = is_numeric($ix) ? $ix : count($this->ref)-1;
		$this->ref[$c_ref]["object"]->add_ajax_field($field, $xml_label, $ix);
		if (!isset($this->ref[$c_ref]["ajax_xtrafield"])) $this->ref[$c_ref]["ajax_xtrafield"] = [];
		$c = count($this->ref[$c_ref]["ajax_xtrafield"]);
		$this->ref[$c_ref]["ajax_xtrafield"][$c] = array("field" => $field, "xml_label" => $xml_label);
	}
	public function set_field_currency($value) {
		$this->set_field_comment($value,"before");
	}
	public function add_field_prop($prop, $value=null, $ix=null) {
		$c_ref = is_numeric($ix) ? $ix : count($this->ref)-1;
		$this->ref[$c_ref]["object"]->add_prop($prop, $value);
		if (isset($this->ref[$c_ref]["prop"][$prop]))
			$this->ref[$c_ref]["prop"][$prop] .= ";" . $value;
		else
			$this->ref[$c_ref]["prop"][$prop] = $value;
	}
	public function set_field_readonly() {
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_readonly();
		$this->ref[$c_ref]["ck_readonly"] = 1;
	}
	public function add_field_list($list, $ck_break=false) {
		$this->set_field_list($list, $ck_break);
	}
	public function set_field_list($list, $ck_break=false) {
		if ($this->use_translator == 1) {
			$keys = array_keys($list);
			for ($i=0; $i<count($keys); $i++) {
				$list[$keys[$i]] = $this->translator->get($list[$keys[$i]]);
			}
		}
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_list($list, $ck_break);
		$this->ref[$c_ref]["list"] = array(
			"vals" => $list,
			"ck_break" => $ck_break,
			"ck_new_entry" => 0
		);
	}
	public function add_field_qry($qry, $ix, $label, $group_label = null, $xtra_fields = null) {
		$this->set_field_qry($qry, $ix, $label, $group_label, $xtra_fields);
	}
	public function set_field_qry($qry, $ix, $label, $group_label = null, $xtra_fields = null) {
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_qry($qry, $ix, $label, $group_label, $xtra_fields);
		$this->ref[$c_ref]["qry"] = array(
			"sql" => $qry, 
			"ix" => $ix, 
			"label" => $label, 
			"group_label" => $group_label,
			"xtra_fields" => $xtra_fields
		);
		$this->ref[$c_ref]["ck_new_entry"] = 0;
		// $this->ref[$c_ref]["ajax"] = $this->ck_ajax_qry($qry, "field"); // called on field->set_qry()
		$this->ref[$c_ref]["ajax"] = $this->ref[$c_ref]["object"]->ajax;
	}
	public function set_field_qry_pk($pk) {
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_qry_pk($pk);
		$this->ref[$c_ref]["qry"]["pk"] = $pk;
	}
	public function set_field_index($ix) { // use to get values for non query fields ($ck_query = 0)
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["field_qry"] = $ix;
		$this->ref[$c_ref]["object"]->set_index($ix);
	}
	public function enable_field_log($signature) {
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->enable_log($signature);
		$this->ref[$c_ref]["ck_log"] = 1;
		$this->ref[$c_ref]["log_signature"] = $signature;
	}
	public function add_html($str) {
		$this->add_field("", "", $type="html", $ck_req=0, $ck_qry=0);
		$this->set_field_comment($str);
	}
	public function set_field_val($field, $val) {
		$c_ref = count($this->ref_tbl_val)-1;
		$this->ref_tbl_val[$field] = $val;
	}
	public function add_insert_field($field, $val) {
		$this->insert_val[] = [ "field" => $field, "val" => $val ];
	}
	public function add_form_separator() {
		$c_ref = count($this->ref)-1; // existing index
		$this->ref[$c_ref]["ck_separator"] = 1;
	}
	public function add_form_rule_unique($field, $param=false, $url=false) {
		if (!is_array($field)) $field = [$field];
		if ($param) $this->unique_condition[$this->unique_ix] = $param;
		if (!$url) $url = "$this->document_name_xml?ck_xml=1&obj=ck_unique&unique_ix=$this->unique_ix&fields=" . implode(",",$field) . "&" . preg_replace("/(id|urlKey|_session_id)=([a-z0-9]+)(\&)*/i","",$_SERVER["QUERY_STRING"]);
		foreach ($field as $f) $url .= "&$f=[$f]";
		if ($param) {
			preg_match_all("[\[([a-zA-Z0-9_])+\]]", $param, $match);
			foreach ($match[0] as $m) {
				$f = substr($m,1,-1);
				$url .= "&$f=[$f]";
			}
		}
		$lbl = [];
		foreach ($field as $f) {
			foreach ($this->ref as $ref) {
				if ($ref["object"]->field == $f) $lbl[] = $ref["object"]->label;
			}
		}
		$fn = "ckUnique($this->unique_ix,'$url'," . str_replace('"',"'",json_encode(array_map("utf8_encode",$lbl))) . ")";
		$str_ck = ""; $str_field = "";
		for ($i=0; $i<count($this->ref); $i++) {
			foreach ($field as $f) {
				$ref = $this->ref[$i]["object"];
				if ($ref->field == $f) {
					$str_field .= ($str_field != "" ? " + ',' + " : "") . "form.$f.value";
					if ($ref->type == "hidden" && !empty($ref->default))
						$this->add_onload_action($fn);
					if ($ref->type == "radio")
						$ref->add_prop("onclick", $fn, $i);
					else 
						$ref->add_prop("onchange", $fn, $i);
					if ($ref->label != "")
						$str_ck .= ($str_ck != "" ? ", " : "") . $ref->label;
				}
			}
		}
		$this->add_form_rule("function() { if (form[\"ck_unique$this->unique_ix\"].value > 0) return '$this->lang_js_unique $str_ck: ' + $str_field; }()");
		$this->add_js("ajax_lib.v2.js", true);
		$this->unique_ix++;
	}
	public function add_form_rule($condition, $msg="FUNCTION", $scope="form", $ck_confirm = 0) {
		$this->form_rule[] = [
			"condition" => $condition,
			"msg" => $msg,
			"scope" => $scope,
			"ck_confirm" => $ck_confirm
		];
	}
	public function add_form_confirm_rule($condition, $msg, $scope = "form") {
		$this->add_form_rule($condition, $msg, $scope, 1);
	}
	public function add_js_function($fn, $scope = "form") {
		$this->add_js_code($fn, $scope);
	}
	public function add_js_code($str, $scope = "form") {
		$c_ref = count($this->js_code);
		$this->js_code[$c_ref]["str"] = $str;
		$this->js_code[$c_ref]["scope"] = $scope;
	}
	public function start_field_group($label = "", $display = "table-row") {
		if (!isset($this->field_group_id)) $this->field_group_id = 0;
		if ($display === false) $display = "inline-block"; // $ck_line_break = false
		$this->field_group = [ "id" => $this->field_group_id, "label" => $label, "display" => $display ];
		$this->field_group_id++;
	}
	public function end_field_group() {
		$this->field_group = false; 
	}
	public function start_filter_group($label = "", $display = "table-row") {
		$this->start_field_group($label, $display); 
	}
	public function end_filter_group() {
		$this->end_field_group(); 
	}
	public function ck_ajax_qry($qry, $scope) {
		if (substr($scope, 0, 12) == "radio_option") {
			$scope = "field";
			$c_ref = count($this->ref)-1;
			$ref = $this->ref;
			$field = substr($scope, 12+1);
		} else if ($scope == "field") {
			$c_ref = count($this->ref)-1;
			$ref = $this->ref;
			$field = $ref[$c_ref]["field"];
		} else if ($scope == "filter") {
			$c_ref = count($this->ref_filter)-1;
			$ref = $this->ref_filter;
			$field = $this->getFieldLbl($ref[$c_ref]["field"]);
		} else if ($scope == "entity_field") {
			$c_ref = count($this->ref)-1;
			$e = $this->ref[$c_ref]["object"];
			$ref = $e->field;
			$c = count($ref)-1;
			$field = $e->prefix . "_" . $ref[$c]->field;
		}
		$ck_ajax = 0;
		$fields = [];
		preg_match_all("[\[([a-zA-Z0-9_])+\]]", $qry, $match);
		if (count($match[0]) > 0) {
			//echo "<pre>"; print_r($match[0]); echo "</pre>"; 
			$this->add_js("ajax_lib.v2.js", true);
			foreach (array_unique($match[0]) as $ix) {
				$ck_ajax_ix = 0;
				for ($j=0; $j<count($ref)-1; $j++) {
					$obj = gettype($ref[$j]) == "array" ? $ref[$j]["object"] : $ref[$j];
					if ($obj->type != "entity" && strtolower(substr($ix,1,-1)) == strtolower($this->getFieldLbl($obj->field))) {
						if ($obj->type == "dropdown" || $obj->type == "radio" || $obj->type == "radio_checkbox" || $obj->type == "checkbox" || $obj->type == "text") {
							$fields[] = [ "field" => $obj->field, "label" => $obj->label, "obj" => $scope ];
							$ev = $obj->type == "radio" || $obj->type == "checkbox" || $obj->type == "radio_checkbox" ? "onclick" : "onchange";
							//echo "{$scope}[$field] found:$ix($j) => $ev:loader_$field.load()<br>";
							if ($scope == "field" && isset($obj->qry) && isset($obj->ajax) && $obj->ajax !== false) {
								//$this->called[$field] = true; // avaliando se a propriedade called cumpre seu papel de evitar loader.load() apenas quando ainda nao carregado
								$obj->add_ajax_action("loader_$field.load();", "S");
								if (isset($_GET["modo"]) && $_GET["modo"] == "update") $obj->add_prop("onchange", "loader_" . $obj->field . ".applyAction(event)");
							} else if ($scope == "field") {
								$obj->add_prop($ev, "loader_$field.load()");
							} else if ($scope == "filter") {
								$obj->add_prop($ev, "loader_$field.load()");
							} else if ($scope == "entity_field" && isset($obj->qry) && isset($obj->ajax) && $obj->ajax !== false) {
								/* (arg $add_sufix = true) disabled - 01-02-2021
								$e->field[$j]->add_ajax_action("loader_{$field}[].load(false,p)", "S", true);
								*/
								$e->field[$j]->add_ajax_action("loader_$field.load(false,p)", "S");
								if (isset($_GET["modo"]) && $_GET["modo"] == "update") { // No update os comandos sao acionados por onchange e nao pelo dropdownLoader
									$src = $e->prefix . "_" . $obj->field;
									$e->field[$j]->add_prop("onchange", "loader_$src.applyAction(event,this.id.replace('$src',''))");
								}
							} else if ($scope == "entity_field") {
								$e->field[$j]->add_prop($ev, "loader_$field.load(false,this.id.replace('" . $this->ref[$c_ref]["object"]->prefix . "_" . $obj->field . "',''))");
							}
						} else if ($obj->type == "hidden") {
							$fields[] = [ "field" => $obj->field, "label" => $obj->label, "obj" => $scope ];
						} else if ($obj->type == "fieldfilter") {
							$fields[] = [ "field" => $obj->field, "label" => $obj->label, "obj" => $scope ];
							if ($scope == "entity_field") {
								/* (arg $add_sufix = true) disabled - 01-02-2021
								$action = "loader_{$field}[].load(false,p)";
								$action_reset = "loader_{$field}[].reset(p)";
								$this->ref[$c_ref]["object"]->field[$j]->add_ajax_action($action, "S", true);
								$this->ref[$c_ref]["object"]->field[$j]->add_ajax_action($action_reset, "R", true);
								*/
								$action = "loader_{$field}.load(false,p)";
								$action_reset = "loader_{$field}.reset(p)";
								$this->ref[$c_ref]["object"]->field[$j]->add_ajax_action($action, "S");
								$this->ref[$c_ref]["object"]->field[$j]->add_ajax_action($action_reset, "R");
							} else {
								$action = "loader_$field.load()";
								$action_reset = "loader_$field.reset()";
								$obj->add_ajax_action($action, "S");
								$obj->add_ajax_action($action_reset, "R");
							}
						}
						$ck_ajax = 1;
						$ck_ajax_ix = 1;
					}
				} // for ($j=0; $j<count($ref)-1; $j++)
				// check if entity field must be triggered by ref field
				if ($scope == "entity_field" && $ck_ajax_ix == 0) {
					foreach ($this->ref as $j => $src) {
						$event_src = false;
						if ($src["type"] != "entity") {
							if (strtolower(substr($ix,1,-1)) == strtolower($src["field"]) &&
								$j < count($this->ref)-1) {
								$event_src = "field";
								$fields[] = [ "field" => $src["field"], "label" => $src["label"], "obj" => "field" ];
							}
						}
						if (!empty($event_src)) {
							if ($src["type"] == "fieldfilter") {
								$action = "loader_$field.load(document.getElementById('".$src["field"]."').value)";
								$action_reset = "loader_$field.reset()";
								if ($scope == "filter") {
									$this->add_filter_field_ajaxaction($action,"S", $j);
									$this->add_filter_field_ajaxaction($action_reset, "R", $j);
								} else if ($event_src == "field") {
									$this->add_field_ajaxaction($action,"S", $j);
									$this->add_field_ajaxaction($action_reset, "R", $j);
								} else if ($event_src == "entity_field") {
									$this->add_entity_field_ajaxaction($action,"S", $j);
									$this->add_entity_field_ajaxaction($action_reset, "R", $j);
								}
							} else {
								$ev = in_array($src["type"], ["radio","checkbox"]) ? "click" : "change";
								if ($scope == "filter") {
									$this->add_filter_field_prop("on$ev", "loader_$field.load()", $j);
								} else if ($event_src == "field") {
									$this->add_field_prop("on$ev", "loader_$field.load()", $j);
								} else if ($event_src == "entity_field") {
									$this->add_entity_field_prop("on$ev", "loader_$field.load()", $j);
								}
							}
							$ck_ajax = 1;
						}
					}
				}
			}
		}
		if ($ck_ajax == 1)
			return $fields;
		else
			return false;
	}
	public function set_field_qry_new_entry($table=null, $field=null, $url=null, $pk="SCOPE_IDENTITY()") {
		$this->set_field_new_entry($table, $field, $url, $pk);
	}
	public function set_field_list_new_entry($table=null, $field=null, $url=null, $pk="SCOPE_IDENTITY()") {
		$this->set_field_new_entry($table, $field, $url, $pk);
	}
	public function set_field_new_entry($table=null, $field=null, $params=[], $pk=false) {
		if (!is_array($params)) $params = strpos($params,"?") !== false ? array("url" => $params) : [];
		$params["table"] = $table;
		$params["field"] = $field;
		if (!isset($params["pk"])) $params["pk"] = $pk ? $pk : "SCOPE_IDENTITY()";
		if (!isset($params["label"])) $params["label"] = $params["field"];
		if (!isset($params["url"])) $params["url"] = "";
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_new_entry($table, $field, $params, $pk);
		$this->ref[$c_ref]["ck_new_entry"] = 1;
		$this->ref[$c_ref]["new_entry"] = $params;
		$this->add_field_prop("onchange", "document.getElementById(this.id+'_entry').style.display=this.value=='NEW'?'':'none'");
	}
	public function set_field_default_label($label) {
		if ($this->use_translator == 1) $ent = $this->translator->get($label);
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_default_label($label);
		$this->ref[$c_ref]["default_label"] = $label;
	}
	public function set_dropdown_multiple($table, $key, $xtra_key=[]) {
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_dropdown_multiple($table, $key, $xtra_key);
		$this->add_field_prop("multiple");
		$this->set_field_entity($table, $key, $xtra_key);
	}
	public function set_field_entity($table, $key, $xtra_key=[]) {
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_entity($table, $key, $xtra_key);
		$this->ref[$c_ref]["entity"] = array("table" => $table, "field" => $key, "xtra_key" => $xtra_key);
		$this->ref[$c_ref]["table"] = $table;
		$this->ref[$c_ref]["key_field"] = $key;
		$this->ref[$c_ref]["xtra_key"] = $xtra_key;
		// form join
		$str = "LEFT JOIN $table ON $table.$key = $this->table.$this->pk";
		$keys = array_keys($xtra_key);
		for ($i=0; $i<count($keys); $i++) {
			if (substr($xtra_key[$keys[$i]],0,6) == "SELECT")
				$str .= " AND $table." . $keys[$i] . " IN (" . $xtra_key[$keys[$i]] . ")";
			else
				$str .= " AND $table." . $keys[$i] . " = '" . $xtra_key[$keys[$i]] . "'";
		}
		$ck = 1;
		for ($i=0; $i<count($this->related); $i++) {
			if ($this->related[$i]["use"] == "F" && $this->related[$i]["sql"] == $str) return false;
		}
		if ($ck == 1) $this->add_related($str, $use="F");
	}
	public function set_field_ref_query($sql, $label, $function="nc_query") {
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_ref_query($sql, $label, $function);
		$this->ref[$c_ref]["ref_qry"] = $sql;
		$this->ref[$c_ref]["ref_qry_label"] = $label;
	}
	public function set_field_filedir($dirgrande, $maxsizegrande=false, $dirthumb=false, $maxsizethumb=false) {
		$this->add_field_dir($dirgrande, $maxsizegrande);
		if ($dirthumb != false) $this->add_field_dir($dirthumb, $maxsizethumb, 1);
		/*
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["filedir"] = $dirgrande;
		if ($maxsizegrande != false) $this->ref[$c_ref]["filemaxsize"] = $maxsizegrande;
		if ($dirthumb != false) $this->ref[$c_ref]["filedirthumb"] = $dirthumb;
		if ($maxsizethumb != false) $this->ref[$c_ref]["filemaxsizethumb"] = $maxsizethumb;
		*/
	}
	public function set_field_file_name($mask) {
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_file_name($mask);
		$this->ref[$c_ref]["file_name_mask"] = $mask;
	}
	public function add_field_dir($dir, $maxsize=false, $ck_thumb=0, $pos=null) {
		$c_ref = count($this->ref)-1; // existing index
		$this->ref[$c_ref]["object"]->add_dir($dir, $maxsize, $ck_thumb, $pos);
		if (!isset($this->ref[$c_ref]["file"])) $this->ref[$c_ref]["file"] = [];
		$c = count($this->ref[$c_ref]["file"]); // new index
		if (is_array($dir)) {
			$this->ref[$c_ref]["file"][$c]["dir"]["O"] = $dir[0]; // access dir
			$this->ref[$c_ref]["file"][$c]["dir"]["D"] = $dir[1]; // write dir
		} else {
			$this->ref[$c_ref]["file"][$c]["dir"]["O"] = $dir;
			$this->ref[$c_ref]["file"][$c]["dir"]["D"] = $dir;
		}
		$this->ref[$c_ref]["file"][$c]["maxsize"] = $maxsize;
		$this->ref[$c_ref]["file"][$c]["ck_thumb"] = $ck_thumb;
		$this->ref[$c_ref]["file"][$c]["pos"] = $pos;
		//$this->ref[$c_ref]["file"][$c]["mask"] = $mask;
	}
	public function hide_file() {
		$c_ref = count($this->ref)-1; // existing index
		$this->ref[$c_ref]["object"]->hide_file();
		$this->ref[$c_ref]["ck_file_link"] = false;
	}
	public function set_publisher_img_dir($dir) {
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->set_publisher_dir($dir);
		$this->ref[$c_ref]["publisher_img_dir"] = $dir;
	}
	public function add_radio_option($val, $label, $field=null, $type=null, $sql=null, $qry_ix=null, $qry_label=null, $group_label=null) {
		$c_ref = count($this->ref)-1; // existing index
		$obj = $this->ref[$c_ref]["object"]->add_radio_option($val, $label, $field, $type, $sql, $qry_ix, $qry_label, $group_label);
		$this->ref[$c_ref]["list"]["vals"][$val] = array(
			"object" => $obj, 
			"ck_xtra" => 1, 
			"label" => $label, 
			"field" => $field, 
			"type" => $type, 
			"ck_req" => 0, 
			"prop" => [],
			"qry" => array(
				"sql" => $sql, 
				"ix" => $qry_ix, 
				"label" => $qry_label, 
				"group_label" => $group_label, 
				"ck_new_entry" => 0,
				"xtra_fields" => []),
			"ajax" => $this->ck_ajax_qry($sql, "radio_option:$field"),
		);
		if (count($this->tab_ref) > 0 && $this->tab_ref[$obj->tab_id]["table"] != null)
			 $field = $this->tab_ref[$obj->tab_id]["table"] . "_" . $field;
		if ($type == "text") {
			$this->ref[$c_ref]["list"]["vals"][$val]["prop"]["onfocus"] = "temp=this.value";
			$this->ref[$c_ref]["list"]["vals"][$val]["prop"]["onblur"] = "temp!=this.value?{$field}[".(count($this->ref[$c_ref]["list"]["vals"])-1)."].checked=true:void(null)";
		} else if ($type == "dropdown") {
			$this->ref[$c_ref]["list"]["vals"][$val]["prop"]["onchange"] = "{$field}[".(count($this->ref[$c_ref]["list"]["vals"])-1)."].checked=true";
		}
	}
	public function add_radio_field_prop($prop, $value=true) {
		$c_ref = count($this->ref)-1; // existing index
		$this->ref[$c_ref]["object"]->add_radio_field_prop($prop, $value);
		$keys = array_keys($this->ref[$c_ref]["list"]["vals"]);
		$ix = $keys[count($keys)-1];
		if (isset($this->ref[$c_ref]["list"]["vals"][$ix]["prop"][$prop]))
			$this->ref[$c_ref]["list"]["vals"][$ix]["prop"][$prop] .= ";" . $value;
		else
			$this->ref[$c_ref]["list"]["vals"][$ix]["prop"][$prop] = $value;
	}
	public function add_radio_qry_new_entry($table=null, $field=null, $params=[]) {
		$params["table"] = $table;
		$params["field"] = $field;
		if (!isset($params["pk"])) $params["pk"] = $pk ? $pk : "SCOPE_IDENTITY()";
		if (!isset($params["label"])) $params["label"] = $params["field"];
		if (!isset($params["url"])) $params["url"] = "";
		$c_ref = count($this->ref)-1;
		$this->ref[$c_ref]["object"]->add_radio_qry_new_entry($table, $field, $params);
		$keys = array_keys($this->ref[$c_ref]["list"]);
		$ix = $keys[count($keys)-1];
		$this->ref[$c_ref]["list"][$ix]["ck_new_entry"] = 1;
		$this->ref[$c_ref]["list"][$ix]["new_entry"] = $params;
		$this->add_radio_field_prop("onchange", "document.getElementById(this.id+'_entry').style.display=this.value=='NEW'?'':'none'");
	}
	// entity field
	public function add_entity_field($field, $label=null, $type=null, $ck_req=0, $ck_qry=1, $title=null) {
		if ($this->use_translator == 1) $label = $this->translator->get($label);
		$c_ref = count($this->ref)-1; // existing index
		$c = count($this->ref[$c_ref]["field"]);
		if ($this->ref[$c_ref]["rel"] == "1XN")
			$obj = $this->ref[$c_ref]["object"]->add_field($field, $label, $type, $ck_req, $ck_qry, $c_ref);
		else if ($this->ref[$c_ref]["rel"] == "NXN")
			$obj = $this->ref[$c_ref]["object"]->set_field($field);
		$this->ref[$c_ref]["field"][$c] = array(
			"object" => $obj, 
			"id_parent" => $c_ref,
			"field" => $field, 
			"label" => $label, 
			"type" => $type, 
			"ck_req" => $ck_req, 
			"ck_qry" => $ck_qry, 
			"ck_bak" => $this->ref[$c_ref]["ck_history"] == 1 ? 1 : 0, 
			"ck_hidden" => 0, 
			"ck_history" => 0, 
			"ck_log" => 0, 
			"prop" => [],
			"ck_readonly" => 0,
			"file_name_mask" => "SELF",
			"title" => $title);
		if ($type == "color") {
			$this->add_js("fn_buildcolortable.js", true);
		} else if ($type == "publisher") {
			$this->publisher_ref[count($this->publisher_ref)] = $field;
			$this->ref[$c_ref]["field"][$c]["publisher_img_action"] = "$this->js_dir/publisher_lib.img.php";
			$this->ref[$c_ref]["field"][$c]["publisher_img_dir"] = "imagens/";
			$this->add_js("publisher_lib.js", true);
			$this->add_onload_action("_initPublisher()");
			$this->add_field_prop("onclick", "_addPublisher()");
		} else if ($type == "fieldfilter") {
			$this->add_js("ajax_lib.v2.js", true);
		} else if ($type == "fieldlist") {
			$this->add_js("fieldlist.js", true);
			$this->add_js("drag_lib.js", true);
		}
		return $obj;
	}
	public function set_entity_field_options($opt) {
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_options($opt);
		if (isset($opt["ajax"])) {
			$this->ref[$c_ref]["field"][$c]["ajaxqry"] = $opt["ajax"]["qry"];
			$this->ref[$c_ref]["field"][$c]["ajaxid"] = $opt["ajax"]["id"];
			$this->ref[$c_ref]["field"][$c]["ajaxlabel"] = $opt["ajax"]["label"];
			$this->ref[$c_ref]["field"][$c]["ajaxfilter"] = isset($opt["ajax"]["filter"]) ? $opt["ajax"]["filter"] : $opt["ajax"]["label"];
			$this->ref[$c_ref]["field"][$c]["ajaxaction"] = isset($opt["ajax"]["action"]) ? $opt["ajax"]["action"] : [];
			$this->ref[$c_ref]["field"][$c]["ajax_xtrafield"] = isset($opt["ajax"]["xtrafield"]) ? $opt["ajax"]["xtrafield"] : [];
		}
		if (isset($opt["new_entry"])) {
			$this->ref[$c_ref]["field"][$c]["new_entry"] = $opt["new_entry"];
			$field = $this->ref[$c_ref]["object"]->field[$c]->field;
			$this->add_entity_field_prop("onchange", "document.getElementById(this.id.replace('$field','{$field}_entry')).style.display=this.value=='NEW'?'':'none'");
		}
		if (isset($opt["entity"])) {
			$this->ref[$c_ref]["field"][$c]["entity"] = $opt["entity"];
		}
		if (isset($opt["ck_readonly"])) {
			$this->ref[$c_ref]["field"][$c]["ck_readonly"] = $opt["ck_readonly"];
		}
		if (isset($opt["ck_disabled"])) {
			$this->ref[$c_ref]["field"][$c]["ck_disabled"] = $opt["ck_disabled"];
		}
	}
	public function set_entity_field_mask($value, $write_mask=0, $unlock_mask=0) {
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_mask($value, $write_mask, $unlock_mask);
		$this->ref[$c_ref]["field"][$c]["mask"] = $value;
		$this->ref[$c_ref]["field"][$c]["write_mask"] = $write_mask;
		$this->ref[$c_ref]["field"][$c]["unlock_mask"] = $unlock_mask;
		if ($unlock_mask == 1) $this->ck_unlock = 1;
		$this->add_entity_field_prop("onfocus", "recvalue(this)");
		$this->add_entity_field_prop("onkeyup", "gotofield(this)");
	}
	public function set_entity_field_default($value) {
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_default($value);
		$this->ref[$c_ref]["field"][$c]["default"] = $value;
	}
	public function add_entity_field_bak() {
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->add_bak();
		$this->ref[$c_ref]["field"][$c]["ck_bak"] = 1;
	}
	public function set_entity_field_default_label($label) {
		if ($this->use_translator == 1) $ent = $this->translator->get($label);
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_default_label($label);
		$this->ref[$c_ref]["field"][$c]["default_label"] = $label;
	}
	public function set_entity_field_comment($value,$pos="after") {
		if ($this->use_translator == 1) $value = $this->translator->get($value);
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_comment($value,$pos);
		if ($pos == "after")
			$this->ref[$c_ref]["field"][$c]["comment"] = $value;
		if ($pos == "before")
			$this->ref[$c_ref]["field"][$c]["comment_before"] = $value;
	}
	public function set_entity_field_hidden() {
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_hidden();
		$this->ref[$c_ref]["field"][$c]["ck_hidden"] = 1;
	}
	public function set_entity_field_ajaxtable($table, $id="id", $label="nome", $filter=null) {
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_ajaxtable($table, $id, $label, $filter);
		$this->ref[$c_ref]["field"][$c]["ajaxtable"] = $table;
		$this->ref[$c_ref]["field"][$c]["ajaxid"] = $id;
		$this->ref[$c_ref]["field"][$c]["ajaxlabel"] = $label;
		$this->ref[$c_ref]["field"][$c]["ajaxfilter"] = $filter == null ? $label : $filter;
		$this->ref[$c_ref]["field"][$c]["ajaxcondition"] = "";
		$this->ref[$c_ref]["field"][$c]["ajaxaction"] = [];
		$this->ref[$c_ref]["field"][$c]["ajax_xtrafield"] = [];
	}
	public function set_entity_field_ajaxqry($qry, $id="id", $label="nome", $filter=null) {
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_ajaxqry($qry, $id, $label, $filter);
		$this->ref[$c_ref]["field"][$c]["ajaxqry"] = $qry;
		$this->ref[$c_ref]["field"][$c]["ajaxid"] = $id;
		$this->ref[$c_ref]["field"][$c]["ajaxlabel"] = $label;
		$this->ref[$c_ref]["field"][$c]["ajaxfilter"] = $filter == null ? $label : $filter;
		$this->ref[$c_ref]["field"][$c]["ajaxaction"] = [];
		$this->ref[$c_ref]["field"][$c]["ajax_xtrafield"] = [];
	}
	public function set_entity_field_ajaxcondition($condition) {
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_ajaxcondition($condition);
		$this->ref[$c_ref]["field"][$c]["ajaxcondition"] = $condition;
	}
	public function set_entity_field_ajaxpath($url, $filter="") {
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_ajax_path($url, $filter, $this->ref[$c_ref]["field"]);
		$this->ref[$c_ref]["field"][$c]["ajaxpath"] = $url;
		$this->ref[$c_ref]["field"][$c]["ajaxfilter"] = $filter;
		$this->ref[$c_ref]["field"][$c]["ajaxaction"] = [];
		$this->ref[$c_ref]["field"][$c]["ajax_xtrafield"] = [];
	}
	public function set_entity_field_ajax_group($group) {
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_ajax_group($group);
		$this->ref[$c_ref]["field"][$c]["ajaxgroup"] = $group;
	}
	public function set_entity_field_ajaxaction($action, $modo = "S", $ix=null) {
		$this->add_entity_field_ajaxaction($action, $modo, $ix);
	}
	public function add_entity_field_ajaxaction($action, $modo = "S", $ix=null, $add_sufix=false) {
		// $modo => S = SELECT, R = RESET
		$c_ref = count($this->ref)-1;
		$c = is_numeric($ix) ? $ix : count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->add_ajax_action($action, $modo);
		$c_action = isset($this->ref[$c_ref]["object"]->field[$c]->ajax_action) ? count($this->ref[$c_ref]["object"]->field[$c]->ajax_action) : 0;
		$this->ref[$c_ref]["object"]->field[$c]->ajax_action[$c_action] = array("action" => $action, "modo" => $modo, "add_sufix" => $add_sufix);
	}
	public function add_entity_field_ajax_xtrafield($field, $xml_label, $ix=null) {
		$c_ref = count($this->ref)-1;
		$c = is_numeric($ix) ? $ix : count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->add_ajax_field($field, $xml_label);
		$c_field = isset($this->ref[$c_ref]["object"]->field[$c]->ajax_xtrafield) ? count($this->ref[$c_ref]["object"]->field[$c]->ajax_xtrafield) : 0;
		$this->ref[$c_ref]["object"]->field[$c]->ajax_xtrafield[$c_field] = array("field" => $field, "xml_label" => $xml_label);
	}
	public function set_entity_field_filedir($dirgrande, $maxsizegrande=false, $dirthumb=false, $maxsizethumb=false) {
		$this->add_entity_field_dir($dirgrande, $maxsizegrande);
		if ($dirthumb != false) $this->add_entity_field_dir($dirthumb, $maxsizethumb, 1);
		/*
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["field"][$c]["filedir"] = $dirgrande;
		if ($maxsizegrande != false) $this->ref[$c_ref]["field"][$c]["filemaxsize"] = $maxsizegrande;
		if ($dirthumb != false) $this->ref[$c_ref]["field"][$c]["filedirthumb"] = $dirthumb;
		if ($maxsizethumb != false) $this->ref[$c_ref]["field"][$c]["filemaxsizethumb"] = $maxsizethumb;
		*/
	}
	public function set_entity_field_file_name($mask) {
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_file_name($mask);
		$this->ref[$c_ref]["field"][$c]["file_name_mask"] = $mask;
	}
	public function add_entity_field_dir($dir, $maxsize=false, $ck_thumb=0, $pos=null) {
		$c_ref = count($this->ref)-1; // existing index
		$c = count($this->ref[$c_ref]["field"])-1; // existing index
		$this->ref[$c_ref]["object"]->field[$c]->add_dir($dir, $maxsize, $ck_thumb, $pos);
		if (!isset($this->ref[$c_ref]["object"]->field[$c]->file)) $this->ref[$c_ref]["field"][$c]["file"] = [];
		$c2 = count($this->ref[$c_ref]["object"]->field[$c]->file); // new index
		if (is_array($dir)) {
			$this->ref[$c_ref]["object"]->field[$c]->file[$c2]["dir"]["O"] = $dir[0];
			$this->ref[$c_ref]["object"]->field[$c]->file[$c2]["dir"]["D"] = $dir[1];
		} else {
			$this->ref[$c_ref]["object"]->field[$c]->file[$c2]["dir"]["O"] = $dir;
			$this->ref[$c_ref]["object"]->field[$c]->file[$c2]["dir"]["D"] = $dir;
		}
		$this->ref[$c_ref]["object"]->field[$c]->file[$c2]["maxsize"] = $maxsize;
		$this->ref[$c_ref]["object"]->field[$c]->file[$c2]["ck_thumb"] = $ck_thumb;
		$this->ref[$c_ref]["object"]->field[$c]->file[$c2]["pos"] = $pos; // H/V
		//$this->ref[$c_ref]["object"]->field[$c]->file[$c2]["mask"] = $mask;
	}
	public function set_entity_field_dir_qry($sql) {
		$c_ref = count($this->ref)-1; // existing index
		$c = count($this->ref[$c_ref]["field"])-1; // existing index
		$this->ref[$c_ref]["object"]->field[$c]->set_dir_qry($sql);
	}
	public function set_entity_publisher_img_dir($dir, $ix=null) {
		$c_ref = count($this->ref)-1;
		$c = is_numeric($ix) ? $ix : count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["field"][$c]["publisher_img_dir"] = $dir;
	}
	public function add_entity_field_prop($prop, $value=null, $ix=null) {
		$c_ref = count($this->ref)-1;
		$c = is_numeric($ix) ? $ix : count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->add_prop($prop, $value);
		if (isset($this->ref[$c_ref]["field"][$c]["prop"][$prop]))
			$this->ref[$c_ref]["field"][$c]["prop"][$prop] .= ";" . $value;
		else
			$this->ref[$c_ref]["field"][$c]["prop"][$prop] = $value;
	}
	public function set_entity_field_readonly() {
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_readonly();
		$this->add_entity_field_prop("readonly");
		$this->ref[$c_ref]["field"][$c]["ck_readonly"] = 1;
	}
	public function add_entity_field_list($list) {
		$this->set_entity_field_list($list);
	}
	public function set_entity_field_list($list, $ck_break=false) {
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_list($list, $ck_break);
		$this->ref[$c_ref]["field"][$c]["list"] = array(
			"vals" => $list,
			"ck_break" => $ck_break,
			"ck_new_entry" => 0
		);
	}
	public function add_entity_field_qry($qry, $ix, $label, $group_label = null, $xtra_fields = null) {
		$this->set_entity_field_qry($qry, $ix, $label, $group_label, $xtra_fields);
	}
	public function set_entity_field_qry($qry, $ix, $label, $group_label = null, $xtra_fields = null) {
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_qry($qry, $ix, $label, $group_label, $xtra_fields);
		$this->ref[$c_ref]["field"][$c]["qry"] = array(
			"sql" => $qry, 
			"ix" => $ix, 
			"label" => $label, 
			"group_label" => $group_label, 
			"xtra_fields" => $xtra_fields
		);
		$this->ref[$c_ref]["field"][$c]["ck_new_entry"] = 0;
		// $this->ref[$c_ref]["field"][$c]["ajax"] = $this->ck_ajax_qry($qry, "entity_field"); // called on field->set_qry()
		$this->ref[$c_ref]["field"][$c]["ajax"] = $this->ref[$c_ref]["field"][$c]["object"]->ajax;
		$this->ref[$c_ref]["field"][$c]["ref_qry"] = null;
	}
	public function set_entity_field_qry_pk($pk) {
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_qry_pk($pk);
		$this->ref[$c_ref]["object"]->field[$c]->qry["pk"] = $pk;
	}
	public function set_entity_field_index($ix) { // use to get values for non query fields ($ck_query = 0)
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_index($ix);
		$this->ref[$c_ref]["object"]->field[$c]->qry["field_qry"] = $ix;
	}
	public function set_entity_field_new_entry($table, $field, $params=[], $pk=false) {
		if (!is_array($params)) $params = strpos($params,"?") !== false ? array("url" => $params) : [];
		$params["table"] = $table;
		$params["field"] = $field;
		if (!isset($params["pk"])) $params["pk"] = $pk ? $pk : "SCOPE_IDENTITY()";
		if (!isset($params["label"])) $params["label"] = $params["field"];
		if (!isset($params["url"])) $params["url"] = "";
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_new_entry($table, $field, $params, $pk);
		$this->ref[$c_ref]["field"][$c]["ck_new_entry"] = 1;
		$this->ref[$c_ref]["field"][$c]["new_entry"] = $params;
		$field = $this->ref[$c_ref]["object"]->field[$c]->field;
		$this->add_entity_field_prop("onchange", "document.getElementById(this.id.replace('$field','{$field}_entry')).style.display=this.value=='NEW'?'':'none'");
	}
	public function set_entity_field_ref_query($sql, $label, $function="nc_query") {
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_ref_query($sql, $label, $function);
		$this->ref[$c_ref]["field"][$c]["ref_qry"] = $sql;
		$this->ref[$c_ref]["field"][$c]["ref_qry_label"] = $label;
	}
	public function set_entity_dropdown_multiple($table, $key, $xtra_key=[]) {
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_dropdown_multiple($table, $key, $xtra_key);
		$this->add_entity_field_prop("multiple");
		$this->set_entity_field_entity($table, $key, $xtra_key);
	}
	public function set_entity_field_entity($table, $key, $xtra_key=[]) {
		$c_ref = count($this->ref)-1;
		$c = count($this->ref[$c_ref]["field"])-1;
		$this->ref[$c_ref]["object"]->field[$c]->set_entity($table, $key, $xtra_key);
		$this->ref[$c_ref]["field"][$c]["entity"] = array("table" => $table, "field" => $key, "xtra_key" => $xtra_key);
		$this->ref[$c_ref]["field"][$c]["table"] = $table;
		$this->ref[$c_ref]["field"][$c]["key_field"] = $key;
		$this->ref[$c_ref]["field"][$c]["xtra_key"] = $xtra_key;
	}
	public function add_list_param($param, $val = "GETFROMQRY") {
		$this->list_param[$param] = $val;
	}
	public function set_list_title_mode($value) {
		$this->list_title_mode = $value;
	}
	private function get_res_aux($ref) { 
		$obj = $ref["object"];
		$ent_table = $ref["table"];
		$key_field = $ref["key_field"];
		$pk = $obj->pk;
		if (count($this->tab_ref) == 0 || 
			!isset($ref["tab_id"]) || 
			$this->tab_ref[$ref["tab_id"]]["table"] == null)
			$tab_table = "";
		else
			$tab_table = $this->tab_ref[$ref["tab_id"]]["table"];
		$str_field = "";
		$str_join = "";
		foreach ($obj->related as $r) {
			$str_join .= $r . "\n";
		}
		for ($i=0; $i<count($obj->field); $i++) {
			$f = $obj->field[$i];
			if (isset($f->field_qry)) 
				$field = $f->field_qry;
			else
				$field = $f->field;
			$type = $f->type;
			if (!empty($f->db_type)) $type = $f->db_type;
			$prop = $f->prop;
			if (isset($f->rec_table["table"]) && $f->type != "fieldlist") {
				$field_table = $f->rec_table["table"];
				if (strpos($str_join, "LEFT JOIN $field_table") === false) {
					$entity_key_field = $f->rec_table["field"];
					$str_join .= "LEFT JOIN $field_table ON ";
					if (!is_array($entity_key_field)) $entity_key_field = array($entity_key_field);
					for ($j=0; $j<count($entity_key_field); $j++) {
						$str_join .= ($j>0?" AND ":"") . "$field_table." . $entity_key_field[$j] . " = $ent_table." . $ref["pk"];
					}
					$str_join .= "\n\t";
				}
			} else {
				$field_table = $ref["table"];
			}
			if ($f->ck_qry == 1 || isset($f->field_qry)) {
				if (isset($f->field_qry)) {
					$str_field .= ($str_field != "" ? ",\n\t" : "") . $f->field_qry;
				} else if ($type == "date" || $type == "datetime") {
					if (is_array($pk) && in_array($field, $pk)) {
						if ($type == "date" && $this->db == "MSSQL")
							$str_field .= ($str_field != "" ? ",\n\t" : "") . "dbo.DATE_FORMAT($field_table.$field, '%Y-%m-%d %H:%i:%s') AS {$field}_key";
						else if ($type == "date" && $this->db == "MYSQL")
							$str_field .= ($str_field != "" ? ",\n\t" : "") . "DATE_FORMAT($field_table.$field, '%Y-%m-%d %H:%i:%s') AS {$field}_key";
					}
					$dt_mask =$f->type == "hidden" ? "%Y-%m-%d" : "%d/%m/%Y";
					if ($type == "date" && $this->db == "MSSQL")
						$str_field .= ($str_field != "" ? ",\n\t" : "") . "dbo.DATE_FORMAT($field_table.$field, '$dt_mask') AS $field";
					else if ($type == "date" && $this->db == "MYSQL")
						$str_field .= ($str_field != "" ? ",\n\t" : "") . "DATE_FORMAT($field_table.$field, '$dt_mask') AS $field";
					else if ($type == "datetime" && $this->db == "MSSQL")
						$str_field .= ($str_field != "" ? ",\n\t" : "") . "dbo.DATE_FORMAT($field_table.$field, '$dt_mask %h:%i') AS $field, dbo.DATE_FORMAT($field, '%H') AS hor_$field, dbo.DATE_FORMAT($field, '%i') AS min_$field";
					else if ($type == "datetime" && $this->db == "MYSQL")
						$str_field .= ($str_field != "" ? ",\n\t" : "") . "DATE_FORMAT($field_table.$field, '$dt_mask %h:%i') AS $field, DATE_FORMAT($field, '%H') AS hor_$field, DATE_FORMAT($field, '%i') AS min_$field";
					$str_field .= ",\nDAY($field_table.$field) AS dia_$field, MONTH($field_table.$field) AS mes_$field, YEAR($field_table.$field) AS ano_$field";
				
				/* Disabled 12/09/2023, using $val->format()
				} else if ($type == "month") {
					$qry = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$field_table' AND COLUMN_NAME = '$field'";
					$res = nc_query($qry);
					if ($res[0]["DATA_TYPE"] == "varchar") {
						$str_field .= ($str_field != "" ? ",\n\t" : "") . "LEFT($field_table.$field,4) AS ano_$field, RIGHT($field_table.$field,2) AS mes_$field";
					} else {
						$str_field .= ($str_field != "" ? ",\n\t" : "") . "YEAR($field_table.$field) AS ano_$field, MONTH($field_table.$field) AS mes_$field";
					}
				*/
				} else if ($type == "daterange") {
					$str_field .= ($str_field != "" ? ",\n\t" : "") . "DAY($field_table.{$field}_ini) AS dia_{$field}_ini, MONTH($field_table.{$field}_ini) AS mes_{$field}_ini, YEAR($field_table.{$field}_ini) AS ano_{$field}_ini";
					$str_field .= ($str_field != "" ? ",\n\t" : "") . "DAY($field_table.{$field}_fim) AS dia_{$field}_fim, MONTH($field_table.{$field}_fim) AS mes_{$field}_fim, YEAR($field_table.{$field}_fim) AS ano_{$field}_fim";
				} else if ($type == "textarea" && $this->db == "MSSQL") {
					$str_field .= ($str_field != "" ? ",\n\t" : "") . "CONVERT(text,$field_table.$field) AS $field";
				} else if ($type == "display") {
					$str_field .= ($str_field != "" ? ",\n\t" : "") . ($this->getFieldLbl($field) == $field ? "$field_table.$field" : $field);
				} else if ($type != "fieldlist" &&
					($type != "dropdown" || !array_key_exists("multiple", $prop))) {
					if (is_array($field)) {
						$str_concat = ""; $str_label = "";
						for ($j=0; $j<count($field); $j++) {
							$str_field .= ($str_field != "" ? ",\n\t" : "") . "$field_table." . $field[$j];
							$str_concat .= ($j > 0 ? "+';'+" : "") . "$field_table." . $field[$j];
							$str_label .= ($j > 0 ? "_" : "") . $field[$j];
							if ($tab_table != "") $str_field .= " AS {$tab_table}_" . $field[$j];
						}
						$str_field .= ",\n\t$str_concat AS $str_label";
					} else {
						$str_field .= ($str_field != "" ? ",\n\t" : "") . "$field_table.$field";
						//if ($tab_table != "") $str_field .= " AS {$tab_table}_{$field}";
					}
				}
			}
			if ($f->type == "fieldfilter") {
				if (!empty($f->ajaxtable)) {
					if (strpos($f->ajaxlabel, ".") === false && strpos($f->ajaxlabel, "(") === false)
						$label_field = $f->ajaxtable . "." . $f->ajaxlabel;
					else
						$label_field = $f->ajaxlabel;
					$str_field .= ",\n\t$label_field AS label_" . $this->getFieldLbl($f->field);
					$ix = $f->ajaxid;
					if (!is_array($ix)) $ix = array($ix);
					if (!is_array($field)) $field = array($field);
					$str_join .= "LEFT JOIN " . $f->ajaxtable . " ON ";
					for ($j=0; $j<count($ix); $j++) {
						 $str_join .= ($j > 0 ? "AND " : "") . $f->ajaxtable . "." . $ix[$j] . " = $field_table." . $field[$j] . "\n";
					}
				}
			}
			if (isset($f->file)) {
				for ($j=0; $j<count($f->file); $j++) {
					preg_match_all("[\[([a-zA-Z0-9_\.])+\]]", $f->file[$j]["dir"]["O"], $match);
					for ($k=0; $k<count($match[0]); $k++) {
						if (strpos($match[0][$k], ".") === false)
							$temp = "$field_table." . substr($match[0][$k],1,-1);
						else
							$temp = substr($match[0][$k],1,-1);
						if (strpos($str_field, $temp) === false) 
							$str_field .= ($str_field != "" ? ",\n\t" : "") . "$temp";
							if ($tab_table != "") $str_field .= " AS {$tab_table}_{$temp}";
					}
				}
			}
			if (!empty($f->ref_label)) {
				$str_field .= ",\n\t$f->ref_label AS label_" . $f->field;
			}
		}
		foreach ($obj->dependency as $d) {
			$t = $d["table"];
			$f = $d["field"];
			$str_field .= ",\n\tISNULL($t.total,0) AS total_$t";
			$str_join .= "LEFT JOIN (SELECT $f, COUNT(*) AS total FROM $t GROUP BY $f) $t ON\n\t$t.$f = $ent_table.$pk\n";
		}
		$a = [];
		if (!empty($obj->prevent_del)) $a = array_merge($a, $obj->prevent_del);
		if (!empty($obj->prevent_edit)) $a = array_merge($a, $obj->prevent_edit);
		if (!empty($a)) {
			foreach ($a as $ck) {
				$_field = $ck["field"];
				if (strpos($_field,".") === false) $_field = "$ent_table.$_field";
				$str_field .= ",\n\t$_field";
				if ($tab_table != "") $str_field .= " AS {$tab_table}_" . $this->getFieldLbl($_field);
			}
		}
		if (isset($ref["entity_duplicate_fields"])) {
			$str_field .= "\n\t, " . $ref["entity_duplicate_parent"];
		}
		$str_field = "\t$str_field";
		$pk_str = "";
		if (is_array($obj->pk)) {
			foreach ($obj->pk as $pk) {
				$pk_str .= ($pk_str != "" ? ", " : "") . "$ent_table.$pk";
			}
		} else {
			$pk_str = "$ent_table." . $obj->pk;
		}
		$pk_str = "\t$pk_str";
		if (!empty($tab_table)) {
			$where_str = "\t$ent_table." . $ref["key_field"] . " = '" . $this->res_upd["id_$tab_table"] . "' AND\n";
		} else if (is_array($ref["key_field"])) {
			$where_str = "";
			foreach ($ref["key_field"] as $ix => $f) {
				if (isset($this->pkVal[$f]))
					$where_str .= "\t$ent_table.$f = '" . $this->pkVal[$f] . "' AND\n";
				else if ($this->get_field_def($ix)->type == "date")
					$where_str .= "\t$ent_table.$f = CONVERT(datetime,'" . $this->res_upd[$ix] . "',120) AND\n";
				else
					$where_str .= "\t$ent_table.$f = '" . $this->res_upd[$ix] . "' AND\n";
			}
		} else {
			$where_str = "\t$ent_table." . $ref["key_field"] . " = '" . $this->pkVal[$this->pk] . "' AND\n";
		}
		if (!empty($obj->key_condition)) {
			$where_str .= "\t$obj->key_condition AND\n";
		}
		$where_str .= "\t1 = 1";
		$qry = "SELECT\n" . 
				"$pk_str,\n" . 
				(!is_array($key_field) ? "\t$ent_table.$key_field,\n" : "") . 
				"$str_field\n" . 
			"FROM $ent_table\n" . 
			$str_join . 
			($ref["ck_history"] == 1 ? "INNER JOIN (SELECT MAX(" . $ref["history_dt_field"] . ") AS log, " . $ref["history_key_field"] . " AS ix
				FROM $ent_table
				WHERE 
					$where_str
					1 = 1
				GROUP BY " . $ref["history_key_field"] . ") max ON max.log = $ent_table." . $ref["history_dt_field"] . " AND max.ix = $ent_table." . $ref["history_key_field"] . "\n" : "") . 
			"WHERE\n" .
				$where_str;
		$order_str = "";
		if (isset($obj->order))
			$order_str .= ($order_str != "" ? ", " : "") . $obj->order;
		if (isset($ref["entity_duplicate_fields"]))
			$order_str .= ($order_str != "" ? ", " : "") . $ref["entity_duplicate_parent"] . ", " . $ref["pk"];
		if ($order_str != "")
			$qry .= "\nORDER BY $order_str";
		if ($this->debug == 1) $this->show_debug($qry,"E");
		return nc_query($qry);
	}
	private function get_file_name($mask, $id, $prefix = "", $c = "") {
		$str = $mask;
		preg_match_all("[\[(\.)*[,a-zA-Z0-9_]+\]]", $str, $match);
		foreach ($match[0] as $m) {
			$var = str_replace([ "[", ".", "]" ], "", $m);
			$temp = explode(",", $var);
			if (count($temp) > 1) $var = $temp[0];
			if ($var == "id") {
				$val = $id;
			} else {
				$var = $prefix . $var . $c;
				if (isset($this->vars[$var])) {
					$val = $this->vars[$var];
				} else if (isset($this->vars[$var."0"])) {
					$c = 0;
					$val = "";
					while (isset($this->vars[$var.$c])) {
						$val .= $this->vars[$var.$c];
						$c++;
					}
				} else {
					$val = "";
					for ($j=0; $j<count($this->ref); $j++) {
						if ($this->ref[$j]["field"] == $var) {
							if (isset($this->ref[$j]["mask"])) {
								$c = 0;
								for ($k=0; $k<count($this->ref[$j]["mask"]); $k++) {
									if (is_numeric($this->ref[$j]["mask"][$k])) {
										$val .= $this->vars[$var.$c];
										$c++;
									} else if ($this->ref[$j]["write_mask"]) {
										$val .= $this->ref[$j]["mask"][$k];
									}
								}
							}
							break;
						}
					}
				}
			}
			$var = $m;
			if (count($temp) > 1)
				$str = str_replace($var, str_pad($val, $temp[1], '0', STR_PAD_LEFT), $str);
			else
				$str = str_replace($var, $val, $str);
		}
		return $str;
	}
	// build functions
	private function get_default($ref) {
		if (isset($ref->default)) {
			$default = $ref->default;
			if (is_callable($default))
				return $default();
			else
				return $default;
		}
		return isset($ref->default) ? $ref->default : "";
	}
	private function get_field($params) {
		foreach ($params as $ix => $val) $$ix = $val;
		if (!isset($scope)) exit("Invalid \$params for get_field([" . implode(", ",array_keys($params)) . "]), missing 'scope'");
		if (!isset($ref)) exit("Invalid \$params for get_field([" . implode(", ",array_keys($params)) . "]), missing 'ref'");
		if (!isset($res)) $res = [];
		if (!isset($prefix)) $prefix = "";
		if (!isset($sufix)) $sufix = "";
		if (!isset($class)) $class = "";
		if (!isset($ck_print)) $ck_print = $this->ck_print;
		// if (!isset($ref["object"])) { echo "<pre>"; print_r($ref); echo "</pre>"; }
		$modo = $this->modo == "complete" && isset($this->vars["modo"]) ? $this->vars["modo"] : $this->modo;
		if (gettype($ref) != "object" && isset($ref["object"])) $ref = $ref["object"];
		if (!empty($ref->ck_log) && $this->modo != "insert") unset($ref->default);
		if ($scope == "filter" &&
			($ref->type == "date" || $ref->type == "datetime")) {
			$ref->type = "daterange";
			$obj = "filter";
		} else if ($scope == "filter" && $ref->type == "month") {
			$ref->type = "monthrange";
			$obj = "filter";
		} else if ($scope == "filter" &&
			$ref->type == "text" &&
			isset($ref->mask)) {
			$obj = "filter";
		} else if ($scope == "filter") {
			$obj = "filter";
		} else if ($scope == "list") {
			$obj = "list";
		} else if ($ref->entity !== false) {
			$obj = "entity_field";
			$entity_ref = $ref->entity->field;
		} else {
			$obj = "field";
		}
		if (empty($class)) $class = ($obj == "entity_field" ? $this->css_formpeq : $this->css_form); 
		$field_label = $this->getFieldLbl($ref->field);
		// tab
		if ($scope != "filter" && $ref->tab_id != -1 && $this->tab_ref[$ref->tab_id]["table"] != null && empty($prefix)) {
			$field_label = $this->tab_ref[$ref->tab_id]["table"] . "_" . $field_label;
		}
		// readonly
		if ($scope == "filter" && count($this->ref_filter) == 0) {
			$ck_readonly = 0;
		} else if (isset($ref->ck_readonly)) {
			if (is_array($ref->ck_readonly)) {
				if ($obj == "list") {
					$ck_readonly = $this->ck_condition($this->ref_list, $ref->ck_readonly, $res);
				} else if ($obj == "entity_field") {
					/*
					if ($this->ck_entity_field($entity_ref, $ref->ck_readonly["field"]))
						$res_ck = $res;
					else if ($this->modo == "update")
						$res_ck = $this->res_upd;
					else
						$res_ck = [];
					$ck_readonly = $this->ck_condition($entity_ref, $ref->ck_readonly, $res_ck);
					*/
					if ($this->ck_entity_field($entity_ref, $ref->ck_readonly["field"]))
						$ck_readonly = $this->ck_condition($entity_ref, $ref->ck_readonly, $res);
					else if ($this->modo == "update")
						$ck_readonly = $this->ck_condition($this->ref, $ref->ck_readonly, $this->res_upd);
					else
						$ck_readonly = $this->ck_condition($entity_ref, $ref->ck_readonly, []);
				} else {
					$ck_readonly = $this->ck_condition($this->ref, $ref->ck_readonly, $res);
				}
			} else {
				$ck_readonly = $ref->ck_readonly;
			}
		} else if ($scope == "form" && $obj == "entity_field" && empty($res)) {
			$ck_readonly = 0;
		} else if (array_key_exists("readonly", $ref->prop)) {
			$ck_readonly = 1;
		} else {
			$ck_readonly = $this->ck_readonly;
		}
		// disabled
		if (isset($ref->ck_disabled) && is_array($ref->ck_disabled)) {
			if ($obj == "list") {
				$ck_disabled = $this->ck_condition($this->ref_list, $ref->ck_disabled, $res);
			} else if ($obj == "entity_field") {
				if ($this->ck_entity_field($entity_ref, $ref->ck_disabled["field"]))
					$res_ck = $res;
				else if ($this->modo == "update")
					$res_ck = $this->res_upd;
				else
					$res_ck = [];
				$ck_disabled = $this->ck_condition($entity_ref, $ref->ck_disabled, $res_ck);
			} else {
				$ck_disabled = $this->ck_condition($this->ref, $ref->ck_disabled, $res);
			}
		} else if (in_array("disabled", array_keys($ref->prop))) { // isset($ref->prop["disabled"]) not working
			$ck_disabled = 1;
		} else {
			$ck_disabled = 0;
		}
		//else if (array_key_exists("prop", $ref) && array_key_exists("readonly", $ref->prop)) unset($ref->prop["readonly"]);
		// properties
		$prop_str = "";
		$label_prop_str = "";
		$_prop = $ref->prop;
		if ($ck_readonly == 1) $_prop["readonly"] = true;
		if ($ck_disabled == 1) $_prop["disabled"] = true;
		if (isset($ref->ck_readonly) && is_array($ref->ck_readonly) && $ck_readonly == 0) unset($_prop["readonly"]);
		if (isset($ref->ck_disabled) && is_array($ref->ck_disabled) && $ck_disabled == 0) unset($_prop["disabled"]);
		foreach ($_prop as $prop => $val) {
			if (empty($ref->prop_condition[$prop]) || call_user_func($ref->prop_condition[$prop], $res)) {
				$parts = explode(":",$prop);
				if (count($parts) > 1 && $parts[0] == "label") {
					$label_prop_str .= ($label_prop_str != "" ? " " : "") . $parts[1] . "=\"$val\"";
				} else {
					if (!empty($res)) {
						preg_match_all("/\[([a-z0-9_]+)\]/i", $val, $match);
						foreach ($match[0] as $found) {
							if (isset($res[substr($found,1,-1)])) {
								$r = $res[substr($found,1,-1)];
								if ($r instanceof DateTime) $r = $r->format("Y-m-d");
								$val = str_replace($found, $r, $val);
							}
						}
					}
					$prop_str .= " " . (!empty($val) ? "$prop=\"$val\"" : $prop);
				}
			}
		}
		// replacing [] markers defined on setup_form()
		if ($scope == "list") $prop_str = str_replace("[]",$sufix,$prop_str);
		// output
		$field_name = $prefix . $field_label;
		if (in_array(gettype($sufix), ["integer", "string"])) $field_name .= $sufix; // $sufix = true esado em readroot para indicar que os clones terao sufixo
		$str = "";
		$debug = "";
		if (!empty($ref->ck_log) && empty($ck_print) && empty($this->ck_readonly) && $ref->type != "hidden" && !empty($res) && array_key_exists($field_label, $res)) {
			$str .= nl2br((string)$res[$field_label]);
		}
		if (empty($ck_print) && $ref->ck_bak == 1) {
			if (in_array($ref->type, ["date", "datetime"]) && $this->pref_field_date == "text") {
				$str .= "<input type=\"hidden\" name=\"dia_{$prefix}" . $field_label . "_bak{$sufix}\" id=\"dia_{$prefix}" . $field_label . "_bak{$sufix}\" value=\"" . (!empty($res) ? $res["dia_".$field_label] : "") . "\">\n";
				$str .= "<input type=\"hidden\" name=\"mes_{$prefix}" . $field_label . "_bak{$sufix}\" id=\"mes_{$prefix}" . $field_label . "_bak{$sufix}\" value=\"" . (!empty($res) ? $res["mes_".$field_label] : "") . "\">\n";
				$str .= "<input type=\"hidden\" name=\"ano_{$prefix}" . $field_label . "_bak{$sufix}\" id=\"ano_{$prefix}" . $field_label . "_bak{$sufix}\" value=\"" . (!empty($res) ? $res["ano_".$field_label] : "") . "\">\n";
				if ($ref->type == "datetime") {
					$str .= "<input type=\"hidden\" name=\"hor_{$prefix}" . $field_label . "_bak{$sufix}\" id=\"hor_{$prefix}" . $field_label . "_bak{$sufix}\" value=\"" . (!empty($res) ? $res["hor_".$field_label] : "") . "\">\n";
					$str .= "<input type=\"hidden\" name=\"min_{$prefix}" . $field_label . "_bak{$sufix}\" id=\"min_{$prefix}" . $field_label . "_bak{$sufix}\" value=\"" . (!empty($res) ? $res["min_".$field_label] : "") . "\">\n";
				}
			} else {
				if (!empty($res)) {
					$val = $res[$field_label];
					if ($val instanceof DateTime) $val = $val->format("Y-m-d");
				} else {
					$val = "";
				}
				$str .= "<input type=\"hidden\" name=\"{$prefix}" . $field_label . "_bak{$sufix}\" id=\"{$prefix}" . $field_label . "_bak{$sufix}\" value=\"$val\">\n";
			}
		}
		if ($ref->type == "text" || 
			$ref->type == "findtext" || 
			$ref->type == "number" || 
			$ref->type == "datetime-local" || 
			$ref->type == "time" || 
			($ref->type == "date" && $this->pref_field_date == "date")) {
			if (!empty($res) && array_key_exists($field_label, $res) && empty($ref->ck_log)) {
				$val = $res[$field_label];
				if (!empty($ref->parser)) $val = ($ref->parser)($val,$res);
			} else {
				$val = "";
			}
			if ($ck_print == 1 || $this->ck_readonly == 1) {
				if ($ref->type == "time" && !empty($val)) $val = floor($val/60) . ":" . str_pad($val%60,2,'0',STR_PAD_LEFT);
				if ($val == "") $val = "-";
				$str .= "<span class=\"$this->css_text_print\" id=\"$field_name\">$val</span>";
			} else {
				if (empty($res)) $val = $this->get_default($ref);
				if (isset($ref->mask)) {
					$pos = 0;
					$str_js = "";
					$ck_show_mask = 1;
					if ($ref->unlock_mask == 1 && !empty($res)) {
						$pat = "";
						for ($i=0; $i<count($ref->mask); $i++) {
							if (is_numeric($ref->mask[$i]))
								$pat .= "[0-9A-Za-z]{" . $ref->mask[$i] . "}";
							else if ($ref->write_mask == 1)
								$pat .= $ref->mask[$i];
						}
						$pat = "/^$pat\$/";
						if (!empty($val)) {
							preg_match($pat, $val, $match);
							if (count($match) == 0) $ck_show_mask = 0;
						}
					}
					$js_str = "";
					$c = 0;
					for ($i=0; $i<count($ref->mask); $i++) {
						if (is_numeric($ref->mask[$i])) {
							$js_str .= ($js_str != "" ? "+" : "") . "{$prefix}$field_label{$c}{$sufix}.value";
							$c++;
						}
					}
					if ($ref->unlock_mask == 1) {
						$str .= "<span id=\"field_$field_name\" " . ($ck_show_mask == 1 ? "style=\"display:none\"" : "") . ">";
						$str .= "<input type=text name=\"{$prefix}" . $field_label . "{$sufix}\" id=\"{$prefix}" . $field_label . "{$sufix}\" value=\"$val\" class=$class $prop_str " . ($ck_show_mask == 1 ? "disabled" : "") . ">\n";
						$str .= "</span>";
						$js_str = "{$prefix}$field_label{$sufix}.value=$js_str;";
						if (strpos($prop_str, "onchange") !== false) $prop_str = str_replace("onchange=\"", "onchange=\"$js_str", $prop_str);
						else $prop_str .= " onchange=\"$js_str\"";
					}
					$c = 0;
					$str .= "<span id=\"mask_$field_name\" " . ($ck_show_mask == 0 ? "style=\"display:none\"" : "") . ">";
					for ($i=0; $i<count($ref->mask); $i++) {
						if (is_numeric($ref->mask[$i])) {
							if ($obj == "filter") {
								$str .= "<input type=text name=\"{$prefix}" . $field_label . "{$c}{$sufix}\" id=\"{$prefix}" . $field_label . "{$c}{$sufix}\" value=\"" . (!empty($res) && $ck_show_mask == 1 && $res[$field_label] != null ? substr($res[$field_label.$c], $pos, $ref->mask[$i]) : (isset($ref->default) ? substr($ref->default, $pos, $ref->mask[$i]) : "")) . "\" class=$class size=".$ref->mask[$i]." maxlength=".$ref->mask[$i]." $prop_str>\n";
							} else {
								$str .= "<input type=text name=\"{$prefix}" . $field_label . "{$c}{$sufix}\" id=\"{$prefix}" . $field_label . "{$c}{$sufix}\" value=\"" . (!empty($res) && $ck_show_mask == 1 && $res[$field_label] != null ? substr($res[$field_label], $pos, $ref->mask[$i]) : (isset($ref->default) ? substr($ref->default, $pos, $ref->mask[$i]) : "")) . "\" class=$class size=".$ref->mask[$i]." maxlength=".$ref->mask[$i]." $prop_str>\n";
								$pos += $ref->mask[$i];
							}
							$str_js .= ($str_js != "" ? "," : "") . $ref->mask[$i];
							$c++;
						} else {
							$str .= $ref->mask[$i] . "\n";
							if ($ref->write_mask == 1) {
								$str_js .= ($str_js != "" ? "," : "") . "'" . $ref->mask[$i] . "'";
								$pos += strlen($ref->mask[$i]);
							}
						}
					}
					$str .= "</span>";
					if ($ref->unlock_mask == 1 && $ck_readonly != 1) {;
						$str .= "<img id=\"toggle_$field_name\" src=\"$this->img_dir/" . ($ck_show_mask == 1 ? $this->img_lock : $this->img_unlock) . "\" style=\"cursor:pointer\" onclick=\"toggleLock(this,'{$prefix}" . $field_label . "',this.id.replace('toggle_{$prefix}" . $field_label . "',''),[$str_js])\">";
					}
				} else {
					$type = $ref->type == "findtext" ? "text" : $ref->type;
					if (!empty($val)) {
						if ($ref->type == "datetime-local") 
							$val = $val->format("Y-m-d H:i");
						else if ($ref->type == "time") 
							$val = str_pad(floor($val/60),2,'0',STR_PAD_LEFT) . ":" . str_pad($val%60,2,'0',STR_PAD_LEFT);
						else if ($val instanceof DateTime) 
							$val = $val->format("Y-m-d");
						else if (!empty($val)) 
							$val = str_replace("\"","'",$val);
					}
					$str .= "<input type=\"$type\" name=\"$field_name\" id=\"$field_name\" value=\"$val\" class=$class $prop_str>\n";
				}
			}
		} else if ($ref->type == "password") {
			$str .= "<input type=text name=\"autocomplete_off_$field_name\" style=\"display:none\" disabled>\n"; // deve existir um campo entre o usuario e a senha para prevenir autocomplete
			$str .= "<input type=password name=\"$field_name\" id=\"$field_name\" class=$class autocomplete=\"off\" $prop_str>\n";
		} else if ($ref->type == "range") {
			$str .= "<input type=range name=\"$field_name\" id=\"$field_name\" value=\"" . (!empty($res) && array_key_exists($field_label, $res) ? $res[$field_label] : $this->get_default($ref)) . "\" class=$class $prop_str>\n";
		} else if ($ref->type == "hidden") {
			if (!empty($res) && array_key_exists($field_label, $res) && empty($ref->ck_log))
				$val = $res[$field_label];
			else
				$val = $this->get_default($ref);
			if (!empty($val)) $val = str_replace('"', "&quot;", $val);
			$str .= "<input type=\"hidden\" name=\"$field_name\" id=\"$field_name\" value=\"$val\">\n";
		} else if ($ref->type == "file") {
			if (empty($ck_print) && $this->ck_readonly == 0) {
				$str .= "<input type=\"file\" name=\"$field_name" . (array_key_exists("multiple", $ref->prop) ? "[]" : "") . "\" id=\"$field_name\" class=\"$class\" $prop_str>\n";
			}
			if ($modo != "insert" && !empty($res)) {
				$ck_file = 0;
				$thumb_ref = null;
				$preview_ref = null;
				for ($i=0; $i<count($ref->file); $i++) {
					if ($ref->file[$i]["ck_thumb"] == 1 ||
						count($ref->file) == 1) {
						$thumb_ref = $ref->file[$i];
						$thumb_dir = $thumb_ref["dir"]["O"]; // origem
						preg_match_all("[\[([a-zA-Z0-9_\.,])+\]]", $thumb_dir, $match);
						for ($j=0; $j<count($match[0]); $j++) {
							$_temp = explode(",",substr($match[0][$j],1,-1));
							$_ix = $this->getFieldLbl($_temp[0]);
							if (isset($this->res_upd[$_ix])) 
								$val = $this->res_upd[$_ix];
							else
								$val = $res[$_ix];
							if (count($_temp) > 1) $val = str_pad($val,$_temp[1],'0',STR_PAD_LEFT);
							$thumb_dir = str_replace($match[0][$j], $val, $thumb_dir);
						}
					} 
					if ($ref->file[$i]["ck_thumb"] == 0 ||
						count($ref->file) == 1) {
						$preview_ref = $ref->file[$i];
						$preview_dir = $preview_ref["dir"]["O"];
						preg_match_all("[\[([a-zA-Z0-9_\.,])+\]]", $preview_dir, $match);
						for ($j=0; $j<count($match[0]); $j++) {
							$_temp = explode(",",substr($match[0][$j],1,-1));
							$_ix = $this->getFieldLbl($_temp[0]);
							if (isset($this->res_upd[$_ix])) 
								$val = $this->res_upd[$_ix];
							else
								$val = $res[$_ix];
							if (count($_temp) > 1) $val = str_pad($val,$_temp[1],'0',STR_PAD_LEFT);
							$preview_dir = str_replace($match[0][$j], $val, $preview_dir);
						}
					}
				}
				if ($ref->ck_qry == 1 && $res[$field_label] != "") {
					$strAttach = ""; $ckPic = 0;
					foreach (explode("|", $res[$field_label]) as $file) {
						$thumb_file_name = $res[$field_label];
						if (substr($file,-3) == "pdf") {
							$pdf_thumb_format = substr($file,0,-4) . "-0.jpg";
							if (is_file("$thumb_dir/$pdf_thumb_format")) {
								$fp = fopen("$thumb_dir/$pdf_thumb_format", "r");
								if ($fp) {
									$thumb_file_name = $pdf_thumb_format;
									fclose($fp);
								}
							}
						}
						$preview_file_name = $file;
						$fp = @fopen("$preview_dir/$preview_file_name", "r");
						if ($fp) {
							$ck_file = 1;
							fclose($fp);
						}
						if ($ck_print == 1 || $this->ck_readonly == 1) {
							if ((($ref->ck_qry == 1 && $res[$field_label] != "") ||
								 ($ref->ck_qry == 0 && $ck_file == 1))) {
								if (substr($thumb_file_name,-3) == "jpg" || substr($thumb_file_name,-3) == "png" || substr($thumb_file_name,-3) == "gif")
									$strAttach .= "<a href=\"$preview_dir/" . rawurlencode($preview_file_name) . "\" target=_blank><img src=\"$thumb_dir/$thumb_file_name\" style=\"max-width:200px;max-height:200px;\" border=0></a>"; //<br>".$res[$field_label];
								else
									$strAttach .= "<a class=\"$this->css_link_general\" href=\"$preview_dir/" . rawurlencode($preview_file_name) . "\" target=_blank><img src=\"$this->img_dir/$this->img_anexo\" align=\"absmiddle\" border=0>$preview_file_name</a>"; //<br>".$res[$field_label];
							} else {
								$strAttach .= "-";
							}
						} else {
							if ((($ref->ck_qry == 1 && $res[$field_label] != "") ||
								 ($ref->ck_qry == 0 && $ck_file == 1))) {
								if (isset($thumb_dir) && is_file("$thumb_dir/$thumb_file_name")) {
									$ck_file = 1;
									$fileSize = getImageSize("$thumb_dir/$thumb_file_name");
								} else {
									$ck_file = 0;
								}
								if ($ck_file == 1 && !empty($fileSize) && in_array($fileSize[2], [1,2,3])) {
									$ckPic = 1;
									if ($thumb_ref != null) 
										$strAttach .= "<a id=\"holder-$file\" href=\"$preview_dir/" . rawurlencode($preview_file_name) . "\" target=_blank><img src=\"$thumb_dir/$thumb_file_name\" border=0 style=\"max-width:500px;max-height:500px;\"></a>\n";
									else
										$strAttach .= "<img id=\"holder-$file\" src=\"$preview_dir/$preview_file_name\" border=0></a>\n";
								} else {
									if ($ref->ck_file_link) 
										$strAttach .= "<a id=\"holder-$file\" href=\"$preview_dir/" . rawurlencode($preview_file_name) . "\" target=_blank>$file</a>\n"; 
									else
										$strAttach .= "<span id=\"holder-$file\">$file</span>\n"; 
								}
								if ($ref->ck_req == 0 || array_key_exists("multiple", $ref->prop)) {
									$strAttach .= "<img src=\"$this->img_dir/$this->img_trash\" id=\"but-rem-$file\" onclick=\"remFile('$file')\" style=\"cursor:pointer;\">\n";
									$strAttach .= "<label style=\"display:none;\"><input type=\"checkbox\" id=\"f-rem-$file\" name=\"{$prefix}remove{$field_label}{$sufix}[]\" value=\"$preview_file_name\">$this->lang_remove_file</label>\n";
								}
							}
						}
					}
					$str .= "<div id=\"{$prefix}" . "file-" . $field_label . "{$sufix}\" " . ($obj != "entity_field" && $ckPic == 0 ? "style=\"display:inline-block;vertical-align:baseline;\"" : "") . ">\n";
					$str .= $strAttach;
					$str .= "</div>";
				}
				if ($ref->ck_qry) $str .= "<input type=\"hidden\" name=\"{$field_name}_bak\" id=\"{$field_name}_bak\" value=\"" . $res[$field_label] . "\">\n";
			}
		} else if ($ref->type == "textarea") {
			if ($ck_print == 1 || $this->ck_readonly == 1) 
				$str .= "<span class=\"$this->css_text_print\" id=\"$field_name\">" . nl2br((string)$res[$field_label]) . "</span>";
			else
				$str .= "<textarea name=\"$field_name\" id=\"$field_name\" class=$class $prop_str>" . (!empty($res) && empty($ref->ck_log) && $ref->ck_qry == 1 ? $res[$field_label] : $this->get_default($ref)) . "</textarea>\n";
		} else if ($ref->type == "date" && $this->pref_field_date == "text") {
			if ($ck_print == 1 || $this->ck_readonly == 1 || $ref->ck_readonly == 1) {
				$str .= "<span class=\"$this->css_text_print\" id=\"$field_name\">" . $res[$field_label] . "</span>";
			} else {
				$str .= "<input type=text name=\"dia_$field_name\" id=\"dia_$field_name\" value=\"" . (!empty($res) ? ($res["dia_".$field_label] != "" ? str_pad($res["dia_".$field_label],2,'0',STR_PAD_LEFT) : "") : (isset($ref->default) ? substr($ref->default,8,2) : "")) . "\" size=2 maxlength=2 class=$class $prop_str> /\n";
				$str .= "<input type=text name=\"mes_$field_name\" id=\"mes_$field_name\" value=\"" . (!empty($res) ? ($res["mes_".$field_label] != "" ? str_pad($res["mes_".$field_label],2,'0',STR_PAD_LEFT) : "") : (isset($ref->default) ? substr($ref->default,5,2) : "")) . "\" size=2 maxlength=2 class=$class $prop_str> /\n";
				$str .= "<input type=text name=\"ano_$field_name\" id=\"ano_$field_name\" value=\"" . (!empty($res) ? $res["ano_".$field_label] : (isset($ref->default) ? substr($ref->default,0,4) : "")) . "\" size=4 maxlength=4 class=$class $prop_str>\n";
				if ($this->use_CalendarPopup == 1 && $prefix != "global_" && !array_key_exists("readonly", $ref->prop)) $str .= "<span id=\"cal_$field_name\"></span><script type=\"text/javascript\">addCalendar(\"_$field_name\"" . (isset($ref->prop["onchange"]) ? ",function(id) { this.id = id; " . $ref->prop["onchange"] . " }" : "") . ");</script>\n";
			}
		} else if ($ref->type == "datetime" && $this->pref_field_date == "text") {
			if ($ck_print == 1 || $this->ck_readonly == 1 || (isset($ref->ck_readonly) && $ref->ck_readonly == 1)) {
				$str .= "<span class=\"$this->css_text_print\" id=\"$field_name\">" . ($res[$field_label] != "" ? $res[$field_label] : "") . "</span>";
			} else {
				$str .= "<input type=text name=\"dia_$field_name\" id=\"dia_$field_name\" value=\"" . (!empty($res) ? ($res["dia_".$field_label] != "" ? str_pad($res["dia_".$field_label],2,'0',STR_PAD_LEFT) : "") : (isset($ref->default) ? substr($ref->default,8,2) : "")) . "\" size=2 maxlength=2 class=$class $prop_str> /\n";
				$str .= "<input type=text name=\"mes_$field_name\" id=\"mes_$field_name\" value=\"" . (!empty($res) ? ($res["mes_".$field_label] != "" ? str_pad($res["mes_".$field_label],2,'0',STR_PAD_LEFT) : "") : (isset($ref->default) ? substr($ref->default,5,2) : "")) . "\" size=2 maxlength=2 class=$class $prop_str> /\n";
				$str .= "<input type=text name=\"ano_$field_name\" id=\"ano_$field_name\" value=\"" . (!empty($res) ? $res["ano_".$field_label] : (isset($ref->default) ? substr($ref->default,0,4) : "")) . "\" size=4 maxlength=4 class=$class $prop_str> - \n";
				$str .= "<input type=text name=\"hor_$field_name\" id=\"hor_$field_name\" value=\"" . (!empty($res) ? ((string)$res["hor_".$field_label] != "" ? str_pad($res["hor_".$field_label],2,'0',STR_PAD_LEFT) : "") : (isset($ref->default) ? substr($ref->default,11,2) : "")) . "\" size=2 maxlength=2 class=$class $prop_str>:";
				$str .= "<input type=text name=\"min_$field_name\" id=\"min_$field_name\" value=\"" . (!empty($res) ? ((string)$res["min_".$field_label] != "" ? str_pad($res["min_".$field_label],2,'0',STR_PAD_LEFT) : "") : (isset($ref->default) ? substr($ref->default,14,2) : "")) . "\" size=2 maxlength=2 class=$class $prop_str>\n";
				if ($this->use_CalendarPopup == 1 && $prefix != "global_" && !array_key_exists("readonly", $ref->prop)) $str .= "<span id=\"cal_$field_name\"></span><script type=\"text/javascript\">addCalendar(\"_$field_name\"" . (isset($ref->prop["onchange"]) ? ",function(id) { this.id = id; " . $ref->prop["onchange"] . " }" : "") . ");</script>\n";
			}
		} else if ($ref->type == "daterange") {
			if ($ck_print == 1 || $this->ck_readonly == 1) {
				$str .= "<span class=\"$this->css_text_print\" id=\"$field_name\">";
				$str .= ($res["dia_".$field_label."_ini"] != "" ? $res["dia_".$field_label."_ini"] . "/" . $res["mes_".$field_label."_ini"] . "/" . $res["ano_".$field_label."_ini"] : ""); 
				$str .= ($res["dia_".$field_label."_fim"] != "" ? $res["dia_".$field_label."_fim"] . "/" . $res["mes_".$field_label."_fim"] . "/" . $res["ano_".$field_label."_fim"] : ""); 
				$str .= "</span>";
			} else {
				if (isset($ref->default))
					$_ck = !empty($ref->default[2]);
				else if (!empty($res) && $res["dia_".$field_label."_ini"] != "")
					$_ck = true;
				else
					$_ck = false;
				$str .= "<input type=checkbox name=\"$field_name\" id=\"$field_name\" value=1 onclick=dia_{$prefix}" . $field_label . "_ini{$sufix}.disabled=!this.checked;mes_{$prefix}" . $field_label . "_ini{$sufix}.disabled=!this.checked;ano_{$prefix}" . $field_label . "_ini{$sufix}.disabled=!this.checked;dia_{$prefix}" . $field_label . "_fim{$sufix}.disabled=!this.checked;mes_{$prefix}" . $field_label . "_fim{$sufix}.disabled=!this.checked;ano_{$prefix}" . $field_label . "_fim{$sufix}.disabled=!this.checked " . ($_ck ? "CHECKED" : "") . " " . ($this->modo == "insert" || ($this->modo == "update" && $this->step == 2) ? "style=display:none" : "")  . ">\n";
				$str .= "<input type=text name=\"dia_{$prefix}" . $field_label . "_ini{$sufix}\" id=\"dia_{$prefix}" . $field_label . "_ini{$sufix}\" value=\"" . (!empty($res) ? $res["dia_".$field_label."_ini"] : (isset($ref->default) ? str_pad(substr($ref->default[0],8,2),2,'0',STR_PAD_LEFT) : "")) . "\" size=2 maxlength=2 class=$class $prop_str onfocus=\"recvalue(this);temp=this.value\" onkeyup=\"gotofield(this)\" onblur=\"temp!=this.value?document.getElementById(this.id.replace('dia_','').replace('_ini','')).checked=1:void(null)\" " . (!$_ck ? "DISABLED" : "") . "> /\n";
				$str .= "<input type=text name=\"mes_{$prefix}" . $field_label . "_ini{$sufix}\" id=\"mes_{$prefix}" . $field_label . "_ini{$sufix}\" value=\"" . (!empty($res) ? $res["mes_".$field_label."_ini"] : (isset($ref->default) ? str_pad(substr($ref->default[0],5,2),2,'0',STR_PAD_LEFT) : "")) . "\" size=2 maxlength=2 class=$class $prop_str onfocus=\"recvalue(this);temp=this.value\" onkeyup=\"gotofield(this)\" onblur=\"temp!=this.value?document.getElementById(this.id.replace('mes_','').replace('_ini','')).checked=1:void(null)\" " . (!$_ck ? "DISABLED" : "") . "> /\n";
				$str .= "<input type=text name=\"ano_{$prefix}" . $field_label . "_ini{$sufix}\" id=\"ano_{$prefix}" . $field_label . "_ini{$sufix}\" value=\"" . (!empty($res) ? $res["ano_".$field_label."_ini"] : (isset($ref->default) ? substr($ref->default[0],0,4) : "")) . "\" size=4 maxlength=4 class=$class $prop_str onfocus=\"recvalue(this);temp=this.value\" onkeyup=\"gotofield(this)\" onblur=\"temp!=this.value?document.getElementById(this.id.replace('ano_','').replace('_ini','')).checked=1:void(null)\" " . (!$_ck ? "DISABLED" : "") . ">\n";
				if ($this->use_CalendarPopup == 1 && ($scope == "filter" || !array_key_exists("readonly", $ref->prop))) $str .= "<script type=\"text/javascript\">addCalendar(\"_{$prefix}" . $field_label . "_ini{$sufix}\",\"document.formulario.{$field_name}.checked = true\")</script> a \n";
				$str .= "<input type=text name=\"dia_{$prefix}" . $field_label . "_fim{$sufix}\" id=\"dia_{$prefix}" . $field_label . "_fim{$sufix}\" value=\"" . (!empty($res) ? $res["dia_".$field_label."_fim"] : (isset($ref->default) ? str_pad(substr($ref->default[1],8,2),2,'0',STR_PAD_LEFT) : "")) . "\" size=2 maxlength=2 class=$class $prop_str onfocus=\"recvalue(this);temp=this.value\" onkeyup=\"gotofield(this)\" onblur=\"temp!=this.value?document.getElementById(this.id.replace('dia_','').replace('_fim','')).checked=1:void(null)\" " . (!$_ck ? "DISABLED" : "") . "> /\n";
				$str .= "<input type=text name=\"mes_{$prefix}" . $field_label . "_fim{$sufix}\" id=\"mes_{$prefix}" . $field_label . "_fim{$sufix}\" value=\"" . (!empty($res) ? $res["mes_".$field_label."_fim"] : (isset($ref->default) ? str_pad(substr($ref->default[1],5,2),2,'0',STR_PAD_LEFT) : "")) . "\" size=2 maxlength=2 class=$class $prop_str onfocus=\"recvalue(this);temp=this.value\" onkeyup=\"gotofield(this)\" onblur=\"temp!=this.value?document.getElementById(this.id.replace('mes_','').replace('_fim','')).checked=1:void(null)\" " . (!$_ck ? "DISABLED" : "") . "> /\n";
				$str .= "<input type=text name=\"ano_{$prefix}" . $field_label . "_fim{$sufix}\" id=\"ano_{$prefix}" . $field_label . "_fim{$sufix}\" value=\"" . (!empty($res) ? $res["ano_".$field_label."_fim"] : (isset($ref->default) ? substr($ref->default[1],0,4) : "")) . "\" size=4 maxlength=4 class=$class $prop_str onfocus=\"recvalue(this);temp=this.value\" onkeyup=\"gotofield(this)\" onblur=\"temp!=this.value?document.getElementById(this.id.replace('ano_','').replace('_fim','')).checked=1:void(null)\" " . (!$_ck ? "DISABLED" : "") . ">\n";
				if ($this->use_CalendarPopup == 1 && ($scope == "filter" || !array_key_exists("readonly", $ref->prop))) $str .= "<script type=\"text/javascript\">addCalendar(\"_{$prefix}" . $field_label . "_fim{$sufix}\",\"document.formulario.{$field_name}.checked = true\")</script>\n";
			}
		} else if ($ref->type == "month") {
			if (!empty($res)) {
				$val = $res[$field_label];
				if ($val instanceof DateTime) {
					$valM = $val->format("m");
					$valY = $val->format("Y");
				} else if ($this->db == "MYSQL" && preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $val, $match)) {
					$valM = $match[2];
					$valY = $match[1];
				} else if (!empty($val)) {
					$valM = substr($val,4,2);
					$valY = substr($val,0,4);
				} else {
					$valM = "";
					$valY = "";
				}
			} else {
				$valM = "";
				$valY = "";
			}
			$meses = [ "01"=>"Jan", "02"=>"Fev", "03"=>"Mar", "04"=>"Abr", "05"=>"Mai", "06"=>"Jun", "07"=>"Jul", "08"=>"Ago", "09"=>"Set", "10"=>"Out", "11"=>"Nov", "12"=>"Dez" ];
			if ($ck_print == 1 || $this->ck_readonly == 1) {
				$str .= "<span class=\"$this->css_text_print\" id=\"$field_name\">" . (!empty($valM) ? $meses[$valM] . "/" . $valY : "") . "</span>";
			} else {
				$str .= "<input type=hidden name=\"dia_$field_name\" id=\"dia_$field_name\" value=\"1\">\n";
				if ($ck_readonly == 1) {
					if (!empty($res)) $str .= $meses[$valM] . "/" . $valY;
					$str .= "<input type=hidden name=\"mes_$field_name\" id=\"mes_$field_name\" value=\"$valM\">\n";
					$str .= "<input type=hidden name=\"ano_$field_name\" id=\"ano_$field_name\" value=\"$valY\">\n";
				} else {
					$str .= "<select grp=\"$field_name\" name=\"mes_{$field_name}" . ($ck_readonly == 1 ? "_bak" : "") . "\" id=\"mes_{$field_name}" . ($ck_readonly == 1 ? "_bak" : "") . "\"  class=$class $prop_str>";
					$str .= "<option value=\"\">--\n";
					foreach ($meses as $m => $label) {
						$str .= "<option " . ((!empty($res) && $m == $valM) || (empty($res) && isset($ref->default) && substr($ref->default,-2) == $m) ? "SELECTED" : "") . " value=\"$m\">$label\n";
					}
					$str .= "</select> / ";
					$str .= "<input type=\"text\" grp=\"$field_name\" name=\"ano_$field_name\" id=\"ano_$field_name\" value=\"" . (!empty($res) ? $valY : (isset($ref->default) ? substr($ref->default,0,4) : "")) . "\" size=4 maxlength=4 class=$class $prop_str>\n";
				}
			}
		} else if ($ref->type == "monthrange") {
			if ($ck_print == 1 || $this->ck_readonly == 1) {
				$str .= "<span class=\"$this->css_text_print\" id=\"$field_name\">";
				$str .= ($res["mes_".$field_label."_ini"] != "" ? $res["mes_".$field_label."_ini"] . "/" . $res["ano_".$field_label."_ini"] : ""); 
				$str .= ($res["mes_".$field_label."_fim"] != "" ? $res["mes_".$field_label."_fim"] . "/" . $res["ano_".$field_label."_fim"] : ""); 
				$str .= "</span>";
			} else {
				if (isset($ref->default)) {
					if (!is_array($ref->default)) $ref->default = [ $ref->default, $ref->default, true ];
					$_ck = !empty($ref->default[2]);
				} else if (!empty($res) && $res["mes_".$field_label."_ini"] != "") {
					$_ck = true;
				} else {
					$_ck = false;
				}
				$meses = array("01"=>"Jan", "02"=>"Fev", "03"=>"Mar", "04"=>"Abr", "05"=>"Mai", "06"=>"Jun", "07"=>"Jul", "08"=>"Ago", "09"=>"Set", "10"=>"Out", "11"=>"Nov", "12"=>"Dez");
				$str .= "<input type=checkbox name=\"$field_name\" id=\"$field_name\" value=1 onclick=mes_{$prefix}" . $field_label . "_ini{$sufix}.disabled=!this.checked;ano_{$prefix}" . $field_label . "_ini{$sufix}.disabled=!this.checked;mes_{$prefix}" . $field_label . "_fim{$sufix}.disabled=!this.checked;ano_{$prefix}" . $field_label . "_fim{$sufix}.disabled=!this.checked " . ($_ck ? "CHECKED" : "") . " " . ($this->modo == "insert" || ($this->modo == "update" && $this->step == 2) ? "style=display:none" : "")  . ">\n";
				$str .= "<select name=\"mes_{$prefix}" . $field_label . "_ini{$sufix}\" id=\"mes_{$prefix}" . $field_label . "_ini{$sufix}\" class=$class $prop_str onfocus=\"recvalue(this);temp=this.value\" onkeyup=\"gotofield(this)\" onchange=\"document.getElementById(this.id.replace('mes_','').replace('_ini','')).checked=this.value!=''?true:false\" " . (!$_ck ? "DISABLED" : "") . ">\n";
				$str .= "<option value=\"\">--\n";
				foreach ($meses as $m => $label) {
					$str .= "<option " . ((!empty($res) && $res["mes_".$field_label."_ini"] == $m) || (!empty($ref->default) && date("n",strtotime($ref->default[0])) == $m) ? "SELECTED" : "") . " value=\"$m\">$label\n";
				}
				$str .= "</select>\n";
				$str .= "<input type=text name=\"ano_{$prefix}" . $field_label . "_ini{$sufix}\" id=\"ano_{$prefix}" . $field_label . "_ini{$sufix}\" value=\"" . (!empty($res) ? $res["ano_".$field_label."_ini"] : (isset($ref->default) ? substr($ref->default[0],0,4) : "")) . "\" size=4 maxlength=4 class=$class $prop_str onfocus=\"recvalue(this);temp=this.value\" onkeyup=\"gotofield(this)\" onchange=\"document.getElementById(this.id.replace('ano_','').replace('_ini','')).checked=this.value!=''?true:false\" " . (!$_ck ? "DISABLED" : "") . "> a \n";
				$str .= "<select name=\"mes_{$prefix}" . $field_label . "_fim{$sufix}\" id=\"mes_{$prefix}" . $field_label . "_fim{$sufix}\" class=$class $prop_str onfocus=\"recvalue(this);temp=this.value\" onkeyup=\"gotofield(this)\" onchange=\"document.getElementById(this.id.replace('mes_','').replace('_fim','')).checked=this.value!=''?true:false\" " . (!$_ck ? "DISABLED" : "") . ">\n";
				$str .= "<option value=\"\">--\n";
				foreach ($meses as $m => $label) {
					$str .= "<option " . ((!empty($res) && $res["mes_".$field_label."_fim"] == $m) || (!empty($ref->default) && date("n",strtotime($ref->default[1])) == $m) ? "SELECTED" : "") . " value=\"$m\">$label\n";
				}
				$str .= "</select>\n";
				$str .= "<input type=text name=\"ano_{$prefix}" . $field_label . "_fim{$sufix}\" id=\"ano_{$prefix}" . $field_label . "_fim{$sufix}\" value=\"" . (!empty($res) ? $res["ano_".$field_label."_fim"] : (isset($ref->default) ? substr($ref->default[1],0,4) : "")) . "\" size=4 maxlength=4 class=$class $prop_str onfocus=\"recvalue(this);temp=this.value\" onkeyup=\"gotofield(this)\" onchange=\"document.getElementById(this.id.replace('ano_','').replace('_fim','')).checked=this.value!=''?true:false\" " . (!$_ck ? "DISABLED" : "") . ">\n";
			}
		} else if ($ref->type == "hour") {
			if ($ck_print == 1 || $this->ck_readonly == 1) {
				$str .= "<span class=\"$this->css_text_print\" id=\"$field_name\">" . floor($res[$field_label]/60) . ":" . str_pad($res[$field_label]%60,2,'0',STR_PAD_LEFT) . "</span>";
			} else if ($this->hour_format == "30m") {
				$str .= "<input type=text name=\"hor_$field_name\" id=\"hor_$field_name\" value=\"" . (!empty($res) ? floor($res[$field_label]/60) : (isset($ref->default) ? floor($ref->default/60) : "")) . "\" size=2 maxlength=2 class=$class $prop_str onfocus=recvalue(this) onkeyup=gotofield(this)>:";
				$str .= "<select name=\"min_$field_name\" id=\"min_$field_name\" class=\"$class\"><option value=0 " . (!empty($res) ? ($res[$field_label]%60 == "0" ? "SELECTED" : "") : (isset($ref->default) && $ref->default%60 == 0 ? "SELECTED" : "")) . ">00<option value=30 " . (!empty($res) ? ($res[$field_label]%60 == 30 ? "SELECTED" : "") : (isset($ref->default) && $ref->default%60 == 30 ? "SELECTED" : "")) . ">30</select>\n";
			} else if ($this->hour_format == "free") {
				$str .= "<input type=text name=\"hor_$field_name\" id=\"hor_$field_name\" value=\"" . (!empty($res) ? floor($res[$field_label]/60) : (isset($ref->default) ? floor($ref->default/60) : "")) . "\" size=2 maxlength=2 class=$class $prop_str onfocus=recvalue(this) onkeyup=gotofield(this)>:";
				$str .= "<input type=text name=\"min_$field_name\" id=\"min_$field_name\" value=\"" . (!empty($res) ? str_pad($res[$field_label]%60,2,'0',STR_PAD_LEFT) : (isset($ref->default) ? str_pad($ref->default%60,2,'0',STR_PAD_LEFT) : "")) . "\" size=2 maxlength=2 class=$class $prop_str onfocus=recvalue(this) onkeyup=gotofield(this)>";
			}
		} else if ($ref->type == "numericrange") {
			if ($ck_print == 1 || $this->ck_readonly == 1) {
				$str .= "<span class=\"$this->css_text_print\" id=\"$field_name\">";
				$str .= ($res[$field_label."_ini"] != "" ? $res[$field_label."_ini"] : ""); 
				$str .= ($res[$field_label."_fim"] != "" ? $res[$field_label."_fim"] : ""); 
				$str .= "</span>";
			} else {
				$str .= "<input type=checkbox name=\"$field_name\" id=\"$field_name\" value=1 onclick=mes_{$prefix}" . $field_label . "_ini{$sufix}.disabled=!this.checked;{$prefix}" . $field_label . "_ini{$sufix}.disabled=!this.checked;mes_{$prefix}" . $field_label . "_fim{$sufix}.disabled=!this.checked;{$prefix}" . $field_label . "_fim{$sufix}.disabled=!this.checked " . ((isset($ref->default) && ($scope != "filter" || count($this->ref_filter) > 0)) || (!empty($res) && $res["mes_".$field_label."_ini"] != "") ? "CHECKED" : "") . " " . ($this->modo == "insert" || ($this->modo == "update" && $this->step == 2) ? "style=display:none" : "")  . ">\n";
				$str .= "<input type=text name=\"{$prefix}{$field_label}_ini{$sufix}\" id=\"{$prefix}{$field_label}_ini{$sufix}\" value=\"" . (!empty($res) ? $res[$field_label."_ini"] : (isset($ref->default) && ($scope != "filter" || count($this->ref_filter) > 0) ? $ref->default["ano_ini"] : "")) . "\" size=4 maxlength=4 class=$class $prop_str onfocus=\"recvalue(this);temp=this.value\" onkeyup=\"gotofield(this)\" onchange=\"document.getElementById(this.id.replace('_ini','')).checked=this.value!=''?true:false\"> a \n";
				$str .= "<input type=text name=\"{$prefix}{$field_label}_fim{$sufix}\" id=\"{$prefix}{$field_label}_fim{$sufix}\" value=\"" . (!empty($res) ? $res[$field_label."_fim"] : (isset($ref->default) && ($scope != "filter" || count($this->ref_filter) > 0) ? $ref->default["ano_fim"] : "")) . "\" size=4 maxlength=4 class=$class $prop_str onfocus=\"recvalue(this);temp=this.value\" onkeyup=\"gotofield(this)\" onchange=\"document.getElementById(this.id.replace('_fim','')).checked=this.value!=''?true:false\">\n";
			}
		} else if ($ref->type == "checkbox") {
			//if (empty($ck_print) && $this->ck_readonly == 0 || $res[$field_label] == 1) 
			$lbl = $ref->label;
			if ($lbl instanceof Closure) $lbl = $lbl($res);
			$str .= "<label>\n";
			$str .= "<input type=checkbox name=\"$field_name\" id=\"$field_name\"" . ((!empty($res) && array_key_exists($field_label, $res) && $res[$field_label] == $ref->cb_value) || (empty($res) && isset($ref->default) && $ref->default) ? " CHECKED" : "") . " value=$ref->cb_value $prop_str " . ($ck_print == 1 ? "disabled" : "") . ">$lbl\n";
			$str .= "</label>\n";
		} else if ($ref->type == "publisher") {
			$str .= "<script type=\"text/javascript\">\n";
			$str .= "_{$field_name} = new Publisher(\"$field_name\");\n";
			$url_dir = "http://" . $_SERVER["HTTP_HOST"] . substr($_SERVER["PHP_SELF"], 0, strrpos($_SERVER["PHP_SELF"], "/")+1) . $ref->publisher_img_dir;
			$str .= "_{$field_name}.setImgDir(\"$url_dir\",\"" . str_replace("\\", "\\\\", realpath($ref->publisher_img_dir)) . "\");\n";
			$str .= "_{$field_name}.setImgAction(\"" . $ref->publisher_img_action . "\");\n";
			if (isset($ref->prop["width"])) $str .= "_{$field_name}.setWidth(\"" . $ref->prop["width"] . "\");\n";
			if (isset($ref->prop["height"])) $str .= "_{$field_name}.setHeight(\"" . $ref->prop["height"] . "\");\n";
			$str .= "_{$field_name}.build();\n";
			$str .= "</script>\n";
			if (!empty($res)) echo "<span id=\"span$field_name\" style=\"display:none\">" . $res[$field_label] . "</span>"; 
			else if (isset($ref->default)) echo "<span id=\"span$field_name\" style=\"display:none\">" . $ref->default . "</span>"; 
		} else if ($ref->type == "fieldfilter") {
			$field = $field_label;
			if (!empty($res) && 
				$ref->ck_qry == 1 &&
				$res[$field] != "" && 
				$res[$field] != "0" &&
				!isset($res["label_$field"])) {
				$val = $res[$field];
				if (!empty($ref->ref_label)) {
					$res["label_$field"] = $val;
				} else if (!empty($ref->ref_qry)) { 
					if (!isset($this->var_bak[$field][$val])) {
						$res_temp = $this->get_ref_query($ref->ref_qry, $res, $debug);
						$this->var_bak[$field][$val] = count($res_temp) > 0 ? $this->parse_label($res_temp[0], $ref->ref_qry_label) : "";
					}
					$res["label_$field"] = $this->var_bak[$field][$val];
				} else if (isset($ref->ajaxqry) && $ref->ajaxqry != null) { 
					$sql = $ref->ajaxqry;
					preg_match_all("/\[([a-zA-Z0-9_])+\]/", $sql, $match);
					for ($i=0; $i<count($match[0]); $i++) {
						$sql = str_replace($match[0][$i], $res[substr($match[0][$i],1,-1)], $sql);
					}
					if ($this->debug == 1) $this->show_debug($sql,"?","relative",$debug);
					$res_ajax = nc_query($sql);
					$res["label_$field"] = $res_ajax[0][$ref->ajaxlabel];
				} else if (isset($ref->ajaxtable)) {
					$sql = "SELECT DISTINCT
								" . $ref->ajaxid . " AS id,
								" . $ref->ajaxlabel . " AS label
							FROM " . $ref->ajaxtable . "
							WHERE 
								" . $ref->ajaxid . " = '$val'";
					if ($this->debug == 1) $this->show_debug($sql,"?","relative",$debug);
					$res_ajax = nc_query($sql);
					$res["label_$field"] = $res_ajax[0]["label"];
				}
			}
			if ($ck_print == 1 || $ck_readonly == 1) {
				$str .= "<span class=\"$this->css_text_print\">" . (isset($res["label_$field"]) ? $res["label_$field"] : "") . "</span>";
				$str .= "<input type=\"hidden\" name=\"{$prefix}{$field}{$sufix}\" id=\"{$prefix}{$field}{$sufix}\" value=\"" . (isset($res[$field])?$res[$field]:"") . "\">\n";
			} else if (empty($ck_print)) {
				$param = "";
				$ckstr = "";
				if (isset($ref->ajaxqry)) {
					$sql = $ref->ajaxqry;
					preg_match_all("[\[([a-zA-Z0-9_])+\]]", $sql, $match);
					foreach ($match[0] as $m) {
						$param .= "&" . substr($m,1,-1) . "=[" . substr($m,1,-1) . "$sufix]";
					}
				}
				if ($ck_readonly == 0 && isset($ref->ajaxfilter) && is_array($ref->ajaxfilter)) {
					for ($i=0; $i<count($ref->ajaxfilter); $i++) {
						if (is_array($ref->ajaxfilter[$i])) {
							$keys = array_keys($ref->ajaxfilter[$i]);
							$filter = $keys[0];
							$label = $ref->ajaxfilter[$i][$keys[0]];
						} else {
							$filter = $ref->ajaxfilter[$i];
							$label = $ref->ajaxfilter[$i];
						}
						$param .= "&ck_$filter=[{$prefix}{$field}_{$filter}{$sufix}]";
						$ckstr .= "<label><input type=checkbox id=\"ck_{$prefix}{$field}_{$filter}{$sufix}\" onclick=document.getElementById(this.id.replace('ck_','')).value=this.checked?1:0 CHECKED>$label</label>\n";
						$ckstr .= "<input type=\"hidden\" name=\"{$prefix}{$field}_{$filter}{$sufix}\" id=\"{$prefix}{$field}_{$filter}{$sufix}\" value=1>\n";
					}
					$ckstr = "filtrar em: $ckstr\n";
				}
				if ($obj == "entity_field" && empty($ref->ajax_path)) {
					$param .= "&prefix=" . substr($prefix,0,-1);
				}
				if (isset($ref->ajax_path)) {
					$url = $ref->ajax_path;
					if ($prefix != "") {
						preg_match_all("[\[([a-zA-Z0-9_])+\]]", $url, $match);
						for ($i=0; $i<count($this->ref); $i++) {
							if ($this->ref[$i]["type"] == "entity" &&
								$this->ref[$i]["object"]->prefix ."_" == $prefix) {
								for ($j=0; $j<count($match[0]); $j++) {
									$f = substr($match[0][$j],1,-1);
									$ent_ref = $this->ref[$i]["field"];
									for ($k=0; $k<count($ent_ref); $k++) {
										if (($ent_ref[$k]["type"] == "date" && $f == "dia_" . $ent_ref[$k]["field"]) ||
											($ent_ref[$k]["type"] == "date" && $f == "mes_" . $ent_ref[$k]["field"]) ||
											($ent_ref[$k]["type"] == "date" && $f == "ano_" . $ent_ref[$k]["field"]) ||
											($ent_ref[$k]["type"] != "date" && $f == $ent_ref[$k]["field"])) {
											$url = str_replace("[$f]", "[$prefix$f$sufix]", $url);
										}
									}
								}
								break;
							}
						}
					}
					if (!empty($param)) $url .= (strpos($url,"?")>0 ? "&" : "?") . $param;
					if ($obj == "entity_field") {
						// $url = preg_replace("/\[([a-z0-9_]+)\]/i", "[$prefix\${1}$sufix]", $ref->ajax_path);
						preg_match_all("/\[([a-z0-9_]+)\]/i", $ref->ajax_path, $match);
						if (isset($match[1])) {
							foreach ($match[1] as $f) {
								for ($i=0; $i<count($entity_ref); $i++) {
									if ($entity_ref[$i]->field == $f && $entity_ref[$i]->type != "hidden") {
										$url = str_replace("[$f]", "[$prefix$f]", $url); // $sufix removed [$prefix$f$sufix] => using setBaseName()
										break;
									}
								}
							}
						}
					}
				} else {
					$url = "$this->document_name_xml?modo=$this->modo&ck_xml=1&field={$field}{$param}&obj=$obj";
					if (!empty($ref->ajax)) {
						foreach ($ref->ajax as $f) {
							$_field = $this->getFieldLbl($f["field"]);
							$_obj = $f["obj"];
							if ($_obj == "entity_field")
								$url .= "&$_field=[" . substr($prefix,0,-1) . "_$_field$sufix]";
							else if ($_obj == "field" || $_obj == "filter")
								$url .= "&$_field=[$_field]";
						}
					}
				}
				$str .= "<span id=\"container$field_name\" clone=\"$sufix\" style=\"display:inline-block;vertical-align:middle;\"></span>\n";
				$str .= "<script type=\"text/javascript\">\n";
				$str .= "<!--- \n";
				//$field_name = $prefix . $field_label . $sufix;
				$return_id = isset($ref->ajax_tags["id"])  ? $ref->ajax_tags["id"] : "id";
				$return_label = isset($ref->ajax_tags["label"]) ? $ref->ajax_tags["label"] : "text";
				if (substr($url,0,8) != "function") $url = "\"$url\"";
				$str .= "loader_$field_name = new fieldFilter(\"container$field_name\", \"$field_name\", $url, \"$return_id\", \"$return_label\");\n";
				if (strlen($sufix) > 0) $str .= "loader_$field_name.setBaseName(\"$prefix$field_label\");\n";
				if ($ck_readonly == 1) {
					$str .= "loader_$field_name.setReadOnly();\n";
				} else {
					if (!empty($ref->ajax_action)) {
						foreach ($ref->ajax_action as $r) {
							$action = $r["action"];
							if (isset($r["add_sufix"]) && $r["add_sufix"] == true) $action = str_replace("[]",$sufix,$action);
							if (substr($action,0,8) != "function") $action = "function(p,r) { $action }";
							$cmd = $r["modo"] == "R" ? "addResetAction" : "addAction";
							$str .= "loader_$field_name.$cmd($action);\n";
						}
					}
					if (!empty($ref->ajax_xtrafield)) {
						foreach ($ref->ajax_xtrafield as $r) {
							$f = $prefix . $r["field"];
							$xml_label = $r["xml_label"];
							if ($ref->scope == "entity_field")
								$dst = $this->get_field_def($r["field"], $ref->entity);
							else
								$dst = $this->get_field_def($r["field"]);
							// if (empty($dst) && !empty($this->debug)) echo "get_field_def(" . $r["field"] . ") requested by $ref->field returned false<br>";
							$reset = false;
							if ($f != $xml_label && substr($xml_label,0,8) == "function") {
								$str .= "loader_$field_name.addField('$f', $xml_label);\n";
							} else if (!empty($dst->mask)) {
								$mask = json_encode(array_values(array_filter($dst->mask,'is_numeric')));
								if ($ref->scope == "entity_field") {
									$str .= "loader_$field_name.addAction(function(p,r) { var mask = $mask, len = 0; for (var i=0; i<mask.length; i++) { document.formulario['" . $ref->entity->prefix . "_$f'+i+p].value = getNodeValue(r,'$xml_label').substr(len,mask[i]); len += mask[i]; } });\n";
									$str .= "loader_$field_name.addResetAction(function(p) { for (var i=0; i<$c; i++) document.formulario['" . $ref->entity->prefix . "_$f'+i+p].value = ''; });\n";
								} else {
									$str .= "loader_$field_name.addAction(function(p,r) { var mask = $mask, len = 0; for (var i=0; i<mask.length; i++) { document.formulario['$f'+i].value = getNodeValue(r,'$xml_label').substr(len,mask[i]); len += mask[i]; } });\n";
									$str .= "loader_$field_name.addResetAction(function() { var mask = $mask; for (var i=0; i<mask.length; i++) document.formulario['$f'+i].value = ''; });\n";
								}
								$reset = true;
							} else if (!empty($dst) && $dst->type == "date" && $this->pref_field_date == "text") {
								foreach (["dia","mes","ano"] as $c => $part) {
									$str .= "loader_$field_name.addField('{$part}_$f', function(r) { return getNodeValue(r,'$xml_label') ? getNodeValue(r,'$xml_label').split('/')[$c] : ''; });\n";
								}
								if ($ref->scope == "entity_field")
									$str .= "loader_$field_name.addResetAction(function(p) { for (var f of ['dia','mes','ano']) document.formulario['" . $ref->entity->prefix . "_' + f + '_$f'+p].value = ''; });\n";
								else
									$str .= "loader_$field_name.addResetAction(function() { for (var f of ['dia','mes','ano']) document.formulario[f + '_$f'].value = ''; });\n";
								$reset = true;
							} else if ($f != $xml_label) {
								$str .= "loader_$field_name.addField('$f', '$xml_label');\n";
							} else {
								$str .= "loader_$field_name.addField('$f');\n";
							}
							if (!$reset) {
								if ($ref->scope == "entity_field") {
									$str .= "loader_$field_name.addResetAction(function(p,r) { document.formulario['$f'+p].value = ''; });\n";
								} else {
									$str .= "loader_$field_name.addResetAction(function() { document.formulario.$f.value = ''; });\n";
								}
							}
						}
					}
				}
				if ($ref->ck_qry == 1 && !empty($res) && !empty($res[$field]))
					$str .= "loader_$field_name.setDefault('" . str_replace("'","\'",$res[$field]) . "', '" . str_replace("'","\'", isset($res["label_$field"]) ? $res["label_$field"] : $res[$field]) . "');\n";
				else if (isset($ref->default) && $this->modo == "insert")
					$str .= "loader_$field_name.setDefault('" . str_replace("'","\'",$ref->default[0]) . "', '" . str_replace("'","\'",$ref->default[1]) . "');\n";
				if (isset($ref->ajax_group) && $ref->ajax_group != "")
					$str .= "loader_$field_name.setGroup('".$ref->ajax_group."');\n";
				if (isset($ref->ajax_limit))
					$str .= "loader_$field_name.setKeywordLimit('".$ref->ajax_limit."');\n";
				if (!empty($_prop["style"])) {
					$str .= "loader_$field_name.build({ style: '" . $_prop["style"] . "' });\n";
				} else if (!empty($_prop["size"])) {
					/* Depracated 13/05/2021
					$size = $_prop["size"];
					if (is_array($size))
						$str .= "loader_$field_name.build(".$size[0].",".$size[1].");\n";
					else */
					$str .= "loader_$field_name.build(" . $_prop["size"] . ");\n";
				} else { 
					$str .= "loader_$field_name.build(30);\n";
				}
				//$str .= "fn_buildfilteredtext(\"$field_name\",\"$url\",".(isset($size)?$size:30).",1," . (!empty($res) && $res[$field] != "" && $res[$field] != "0" ? "new Array('" . $res[$field] . "','" . str_replace("'","\'",$res["label_$field"]) . "')" : "false") . ",".$ref->ajax_action.",false," . (isset($ref->prop["readonly"])?"true":"false") . ")\n";
				$str .= "// --->\n";
				$str .= "</script>\n";
				if ($ckstr != "") $str .= $ckstr;
			}
		} else if ($ref->type == "fieldlist") {
			$str .= "<div id=\"container_$field_name\"></div>\n";
			$str .= "<script type=\"text/javascript\">\n";
			$str .= "_{$field_name} = new fieldList(\"$field_name\",\"container_$field_name\");\n";
			$str .= "_{$field_name}.reqKeyword(0);\n";
			if (isset($ref->new_entry)) {
				$url = "?modo=$this->modo&ck_xml=1&obj=insert&tbl=".$ref->new_entry["table"]."&field=".$ref->new_entry["field"];
				if (isset($ref->new_entry["xtra_fields"])) {
					foreach ($ref->new_entry["xtra_fields"] as $ix => $val) {
						$url .= "&f:$ix=$val";
					}
				}
				$str .= "_{$field_name}.setSaveNotFound('".$ref->new_entry["label"]."','$url',1);\n";
			}
			if (isset($ref->qry)) {
				$url = "$this->document_name_xml?modo=$this->modo&ck_xml=1&obj=$obj&field=$field_label";
				if ($obj == "entity_field") $url .= "&prefix=" . substr($prefix,0,-1);
				preg_match_all("[\[([a-zA-Z0-9_])+\]]", $ref->qry, $match);
				foreach ($match[0] as $ix) {
					$ix = substr($ix,1,-1);
					if ($ix != $this->keyword) $url .= "&$ix=[$ix]";
				}
				$str .= "_{$field_name}.setFilter(\"$url\");\n";
			}
			$str .= "_{$field_name}.build();\n";
			if (!empty($res)) {
				$str_xtra_keys = "";
				if (isset($ref->rec_table["xtra_key"])) {
					foreach ($ref->rec_table["xtra_key"] as $ix => $val) {
						$str_xtra_keys .= "$ix = '$val' AND\n";
					}
				}
				$tbl = $ref->rec_table["table"];
				if (isset($ref->rec_table["label"]) && isset($ref->rec_table["label_table"]))
					$field_display = "tbl_label." . $ref->rec_table["label"];
				else if (isset($ref->rec_table["label"]))
					$field_display = "$tbl." . $ref->rec_table["label"];
				else
					$field_display = "$tbl." . $field_label;
				$qry = "SELECT $tbl.$field_label AS ix, $field_display AS val
					FROM $tbl
					" . (isset($ref->rec_table["label_table"]) ? "
					INNER JOIN " . $ref->rec_table["label_table"] . " tbl_label ON tbl_label." . $ref->rec_table["label_key"] . " = $tbl.$field_label
					" : "") . "
					WHERE
						$str_xtra_keys
						$tbl." . $ref->rec_table["field"] . " = " . $res["id"] . "
					ORDER BY val";
				$res_dynlist = nc_query($qry);
				if ($this->debug == 1) $this->show_debug($qry,"?","relative",$debug);
				for ($i=0; $i<count($res_dynlist); $i++) {
					$str .= "_{$field_name}.addItem(\"" . $res_dynlist[$i]["ix"] . "\",\"" . $res_dynlist[$i]["val"] . "\");\n";
				}
			}
			$str .= "</script>\n";
		} else if ($ref->type == "color") {
			$str .= "<script type=\"text/javascript\">fn_BuildColorTable(\"$field_name\",\"" . (!empty($res) ? $res[$field_label] : "gray") . "\")</script>\n";
		} else if ($ref->type == "display") {
			//$str .= "<input type=\"hidden\" name=\"$field_name\" value=\"" . $res[$field_label] . "\">";
			$str .= "<span " . ($ck_print == 1 ? "class=\"$this->css_text_print\"" : "") . " id=\"$field_name\">";
			if (array_key_exists($field_label, $res)) {
				$val = $res[$field_label];
				if ($val != null) {
					if (!empty($ref->parser)) {
						$str .= ($ref->parser)($val,$res);
					} else {
						if ($val instanceof DateTime) {
							if (strtotime($val->format("Y-m-d H:i")) != strtotime($val->format("Y-m-d")))
								$str .= $val->format("d/m/Y H:i");
							else
								$str .= $val->format("d/m/Y");
						} else {
							$str .= nl2br((string)$val);
						}
					}
				}
			} else if (isset($ref->default)) {
				$str .= nl2br($ref->default);
			}
			$str .= "</span>";
		} else if ($ref->type == "radio" || $ref->type == "radio_checkbox") {
			if ($ref->type == "radio_checkbox" || $ck_readonly == 1) {
				$val = (!empty($res) ? $res[$field_label] : $this->get_default($ref));
				if (is_array($val)) $val = implode(",",$val);
				$str .= "<input type=\"hidden\" name=\"$field_name\" id=\"$field_name\" value=\"$val\">\n";
			}
			if (isset($ref->qry["sql"])) {
				$sql = $ref->qry["sql"];
				$ix = $ref->qry["ix"];
				$label = $ref->qry["label"];
				$res_aux = nc_query($sql);
				$list = [];
				foreach ($res_aux as $r) {
					$list[$r[$ix]] = $r[$label];
				}
			} else if (isset($ref->list)) {
				$list = $ref->list["vals"];
			}
			if (!empty($res) && array_key_exists($field_label, $res) && strlen(isnull($res[$field_label],"")) != 0)
				$val = $res[$field_label];
			else if (empty($res) && isset($ref->default))
				$val = $ref->default;
			else
				$val = "";
			$j = 0;
			foreach ($list as $ix => $label) {
				$str .= "<label $label_prop_str>\n";
				if (empty($ck_print)) {
					$sit_str = "";
					if (!is_array($val) && strval($val) == strval($ix))
						$sit_str .= " CHECKED";
					else if (is_array($val) && in_array(strval($ix),$val))
						$sit_str .= " CHECKED";
					if ($ck_readonly == 1)
						$sit_str .= " DISABLED";
					if ($ref->type == "radio") {
						$str .= "<input type=radio name=\"$field_name\" id=\"$field_name\" value=\"$ix\" $sit_str $prop_str>";
					} else if ($ref->type == "radio_checkbox") {
						$f = $prefix . $field_label;
						$params = "field:'$f'";
						$f .= chr(ord("A")+$j);
						if ($this->builder == "list" && !empty($res)) $default = $params .= ", default: '" . $res[$field_label] . "'";
						if ($sufix !== "") // !empty() dont apply to $sufix = 0
							$params .= ", suffix: '$sufix'";
						else if ($obj == "entity_field")
							$params .= ", suffix: this.id.replace('$f','')";
						if ($scope == "filter" && count($list) != 2)
							$params .= ", multiple: true";
						$prop_str_cb = "setRadioCb(this,{ $params })";
						if (strpos($prop_str, "onclick") === false)
							$prop_str_cb = "$prop_str onclick=\"$prop_str_cb\"";
						else
							$prop_str_cb = str_replace("onclick=\"", " onclick=\"$prop_str_cb;", $prop_str);
						$str .= "<input type=checkbox name=\"$f$sufix\" id=\"$f$sufix\" value=\"$ix\" $sit_str $prop_str_cb>";
					}
					if (is_array($label)) {
						$opt_field = $label["object"];
						$str .= $opt_field->label;
						$str .= "</label>";
						if ($opt_field->type != null) {
							if ($opt_field->label != "")
								$str .= ": ";
							if ($this->modo == "update")
								$str .= $this->get_field(["scope" => $scope, "ref" => $opt_field, "res" => $res]);
							else
								$str .= $this->get_field(["scope" => $scope, "ref" => $opt_field]);
							if (isset($opt_field->comment))
								$str .= $opt_field->comment;
						}
						if ($j < count($ref->list["vals"])-1) $str .= "<br>\n";
					} else {
						$str .= $label;
						$str .= "</label>\n";
						if (isset($ref->list) && $ref->list["ck_break"] && $j < count($ref->list["vals"])-1) $str .= "<br>\n";
					}
				} else if (!empty($res) && $res[$field_label] == $ix) {
					$str .= $label;
					$str .= "</label>\n";
				}
				$str .= "\n";
				$j++;
			}
		} else if ($ref->type == "dropdown") {
			if (!empty($ck_print)) $str .= "<span class=\"$this->css_text_print\" id=\"$field_name\">";
			if (empty($ck_print) && $ref->ck_readonly != 1) $str .= "<select name=\"{$field_name}" . (array_key_exists("multiple",$ref->prop)?"[]":"") . "\" id=\"$field_name\" $prop_str class=$class>\n";
			// multiple
			if (!empty($res) && array_key_exists("multiple", $ref->prop)) {
				if (isset($ref->rec_table)) {
					$qry = "SELECT " . $field_label . " AS val
						FROM " . $ref->rec_table["table"] . " 
						WHERE " . $ref->rec_table["field"] . " = " . $res["id"];
					if (is_array($ref->rec_table["xtra_key"])) {
						$keys = array_keys($ref->rec_table["xtra_key"]);
						for ($i=0; $i<count($ref->rec_table["xtra_key"]); $i++) {
							$qry .= " AND " . $keys[$i] . " = '" . $ref->rec_table["xtra_key"][$keys[$i]] . "'";
						}
					}
					if ($this->debug == 1) $this->show_debug($qry,"?","relative",$debug);
					$res_multiple = nc_query($qry);
					$multiple_ref = [];
					for ($i=0; $i<count($res_multiple); $i++) {
						array_push($multiple_ref, $res_multiple[$i]["val"]);
					}
				} else if (!empty($ref->list)) {
					$multiple_ref = explode(",", $res[$field_label]);
				}
			} 
			// qry
			$ck_sel = 0;
			if (($ck_print == 1 || !empty($ref->ck_readonly)) && !empty($ref->ref_qry)) {
				$res_temp = $this->get_ref_query($ref->ref_qry, $res, $debug);
				preg_match_all("/\[([a-zA-Z0-9_])+\]/", $ref->ref_qry_label, $match);
				if (count($match[0]) > 0) {
					$_str = $ref->ref_qry_label;
					foreach ($match[0] as $f) {
						$_str = str_replace($f, $res_temp[0][substr($f,1,-1)], $_str);
					}
					$str .= "$_str\n";
				} else if (!empty($res_temp)) {
					$str .= $res_temp[0][$ref->ref_qry_label] . "\n";
				}
				$str .= "<input type=hidden name=\"$field_name\" id=\"$field_name\" value=\"" . $res[$field_label] . "\">\n";
			} else if (isset($ref->qry["sql"])) {
				$sql = $ref->qry["sql"];
				$ix = $ref->qry["ix"];
				$label = $ref->qry["label"];
				$group_label = $ref->qry["group_label"];
				if (!empty($ref->ajax)) {
					$ck_ajax = 0;
					preg_match_all("/\[([a-zA-Z0-9_])+\]/", $sql, $match);
					/* if (!empty($_SESSION["verpro_debug"])) {
						echo "<pre>"; print_r($match[0]); echo "</pre><hr>\n";
						echo "<pre>"; print_r($ref->ajax); echo "</pre><hr>\n";
					} */
					foreach ($ref->ajax as $_dst) { // dst
						if ($_dst["obj"] == "field" && $scope == "filter" && !empty($this->ref_filter)) {
							$_src = $this->ref_filter;
						} else if ($_dst["obj"] == "field" || $_dst["obj"] == "filter") {
							$_src = $this->ref;
						} else if ($_dst["obj"] == "entity_field") {
							$_src = $entity_ref;
						}
						$f = $_dst["field"];
						foreach ($_src as $_obj) {
							if (gettype($_obj) == "array") $_obj = $_obj["object"];
							if (!is_array($_obj->field) &&
								strtolower($f) == strtolower($this->getFieldLbl($_obj->field))) {
								if (strtolower($f) != strtolower($ref->field)) $label_origem = $_obj->label;
								if (!empty($res) && array_key_exists($_obj->field, $res))
									$sql = str_replace("[$f]", (string)$res[$_obj->field], $sql);
								else if (!empty($this->res_upd) && array_key_exists($_obj->field, $this->res_upd))
									$sql = str_replace("[$f]", (string)$this->res_upd[$_obj->field], $sql);
								else if (isset($_obj->default))
									$sql = str_replace("[$f]", $_obj->default, $sql);
								$ck_ajax = 1;
							} else if (isset($_obj->field_qry) &&
								strtolower($f) == strtolower($this->getFieldLbl($_obj->field_qry))) {
								$label_origem = $_obj->label;
								$sql = str_replace("[$f]", $res[$_obj->field_qry], $sql);
								$ck_ajax = 1;
							}							
						}
					}
					// Essa linha talvez não seja necessaria, se o registro não consta no record set já é incluido automaticamente
					if (!empty($res[$this->getFieldLbl($ref->field)])) $sql = str_replace("[$ref->field]", $res[$this->getFieldLbl($ref->field)], $sql);
					/* Replaced by previous loop 20/05/2024
					foreach ($match[0] as $_dst) { // dst
						if ($obj == "entity_field") {
							foreach ($entity_ref as $_obj) { // src
								if (!is_array($_obj->field) &&
									strtolower(substr($_dst,1,-1)) == strtolower($this->getFieldLbl($_obj->field))) {
									if (strtolower(substr($_dst,1,-1)) != strtolower($ref->field)) $label_origem = $_obj->label;
									if (!empty($res) && array_key_exists($_obj->field, $res))
										$sql = str_replace($_dst, $res[$_obj->field], $sql);
									else if (!empty($this->res_upd) && array_key_exists($_obj->field, $this->res_upd))
										$sql = str_replace($_dst, (string)$this->res_upd[$_obj->field], $sql);
									else if (isset($_obj->default))
										$sql = str_replace($_dst, $_obj->default, $sql);
									$ck_ajax = 1;
								} else if (isset($_obj->field_qry) &&
									strtolower(substr($_dst,1,-1)) == strtolower($this->getFieldLbl($_obj->field_qry))) {
									$label_origem = $_obj->label;
									$sql = str_replace($_dst, $res[$_obj->field_qry], $sql);
									$ck_ajax = 1;
								}
							}
						}
						foreach ($scope == "filter" && count($this->ref_filter) > 0 ? $this->ref_filter : $this->ref as $_src) { // src
							$_obj = $_src["object"];
							if (!is_array($_obj->field) &&
								strtolower(substr($_dst,1,-1)) == strtolower($this->getFieldLbl($_obj->field))) {
								if (strtolower(substr($_dst,1,-1)) != strtolower($ref->field)) $label_origem = $_obj->label;
								if (!empty($res) && array_key_exists($_obj->field, $res))
									$sql = str_replace($_dst, isnull($res[$_obj->field],""), $sql);
								else if (!empty($this->res_upd) && array_key_exists($_obj->field, $this->res_upd) && $this->res_upd[$_obj->field] != null)
									$sql = str_replace($_dst, isnull($this->res_upd[$_obj->field],""), $sql);
								else if (!empty($this->res_upd) && array_key_exists($_obj->field, $this->res_upd))
									$sql = str_replace($_dst, "", $sql);
								else if (isset($_obj->default))
									$sql = str_replace($_dst, $_obj->default, $sql);
								$ck_ajax = 1;
							} else if (isset($_obj->field_qry) &&
								strtolower(substr($_dst,1,-1)) == strtolower($this->getFieldLbl($_obj->field_qry))) {
								$label_origem = $_obj->label;
								$sql = str_replace($_dst, $res[$_obj->field_qry], $sql);
								$ck_ajax = 1;
							}
						}
					} */
					preg_match_all("/\[([a-zA-Z0-9_])+\]/", $sql, $match);
					if (!empty($this->ck_print) ||
						($ref->scope == "entity_field" && $ref->get_ajax_scope() == "field" && $this->modo == "update") ||
						(empty($match[0]) && (empty($ref->ajax_xtrafield) || !empty($this->ck_print)) /* depracated 15/06/2023 && strpos($sql, "AS level") === false */)) {
						if ($this->debug == 1) $this->show_debug($sql,"?","relative",$debug);
						$res_aux = nc_query($sql);
					} else {
						$res_aux = [];
					}
				} else {
					preg_match_all("/\[([a-zA-Z0-9_])+\]/", $sql, $match);
					foreach ($match[0] as $m) {
						if ($this->modo == "insert" || $this->modo == "complete") {
							$sql = str_replace($m, 0, $sql);
						} else if (isset($this->res_upd)) {
							foreach ($this->res_upd as $_ix => $_val) {
								if (strtolower(substr($m,1,-1)) == strtolower($_ix)) {
									$sql = str_replace($m, $_val, $sql);
								}
							}
						}
					}
					if (!empty($res) && isset($ref->qry["pk"])) {
						preg_match("/WHERE(.*)(GROUP BY|ORDER BY)/s", $sql, $match);
						if (count($match) == 0) preg_match("/WHERE(.*)/s", $sql, $match);
						if (count($match) > 0) {
							$sql = str_replace(trim($match[1]), "(" . trim($match[1]) . ") OR " .  $ref->qry["pk"] . " = '" . $res[$field_label] . "'", $sql);
						}
					}
					if (isset($this->ck_res_aux[$sql])) {
						$res_aux = $this->ck_res_aux[$sql];
					} else {
						if ($this->debug == 1) $this->show_debug($sql,"?","relative",$debug);
						$res_aux = nc_query($sql);
						$this->ck_res_aux[$sql] = $res_aux;
					}
				}
				$this->log_res[$this->getFieldLbl($ref->field)] = $res_aux;
				if (empty($ck_print) && $ref->ck_readonly != 1 && 
					(!isset($ref->ajax) || $ref->ajax == false || count($res_aux) > 0) && // no dependency option written
					($ref->ck_req == 0 || $ref->ajax == false || count($res_aux) > 1) &&
					!array_key_exists("size", $ref->prop) && 
					!array_key_exists("multiple", $ref->prop)) {
					$str .= "<option value=\"\">" . (isset($ref->default_label) ? $ref->default_label : "--$this->lang_form_dropdown_choose--") . "</option>\n";
				} else if (empty($ck_print) && $ref->ck_readonly != 1 && 
					(isset($ref->ajax) && $ref->ajax != false && count($res_aux) == 0)) {
					$str .= "<option value=\"\">--$this->lang_form_dropdown_choose " . $ref->ajax[0]["label"] . "--</option>\n";
				}
				for ($j=0; $j<count($res_aux); $j++) {
					if ($group_label != null && 
						($j == 0 ||
						 $res_aux[$j][$group_label] != $res_aux[$j-1][$group_label])) {
						if (empty($ck_print) && $ref->ck_readonly != 1) $str .= "<optgroup label=\"" . $res_aux[$j][$group_label] . "\">\n";
					}
					if ($ck_print == 1 || $ref->ck_readonly == 1) {
						if (( array_key_exists("multiple", $ref->prop) && in_array($res_aux[$j][$ix], $multiple_ref)) ||
							(!array_key_exists("multiple", $ref->prop) && $res[$field_label] == $res_aux[$j][$ix])) {
							if ($label instanceof Closure) {
								$str .= $label($res_aux[$j]);
							} else {
								preg_match_all("/\[([a-zA-Z0-9_])+\]/", $label, $match);
								if (count($match[0]) > 0) {
									$_str = $label;
									foreach ($match[0] as $f) {
										$_str = str_replace($f, $res_aux[$j][substr($f,1,-1)], $_str);
									}
									$str .= "$_str\n";
								} else {
									$str .= $res_aux[$j][$label] . "\n";
								}
								if (array_key_exists("multiple", $ref->prop)) $str .= "<br>\n";
							}
						}
					}
					if (empty($ck_print) && $ref->ck_readonly != 1) {
						$xtra_fields = "";
						if (is_array($ref->qry["xtra_fields"])) {
							for ($k=0; $k<count($ref->qry["xtra_fields"]); $k++) {
								$xtra_fields = $ref->qry["xtra_fields"][$k] . "='" . $res_aux[$j]["xtra_fields"][$k] . "'";
							}
						} else if ($ref->qry["xtra_fields"] != "") {
							$xtra_fields = $ref->qry["xtra_fields"] . "='" . $res_aux[$j][$ref->qry["xtra_fields"]] . "'";
						}
						if (!empty($res) && 
							array_key_exists("multiple", $ref->prop) && 
							in_array($res_aux[$j][$ix], $multiple_ref)) {
							$sel = 1;
						} else if (!empty($res) && 
							!array_key_exists("multiple", $ref->prop) && 
							!is_array($ref->field) &&
							array_key_exists($field_label, $res) &&
							$res[$field_label] == $res_aux[$j][$ix]) {
							$sel = 1;
						} else if (!empty($res) && 
							!array_key_exists("multiple", $ref->prop) && 
							is_array($ref->field)) {
							$keys = array_keys($ref->field);
							$c = 0;
							for ($k=0; $k<count($keys); $k++) {
								if ($res[$ref->field[$k]] == $res_aux[$j][$ref->field[$k]]) $c++;
							}
							$sel = $c == count($ref->field) ? 1 : 0;
						} else if (empty($res) && 
							isset($ref->default) &&
							$ref->default == $res_aux[$j][$ix]) {
							$sel = 1;
						} else {
							$sel = 0;
						}
						if (is_array($ix)) {
							$val = "";
							foreach ($ix as $_label) {
								$val .= ($val != "" ? ";" : "") . $res_aux[$j][$_label];
							}
						} else {
							$val = $res_aux[$j][$ix];
						}
						if ($label instanceof Closure) {
							$text = $label($res_aux[$j]);
						} else {
							preg_match_all("[\[([a-zA-Z0-9_])+\]]", $label, $match);
							if (count($match[0]) > 0) {
								$text = $label;
								foreach ($match[0] as $m) $text = str_replace($m, $res_aux[$j][substr($m,1,-1)], $text);
							} else {
								$text = $res_aux[$j][$label];
							}
						}
						if ($sel == 1) {
							$str .= "<option $xtra_fields SELECTED value=\"$val\">$text</option>\n";
							$ck_sel = 1;
						} else {
							$str .= "<option $xtra_fields value=\"$val\">$text</option>\n";
						}
					}
					if ($group_label != null && 
						($j == count($res_aux)-1 ||
						 $res_aux[$j][$group_label] != $res_aux[$j+1][$group_label])) {
						if (empty($ck_print) && $ref->ck_readonly != 1) $str .= "</optgroup>\n";
					}
				}
				if (isset($ref->new_entry)) {
					$str .= "<option value=\"NEW\">$this->lang_other:</option>\n";
				}
			} else if (isset($ref->list)) { // list
				$list = $ref->list["vals"];
				if ($list instanceof Closure && !empty($res)) {
					$list = $list($res[$ref->ajax[0]["field"]]);
					if (!is_array($list)) $list = [];
				}
				if (empty($ck_print) && $ref->ck_readonly != 1 &&
					!array_key_exists("size", $ref->prop) && 
					!array_key_exists("multiple", $ref->prop)) {
					if ($list instanceof Closure)
						$str .= "<option value=\"\">--$this->lang_form_dropdown_choose " . $ref->ajax[0]["label"] . " --</option>\n";
					else if (
						($ref->ck_req == 0 || !isset($ref->default)) && 
						($ref->ck_req == 0 || isset($ref->prop["onchange"]) || count($list) > 1))
						$str .= "<option value=\"\">" . (isset($ref->default_label) ? $ref->default_label : "--$this->lang_form_dropdown_choose--") . "</option>\n";
				}
				// print_r($list); echo "<hr>$ck_sel<hr>"; print_r($res); echo "<hr>";
				if (empty($ck_print) && $ref->ck_readonly != 1) {
					$ck_sel = !array_key_exists("multiple", $ref->prop) && !empty($res[$field_label]) ? 1 : 0;
					$ck_opt = 0;
					foreach ($list as $ix => $val) {
						if (empty($ck_print) && $ref->ck_readonly != 1) {
							if ((!empty($res) && array_key_exists("multiple", $ref->prop) && in_array($ix, $multiple_ref)) ||
								(!empty($res) && !array_key_exists("multiple", $ref->prop) && $res[$field_label] == $ix) ||
								(empty($res) && isset($ref->default) && $ref->default === $ix)) {
								$ck_sel = 1;
								$ck_opt = 1;
								$str .= "<option SELECTED value=\"$ix\">$val\n";
							} else {
								$str .= "<option value=\"$ix\">$val\n";
							}
						}
					}
					if ($ck_opt == 0 && $ck_sel == 1) {
						$ix = $val = $res[$field_label];
						$str .= "<option SELECTED value=\"$ix\">$val\n";
					}
					if (isset($ref->new_entry)) {
						$str .= "<option value=\"NEW\">$this->lang_other:</option>\n";
					}
				} else {
					foreach ($list as $ix => $val) {
						if (!empty($res) && $res[$field_label] == $ix) {
							$str .= $val . "\n";
							$str .= "<input type=hidden name=\"$field_name\" id=\"$field_name\" value=\"$ix\">\n";
						}
					}
				}
				$this->log_res[$this->getFieldLbl($ref->field)] = $list;
			} else if (isset($ref->ajax_path)) {
				$ck_ajax = 1;
				preg_match_all("[\[([a-zA-Z0-9_])+\]]", $ref->ajax_path, $match);
				if ($scope == "form" || count($this->ref_filter) == 0)
					$ck_ref = ($obj == "entity_field" ? $entity_ref : $this->ref);
				else if ($scope == "filter")
					$ck_ref = $this->ref_filter;
				for ($i=0; $i<count($match[0]); $i++) {
					for ($j=0; $j<count($ck_ref); $j++) {
						$_obj = gettype($ck_ref[$j]) == "array" ? $ck_ref[$j]["object"] : $ck_ref[$j];
						$_found = substr($match[0][$i],1,-1);
						if (!is_array($_obj->field) &&
							strtolower($_found) == strtolower($this->getFieldLbl($_obj->field))) {
							if ($obj == "entity_field") $ref->ajax_path = str_replace("[$_found]", "[$prefix$_found]" , $ref->ajax_path);
							$str .= "<option value=\"\">--$this->lang_form_dropdown_choose " . $_obj->label . "--</option>\n";
							break;
						}
					}
				}
			}
			if (empty($ck_print) && $ref->ck_readonly != 1) {
				// selection not found
				if ((!empty($res) && (empty($ck_ajax) || !isset($sql) || strpos($sql, "AS level") === false)) && 
					!is_array($ref->field) && array_key_exists($field_label, $res) && $res[$field_label] != "" && 
					 $ck_sel == 0) {
					$val = array_key_exists($field_label, $res) ? $res[$field_label] : "";
					if (!empty($ref->ref_qry)) {
						$sql = $ref->ref_qry;
						preg_match_all("/\[([a-zA-Z0-9_])+\]/", $sql, $match);
						$ix = "";
						foreach ($match[0] as $m) {
							$sql = str_replace($m, $res[substr($m,1,-1)], $sql);
							$ix .= $res[substr($m,1,-1)];
						}
						if (!isset($this->var_bak[$field_label][$ix])) {
							if ($this->debug == 1) $this->show_debug($sql,"?","relative",$debug);
							$res_temp = nc_query($sql);
							$this->var_bak[$field_label][$ix] = count($res_temp) > 0 ? $this->parse_label($res_temp[0], $ref->ref_qry_label) : "";
						}
						$str .= "<option SELECTED value=\"$val\">" . $this->var_bak[$field_label][$ix] . "</option>\n";
					} else if (!empty($ref->ref_label)) {
						$str .= "<option SELECTED value=\"$val\">" . $res["label_" . $field_label] . "</option>\n";
					} else if (!array_key_exists("multiple", $ref->prop)) {
						$str .= "<option SELECTED value=\"$val\">$val</option>\n";
					}
				}
				$str .= "</select>\n";
				$ck_ajax = 0;
				if (empty($ck_print) && $ref->ck_readonly != 1) {
					if (!empty($ref->ajax)) {
						$url = "?modo=$this->modo&ck_xml=1&field=" . $this->getFieldLbl($ref->field) . "&obj=$obj" . ($obj == "entity_field" ? "&prefix=" . substr($prefix,0,-1) : "");
						$qstr = preg_replace("/(modo|step|id|urlKey|_session_id)=([a-z0-9]+)(\&)*/i","",$_SERVER["QUERY_STRING"]);
						if (!empty($qstr)) $url .= "&$qstr";
						foreach ($ref->ajax as $f) {
							$_field = $this->getFieldLbl($f["field"]);
							$_src = $f["obj"];
							if ($_src == "entity_field")
								$url .= "&$_field=[" . substr($prefix,0,-1) . "_$_field$sufix]";
							else if ($_src == "field" || $_src == "filter")
								$url .= "&$_field=[$_field]";
							else if ($_src == "label") 
								$url .= "&$_field=[$_field$sufix]";
						}
						$ck_ajax = 1;
					}
					if (!empty($ref->ajax_path)) {
						if ($obj == "entity_field")
							$url = preg_replace("/\[([a-z0-9_]+)\]/i", "[\${1}$sufix]", $ref->ajax_path);
						else
							$url = $ref->ajax_path;
						$ck_ajax = 1;
					} 
				}
				// if ($ck_ajax == 1 && ($scope != "entity_field" || !array_key_exists($field_label, $res))) { // Depracated 07/05/2024, replaced by next line
				if ($ck_ajax == 1 && ($ref->scope != "entity_field" || $sufix === "")) {
					$str .= "<script type=\"text/javascript\">\n";
					$str .= "<!--- \n";
					/* Depracated 07/05/2024, replaced by next block
					if ($_src == "field" && $prefix != "") {
						$src_field  = "function() {\n";
						$src_field .= "	var ref = [\"$field_name\"]; var c = 0;\n";
						if ($modo == "insert") {
							$src_field .= "	while (document.getElementById(\"$field_name\"+c)) {\n";
							$src_field .= "		ref.push(\"$field_name\"+c); c++;\n";
							$src_field .= "	}\n";
						}
						$src_field .= "	return ref;\n";
						$src_field .= "}";
					} else {
						$src_field  = "\"$field_name\"";
					} */
					if ($ref->scope == "entity_field" && $ref->get_ajax_scope() == "field") { // if entity_field triggered by field on form body
						$src_field  = "function() {\n";
						$src_field .= "	var f = \"$field_name\", ref = [f], c = 0;\n";
						$src_field .= "	while (document.formulario[f+c]) {\n";
						$src_field .= "		ref.push(f+c); c++;\n";
						$src_field .= "	}\n";
						$src_field .= "	return ref;\n";
						$src_field .= "}";
					} else {
						$src_field  = "\"$field_name\"";
					}
					$return_id = !empty($ref->ajax_tags) ? $ref->ajax_tags["id"] : "id";
					$return_label = !empty($ref->ajax_tags) ? $ref->ajax_tags["label"] : "text";
					$str .= "loader_$field_name = new dropdownLoader($src_field, \"$url\", \"$return_id\", \"$return_label\");\n";
					if (isset($ref->qry) && !empty($ref->qry["group_label"])) {
						$grpLbl = $ref->qry["group_label"];
						if (substr($grpLbl,0,8) != "function") $grpLbl = "'$grpLbl'";
						$str .= "loader_$field_name.setGroup($grpLbl);\n";
					}
					if (isset($ref->ajax_group) && $ref->ajax_group != "") $str .= "loader_$field_name.setGroup('".$ref->ajax_group."');\n";
					if ($modo == "insert" && isset($ref->default)) 
						$str .= "loader_$field_name.setDefault('".$ref->default."');\n";
					if ($modo == "update" && !array_key_exists("multiple", $ref->prop) && array_key_exists($field_label, $res) && $res[$field_label] != "") {
						if (!empty($ref->ref_label))
							$str .= "loader_$field_name.selectDefault('".$res[$field_label]."','".str_replace("'","\\'",$res["label_$field_label"])."');\n";
						else
							$str .= "loader_$field_name.setDefault('".$res[$field_label]."');\n";
					}
					if ($modo == "update" && array_key_exists("multiple", $ref->prop)) {
						$ids = "";
						foreach ($multiple_ref as $val) {
							$ids .= ($ids != "" ? "," : "") . "'$val'";
						} 
						if ($ids != "") $str .= "loader_$field_name.setDefault([$ids]);\n";
					}
					if ($this->idioma != "pt") $str .= "loader_$field_name.setLanguage('$this->idioma');\n";
					if (isset($ref->ajax_action)) {
						foreach ($ref->ajax_action as $r) {
							$action = $r["action"];
							if (isset($r["add_sufix"]) && $r["add_sufix"] == true) $action = str_replace("[]",$sufix,$action);
							if (substr($action,0,8) != "function") $action = "function(p,r,e) { $action }";
							$str .= "loader_$field_name.addAction($action);\n";
						}
					}
					if (isset($ref->ajax_xtrafield)) {
						foreach ($ref->ajax_xtrafield as $r) {
							$field = $prefix . $r["field"] . $sufix;
							$xml_label = $r["xml_label"];
							if ($field != $xml_label && substr($xml_label,0,8) == "function")
								$str .= "loader_$field_name.addField('$field', $xml_label);\n";
							else if ($field != $xml_label)
								$str .= "loader_$field_name.addField('$field', '$xml_label');\n";
							else
								$str .= "loader_$field_name.addField('$field');\n";
						}
					}
					if (($modo == "insert" || $modo == "report")) {
						if (!empty($ref->ajax)) {
							$str_cond = "";
							foreach ($ref->ajax as $src) {
								if ($src["obj"] == "field") {
									foreach ($this->ref as $field_obj) {
										if ($field_obj["field"] == $src["field"]) {
											$str_cond .= ($str_cond != "" ? " && " : "") . "document.formulario.".$this->getFieldLbl($field_obj["field"]).".value != ''";
										}
									}
								}
							}
							if ($str_cond != "") $str .= "if ($str_cond) loader_$field_name.load();\n";
						} else {
							$str .= "loader_$field_name.load();\n";
						}
					} else if ($modo == "update" && !($ref->scope == "entity_field" && $ref->get_ajax_scope() == "field")) {
						if (isset($res_aux) && count($res_aux) == 0) { // if (count($res_aux) == 0) => included on 10/05/2019, to prevent load if query has been queued; using loader.applyAction() instead
							$str .= "loader_$field_name.load();\n"; 
						}
					}
					$str .= "// --->\n";
					$str .= "</script>\n";
				}
				if (isset($ref->new_entry)) {
					$str .= "<input name=\"{$prefix}" . $field_label . "_entry{$sufix}\" id=\"{$prefix}" . $field_label . "_entry{$sufix}\" size=15 " . ($this->use_uppercase ? "onkeyup=\"this.value=this.value.toUpperCase()\"" : "") . " style=\"display:none\" class=$class>\n";
				}
			}
			if ($ck_print == 1) $str .= "</span>";
			if ($ref->ck_readonly == 1 && !empty($res) && $res[$field_label] == "") $str .= "<input type=\"hidden\" name=\"$field_name\" id=\"$field_name\" value=\"\">\n";
		}
		$str .= $debug;
		return $str;
	}
	public function build($modo, $step, $vars=false) {
		//if ($modo == "update" && $step == 1 && $this->document_name_list != "" && $this->debug == 1) echo "set_document_name($this->document_name_list) deve ser usado unicamente com modo <b>report</b><br>";
		if ($modo == "delete") $this->ck_recursive = 0;
		$this->modo = $modo;
		$this->step = $step;
		$this->vars = $vars ? $vars : $_REQUEST;
		if (!empty($this->vars["ck_xml"])) $modo = "xml";
		if (!empty($this->vars["ck_xls"])) $modo = "xls";
		if (!empty($this->vars["ck_csv"])) $modo = "csv";
		if (in_array($modo, ["csv","xls"])) $this->debug = 0;
		if ($this->debug == 1 && !in_array($modo, ["xml","csv","xls"])) $this->show_debug(print_r($this->vars,true),"V");
		$this->ck_print = isset($this->vars["ck_print"]) ? 1 : 0;
		if ($modo == "report" && $this->step == 2) $this->ck_print = 1;
		if ($this->ck_print == 1 && $this->css_table_print != "") $this->css_table = $this->css_table_print;
		if ($this->ck_print == 1 && $this->css_text_print != "") $this->css_text = $this->css_text_print;
		if ($this->ck_print == 1 && $this->css_label_print != "") $this->css_label = $this->css_label_print;
		if ($this->ck_print == 1) $this->add_onload_action("window.focus()");
		// Load dropdownLoaders with external ajax path
		if ($modo == "update") {
			foreach ($this->ref as $ref) {
				$obj = $ref["object"];
				if ($obj->type == "dropdown" && !empty($obj->ajax_path) && empty($obj->ajax)) $this->add_onload_action("loader_$obj->field.load();");
			}
		}
		// use vars[modo] for modo = complete
		if (isset($this->vars["_use_vars"])) {
			$modo = $this->vars["modo"];
			$step = $this->vars["step"];
		} else if (isset($this->vars["modo"]) && $this->vars["modo"] == "xml") {
			$modo = $this->vars["modo"];
		} else if (($modo == "complete" || $modo == "updatedelete") && $step != 1) {
			$modo = $this->vars["modo"];
		}
		if (($modo == "complete" || $modo == "updatedelete" || $modo == "update" || $modo == "report") && $step == 1 && isset($this->vars["count"])) {
			$this->update_list();
			$this->delete();
			return false;
		}
		if (($modo == "complete" && $step == 1 && count($this->ref_filter) > 0 && !isset($this->vars["_ck_filter"])) ||
			($modo == "updatedelete" && $step == 1 && count($this->ref_filter) > 0 && !isset($this->vars["_ck_filter"])) ||
			($modo == "updatedelete" && ($step == 0 || ($step == 1 && $this->use_list_filter == 1 && count($this->ref_filter) > 0))) ||
			($modo == "update" && ($step == 0 || ($step == 1 && $this->use_list_filter == 1 && count($this->ref_filter) > 0))) ||
			($modo == "delete" && ($step == 0 || ($step == 1 && $this->use_list_filter == 1 && count($this->ref_filter) > 0))) ||
			($modo == "report" && ($step == 0 || ($step == 1 && $this->use_list_filter == 1 && count($this->ref_filter) > 0))))
			$this->build_filter();
		if (($modo == "insert" && $step == 1) || 
		    ($modo == "update" && $step == 2) ||
		    ($modo == "report" && $step == 2) ||
			($modo == "complete" && $this->setup_complete == "integrated" && $step == 1)) 
			$this->build_form();
		if ($modo == "complete") echo "<br>";
		if (($modo == "delete" && $step == 1) || 
		    ($modo == "update" && $step == 1) ||
			($modo == "report" && $step == 1) ||
			($modo == "complete" && $step == 1 && !isset($this->vars["count"]) && (count($this->ref_filter) == 0 || isset($this->vars["_ck_filter"]))) ||
			($modo == "updatedelete" && $step == 1 && !isset($this->vars["count"]) && (count($this->ref_filter) == 0 || isset($this->vars["_ck_filter"])))) {
			if ($modo == "delete" || $modo == "complete" || $modo == "updatedelete") $this->set_list_form([$this->lang_button_label => ""]);
			$this->build_list();
		}
		if ($modo == "insert" && $step == 2) {
			$this->return_pk = "";
			for ($i=0; $i<$this->repeat_insert; $i++) {
				$this->insert(false, $i == $this->repeat_insert-1 ? 1 : 0);
			}
		}
		if ($modo == "update" && $step == 3) 
			$this->update();
		else if ($modo == "delete" && $step == 2) 
			$this->delete();
		else if ($modo == "xml" && !empty($this->vars["obj"]) && $this->vars["obj"] == "ck_unique") 
			$this->build_xml_unique();
		else if ($modo == "xml" && !empty($this->vars["obj"]) && $this->vars["obj"] == "insert") 
			$this->build_xml_insert();
		else if ($modo == "xml") 
			$this->build_xml();
		else if ($modo == "xls") 
			$this->build_xls();
		else if ($modo == "csv") 
			$this->build_csv();
		if (isset($this->exec_build)) {
			$ex = $this->exec_build; $ex();
		}
	}
	private function build_form($id = null) {
		$this->builder_ref = "form";
		$this->builder = "form";
		if (isset($this->vars["modo"]) && ($this->vars["modo"] == "update" || $this->vars["modo"] == "insert"))
			$modo = $this->vars["modo"];
		else
			$modo = $this->modo;
		$this->setup_form("form", $this->form_objs);
		for ($i=0; $i<count($this->ref); $i++) {
			$obj = $this->ref[$i]["object"];
			// change behaviour for password
			if ($obj->type == "password") {
				if ($obj->ck_qry == 1) {
					$obj->prop = $this->ref[$i+1]["object"]->prop;
					if ($modo == "update") $obj->label = "$this->lang_password_alter " . $obj->label;
				}
			}
		}
		if (($modo == "update" || $modo == "report") && $this->step == 2) {
			$this->get_form_data($this->ck_print);
		}
		if (isset($this->title)) $title = $this->title;
		else if ($modo == "insert") $title = "$this->lang_title_insert $this->ent";
		else if ($modo == "update" && $this->ck_print == 0) $title = "$this->lang_title_update $this->ent";
		else if ($modo == "delete") $title = "$this->lang_title_delete $this->ent";
		else if ($this->ck_print == 1) $title = "$this->lang_title_print $this->ent";
		else if ($modo == "report") $title = "$this->lang_title_report $this->ent";
		echo "<!DOCTYPE html>\n";
		echo "<HTML>\n";
		echo "<HEAD>\n";
		echo "<TITLE>" . strip_tags($title) . "</TITLE>\n";
		echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=ISO-8859-1\">\n";
		echo $this->get_js();
		if ($this->ck_print == 0) {
			// refresh dependency
			for ($i=0; $i<count($this->tab_ref); $i++) {
				if ($this->tab_ref[$i]["table"] != null) {
					$temp = [];
					for ($j=0; $j<count($this->ref); $j++) {
						if ($this->ref[$j]["tab_id"] == $i && 
							$this->ref[$j]["type"] != "entity" && 
							$this->ref[$j]["ck_req"] == 1) {
							$temp[count($temp)] = $j;
						}
					}
					for ($j=0; $j<count($temp); $j++) {
						if (count($this->ref[$temp[$j]]["req_dependency"]) > 0) {
							for ($k=0; $k<count($temp); $k++) {
								$this->ref[$temp[$j]]["req_dependency"][count($this->ref[$temp[$j]]["req_dependency"])] = $this->ref[$temp[$k]]["field"];
							}
						}
					}
				}
			}
			// build ckForm
			echo "<script type=\"text/javascript\">\n";
			echo "<!--- \n";
			if ($this->unique_ix > 0) {
				echo $this->get_js_unique();
			}
			if ($this->ck_mask >= 0) {
				echo $this->get_js_mask();
				if ($this->ck_mask == 1) {
					echo $this->get_js_unlock();
				}
			}
			if ($this->step == 0 && !empty($this->ref_filter))
				echo $this->get_js_form("filter", $this->ref_filter);
			else
				echo $this->get_js_form("form", $this->ref);
			/*
			if (($modo == "insert" && $this->step == 1) ||
				($modo == "update" && $this->step == 2) ||
				($modo == "complete" && $this->step == 0 && count($this->ref_filter) == 0) ||
				($modo == "update" && $this->step == 0 && count($this->ref_filter) == 0) ||
				($modo == "delete" && $this->step == 0 && count($this->ref_filter) == 0) ||
				($modo == "report" && $this->step == 0 && count($this->ref_filter) == 0))
				echo $this->get_js_form("form", $this->ref);
			else
				echo $this->get_js_form("filter", $this->ref_filter);
			*/
			if (count($this->ent_NXN) > 0) {
				echo "function chDisplayNXN(list) {\n";
				echo "	for (var i=0; i<list.length; i++) {\n";
				echo "		if (document.getElementById(\"table_\"+list[i])) {\n";
				echo "			obj = document.getElementById(\"table_\"+list[i])\n";
				echo "			obj.style.display = obj.style.display == 'none' ? '' : 'none'\n";
				echo "		}\n";
				echo "	}\n";
				echo "}\n";
				echo "function ckAll_NXN(ck,list,ck_collapse) {\n";
				echo "	var grps = []\n";
				echo "	for (var val of list) {\n";
				echo "		if (document.getElementById(\"ck_\"+val)) {\n";
				echo "			var elm = document.getElementById(\"ck_\"+val);\n";
				echo "			var grp = document.getElementById(\"ck_\"+val).getAttribute('grp');\n";
				echo "			if (!elm.disabled && grps.indexOf(grp) < 0) elm.checked = ck;\n";
				echo "			if (grp && elm.checked) grps.push(grp);\n";
				echo "		}\n";
				echo "	}\n";
				echo "	if (ck_collapse && ((ck && document.getElementById(\"table_\"+list[1]).style.display == '') || (!ck && document.getElementById(\"table_\"+list[1]).style.display == 'none'))) chDisplayNXN(list);\n";
				echo "}\n";
				echo "function unCheck(src) {\n";
				echo "	for (var elm of document.querySelectorAll('input[grp=\"'+src.getAttribute('grp')+'\"]')) {\n";
				echo "		if (elm != src) elm.checked = false;\n";
				echo "	}\n";
				echo "}\n";
			}
			if (count($this->tab_ref) > 0) {
				if (!isset($this->css_tab_selected)) $this->css_tab_selected = $this->css_label_entity;
				if (!isset($this->css_tab)) $this->css_tab_selected = $this->css_label;
				echo "function chTab(ix) {\n";
				echo "	var c = 0;\n";
				echo "	while (document.getElementById(\"tab\"+c)) {\n";
				echo "		document.getElementById(\"tab\"+c).className = (c == ix ? \"$this->css_tab_selected\" : \"$this->css_tab\");\n";
				echo "		document.getElementById(\"body\"+c).style.display = (c == ix ? \"\" : \"none\");\n";
				echo "		c++;\n";
				echo "	}\n";
				echo "}\n";
			}
			if ($this->ck_print == 0 && $this->ck_readonly == 0 && $this->ck_global_form == 0) {
				echo "function ckAll(field,val) {\n";
				echo "	var c = 0;\n";
				echo "	while (document.formulario[field+c]) {\n";
				echo "		document.formulario[field+c].value = val;\n";
				echo "		c++;\n";
				echo "	}\n";
				echo "}\n";
			}
			if ($this->ck_print == 0 && $this->ck_readonly == 0 && $this->ck_global_form == 1) {
				echo "function ckAll_globalform(table,ck) {\n";
				echo "	for (var i=0; i<document.getElementById(\"count_\"+table).value; i++) {\n";
				echo "		document.getElementById(\"ck_\"+table+i).checked = ck;\n";
				echo "	}\n";
				echo "}\n";
				echo "function fn_sync_dropdown_globalform(table,field,ix) {\n";
				echo "	if (document.getElementById(table+\"_\"+field+ix).value == \"\") {\n";
				echo "		document.getElementById(\"comment_\"+table+\"_\"+field+ix).innerHTML = \"\";\n";
				echo "		for (var j=document.getElementById(table+\"_\"+field+ix).length-1; j>= 0; j--) {\n";
				echo "			document.getElementById(table+\"_\"+field+ix).remove(j);\n";
				echo "		}\n";
				echo "		for (var j=0; j<document.getElementById(\"global_\"+table+\"_\"+field).length; j++) {\n";
				echo "			var oOption = document.createElement(\"OPTION\");\n";
				echo "			document.getElementById(table+\"_\"+field+ix).options.add(oOption);\n";
				echo "			oOption.value = document.getElementById(\"global_\"+table+\"_\"+field)[j].value;\n";
				echo "			oOption.text = document.getElementById(\"global_\"+table+\"_\"+field)[j].text;\n";
				echo "		}\n";
				echo "	}\n";
				echo "}\n";
				for ($i=0; $i<count($this->ent_1XN); $i++) {
					$c = $this->ent_1XN[$i];
					$ent = $this->ref[$c]["object"];
					$ent_table = $ent->prefix;
					echo "function fn_ckalterdropdown_$ent_table(field,ix) {\n";
					echo "	var get_field = ''\n";
					echo "	if (document.getElementById(\"{$ent_table}_\"+field+ix).length != document.getElementById(\"global_{$ent_table}_\"+field).length)\n";
					echo "		var get_field = field\n";
					echo "	else {\n";
					echo "		for (var i=0; i<document.getElementById(\"{$ent_table}_\"+field+ix).length ;i++) {\n";
					echo "			if (document.getElementById(\"{$ent_table}_\"+field+ix)[i].value != document.getElementById(\"global_{$ent_table}_\"+field)[i].value) {\n";
					echo "\t\tvar get_field = field\n";
					echo "\t\tbreak;\n";
					echo "			}\n";
					echo "		}\n";
					echo "	}\n";
					echo "	return get_field\n";
					echo "}\n";
					echo "function fn_apply_$ent_table() {\n";
					echo "	for (var i=0; i<document.getElementById(\"count_$ent_table\").value; i++) {\n";
					echo "		if (document.getElementById(\"ck_$ent_table\"+i).checked) {\n";
					$ref = $ent->edicao_global_ref;
					foreach ($ent->field as $obj) {
						$field = $obj->field;
						$type = $obj->type;
						//$ck_hidden = $obj->ck_hidden;
						if ($type != "hidden" &&
							$type != "file" &&
							//$ck_hidden == 0 &&
							(count($ref) == 0 || in_array($field, $ref))) {
							echo "			if (document.getElementById(\"ck_global_{$ent_table}_{$field}\").checked) {\n";
							if ($type == "dropdown") { // sincroniza se houve chamadas ajax
								echo "\t\tvar x = fn_ckalterdropdown_$ent_table(\"{$field}\",i);\n";
								echo "\t\tif (x != \"\") fn_sync_dropdown_globalform('{$ent_table}',x,i)\n";
							}
							if ($type == "checkbox") {
								echo "\t\tdocument.getElementById(\"{$ent_table}_{$field}\"+i).checked = document.getElementById(\"global_{$ent_table}_{$field}\").checked?1:0\n";
							} else if ($type == "radio") {
								echo "\t\tfor (var j=0; j<document.formulario[\"global_{$ent_table}_{$field}\"].length; j++) {\n";
								echo "\t\t\tif (document.formulario[\"global_{$ent_table}_{$field}\"][j].checked) document.formulario[\"{$ent_table}_{$field}\"+i][j].checked = true\n";
								echo "\t\t}\n";
							} else if ($type == "date") {
								echo "\t\tdocument.getElementById(\"dia_{$ent_table}_{$field}\"+i).value = document.getElementById(\"dia_global_{$ent_table}_{$field}\").value\n";
								echo "\t\tdocument.getElementById(\"mes_{$ent_table}_{$field}\"+i).value = document.getElementById(\"mes_global_{$ent_table}_{$field}\").value\n";
								echo "\t\tdocument.getElementById(\"ano_{$ent_table}_{$field}\"+i).value = document.getElementById(\"ano_global_{$ent_table}_{$field}\").value\n";
							} else if ($type == "dropdown" && array_key_exists("multiple", $obj->prop)) {
								echo "\t\tfor (var j=0; j<document.getElementById(\"global_{$ent_table}_{$field}\").length; j++) {\n";
								echo "\t\t	if (document.getElementById(\"global_{$ent_table}_{$field}\")[j].selected) document.getElementById(\"{$ent_table}_{$field}\"+i)[j].selected = true\n";
								echo "\t\t	else document.getElementById(\"{$ent_table}_{$field}\"+i)[j].selected = false\n";
								echo "\t\t}\n";
							} else {
								echo "\t\tdocument.getElementById(\"{$ent_table}_{$field}\"+i).value = document.getElementById(\"global_{$ent_table}_{$field}\").value\n";
							}
							if ($type == "fieldfilter") { // sincroniza dados com objeto ajax
								echo "\t\tdocument.getElementById(\"nome{$ent_table}_{$field}\"+i).value = document.getElementById(\"nomeglobal_{$ent_table}_{$field}\").value\n";
							}
							echo "			}\n";
						}
					}
					echo "		}\n";
					echo "	}\n";
					echo "}\n";
				}
			}
			if ($this->ck_multiple_entry == 1) {
				echo "function promptClone(table,holder) {\n";
				echo "	var x = prompt(\"$this->lang_prompt_entity\",1)\n";
				echo "	if (x && cknum(x)) {\n";
				echo "		for (var i=0; i<x; i++) {\n";
				echo "			var clone = addClone(table); remClass(clone,'form-entity-root');\n";
				echo "			var node = document.getElementById('readroot_'+table);\n";
				echo "			while (node.tagName != 'TR') {\n";
				echo "				var node = node.parentNode;\n";
				echo "				node.style.display = '';\n";
				echo "			}\n";
				echo "		}\n";
				echo "		if (holder) holder.style.display = '';\n";
				echo "	}\n";
				echo "}\n";
			}
			echo "// --->\n";
			echo "</script>\n";
		}
		if (count($this->onload_action["form"]) > 0) { // Publicar para ck_print = 0|1
			echo "<script type=\"text/javascript\">\n";
			echo "<!--- \n";
			echo "window.addEventListener('load', function() {\n";
			foreach ($this->onload_action["form"] as $cmd) {
				echo "\t$cmd\n";
			}
			echo "}, false);\n";
			echo "</script>\n";
		}
		echo "<link rel='STYLESHEET' type='text/css' href='" . ($this->ck_print == 1 ? $this->css_print_path : $this->css_path) . "'>\n";
		for ($i=0; $i<count($this->css_ref); $i++) {
			echo "<link rel='STYLESHEET' type='text/css' href='" . $this->css_ref[$i] . "'>\n";
		}
		if (!empty($this->cssStyle)) {
			echo "<style type=\"text/css\">\n<!--\n";
			foreach ($this->cssStyle["form"] as $line) {
				echo $line . "\n";
			}
			echo "-->\n</style>\n";
		}
		echo "</HEAD>\n";
		echo "<body bgcolor=White>\n";
		if ($modo == "insert" && 
			$this->ck_return_pk == 1 && 
			isset($this->vars["ck_return_pk"]) &&
			strpos($this->ck_return_use,"I") !== false) {
			echo $this->build_return_pk();
		}
		if ($this->ck_print == 0) 
			echo "<form " . ($this->document_name_form != "" ? "action=\"" . $this->document_name_form . "\"" : "") . " name=\"formulario\" method=\"POST\" enctype=\"multipart/form-data\">\n";
		$params = ["scope" => "form", "ref"=>$this->ref, "tpl"=>$this->tpl_form, "title"=> $title];
		if ($this->tpl_form == "")
			echo $this->get_form_default($params);
		else
			echo $this->get_form_custom($params);
		if ($this->ck_print == 0) 
			echo "</form>\n";
		echo "</BODY></HTML>\n";
	}
	public function get_print($id=false) {
		$this->get_form_data(1,$id);
		$this->builder = "form";
		$params = ["scope" => "form", "ref"=>$this->ref, "tpl"=>$this->tpl_form, "ck_print"=>1];
		if ($this->tpl_form == "")
			return $this->get_form_default($params);
		else
			return $this->get_form_custom($params);
	}
	private function setup_form($scope, $ref) {
		// add rules for conditional params (hidden, required, readonly)
		$fn = function($ref,$dst,$rule,$scope) {
			if (isset($dst->$rule) && is_array($dst->$rule)) {
				foreach ($ref as $src) {
					if ($src->type != "entity") {
						$this->setup_field($src, $dst, $rule, $scope, $ref);
					}
				}
			}
		};
		foreach ($ref as $dst) {
			if ($dst->type != "entity") {
				foreach (["ck_req", "ck_readonly", "ck_disabled", "ck_hidden", "ck_empty", "ck_value"] as $rule) {
					$fn($ref, $dst, $rule, $scope); // check for trigger on the same form scope
					if ($dst->scope == "entity_field") $fn($this->form_objs, $dst, $rule, $scope); // check for trigger on form header
				}
			} else {
				if ($dst->rel == "1XN") $this->setup_form($scope, $dst->field); // setup entity form
				foreach (["ck_req", "ck_hidden"] as $rule) {
					$fn($ref, $dst, $rule, $scope);
				}
			}
		}
	}
	private function setup_field($src, $dst, $rule, $scope, $ref=false) {
		if ($rule == "ck_value") {
			$ck = $dst->$rule[1];
		} else {
			$ck = $dst->$rule;
		}
		$ck_src = 0;
		foreach ((isset($ck["field"]) ? [$ck] : $ck) as $r) {
			if (isset($r["src"])) {
				foreach ($r["src"] as $field_name) {
					if ($this->getFieldLbl($src->field) == $field_name) {
						$ck_src = 1;
					}
				}
			}
		}
		if ($ck_src == 0) return false;
		$src_prefix = $src->scope == "entity_field" ? $src->entity->prefix . "_" : "";
		$dst_prefix = $dst->type != "entity" && $dst->scope == "entity_field" ? $dst->entity->prefix . "_" : "";
		if ($src_prefix != "") 
			$cmd_field_ix = "+ix"; // "+this.id.replace('$src_prefix" . $this->getFieldLbl($src->field) . "','')"; // Modified 21/09/2023
		else if ($scope == "list-js")
			$cmd_field_ix = "+ix";
		else
			$cmd_field_ix = "";
		$exp = "";
		foreach ($a = (isset($ck["field"]) ? [$ck] : $ck) as $r) {
			$condition = $r["op"];
			$val = $r["val"];
			$str_field = $this->getFieldLbl($r["field"]);
			$type = $src->type;
			if ($val instanceof Closure) {
				$_val = [];
				foreach (nc_query($src->qry["sql"]) as $_r) {
					if ($val($_r)) $_val[] = $_r[$src->qry["ix"]];
				}
				$val = $_val;
			}
			if (count($a) > 1) { // checks for src type
				foreach ($ref as $f) {
					if ($f->field == $str_field) {
						$type = $f->type;
						break;
					}
				}
			}
			if ($scope == "list") $str_field .= "[]";
			if ($scope == "form" && $src_prefix != "") 
				$str_field = "'$src_prefix$str_field'+ix"; // "'$src_prefix$str_field'+this.id.replace('$src_prefix$str_field','')"; // Modified 21/09/2023
			else
				$str_field = "'$str_field'";
			if ($scope == "list-js") $str_field .= "+ix";
			if ($exp != "") $exp .= " " . $r["bool"] . " ";
			if ($type == "checkbox") { // bug: must use rule field type
				$_ck = ($val == 1 && $condition == "==") || ($val == 0 && $condition == "!=");
				$exp .= (!$_ck?"!":"") . "document.formulario[$str_field].checked";
			} else if (is_array($val)) {
				$exp .= str_replace('"',"'",json_encode(array_map("utf8_encode",$val))) . ".map(String).indexOf(document.formulario[$str_field].value) " . ($condition == "==" ? ">= 0" : "< 0");
			} else {
				$exp .= "document.formulario[$str_field].value $condition '$val'";
			}
		}
		if ($exp != "") {
			// echo "$rule: dst($dst_prefix$dst->pos):" . $this->getFieldLbl($dst->field) . ", src($src_prefix$src->pos):" . $this->getFieldLbl($src->field) . ": $exp<hr>";
			if ($rule == "ck_req") {
				$spanId = $dst->type == "entity" ? $dst->prefix : $dst->field;
				$cmd = "if (obj = document.getElementById('{$dst_prefix}req-$spanId')) obj.style.display = ($exp ? 'inline-block' : 'none');";
			} else if ($rule == "ck_hidden") {
				if ($scope == "list") {
					$cmd = "obj = document.getElementById('" . $this->getFieldLbl($dst->field) . "[]'); while (obj.tagName != 'TR') obj = obj.parentNode; obj.style.display = ($exp ? 'none' : '');";
				} else if ($scope == "list-js") {
					$cmd = "obj = document.getElementById('" . $this->getFieldLbl($dst->field) . "'+ix); while (obj.tagName != 'TR') obj = obj.parentNode; obj.style.display = ($exp ? 'none' : '');";
				} else { // $scope form || filter
					if ($dst->type == "entity") {
						$cmd = "for (var elm of document.querySelectorAll('#hdr-" . $dst->prefix . ",#body-" . $dst->prefix . ",#holder-" . $dst->prefix . "')) elm.style.display = ($exp ? 'none' : '');";
					} else if ($dst->scope == "entity_field") {
						$cmd = "if (obj = document.getElementById('holder-" . $dst_prefix . $this->getFieldLbl($dst->field) . "'$cmd_field_ix)) obj.style.display = ($exp ? 'none' : '');";
					} else if ($src != $dst) { // Dont hide if triggered by itself
						$cmd = "if (obj = document.getElementById('holder-" . $this->getFieldLbl($dst->field) . "')) obj.style.display = ($exp ? 'none' : '');";
						if ($dst->field_group !== false)
							$cmd .= "if (obj = document.getElementById('grp-holder-" . $this->getFieldLbl($dst->field) . "')) obj.style.display = ($exp ? 'none' : '" . $dst->field_group["display"] . "');";
					}
				}
			} else if ($rule == "ck_readonly") {
				if (in_array($dst->type, ["date","datetime"]) && $this->pref_field_date == "text") {
					if ($dst->type == "datetime") 
						$fields = ["dia","mes","ano","hor","min"];
					else if ($dst->type == "date") 
						$fields = ["dia","mes","ano"];
					foreach ($fields as $p => $f) $fields[$p] = $f . "_" . $dst_prefix . $dst->field;
					$cmd  = "\n\tvar fields = " . str_replace("\"","'",json_encode($fields)) . ";";
					$cmd .= "\n\tfor (ix in fields) document.formulario[fields[ix]].readOnly = ($exp);\n";
				} else {
					$cmd = "";
					if (empty($dst->mask) || !empty($dst->unlock_mask))
						$cmd .= "document.formulario['" . $dst_prefix . $this->getFieldLbl($dst->field) . "'$cmd_field_ix].readOnly = ($exp);";
					if (!empty($dst->mask))
						$cmd .= "\n\tfor (var i=0; i<" . count(array_filter($dst->mask,'is_numeric')) . "; i++) document.formulario['" . $dst_prefix . $this->getFieldLbl($dst->field) . "'+i$cmd_field_ix].readOnly = ($exp);";
					if ($src->scope == "field" && $dst->scope == "entity_field") $cmd .= "\n\tfor (var i=0; i<count_" . $dst->entity->prefix . ".value; i++) document.formulario['" . $dst_prefix . $dst->field . "'+i].readOnly = ($exp);";
				}
			} else if ($rule == "ck_disabled") {
				if (in_array($dst->type, ["date","datetime"]) && $this->pref_field_date == "text") {
					if ($dst->type == "datetime") 
						$fields = ["dia","mes","ano","hor","min"];
					else if ($dst->type == "date") 
						$fields = ["dia","mes","ano"];
					foreach ($fields as $p => $f) $fields[$p] = $f . "_" . $dst_prefix . $dst->field;
					$cmd  = "\n\tvar fields = " . str_replace("\"","'",json_encode($fields)) . ";";
					$cmd .= "\n\tfor (ix in fields) document.formulario[fields[ix]].disabled = ($exp);\n";
				} else if (in_array($dst->type, ["radio"])) {
					$cmd  = "\n\tvar field = document.formulario['" . $dst_prefix . $this->getFieldLbl($dst->field) . "'$cmd_field_ix];";
					$cmd .= "\n\tfor (ix in field) field[ix].disabled = ($exp);";
				} else {
					$cmd = "";
					if (empty($dst->mask) || !empty($dst->unlock_mask))
						$cmd .= "document.formulario['" . $dst_prefix . $this->getFieldLbl($dst->field) . "'$cmd_field_ix].disabled = ($exp);";
					if (!empty($dst->mask))
						$cmd .= "\n\tfor (var i=0; i<" . count(array_filter($dst->mask,'is_numeric')) . "; i++) document.formulario['" . $dst_prefix . $this->getFieldLbl($dst->field) . "'+i$cmd_field_ix].disabled = ($exp);";
					if ($src->scope == "field" && $dst->scope == "entity_field") $cmd .= "\n\tfor (var i=0; i<count_" . $dst->entity->prefix . ".value; i++) document.formulario['" . $dst_prefix . $dst->field . "'+i].disabled = ($exp);";
				}
			} else if ($rule == "ck_empty") {
				if (in_array($dst->type, ["date","datetime"]) && $this->pref_field_date == "text") {
					if ($dst->type == "datetime") 
						$fields = ["dia","mes","ano","hor","min"];
					else if ($dst->type == "date") 
						$fields = ["dia","mes","ano"];
					foreach ($fields as $p => $f) $fields[$p] = $f . "_" . $dst_prefix . $dst->field;
					$cmd  = "\n\tvar fields = " . str_replace("\"","'",json_encode($fields)) . ";";
					$cmd .= "\n\tif ($exp) for (ix in fields) document.formulario[fields[ix]].value = '';";
				} else if (in_array($dst->type, ["radio"])) {
					$cmd  = "\n\tvar field = document.formulario['" . $dst_prefix . $this->getFieldLbl($dst->field) . "'$cmd_field_ix];";
					$cmd .= "\n\tif ($exp) for (ix in field) field[ix].checked = false;";
				} else {
					$cmd = "if ($exp) document.formulario['" . $dst_prefix . $this->getFieldLbl($dst->field) . "'$cmd_field_ix].value = '';";
					if ($src->scope == "field" && $dst->scope == "entity_field") $cmd .= "if ($exp) for (var i=0; i<count_" . $dst->entity->prefix . ".value; i++) document.formulario['" . $dst_prefix . $dst->field . "'+i].value = '';";
				}
			} else if ($rule == "ck_value") {
				if (is_numeric($dst->$rule[0])) {
					$cmd = "if ($exp) {\n\t\tdocument.formulario['" . $dst_prefix . $this->getFieldLbl($dst->field) . "'$cmd_field_ix].value = " . $dst->$rule[0] . ";";
					if ($src->scope == "field" && $dst->scope == "entity_field") $cmd .= "\n\t\tif ($exp) for (var i=0; i<count_" . $dst->entity->prefix . ".value; i++) document.formulario['" . $dst_prefix . $dst->field . "'+i].value = " . $dst->$rule[0] . ";";
					$cmd .= "\n\t}";
				} else if (in_array($dst->type, ["date","datetime"]) && $this->pref_field_date == "text") {
					if ($dst->type == "datetime") 
						$fields = ["dia","mes","ano","hor","min"];
					else if ($dst->type == "date") 
						$fields = ["dia","mes","ano"];
					foreach ($fields as $p => $f) $fields[$p] = $f . "_" . $dst_prefix . $dst->field;
					$cmd  = "\n\tvar fields = " . str_replace("\"","'",json_encode($fields)) . ";";
					$cmd .= "\n\tvar vals = " . str_replace("\"","'",json_encode(explode("-",$dst->$rule[0]))) . ";";
					$cmd .= "\n\tif ($exp) for (var ix in fields) document.formulario[fields[ix]].value = vals[ix];";
				} else if (in_array($dst->type, ["radio"])) {
					$field = "document.formulario['" . $dst_prefix . $this->getFieldLbl($dst->field) . "'$cmd_field_ix]";
					$cmd = "\n\tif ($exp) for (var f of $field) if (f.value = document.formulario['" . $dst->$rule[0] . "'$cmd_field_ix].value) f.checked = true;";
				} else {
					$cmd = "if ($exp) document.formulario['" . $dst_prefix . $this->getFieldLbl($dst->field) . "'$cmd_field_ix].value = document.formulario['" . $dst->$rule[0] . "'$cmd_field_ix].value;";
				}
			}
			if (!empty($cmd)) {
				if ($scope == "list-js") {
					$return = $cmd;
				} 
				if ($scope == "list" || $scope == "list-js") {
					$fSrc = $this->getFieldLbl($src->field);
					$fDst = $this->getFieldLbl($dst->field);
					$cmd = "setup" . ucfirst($fDst) . "([]);";
				}
				if ($scope == "form") { // Modified 21/09/2023
					$getFnPart = function($val) { return str_replace(" ","",ucwords(str_replace("_"," ",$val))); };
					if ($dst->type == "entity") {
						$pDst = "";
						$fDst = $getFnPart($dst->prefix);
					} else {
						$pDst = $dst->scope == "entity_field" ? $getFnPart($dst->entity->prefix) : "";
						$fDst = $getFnPart($this->getFieldLbl($dst->field));
					}
					$fn = "set$pDst$fDst" . ucfirst(str_replace("ck_", "", $rule));
					if ($src->scope == "entity_field") {
						if (empty($this->writtenFn[$fn])) $this->add_js_code("function $fn(ix) {\n\t$cmd\n}");
						$cmd = "$fn(this.id.replace('" . $src->entity->prefix . "_" . $this->getFieldLbl($r["field"]) . "',''))";
					} else {
						if (empty($this->writtenFn[$fn])) $this->add_js_code("function $fn() {\n\t$cmd\n}");
						$cmd = "$fn()";
					}
					$this->writtenFn[$fn] = true;
				}
				if ($src->type == "fieldfilter") {
					$src->add_ajax_action($cmd, "S");
					$src->add_ajax_action($cmd, "R");
				} else {
					if ($src->type == "radio" || $src->type == "checkbox" || $src->type == "radio_checkbox")
						$op = "onclick";
					else
						$op = "onchange";
					$src->add_prop($op, $cmd);
				}
				if (!empty($return)) return $return;
			}
		}
	}
	public function get_form_data($ck_print, $pkVal=false) {
		$str_field = "";
		for ($i=0; $i<count($this->ref); $i++) {
			$obj = $this->ref[$i]["object"];
			$field = $obj->field;
			$type = $obj->type;
			if (!empty($obj->db_type)) $type = $obj->db_type;
			if ($type != "entity" && 
				($obj->ck_qry == 1 || !empty($obj->field_qry))) {
				if (isset($obj->rec_table) && $obj->rec_table["table"] != null) {
					$table = $obj->rec_table["table"];
					$label = $this->getFieldLbl($field);
					if (!is_array($field)) $field = $this->getFieldIx($field);
				} else if (count($this->tab_ref) == 0 || $obj->tab_id == -1 || $this->tab_ref[$obj->tab_id]["table"] == null) {
					$table = $this->table;
					if (strrpos($table, " ") !== false) $table = substr($table, trim(strrpos($table, " ")));
					$label = $this->getFieldLbl($field);
					if (!is_array($field)) $field = $this->getFieldIx($field);
				} else {
					$table = $this->tab_ref[$obj->tab_id]["table"];
					$label = "{$table}_{$field}";
				}
				if ($obj->field == $this->getFieldLbl($obj->field))
					$extended_field = "$table.$field";
				else
					$extended_field = $this->getFieldIx($obj->field);
				if (!empty($obj->field_qry)) {
					$str_field .= "\t$obj->field_qry,\n";
					$found[] = $this->getFieldLbl($obj->field_qry);
				} else if ($type == "date" && $this->pref_field_date == "date") {
					$str_field .= "\t" . ($this->db == "MSSQL" ? "dbo." : "") . "DATE_FORMAT($extended_field,'%Y-%m-%d') AS $label,\n";
				} else if ($type == "date" && $obj->ck_readonly != 1 && empty($ck_print)) {
					$str_field .= "\tDAY($extended_field) AS dia_$label, MONTH($extended_field) AS mes_$label, YEAR($extended_field) AS ano_$label,\n";
				} else if ($type == "date" && ($obj->ck_readonly == 1 || $this->ck_readonly == 1 || $ck_print == 1)) {
					$str_field .= "\t" . ($this->db == "MSSQL" ? "dbo." : "") . "DATE_FORMAT($extended_field, '%d/%m/%Y') AS $label,\n";
				} else if ($type == "datetime" && $obj->ck_readonly != 1 && $this->ck_readonly != 1 && empty($ck_print) && $this->db == "MSSQL") {
					$str_field .= "\tDAY($extended_field) AS dia_$label, MONTH($extended_field) AS mes_$label, YEAR($extended_field) AS ano_$label, datepart(hh, $extended_field) AS hor_$label, datepart(n, $extended_field) AS min_$label,\n";
				} else if ($type == "datetime" && $obj->ck_readonly != 1 && $this->ck_readonly != 1 && empty($ck_print) && $this->db == "MYSQL") {
					$str_field .= "\tDAY($extended_field) AS dia_$label, MONTH($extended_field) AS mes_$label, YEAR($extended_field) AS ano_$label, HOUR($extended_field) AS hor_$label, MINUTE($extended_field) AS min_$label,\n";
				} else if ($type == "datetime" && ($obj->ck_readonly == 1 || $this->ck_readonly == 1 || $ck_print == 1)) {
					$str_field .= "\t" . ($this->db == "MSSQL" ? "dbo." : "") . "DATE_FORMAT($extended_field, '%d/%m/%Y %h:%i') AS $label,\n";
				} else if ($type == "daterange") {
					$str_field .= "\tDAY($extended_field_ini) AS dia_{$label}_ini, MONTH($extended_field_ini) AS mes_{$label}_ini, YEAR($extended_field_ini) AS ano_{$label}_ini,\n";
					$str_field .= "\tDAY($extended_field_fim) AS dia_{$label}_fim, MONTH($extended_field_fim) AS mes_{$label}_fim, YEAR($extended_field_fim) AS ano_{$label}_fim,\n";
				/* Disabled 12/09/2023, using $val->format()
				} else if ($type == "month" && empty($ck_print) && $this->db == "MSSQL") {
					$str_field .= "\tCASE
							WHEN (SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' AND COLUMN_NAME = '$field') = 'varchar' THEN LEFT($extended_field,4)
							ELSE CONVERT(varchar,YEAR($extended_field))
						END AS ano_$field,
						CASE
							WHEN (SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' AND COLUMN_NAME = '$field') = 'varchar' THEN RIGHT($extended_field,2)
							ELSE CONVERT(varchar,MONTH($extended_field))
						END AS mes_$field,\n";
					//$str_field .= "MONTH($extended_field) AS mes_$label, YEAR($extended_field) AS ano_$label,\n";
				} else if ($type == "monthx" && $ck_print == 1) {
					$str_field .= "\tCASE
							WHEN (SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' AND COLUMN_NAME = '$field') = 'varchar' THEN dbo.formatCompetencia($extended_field)
							ELSE " . ($this->db == "MSSQL" ? "dbo." : "") . "DATE_FORMAT($extended_field, '%m/%Y')
						END AS $label,\n";
				} else if ($type == "month" && empty($ck_print) && $this->db == "MYSQL") {
					$str_field .= "\tYEAR($extended_field) AS ano_$label, MONTH($extended_field) AS mes_$label,\n";
				*/
				} else if ($this->db == "MSSQL" && ($type == "textarea" || $type == "publisher")) {
					$str_field .= "\tCONVERT(text, $extended_field) AS $label,\n";
				} else if ($type == "display") {
					if (!empty($obj->field)) {
						$str_field .= "\t$extended_field AS $label,\n";
						$found[] = $this->getFieldLbl($extended_field);
					}
				} else if ($type != "fieldlist" &&
					($type != "dropdown" || !array_key_exists("multiple", $obj->prop) || !isset($obj->rec_table))) {
					$str_field .= "\t$extended_field AS $label,\n";
					$found[] = $this->getFieldLbl($label);
				}
				if ($type == "radio" &&
					isset($obj->list)) {
					foreach ($obj->list["vals"] as $item) {
						if (is_array($item) &&
							$item["field"] != null) {
							$str_field .= "\t$table." . $item["field"] . ",\n";
						}
					}
				}
				if (!empty($obj->ref_label)) {
					$str_field .= "\t$obj->ref_label AS label_$label,\n";
				}
			}
			// get fields on dir name
			if (isset($obj->file)) {
				$file = $obj->file;
				foreach ($obj->file as $file) {
					preg_match_all("[\[([a-zA-Z0-9_\.,])+\]]", $file["dir"]["O"], $match);
					foreach ($match[0] as $m) {
						$ix = substr($m,1,-1);
						if (strpos($ix,",") > 0) $ix = substr($ix,0,strpos($ix,","));
						if (!preg_match("/$ix,*(\n|\r|$)/", $str_field)) {
							if (strpos($m, ".") === false) $ix = "$table.$ix";
							if (!in_array($ix, $found)) $str_field .= "\t$ix,\n";
						}
					}
				}
			}
			// get fields on qry
			if (isset($obj->ref_qry)) { 
				preg_match_all("[\[([a-zA-Z0-9_\.])+\]]", $obj->ref_qry, $match);
				for ($j=0; $j<count($match[0]); $j++) {
					$ix = substr($match[0][$j],1,-1);
					if (!in_array($ix,$found)) $str_field .= "\t$ix,\n";
				}
			}
		}
		for ($i=0; $i<count($this->tab_ref); $i++) {
			$table = $this->tab_ref[$i]["table"];
			if (!empty($table)) $str_field .= "\tCASE WHEN $table.id IS NOT NULL THEN $table.id ELSE 0 END AS id_$table,\n";
		}
		if (is_array($this->pk)) {
			$str_pk = "";
			foreach ($this->pk as $pk) {
				if (!in_array($pk, $found)) $str_pk .= ($str_pk != "" ? ",\n\t" : "") . "$this->table.$pk";
			}
			$str_field .= $str_pk;
		} else {
			if (!in_array($this->pk, $found)) $str_field .= "$this->table.$this->pk";
		}
		// join
		$join_str = "";
		for ($i=0; $i<count($this->related); $i++) {
			if (strpos($this->related[$i]["use"],"F") !== false) $join_str .= $this->related[$i]["sql"] . "\n";
		}
		// PK
		if ($pkVal) {
			if (is_array($pkVal)) {
				$this->pkVal = $pkVal;
			} else {
				$this->pkVal[$this->pk] = $pkVal;
			}
		} else {
			if (is_array($this->pk)) {
				foreach ($this->pk as $pk) {
					if (isset($this->vars[$pk])) $this->pkVal[$pk] = $this->vars[$pk];
				}
			} else if (isset($this->vars[$this->pk])) {
				$this->pkVal[$this->pk] = $this->vars[$this->pk];
			} else if ($this->id) {
				$this->pkVal[$this->pk] = $this->id;
			}
		}
		if (substr(rtrim($str_field),-1) == ",") $str_field = substr(rtrim($str_field),0,-1);
		if (isset($this->pkVal) && count($this->pkVal) > 0) {
			$qry = "SELECT\n"
				 . $str_field . "\n"
				 . "FROM $this->table\n";
			if ($join_str != "") $qry .= $join_str . "\n";
			$qry .= "WHERE\n\t";
			$c = 0;
			foreach ($this->pkVal as $pk => $val) {
				$f = "$this->table.$pk";
				if (is_array($this->pk)) {
					foreach ($this->label as $l) {
						if ($this->getFieldLbl($l["field"]) == $pk) {
							$f = $this->getFieldIx($l["field"]);
							break;
						}
					}
				}
				$qry .= ($c > 0 ? " AND\n\t" : "") . "$f = '$val'";
				$c++;
			}
			if ($this->debug == 1) $this->show_debug($qry,"H");
			return $this->res_upd = nc_query($qry)[0];
		}
	}
	public function get_res_header() {
		return $this->res_upd;
	}
	private function feed_filter_prop() {
		for ($i=0; $i<count($this->ref_filter); $i++) {
			if ($this->ref_filter[$i]["type"] == null) {
				for ($j=0; $j<count($this->ref); $j++) {
					if ($this->ref_filter[$i]["field"] == $this->ref[$j]["field"]) {
						$keys = array_keys($this->ref[$j]);
						for ($k=0; $k<count($keys); $k++) {
							if (isset($this->ref_filter[$i][$keys[$k]]))
								$prop = $this->ref_filter[$i][$keys[$k]];
							else
								$prop = null;
							if ($prop == null ||
								(is_array($prop) && count($prop) == 0))
								$this->ref_filter[$i][$keys[$k]] = $this->ref[$j][$keys[$k]];
						}
						break;
					}
				}
			}
			if ($this->ref_filter[$i]["type"] == "text" &&
				(!isset($this->ref_filter[$i]["mask"]) || count($this->ref_filter[$i]["mask"]) == 0)) {
				$this->ref_filter[$i]["type"] = "findtext";
			}
		}
	}
	private function build_filter() {
		$this->builder = "form";
		$this->builder_ref = "filter";
		$this->tab_ref = [];
		$this->feed_filter_prop();
		if (isset($this->title)) $title = $this->title;
		else if ($this->modo == "update") $title = "$this->lang_title_update $this->ent";
		else if ($this->modo == "delete") $title = "$this->lang_title_delete $this->ent";
		else if ($this->modo == "complete" || $this->modo == "updatedelete") $title = "$this->lang_title_complete $this->ent";
		else if ($this->modo == "report") $title = "$this->lang_title_report $this->ent";
		echo "<!DOCTYPE html>\n";
		echo "<HTML>\n";
		echo "<HEAD>\n";
		echo "<TITLE>" . strip_tags($title) . "</TITLE>\n";
		echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=ISO-8859-1\">\n";
		echo "<link rel='STYLESHEET' type='text/css' href='" . $this->css_path . "'>\n";
		for ($i=0; $i<count($this->css_ref); $i++) {
			echo "<link rel='STYLESHEET' type='text/css' href='" . $this->css_ref[$i] . "'>\n";
		}
		echo "<script type=\"text/javascript\">\n";
		echo "<!--- \n";
		if ($this->ck_mask >= 0) {
			echo $this->get_js_mask();
			if ($this->ck_mask == 1) {
				echo $this->get_js_unlock();
			}
		}
		echo $this->get_js_form("filter", !empty($this->ref_filter) ? $this->ref_filter : $this->ref);
		if (count($this->onload_action["filter"]) > 0) { // Publicar para ck_print = 0|1
			echo "window.addEventListener('load', function() {\n";
			foreach ($this->onload_action["filter"] as $cmd) {
				echo "\t$cmd\n";
			}
			echo "}, false);\n";
		}
		echo "// --->\n";
		echo "</script>\n";
		if ($this->step == 0) echo $this->get_js();
		echo "</HEAD>\n";
		echo "<body bgcolor=White>\n";
		echo "<form " . ($this->document_name_form != "" ? "action=\"" . $this->document_name_form . "\"" : "") . " name=\"formulario\" method=\"POST\" onsubmit=\"return ckForm(this)\">\n";
		if (count($this->ref_filter) > 0) {
			$ref = $this->ref_filter;
			$this->setup_form("filter", $this->filter_objs);
		} else {
			$this->setup_form("filter", $this->form_objs);
			$ref = [];
			for ($i=0; $i<count($this->ref); $i++) {
				if ($this->ref[$i]["type"] != "entity" && 
					$this->ref[$i]["type"] != "file" && 
					$this->ref[$i]["type"] != "textarea") $ref[] = $this->ref[$i];
			}
		}
		$params = ["scope" => "filter", "ref"=>$ref, "tpl"=>$this->tpl_filter, "title"=> $title];
		if ($this->tpl_filter == "")
			echo $this->get_form_default($params);
		else
			echo $this->get_form_custom($params);
		echo "</form>\n";
	}
	private function get_form_default($params) {
		foreach ($params as $ix => $val) $$ix = $val;
		if (!isset($scope)) exit("Invalid \$params for get_form_default([" . implode(", ",array_keys($params)) . "]), missing 'scope'");
		if (!isset($ref)) exit("Invalid \$params for get_form_default([" . implode(", ",array_keys($params)) . "]), missing 'ref'");
		if (!isset($ck_print)) $ck_print = $this->ck_print;
		if (!isset($title)) $title = "";
		$ret = "";
		// print_r($this->res_upd);
		if (isset($this->vars["modo"]) && ($this->vars["modo"] == "update" || $this->vars["modo"] == "insert"))
			$modo = $this->vars["modo"];
		else
			$modo = $this->modo;
		$ret .= "<table border=0 id=\"tbl-cls_form\" width=\"$this->form_width\" cellspacing=\"$this->cellspacing\" cellpadding=\"$this->cellpadding\" class=\"$this->css_table\">\n";
		if ($this->ck_display_title == 1 && $title != "") {
			if ($ck_print == 1)
				$ret .= "<tr><td colspan=2 class=\"$this->css_title_pg\"><span>$title</span></td></tr>\n";
			else
				$ret .= "<tr><td colspan=2 class=\"$this->css_title_pg\"><span style=\"float:left\">$title</span>" . ($this->use_required_position == "right" && empty($ck_print) ? "<span class=\"$this->css_required\" style=\"float:right; line-height:12pt\">$this->str_required $this->lang_label_required</span>" : "") . "</td></tr>\n";
			if (empty($ck_print) && $this->ck_readonly == 0 && $scope == "form" && $this->use_required_position == "down") {
				$ret .= "<tr class=\"$this->css_list_separator\"><td colspan=2 class=\"$this->css_list_separator\"></td></tr>\n";
				$ret .= "<tr class=\"{$this->css_label_entity}\"><td colspan=2>$this->str_required=$this->lang_label_required</td></tr>\n";
			}
			$ret .= "<tr class=\"$this->css_form_separator\"><td colspan=2 class=\"$this->css_form_separator\"></td></tr>\n";
		}
		for ($i=0; $i<count($ref); $i++) {
			$obj = $ref[$i]["object"];
			if (empty($ck_print) && $obj->tab_id >= 0 && empty($ck_tab)) {
				if ($i > 0) $ret .= "<tr class=\"$this->css_form_separator\"><td colspan=2 class=\"$this->css_form_separator\"></td></tr>\n";
				$ret .= "<tr><td colspan=2 style=\"padding:0;border:0\">\n";
				$ret .= "<table " . (isset($this->css_table_tab) ? "class=\"$this->css_table_tab\"" : "") . " width=\"100%\" style=\"width:calc(100% + ({$this->cellspacing}px * 2)); border-spacing:{$this->cellspacing}px 0; margin: 0 -{$this->cellspacing}px;\"><tr>\n";
				foreach ($this->tab_ref as $cTab => $tab) {
					if ($tab["label"] != false) {
						$str = "chTab($cTab)";
						foreach ($tab["actions"] as $cmd) $str .= ";$cmd";
						$ret .= "<td id=\"tab$cTab\" align=center class=\"" . ($cTab==0 ? $this->css_tab_selected : $this->css_tab) . "\" onclick=\"$str\" style=\"width:" . round(100/count($this->tab_ref)) . "%;\">" . $tab["label"] . "</td>\n";
					}
				}
				$ret .= "</tr></table>\n";
				$ret .= "</td>\n";
				$ret .= "</tr>\n";
				$ck_tab = 1;
			}
			if (empty($ck_print) && $scope == "form" && ($i == 0 || $obj->tab_id != $ref[$i-1]["object"]->tab_id)) {
				$ret .= "<tbody " . ($obj->tab_id >= 0 ? " id=\"body" . $obj->tab_id . "\" class=\"glueprevious\"" : "") . ($obj->tab_id > 0 ? " style=\"display:none\"" : "") . ">\n";
			}
			if ($ck_print == 1 && $i > 0 && $ref[$i]["tab_id"] != $ref[$i-1]["tab_id"] && $ref[$i]["type"] != "entity") {
				$ret .= "<tr class=\"separator\"><td colspan=2></td></tr>\n";
			}
			$ck_hidden = $this->ck_hidden($ref, $i);
			if ($obj->type == "entity" && $obj->rel == "1XN") {
				for ($j=0; $j<count($this->ent_1XN); $j++) {
					if ($this->ent_1XN[$j] == $i) break;
				}
				$ent_table = $j . $obj->table;
				$prefix = $obj->prefix;
				if ($modo == "update" || $modo == "report" || $ck_print == 1) {
					if (isset($obj->qry)) {
						$qry = $obj->qry;
						preg_match_all("[\[([a-zA-Z0-9_])+\]]", $qry, $match);
						for ($k=0; $k<count($match[0]); $k++) {
							$ix = substr($match[0][$k],1,-1);
							if (isset($this->res_upd)) {
								if ($ix == "id" && count($this->tab_ref) > 0 && $this->tab_ref[$obj->tab_id]["table"] != null)
									$val = $this->res_upd["id_" . $this->tab_ref[$obj->tab_id]["table"]];
								else
									$val = $this->res_upd[$ix];
							} else if ($ix == "id")
								$val = 0;
							else 
								$val = "";
							$qry = str_replace($match[0][$k], $val, $qry);
						}
						if ($this->debug == 1) $this->show_debug($qry,"E","fixed",$ret);
						$res_aux = nc_query($qry);
					} else {
						$res_aux = $this->get_res_aux($ref[$i]);
					}
				}
				if (($modo == "report" || !empty($ck_print)) && empty($res_aux) && !empty($obj->ck_hide_empty)) $ck_hidden = 1; // form title must allways be visible on insert, update; hide form title only for print preview if no data is found
				if ($ck_print == 1 && $ck_hidden == 0 && $i > 0 && $ref[$i]["tab_id"] != $ref[$i-1]["tab_id"]) {
					$ret .= "<tr class=\"separator\"><td colspan=2></td></tr>\n";
				}
				if ($i == 0 ||
					$ref[$i-1]["type"] == "entity" || 
					$ref[$i-1]["label"] != $obj->label) {
					$ret .= "<tr id=\"hdr-$obj->prefix\" " . ($ck_hidden == 1 ? "style=display:none" : "") . ">\n";
					$ret .= "<td colspan=2 class=\"{$this->css_label}\">\n";
					$ck_req_default = is_array($obj->ck_req) ? $this->ck_condition($ref, $obj->ck_req) : $obj->ck_req;
					if ($obj->label != "") $ret .= "<span style=\"float:left\">" . (!empty($obj->ck_req) && empty($ck_print) ? "<span id=\"req-$obj->prefix\" " . ($ck_req_default == 0 ? "style=\"display:none;\"" : "") . ">$this->str_required</span> " : "") . $obj->label . ":</span>\n";
					if ($obj->ck_multiple_entry == 1)
						$action_str = "promptClone('$prefix',document.getElementById('holder-$prefix'));";
					else
						$action_str = "clone=addClone('$prefix');remClass(clone,'form-entity-root');document.getElementById('holder-$prefix').style.display='';";
					// $action_str .= "document.getElementById('titulo_$prefix')?document.getElementById('titulo_$prefix').style.display='':void(null);";
					if (!empty($obj->cmd)) foreach ($obj->cmd as $cmd) $action_str .= "$cmd;";
					if (empty($ck_print) && $this->ck_readonly == 0 && $obj->ck_readonly == 0) $ret .= "<span style=\"float:right\"><a class=\"$this->css_link_general\" id=\"clone_$prefix\" href=\"javascript:void(null)\" onclick=\"$action_str\">[+]</a></span>\n";
					$ret .= "</td></tr>\n";
				}
				if (empty($ck_print) && $this->ck_readonly == 0 && $obj->ck_edicao_global == 1) {
					$global_ref = $obj->edicao_global_ref;
					$ret .= "<tr " . ($ck_hidden == 1 ? "style=display:none" : "") . " class=\"$this->css_label\">\n";
					$ret .= "<td colspan=2>\n";
					$ret .= "$this->list_bullet <a href=\"javascript:void(null)\" onclick=\"document.getElementById('indexacao_global_$prefix').style.display=document.getElementById('indexacao_global_$prefix').style.display=='none'?'':'none'\" class=\"$this->css_link_general\">$this->lang_global_edition:</a><br>\n";
					$ret .= "<table id=\"indexacao_global_$prefix\" width=100% border=0 cellspacing=0 cellpadding=5 style=display:none>\n";
					$ret .= "<tr class=\"$this->css_text\">\n";
					$count = count($global_ref) == 0 ? count($obj->field) : count($global_ref);
					// check split point
					$c = 0;
					for ($j=0; $j<$count; $j++) {
						if (count($global_ref) == 0) {
							$ix = $j;
						} else {
							for ($k=0; $k<count($obj->field); $k++) {
								if ($global_ref[$j] == $obj->field[$k]->field) {
									$ix = $k;
									break;
								}
							}
						}
						if ($obj->field[$ix]->type != "hidden" &&
							$obj->field[$ix]->type != "file" &&
							$obj->field[$ix]->ck_hidden == 0) $c++;
					}
					$split = ceil($c/2);
					// draw form
					$c = 0;
					for ($j=0; $j<$count; $j++) {
						if ($j == 0 || $c == $split) $ret .= "<td width=50%>\n";
						if (count($global_ref) == 0) {
							$ix = $j;
						} else {
							for ($k=0; $k<count($obj->field); $k++) {
								if ($global_ref[$j] == $obj->field[$k]->field) {
									$ix = $k;
									break;
								}
							}
						}
						$entObj = $obj->field[$ix];
						$field = $entObj->field;
						$label = $entObj->label;
						if ($entObj->type != "hidden" &&
							$entObj->type != "file" &&
							$entObj->ck_hidden == 0 &&
							(count($global_ref) == 0 || in_array($field, $global_ref))) {
							$c++;
							$ret .= "<label><input type=checkbox id=\"ck_global_{$prefix}_{$field}\" name=\"ck_global_{$prefix}_{$field}\" value=1 onclick=\"document.getElementById('{$field}_{$prefix}div').style.display=this.checked?'':'none'\" " . (count($global_ref) == 1 ? "style=\"display:none\" CHECKED" : "") . ">$label</label><br>\n";
							$ret .= "<div id=\"{$field}_{$prefix}div\" " . (count($global_ref) != 1 ? "style=\"padding-left:4px;display:none\"" : "") . ">\n";
							if (isset($entObj->comment_before)) $ret .= $entObj->comment_before . "\n";
							$ret .= $this->get_field(["scope" => $scope, "ref" => $obj->field[$ix], "prefix" => "global_{$prefix}_", "ck_print" => $ck_print]);
							$ret .= "<span id=\"comment_global_{$prefix}_{$field}\">";
							if (isset($entObj->comment)) $ret .= $entObj->comment;
							$ret .= "</span>\n";
							$ret .= "<br>";
							$ret .= "</div>\n";
						}
						if ($j == $count-1 ||
							$c == $split) $ret .= "</td>\n";
					}
					$ret .= "</tr>\n";
					$ret .= "<tr>\n";
					$ret .= "	<td class=\"$this->css_text\">\n";
					$ret .= "	<label><input type=checkbox onclick=\"ckAll_globalform('$prefix',this.checked)\" CHECKED>MARCAR/DESMARCAR TODAS</label>\n";
					$ret .= "	<input type=button class=\"$this->css_button\" value=\"Aplicar\" onclick=\"fn_apply_$prefix()\">\n";
					$ret .= "	</td>\n";
					$ret .= "</tr>\n";
					$ret .= "</table>\n";
					$ret .= "</td></tr>\n";
				}
				if ($modo != "report" && empty($res_aux)) $ck_hidden = 1; // hide form holder if no data is found
				$ret .= "<tr id=\"holder-$prefix\" " . ($ck_hidden == 1 ? "style=display:none" : "") . "><td colspan=2 class=\"" . ($this->css_text_entity) . "\">\n";
				//$ret .= "<div display:" . (empty($ck_print) ? "none" : "") . "\">\n";
				if ($obj->path != null) {
					if (empty($ck_print) && $this->ck_readonly == 0 && $obj->ck_edicao_global == 1) {
						$ret .= "<table border=0 cellspacing=0 cellpadding=0>\n";
						$ret .= "<tr>\n";
						$ret .= "<td><input id=\"ck_{$prefix}\" type=checkbox CHECKED></td><td>\n";
					}
					if (empty($ck_print) && $this->ck_readonly == 0) {
						$str = file_get_contents($obj->path);
						foreach ($obj->field as $f) {
							$field = $f->field;
							if ($f->type == "hidden") {
								$str = str_replace("[$field]", $this->get_field(["scope" => $scope, "ref" => $f, "prefix" => $prefix . "_", "ck_print" => $ck_print]), $str);
							} else {
								$temp = "";
								if (isset($f->comment_before)) $temp .= $f->comment_before . "\n";
								$temp .= $this->get_field(["scope" => $scope, "ref" => $f, "prefix" => $prefix . "_", "ck_print" => $ck_print]);
								$temp .= "<span id=\"comment_{$prefix}_{$field}\">";
								if (isset($f->comment)) $temp .= $f->comment;
								$temp .= "</span>";
								$str = str_replace("[$field]", $temp, $str);
								$str = str_replace("[preview-$field]", "", $str);
							}
						}
						$action_str = "removeClone('$prefix',this.id.replace('delete',''));";
						if (!empty($obj->cmd)) foreach ($obj->cmd as $cmd) $action_str .= "$cmd;";
						$delete_str  = "<input type=button id=\"delete\" onclick=\"$action_str\" value=\"[-]\" class=\"$this->css_button_remove\">";
						$delete_str .= "<input type=\"hidden\" name=\"id_{$prefix}\" id=\"id_{$prefix}\" value=0>\n";
						if (strpos($str, "[DELETE]") !== false)
							$str = str_replace("[DELETE]", $delete_str, $str);
						else
							$str = "<div style='float:right'>$delete_str</div>\n$str\n";
					} else {
						for ($j=0; $j<count($res_aux); $j++) {
							for ($k=0; $k<count($obj->field); $k++) {
								if ($obj->field[$k]->type != "hidden") {
									$field = $obj->field[$k]->field;
									$str = str_replace("[$field]", $this->get_field(["scope" => $scope, "ref" => $obj->field[$k], "res" => $res_aux[$j], "prefix" => $obj->rec_table["table"] . "_", "ck_print" => $ck_print]), $str);
								}
							}
						}
					} // if ($obj->path != null) {
					$ret .= "<div id=\"readroot_{$prefix}\" class=\"form-entity-root\" style=\"display:" . (empty($ck_print) ? "none" : "") . "\">\n";
					$ret .= $str;
					$ret .= "</div>\n";
					if (empty($ck_print) && $this->ck_readonly == 0 && $obj->ck_edicao_global == 1) {
						$ret .= "</td></tr></table>\n";
					}
				} else { // if ($obj->path != null) {
					$ret .= "<table border=0 cellspacing=\"$this->cellspacing\" cellpadding=\"$this->cellpadding\" class=\"$this->css_table\" " . ($obj->layout == "V" ? "width=\"100%\"" : "") . ">\n";
					if (empty($ck_print) || count($res_aux) > 0) {
						if ($obj->layout == "H") {
							if (!empty($obj->col_group_id)) {
								$ret .= "<tr id=\"grp_{$prefix}\" class=\"$this->css_label_entity\">\n";
								for ($j=0; $j<count($obj->field); $j++) {
									$grp = $obj->field[$j]->col_group;
									if (!empty($grp)) {
										$colspan = 0;
										for ($k=$j; $k<count($obj->field); $k++) {
											if ($obj->field[$k]->col_group == $grp) {
												$colspan++;
												if (!empty($grp["color"])) $obj->field[$k]->add_holder_prop("style", "background-color:" . $grp["color"]);
											} else {
												break;
											}
										}
										$j += $colspan-1;
										$ret .= "<td colspan=$colspan " . (!empty($grp["color"]) ? "style=\"border-top-left-radius:10px; border-top-right-radius:10px; background-color:" . $grp["color"] . "\"" : "") . ">" . $grp["label"] . "</td>\n";
									} else {
										$ret .= "<td></td>\n";
									}
								}
								$ret .= "</tr>\n";
							}
							$ret .= "<tr id=\"titulo_{$prefix}\">\n";
							if (empty($ck_print) && $this->ck_readonly == 0 && $obj->ck_edicao_global == 1) $ret .= "<td></td>\n";
							$c = 0;
							foreach ($obj->field as $j => $entObj) {
								if ($entObj->type != "hidden") {
									if (empty($entObj->field_group)) $c++;
									if ($j == 0 || 
										($entObj->label != $obj->field[$j-1]->label && (empty($entObj->field_group) || $entObj->field_group != $obj->field[$j-1]->field_group))) {
										$class = $this->css_label_entity;
										$ck_global = $this->ck_global_form == 0 && $entObj->type == "radio" ? 1 : 0;
										$prop_str = "";
										foreach ($entObj->title_prop as $ix => $val) {
											$prop_str .= ($prop_str != "" ? " " : "") . "$ix=\"$val\"";
										}
										if (!empty($entObj->field_group))
											$lbl = $entObj->field_group["label"];
										else
											$lbl = $entObj->label;
										$ret .= "<td " 
											. ($c > 1 ? "colspan=\"$c\"" : "") 
											. " class=\"$class\" $prop_str " 
											. (!empty($entObj->title) ? " title=\"$entObj->title\"" : "") 
											. ($ck_global == 1 ? " style=\"position:initial;\" onmouseover=\"this.getElementsByTagName('span')[0].style.display='inline';\" onmouseout=\"this.getElementsByTagName('span')[0].style.display='none';\"" : "") 
											. ($entObj->ck_req != 0 && strpos($lbl," ") === false ? "nowrap" : "") . ">";
										if ($ck_global == 1) {
											$ret .= "<span class=\"{$this->css_text_entity}\" style=\"position:absolute;display:none;margin-top:-20px;margin-left:5px;background-color:white;white-space:nowrap;padding:2px;\">";
											$clone = clone $entObj;
											$clone->prop["onclick"] = "ckAll('{$prefix}_" . $clone->field . "',this.value)";
											$ret .= $this->get_field(["scope" => $scope, "ref" => $clone, "prefix" => "global_{$prefix}_", "ck_print" => $ck_print]);
											$ret .= "</span>";
										}
										$ret .= $lbl;
										if ($entObj->ck_req != 0 && empty($ck_print)) {
											if (is_array($entObj->ck_req)) {
												if ($this->ck_entity_field($obj->field, $entObj->ck_req["field"]))
													$ck_req_default = $this->ck_condition($obj->field, $entObj->ck_req);
												else if ($this->modo == "update")
													$ck_req_default = $this->ck_condition($this->ref, $entObj->ck_req);
											} else {
												$ck_req_default = 1;
											}
											$ret .= " <span id=\"{$prefix}_req-$entObj->field\" " . ($ck_req_default == 0 ? "style=\"display:none;\"" : "") . ">" . $this->str_required . "</span>";
										}
										$ret .= "</td>\n";
										$c = 0;
									}
								}
							}
							$ret .= "</tr>\n";
							if (empty($ck_print) && $this->ck_readonly == 0) {
								$ret .= "<tr id=\"readroot_{$prefix}\" class=\"form-entity-root\" style=\"display:none\">\n";
								if (empty($ck_print) && $this->ck_readonly == 0 && $obj->ck_edicao_global == 1) 
									$ret .= "<td class={$this->css_text_entity}><input id=\"ck_{$prefix}\" type=checkbox CHECKED></td>\n";
								foreach ($obj->field as $k => $entObj) {
									$f = clone $entObj;
									if ($f->ck_readonly == 1) $f->set_readonly(0);
									$params = ["scope" => $scope, "ref" => $f, "prefix" => $prefix . "_", "ck_print" => $ck_print];
									if ($f->type == "hidden") {
										$ret .= $this->get_field($params);
									} else {
										$prop_str = "";
										foreach ($f->holder_prop as $ix => $val) {
											$prop_str .= ($prop_str != "" ? " " : "") . "$ix=\"$val\"";
										}
										if (empty($entObj->field_group) || $k == 0 || $entObj->field_group != $obj->field[$k-1]->field_group) 
											$ret .= "<td class=\"{$this->css_text_entity}\" $prop_str nowrap>\n";
										if (!empty($entObj->field_group)) {
											$display = $entObj->field_group["display"];
											if (is_array($entObj->ck_hidden) && $this->ck_condition($obj->field, $entObj->ck_hidden)) $display = "none";
											$ret .= "<div id=\"holder-" . $obj->prefix . "_" . $this->getFieldLbl($f->field) . "\" style=\"display:$display;\">\n";
										}
										$ret .= "<span id=\"comment_before_" . $obj->prefix . "_" . $this->getFieldLbl($f->field) . "\">" . (isset($f->comment_before) ? $f->comment_before : "") . "</span>\n";
										if (isset($f->ck_readonly) && $f->ck_readonly == 1) {
											$f->ck_readonly = 0;
											unset($f->prop["readonly"]);
										}
										$ret .= $this->get_field($params);
										$ret .= "<span id=\"comment_" . $obj->prefix . "_" . $this->getFieldLbl($f->field) . "\">" . (isset($f->comment) ? $f->comment : "") . "</span>";
										if (!empty($entObj->field_group)) 
											$ret .= "</div>\n";
										if (empty($entObj->field_group) || $k == count($obj->field)-1 || $entObj->field_group != $obj->field[$k+1]->field_group) 
											$ret .= "</td>\n";
									}
								}
								$action_str = "removeClone('$prefix',this.id.replace('{$prefix}_delete',''));";
								if (!empty($obj->cmd)) foreach ($obj->cmd as $cmd) $action_str .= "$cmd;";
								if (empty($ck_print) && $this->ck_readonly == 0) {
									$ret .= "<td nowrap class={$this->css_text_entity} width=30>\n";
									$ret .= "<input type=button id=\"{$prefix}_delete\" onclick=\"$action_str\" value=\"[-]\" class=\"$this->css_button_remove\">\n";
									$ret .= "<input type=hidden id=\"id_{$prefix}\" name=\"id_{$prefix}\" value=\"0\">\n";
									$ret .= "</td>\n";
									if (isset($obj->entity_duplicate_fields)) $ret .= "<td class=\"{$this->css_text_entity}\" width=30><input type=button id=\"{$prefix}_clone\" onclick=\"addCloneChild('{$prefix}', this.id.replace('{$prefix}_clone',''), new Array(" . $this->get_entity_duplicate_str($i, $prefix) . "))\" value=[+] class=\"$this->css_button_remove\"></td>\n";
								}
								$ret .= "</tr>\n";
							} else {
								for ($j=0; $j<count($res_aux); $j++) {
									$ret .= "<tr>\n";
									foreach ($obj->field as $k => $entObj) {
										if ($entObj->type != "hidden") {
											$prop_str = "";
											foreach ($entObj->holder_prop as $ix => $val) $prop_str .= ($prop_str != "" ? " " : "") . "$ix=\"$val\"";
											if (empty($entObj->field_group) || $k == 0 || $entObj->field_group != $obj->field[$k-1]->field_group) {
												$ret .= "<td class={$this->css_text_entity} $prop_str nowrap>\n";
											}
											if (!empty($entObj->field_group)) {
												$ret .= "<div style=\"display:" . $entObj->field_group["display"] . "\">\n";
											}
											$ret .= $this->get_field(["scope" => $scope, "ref" => $entObj, "res" => $res_aux[$j], "prefix" => $obj->table . "_", "ck_print" => $ck_print]);
											if (!empty($entObj->field_group)) {
												$ret .= "</div>\n";
											}
											if (empty($entObj->field_group) || $k == count($obj->field)-1 || $entObj->field_group != $obj->field[$k+1]->field_group) {
												$ret .= "</td>\n";
											}
										}
									}
									$ret .= "</tr>\n";
								}
							} // if ($obj->path != null) {
						} else if ($obj->layout == "V") {
							if (empty($ck_print) && $this->ck_readonly == 0) {
								$ret .= "<tr id=\"readroot_{$prefix}\" class=\"form-entity-root\" style=\"display:none\"><td>\n";
								$ret .= "<table cellspacing=\"$this->cellspacing\" cellpadding=\"$this->cellpadding\" class=\"$this->css_table\" width=\"100%\">\n";
								for ($j=0; $j<count($obj->field); $j++) {
									$ret .= "<tr>\n";
									if (empty($ck_print) && $this->ck_readonly == 0 && $obj->ck_edicao_global == 1) $ret .= "<td></td>\n";
									if ($obj->field[$j]->type == "hidden")
										$ret .= $this->get_field(["scope" => $scope, "ref" => $obj->field[$j], "prefix" => $prefix . "_", "ck_print" => $ck_print]);
									else {
										$ret .= "<td class=\"$this->css_label\" width=20%>\n";
										if ($obj->field[$j]->type == "checkbox") 
											$ret .= "&nbsp;";
										else
											$ret .= $obj->field[$j]->label;
										$ret .= "</td>\n";
										$ret .= "<td width=80% class=\"$this->css_text\" nowrap>\n";
										if (isset($obj->field[$j]->comment_before)) $ret .= $obj->field[$j]->comment_before . "\n";
										$ret .= $this->get_field(["scope" => $scope, "ref" => $obj->field[$j], "prefix" => $prefix . "_", "ck_print" => $ck_print]);
										if (isset($obj->field[$j]->comment)) $ret .= $obj->field[$j]->comment;
										$ret .= "</td>\n";
									}
									if ($j == 0 && empty($ck_print)) {
										$action_str = "removeClone('$prefix',this.id.replace('delete',''));";
										if (!empty($obj->cmd)) foreach ($obj->cmd as $cmd) $action_str .= "$cmd;";
										$ret .= "<td nowrap width=30>\n";
										$ret .= "<input type=button id=\"delete\" onclick=\"$action_str\" value=\"[-]\" class=\"$this->css_button_remove\">\n";
										$ret .= "<input type=\"hidden\" name=\"id_{$prefix}\" id=\"id_{$prefix}\" value=0>\n";
										$ret .= "</td>\n";
									}
									$ret .= "</tr>\n";
								}
								$ret .= "</table>\n";
								$ret .= "</td></tr>\n";
							} // if ($obj->path != null) {
						}
					}
				} // if ($obj->path != null) {
				if (($modo == "update" || $modo == "report") && (empty($ck_print) || count($res_aux) > 0)) {
					for ($j=0; $j<count($res_aux); $j++) {
						$ck_trava = 0;
						$a = [];
						if (!empty($obj->prevent_del)) $a = array_merge($a, $obj->prevent_del);
						if (!empty($obj->prevent_edit)) $a = array_merge($a, $obj->prevent_edit);
						foreach ($a as $ck) {
							$field = $this->getFieldLbl($ck["field"]);
							$val = $ck["val"];
							$op = $ck["op"];
							if ($op == "==" && $res_aux[$j][$field] == $val) $ck_trava = 1;
							else if ($op == "!=" && $res_aux[$j][$field] != $val) $ck_trava = 1;
							else if ($op == ">=" && $res_aux[$j][$field] >= $val) $ck_trava = 1;
							else if ($op == "<=" && $res_aux[$j][$field] <= $val) $ck_trava = 1;
							else if ($op == ">" && $res_aux[$j][$field] > $val) $ck_trava = 1;
							else if ($op == "<" && $res_aux[$j][$field] < $val) $ck_trava = 1;
						}
						if ($obj->path != null) {
							$ret .= "<span id=\"$prefix$j\" " . ($obj->path != null ? "style=\"padding-bottom:3px\"" : "") . ">\n";
							if (empty($ck_print) && $this->ck_readonly == 0 && $obj->ck_edicao_global == 1) {
								$ret .= "<table border=0 cellspacing=0 cellpadding=0>\n";
								$ret .= "<tr><td class=\"$this->css_text\" style=\"padding:0; border-top:5px white solid\"><input id=\"ck_{$prefix}{$j}\" name=\"ck_{$prefix}{$j}\" type=checkbox CHECKED><span class=normal>" . (is_numeric($res_aux[$j][$this->pk]) ? str_pad($res_aux[$j][$this->pk],7,'0',STR_PAD_LEFT) : $res_aux[$j][$this->pk]) . "</span></td></tr>\n";
								$ret .= "<tr><td>\n";
							}
							if (empty($ck_print) && $this->ck_readonly == 0) {
								$str = file_get_contents($obj->path);
								$str = preg_replace("/id=[a-zA-Z0-9_]+/", "\${0}".$j, $str);
								$str = preg_replace('/id="[a-zA-Z0-9_]+/', "\${0}".$j, $str);
								$str = preg_replace("/id='[a-zA-Z0-9_]+/", "\${0}".$j, $str);
								for ($k=0; $k<count($obj->field); $k++) {
									$field = $obj->field[$k]->field;
									if ($obj->field[$k]->type == "hidden") {
										$str = str_replace("[$field]", $this->get_field(["scope" => $scope, "ref" => $obj->field[$k], "res" => $res_aux[$j], "prefix" => $prefix . "_", "sufix" => $j, "ck_print" => $ck_print]), $str);
									} else {
										$temp = "";
										if (isset($obj->field[$k]->comment_before)) $temp .= $obj->field[$k]->comment_before . "\n";
										$temp .= $this->get_field(["scope" => $scope, "ref" => $obj->field[$k], "res" => $res_aux[$j], "prefix" => $prefix . "_", "sufix" => $j, "ck_print" => $ck_print]);
										$temp .= "<span id=\"comment_{$prefix}_{$field}{$j}\">";
										if (isset($obj->field[$k]->comment)) $temp .= $obj->field[$k]->comment;
										$temp .= "</span>";
										$str = str_replace("[$field]", $temp, $str);
										if ($obj->field[$k]->type == "date") {
											$str = str_replace("[preview-$field]", $res_aux[$j]["dia_$field"]."/".$res_aux[$j]["mes_$field"]."/".$res_aux[$j]["ano_$field"], $str);
										} else
											$str = str_replace("[preview-$field]", $res_aux[$j][$field], $str);
									}
								}
								$action_str = "removeClone('$prefix',this.id.replace('delete',''));";
								if (!empty($obj->cmd)) foreach ($obj->cmd as $cmd) $action_str .= "$cmd;";
								$delete_str  = "<input type=button id=\"delete{$j}\" onclick=\"$action_str\" value=\"[-]\" class=\"$this->css_button_remove\">";
								$id = $res_aux[$j][$obj->pk];
								$delete_str .= "<input type=\"hidden\" name=\"id_{$prefix}{$j}\" id=\"id_{$prefix}{$j}\" value=\"$id\">";
								if (strpos($str, "[DELETE]") !== false)
									$str = str_replace("[DELETE]", $delete_str, $str);
								else
									$str = "<div style='float:right'>$delete_str</div>\n$str\n";
							} else {
								$key_field = $obj->key_field;
								$str_field = "";
								foreach ($obj->field as $f) {
									$str_field .= ($str_field != "" ? ", " : "") . $f->field;
								}
								if (isset($obj->prevent_del)) {
									foreach ($obj->prevent_del as $ck) {
										if (strpos($str_field, $ck["field"]) !== false) $str_field .= ", " . $ck["field"];
									}
								}
								$qry = "SELECT id, $str_field FROM " . $obj->rec_table["table"] . " WHERE $key_field = " . $this->vars["id"];
								if ($this->debug == 1) $this->show_debug($qry,"?","relative",$ret);
								$_res_aux = nc_query($qry);
								for ($k=0; $k<count($_res_aux); $k++) {
									for ($l=0; $l<count($obj->field); $l++) {
										if ($obj->field[$l]->type != "hidden") {
											$field = $obj->field[$l]->field;
											$str = str_replace("[$field]", $this->get_field(["scope" => $scope, "ref" => $obj->field[$l], "res" => $_res_aux[$j], "prefix" => $obj->rec_table["table"] . "_", "sufix" => $j, "ck_print" => $ck_print]), $str);
										}
									}
								}
							} // if ($obj->path != null) {
							$ret .= $str;
							$ret .= "</span>\n";
						} else {
							//$ret .= "<table border=0 cellspacing=\"$this->cellspacing\" cellpadding=\"$this->cellpadding\" class=\"$this->css_table\">\n";
							if ($obj->layout == "H") {
								if (empty($ck_print) && $this->ck_readonly == 0) {
									$ret .= "<tr id=\"$prefix$j\">\n";
									if ($obj->ck_edicao_global == 1) $ret .= "<td class={$this->css_text_entity}><input id=\"ck_{$prefix}$j\" type=checkbox CHECKED></td>\n";
									foreach ($obj->field as $k => $entObj) {
										if ($entObj->type == "hidden") {
											$ret .= $this->get_field(["scope" => $scope, "ref" => $entObj, "res" => $res_aux[$j], "prefix" => $prefix . "_", "sufix" => $j, "ck_print" => $ck_print]);
										} else {
											$rowspan = 1;
											$ck_td = 1;
											if (isset($obj->entity_duplicate_fields)) {
												$field_parent = $obj->entity_duplicate_parent;
												if ($res_aux[$j][$field_parent] == $res_aux[$j]["id"]) { // parent
													$ck = 0;
													for ($l=0; $l<count($obj->entity_duplicate_fields); $l++) {
														if ($obj->entity_duplicate_fields[$l] == $entObj->field) { $ck = 1; break; }
													}
													if ($ck == 0) {
														for ($l=$j; $l<count($res_aux); $l++) {
															if ($res_aux[$l][$field_parent] != $res_aux[$j]["id"]) break;
														}
														$rowspan = $l-$j;
													}
												} else { // child
													$ck_td = 0;
													for ($l=0; $l<count($obj->entity_duplicate_fields); $l++) {
														if ($obj->entity_duplicate_fields[$l] == $entObj->field) { $ck_td = 1; break; }
													}
												}
											}
											if ($ck_td == 1) {
												$prop_str = "";
												foreach ($entObj->holder_prop as $ix => $val) {
													$prop_str .= ($prop_str != "" ? " " : "") . "$ix=\"$val\"";
												}
												if (empty($entObj->field_group) || $k == 0 || $entObj->field_group != $obj->field[$k-1]->field_group) 
													$ret .= "<td " . ($rowspan > 1 ? "rowspan=$rowspan" : "") . " class=\"{$this->css_text_entity}\" $prop_str nowrap>\n";
												if (!empty($entObj->field_group)) 
													$ret .= "<div style=\"display:" . $entObj->field_group["display"] . "\">\n";
												$ret .= "<span id=\"comment_before_" . $obj->prefix . "_" . $this->getFieldLbl($entObj->field) . "$j\">" . (!empty($entObj->comment_before) ? preg_replace("/(id|name)=[\"']{0,1}([a-zA-Z_]+)[\"']{0,1}/", "\${1}=\"\${2}$j\"", $entObj->comment_before) : "") . "</span>\n";
												$clone = clone $entObj;
												if (!empty($obj->prevent_edit)) {
													foreach ($obj->prevent_edit as $ck) {
														$field = $this->getFieldLbl($ck["field"]);
														$val = $ck["val"];
														$op = $ck["op"];
														if ($op == "==" && $res_aux[$j][$field] == $val) $clone->set_readonly();
														else if ($op == "!=" && $res_aux[$j][$field] != $val) $clone->set_readonly();
														else if ($op == ">=" && $res_aux[$j][$field] >= $val) $clone->set_readonly();
														else if ($op == "<=" && $res_aux[$j][$field] <= $val) $clone->set_readonly();
														else if ($op == ">" && $res_aux[$j][$field] > $val) $clone->set_readonly();
														else if ($op == "<" && $res_aux[$j][$field] < $val) $clone->set_readonly();
													}
												}
												$ret .= $this->get_field(["scope" => $scope, "ref" => $clone, "res" => $res_aux[$j], "prefix" => $prefix . "_", "sufix" => $j, "ck_print" => $ck_print]);
												$ret .= "<span id=\"comment_" . $obj->prefix . "_" . $this->getFieldLbl($entObj->field) . "$j\">" . (!empty($entObj->comment) ? preg_replace("/(id|name)=[\"']{0,1}([a-zA-Z_]+)[\"']{0,1}/", "\${1}=\"\${2}$j\"", $entObj->comment) : "") . "</span>";
												if (!empty($entObj->field_group)) 
													$ret .= "</div>\n";
												if (empty($entObj->field_group) || $k == count($obj->field)-1 || $entObj->field_group != $obj->field[$k+1]->field_group) 
													$ret .= "</td>\n";
											}
										}
									}
									$action_str = "removeClone('$prefix',this.id.replace('{$prefix}_delete',''));";
									if (!empty($obj->cmd)) foreach ($obj->cmd as $cmd) $action_str .= "$cmd;";
									if (is_array($obj->pk)) {
										$id = "";
										foreach ($obj->pk as $pk_field) {
											$val = (isset($res_aux[$j]["{$pk_field}_key"]) ? $res_aux[$j]["{$pk_field}_key"] : $res_aux[$j][$pk_field]);
											if ($val instanceof DateTime) $val = "date:" . $val->format("Y-m-d H:i:s");
											$id .= ($id != ""?";":"") . $val;
										}
									} else {
										$id = $res_aux[$j][$obj->pk_label];
									}
									$remItem = empty($ck_print) && $this->ck_readonly == 0 && $obj->ck_readonly == 0 && $obj->ck_history == 0;
									if ($remItem && !empty($obj->protectCmd)) {
										$protectCmd = $obj->protectCmd;
										$remItem = !$protectCmd($res_aux[$j]);
									}
									foreach ($obj->dependency as $d) {
										$t = $d["table"];
										if ($res_aux[$j]["total_$t"] > 0) $remItem = false;
									}
									$ret .= "<td class=\"{$this->css_text_entity}\" width=30>\n";
									if ($remItem) {
										$ret .= "<input type=button id=\"{$prefix}_delete{$j}\" onclick=\"$action_str\" value=\"[-]\" class=\"$this->css_button_remove\" " . ($ck_trava == 1 ? "style=\"background-color:silver;border-color:gray;\" disabled" : "") . ">\n";
									}
									$ret .= "<input type=\"hidden\" name=\"id_{$prefix}{$j}\" id=\"id_{$prefix}{$j}\" value=\"$id\">\n";
									if (!empty($obj->ck_upd)) {
										$src = [];
										foreach ($obj->field as $f) $src[$f->field] = isnull($res_aux[$j][$f->field],"");
										$ret .= "<input type=\"hidden\" name=\"src_{$prefix}{$j}\" id=\"src_{$prefix}{$j}\" value=\"" . base64_encode(json_encode(array_map("utf8_encode", $src))) . "\">\n";
									}
									if (isset($obj->entity_duplicate_fields) && $res_aux[$j][$obj->entity_duplicate_parent] != $res_aux[$j]["id"]) $ret .= "<input type=\"hidden\" name=\"{$prefix}_ck_child{$j}\" id=\"{$prefix}_ck_child{$j}\" value=\"1\">\n";
									$ret .= "</td>\n";
									if (isset($obj->entity_duplicate_fields) && $res_aux[$j][$obj->entity_duplicate_parent] == $res_aux[$j]["id"]) $ret .= "<td class=\"{$this->css_text_entity}\" width=30><input type=button id=\"{$prefix}_clone{$j}\" onclick=\"addCloneChild('{$prefix}', this.id.replace('{$prefix}_clone',''), new Array(" . $this->get_entity_duplicate_str($i, $prefix) . "))\" value=[+] class=\"$this->css_button_remove\"></td>\n";
									/* ck_history depracated 19/09/2023
									} else if ($obj->ck_history == 1) {
										$ret .= "<td>\n";
										$ret .= "<input type=\"hidden\" name=\"id_{$prefix}{$j}\" id=\"id_{$prefix}{$j}\" value=\"$id\">\n";
										if (isset($obj->entity_duplicate_fields) && $res_aux[$j][$obj->entity_duplicate_parent] != $res_aux[$j]["id"]) $ret .= "<input type=\"hidden\" name=\"{$prefix}_ck_child{$j}\" id=\"{$prefix}_ck_child{$j}\" value=\"1\">\n";
										$ret .= "</td>\n";
									*/
									$ret .= "</tr>\n";
								}
							} else if ($obj->layout == "V") {
								$ret .= "<tr id=\"$prefix$j\"><td>\n";
								$ret .= "<table cellspacing=\"$this->cellspacing\" cellpadding=\"$this->cellpadding\" class=\"$this->css_table\" width=\"100%\">\n";
								for ($k=0; $k<count($obj->field); $k++) {
									$ent_obj = $obj->field[$k];
									if ($obj->type == "hidden") {
										$ret .= $this->get_field(["scope" => $scope, "ref" => $ent_obj, "res" => $res_aux[$j], "prefix" => $prefix . "_", "sufix" => $j, "ck_print" => $ck_print]);
									} else {
										$ret .= "<tr>\n";
										//$ret .= "<td width=20% class=\"$this->css_label\">\n";
										$ret .= "<td width=20% class=\"$this->css_label\" " . ($ent_obj->ck_req == 1 ? "style=\"text-indent:-20px!important;padding-left:25px!important;\"" : "") . " nowrap>";
										if (is_array($ent_obj->ck_req))
											$ck_req = $this->ck_condition($ref, $ent_obj->ck_req);
										else
											$ck_req = $ent_obj->ck_req;
										if ($ck_print == 1 || $scope != "form") $ck_req = 0;
										$ret .= ($ent_obj->type != "checkbox" ? ($ck_req == 1 ? "$this->str_required " : "") . $ent_obj->label . ":" : "&nbsp;");
										$ret .= "</td>\n";
										$ret .= "<td width=80% class=\"$this->css_text\" nowrap>\n";
										if (isset($ent_obj->comment_before)) $ret .= $ent_obj->comment_before . "\n";
										$ret .= $this->get_field(["scope" => $scope, "ref" => $ent_obj, "res" => $res_aux[$j], "prefix" => $prefix . "_", "sufix" => $j, "ck_print" => $ck_print]);
										if (isset($ent_obj->comment)) $ret .= $ent_obj->comment;
										$ret .= "</td>\n";
										if ($k == 0 && empty($ck_print)) {
											$action_str = "removeClone('$prefix',this.id.replace('delete',''));";
											if (!empty($ent_obj->cmd)) foreach ($ent_obj->cmd as $cmd) $action_str .= "$cmd;";
											$ret .= "<td nowrap width=30>\n";
											$ret .= "<input type=button id=\"delete$j\" onclick=\"$action_str\" value=\"[-]\" class=\"$this->css_button_remove\">\n";
											if (is_array($obj->pk)) {
												$id = "";
												for ($k=0; $k<count($obj->pk); $k++) {
													$id .= ($k>0?";":"") . $res_aux[$j][$obj->pk[$k]];
												}
											} else {
												$id = $res_aux[$j][$obj->pk];
											}
											$ret .= "<input type=\"hidden\" name=\"id_{$prefix}{$j}\" id=\"id_{$prefix}{$j}\" value=\"$id\">\n";
											$ret .= "</td>\n";
										}
										$ret .= "</tr>\n";
									}
								}
								$ret .= "</table>\n";
								$ret .= "</td></tr>\n";
							}
							//$ret .= "</table>\n";
						}
					} // for ($j=0;$j<count($res_aux);$j++) {
				}
				if ($obj->path != null) {
					$ret .= "<span id=\"anchor_{$prefix}\" style=\"display:none\"></span>\n";
				} else {
					$ret .= "<tr id=\"anchor_{$prefix}\" style=\"display:none\"></tr>\n";
					$ret .= "</table>\n";
				}
				if (empty($ck_print)) {
					$ret .= "<input type=\"hidden\" name=\"count_{$prefix}\" id=\"count_{$prefix}\" value=" . ($modo == "insert" ? 0 : count($res_aux)) . ">\n";
					$ret .= "<input type=\"hidden\" name=\"delete_list_{$prefix}\" id=\"delete_list_{$prefix}\" value=0>\n";
				}
				$ret .= "</td></tr>\n";
			} else if ($obj->type == "entity" && $obj->rel == "NXN") { // } if 1XN
				$prefix = $obj->prefix;
				$label = $obj->src_label;
				$cols = $obj->cols;
				if (is_array($obj->qry)) {
					$ck = [];
					if ($this->modo == "update") {
						$qry = "SELECT $obj->src_field FROM $obj->table WHERE $obj->key_field = " . $this->res_upd[$this->pk];
						foreach (nc_query($qry) as $r) $ck[$r[$obj->src_field]] = 1;
					}
					$res_aux = [];
					foreach ($obj->qry as $ix => $val) $res_aux[] = [ $obj->src_field => $ix, "label" => $val, "ck" => (int)isset($ck[$ix]) ];
					$ck_group = 0;
				} else {
					$qry = $obj->qry;
					preg_match_all("[\[([a-zA-Z0-9_])+\]]", $qry, $match);
					foreach ($match[0] as $m) {
						$ix = substr($m,1,-1);
						if (isset($this->res_upd)) {
							if ($ix == "id" && count($this->tab_ref) > 0 && $this->tab_ref[$obj->tab_id]["table"] != null)
								$val = $this->res_upd["id_" . $this->tab_ref[$obj->tab_id]["table"]];
							else
								$val = $this->res_upd[$ix];
						} else if ($ix == "id")
							$val = 0;
						else 
							$val = "";
						$qry = str_replace($m, (string)$val, $qry);
					}
					if (isset($obj->group)) {
						$entity_group = $obj->group;
						$ck_group = 1;
					} else {
						$ck_group = 0;
					}
					if ($this->debug == 1) $this->show_debug($qry,"E");
					$res_aux = nc_query($qry);
				}
				$ret .= "<tr id=\"hdr-$prefix\" " . (empty($ck_print) && $ck_hidden == 1 ? "style=display:none" : "") . ">\n";
				$ret .= "<td colspan=2 class=\"$this->css_label\">";
				if (isset($this->ref[$i]["entity_ckall"])) {
					$list = "0";
					for ($j=0; $j<count($res_aux); $j++) {
						$list .= ",'$prefix$j'";
					}
					$action_str = "ckAll_NXN(this.checked, new Array($list))";
					$ret .= "<input type=checkbox onclick=\"$action_str\">";
				}
				$ret .= $obj->label . ":"; 
				$ret .= "</td>\n";
				$ret .= "</tr>\n";
				$ret .= "<tr id=\"body-$prefix\" " . (empty($ck_print) && $ck_hidden == 1 ? "style=display:none" : "") . ">\n";
				$ret .= "<td class=\"{$this->css_text_entity}\" colspan=2>\n";
				$ret .= "<table width=100% cellspacing=\"$this->cellspacing\" cellpadding=\"$this->cellpadding\" class=\"$this->css_table\">\n";
				$c = 0;
				$group_bak = "";
				for ($j=0; $j<count($res_aux); $j++) {
					if (empty($ck_print) && $this->ck_readonly == 0 || $res_aux[$j]["ck"] == 1) {
						if ($ck_group == 1 && 
							$obj->group != "" && 
							 ($j==0 || 
							 ($obj->ck_split_selected == 1 && $res_aux[$j]["ck"] != $res_aux[$j-1]["ck"]) || 
							  $res_aux[$j][$entity_group] != $group_bak)) {
							$group_bak = $res_aux[$j][$entity_group];
							$list = "0";
							$ck_group_selected = 0;
							for ($k=$j; $k<count($res_aux); $k++) {
								if ($res_aux[$k]["ck"] == 1) $ck_group_selected = 1;
								if ($res_aux[$j][$entity_group] != $res_aux[$k][$entity_group] ||
									($obj->ck_collapse == 0 && $res_aux[$j]["ck"] != $res_aux[$k]["ck"])) break;
								$list .= ",'$prefix$k'";
							}
							$action_str = "ckAll_NXN(this.checked, new Array($list), true)";
							if (isset($obj->prop["onclick"])) $action_str .= ";" . $obj->prop["onclick"];
							$ret .= "<tr onclick=\"chDisplayNXN([$list])\" style=\"cursor:pointer;\">\n";
							$ret .= "<td class=\"$this->css_text\" colspan=$cols>\n";
							if (empty($ck_print) && $this->ck_readonly == 0) 
								$ret .= "<input type=checkbox onclick=\"$action_str\">" . $res_aux[$j][$entity_group];
							else
								$ret .= $res_aux[$j][$entity_group];
							if ($res_aux[$j]["ck"] == 0 && $modo == "update" && $obj->group != "" && $obj->ck_split_selected == 1) $ret .= " ($this->lang_label_no_select_NXN)\n";
							$ret .= "</td>\n";
							$ret .= "</tr>\n";
							$c = 0;
						}
						if ($obj->ck_collapse == 1)
							$_ck_hidden = $ck_group_selected == 1 ? 0 : 1;
						else
							$_ck_hidden = (($modo == "update" || $modo == "report") && $res_aux[$j]["ck"] == 0 && $ck_group == 1 && $obj->group != "" ? 1 : 0);
						if ($c%$cols == 0) $ret .= "<tr " . ($_ck_hidden == 1 ? "style=display:none" : "") . " id=\"table_$prefix$j\">\n";
						$ret .= "<td class=" . ($ck_print == 1 ? "\"$this->css_text_print\"" : "\"{$this->css_text_entity}\"") . " width=" . round(100/$cols) . "%>\n";
						$ck_trava = 0;
						if (isset($obj->prevent_del)) {
							for ($k=0; $k<count($obj->prevent_del); $k++) {
								$field = $ck["field"];
								$val = $ck["val"];
								if ($res_aux[$j][$field] == $val) $ck_trava = 1;
							}
						}
						$prop_str = "";
						$onclick = "";
						if (!empty($obj->restriction) && $res_aux[$j][$obj->restriction] != "") {
							$prop_str .= " grp=\"" . $res_aux[$j][$obj->restriction] . "\"";
							$onclick .= "unCheck(this);";
						}
						if (isset($obj->prop["onclick"])) {
							$onclick .= $obj->prop["onclick"];
						}
						if ($res_aux[$j]["ck"] == 1) {
							$prop_str .= " CHECKED";
						} 
						if ($ck_trava == 1 || $ck_print == 1) {
							$prop_str .= " disabled";
						} else if (!empty($onclick)) {
							$prop_str .= " onclick=\"$onclick\"";
						}
						$_field = $obj->field[0]->field;
						if (!is_array($_field)) $_field = [ $_field ];
						if ($res_aux[$j][$_field[0]] == "") 
							$prop_str .= " disabled";
						$ret .= "<label><input type=checkbox name=\"{$prefix}{$j}\" id=\"ck_$prefix$j\" value=1 $prop_str><span id=\"comment_$prefix$j\">" . $res_aux[$j][$label] . "</span></label>\n";
						if ($ck_trava == 1 && $res_aux[$j]["ck"] == 1) $ret .= "<input type=\"hidden\" name={$prefix}{$j} value=1>\n";
						$ret .= "<input type=\"hidden\" name=\"{$prefix}_bak{$j}\" value=\"" . $res_aux[$j]["ck"] . "\">\n";
						foreach ($obj->field as $f) {
							$_field = $f->field;
							if (!is_array($_field)) $_field = array($_field);
							$_label = ""; $_val = "";
							foreach ($_field as $_ix) {
								$_label .= ($_label != "" ? "_" : "") . $_ix;
								$_val .= ($_val != "" ? "," : "") . $res_aux[$j][$_ix];
							}
							$ret .= "<input type=\"hidden\" name=\"{$prefix}_{$_label}{$j}\" id=\"{$prefix}_{$_label}{$j}\" value=\"$_val\">\n";
							//if ($obj->ck_dependent == 1) break;
						}
						if ($obj->ck_dependent == 1 && count($obj->field) == 2) {
							// for ($k=1; $k<count($obj->field); $k++) {
							foreach (array_slice($obj->field,1) as $f) {
								if ($f->type != "hidden") 
									$ret .= "<span id=\"comment_before_{$prefix}_" . $f->field . "$j\">" . (isset($f->comment_before) ? $f->comment_before : "") . "</span>\n";
								//if (($modo == "update" || $modo == "report") && $this->step == 2)
									$ret .= $this->get_field(["scope" => $scope, "ref" => $f, "res" => $res_aux[$j], "prefix" => "{$prefix}_", "sufix" => $j, "ck_print" => $ck_print]);
								//else
								//	$ret .= $this->get_field(["scope" => $scope, "ref" => $f, "prefix" => "{$prefix}_", "sufix" => $j, "ck_print" => $ck_print]);
								if ($f->type != "hidden") 
									$ret .= "<span id=\"comment_{$prefix}_" . $f->field . "$j\">" . (isset($f->comment) ? $f->comment : "") . "</span>\n";
							}
						}
						$ret .= "</td>\n";
						if ($obj->ck_dependent == 1 && count($obj->field) > 2) {
							// for ($k=1; $k<count($obj->field); $k++) {
							foreach (array_slice($obj->field,1) as $f) {
								$ret .= "<td class=" . ($ck_print == 1 ? "\"$this->css_text_print\"" : "\"{$this->css_text_entity}\"") . " nowrap>\n";
								$ret .= "<span id=\"comment_before_{$prefix}_" . $f["field"] . "$j\">" . (isset($f->comment_before) ? $f->comment_before : "") . "</span>\n";
								//if (($modo == "update" || $modo == "report") && $this->step == 2)
									$ret .= $this->get_field(["scope" => $scope, "ref" => $f, "res" => $res_aux[$j], "prefix" => "{$prefix}_", "sufix" => $j, "ck_print" => $ck_print]);
								//else
								//	$ret .= $this->get_field(["scope" => $scope, "ref" => $f, null, "{$prefix}_", $j, "ck_print" => $ck_print]);
								$ret .= "<span id=\"comment_{$prefix}_" . $f["field"] . "$j\">" . (isset($f->comment) ? $f->comment : "") . "</span>\n";
								$ret .= "</td>\n";
							}
						}
						if ($j == count($res_aux)-1 || $c%$cols == $cols-1) $ret .= "</tr>\n";
						$c++;
					}
				} // for ($j=0; $j<count($res_aux); $j++) {
				if ($c == 0) {
					$ret .= "<tr>\n";
					$ret .= "<td class=\"" . ($ck_print == 1 ? $this->css_text_print : $this->css_text_entity) . "\" colspan=$cols>\n";
					if ($ck_print == 1) 
						$ret .= "Nenhum evento selecionado\n";
					else if (empty($ck_print) && $this->ck_readonly == 0) 
						$ret .= $this->lang_label_no_events_1XN;
					$ret .= "</td>\n";
					$ret .= "</tr>\n";
				}
				$ret .= "</table>\n";
				$ret .= "<input type=\"hidden\" name=\"count_{$prefix}\" id=\"count_{$prefix}\" value=" . count($res_aux) . ">\n";
				$ret .= "</td></tr>\n";
			} else if ($ref[$i]["type"] != "dbfield") {
				$ix = $this->getFieldLbl($obj->field);
				/* Disabled 08/02/2024, parser set on get_field()
				if (!empty($this->res_upd) && isset($obj->parser)) $this->res_upd[$ix] = ($obj->parser)(isset($this->res_upd[$ix]) ? $this->res_upd[$ix] : false, $this->res_upd);
				*/
				if ($ref[$i]["type"] == "hidden" && $ref[$i]["field_group"] == false) {
					if (!empty($this->res_upd))
						$ret .= $this->get_field(["scope" => $scope, "ref" => $obj, "res" => $this->res_upd, "ck_print" => $ck_print]);
					else 
						$ret .= $this->get_field(["scope" => $scope, "ref" => $obj, "ck_print" => $ck_print]);
				} else {
					// nao mostra td label se todos os campos do tab sao checkbox
					$ck_td_label = 1;
					if (count($this->tab_ref) > 0) {
						$ck_td_label = 0;
						for ($j=0; $j<count($ref); $j++) {
							if ($ref[$j]["tab_id"] == $ref[$i]["tab_id"] &&
								$ref[$j]["type"] != "checkbox") {
								$ck_td_label = 1;
								break;
							}
						}
					}
					if ($ref[$i]["type"] == "html") {
						$ret .= "<tr id=\"holder-" . $this->getFieldLbl($obj->field) . "\" " . (empty($ck_print) && $ck_hidden == 1 ? "style=display:none" : "") . "><td class=$this->css_text_entity>\n";
						$ret .= $ref[$i]["comment"];
						$ret .= "</td></tr>\n";
					} else {
						$ret .= "<tr id=\"holder-" . $this->getFieldLbl($obj->field) . "\" " . ($ck_hidden == 1 ? "style=display:none" : "") . ">\n";
						if ($ck_td_label == 1) {
							$str = ($ref[$i]["field_group"] == false ? $ref[$i]["label"] : $ref[$i]["field_group"]["label"]);
							if ($ck_print == 1 || $scope != "form") $ref[$i]["ck_req"] = 0;
							$ck_req = $ref[$i]["object"]->ck_req;
							if (is_array($ck_req)) {
								$ck_req_default = $this->ck_condition($ref, $ck_req);
								$ck_req = 1;
							} else {
								$ck_req_default = 1;
							}
							$ret .= "<td width=20% class=\"$this->css_label\" " . ($this->ck_nowrap_title == 1 || ($ck_req == 1 && empty($ck_print)) ? "nowrap" : "") . ">";
							if ($ref[$i]["type"] != "checkbox" || !empty($ref[$i]["field_group"])) {
								if (strip_tags($str) != "") $str .= ":";
								if ($ck_req == 1 && empty($ck_print)) {
									$ret .= "<div id=\"req-".$ref[$i]["field"]."\" style=\"display:" . ($ck_req_default == 0 ? "none" : "inline-block") . ";\">$this->str_required</div> ";
									$ret .= "<div style=\"display:inline-block;vertical-align:top;white-space:normal;\">$str</div>";
								} else {
									$ret .= $str;
								}
							} else {
								$ret .= "&nbsp;";
							}
							if ($ref[$i]["type"] == "publisher") $ret .= "<br><br><br><label><input class=\"form1\" name=\"ck" . $ref[$i]["field"] . "\" id=\"ck" . $ref[$i]["field"] . "\" value=\"" . $ref[$i]["field"] . "\" type=\"checkbox\" onclick=\"setMode(this.value,this.checked);\">HTML</label>";
							$ret .= "</td>\n";
						}
						$ret .= "<td width=80% class=\"$this->css_text\">\n";
						// add entity if entity label equal to entity field
						if ($i < count($ref)-1) {
							$nextObj = $ref[$i+1]["object"];
							if ($nextObj->type == "entity" && 
								$nextObj->label == $obj->label) {
								$prefix = $nextObj->prefix;							
								if ($ref[$i+1]["ck_multiple_entry"] == 1)
									$action_str = "promptClone('$prefix')";
								else
									$action_str = "clone=addClone('$prefix');remClass(clone,'form-entity-root');document.getElementById('holder-$prefix').style.display=''";
								if (isset($ref[$i+1]["prop"]["onclick"])) $action_str .= ";" . $ref[$i]["prop"]["onclick"];
								$ret .= "<a class=\"$this->css_link_general\" href=\"javascript:void(null)\" onclick=\"$action_str\" style=\"float:right\">[+]</a>\n";
							}
						}
						// add field
						for ($j=$i; $j<count($ref); $j++) {
							$grp = $ref[$j]["field_group"];
							if ($this->ck_hidden($ref, $j) == 1) $display = "none";
							else if ($grp != false) $display = $grp["display"];
							else $display = "inline-block";
							$obj = $ref[$j]["object"];
							if ($grp != false) $ret .= "<div id=\"grp-holder-" . $this->getFieldLbl($obj->field) . "\" style=\"display:$display;padding:2px;\">\n";
							if ($grp != false && $grp["display"] == "table-row") $ret .= "<div style=\"display:table-cell\">\n";
							if ($grp != false && $obj->type != "checkbox" && ($j == 0 || $ref[$j]["label"] != $ref[$j-1]["label"])) {
								$ret .= ($grp["display"] == "table-row" || $obj->label != $grp["label"] ? $obj->label . ($obj->label != "" && $obj->label != "/" && substr(trim($obj->label), -1) != "?" ? ":" : "") . " " : "");
							}
							if ($grp != false && $grp["display"] == "table-row") $ret .= "</div>\n<div style=\"display:table-cell;padding:2px;\">\n";
							$ret .= "<span id=\"comment_before_" . $this->getFieldLbl($obj->field) . "\">" . (isset($obj->comment_before) ? $obj->comment_before : "") . "</span>\n";
							// if (($modo == "update" || $modo == "report") && $this->step == 2)
							if (isset($this->res_upd))
								$ret .= $this->get_field(["scope" => $scope, "ref" => $obj, "res" => $this->res_upd, "ck_print" => $ck_print]);
							else if (($modo == "update" || $modo == "report") && $this->step == 1 && count($this->ref_filter) > 0)
								$ret .= $this->get_field(["scope" => $scope, "ref" => $obj, "res" => $this->vars, "ck_print" => $ck_print]);
							else
								$ret .= $this->get_field(["scope" => $scope, "ref" => $obj, "ck_print" => $ck_print]);
							$comment = isset($obj->comment) ? $obj->comment : "";
							if ($comment instanceof Closure) $comment = $comment($modo == "update" ? $this->res_upd : []);
							$ret .= "<span id=\"comment_" . $this->getFieldLbl($obj->field) . "\">$comment</span>\n";
							if ($grp != false && $grp["display"] == "table-row") $ret .= "</div>\n";
							if ($grp != false) $ret .= "</div>\n";
							if ($j == count($ref)-1) 
								break;
							else if ($grp == false) 
								break;
							else if ($ref[$j+1]["field_group"] == false) 
								break;
							else if ($grp["id"] != $ref[$j+1]["field_group"]["id"] || gettype($grp["label"]) != gettype($ref[$j+1]["field_group"]["label"]))
								break;
						}
						$i = $j;
						$ret .= "</td></tr>\n";
					}
				}
			}
			if (isset($ref[$i]["ck_separator"]) && $ref[$i]["ck_separator"] == 1) {
				$ret .= "<tr class=\"$this->css_form_separator\"><td colspan=2 class=\"$this->css_form_separator\"></td></tr>\n";
			}
			if (empty($ck_print) && $scope == "form" && ($i == count($ref)-1 || $obj->tab_id != $ref[$i+1]["object"]->tab_id)) {
				$ret .= "</tbody>\n";
			}
		} // for ($i=0; $i<count($ref); $i++) {
		if ($this->ck_readonly == 0 && ($this->modo != "report" || $this->step == 0)) {
			if (empty($ck_print)) {
				$ret .= "<tr class=\"$this->css_form_separator\"><td colspan=2 class=\"$this->css_form_separator\"></td></tr>\n";
				$ret .= "<tr><td colspan=2 align=\"$this->button_align\">\n";
				$ret .= $this->get_form_button($scope);
				if ($this->use_print_back == 1) $ret .= $this->get_form_back();
				$ret .= "</td></tr>\n";
			} else if ($this->use_print_close == 1 || $this->use_print_button == 1) {
				$ret .= "<tr class=\"$this->css_form_separator\"><td colspan=2 class=\"$this->css_form_separator\"></td></tr>\n";
				$ret .= "<tr><td colspan=2 align=\"$this->button_align\">\n";
				if ($this->use_print_close == 1) $ret .= $this->get_form_close();
				if ($this->use_print_button == 1) $ret .= $this->get_form_print();
				$ret .= "</td></tr>\n";
			}
		}
		$ret .= "</table>\n";
		return $ret;
	}
	//private function get_form_custom($scope, $ref, $tpl, $title="") {
	private function get_form_custom($params) {
		foreach ($params as $ix => $val) $$ix = $val;
		if (!isset($scope)) exit("Invalid \$params for get_form_default([" . implode(", ",array_keys($params)) . "]), missing 'scope'");
		if (!isset($ref)) exit("Invalid \$params for get_form_default([" . implode(", ",array_keys($params)) . "]), missing 'ref'");
		if (!isset($tpl)) exit("Invalid \$params for get_form_default([" . implode(", ",array_keys($params)) . "]), missing 'tpl'");
		if (!isset($ck_print)) $ck_print = $this->ck_print;
		if (!isset($title)) $title = "";
		if (isset($this->vars["modo"]) && ($this->vars["modo"] == "update" || $this->vars["modo"] == "insert"))
			$modo = $this->vars["modo"];
		else
			$modo = $this->modo;
		if (substr($tpl,-3) == "php") {
			ob_start();
			include $tpl;
			$htm = ob_get_clean();
		} else {
			$tpl = file($tpl);
			$htm = "";
			for ($i=0; $i<count($tpl); $i++) {
				$htm .= $tpl[$i];
			}
		}
		for ($i=0; $i<count($ref); $i++) {
			if ($ref[$i]["type"] != "entity") {
				$field = $ref[$i]["field"];
				if (stripos($htm, "[$field]") === false) {
					exit("Field $field not found on template");
				}
				$temp = "";
				$temp .= "<span id=\"comment_before_{$field}\">";
				if (isset($ref[$i]["comment_before"])) $temp .= $ref[$i]["comment_before"] . "\n";
				$temp .= "</span>\n";
				if ($modo == "update" && $this->step == 2) {
					$temp .= $this->get_field(["scope" => $scope, "ref" => $ref[$i], "res" => $this->res_upd]);
					if ($ref[$i]["type"] == "date")
						$htm = str_replace("[preview-$field]", $this->res_upd["dia_$field"]."/".$this->res_upd["mes_$field"]."/".$this->res_upd["ano_$field"], $htm);
					else
						$htm = str_ireplace("[preview-$field]", $this->res_upd[$field], $htm);
				} else if (($modo == "update" || $modo == "report") && $this->step == 1 && count($this->ref_filter) > 0) {
					$temp .= $this->get_field(["scope" => $scope, "ref" => $ref[$i], "res" => $this->vars]);
					$htm = str_ireplace("[preview-$field]", $this->vars[$field], $htm);
				} else {
					$temp .= $this->get_field(["scope" => $scope, "ref" => $ref[$i] ]);
					if (isset($ref[$i]["default"]) && $ref[$i]["default"] != "")
						$htm = str_ireplace("[preview-$field]", $ref[$i]["default"], $htm);
					else
						$htm = str_ireplace("[preview-$field]", "", $htm);
				}
				$temp .= "<span id=\"comment_{$field}\">";
				if (isset($ref[$i]["comment"])) $temp .= $ref[$i]["comment"];
				$temp .= "</span>";
				$htm = str_ireplace("[$field]", $temp, $htm);
			}
		}
		if (isset($this->tpl_val)) {
			$keys = array_keys($this->tpl_val);
			for ($i=0; $i<count($keys); $i++) {
				$htm = str_ireplace("[".$keys[$i]."]", $this->tpl_val[$keys[$i]], $htm);
			}
		}
		$htm = str_ireplace("[title]", $title, $htm);
		$htm = str_ireplace("[submit]", $this->get_form_button($scope), $htm);
		if ($this->use_print_back == 1) $htm = str_ireplace("[back]", $this->get_form_back(), $htm);
		return $this->get_js() . $htm;
	}
	private function build_return_pk() {
		if (count($this->ck_return_field) == 0) {
			$val = $this->vars["ck_return_pk"];
		} else {
			$str = "";
			foreach ($this->ck_return_field as $field) {
				$str .= ($str != "" ? "," : "") . $field;
			}
			$qry = "SELECT $str FROM $this->table WHERE $this->pk IN (" . $this->vars["ck_return_pk"] . ")";
			$res = nc_query($qry);
			$val = "";
			for ($i=0; $i<count($res); $i++) {
				foreach ($this->ck_return_field as $field) {
					$field = $this->getFieldLbl($field);
					$val .= ($val != "" ? "," : "") . $res[$i][$field];
				}
			}
		}
		$msg = ($this->ck_return_entity == "default" ? $this->ent : $this->ck_return_entity) . ":<br><br>";
		if (is_array($this->ck_return_url)) {
			$msg .= "<b>$val</b>";
			foreach ($this->ck_return_url as $label => $url) {
				$url = str_replace("[id]", $this->vars["ck_return_pk"], $url);
				$msg .= "<li><a href=\"$url\" target=\"_blank\" class=\"$this->css_link_general\">$label</a>";
			}
		} else {
			$url = str_replace("[id]", $this->vars["ck_return_pk"], $this->ck_return_url);
			$msg .= "<li><a href=\"$url\" target=\"_blank\" class=\"$this->css_link_general\">$val</a>";
		}
		echo "<script type=\"text/javascript\">\n";
		echo "_div = document.createElement('div');\n";
		echo "_div.style.padding = '10px';\n";
		echo "_div.innerHTML = '$msg';\n";
		echo "_popup = new popupObj(200,200);\n";
		echo "_popup.setTitle('$this->lang_return_pk');\n";
		echo "_popup.setContent(_div);\n";
		echo "_popup.build();\n";
		echo "</script>\n";
	}
	public function exec_before_list($cmd) {
		$this->before_list_cmd = $cmd;
	}
	private function build_list() {
		$this->builder = "list";
		$this->get_list();
		if ((!empty($this->res_list) && ($this->modo == "report" || $this->pageby)) ||
			 !empty($this->vars["_ck_filter"]) ||
			 !empty($this->total_field) ||
			 !empty($this->total_sum) ||
			 !empty($this->ck_display_total)) {
			foreach ($this->hdr_total as $field) {
				/* foreach ($this->label as $lbl) {
					if ($this->getFieldLbl($lbl->field) == $field) {
						// find parser
						break;
					}
				} */
				$hdr = [];
				foreach ($this->res_list as $r) {
					if (!isset($hdr[$r[$field]])) $hdr[$r[$field]] = 0;
					$hdr[$r[$field]]++;
				}
				arsort($hdr);
				$str = "";
				foreach ($hdr as $h => $val) {
					$str .= "<b>$h:</b> $val; ";
					$this->add_header("<b>$h:</b> $val");
				}
				// $this->add_header($str);
			}
			if ($this->pageby) {
				$total = $this->res_tot[0]["total"];
			} else if (!empty($this->total_sum)) {
				$total = 0;
				foreach ($this->res_list as $r) {
					$total += $r[$this->total_sum];
				}
			} else if (!empty($this->total_field)) {
				$total = [];
				if (!is_array($this->total_field)) $this->total_field = preg_split("/(?![^()]*\)),/", $this->total_field);
				foreach ($this->res_list as $r) {
					$ck = "";
					for ($i=0; $i<count($this->total_field); $i++) {
						if ($i>0) $ck .= "|";
						$ck .= $r[$this->getFieldLbl($this->total_field[$i])];
					}
					$total[$ck] = $ck;
				}
				$total = count($total);
			} else {
				$total = count($this->res_list);
			}
			if ($total == 0) 
				$this->add_header($this->lang_not_found . ($this->modo == "delete" ? " (que não esteja relacionado em outras tabelas)" : ""));
			else if ($this->total_label) 
				$this->add_header("<b>$this->total_label:</b> $total");
		}
		// Define global antes de executar setup_field()
		$global = [];
		for ($i=0; $i<count($this->label); $i++) {
			if ($this->label[$i]["ck_update"] == 1 && $this->label[$i]["ck_update_global"] == 1) {
				$global[] = clone $this->ref_list[$this->label[$i]["object_id"]];
			}
		}
		if (isset($this->title)) $title = $this->title;
		else if ($this->modo == "delete") $title = "$this->lang_title_delete $this->ent";
		else if ($this->modo == "update") $title = "$this->lang_title_update $this->ent";
		else if ($this->modo == "complete" || $this->modo == "updatedelete") $title = "$this->lang_title_complete $this->ent";
		else if ($this->modo == "report") $title = "$this->lang_title_report $this->ent";
		echo "<!DOCTYPE html>\n";
		echo "<HTML>\n";
		echo "<HEAD>\n";
		echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=ISO-8859-1\">\n";
		echo "<TITLE>" . strip_tags($title) . "</TITLE>\n";
		echo "<link rel='STYLESHEET' type='text/css' href='" . $this->css_path . "'>\n";
		for ($i=0; $i<count($this->css_ref); $i++) {
			echo "<link rel='STYLESHEET' type='text/css' href='" . $this->css_ref[$i] . "'>\n";
		}
		if (!empty($this->cssStyle)) {
			echo "<style type=\"text/css\">\n<!--\n";
			foreach ($this->cssStyle["list"] as $line) {
				echo $line . "\n";
			}
			echo "-->\n</style>\n";
		}
		echo $this->get_js();
		echo "<script type=\"text/javascript\">\n";
		echo "<!--- \n";
		if ($this->ck_reorder == 1) {
			echo "window.addEventListener('load', function() {\n";
			echo "	var tblAdm = new setupOrder('title-cls_form',true);\n";
			echo "}, false);\n";
		}
		if ($this->list_form) {
			if ($this->list_form == "custom") {
				echo "function setAll(val,field) {\n";
				echo "	var field = document.formulario[field+'[]'];\n";
				echo "	for (var i=0; i<field.length; i++) {\n";
				echo "		if (!field[i].disabled) field[i].checked = val;\n";
				echo "	}\n";
				echo "}\n";
			} else {
				echo "function setAll(val,field,group_key,cmd) {\n";
				echo "	var form = document.formulario;\n";
				echo "	for (var i=0; i<form.count.value; i++) {\n";
				echo "		if (form[field+i] && \n";
				echo "			!form[field+i].disabled && \n";
				echo "			(!group_key || group_key == form[\"group_key\"+i].value)) {\n";
				echo "			if (form[field+i].type == \"text\") {\n";
				echo "				form[field+i].value = val;\n";
				echo "			} else if (form[field+i].type == \"checkbox\") {\n";
				echo "				form[field+i].checked = val;\n";
				echo "			} else if (form[field+i].type == \"hidden\") {\n"; // check for radio_checkbox
				echo "				form[field+i].value = val;\n";
				echo "				var c = 0, ini = 'A'.charCodeAt(0);\n";
				echo "				while (form[field+String.fromCharCode(ini+c)+i]) {\n";
				echo "					form[field+String.fromCharCode(ini+c)+i].checked = (form[field+String.fromCharCode(ini+c)+i].value == val); c++;\n";
				echo "				}\n";
				echo "			} else if (form[field+i].length) {\n"; // radio
				echo "				for (var j=0; j<form[field+i].length; j++) {\n";
				echo "					var f = form[field+i][j];\n";
				echo "					if (f.value == val) f.checked = true;\n";
				echo "				}\n";
				echo "			}\n";
				echo "			if (cmd) cmd(i);\n";
				echo "		}\n";
				echo "	}\n";
				echo "}\n";
				echo "blockHide = [];\n";
				echo "function displayPrompt(id, ck, ck_timing) {\n";
				echo "	var div = \"div_prompt_\" + id;\n";
				echo "	var td = \"td_\" + id;\n";
				echo "	if (ck) blockHide[div] = true;\n";
				echo "	if (!ck && ck_timing && blockHide[div]) return false;\n";
				echo "	document.getElementById(div).style.display = ck ? \"\" : \"none\";\n";
				echo "	document.getElementById(div).style.width = document.getElementById(td).offsetWidth;\n";
				echo "	document.getElementById(div).style.left = findPosX(document.getElementById(td));\n";
				echo "	var t = findPosY(document.getElementById(td)) - document.getElementById(div).offsetHeight;\n";
				echo "	if (t >= 0)\n";
				echo "		document.getElementById(div).style.top = t;\n";
				echo "	else\n";
				echo "		document.getElementById(div).style.top = 0;\n";
				echo "}\n";
			}
			foreach ($this->ref_list as $dst) {
				$rules = [];
				foreach (["ck_req", "ck_readonly", "ck_disabled", "ck_hidden", "ck_empty", "ck_value"] as $rule) {
					if (isset($dst->$rule) && is_array($dst->$rule)) {
						foreach ($this->ref_list as $src) {
							$rules[] = $this->setup_field($src, $dst, $rule, "list-js");
						}
					}
				}
				if (!empty($rules)) {
					echo "function setup" . ucfirst($this->getFieldLbl($dst->field)) . "(ix) {\n";
					echo "\tvar form = document.formulario;\n";
					foreach ($rules as $rule) {
						if (!empty($rule)) echo "\t" . trim(str_replace("document.formulario","form",$rule)) . "\n";
					}
					echo "}\n";
				}
			}
		}
		echo $this->get_js_form("list", $this->ref_list);
		if (!empty($this->onload_action["list"])) {
			echo "window.addEventListener('load', function() {\n";
			foreach ($this->onload_action["list"] as $cmd) {
				echo "\t$cmd\n";
			}
			echo "}, false);\n";
		}
		if (!empty($this->res_json)) {
			$list = [];
			foreach ($this->res_list as $res) {
				$r = [ "id" => $res["id"] ];
				foreach ($this->res_json as $ix) $r[$ix] = utf8_encode($res[$ix]);
				$list[] = $r;
			}
			echo "_globals = {\n";
			echo "	list: " . json_encode($list) . "\n";
			echo "};\n";
		}
		echo "// --->\n";
		echo "</script>\n";
		echo "</HEAD>\n";
		if ($this->modo == "update" && 
			$this->ck_return_pk == 1 && 
			isset($this->vars["ck_return_pk"]) &&
			strpos($this->ck_return_use,"U") !== false) {
			echo $this->build_return_pk();
		}
		echo "<body bgcolor=White>\n";
		if ($this->list_form) {
			echo "<form name=\"formulario\" action=\"" . array_values($this->list_form_trigger)[0] . "\" method=\"POST\">\n";
		}
		// Preenchimento global
		foreach ($global as $obj) {
			$field = $this->getFieldLbl($obj->field);
			$rules = "";
			foreach (["ck_req", "ck_readonly", "ck_disabled", "ck_hidden"] as $rule) {
				foreach ($this->ref_list as $dst) {
					if (isset($dst->$rule) && is_array($dst->$rule)) {
						$rules .= "setup" . ucfirst($this->getFieldLbl($dst->field)) . "(ix);";
					}
				}
			}
			if ($obj->type == "checkbox")
				$args = [ "this.checked" ];
			else if ($obj->type == "radio_checkbox")
				$args = [ "this.checked ? this.value : ''" ];
			else
				$args = [ "this.value" ];
			if ($obj->type == "radio_checkbox")
				$args[] = "'".$this->getFieldLbl($obj->field)."'";
			else
				$args[] = "this.id.replace('global_','')";
			if (!empty($rules)) {
				$args[] = "false";
				$args[] = "function(ix) { $rules }";
			}
			if ($obj->type == "text" || $obj->type == "date") {
				$obj->prop["onchange"] = "setAll(" . implode(",",$args) . ");" . (isset($obj->prop["onchange"]) ? $obj->prop["onchange"] : "");
			} else if ($obj->type == "checkbox" || $obj->type == "radio" || $obj->type == "radio_checkbox") {
				$obj->prop["onclick"] = "setAll(" . implode(",",$args) . ");" . (isset($obj->prop["onclick"]) ? $obj->prop["onclick"] : "");
			}
			echo "<div id=\"div_prompt_$field\" style=\"position:absolute;left:0;top:0;z-index:2;display:none;\"\n";
			echo "	onMouseOver=\"displayPrompt('$field',1)\"\n";
			echo "	onMouseOut=\"displayPrompt('$field',0)\">\n";
			echo "<table class=\"$this->css_table\" width=100% height=100% border=0 cellspacing=0 cellpadding=3>\n";
			echo "<tr><td height=100% class=\"$this->css_list_title\" style=\"text-align:" . $this->label[$i]["alignment"] . "\">\n";
			echo $obj->comment_before;
			echo  $this->get_field(["scope" => "list", "ref" => $obj, "prefix" => $prefix = "global_"]);
			echo $obj->comment;
			echo "</td></tr>\n";
			echo "</table>\n";
			echo "</div>\n";
		}
		echo "<table border=0 id=\"tbl-cls_form\" width=\"$this->list_width\" cellspacing=\"$this->cellspacing\" cellpadding=\"$this->cellpadding\" class=\"$this->css_table\">\n";
		if ($this->ck_recursive != 0)
			$colspan = 2;
		else if (is_array($this->label)) {
			$colspan = 0;
			for ($i=0; $i<count($this->label); $i++) {
				if ($this->label[$i]["ck_hidden"] == 0) $colspan++;
			}
		} else
			$colspan = 1;
		if ($this->modo == "complete" || $this->modo == "updatedelete") 
			$colspan++;
		if (isset($this->delete_mode) && $this->delete_mode != "checkbox")
			$colspan += count($this->link_ref);
		if ($this->modo == "delete") {
			for ($i=0; $i<count($this->dependency); $i++) {
				$this->header[count($this->header)] = "$this->lang_delete_dependency \"" . $this->dependency[$i]["label"] . "\"";
			}
		}
		if (($this->modo == "updatedelete" || $this->modo == "complete") && count($this->ref_filter) > 0) $this->ck_display_title == 0;
		$ck_paging = ($this->pageby && 
				$this->res_tot[0]["total"] > $this->pageby &&
				strpos($this->pageby_pos, "U") !== false ? 1 : 0);
		if ($ck_paging == 0 && count($this->header) == 0) $this->ck_header = 0;
		if ($ck_paging == 1) $this->ck_header = 1;
		if ($this->ck_display_title == 1) {
			echo $this->get_list_title($title, $colspan);
			if ($this->ck_header == 1) echo "<tr class=\"$this->css_list_separator\"><td colspan=$colspan class=\"$this->css_list_separator\"></td></tr>\n";
		}
		if ($this->ck_header == 1) {
			echo "<tr><td colspan=$colspan class=\"{$this->css_list_header}\">\n";
			if ($ck_paging == 1) {
				echo "<span " . (!empty($this->header) ? "style=\"float:right\"" : "") . ">" . $this->get_paging($this->res_tot[0]["total"]) . "</span>";
			}
			foreach ($this->header as $hdr) {
				if (is_array($hdr)) {
					$src = $hdr[1];
					$hdr = $hdr[0];
				} else {
					$src = false;
				}
				if (gettype($hdr) == "object") {
					echo $hdr($this->res_list) . "<br>\n";
				} else {
					preg_match_all("/\[[a-zA-Z0-9_:]+\]/", $hdr, $match);
					if (count($match[0]) > 0) {
						$found = substr($match[0][0],1,-1);
						$parts = explode(":",$found);
						$field = $parts[count($parts)-1];
						if (count($parts) > 1) {
							$val = 0;
							for ($j=0; $j<count($this->res_list); $j++) {
								if ($parts[0] == "sum") $val += $this->res_list[$j][$field];
							}
						} else {
							$val = $this->res_list[0][$field];
						}
						$hdr = str_replace("[$found]", $val, $hdr);
					}
					if (!empty($hdr)) {
						if ($src) {
							$params = "";
							foreach ($_REQUEST as $ix => $val) {
								if ($ix != $src && $ix != "urlKey") {
									if (is_array($val))
										foreach ($val as $v) $params .= "&{$ix}[]=$v";
									else
										$params .= "&$ix=$val";
								}
							}
							$hdr = "$hdr <a href='?$params' class='rem-header'>&#10006;</a>";
						}
						echo $hdr . "<br>\n";
					}
				}
			}
			echo "</td></tr>\n";
		}
		if (count($this->graph) > 0)
			$this->build_graph_list($colspan); // demo
		else
			$this->build_default_list($colspan);
		echo "</table>\n";
		if ($this->list_form) {
			echo "</form>\n";
		}
		if ($this->ck_xls == 1) {
			echo "<form name=\"export_xls\" method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"html\" id=\"html\">\n";
			echo "<input type=\"hidden\" name=\"modo\" id=\"modo\" value=\"xls\">\n";
			echo "<input type=\"hidden\" name=\"filename\" id=\"filename\" value=\"report-" . str_replace(" ","_",$this->ent) . ".xls\">\n";
			echo "</form>\n";
		}
		echo "</body></html>\n";
	}
	private function get_list() {
		if (is_array($this->table)) {
			$this->res_list = $this->table;
		} else {
			// add filter condition
			$ref = $this->cls_report || count($this->ref_filter) > 0 ? $this->ref_filter : $this->ref;
			if (count($ref) > 0) {
				$ck_session = 0;
				if (isset($this->vars["get_session_filter"])) {
					$this->vars = $_SESSION["vars_bak"];
					session_unregister("vars_bak");
					$ck_session = 1;
				}
				$this->feed_filter_prop();
				for ($i=0; $i<count($ref); $i++) {
					$obj = $ref[$i]["object"];
					if ($obj->type != "entity") {
						$field = $this->getFieldLbl($obj->field);
						if (!empty($obj->qry_index))
							$qry_field = $obj->qry_index;
						else if (isset($obj->qry_field) && strpos($obj->qry_field, ".") !== false)
							$qry_field = $obj->qry_field;
						else if (isset($obj->qry_field) && isset($obj->rec_table["table"]) && $obj->rec_table["table"] != null)
							$qry_field = $obj->rec_table["table"] . "." . $obj->qry_field;
						else if (isset($obj->qry_field))
							$qry_field = $this->table . "." . $obj->qry_field;
						else if (!is_array($obj->field) && strpos($obj->field, ".") === false)
							$qry_field = $this->table . "." . $obj->field;
						else if (!is_array($obj->field)) 
							$qry_field = $this->getFieldIx($obj->field);
						else if (is_array($obj->field)) {
							$qry_field = $obj->field;
							for ($j=0; $j<count($qry_field); $j++) {
								if (strpos($qry_field[$j], ".") === false) $qry_field[$j] = $this->table . "." . $qry_field[$j];
							}
						}
						if ($obj->type == "text" && !isset($obj->mask))
							$obj->type = "findtext";
						if ($obj->type == "date" && count($this->ref_filter) == 0)
							$obj->type = "daterange";
						if (isset($obj->mask)) {
							$val = "";
							$c = 0;
							$mask = $obj->mask;
							for ($j=0; $j<count($mask); $j++) {
								if (is_numeric($mask[$j])) {
									if (isset($this->vars[$field.$c])) {
										$val .= $this->vars[$field.$c];
										$var_bak = $this->vars[$field.$c];
									} else {
										$var_bak = "";
									}
									$c++;
								} else if ($obj->write_mask == 1 && $var_bak != "")
									$val .= $mask[$j];
							}
							if ($val == "" && isset($this->vars[$field])) $val = $this->vars[$field];
						} else if ($obj->type != "entity" && isset($this->vars[$field])) {
							$val = $this->vars[$field];
						} else {
							$val = "";
						}
						if ($val != "" && $obj->ck_qry == 1) {
							$val = $this->get_val($ref[$i]);
							if ($obj->type == "date" || $obj->type == "daterange") {
								$this->add_condition("$qry_field BETWEEN " . $val["ini"] . " AND " . $val["fim"]);
								$this->add_header("<b>" . $obj->label . "</b>: " . $this->vars["dia_{$field}_ini"] . "/" . $this->vars["mes_{$field}_ini"] . "/" . $this->vars["ano_{$field}_ini"] . " a " . $this->vars["dia_{$field}_fim"] . "/" . $this->vars["mes_{$field}_fim"] . "/" . $this->vars["ano_{$field}_fim"]); 
							} else if ($obj->type == "month" || $obj->type == "monthrange") {
								if (is_array($val)) {										
									$this->add_condition("$qry_field BETWEEN " . $val["ini"] . " AND " . $val["fim"]); 
									$this->add_header("<b>" . $obj->label . "</b>: " . str_pad($this->vars["mes_{$field}_ini"],2,'0',STR_PAD_LEFT) . "/" . $this->vars["ano_{$field}_ini"] . " a " . str_pad($this->vars["mes_{$field}_fim"],2,'0',STR_PAD_LEFT) . "/" . $this->vars["ano_{$field}_fim"]); 
								} else {
									$this->add_condition("$qry_field = $val"); 
									$this->add_header("<b>" . $obj->label . "</b>: " . substr($this->vars[$field],4,2) . "/" . substr($this->vars[$field],0,4));
								}
							} else if ($obj->type == "findtext") {
								if (isset($obj->keyword_ref) && count($obj->keyword_ref) > 0) {
									$str = "";
									for ($j=0; $j<count($obj->keyword_ref); $j++) {
										$keyword_ref = $obj->keyword_ref[$j];
										if (isset($this->vars["ck_".$this->getFieldLbl($keyword_ref)]))
											$str .= ($str != "" ? " OR " : "") . $this->getFieldIx($keyword_ref) . " LIKE " . ($this->db == "MYSQL" ? "_utf8'%" . str_replace("'","",(is_numeric(substr($val,1,-1)) ? (substr($val,1,-1)*1) : $val)) . "%'COLLATE utf8_unicode_ci" : "'%" . str_replace("'","",$val) . "%'");
									}
									$this->add_condition($str); 
								} else {
									$str = "";
									foreach (is_array($qry_field) ? $qry_field : array($qry_field) as $_f) {
										$str .= ($str != "" ? " OR " : "") . $this->getFieldIx($_f) . " LIKE " . ($this->db == "MYSQL" ? "_utf8'%" . str_replace("'","",(is_numeric(substr($val,1,-1)) ? (substr($val,1,-1)*1) : $val)) . "%'COLLATE utf8_unicode_ci" : "'%" . str_replace("'","",$val) . "%'"); 
									}
									$this->add_condition($str); 
									if (empty($this->group_key) && empty($this->group)) $_like_to_order[$this->getFieldLbl($qry_field)] = "CASE WHEN $str THEN 1 ELSE 2 END";
								}
								$this->add_header("<b>" . $obj->label . "</b>: " . $this->vars[$field]);
							} else {
								if (is_array($qry_field)) {
									$temp = explode(";",$val);
									for ($j=0; $j<count($qry_field); $j++) {
										$this->add_condition($this->getFieldIx($qry_field[$j]) . " = '" . $temp[$j] . "'");
									}
								} else if (is_array($val)) {
									$this->add_condition("$qry_field IN (" . implode(",",$val) . ")");
								} else if ($obj->type == "radio_checkbox") {
									$this->add_condition("$qry_field IN ('" . str_replace(",","','",$this->vars[$field]) . "')");
								} else {
									$this->add_condition("$qry_field = $val");
								}
								if (isset($obj->ref_qry)) {
									$res_temp = $this->get_ref_query($obj->ref_qry, $this->vars);
									$this->add_header("<b>" . $obj->label . "</b>: " . (count($res_temp) > 0 ? $res_temp[0][$obj->ref_qry_label] : $val)); 
								} else if (isset($obj->qry) && $this->vars[$field] != "") {
									$sql = $obj->qry["sql"];
									if (strpos($sql, "ORDER BY") !== false)
										$sql = substr($sql, 0, strpos($sql, "ORDER BY"));
									$sql = "SELECT * FROM ($sql) rs WHERE ";
									if (is_array($obj->qry["ix"])) {
										$temp = explode(";", $this->vars[$field]);
										for($j=0; $j<count($obj->qry["ix"]); $j++) {
											$sql .= ($j>0?" AND ":"") . $obj->qry["ix"][$j] . " = '" . $temp[$j] . "'";
										}
									} else {
										$sql .= $obj->qry["ix"] . " = '" . $this->vars[$field] . "'";
									}
									preg_match_all("[\[([a-zA-Z0-9_])+\]]", $sql, $match);
									for ($j=0; $j<count($match[0]); $j++) {
										$val = isset($this->vars[substr($match[0][$j],1,-1)]) ? $this->vars[substr($match[0][$j],1,-1)] : "";
										$sql = str_replace($match[0][$j], $val, $sql);
									}
									if ($this->debug == 1) $this->show_debug($sql);
									$res_temp = nc_query($sql);
									$this->add_header("<b>" . $obj->label . "</b>: " . (count($res_temp) > 0 ? $res_temp[0][$obj->qry["label"]] : $this->vars[$field])); 
								} else if (isset($obj->list)) {
									if ($obj->type == "radio_checkbox") {
										$str = "";
										foreach (explode(",", $this->vars[$field]) as $val) $str .= ($str != "" ? ", " : "") . $obj->list["vals"][$val];
									} else {
										$str = $obj->list["vals"][$this->vars[$field]];
										if (is_array($str)) {
											$o = $str["object"];
											$str = $o->label;
											if ($o->type == "dropdown") {
												$val = $this->vars[$this->getFieldLbl($o->field)];
												if ($val != "") {
													$sql = $o->qry["sql"];
													if (strpos($sql, "ORDER BY") !== false) $sql = substr($sql, 0, strpos($sql, "ORDER BY"));
													$qry = "SELECT " . $o->qry["label"] . " AS label 
														FROM ($sql) rs 
														WHERE " . $this->getFieldLbl($o->qry["ix"]) . " = '$val'";
													$res = nc_query($qry);
													$str .= " - " . $res[0]["label"];
													$this->add_condition("$o->field = '$val'"); 
												}
											}
										}
									}
									$this->add_header("<b>" . $obj->label . "</b>: " . $str); 
								} else if ($obj->type == "checkbox") {
									$this->add_header($obj->label); 
								} else if ($obj->type != "hidden") {
									if (isset($this->vars[$field])) {
										$this->add_header("<b>" . $obj->label . "</b>: " . $this->vars[$field]); 
									} else if (isset($this->vars[$field."0"])) {
										$c = 0; $str = "";
										while (isset($this->vars[$field.$c])) {
											$str .= $this->vars[$field.$c];
											$c++;
										}
										$this->add_header("<b>" . $obj->label . "</b>: " . $str); 
									}
								}
							}
						}
						if (isset($this->setup_complete) &&
							$this->setup_complete == "linked" && 
							$ck_session == 0)
							$_SESSION["vars_bak"] = $this->vars;
					}
				}
			}
			$group_key_str = "";
			$group_str = "";
			$order_str = "";
			$where_str = "";
			$join_str = "";
			$join_str_count = "";
			$label_str = "";
			$found_labels = [];
			// TABLE
			$temp = explode(" ", $this->table);
			$qry_table = $temp[count($temp)-1];
			// ORDER BY
			if (isset($_like_to_order)) {
				foreach ($_like_to_order as $ix => $expression) {
					$label_str .= "\t$expression AS search_$ix,\n";
					$order_str .= "\tsearch_$ix,\n";
				}
			}
			for ($i=0; $i<count($this->group_key); $i++) {
				if ($this->list_modo == "custom") {
					$temp = explode(".",$this->group_key[$i]["field"]);
					$label = $temp[count($temp)-1];
				} else if ($this->list_modo == "auto") {
					$label = "grp$i";
				}
				if (isset($this->group_key[$i]["color"])) $group_key_str .= "\t" . $this->group_key[$i]["color"] . ",\n";
				$group_key_str .= "\t" . $this->group_key[$i]["field"] . " AS $label,\n";
				if (substr($this->group_key[$i]["order"],0,3) != "grp") $group_key_str .= "\t" . $this->group_key[$i]["order"] . " AS order_$label,\n";
				if (count($this->order) == 0) $order_str .= "\t" . $this->group_key[$i]["order"] . " " . $this->group_key[$i]["direction"] . ",\n";
			}
			foreach ($this->order as $i => $order) {
				if (!$this->pageby) {
					$ck = 0;
					foreach ($this->label as $label) {
						if ($order["field"] == $this->getFieldIx($label["field"]) ||
							$order["field"] == $this->getFieldLbl($label["field"])) {
							$ck = 1;
							break;
						}
					}
					if ($ck == 0) $label_str .= "\t" . $order["field"] . " AS order$i,\n";
				}
				$order_str .= "\t" . $order["field"] . " " . $order["direction"] . ",\n";
			}
			// GROUP BY
			foreach ($this->group as $group) {
				$group_str .= ($group_str != "" ? ",\n" : "") . "\t" . $group["field"];
			}
			// LABELS
			$labels = [];
			foreach ($this->label as $label) {
				$labels[] = $this->getFieldLbl($label["field"]);
			}
			foreach ($this->label as $label) {
				$field = $label["field"];
				if ($label["ck_update"] == 1 && isset($label["object_id"])) {
					$obj = $this->ref_list[$label["object_id"]];
					if (!empty($obj->field_qry)) $field = $obj->field_qry;
				}
				if (!empty($field) && !in_array($this->getFieldLbl($field), $found_labels) && !($field instanceof Closure)) {
					$label_str .= "\t$field,\n";
					$found_labels[] = $this->getFieldLbl($field);
				}
				if ($label["ck_update"] == 1) {
					if (is_array($label["update_key"]) &&
						$label["update_key"] != $this->pk) {
						foreach ($label["update_key"] as $f) {
							if (strpos($f, ".") === false) $f = $label["table"] . ".$f";
							if (!in_array($this->getFieldLbl($f), $found_labels)) {
								$label_str .= "\t$f,\n";
								$found_labels[] = $f;
							}
						}
					}
					if ($label["type"] == "date" || $label["type"] == "datetime") {
						$field_label = $this->getFieldLbl($label["field"]);
						$field_index = $this->getFieldIx($label["field"]);
						$label_str .= "\tDAY($field_index) AS dia_$field_label, MONTH($field_index) AS mes_$field_label, YEAR($field_index) AS ano_$field_label,\n";
						if ($label["type"] == "datetime")
							$label_str .= "\tDATEPART(hh, $field_index) AS hor_$field_label, DATEPART(n, $field_index) AS min_$field_label,\n";
					}
				}
				if ($label["orderby"] != null) {
					$field_index = $label["orderby"];
				} else {
					if ($this->ck_recursive != 0 || $this->pageby) {
						$field_index = $this->getFieldLbl($label["field"]);
					} else {
						$field_index = $this->getFieldIx($label["field"]);
					}
				}
				if (count($this->order) == 0 &&
					!is_numeric(trim($field_index)) && 
					strtoupper(substr($field_index, 0, 6)) != "COUNT(" && 
					strtoupper(substr($field_index, 0, 4)) != "SUM(" && 
					strtoupper(substr($field_index, 0, 4)) != "MAX(" && 
					strtoupper(substr($field_index, 0, 4)) != "MIN(" && 
					strtoupper(substr($field_index, 0, 4)) != "AVG(" &&
					($this->ck_active == 0 || $field_index != $this->active_field)) {
					$order_str .= "\t$field_index,\n";
				}
				if (isset($label["link"])) {
					foreach ($label["link"] as $link) {
						if (is_string($link["url"])) {
							preg_match_all("/\[([a-zA-Z0-9\(\)_.,+ ])*\]/", $link["url"], $match);
							foreach ($match[0] as $found) {
								$found = substr($found,1,-1);
								if ($found != "" && $found != "id" && $found != "row" && 
									!in_array($this->getFieldLbl($found), $labels) &&
									!in_array($this->getFieldLbl($found), $found_labels)) {
									$temp = explode(",", $found);
									$str = (strpos($temp[0], ".") == 0 ? $this->table."." : "") . $temp[0];
									$label_str .= "\t$str,\n";
									$found_labels[] = $this->getFieldLbl($str);
								}
							}
						}
					}
				}
				if (isset($label["object_id"])) {
					$obj = $this->ref_list[$label["object_id"]];
					$rules = [];
					if (is_array($obj->ck_disabled)) $rules[] = $obj->ck_disabled;
					if (is_array($obj->ck_readonly)) $rules[] = $obj->ck_readonly;
					foreach ($rules as $rule) {
						if (is_array($rule) && !in_array($this->getFieldLbl($rule["field"]), $found_labels)) {
							$temp = explode(",", $rule["field"]);
							$str = (strpos($temp[0], ".") == 0 ? $this->table."." : "") . $temp[0];
							$label_str .= "\n$str,\n";
						}
					}
				}
			}
			// PK (must be read after labels in case pk is declared on $this->label)
			$pk_str = ""; $pk_str_full = "";
			if (!empty($this->pk)) {
				$pk = is_array($this->pk) ? $this->pk : [$this->pk];
				foreach ($pk as $field) {
					preg_match("/[a-z0-9_]+/i", $field, $match);
					if ($match[0] == $field) $field = $qry_table . "." . $field;
					$pk_str_full .= ($pk_str_full != "" ? ", " : "") . $this->getFieldIx($field);
					if (!in_array($this->getFieldLbl($field), $found_labels)) {
						$pk_str .= ($pk_str != "" ? ", " : "") . $field;
						$found_labels[] = $this->getFieldLbl($field);
					}
				}
			}
			if (!empty($pk_str)) $pk_str = "\t$pk_str\n";
			foreach ($this->list_row as $row) { 
				if (gettype($row["field"]) == "string") $label_str .= "\t" . $row["field"] . ",\n";
			}
			foreach ($this->color_ref as $color) {
				if (gettype($color["param"]) == "string") $label_str .= "\t" . $color["param"] . ",\n";
			}
			foreach ($this->link_ref as $link) {
				//preg_match_all("/\[([a-zA-Z0-9_.,])*\]/", $link["url"], $match);
				if (!($link["url"] instanceof Closure)) {
					preg_match_all("/\[.*?\]/", $link["url"], $match);
					foreach ($match[0] as $found) {
						$found = substr($found,1,-1);
						$found = explode(",",$found)[0];
						if ($found != "" && $found != "row" && !in_array($this->getFieldLbl($found), $found_labels)) {
							if (preg_match("/^[a-zA-Z0-9_]+,[0-9]+$/i", $found)) $found = substr($found, 0, strpos($found,",")); // verifica formato [field,(int)length]
							if (preg_match("/^[a-zA-Z0-9_]+$/i", $found)) $found = $this->table . ".$found";
							$label_str .= "\t$found,\n";
							$found_labels[] = $this->getFieldLbl($found);
						}
					}
				}
				foreach ($link["conditions"] as $condition) {
					if (!($condition instanceof Closure) && !in_array($this->getFieldLbl($condition["param"]), $found_labels)) {
						$label_str .= "\t" . $condition["param"] . ",\n";
						$found_labels[] = $this->getFieldLbl($condition["param"]);
					}
				}
			}
			if (!empty($this->document_name_list) && is_string($this->document_name_list)) {
				preg_match_all("/\[.*?\]/", $this->document_name_list, $match);
				foreach ($match[0] as $found) {
					$found = substr($found,1,-1);
					if ($found != "" && $found != "id" && $found != "row" && !in_array($this->getFieldLbl($found), $found_labels)) {
						if (preg_match("/^[a-zA-Z0-9_]+,[0-9]+$/i", $found)) $found = substr($found, 0, strpos($found,",")); // verifica formato [field,(int)length]
						if (preg_match("/^[a-zA-Z0-9_]+$/i", $found)) $found = $this->table . ".$found";
						$label_str .= "\t$found,\n";
						$found_labels[] = $this->getFieldLbl($found);
					}
				}
			}
			if ($this->ck_collapse == 1 && $this->db == "MSSQL") {
				for ($i=0; $i<count($this->group_key); $i++) {
					$str = "";
					for ($j=0; $j<=$i; $j++) {
						$str .= ($str != "" ? "," : "") . $this->group_key[$j]["field"];
					}
					if ($this->ck_active == 1) {
						$str .= ($str != "" ? "," : "") . $this->active_field;
					}
					$label_str .= "\tCOUNT(*) OVER(PARTITION BY $str) AS total_grp$i,\n";
				}
			}
			foreach ($this->related as $r) {
				if (strpos($r["use"],"L") !== false) $join_str .= $r["sql"] . "\n";
				if (strpos($r["use"],"C") !== false) $join_str_count .= $r["sql"] . "\n";
			}
			$ck_delete_str = "";
			if ($this->modo == "delete" || $this->modo == "complete" || $this->modo == "updatedelete") {
				for ($i=0; $i<count($this->dependency); $i++) {
					$table = $this->dependency[$i]["table"];
					if (strpos($this->dependency[$i]["table"], "SELECT") !== false) {
						$table = "($table) rs$i";
						$table_id = "rs$i";
					} else if (strpos($this->dependency[$i]["table"], " ") !== false)
						$table_id = trim(substr($this->dependency[$i]["table"], strpos($this->dependency[$i]["table"], " ")));
					else 
						$table_id = $table;
					$ix = $this->dependency[$i]["ix"];
					if ($this->ck_recursive == 0) {
						$join_str .= "LEFT JOIN (SELECT DISTINCT $ix FROM $table) rs$i ON rs$i.$ix = $this->table.$this->pk\n";
						if ($this->modo == "delete") 
							$where_str .= "\trs$i.$ix IS NULL AND\n";
						else
							$ck_delete_str .= "WHEN rs$i.$ix IS NOT NULL THEN 0\n";
					} else if ($this->ck_recursive != 0) {
						$where_str .= "\t$this->table.$this->pk NOT IN (SELECT $ix FROM $table WHERE $ix IS NOT NULL) AND\n";
					}
				}
			}
			if ($this->modo == "complete" || $this->modo == "updatedelete") {
				if ($ck_delete_str == "") 
					$label_str .= "\t1 AS _ck_delete,\n";
				else
					$label_str .= "\tCASE $ck_delete_str ELSE 1 END AS _ck_delete,\n";
			}
			for ($i=0; $i<count($this->condition); $i++) {
				$where_str .= "\t(" . $this->condition[$i] . ") AND\n";
			}
			$where_str .= "\t(1 = 1)\n";
			$order_str = "\t" . substr(trim($order_str),0,-1); // remove comma
			if (empty($pk_str)) $label_str = substr(rtrim($label_str),0,-1) . "\n"; // if pk is declared with add_Label()
			$connect = new connect;
			if (!empty($this->time_limit)) {
				$connect->setTimeLimit($this->time_limit);
			}
			if (!empty($this->before_list_cmd)) {
				$cmd = $this->before_list_cmd;
				$qry = $cmd($connect);
			}
			if ($this->list_modo == "custom") {
				// $this->res_list already set
				if ($this->debug == 1 && isset($this->list_qry)) $this->show_debug($this->list_qry,"L");
			} else if ($this->pageby) {
				// find paging for DESC order, using CHECKSUM() para contemplar multiple pk
				if ($this->db == "MSSQL") $pk_str_full = "CHECKSUM($pk_str_full)";
				$qry = "SELECT COUNT(DISTINCT $pk_str_full) AS total\n"
					. "FROM $this->table\n"
					. $join_str_count
					. "WHERE\n"
					. $where_str;
				if ($this->debug == 1) $this->show_debug($qry,"C");
				$this->res_tot = $connect->query($qry);
				$temp = explode(",", $order_str);
				if (!isset($this->vars["pagina"])) $this->vars["pagina"] = 1;
				$ini = ($this->vars["pagina"]-1)*$this->pageby;
				$fim = ($this->vars["pagina"])*$this->pageby;
				// exec query
				if (count($pk) == 1 && !empty($pk_str)) $pk_str = "\t" . trim($pk_str) . " AS id\n";
				if ($this->db == "MSSQL") {
					$qry = "SELECT * FROM (\n"
							. "SELECT DISTINCT\n"
							. "\tDENSE_RANK() OVER(ORDER BY\n" . str_replace("\t","\t\t",$order_str) . ") AS rownum,\n"
								. ($this->ck_active == 1 && strpos($label_str, "$this->active_field AS grp_ativo,") === false ? "\t$this->active_field AS grp_ativo,\n" : "")
								. $group_key_str
								. $label_str
								. $pk_str
							. "FROM $this->table\n"
							. $join_str
							. "WHERE\n"
							. $where_str . ") rs\n"
						. "WHERE\n"
						. "\trownum BETWEEN " . ($ini+1) . " AND $fim\n"
						. (!empty($group_str) ? "GROUP BY\n$group_str\n" : "")
						. "ORDER BY\n"
						. "\trownum";
				} else if ($this->db == "MYSQL") {
					$qry = "SELECT DISTINCT 
						" . ($this->ck_active == 1 && strpos($label_str, "$this->active_field AS grp_ativo,") === false ? "$this->active_field AS grp_ativo," : "") . "\n"
						. $group_key_str
						. $label_str
						. $pk_str
						. "FROM $this->table\n"
						. $join_str
						. "WHERE\n"
						. $where_str
						. (!empty($group_str) ? "GROUP BY\n$group_str\n" : "")
						. "ORDER BY\n"
						. ($this->ck_active == 1 ? "\tgrp_ativo DESC," : "") . "\n"
						. $order_str . "\n"
						. "LIMIT\n"
						. "\t$ini, $this->pageby";
				}
				if ($this->debug == 1) $this->show_debug($qry,"L");
				$this->res_list = $connect->query($qry);
			} else if (!empty($this->ck_recursive)) {
				$temp = explode(",", $label_str);
				$recursive_str = "";
				for ($i=0; $i<count($temp); $i++) {
					if (trim($temp[$i]) != "") {
						$recursive_str .= $this->getFieldLbl($temp[$i]) . ",";
					}
				}
				if ($this->db == "MSSQL") {
					$qry = "WITH tree (
							" . ($this->ck_active == 1 && strpos($label_str, "$this->active_field,") === false ? "grp_ativo," : "") . " 
							$recursive_str 
							id_tree, 
							level, 
							pathstr)
						AS (SELECT
							" . ($this->ck_active == 1 ? "$this->active_field AS grp_ativo," : "") . "
							$label_str
							$pk_str,
							0 AS level,
							CAST(" . $this->getFieldIx($this->label[0]["field"]) . " AS VARCHAR(MAX))
						FROM $this->table
						WHERE 
							$where_str
							$this->recursive_index IS NULL
						UNION ALL
						SELECT
							" . ($this->ck_active == 1 && strpos($label_str, "$this->active_field AS grp_ativo,") === false ? "$this->active_field AS grp_ativo," : "") . "
							$label_str
							$pk_str,
							tree.level + 1 AS level,
							tree.pathstr + ',' + " . $this->label[0]["field"] . "
						FROM $this->table
						INNER JOIN tree ON tree.id_tree = $qry_table.$this->recursive_index
						$join_str
						WHERE
							$where_str)
						SELECT 
							" . ($this->ck_active == 1 && strpos($label_str, "$this->active_field,") === false ? "grp_ativo," : "") . " 
							$recursive_str 
							id_tree AS $this->pk, 
							level, 
							pathstr
						FROM tree
						ORDER BY
							" . ($this->ck_active == 1 ? "grp_ativo DESC," : "") . "
							pathstr,
							$order_str";
				} else if ($this->db == "MYSQL") {
					$levels = "";
					$pathstr = "";
					$join_str .= "LEFT JOIN (SELECT DISTINCT $this->recursive_index FROM $this->table) child ON child.$this->recursive_index = $this->table.$this->pk\n";
					$label_str .= "CASE WHEN child.id_parent IS NOT NULL THEN 1 ELSE 0 END AS ck_child,";
					for ($i=0; $i<$this->ck_recursive; $i++) {
						if ($i == 0)
							$table = $this->table;
						else
							$table = "parent" . ($i);
						if ($i == 1)
							$parent = $this->table;
						else
							$parent = "parent" . ($i-1);
						if ($i>0) $join_str .= "LEFT JOIN $this->table parent$i ON parent$i.$this->pk = $parent.$this->recursive_index\n\t\t\t\t";
						$levels = "WHEN $table.$this->pk IS NOT NULL THEN $i\n\t\t\t\t\t" . $levels;
						$field = str_replace($this->table,$table,$this->getFieldIx($this->label[0]["field"]));
						$pathstr = ($i < $this->ck_recursive-1 ? "," : "") . "CASE WHEN $field IS NOT NULL THEN CONCAT($field,',') ELSE '' END" . $pathstr;
					}
					$group_order_str = "";
					for ($i=0; $i<count($this->group_key); $i++) {
						$group_order_str .= ($i>0?",":"") . "order_grp$i";
					}
					$qry = "SELECT 
							" . ($this->ck_active == 1 && strpos($label_str, "$this->active_field AS grp_ativo,") === false ? "$this->active_field AS grp_ativo," : "") . " 
							$label_str
							$group_key_str
							$this->table.$this->pk, 
							CASE $levels END AS level, 
							CONCAT($pathstr) AS pathstr
						FROM $this->table
						$join_str
						WHERE
							$where_str
						ORDER BY
							" . ($this->ck_active == 1 ? "grp_ativo DESC," : "") . "
							$group_order_str,
							pathstr,
							$order_str";
				}
				if ($this->debug == 1) $this->show_debug($qry,"L");
				$this->res_list = $connect->query($qry, $method = "QUERY"); // check if $connect->query version accepts $method = "QUERY"
			} else {
				$qry = "SELECT " . (!empty($this->ck_distinct) ? "DISTINCT" : "") . "\n" /* Depracated 20/12/2023 (strpos($label_str, "CONVERT(text") === false ? "DISTINCT" : "") . */ 
						. ($this->ck_active == 1 && strpos($label_str, "$this->active_field AS grp_ativo,") === false ? "\t$this->active_field AS grp_ativo,\n" : "")
						. $group_key_str
						. $label_str
						. $pk_str
						. "FROM $this->table\n"
						. $join_str
						. "WHERE\n"
						. $where_str
						. (!empty($group_str) ? "GROUP BY\n$group_str\n" : "")
						. "ORDER BY\n"
						. ($this->ck_active == 1 ? "\tgrp_ativo DESC,\n" : "")
						. $order_str;
				if ($this->debug == 1) $this->show_debug($qry,"L");
				$this->res_list = $connect->query($qry);
			}
			$connect->close();
		}
		return $this->res_list;
	}
	private function build_linked_insert_button() {
		$keys = array_keys($this->vars);
		$url = "?_use_vars=1&modo=insert&step=1";
		for ($i=0; $i<count($keys); $i++) {
			if ($keys[$i] != "pagina" &&
				$keys[$i] != "modo" &&
				$keys[$i] != "step")
				$url .= "&" . $keys[$i] . "=" . $this->vars[$keys[$i]];
		}
		return "<input type=\"button\" class=\"$this->css_button\" value=\"$this->lang_title_insert\" onclick=\"window.location='$url'\">\n";
	}
	private function build_default_list($colspan) {
		if ($this->use_list_header_separator == 1 && count($this->group_key) == 0)
			echo "<tr class=\"$this->css_list_separator\"><td colspan=$colspan class=\"$this->css_list_separator\"></td></tr>\n";
		if ($this->ck_repeat_row == "G") {
			for ($i=0; $i<count($this->label); $i++) {
				if (empty($this->label[$i]["norepeat"])) $this->label[$i]["repeat"] = 1;
			}
		}
		if (count($this->group_key) == 0 &&
			count($this->label) > 1 &&
			count($this->res_list) > 0) {
			// class applied on TR for update to preserve td rollover; on report tr class are inherited to xls
			echo "<tr id=\"title-cls_form\" " . (isset($this->css_list_title) ? "class=\"$this->css_list_title\" " : "") . ($this->modo == "update" || $this->modo == "complete" || $this->modo == "updatedelete" ? "class=\"$this->css_list_title\"" : "") . ">\n";
			for ($i=0; $i<count($this->label); $i++) {
				$field = $this->getFieldLbl($this->label[$i]["field"]);
				$prop = $this->label[$i]["label_prop"];
				for ($j=$i; $j<count($this->label); $j++) {
					if ($this->label[$i]["label"] != $this->label[$j]["label"]) break;
				}
				$prop["colspan"] = $j-$i;
				$i = $j-1;
				if ($this->list_title_mode == "V") {
					$prop["align"] = "right";
					$prop["nowrap"] = true;
					$prop["style"] = (isset($prop["style"]) ? $prop["style"] : "") . "direction:rtl; writing-mode:tb-rl;";
				} else {
					//$prop["valign"] = "top"; 
					if ($prop["colspan"] > 1) 
						$prop["style"] = (isset($prop["style"]) ? $prop["style"] : "") . "text-align:center;";
					else
						$prop["style"] = (isset($prop["style"]) ? $prop["style"] : "") . "text-align:" . $this->label[$i]["alignment"] . ";";
				}
				if ($this->label[$i]["ck_update_global"] == 1) {
					$prop["id"] = "td_$field"; 
					$prop["onMouseOver"] = "displayPrompt('$field',1)"; 
					$prop["onMouseOut"] = "blockHide['div_prompt_$field']=false;setTimeout('displayPrompt(\'$field\',0,1)',500);"; 
					$prop["style"] = (isset($prop["style"]) ? $prop["style"] : "") . "cursor:pointer;";
				}
				if ($this->label[$i]["type"] == "checkbox" && $this->label[$i]["ck_update"] == 1)
					$prop["nowrap"] = true;
				if ($this->list_form && $i == 0) {
					$prop["nowrap"] = true;
					$field = "id";
				}
				if (!empty($this->label[$i]["title"])) {
					$prop["title"] = $this->label[$i]["title"];
					$prop["style"] = (isset($prop["style"]) ? $prop["style"] : "") . "cursor:pointer;";
				}
				$str = "";
				foreach ($prop as $ix => $val) {
					if (!empty($val) && !($val instanceof Closure)) $str .= ($j>0?" ":"") . $ix . "=\"" . preg_replace("/\[([a-z0-9_]+)\]/i","",$val) . "\"";
				}
				$lbl = $this->label[$i]["label"];
				if ($lbl instanceof Closure) $lbl = $lbl(false);
				if ($this->label[$i]["ck_hidden"] == 0) {
					echo "<td $str>"
					. (($this->list_form == "custom" && $i == 0) || 
					   ($this->label[$i]["type"] == "checkbox" && $this->label[$i]["ck_update"] == 1) ? "<label><input type=\"checkbox\" onclick=\"setAll(this.checked,'$field')\">" : "")
					.  htmlspecialchars_decode(htmlentities($lbl))
					. ($this->label[$i]["mode"] == "NUM" && $this->label[$i]["un"] != "" ? " (".$this->label[$i]["un"].")" : "")
					. (($this->list_form && $i == 0) || ($this->label[$i]["type"] == "checkbox" && $this->label[$i]["ck_update"] == 1) ? "</label>" : "")
					. "</td>\n";
				}
				//if ($j > $i+1) $i = $j-1;
			}
			if ($this->modo == "complete" || $this->modo == "updatedelete") {
				echo "<td>&nbsp;</td>";
			}
			echo "</tr>\n";
		}
		if (count($this->group_key) > 1) {
			if (!isset($this->label[0]["label_prop"]["style"])) $this->label[0]["label_prop"]["style"] = "";
			$this->label[0]["label_prop"]["style"] .= "padding-left:" . (20*(count($this->group_key)-1)) . "px !important;";
		}
		// check if totals must be published
		$ck_total = 0;
		if (is_array($this->label) &&
			count($this->res_list) > 0) {
			foreach ($this->label as $label) {
				if (!empty($label["ck_show_total"]) || !empty($label["ck_show_count"])) $ck_total = 1;
			}
		}
		// list loop
		$ck_even = 0;
		for ($i=0; $i<=count($this->group_key); $i++) {
			$count_grp[$i] = 0;
		}
		for ($i=0; $i<count($this->res_list); $i++) {
			// color
			$bgcolor = "";
			if ($this->ck_active == 1 && $this->res_list[$i]["grp_ativo"] == 0) {
				$bgcolor = $this->inactive_color;
			} else {
				for ($j=0; $j<count($this->color_ref); $j++) {
					$param = $this->color_ref[$j]["param"];
					if (empty($param)) {
						$bgcolor = "#" . $this->color_ref[$j]["color"];
					} else {
						$col = $this->getFieldLbl($param["col"]);
						$op = $param["op"];
						$val = $param["val"];
						if (($op == "==" && $this->res_list[$i][$col] == $val) || 
							($op == "!=" && $this->res_list[$i][$col] != $val) || 
							($op == ">=" && $this->res_list[$i][$col] >= $val) || 
							($op == "<=" && $this->res_list[$i][$col] <= $val) || 
							($op == ">" && $this->res_list[$i][$col] > $val) || 
							($op == "<" && $this->res_list[$i][$col] < $val)) {
							$bgcolor = "#" . $this->color_ref[$j]["color"];
						}
					}
				}
			}
			// group
			if (count($this->group_key) > 0) {
				for ($j=0; $j<count($this->group_key); $j++) {
					if (isset($this->group_key[$j]["color"]))
						$bgcolor_group = $this->res_list[$i][$this->getFieldLbl($this->group_key[$j]["color"])];
					else if ($this->ck_active == 1 && $this->res_list[$i]["grp_ativo"] == 0)
						$bgcolor_group = $this->inactive_color;
					else 
						$bgcolor_group = "";
					if ($this->list_modo == "custom") {
						$label = $this->getFieldLbl($this->group_key[$j]["field"]);
					} else if ($this->list_modo == "auto") {
						$label = "grp$j";
					}
					if ($j == 0) $label_bak = $label;
					if ($i==0 || 
						$this->res_list[$i][$label] != $this->res_list[$i-1][$label] || 
						$this->res_list[$i][$label_bak] != $this->res_list[$i-1][$label_bak] ||
						($this->ck_active == 1 && $this->res_list[$i]["grp_ativo"] != $this->res_list[$i-1]["grp_ativo"])) {
						$count_grp[$j]++;
						for ($k=$j+1; $k<=count($this->group_key); $k++) {
							$count_grp[$k] = 0;
						}
						if ($i>0) {
							$temp = explode("-", $id_tr);
							$id_tr = $temp[0];
						}
						if ($j == 0) echo "<tr " . ($i>0?"id=\"$id_tr-end\"":"") . " class=\"$this->css_list_separator\" " . ($this->ck_collapse == 1 && $i > 0 ? "style=display:none" : "") . "><td colspan=$colspan class=\"$this->css_list_separator\"></td></tr>\n";
						$id_tr = "trline";
						for ($k=0; $k<=$j; $k++) {
							$id_tr .= ($k>0?"-":"") . $count_grp[$k];
						}
						if (!isset($this->res_list[$i]["total_grp$j"])) {
							for ($k=$i; $k<count($this->res_list); $k++) {
								if ($this->res_list[$i][$label] != $this->res_list[$k][$label]) break;
							}
							$this->res_list[$i]["total_grp$j"] = $k-$i;
						}
						echo "<tr id=\"$id_tr\" " . ($this->ck_collapse == 1 ? "style=\"cursor:pointer;" . ($j>0?"display:none;":"") . "\" onclick=\"chDisplay(this.id)\"" : "") . ">\n";
						echo "<td class=\"$this->css_list_group\" colspan=$colspan " . ($this->ck_collapse == 1 ? "width=$this->list_width" : "") . " style=\"" . ($bgcolor_group != "" ? "background-color:$bgcolor_group!important;" : "") . (count($this->group_key) > 1 && $j>0 ? "padding-left:" . (20*$j) . "px !important;" : "") . "\">";
						echo $this->res_list[$i][$label] . ($this->ck_active == 1 && $this->res_list[$i]["grp_ativo"] == 0 ? " ($this->lang_label_non_active)" : "");
						if ($this->ck_collapse == 1 && isset($this->res_list[$i]["total_grp$j"])) echo " (" . $this->res_list[$i]["total_grp$j"] . ")";
						echo "</td>";
						echo "</tr>\n";
						$ck_even = 0;
						//if ($j == count($this->group_key)-1 && $this->ck_collapse == 1) {
						if ($j == count($this->group_key)-1) {
							if (count($this->label) > 1 &&
								count($this->res_list) > 0) {
								echo "<tr class=\"$this->css_list_title\" id=\"$id_tr-titulo\" " . ($this->ck_collapse == 1 ? "style=\"display:none\"" : "") . ">\n";
								for ($k=0; $k<count($this->label); $k++) {
									$field = $this->getFieldLbl($this->label[$k]["field"]);
									$prop = $this->label[$k]["label_prop"];
									for ($l=$k; $l<count($this->label); $l++) {
										if ($this->label[$k]["label"] != $this->label[$l]["label"]) break;
									}
									if ($l-$k > 1) $prop["colspan"] = $l-$k;
									$k = $l-1;
									if ($this->list_title_mode == "V") {
										$prop["align"] = "right";
										$prop["nowrap"] = true;
										$prop["style"] = (isset($prop["style"]) ? $prop["style"] : "") . "direction:rtl; writing-mode:tb-rl;";
									} else {
										//$prop["valign"] = "top"; 
										$prop["style"] = (isset($prop["style"]) ? $prop["style"] : "") . "text-align:" . $this->label[$k]["alignment"] . ";";
									}
									if ($this->label[$k]["ck_update_global"] == 1) {
										$prop["id"] = "td_$field"; 
										$prop["onMouseOver"] = "displayPrompt('$field',1)"; 
										$prop["onMouseOut"] = "blockHide['div_prompt_$field']=false;setTimeout('displayPrompt(\'$field\',0,1)',500);"; 
										$prop["style"] = (isset($prop["style"]) ? $prop["style"] : "") . "cursor:pointer;";
									}
									if ($bgcolor_group != "")
										$prop["style"] = (isset($prop["style"]) ? $prop["style"] : "") . "background-color:$bgcolor_group!important;";
									if ($this->label[$k]["type"] == "checkbox" && $this->label[$k]["ck_update"] == 1)
										$prop["nowrap"] = true;
									if (isset($prop["colspan"])) {
										if (!isset($prop["style"])) $prop["style"] = "";
										$prop["style"] = preg_replace("/text-align:([a-z]+);/","",$prop["style"]);
										$prop["style"] = (isset($prop["style"]) ? $prop["style"] : "") . "text-align:center;";
									}
									$str = "";
									foreach ($prop as $ix => $val) {
										$str .= ($str != "" ? " " : "") . "$ix=\"$val\"";
									}
									if ($this->label[$k]["ck_hidden"] == 0) {
										echo "<td class=\"$this->css_list_title\" $str>";
										if ($this->label[$k]["type"] == "checkbox" && $this->label[$k]["ck_update"] == 1)
											echo "<label><input type=checkbox onclick=\"setAll(this.checked,'$field','" . addslashes($this->res_list[$i]["grp$j"]) . "')\">" . $this->label[$k]["label"] . "</label>";
										else
											echo $this->label[$k]["label"];
										echo "</td>\n";
									}
								}
								if ($this->list_form && ($this->modo == "complete" || $this->modo == "updatedelete")) {
									echo "<td class=\"$this->css_list_title\" " . ($bgcolor_group != "" ? "style=\"background-color:$bgcolor_group\"" : "") . ">";
									echo "<input type=checkbox onclick=\"setAll(this.checked,'delete','" . addslashes($this->res_list[$i]["grp$j"]) . "')\"> " . ucfirst(strtolower($this->lang_title_delete)) . "\n";
									echo "</td>\n";
								}
								echo "</tr>\n";
							}
						}
					}
					$label_bak = $label;
				} // for ($j=0; $j<count($this->group_key); $j++) {
			} else {
				$id_tr = "trline";
				if ($this->ck_active == 1) {
					if (($i==0 || $this->res_list[$i]["grp_ativo"] != $this->res_list[$i-1]["grp_ativo"]) && $this->res_list[$i]["grp_ativo"] == 0) {
						if ($i > 0) echo "<tr class=\"$this->css_list_separator\"><td colspan=$colspan class=\"$this->css_list_separator\"></td></tr>";
						echo "<tr class=\"$this->css_list_title\">\n";
						echo "<td bgcolor=\"$bgcolor\" colspan=$colspan " . ($this->ck_active == 1 && $this->res_list[$i]["grp_ativo"] == 0 ? "style=background-color:$this->inactive_color" : "") . ">\n";
						echo ($this->ck_active == 1 && $this->res_list[$i]["grp_ativo"] == 0 ? "($this->lang_label_non_active)" : "" );
						echo "</td>";
						$ck_even = 0;
					}
				}
			}
			$ck_even++;
			$key = "";
			foreach (is_array($this->pk) ? $this->pk : [$this->pk] as $ix) {
				if (!empty($this->res_list[$i][$this->getFieldLbl($ix)])) {
					$val = $this->res_list[$i][$this->getFieldLbl($ix)];
					if ($val instanceof DateTime) $val = $val->format("Y-m-d");
					$key .= ($key != "" ? "-" : "") . $val;
				}
			}
			if (isset($this->css_list_even) && $this->css_list_even != "" && $ck_even % 2 == 1)
				$class = $this->css_list_even;
			else if (isset($this->css_list_text))
				$class = $this->css_list_text;
			else
				$class = $this->css_text;
			$p = $count_grp[count($this->group_key)];
			$params = "";
			$prop = [];
			if (!empty($this->row_prop)) {
				foreach ($this->row_prop as $ix => $ref) {
					if (!$ref["condition"] || $ref["condition"]($this->res_list[$i])) {
						$prop[$ix] = $ref["val"];
					}
				}
			}
			if ($this->ck_collapse == 1 || ($this->ck_recursive != 0 && $this->res_list[$i]["level"] != 0)) {
				if (!isset($prop["style"])) $prop["style"] = "";
				$prop["style"] .= "display:none;";
			}
			if (!empty($key)) {
				$prop["key"] = $key;
			}
			if (!empty($this->css_list_text)) {
				$prop["class"] = $this->css_list_text;
			}
			foreach ($prop as $ix => $val) {
				preg_match_all("[\[([a-zA-Z0-9_])+\]]", $val, $match);
				foreach ($match[0] as $_ix) $val = str_replace($_ix, $this->res_list[$i][substr($_ix,1,-1)], $val);
				$params .= " $ix=\"$val\"";
			}
			echo "<tr id=\"$id_tr-$p\" $params>\n";
			if ($this->ck_recursive != 0) {
				$str = "";
				for ($j=$i; $j<count($this->res_list); $j++) {
					if ($j != $i && $this->res_list[$i]["level"] == $this->res_list[$j]["level"]) break;
					if ($this->res_list[$j]["level"] == $this->res_list[$i]["level"]+1) $str .= ($str != "" ? "," : "") . "'$id_tr-" . ($p+$j-$i) . "'";
				}
				if ($this->res_list[$i]["level"] < $this->res_list[$j-1]["level"])
					echo "<td><a href=\"javascript:chDisplay([$str])\" class=\"$this->css_link_general\">[+]</a></td>\n";
				else
					echo "<td bgcolor=white></td>\n";
			}
			$params = "";
			if ($this->modo == "update" || $this->modo == "complete" || $this->modo == "updatedelete" || $this->modo == "report") {
				foreach ($this->list_param as $ix => $val) {
					if (strtoupper($val) == "GETFROMQRY" && isset($this->res_list[$i][$this->getFieldLbl($ix)])) {
						$val = $this->res_list[$i][$this->getFieldLbl($ix)];
						if ($val instanceof DateTime) $val = $val->format("Y-m-d");
					}
					$params .= ($params != "" ? "&" : "") . $this->getFieldLbl($ix) . "=" . urlencode($val);
				}
				foreach ($_GET as $ix => $val) {
					$ck_filter = 0;
					/* Filter comes from POST
					foreach ($this->ref_filter as $f) {
						if ($ix == $this->getFieldLbl($f["field"])) {
							$ck_filter = 1;
							break;
						}
					} */
					if (!in_array($ix,["modo","step","id","urlKey","_session_id"]) && $ck_filter == 0) {
						if (is_array($val)) {
							foreach($val as $arr_val) {
								$params .= ($params != "" ? "&" : "") . $ix . "[]=$arr_val";
							}
						} else {
							$params .= ($params != "" ? "&" : "") . $ix . "=" . urlencode($val);
						}
					}
				}
			}
			for ($j=0; $j<count($this->label); $j++) { // label
				// rowspan
				if ($this->ck_repeat_row &&
					$i < count($this->res_list)) {
					$ck_td = 0;
					$rowspan = 0;
					for ($k=$i; $k<count($this->res_list); $k++) { // list
						$ck_break = 0;
						for ($l=0; $l<=$j; $l++) { // label
							$_field = $this->getFieldLbl($this->label[$l]["field"]);
							if (empty($this->label[$l]["repeat"]) && !empty($_field)) {
								if ($i == 0 || $this->res_list[$i][$_field] != $this->res_list[$i-1][$_field]) {
									$ck_td = 1;
								}
								if ($this->res_list[$i][$_field] != $this->res_list[$k][$_field]) {
									$ck_break = 1;
									break;
								}
							}
						}
						if ($ck_break == 1) break;
						$rowspan++;
					}
				} else {
					$rowspan = 1;
					$ck_td = 1;
				}
				if ($this->label[$j]["ck_hidden"] == 0 && $ck_td == 1) {
					echo $this->get_list_cell($i, $j, $this->label[$j], $this->res_list[$i], $rowspan, $bgcolor, $params, $class="");
				} else if ($this->label[$j]["ck_update"] == 1 && $this->label[$j]["type"] == "hidden") {
					$f = $this->getFieldLbl($this->label[$j]["field"]);
					$obj = $this->ref_list[$this->label[$j]["object_id"]];
					if (!empty($obj->ck_log)) {
						$val = "";
					} else {
						$val = $this->res_list[$i][$f];
						if ($val instanceof DateTime) $val = $val->format("Y-m-d");
					}
					echo "<input type=\"hidden\" name=\"$f$i\" id=\"$f$i\" value=\"$val\">";
				}
			}
			$this->add_list_links($i);
			if ($this->add_print == 1) {
				echo "<td class=\"$this->css_print_option\">\n";
				$url = $_SERVER["SCRIPT_NAME"] . "?modo=update&step=2&id=" . $this->res_list[$i]["id"] . "&ck_print=1&$params";
				echo "<a target=\"_blank\" href=\"$url\"><img src=\"$this->img_dir/$this->img_print\" border=0 title=\"$this->lang_print_label\"></a>\n";
				echo "</td>\n";
			} 
			echo "</tr>\n";
			// list rows definida antes dos rowspans para ser associada com a 1a linha do grupo
			$str_list_rows = "";
			foreach ($this->list_row as $j => $row) {
				$field = $this->getFieldLbl($row["field"]);
				if ($row["ck_update"] == 1 || !empty($this->res_list[$i][$field])) {
					$colspan = 0;
					foreach ($this->label as $label) {
						if ($label["ck_hidden"] == 0) $colspan++;
					}
					if ($row["ck_update"] == 1) $obj = $this->ref_list[$row["object_id"]];
					if ($this->ck_collapse == 1) {
						$ck_hidden = 1;
					} else if ($row["ck_hidden"] == 1) {
						$ck_hidden = 1;
					} else if ($row["ck_update"] == 1 && !empty($obj->ck_hidden)) {
						$ck_hidden = $this->ck_condition($this->ref_list, $obj->ck_hidden, $this->res_list[$i]);
					} else {
						$ck_hidden = 0;
					}
					$str_list_rows .= "<tr id=\"$id_tr-$p-row$j\" " . ($ck_hidden == 1 ? "style=\"display:none\"" : "") . "><td colspan=$colspan class=\"$this->css_text_entity\">\n";
					if ($row["ck_update"] == 1) {
						$str = $this->res_list[$i][$this->getFieldLbl($obj->field)];
						if (!empty($obj->ck_log) && !empty($str)) $str_list_rows .= nl2br(trim($str)) . "<br>";
						$str_list_rows .= $obj->comment_before;
						// if ($obj->ck_log == 1) $str_list_rows .= "[<a class=\"$this->css_link_general\" href=\"javascript:void(null)\" onclick=\"_obj=document.getElementById('edit_row_$i-$j');_obj.style.display=_obj.style.display==''?'none':''\">+ " . $row["label"] . "</a>]<br><span id=\"edit_row_$i-$j\" style=\"display:none;\">\n";
						$params = [ "scope" => "list", "ref" => $obj, "sufix" => $i ];
						if (empty($obj->ck_log)) $params["res"] = $this->res_list[$i];
						$str_list_rows .= $this->get_field($params);
						// if ($obj->ck_log == 1) $str_list_rows .= "</span>\n";
						$str_list_rows .= $obj->comment;
						$field_name = $this->getFieldLbl($obj->field);
						$str_list_rows .= "<input type=\"hidden\" name=\"{$field_name}_bak{$i}\" id=\"{$field_name}_bak{$i}\" value=\"" . $this->res_list[$i][$field_name] . "\">\n";
					} else {
						$str_list_rows .= nl2br(trim($this->res_list[$i][$field]));
					}
					$str_list_rows .= "</td></tr>\n";
				}
			}
			// write lines with cells that dont have rowspan
			if ($this->ck_repeat_row) {
				for ($j=$i+1; $j<count($this->res_list); $j++) {
					$new_line = 1;
					foreach ($this->label as $label) {
						$_field = $this->getFieldLbl($label["field"]);
						if (empty($label["repeat"]) &&
							$i < count($this->res_list)-1 &&
							$this->res_list[$i][$_field] != $this->res_list[$j][$_field]) {
							$new_line = 0;
							break;
						}
					}
					if ($new_line == 1) {
						$count_grp[count($this->group_key)]++;
						$p = $count_grp[count($this->group_key)];
						$params = "";
						foreach ($prop as $ix => $val) {
							preg_match_all("[\[([a-zA-Z0-9_])+\]]", $val, $match);
							foreach ($match[0] as $_ix) $val = str_replace($_ix, $this->res_list[$j][substr($_ix,1,-1)], $val);
							$params .= " $ix=\"$val\"";
						}
						echo "<tr id=\"$id_tr-$p\" " . (isset($this->css_list_text) ? "class=\"$this->css_list_text\" " : "") . ($this->ck_collapse == 1 ? " style=display:none" : "") . " $params>\n";
						foreach ($this->label as $k => $label) {
							if (!empty($label["repeat"])) {
								if ($label["ck_hidden"] == 0 && $ck_td == 1) {
									echo $this->get_list_cell($j, $k, $label, $this->res_list[$j], 1, $bgcolor, $params, $class);
								} else if ($label["ck_update"] == 1 && $label["type"] == "hidden") {
									$f = $this->getFieldLbl($label["field"]);
									echo "<input type=\"hidden\" name=\"$f$j\" id=\"$f$j\" value=\"" . $this->res_list[$j][$f] . "\">";
								}
							}
						}
						if (!empty($this->ck_repeat_link)) $this->add_list_links($j);
						echo "</tr>\n";
					} else
						break;
				}
				$i = $j-1;
			}
			echo $str_list_rows;
			$count_grp[count($this->group_key)]++;
			// group end
			if ($ck_total == 1) {
				for ($j=0; $j<count($this->group_key); $j++) {
					if ($this->list_modo == "custom") {
						$label = $this->getFieldLbl($this->group_key[$j]["field"]);
					} else if ($this->list_modo == "auto") {
						$label = "grp$j";
					}
					if ($i == count($this->res_list)-1 ||
						$this->res_list[$i][$label] != $this->res_list[$i+1][$label] ||
						$this->res_list[$i][$label_bak] != $this->res_list[$i+1][$label_bak] ||
						($this->ck_active == 1 && $this->res_list[$i]["grp_ativo"] != $this->res_list[$i+1]["grp_ativo"])) {
						if ($j==0) {
							echo "<tr id=\"$id_tr-total\" " . ($this->ck_collapse == 1 ? "style=display:none" : "") . " class=\"$this->css_list_group\">";
							for ($k=0; $k<count($this->label); $k++) {
								if ($this->label[$k]["ck_hidden"] == 0) {
									if (!empty($this->label[$k]["ck_show_total"])) {
										echo "<td align=\"" . $this->label[$k]["alignment"] . "\" nowrap>" . /* $this->label[$i]["un"] . " " . */ number_format($this->label[$k]["total"], $this->label[$k]["depth"], ",", ".") . "</td>";
										$this->label[$k]["total"] = 0;
									} else {
										$c = 0;
										for ($l=$k; $l<count($this->label); $l++) {
											if (!empty($this->label[$l]["ck_show_total"]) || !empty($this->label[$l]["ck_show_count"])) break;
											$c++;
										}
										echo "<td colspan=\"$c\" class=\"$this->css_list_group\" " . ($this->ck_collapse == 1 ? "width=$this->list_width" : "") . " style=\"" . ($this->ck_active == 1 && $this->res_list[$i]["grp_ativo"] == 0 ? "background-color:$bgcolor!important;" : "") . (count($this->group_key) > 1 ? "padding-left:" . (20*(count($this->group_key)-1)) . "px !important;" : "") . "\">" . ($k == 0 ? "TOTAL" : "") . "</td>";
										$k = $l-1;
									}
								}
							}
							echo "</tr>\n";
						}
					}
				}
			}
			if (!empty($this->subtotal)) {
				foreach ($this->subtotal as $ix => $subtotals) {
					if ($i == count($this->res_list)-1 ||
						$this->res_list[$i][$ix] != $this->res_list[$i+1][$ix]) {
						$id = $this->res_list[$i][$ix];
						if (!isset($pTot[$ix])) $pTot[$ix] = 0;
						$rSubTot = [];
						for ($_i = $pTot[$ix]; $_i <= $i; $_i++) {
							$rSubTot[] = $this->res_list[$_i];
						}
						$pTot[$ix] = $i+1;
						echo "<tr class=\"$this->css_list_separator\"><td colspan=\"" . count($this->label) . "\"></td></tr>\n";
						echo "<tr>\n";
						echo "<td class=\"$this->css_list_header\" colspan=\"" . count($this->label) . "\">\n";
						foreach ($subtotals as $subtotal) {
							echo "<b>" . $subtotal["label"] . "</b>: " . $subtotal["val"]($rSubTot) . "<br>\n";
						}
						echo "</td>\n";
						echo "</tr>\n";
						if ($i < count($this->res_list)-1) {
							echo "<tr class=\"$this->css_list_separator\"><td colspan=\"" . count($this->label) . "\"></td></tr>\n";
							echo "<tr class=\"$this->css_list_title\">\n";
							foreach ($this->label as $lbl) {
								if ($lbl["ck_hidden"] == 0) echo "<td>" . $lbl["label"] . "</td>\n";
							}
							echo "</tr>\n";
						}
					}
				}
			}
		} // for ($i=0; $i<count($this->res_list); $i++) {
		if (count($this->group_key) == 0 && $ck_total == 1) {
			echo "<tr id=\"tr-total\" class=\"$this->css_label\">\n";
			for ($i=0; $i<count($this->label); $i++) {
				if (!empty($this->label[$i]["ck_show_total"])) {
					if (isset($this->label[$i]["parser"]))
						$label = $this->label[$i]["parser"]($this->label[$i]["total"], false);
					else
						$label = number_format($this->label[$i]["total"], $this->label[$i]["depth"], ",", ".");
					echo "<td align=\"" . $this->label[$i]["alignment"] . "\" nowrap>$label</td>";
				} else {
					$c = 0;
					for ($j=$i; $j<count($this->label); $j++) {
						if (!empty($this->label[$j]["ck_show_total"]) || !empty($this->label[$j]["ck_show_count"])) break;
						if ($this->label[$j]["ck_hidden"] == 0) $c++;
					}
					if ($c > 0) echo "<td colspan=\"$c\">" . ($i == 0 ? "TOTAL" : "") . "</td>";
					$i = $j-1;
				}
			}
			echo "</tr>\n";
		}
		if ($this->modo == "delete") {
			$ck_buttons = 1;
		} else if (!empty($this->list_form)) {
			$ck_buttons = 1;
		} else {
			$ck_buttons = 0;
			foreach ($this->ref_list as $elm) {
				if ($elm->type != "hidden") $ck_buttons++;
			}
		}
		if ($ck_buttons) {
			echo "<tr class=\"$this->css_list_separator\"><td colspan=$colspan class=\"$this->css_list_separator\"></td></tr>\n";
			echo "<tr><td colspan=$colspan align=\"$this->button_align\" class=\"$this->css_edit_list\">\n";
			echo "<input type=\"hidden\" name=\"count\" id=\"count\" value=\"" . count($this->res_list) . "\">\n";
			echo "<input type=\"hidden\" name=\"step\" id=\"step\" value=\"" . ($this->modo == "delete" ? 2 : 1) . "\">\n";
			echo "<input type=\"hidden\" name=\"modo\" id=\"modo\" value=\"" . $this->modo . "\">\n";
			$keys = array_keys($this->vars);
			for ($i=0; $i<count($keys); $i++) {
				if ($keys[$i] != "count" &&
					$keys[$i] != "step" &&
					$keys[$i] != "modo") {
					$ck_add = 1;
					$temp = array_merge($this->label, $this->list_row);
					for ($j=0; $j<count($temp); $j++) {
						$field = $this->getFieldLbl($temp[$j]["field"]);
						if ($temp[$j]["ck_update"] == 1 &&
							$field == substr($keys[$i], 0, strlen($field))) {
							$ck_add = 0;
							break;
						}
					}
					preg_match("/id[0-9]+/", $keys[$i], $match);
					if (count($match) > 0) $ck_add = 0;
					if ($ck_add == 1)
						echo "<input type=\"hidden\" name=\"" . $keys[$i] . "\" id=\"" . $keys[$i] . "\" value=\"" . $this->vars[$keys[$i]] . "\">\n";
				}
			}
			for ($i=0; $i<count($this->ref); $i++) {
				$field = $this->ref[$i]["field"];
				if ($this->ref[$i]["type"] != "entity" && isset($this->vars[$field])) {
					if (isset($this->ref[$i]["comment"]) && $this->ref[$i]["comment"] != "") {
						preg_match_all("[name=([A-Za-z0-9_])+]", $this->ref[$i]["comment"], $match);
						for ($j=0; $j<count($match[0]); $j++) {
							$match[0][$j] = str_replace("name=","",$match[0][$j]);
							if (isset($this->vars[$match[0][$j]])) echo "<input type=\"hidden\" name=\"" . $match[0][$j] . "\" id=\"" . $match[0][$j] . "\" value=\"" . $this->vars[$match[0][$j]] . "\">\n";
						}
					}
				}
			}
			echo "<span class=\"$this->css_text" . (count($this->list_form_trigger) > 1 ? " btngroup" : "") . "\" id=\"botao\">\n";
			foreach ($this->list_form_trigger as $label => $dst) {
				echo "<input type=button value=\"$label\" onclick=\"ckFormList(this.form" . ($dst != "" ? ",function() { $dst }" : "") . ");\" name=\"ok\" class=\"$this->css_button\">\n";
			}
			echo "</span>\n";
			echo "</td></tr>\n";
		}
		if ($this->pageby && 
			$this->res_tot[0]["total"] > $this->pageby &&
			strpos($this->pageby_pos, "D") !== false) {
			if ($this->modo == "update" ||
				$this->modo == "delete" ||
				$this->modo == "updatedelete" ||
				$this->modo == "complete")
			echo "<tr class=\"$this->css_list_separator\"><td colspan=2 class=\"$this->css_list_separator\"></td></tr>\n";
			echo "<tr><td colspan=$colspan class=\"$this->css_list_header\" align=\"$this->pageby_align\">\n";
			echo $this->get_paging($this->res_tot[0]["total"]);
			echo "</td></tr>\n";
		}
	}
	private function build_graph_list($colspan) { // incompleta
		for ($i=0; $i<count($this->graph); $i++) {
			$label_ref = [];
			for ($j=0; $j<count($this->graph[$i]["fields"]); $j++) {
				for ($k=0; $k<count($this->label); $k++) {
					if (strtolower($this->getFieldLbl($this->label[$k]["field"])) == strtolower($this->graph[$i]["fields"][$j])) {
						$label_ref[count($label_ref)] = $this->label[$k];
						break;
					}
				}
			}
			if (count($this->group_key) == 0 &&
				count($label_ref) > 1 &&
				count($this->res_list) > 0) {
				echo "<tr class=\"$this->css_list_title\">\n";
				for ($j=0; $j<count($label_ref); $j++) {
					for ($k=$jni=$j; $k<count($label_ref); $k++) {
						if ($label_ref[$j]["label"] != $label_ref[$k]["label"]) break;
						$j = $k;
					}
					if ($this->list_title_mode == "V") 
						$nowrap = "NOWRAP";
					else if ($label_ref[$j]["type"]=="checkbox" && $label_ref[$j]["ck_update"]==1) 
						$nowrap = "NOWRAP";
					else
						$nowrap = "";
					echo "<td class=\"$this->css_list_title\" colspan=" . ($k-$jni) . " " . ($this->list_title_mode == "V" ? "style=\"direction:rtl; writing-mode:tb-rl\" align=right" : "style=\"text-align:" . $label_ref[$j]["alignment"] . "\"") . " $nowrap>" . ($label_ref[$j]["type"]=="checkbox" && $label_ref[$j]["ck_update"]==1 ? "<input type=checkbox onclick=setAll(this.checked,'".$this->getFieldLbl($label_ref[$j]["field"])."')> " : "") . $label_ref[$j]["label"] . ($label_ref[$j]["mode"] == "NUM" ? " (".$label_ref[$j]["un"].")" : "") . "</td>";
					//if ($k > $j+1) $j = $k-1;
				}
				if ($this->modo == "complete" || $this->modo == "updatedelete")
					echo "<td>&nbsp;</td>";
				echo "</tr>\n";
			}
			// list loop
			$c = 0;
			for ($j=0; $j<count($this->res_list); $j++) {
				// color
				$bgcolor = "";
				if ($this->ck_active == 1 && $this->res_list[$j]["grp_ativo"] == 0) {
					$bgcolor = $this->inactive_color;
				} else {
					for ($k=0; $k<count($this->color_ref); $k++) {
						$param = $this->color_ref[$k]["param"];
						if (empty($param)) {
							$bgcolor = "#" . $this->color_ref[$k]["color"];
						} else {
							$col = $this->getFieldLbl($param["col"]);
							$op = $param["op"];
							$val = $param["val"];
							if (($op == "==" && $this->res_list[$i][$col] == $val) || 
								($op == "!=" && $this->res_list[$i][$col] != $val) || 
								($op == ">=" && $this->res_list[$i][$col] >= $val) || 
								($op == "<=" && $this->res_list[$i][$col] <= $val) || 
								($op == ">" && $this->res_list[$i][$col] > $val) || 
								($op == "<" && $this->res_list[$i][$col] < $val)) {
								$bgcolor = "#" . $this->color_ref[$k]["color"];
							}
						}
					}
				}
				$c++;
				if (isset($this->css_list_even) && $this->css_list_even != "" && $c%2 == 0)
					$class = $this->css_list_even;
				else if (isset($this->css_list_text))
					$class = $this->css_list_text;
				else
					$class = $this->css_text;
				echo "<tr id=\"$id_tr-$p\" class=\"$class\" " . ($this->ck_collapse == 1 || ($this->ck_recursive != 0 && $this->res_list[$j]["level"] != 0) ? "style=display:none" : "") . ">\n";
				$params = "";
				if ($this->modo == "update" || 
					$this->modo == "complete" || 
					$this->modo == "updatedelete" || 
					$this->modo == "report") {
					$keys = array_keys($this->list_param);
					for ($k=0; $k<count($keys); $k++) {
						if (strtoupper($this->list_param[$keys[$k]]) == "GETFROMQRY")
							$val = $this->res_list[$j][$keys[$k]];
						else
							$val = $this->list_param[$keys[$k]];
						$params .= ($params != "" ? "&" : "") . $keys[$k] . "=" . urlencode($val);
					}
					$keys = array_keys($_GET);
					for ($k=0; $k<count($keys); $k++) {
						$ck = 0;
						for ($l=0; $l<count($this->ref_filter); $l++) {
							if ($keys[$k] == $this->ref_filter[$l]["field"]) {
								$ck = 1;
								break;
							}
						}
						if ($ck == 0 && $keys[$k] != "modo" && $keys[$k] != "step")
							$params .= ($params != "" ? "&" : "") . $keys[$k] . "=" . urlencode($_GET[$keys[$k]]);
					}
				}
				// rowspan
				if ($this->ck_repeat_row &&
					$i < count($this->res_list)-1) {
					$rowspan = 0;
					for ($j=$i; $j<count($this->res_list); $j++) {
						$ck_break = 0;
						foreach ($this->label as $label) {
							$_field = $this->getFieldLbl($label["field"]);
							if (empty($this->label["repeat"]) &&
								$this->res_list[$i][$_field] != $this->res_list[$j][$_field]) {
								$ck_break = 1;
								break;
							}
						}
						if ($ck_break == 1) break;
						$rowspan++;
					}
				} else
					$rowspan = 1;
				for ($k=0; $k<count($label_ref); $k++) {
					echo $this->get_list_cell($j, $k, $label_ref[$k], $this->res_list[$j], $rowspan, $bgcolor, $params, $class);
				}
				/* for ($k=0; $k<count($this->link_ref); $k++) { // link_ref not added on graph_list
					...
				} */
				echo "</tr>\n";
				// write lines with only repeated columns
				if ($this->ck_repeat_row) {
					for ($j=$i+1; $j<count($this->res_list); $j++) {
						$new_line = 1;
						foreach ($this->label as $label) {
							$_field = $this->getFieldLbl($label["field"]);
							if (empty($label["repeat"]) &&
								$i < count($this->res_list)-1 &&
								$this->res_list[$i][$_field] != $this->res_list[$j][$_field]) {
								$new_line = 0;
								break;
							}
						}
						if ($new_line == 1) {
							echo "<tr id=\"$id_tr-$p\" " . ($this->ck_collapse == 1 ? "style=display:none" : "") . ">\n";
							foreach ($this->label as $k => $label) {
								if (!empty($label["repeat"])) echo $this->get_list_cell($i, $k, $label, $this->res_list[$j], 1, $bgcolor, $params, $class);
							}
							echo "</tr>\n";
						} else
							break;
					}
					$i = $j-1;
				}
				// write lines with only repeated columns
				if ($this->ck_repeat_row) {
					for ($k=$j+1; $k<count($this->res_list); $k++) {
						foreach ($this->label as $label) {
							$_field = $this->getFieldLbl($label["field"]);
							if (!empty($label["repeat"]) &&
								$i < count($this->res_list)-1 &&
								$this->res_list[$j][$_field] != $this->res_list[$k][$_field]) {
								$ck_row = 0;
							}
						}
						if ($ck_row == 1) {
							echo "<tr id=\"$id_tr-$p\" class=\"$this->css_text\" " . ($this->ck_collapse == 1 ? "style=display:none" : "") . ">\n";
							foreach ($label_ref as $l => $label) {
								if (!empty($label["repeat"])) echo $this->get_list_cell($j, $l, $label, $this->res_list[$k], 1, $bgcolor, $params, $class);
							}
							echo "</tr>\n";
						} else
							break;
					}
					$j = $k-1;
				}
			} // for ($j=0; $j<count($this->res_list); $j++) {
			if (is_array($label_ref) &&
				count($this->res_list) > 0 && 
				count($this->group_key) == 0) {
				echo "<tr class=\"$this->css_label\">\n";
				$ck_total = 0;
				for ($j=0; $j<count($label_ref); $j++) {
					if ($label_ref[$j]["mode"] == "NUM") $ck_total = 1;
				}
				if ($ck_total == 1) {
					for ($j=0; $j<count($label_ref); $j++) {
						if ($label_ref[$j]["mode"] == "NUM") {
							echo "<td align=right nowrap>" . /* $label_ref[$j]["un"] . " " . */ number_format($label_ref[$j]["total"], $label_ref[$j]["depth"], ",", ".") . "</td>";
						} else {
							$c = 0;
							for ($k=$j; $k<count($label_ref); $k++) {
								if ($label_ref[$k]["mode"] == "NUM") break;
								$c++;
							}
							echo "<td colspan=\"$c\">" . ($j == 0 ? "TOTAL" : "") . "</td>";
							$j = $k-1;
						}
					}
				}
				echo "</tr>\n";
			}
		if ($i < count($this->graph)-1)
			echo "<tr class=\"$this->css_list_separator\"><td colspan=$colspan class=\"$this->css_list_separator\"></td></tr>\n";
		}
	}
	private function update_list() {
		$pks = [];
		$qry_str = "";
		$ref = array_merge($this->label, $this->list_row);
		for ($i=0; $i<$this->vars["count"]; $i++) {
			$str_val = [];
			$info = [];
			foreach ($ref as $r) {
				if ($r["ck_update"] == 1) {
					$update_key = $r["update_key"];
					$obj = $this->ref_list[$r["object_id"]];
					if ($obj->ck_qry == 1 &&
						((!is_array($update_key) && isset($this->vars[$update_key.$i])) ||
						 (is_array($update_key) && isset($this->vars[$update_key[0].$i])))) {
						if (is_array($update_key)) $update_key = implode(",",$update_key);
						$tbl_val[$update_key] = $r["table"];
						$field = $this->getFieldLbl($obj->field);
						$var = "$field$i";
						$var_bak = "{$field}_bak{$i}";
						// fill $info array to use on parser
						if ($obj->type == "date" && $this->pref_field_date == "text") {
							$info[$field] = $this->vars["ano_$var"] . "-" . $this->vars["mes_$var"] . "-" . $this->vars["dia_$var"];
							$info[$field."_bak"] = $this->vars["ano_$var_bak"] . "-" . $this->vars["mes_$var_bak"] . "-" . $this->vars["dia_$var_bak"];
						} else {
							if (isset($this->vars[$var]))     $info[$field] = $this->vars[$var];
							if (isset($this->vars[$var_bak])) $info[$field."_bak"] = $this->vars[$var_bak];
						}
						$val = "";
						if (!empty($obj->ck_log)) {
							if (!empty($obj->log_parser)) {
								$val = $obj->log_parser;
								if (($val instanceof Closure)) $val = $val($info);
							} else {
								$val = $this->vars[$var];
							}
							if (!empty($val)) {
								$val = "[$obj->log_signature] " . trim($val);
								$val = str_replace("'","''",$val);
							}
						} else {
							if (isset($this->vars[$var]) && $this->vars[$var] != $this->vars[$var_bak]) {
								foreach ($r["xtra_field"] as $f) {
									if ($f["condition"] === false || $f["condition"] == $this->vars[$var]) {
										$_field = $f["field"];
										$_val = $f["val"];
										if ($_val != "NULL" && !is_numeric($_val) && strpos($_val,"(") === false) $_val = "'$_val'";
										$str_val[$update_key][$_field] = $_val;
									}
								}
							}
							if ($obj->type == "checkbox") {
								// $this->vars[$var] = isset($this->vars[$var]) ? 1 : 0;
								if (!isset($this->vars[$var])) $this->vars[$var] = $obj->cb_value == 1 ? 0 : $this->vars[$var_bak]; // Para valores customizados de checkbox usando set_index() usa-se var_bak quando nao esta clicado
								if ($this->vars[$var] != $this->vars[$var_bak]) {
									$val = $this->vars[$var];
								}
								unset($this->vars[$var]);
								unset($this->vars[$var_bak]);
							} else if ($obj->type == "date") {
								if (($this->vars["dia_$var"] != $this->vars["dia_$var_bak"]) ||
									($this->vars["mes_$var"] != $this->vars["mes_$var_bak"]) ||
									($this->vars["ano_$var"] != $this->vars["ano_$var_bak"])) {
									$val = $this->get_val($obj, "", $i);
								}
								unset($this->vars["dia_$var"]);
								unset($this->vars["mes_$var"]);
								unset($this->vars["ano_$var"]);
								unset($this->vars["dia_$var_bak"]);
								unset($this->vars["mes_$var_bak"]);
								unset($this->vars["ano_$var_bak"]);
							} else if ($obj->type == "datetime") {
								if (($this->vars["dia_$var"] != $this->vars["dia_$var_bak"]) ||
									($this->vars["mes_$var"] != $this->vars["mes_$var_bak"]) ||
									($this->vars["ano_$var"] != $this->vars["ano_$var_bak"]) ||
									($this->vars["hor_$var"] != $this->vars["hor_$var_bak"]) ||
									($this->vars["min_$var"] != $this->vars["min_$var_bak"])) {
									$val = $this->get_val($obj, "", $i);
								}
								unset($this->vars["dia_$var"]);
								unset($this->vars["mes_$var"]);
								unset($this->vars["ano_$var"]);
								unset($this->vars["hor_$var"]);
								unset($this->vars["min_$var"]);
								unset($this->vars["dia_$var_bak"]);
								unset($this->vars["mes_$var_bak"]);
								unset($this->vars["ano_$var_bak"]);
								unset($this->vars["hor_$var_bak"]);
								unset($this->vars["min_$var_bak"]);
							} else if ($obj->type == "radio_checkbox") {
								if ($this->vars[$var] != $this->vars[$var_bak]) {
									$val = $this->get_val($obj, "", $i);
								}
								unset($this->vars[$var]);
								unset($this->vars[$var_bak]);
							} else if (isset($this->vars[$var])) {
								if ($this->vars[$var] != $this->vars[$var_bak]) {
									$val = $this->get_val($obj, "", $i);
								}
								unset($this->vars[$var]);
								unset($this->vars[$var_bak]);
							}
						}
						if (strlen((string)$val) > 0) {
							$field = $this->getFieldLbl($this->getFieldIx($obj->field));
							if (!empty($obj->ck_log)) {
								if ($val != "NULL") {
									if ($this->db == "MSSQL")
										$str_val[$update_key][$field] = "CASE WHEN $field IS NOT NULL THEN $field + CHAR(13) ELSE '' END + '$val'";
									else if ($this->db == "MYSQL")
										$str_val[$update_key][$field] = "CONCAT(CASE WHEN $field IS NOT NULL THEN CONCAT($field, CHAR(13)) ELSE '' END, '$val')";
								}
							} else {
								$str_val[$update_key][$field] = $val;
							}
						}
					}
				}
			}
			foreach ($str_val as $key => $vals) {
				$upd_str = "";
				$ins_str_field = "";
				$ins_str_val = "";
				$str_where = "";
				foreach ($vals as $f => $val) {
					$upd_str .= ($upd_str != "" ? ", " : "") . "$f = $val";
					$ins_str_field .= ($ins_str_field != "" ? ", " : "") . "$f";
					$ins_str_val .= ($ins_str_val != "" ? ", " : "") . "$val";
				}
				foreach (explode(",", $key) as $f) {
					$key_val = $this->format_sql_str($this->vars[$f.$i]);
					$ins_str_field .= ", $f";
					if ($key_val != "''") {
						$ins_str_val .= ", $key_val";
						$str_where .= ($str_where != "" ? " AND " : "") . "$f = " . $key_val;
					} else {
						$ins_str_val .= ", NULL";
						$str_where .= ($str_where != "" ? " AND " : "") . "$f IS NULL";
					}
				}
				if ($this->db == "MSSQL" && $tbl_val[$key] != $this->table && $val == "NULL") {
					$qry = "DELETE FROM " . $tbl_val[$key] . "
						WHERE $str_where";
				} else {
					$qry = "UPDATE " . $tbl_val[$key] . "
						SET $upd_str
						WHERE $str_where";
					$temp = explode(",", $key);
					if (count($temp) == 1 && $temp[0] == "id") {
						$pks[] = $this->vars["id$i"];
					}
				}
				$qry_str .= "$qry;\n";
				if ($this->db == "MSSQL" && $tbl_val[$key] != $this->table && $val != "NULL") {
					$qry = "IF (@@ROWCOUNT = 0)
						INSERT INTO " . $tbl_val[$key] . " ($ins_str_field)
						VALUES ($ins_str_val)";
					$qry_str .= "$qry;\n";
				}
				if ($this->db == "MYSQL") nc_query($qry);
			}
		}
		if ($qry_str != "") {
			if ($this->debug == 1) $this->show_debug($qry_str,"?");
			if ($this->db == "MSSQL") nc_query($qry_str,"NONQUERY");
		}
		if (isset($this->exec_input)) {
			$ex = $this->exec_input;
			foreach ($pks as $id) $ex($id);
		}
	}
	private function get_js() {
		$str = "";
		for ($i=0; $i<count($this->js_ref); $i++) {
			if (substr($this->js_ref[$i],0,7) == "http://" || substr($this->js_ref[$i],0,8) == "https://")
				$js = $this->js_ref[$i];
			else
				$js = $this->js_ref[$i] . (strpos($this->js_ref[$i],"?")?"&":"?") . "rand=" . rand(1,1000);
			$str .= "<script type=\"text/javascript\" src=\"$js\"></script>\n";
		}
		return $str;
	}
	private function get_js_unique() {
		$str  = "function ckUnique(ix,url,fields) {\n";
		$str .= "	url = parseUrl(url);\n";
		if ($this->modo == "update") $str .= "	url += \"&pk=$this->pk&val=" . $this->vars[$this->pk] . "\";\n";
		$str .= "	var xhr =  new XMLHttpRequest();\n";
		$str .= "	xhr.open('POST', url, true);\n";
		$str .= "	xhr.onreadystatechange = function() {\n";
		$str .= "		if (xhr.readyState == 4) {\n";
		$str .= "			var res = xhr.responseXML.getElementsByTagName('data');\n";
		$str .= "			var ck = parseFloat(getNodeValue(res[0], 'ck'));\n";
		$str .= "			document.formulario[\"ck_unique\"+ix].value = ck;\n";
		$str .= "			if (ck > 0) alert('$this->lang_js_unique ' + fields.join(','));\n";
		$str .= "		}\n";
		$str .= "	}\n";
		$str .= "	xhr.send(null);\n";
		$str .= "}\n";
		return $str;
	}
	private function get_js_mask() {
		$str  = "window.addEventListener('load', function() {\n";
		$str .= "	document.body.onpaste = function (e) {\n";
		$str .= "		if (e.target.tagName.toLowerCase() == \"input\" && e.target.maxLength && e.target.maxLength > 0) {\n";
		$str .= "			if (e.target.name.substr(-1) == 0) {\n";
		$str .= "				var basename = e.target.name.substr(0,e.target.name.length-1);\n";
		$str .= "				// fill field sequence\n";
		$str .= "				var c = 0; var p = 0;\n";
		$str .= "				while (f = e.target.form[basename+c]) {\n";
		$str .= "					f.value = e.clipboardData.getData('Text').substr(p, f.maxLength);\n";
		$str .= "					c++; p += f.maxLength;\n";
		$str .= "				}\n";
		$str .= "				// put cursor on last field\n";
		$str .= "				var f = e.target.form[basename+(c-1)];\n";
		$str .= "				f.focus();\n";
		$str .= "				f.selectionStart = f.selectionEnd = f.value.length;\n";
		$str .= "			}\n";
		$str .= "		}\n";
		$str .= "	}\n";
		$str .= "}, false);\n";
		return $str;
	}
	private function get_js_unlock() {
		$str  = "function toggleLock(src,ix,p,mask) {\n";
		$str .= "	var td = src.closest('TD')\n";
		$str .= "	if (document.getElementById(\"mask_\"+ix+p).style.display == 'none') {\n";
		$str .= "		var pat = \"\";\n";
		$str .= "		for (var i=0; i<mask.length; i++) {\n";
		$str .= "			pat += cknum(mask[i]) ? \"[0-9A-Za-z]{\"+mask[i]+\"}\" : mask[i];\n";
		$str .= "		}\n";
		$str .= "		var pat = \"^\" + pat + \"$\";\n";
		$str .= "		var ER = new RegExp(pat);\n";
		$str .= "		if (document.getElementById(ix+p).value != \"\" && !ER.test(document.getElementById(ix+p).value)) {\n";
		$str .= "			alert(\"O valor não coincide com a máscara.\")\n";
		$str .= "		} else {\n";
		$str .= "			var p_str = 0;\n";
		$str .= "			var c = 0;\n";
		$str .= "			for (var i=0; i<mask.length; i++) {\n";
		$str .= "				if (cknum(mask[i])) {\n";
		$str .= "					document.getElementById(ix+c+p).value = document.getElementById(ix+p).value.substring(p_str, p_str+mask[i]);\n";
		$str .= "					p_str += mask[i];\n";
		$str .= "					c++;\n";
		$str .= "				} else {\n";
		$str .= "					p_str += mask[i].length;\n";
		$str .= "				}\n";
		$str .= "			}\n";
		$str .= "			td.querySelector('#'+ix+p).value = val;\n";
		$str .= "			td.querySelector('#'+ix+p).disabled = true;\n";
		$str .= "			td.querySelector('#toggle_'+ix+p).src = \"$this->img_dir/$this->img_lock\";\n";
		$str .= "			td.querySelector('#mask_'+ix+p).style.display = '';\n";
		$str .= "			td.querySelector('#field_'+ix+p).style.display = 'none';\n";
		$str .= "		} \n";
		$str .= "	} else {\n";
		$str .= "		var val = \"\";\n";
		$str .= "		var c = 0;\n";
		$str .= "		for (var i=0; i<mask.length; i++) {\n";
		$str .= "			if (cknum(mask[i])) {\n";
		$str .= "				val += document.getElementById(ix+c+p).value;\n";
		$str .= "				c++;\n";
		$str .= "			} else if (val != \"\") {\n";
		$str .= "				val += mask[i];\n";
		$str .= "			}\n";
		$str .= "		}\n";
		$str .= "		td.querySelector('#'+ix+p).value = val;\n";
		$str .= "		td.querySelector('#'+ix+p).disabled = false;\n";
		$str .= "		td.querySelector('#toggle_'+ix+p).src = \"$this->img_dir/$this->img_unlock\";\n";
		$str .= "		td.querySelector('#mask_'+ix+p).style.display = 'none';\n";
		$str .= "		td.querySelector('#field_'+ix+p).style.display = '';\n";
		$str .= "	} \n";
		$str .= "}\n";
		return $str;
	}
	private function get_js_form($scope, $ref) {
		$function_sufix = ($scope == "list" ? "List" : "");
		$str = "";
		$str .= "function setRadioCb(src,params) {\n";
		$str .= "	var suffix = params.suffix ? params.suffix : '';\n";
		$str .= "	var form = src.form, c = 0, p = 0, str = '', ini = 'A'.charCodeAt(0);\n";
		$str .= "	while (form[params.field+String.fromCharCode(ini+p)+suffix]) {\n";
		$str .= "		var f = form[params.field+String.fromCharCode(ini+p)+suffix];\n";
		$str .= "		if (f != src && !params.multiple) f.checked = false;\n";
		$str .= "		if (f.checked) str += (str != '' ? ',' : '') + f.value;\n";
		$str .= "		p++;\n";
		$str .= "	}\n";
		$str .= "	if (str == '' && params.default) var str = params.default;\n";
		$str .= "	form[params.field+suffix].value = str;\n";
		$str .= "}\n";
		if ($scope == "list")
			$str .= $this->get_js_list_functions($ref);
		else
			$str .= $this->get_js_form_functions($scope, $ref);
		$str .= "function ckForm$function_sufix(form,cmd) {\n";
		$str .= "	var msg = \"\";\n";
		$ck_confirm_rule = 0;
		foreach ($this->form_rule as $rule) {
			if ($rule["scope"] == $scope) {
				if ($rule["ck_confirm"] == 1) {
					$str .= "\tvar msg_confirm = \"\";\n";
					$ck_confirm_rule = 1;
					break;
				}
			}
		}
		if ($scope == "list")
			$str .= $this->get_js_list_ckFormBody();
		else
			$str .= $this->get_js_form_ckFormBody($scope, $ref);
		if ($this->ck_file == 1) {
			$str .= "	msg += ckFileLimit(form," . substr(ini_get("post_max_size"),0,-1) . "," . substr(ini_get("upload_max_filesize"),0,-1) . ");\n";
		}
		$str .= "	if (msg != \"\") {\n";
		$str .= "		alert(msg);\n";
		$str .= "		return false;\n";
		$str .= "	} else {\n";
		if ($ck_confirm_rule == 1) $str .= "		if (msg_confirm == \"\" || confirm(msg_confirm + \"Deseja Prosseguir?\")) {\n";
		if (empty($this->list_form_trigger))
		$str .= "		waitSubmit{$function_sufix}(form);\n";
		$str .= "		if (cmd) { var ret = cmd(form); if (ret === false) return; }\n";
		if (!empty($this->ent_1XN))
		$str .= "		for (var elm of document.querySelectorAll('.form-entity-root')) elm.parentNode.removeChild(elm);\n";
		$str .= "		form.submit();\n";
		if ($ck_confirm_rule == 1) $str .= "		}\n";
		$str .= "	}\n";
		$str .= "}\n";
		if (empty($this->list_form_trigger)) {
			$str .= "function waitSubmit{$function_sufix}(form) {\n";
			$str .= "	form.ok.disabled=true;\n";
			$str .= "	document.getElementById(\"botao\").innerHTML = \"<span class=$this->css_text>$this->lang_wait</span>\";\n";
			$str .= "	return true;\n";
			$str .= "}\n";
		}
		foreach ($this->js_code as $code) {
			if ($code["scope"] == $scope) $str .= $code["str"]."\n";
		}
		return $str;
	}
	private function get_js_form_functions($scope, $ref) {
		$str = "";
		if ($this->ck_file) {
			/*
			$str .= "function ckFileLimit(form, form_limit, field_limit) {\n";
			$str .= "	var msg = \"\";\n";
			$str .= "	var tot = 0;\n";
			$str .= "	for (var i=0; i<form.length; i++) {\n";
			$str .= "		if (form[i].type == \"file\") {\n";
			$str .= "			for (var j=0; j<form[i].files.length; j++) {\n";
			$str .= "				if (form[i].files[j].size > field_limit*1024*1024) msg += \"O arquivo \" + form[i].files[j].name + \" (\" + formatnum(form[i].files[j].size/1024/1024,2,',','.') + \"Mb) excede o limite máximo permitido de \" + field_limit + \"Mb\\n\";\n";
			$str .= "				tot += form[i].files[j].size;\n";
			$str .= "			}\n";
			$str .= "		}\n";
			$str .= "	}\n";
			$str .= "	if (tot > form_limit*1024*1024) msg += \"O total dos arquivos anexados (\" + formatnum(tot/1024/1024,2,',','.') + \"Mb) excede o limite máximo permitido de \" + form_limit + \"Mb\\n\";\n";
			$str .= "	return msg;\n";
			$str .= "}\n";
			*/
			$str .= "function remFile(file) {\n";
			$str .= "	var holder = document.getElementById('holder-' + file);\n";
			$str .= "	var button = document.getElementById('but-rem-' + file);\n";
			$str .= "	var field = document.getElementById('f-rem-' + file);\n";
			$str .= "	var ck = !field.checked;\n";
			$str .= "	holder.style.textDecoration = ck ? 'line-through' : '';\n";
			$str .= "	holder.style.opacity = ck ? 0.3 : 1;\n";
			$str .= "	button.style.opacity = ck ? 0.3 : 1;\n";
			$str .= "	field.checked = ck;\n";
			$str .= "}\n";
		}
		if ($scope == "form" && count($this->publisher_ref) > 0 && count($this->ent_1XN) > 0) {
			$str .= "function _addPublisher() {\n";
			for ($i=0; $i<count($this->ent_1XN); $i++) {
				$p = $this->ent_1XN[$i];
				$ent_table = $this->ref[$p]["table"];
				$prefix = $this->ref[$p]["object"]->prefix;
				$str .= "	for (var i=0; i<document.formulario.count_{$prefix}.value; i++) {\n";
				for ($j=0; $j<count($this->ref[$p]["field"]); $j++) {
					if ($this->ref[$p]["object"]->field[$j]->type == "publisher") {
						$field = $prefix . "_" . $this->ref[$p]["object"]->field[$j]->field;
						$str .= "		eval(\"_$field\"+i+\" = new Publisher(\\\"$field\"+i+\"\\\", ck_register=true)\");\n";
						$str .= "		eval(\"_$field\"+i).setImgAction(\"../js/publisher_lib.img.php\");\n";
					}
				}
				$str .= "	}\n";
			}
			$str .= "	_initPublisher();\n";
			$str .= "}\n";
		}
		return $str;
	}
	function get_js_form_ckFormBody($scope, $ref) {
		$str = "";
		if ($this->ck_verify_email == 1)
			$str .= "	var mail_reg = new RegExp(\"^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]{2,64}(\.[a-z0-9-]{2,64})*\.[a-z]{2,4}\$\");\n";
		for ($i=0; $i<count($this->onsubmit_action); $i++) {
			$str .= "	" . $this->onsubmit_action[$i] . ";\n";
		}
		if ($scope == "form" && !empty($this->publisher_ref))
			$str .= "	feedPublisherContent(this.form);\n";
		if ($scope == "form" || $scope == "filter") {
			// define ref field
			for ($i=0; $i<count($ref); $i++) {
				$obj = $ref[$i]["object"];
				$label = strip_tags(str_replace("<br>"," ",$obj->label));
				if ($ref[$i]["field_group"] && $ref[$i]["field_group"]["label"] != $label) $label = $ref[$i]["field_group"]["label"] . (!empty($label) ? "/" . $label : "");
				$field = $obj->field;
				if (gettype($obj->field) == "string") $field = $this->getFieldLbl($field);
				// check req dependency
				if ($scope == "form" && !empty($ref[$i]["req_dependency"])) {
					//print_r($ref[$i]["req_dependency"]);
					$str .= "	if (";
					if (!empty($ref[$i]["req_dependency"])) {
						foreach ($ref[$i]["req_dependency"] as $dep) {
							for ($l=0; $l<count($ref); $l++) {
								$obj_dep = $ref[$l]["object"];
								if ($dep == $obj_dep->field ||
									($dep == "tab" && $ref[$i]["tab_id"] == $ref[$l]["tab_id"])) {
									if ($obj_dep->type == "checkbox") {
										$str .= "form." . $obj_dep->field . ".checked || ";
									} else if ($obj_dep->type == "text" && isset($obj_dep->mask)) {
										$c = 0;
										if ($obj_dep->unlock_mask == 1) $str .= "form." . $obj_dep->field . ".disabled && ";
										for ($m=0; $m<count($obj_dep->mask); $m++) {
											if (is_numeric($obj_dep->mask[$m])) {
												$str .= "form." . $obj_dep->field . "$c.value != \"\" || ";
												$c++;
											}
										}
									} else if ($obj_dep->type == "radio") {
										$str .= "getRadioValue(\"" . $obj_dep->field . "\") != \"\" || ";
									} else {
										$str .= "form." . $obj_dep->field . ".value != \"\" || ";
									}
								}
							}
						}
					}
					$str .= "1 == 0) { // req dependency\n";
				}
				if (!isset($obj->ck_readonly) || $obj->ck_readonly !== 1) {
					if (isset($this->tab_ref[$obj->tab_id])) {
						$label .= " (" . $this->tab_ref[$obj->tab_id]["label"] . ")";
						if ($obj->type != "entity" && $this->tab_ref[$obj->tab_id]["table"] != null) $field = $this->tab_ref[$obj->tab_id]["table"] . "_" . $field;
					}
					if ($obj->type == "entity" && $ref[$i]["rel"] == "1XN") {
						for ($j=0; $j<count($this->ent_1XN); $j++) {
							if ($this->ent_1XN[$j] == $i) break;
						}
						if (isset($obj->prefix))
							$ent_table = $obj->prefix;
						else
							$ent_table = $i . $ref[$i]["entity"]["table"];
						$str .= "	for (var i=0; i<form.count_$ent_table.value; i++) {\n";
						foreach ($ref[$i]["object"]->field as $obj_ent) {
							if ($obj_ent->type != "dbfield" &&
								$obj_ent->type != "checkbox" && 
								($obj_ent->ck_req == 1 || is_array($obj_ent->ck_req))) {
								$ck_req = $obj_ent->ck_req;
								if (is_array($ck_req)) {
									$ck_field = false;
									foreach ($ref[$i]["object"]->field as $src) {
										if ($src->field == $ck_req["field"]) {
											$ck_field = $src;
											break;
										}
									}
									if (!$ck_field) {
										foreach ($ref as $field) {
											$field = $field["object"];
											if ($field->field == $ck_req["field"]) {
												$ck_field = $field;
												break;
											}
										}
									}
									$str_ck_req = "";
									foreach (isset($ck_req["val"]) ? [$ck_req] : $ck_req as $ck_req) {
										if ($str_ck_req != "") $str_ck_req .= " " . $ck_req["bool"] . " ";
										$field_name = $ck_req["field"];
										$field_def = $ck_field->scope && $ck_field->scope == "entity_field" ? "form['" . $ent_table . "_$field_name'+i]" : "form.$field_name";
										if ($ck_field->type == "checkbox") {
											$str_ck_req .= (($ck_req["op"] == "==" && $ck_req["val"] == 0) || ($ck_req["op"] == "!=" && $ck_req["val"] == 1) ? "!" : "") . "$field_def.checked";
										} else {
											$field_val = $ck_field->type == "radio" ? "getRadioValue($field_def)" : "$field_def.value";
											if (is_array($ck_req["val"]))
												$str_ck_req .= str_replace('"',"'",json_encode($ck_req["val"])) . ".map(String).indexOf($field_val) " . ($ck_req["op"] == "==" ? ">= 0" : "< 0");
											else
												$str_ck_req .= $field_val . " " . $ck_req["op"] . " '" . $ck_req["val"] . "'";
										}
									}
									$str_ck_req = " && ($str_ck_req)";
								} else {
									$str_ck_req = "";
								}
								if (is_array($obj_ent->field)) {
									$str_field = "";
									foreach ($obj_ent->field as $field) {
										$str_field .= ($str_field != "" ? "_" : "") . $field;
									}
								} else {
									$str_field = $obj_ent->field;
								}
								$str_field = $ent_table . "_" . $str_field;
								if ($obj_ent->type == "text" && isset($obj_ent->mask)) {
									$str .= "		if (";
									$c = 0;
									if (isset($ref[$i]["entity_duplicate_fields"])) $str .= "document.getElementById(\"".$str_field."_\"+i) && ";
									if ($obj_ent->unlock_mask == 1) $str .= "form[\"$str_field\"+i].disabled && ";
									$str .= "(";
									for ($k=0; $k<count($obj_ent->mask); $k++) {
										if (is_numeric($obj_ent->mask[$k])) {
											$str .= ($c>0?" || ":"") . "form[\"".$str_field."$c\"+i].value.length != " . $obj_ent->mask[$k];
											$c++;
										}
									}
									$str .= "))\n";
									$str .= "			msg += \"$this->lang_js_field '" . strip_tags($obj_ent->label) . "' $this->lang_js_line \" + (i+1) + \" de " . trim(strip_tags($ref[$i]["label"])) . " $this->lang_js_format.\\n\";\n";
								} else if ($obj_ent->type == "radio") {
									if (isset($ref[$i]["entity_duplicate_fields"])) $str .= "		if (document.getElementById(\"$str_field\"+i)) {\n";
									$str .= "		var ck = 0\n";
									$str .= "		for (var j=0; j<form[\"$str_field\"+i].length; j++) {\n";
									$str .= "			if (form[\"$str_field\"+i][j].checked == true) var ck = 1;\n";
									$str .= "		}\n";
									$str .= "		if (ck == 0 $str_ck_req)\n";
									$str .= "			msg += \"$this->lang_js_field '" . strip_tags($obj_ent->label) . "' $this->lang_js_line \" + (i+1) + \" de " . trim(strip_tags($ref[$i]["label"])) . " $this->lang_js_req.\\n\";\n";
									if (isset($ref[$i]["entity_duplicate_fields"])) $str .= "		}\n";
								} else if ($obj_ent->type == "date" || $obj_ent->type == "datetime" || $obj_ent->type == "month") {
									if (!empty($obj_ent->ck_req)) {
										$str .= "		if ((form[\"dia_$str_field\"+i].value == \"\" || form[\"mes_$str_field\"+i].value == \"\" || form[\"ano_$str_field\"+i].value == \"\") $str_ck_req)\n";
										$str .= "			msg += \"$this->lang_js_field '" . strip_tags($obj_ent->label) . "' $this->lang_js_line \" + (i+1) + \" de " . trim(strip_tags($ref[$i]["label"])) . " " . ($obj_ent->ck_req == 1 ? "$this->lang_js_req," : "") . " $this->lang_js_req.\\n\";\n";
										$str .= "		else if (!ckdata(form[\"dia_$str_field\"+i].value,form[\"mes_$str_field\"+i].value,form[\"ano_$str_field\"+i].value) $str_ck_req)\n";
										$str .= "			msg += \"$this->lang_js_field '" . strip_tags($obj_ent->label) . "' $this->lang_js_line \" + (i+1) + \" de " . trim(strip_tags($ref[$i]["label"])) . " " . ($obj_ent->ck_req == 1 ? "$this->lang_js_req," : "") . " $this->lang_js_format.\\n\";\n";
									} else {
										$str .= "		if (" . (isset($ref[$i]["entity_duplicate_fields"]) ? "form[\"dia_$str_field\"+i] && " : "") . "(form[\"dia_$str_field\"+i].value != \"\" || form[\"mes_$str_field\"+i].value != \"\" || form[\"ano_$str_field\"+i].value != \"\") && !ckdata(form[\"dia_$str_field\"+i].value,form[\"mes_$str_field\"+i].value,form[\"ano_$str_field\"+i].value))\n";
										$str .= "			msg += \"$this->lang_js_field '" . strip_tags($obj_ent->label) . "' $this->lang_js_line \" + (i+1) + \" de " . trim(strip_tags($ref[$i]["label"])) . " " . ($obj_ent->ck_req == 1 ? "$this->lang_js_req," : "") . " $this->lang_js_format.\\n\";\n";
									}
									if (!empty($obj_ent->min_value)) {
										$str .= "		else if (new Date(form[\"ano_$str_field\"+i].value,form[\"mes_$str_field\"+i].value-1,form[\"dia_$str_field\"+i].value) < new Date(".date("Y,n-1,j", $obj_ent->min_value)."))\n";
										$str .= "			msg += \"$this->lang_js_field '" . strip_tags($obj_ent->label) . "' $this->lang_js_line \" + (i+1) + \" de " . trim(strip_tags($ref[$i]["label"])) . " " . " $this->lang_js_min " . date("d/m/Y",$obj_ent->min_value) . ".\\n\";\n";
									} else if (!empty($obj_ent->max_value)) {
										$str .= "		else if (new Date(form[\"ano_$str_field\"+i].value,form[\"mes_$str_field\"+i].value-1,form[\"dia_$str_field\"+i].value) > new Date(".date("Y,n-1,j", $obj_ent->max_value)."))\n";
										$str .= "			msg += \"$this->lang_js_field '" . strip_tags($obj_ent->label) . "' $this->lang_js_line \" + (i+1) + \" de " . trim(strip_tags($ref[$i]["label"])) . " " . " $this->lang_js_max " . date("d/m/Y",$obj_ent->max_value) . ".\\n\";\n";
									}
								} else if ($obj_ent->type == "daterange") {
									if (isset($ref[$i]["entity_duplicate_fields"])) $str .= "		if (form[\"dia_".$str_field."_ini\"+i]) {\n";
									//$str .= "		if (" . ($obj_ent->ck_req == 0 ? "(form[\"dia_" . $str_field . "_ini\"+i]\"+i).value != \"\" || form[\"mes_" . $str_field . "_ini\"+i].value != \"\" || form[\"ano_" . $str_field . "_ini\"+i].value != \"\") && " : "") . "(form[\"" . $str_field . "+i].checked && (!ckdata(form[\"dia_" . $str_field . "_ini\"+i].value,form[\"mes_" . $str_field . "_ini\"+i].value,form[\"ano_" . $str_field . "_ini\"+i].value))) $str_ck_req)\n";
									//$str .= "			msg += \"$this->lang_js_field '" . strip_tags($obj_ent->label) . "' $this->lang_js_line \" + (i+1) + \" de " . trim(strip_tags($ref[$i]["label"])) . " " . ($obj_ent->ck_req == 1 ? "$this->lang_js_req," : "") . " $this->lang_js_format.\\n\";\n";
									$str .= "		if (form[\"" . $str_field . "+i].checked && (!ckdata(form[\"dia_" . $str_field . "_ini\"+i].value,form[\"mes_" . $str_field . "_ini\"+i].value,form[\"ano_" . $str_field . "_ini\"+i].value) || !ckdata(form[\"dia_" . $str_field . "_fim\"+i].value,form[\"mes_" . $str_field . "_fim\"+i].value,form[\"ano_" . $str_field . "_fim\"+i].value)) $str_ck_req)\n";
									$str .= "			msg += \"$this->lang_js_field '" . strip_tags($obj_ent->label) . "' $this->lang_js_line \" + (i+1) + \" de " . trim(strip_tags($ref[$i]["label"])) . " " . ($obj_ent->ck_req == 1 ? "$this->lang_js_req," : "") . " $this->lang_js_format.\\n\";\n";
									if (isset($ref[$i]["entity_duplicate_fields"])) $str .= "		}\n";
								} else if ($obj_ent->type == "hour") {
									$str .= "		if (" . (isset($ref[$i]["entity_duplicate_fields"]) ? "form[\"hor_$str_field\"+i] && " : "") . "form[\"hor_$str_field\"+i].value == \"\" && form[\"min_$str_field\"+i].value == \"\" $str_ck_req)\n";
									$str .= "			msg += \"$this->lang_js_field '" . strip_tags($obj_ent->label) . "' $this->lang_js_line \" + (i+1) + \" de " . trim(strip_tags($ref[$i]["label"])) . " $this->lang_js_req.\\n\";\n";
								} else if ($obj_ent->type == "dropdown") {
									$str .= "		if (" . (isset($ref[$i]["entity_duplicate_fields"]) ? "form[\"$str_field\"+i] && " : "") . "form[\"$str_field\"+i].value == \"\" $str_ck_req)\n";
									$str .= "			msg += \"$this->lang_js_field '" . strip_tags($obj_ent->label) . "' $this->lang_js_line \" + (i+1) + \" de " . trim(strip_tags($ref[$i]["label"])) . " $this->lang_js_req.\\n\";\n";
								} else if ($obj_ent->type == "publisher") {
									if (isset($ref[$i]["entity_duplicate_fields"])) $str .= "		if (document.getElementById(\"obj$str_field\"+i)) {\n";
									$str .= "		if (document.getElementById(\"obj$str_field\"+i).contentWindow.document.body.innerText) // IE, Safari\n";
									$str .= "			var publisher_text = document.getElementById(\"obj$str_field\"+i).contentWindow.document.body.innerText;\n";
									$str .= "		else // Firefox\n";
									$str .= "			var publisher_text = document.getElementById(\"obj$str_field\"+i).contentWindow.document.body.textContent;\n";
									$str .= "		if (publisher_text == \"\")\n";
									$str .= "			msg += \"$this->lang_js_field '" . strip_tags($obj_ent->label) . "' $this->lang_js_line \" + (i+1) + \" de " . trim(strip_tags($ref[$i]["label"])) . " $this->lang_js_req.\\n\";\n";
									if (isset($ref[$i]["entity_duplicate_fields"])) $str .= "		}\n";
								} else if ($obj_ent->type == "file") {
									$str .= "		if (" . (isset($ref[$i]["entity_duplicate_fields"]) ? "form[\"$str_field\"+i] && " : "") . "form[\"id_".$ent_table."\"+i].value == '0' && form[\"$str_field\"+i].value == \"\" $str_ck_req)\n";
									$str .= "			msg += \"$this->lang_js_field '" . strip_tags($obj_ent->label) . "' $this->lang_js_line \" + (i+1) + \" de " . trim(strip_tags($ref[$i]["label"])) . " $this->lang_js_req.\\n\";\n";
								} else {
									$str .= "		if (" . (isset($ref[$i]["entity_duplicate_fields"]) ? "form[\"$str_field\"+i] && " : "") . "form[\"$str_field\"+i].value == \"\" $str_ck_req)\n";
									$str .= "			msg += \"$this->lang_js_field '" . strip_tags($obj_ent->label) . "' $this->lang_js_line \" + (i+1) + \" de " . trim(strip_tags($ref[$i]["label"])) . " $this->lang_js_req.\\n\";\n";
								}
							}
						}
						foreach ($this->form_rule as $rule) {
							if ($rule["scope"] == "entity") {
								if ($rule["ck_confirm"] == 1) {
									$var = "msg_confirm";
								} else {
									$var = "msg";
								}
								if (strtoupper($rule["msg"]) == "FUNCTION") {
									$str .= "		var str = " . $rule["condition"] . "\n";
									$str .= "		if (str) $var += str\n";
								} else {
									$str .= "		if (" . $rule["condition"] . ")\n";
									$str .= "			$var += \"" . $rule["msg"] . "\\n\";\n";
								}
							}
						}
						$str .= "	}\n";
					} else {
						$ck_req = $obj->ck_req;
						$str_ck_req = $this->getJsCkReq($ck_req, $ref);
						if ($obj->type == "daterange" || ($scope == "filter" && ($obj->type == "date" || $obj->type == "datetime"))) {
							//$str .= "	if (" . ($obj->ck_req != 1 ? "(form.dia_" . $field . "_ini.value != \"\" || form.mes_" . $field . "_ini.value != \"\" || form.ano_" . $field . "_ini.value != \"\") && " : "") . "(form.$field.checked && (!ckdata(form.dia_" . $field . "_ini.value,form.mes_" . $field . "_ini.value,form.ano_" . $field . "_ini.value))))\n";
							//$str .= "		msg += \"$this->lang_js_field \\\"$label\\\" " . ($obj->ck_req == 1 ? "$this->lang_js_req," : "") . " $this->lang_js_format.\\n\";\n";
							$str .= "	if (form.$field.checked && (!ckdata(form.dia_" . $field . "_ini.value,form.mes_" . $field . "_ini.value,form.ano_" . $field . "_ini.value) || !ckdata(form.dia_" . $field . "_fim.value,form.mes_" . $field . "_fim.value,form.ano_" . $field . "_fim.value)))\n";
							$str .= "		msg += \"$this->lang_js_field \\\"$label\\\" " . ($obj->ck_req == 1 ? "$this->lang_js_req," : "") . " $this->lang_js_format.\\n\";\n";
						} else if (($obj->type == "date" || $obj->type == "datetime") && $this->pref_field_date == "text") {
							if (!empty($ck_req)) {
								$str .= "	if ((form.dia_$field.value == \"\" || form.mes_$field.value == \"\" || form.ano_$field.value == \"\") $str_ck_req)\n";
								$str .= "		msg += \"$this->lang_js_field \\\"$label\\\" $this->lang_js_req.\\n\";\n";
							}
							$str .= "	" . (!empty($ck_req) ? "else " : "") . "if ((form.dia_$field.value != \"\" || form.mes_$field.value != \"\" || form.ano_$field.value != \"\") && !ckdata(form.dia_$field.value,form.mes_$field.value,form.ano_$field.value))\n";
							$str .= "		msg += \"$this->lang_js_field \\\"$label\\\" $this->lang_js_format.\\n\";\n";
							if ($obj->type == "datetime") {
								$str .= "	if (" . ($obj->ck_req != 1 ? "(form.dia_$field.value != \"\" || form.mes_$field.value != \"\" || form.ano_$field.value != \"\") && " : "") . "(form.hor_$field.value == \"\" || form.hor_$field.value >= 24 || form.min_$field.value == \"\" || form.min_$field.value >= 60))\n";
								$str .= "		msg += \"$this->lang_js_field \\\"$label\\\" " . ($obj->ck_req == 1 ? "$this->lang_js_req," : "") . " $this->lang_js_incomplete.\\n\";\n";
							}
							if (!empty($obj->min_value)) {
								$str .= "	else if (new Date(form.ano_$field.value,form.mes_$field.value-1,form.dia_$field.value) < new Date(".date("Y,n-1,j", $obj->min_value)."))\n";
								$str .= "		msg += \"$this->lang_js_field \\\"$label\\\" $this->lang_js_min " . date("d/m/Y",$obj->min_value) . ".\\n\";\n";
							} else if (!empty($obj->max_value)) {
								$str .= "	else if (new Date(form.ano_$field.value,form.mes_$field.value-1,form.dia_$field.value) > new Date(".date("Y,n-1,j", $obj->max_value)."))\n";
								$str .= "		msg += \"$this->lang_js_field \\\"$label\\\" $this->lang_js_max " . date("d/m/Y",$obj->max_value) . ".\\n\";\n";
							}
						} else if ($obj->type == "monthrange" || ($scope == "filter" && $obj->type == "month")) {
							//$str .= "	if (" . ($obj->ck_req == 0 ? "(form.mes_" . $field . "_ini.value != \"\" || form.ano_" . $field . "_ini.value != \"\") && " : "") . "(form.$field.checked && (!ckdata(1,form.mes_" . $field . "_ini.value,form.ano_" . $field . "_ini.value))))\n";
							//$str .= "		msg += \"$this->lang_js_field \\\"$label\\\" " . ($obj->ck_req == 1 ? "$this->lang_js_req," : "") . " $this->lang_js_format.\\n\";\n";
							$str .= "	if (form.$field.checked && (!ckdata(1,form.mes_" . $field . "_ini.value,form.ano_" . $field . "_ini.value) || !ckdata(1,form.mes_" . $field . "_fim.value,form.ano_" . $field . "_fim.value)))\n";
							$str .= "		msg += \"$this->lang_js_field \\\"$label\\\" " . ($obj->ck_req == 1 ? "$this->lang_js_req," : "") . " $this->lang_js_format.\\n\";\n";
						} else if ($obj->type == "month") {
							if (!empty($ck_req)) {
								$str .= "	if ((form.mes_$field.value == \"\" || form.ano_$field.value == \"\") $str_ck_req)\n";
								$str .= "		msg += \"$this->lang_js_field \\\"$label\\\" $this->lang_js_req.\\n\";\n";
							}
							$str .= "	" . (!empty($ck_req) ? "else " : "") . "if ((form.mes_$field.value != \"\" || form.ano_$field.value != \"\") && !ckdata(1,form.mes_$field.value,form.ano_$field.value))\n";
							$str .= "		msg += \"$this->lang_js_field \\\"$label\\\" $this->lang_js_format.\\n\";\n";
						} else if ($obj->type == "hour") {
							$str .= "	if (form.hor_".$field . ".value == \"\" && form.min_$field.value == \"\")\n";
							$str .= "		msg += \"$this->lang_js_field \\\"$label\\\" $this->lang_js_req.\\n\";\n";
						} else if ($obj->type == "file") {
							$str .= "	if (form.$field.type == 'file') {\n";
							if (!empty($ck_req)) {
								$str .= "	if (" . ($this->modo == "update" ? "form." . $field . "_bak.value == '' && " : "") . "form.$field.files.length == 0 $str_ck_req)\n";
								$str .= "		msg += \"$this->lang_js_field \\\"$label\\\" $this->lang_js_req.\\n\";\n";
							}
							if (count($obj->file) > 0 && $obj->file[0]["maxsize"]) {
								$str .= "	if (form.$field.value != \"\" && ['jpeg','jpg','png','gif'].indexOf(form.".$field . ".value.toLowerCase().split('.').slice(-1)[0]) < 0)\n";
								$str .= "		msg += \"$this->lang_js_file \\\"$label\\\", $this->lang_js_file_auth: (JPG, PNG, GIF).\\n\";\n";
							} else {
								$str .= "	for (var file of form.$field.files) if (['php','exe','bat','dll'].indexOf(file.name.toLowerCase().split('.').slice(-1)[0]) >= 0)\n";
								$str .= "		msg += \"$this->lang_js_file \\\"$label\\\": \" + file.name +  \"\\n\";\n";
							}
							$str .= "	}\n";
						} else if ($obj->type != "entity" && 
							$obj->type != "dbfield" &&
							$obj->type != "display" &&
							$obj->type != "checkbox" && 
							($this->modo == "insert" || $obj->type != "file") &&
							(!empty($ck_req))) {
							if ($obj->type == "text" && isset($obj->mask)) {
								$str .= "	if (";
								$c = 0;
								if ($obj->unlock_mask == 1) $str .= "form.$field.disabled && ";
								$str_req = "";
								$str_len = "";
								$str_fill = "";
								foreach ($obj->mask as $mask) {
									if (is_numeric($mask)) {
										$str_req .= ($c>0 ? " || " : "") . "form." . $field . "$c.value == ''";
										$str_len .= ($c>0 ? " && " : "") . "form." . $field . "$c.value != ''";
										$str_fill .= ($c>0 ? " || ":"") . "form." . $field . "$c.value.length != " . $mask;
										$c++;
									}
								}
								if ($obj->ck_req == 1 || is_array($obj->ck_req))
									$str .= "($str_req || ($str_len && ($str_fill))) $str_ck_req";
								else
									$str .= "($str_len && ($str_fill)) $str_ck_req";
								$str .= ")\n";
								$str .= "		msg += \"$this->lang_js_field \\\"$label\\\" $this->lang_js_format.\\n\";\n";
							} else if ($obj->type == "radio" && $obj->ck_readonly == 0) {
								$str .= "	var ck = 0\n";
								$str .= "	for (var i=0; i<form.$field.length; i++) {\n";
								$str .= "		if (form." . $field . "[i].checked == true) var ck = 1;\n";
								$str .= "	}\n";
								$str .= "	if (ck == 0 $str_ck_req)\n";
								$str .= "		msg += \"$this->lang_js_field \\\"$label\\\" $this->lang_js_req.\\n\";\n";
								$list = $obj->list["vals"];
								$c = 0;
								foreach ($list as $item) {
									if (is_array($item) && $item["type"] == "dropdown") {
										$str .= "		if (form." . $field . "[$c].checked && form." . $item["field"] . ".selectedIndex == 0 $str_ck_req)\n";
										$str .= "			msg += \"$this->lang_js_field \\\"" . $item["label"] . "\\\" $this->lang_js_req.\\n\";\n";
									}
									if (is_array($item) && $item["type"] == "text") {
										$str .= "		if (form." . $field . "[$c].checked && form." . $item["field"] . ".selectedIndex == \"\" $str_ck_req)\n";
										$str .= "			msg += \"$this->lang_js_field \\\"" . $item["label"] . "\\\" $this->lang_js_req.\\n\";\n";
									}
									$c++;
								}
							} else if ($obj->type == "publisher") {
								$str .= "	if (document.getElementById(\"obj" . $field . "\").contentWindow.document.body.innerText) // IE, Safari\n";
								$str .= "		var publisher_text = document.getElementById(\"obj" . $field . "\").contentWindow.document.body.innerText;\n";
								$str .= "else // Firefox\n";
								$str .= "		var publisher_text = document.getElementById(\"obj" . $field . "\").contentWindow.document.body.textContent;\n";
								$str .= "	if (publisher_text == \"\" $str_ck_req)\n";
								$str .= "		msg += \"$this->lang_js_field \\\"$label\\\" $this->lang_js_req.\\n\";\n";
							} else if ($obj->type == "dropdown" && !empty($obj->prop["multiple"])) {
								$str .= "	if (form.$field.selectedIndex == -1 $str_ck_req)\n";
								$str .= "		msg += \"$this->lang_js_field \\\"$label\\\" $this->lang_js_req.\\n\";\n";
							} else {
								$str .= "	if (form.$field.value == \"\" $str_ck_req)\n";
								$str .= "		msg += \"$this->lang_js_field \\\"$label\\\" $this->lang_js_req.\\n\";\n";
							}
							if (isset($obj->pattern)) {
								$str .= "	if (form.$field.value != \"\" && !/^$obj->pattern\$/.test(form.$field.value))\n";
								$str .= "		msg += \"$label com valor inválido. Permitido \\\"$obj->pattern\\\".\\n\";\n";
							}
						}
					}
				}
				if ($scope == "form" && count($ref[$i]["req_dependency"]) > 0) {
					$str .= "	}\n";
				}
			} // for ($i=0; $i<count($ref); $i++) {
		} else if ($scope == "filter" && 
			$this->step == 0) {
			for ($i=0; $i<count($ref); $i++) {
				$obj = $ref[$i]["object"];
				if ($obj->type == "date" || $obj->type == "datetime") {
					$str .= "	if (form." . $obj->field . ".checked && (!ckdata(form.dia_" . $obj->field . "_ini.value,form.mes_" . $obj->field . "_ini.value,form.ano_" . $obj->field . "_ini.value) || !ckdata(form.dia_" . $obj->field . "_fim.value,form.mes_" . $obj->field . "_fim.value,form.ano_" . $obj->field . "_fim.value)))\n";
					$str .= "		msg += \"$this->lang_js_field \\\"$obj->label\\\" " . ($obj->ck_req == 1 ? "$this->lang_js_req," : "") . " $this->lang_js_format.\\n\";\n";
				} else if ($obj->type == "daterange" || ($scope == "filter" && ($obj->type == "date" || $obj->type == "datetime"))) {
					$str .= "	if (form." . $obj->field . ".checked && (!ckdata(form.dia_" . $obj->field . "_ini.value,form.mes_" . $obj->field . "_ini.value,form.ano_" . $obj->field . "_ini.value) || !ckdata(form.dia_" . $obj->field . "_fim.value,form.mes_" . $obj->field . "_fim.value,form.ano_" . $obj->field . "_fim.value)))\n";
					$str .= "		msg += \"$this->lang_js_field \\\"$obj->label\\\" " . ($obj->ck_req == 1 ? "$this->lang_js_req," : "") . " $this->lang_js_format.\\n\";\n";
				}
			}
		}
		if ($scope == "form") {
			foreach ($this->form_objs as $obj) {
				if ($obj->type == "entity" && !empty($obj->ck_req)) {
					$str_ck_req = $this->getJsCkReq($obj->ck_req, $ref);
					$str .= "	if (form.count_" . $obj->prefix . ".value == 0 $str_ck_req)/**/\n";
					$str .= "		msg += \"" . $this->lang_js_ent . " \\\"" . $obj->label . ($obj->tab_id >= 0 ? " (" . $this->tab_ref[$obj->tab_id]["label"] . ")" : "") . "\\\".\\n\";\n";
				}
			}
		}
		foreach ($this->form_rule as $rule) {
			if ($rule["scope"] == $scope) {
				if ($rule["ck_confirm"] == 1) {
					$var = "msg_confirm";
				} else {
					$var = "msg";
				}
				if (strtoupper($rule["msg"]) == "FUNCTION") {
					$str .= "	var str = " . $rule["condition"] . "\n";
					$str .= "	if (str) $var += str\n";
				} else {
					$str .= "	if (" . $rule["condition"] . ")\n";
					$str .= "		$var += \"" . $rule["msg"] . "\\n\";\n";
				}
			}
		}
		return $str;
	}
	private function get_js_list_functions($ref) {
		$str = "";
		$str .= "function markTr(tr,color) {\n";
		$str .= "	var tds = tr.getElementsByTagName('TD');\n";
		$str .= "	for (var i=0; i<tds.length; i++) {\n";
		$str .= "		if (color)\n";
		$str .= "			tds[i].style.color = color;\n";
		$str .= "		else if (tds[i].style.removeProperty)\n";
		$str .= "			tds[i].style.removeProperty('color');\n";
		$str .= "		else if (tds[i].style.removeAttribute)\n";
		$str .= "			tds[i].style.removeAttribute('color');\n";
		$str .= "	}\n";
		$str .= "}\n";
		if ($this->ck_collapse == 1) {
			$str .= "function chDisplay(id, ck) {\n";
			$str .= "	var id_level = id.split(\"-\").length\n";
			$str .= "	var tr_ref = document.getElementsByTagName('TR');\n";
			$str .= "	var _display = false;\n";
			$str .= "	for (var i=0; i<tr_ref.length; i++) {\n";
			$str .= "		var tr_level = tr_ref[i].id.replace(/-row[0-9]+/,'').split(\"-\").length;\n";
			$str .= "		if (tr_ref[i].id.substr(0,id.length) == id && tr_ref[i].id.substr(id.length,1) == \"-\" && (_display == \"none\" || tr_level == id_level + 1)) {\n";
			$str .= "			if (!_display) _display = (typeof ck != 'undefined' ? !ck : tr_ref[i].style.display == '') ? 'none' : '';\n";
			$str .= "			tr_ref[i].style.display = _display;\n";
			$str .= "		}\n";
			$str .= "	}\n";
			$str .= "}\n";
			$str .= "function chAllDisplay(ck, prefix) {\n";
			$str .= "	if (!prefix) var prefix = 'trline';\n";
			$str .= "	document.getElementById('uncollapse').style.display = ck ? 'none' : '';\n";
			$str .= "	document.getElementById('collapse').style.display = ck   ? '' : 'none';\n";
			$str .= "	var c = 1;\n";
			$str .= "	function cmd() {\n";
			$str .= "		setTimeout(function() {\n";
			$str .= "			chDisplay(prefix + c, ck);\n";
			$str .= "			if (document.getElementById(prefix + c + '-1')) chAllDisplay(ck, prefix + c + '-');\n";
			$str .= "			c++;\n";
			$str .= "			if (document.getElementById(prefix + c)) cmd();\n";
			$str .= "		}, 10)\n";
			$str .= "	}\n";
			$str .= "	cmd();\n";
			$str .= "}\n";
		} else if ($this->ck_recursive != 0) {
			$str .= "function chDisplay(list) {\n";
			$str .= "	for (var i=0; i<list.length; i++) {\n";
			$str .= "		if (document.getElementById(list[i])) {\n";
			$str .= "			obj = document.getElementById(list[i])\n";
			$str .= "			obj.style.display = obj.style.display == '' ? 'none' : ''\n";
			$str .= "		}\n";
			$str .= "	}\n";
			$str .= "}\n";
		}
		if ($this->ck_xls == 1) {
			$str .= "function gotoExcel() {\n";
			$str .= "	if (!document.export_xls) alert(\"Aguarde o carregamento completo da página\");\n";
			$str .= "	var html = document.getElementById('tbl-cls_form').outerHTML;\n";
			$str .= "	html = html.replaceAll('display:none','').replaceAll('display: none','').replaceAll('DISPLAY: none','');\n";
			$str .= "	html = html.replace(/<img ([^>]+)>/gi,'');\n";
			$str .= "	html = html.replace(/<form ([^>]+)>/gi,'');\n";
			$str .= "	html = html.replace(/<\/form>/gi,'');\n";
			$str .= "	html = html.replace(/<input ([^>]+)hidden([^>]+)>/gi,'');\n";
			$str .= "	html = html.replace(/<input ([^>]+)checkbox([^>]+)>Excluir/gi,'');\n";
			$str .= "	document.export_xls.html.value = Base64.encode(html);\n";
			$str .= "	console.log(document.export_xls.html.value.length);\n";
			$str .= "	document.export_xls.submit();\n";
			$str .= "}\n";
		}
		if ($this->modo == "delete" || $this->modo == "complete" || $this->modo == "updatedelete") {
			$str .= "function markDelete(e) {\n";
			$str .= "	var ck = e.checked;\n";
			$str .= "	while(e.tagName != 'TR'){e=e.parentNode};\n";
			$str .= "	e.style.textDecoration = ck ? 'line-through' : 'none';\n";
			$str .= "}\n";
		}
		return $str;
	}
	function get_js_list_ckFormBody() {
		$str = "";
		$ck_form = 0;
		foreach ($this->ref_list as $obj) {
			if (!empty($obj->ck_req)) $ck_form = 1;
		}
		foreach ($this->form_rule as $rule) {
			if ($rule["scope"] == "label") $ck_form = 1;
		}
		if ($ck_form == 1) {
			$str .= "	for (var i=0; i<form.count.value; i++) {\n";
			$str .= "		var msg_row = \"\"\n";
			foreach ($this->ref_list as $obj) {
				if (!empty($obj->ck_req)) {
					$str .= "		if (form['" . $this->getFieldLbl($obj->field) . "'+i].value == \"\" " . $this->getJsCkReq($obj->ck_req, $this->ref_list, "i") . ")\n";
					$str .= "			msg_row += \" - " . $obj->label . " $this->lang_js_req.\\n\";\n";
				}
			}
			foreach ($this->form_rule as $rule) {
				if ($rule["scope"] == "label") {
					$str .= "		if (" . $rule["condition"] . ")\n";
					$str .= "			msg_row += \" - " . $rule["msg"] . "\\n\";\n";
				}
			}
			$str .= "		if (msg_row != \"\") msg += \"Linha \" + (i+1) + \":\\n\" + msg_row;\n";
			$str .= "	}\n";
		}
		return $str;
	}
	private function getJsCkReq($ck_req, $ref, $ix=false) {
		if (is_array($ck_req)) {
			$str_ck_req = "";
			foreach ((isset($ck_req["val"]) ? [$ck_req] : $ck_req) as $ck_req) {
				$field_name = $ck_req["field"];
				if ($ix)
					$str_field = "form['$field_name'+$ix]";
				else
					$str_field = "form.$field_name";
				foreach ($ref as $src) {
					if (gettype($src) == "array" && isset($src["object"])) $src = $src["object"];
					if (!is_array($src->field) && $this->getFieldLbl($src->field) == $field_name) break;
				}
				if ($str_ck_req != "") $str_ck_req .= " " . $ck_req["bool"] . " ";
				if ($src->type == "checkbox") {
					$str_ck_req .= (($ck_req["op"] == "==" && $ck_req["val"] == 0) || ($ck_req["op"] == "!=" && $ck_req["val"] == 1) ? "!" : "") . "$str_field.checked";
				} else {
					// $field_val = $src->type == "radio" ? "getRadioValue($str_field)" : "$str_field.value";
					if (is_array($ck_req["val"]))
						$str_ck_req .= str_replace('"',"'",json_encode(array_map("utf8_encode",$ck_req["val"]))) . ".map(String).indexOf($str_field.value) " . ($ck_req["op"] == "==" ? ">= 0" : "< 0");
					else
						$str_ck_req .= $str_field . ".value " . $ck_req["op"] . " '" . $ck_req["val"] . "'";
				}
			}
			$str_ck_req = " && ($str_ck_req)";
		} else {
			$str_ck_req = "";
		}
		return $str_ck_req;
	}
	private function get_paging($total) {
		$str  = "<form method=\"" . $this->pageby_method . "\">\n";
		$str .= "P&aacute;gina <select class=\"$this->css_formpeq\" onchange=\"pagina.value=this.value;submit()\">\n";
		for ($i=0; $i<ceil($total/$this->pageby); $i++) {
			$str .= "<option " . ($this->vars["pagina"] == $i+1 ? "SELECTED" : "") . " value=" . ($i+1) . ">" . ($i+1) . "\n";
		}
		$str .= "</select> de " . ceil($total/$this->pageby) . "\n";
		if ($this->vars["pagina"] > 1) $str .= "<input type=\"button\" value=\"&lt;&lt; Anterior\" class=\"$this->css_button\" onclick=\"pagina.value=parseFloat(pagina.value)-1;submit();\">\n";
		if ($this->vars["pagina"] < ceil($total/$this->pageby)) $str .= "<input type=\"button\" value=\"Pr&oacute;xima &gt;&gt;\" class=\"$this->css_button\" onclick=\"pagina.value=parseFloat(pagina.value)+1;submit();\">\n";
		foreach ($_REQUEST as $ix => $val) {
			if ($ix != "pagina" && $ix != "get_session_filter") {
				if (is_array($val)) {
					foreach ($val as $arr_val) $str .= "<input type=\"hidden\" name=\"" . $ix . "[]\" value=\"$arr_val\">\n";
				} else {
					$str .= "<input type=\"hidden\" name=\"$ix\" value=\"$val\">\n";
				}
			}
		}
		$str .= "<input type=\"hidden\" name=\"pagina\" value=\"" . $this->vars["pagina"] . "\">\n";
		$str .= "</form>\n";
		return $str;
	}
	private function get_form_button($scope) {
		if (($this->modo == "updatedelete" || $this->modo == "complete") && isset($this->vars["modo"]))
			$modo = $this->vars["modo"];
		else
			$modo = $this->modo;
		if ($this->ck_print == 1) {
			$str = "";
			if ($this->use_print_close == 1) $str .= "<span id=\"botao\"><input type=button value=\"$this->lang_close_button_label\" onclick=javascript:self.close() name=ok class=\"$this->css_button\"></span>\n";
			if ($this->use_print_button == 1) $str .= "<span id=\"botao\"><input type=button value=\"$this->lang_print_button_label\" onclick=javascript:self.print() name=ok class=\"$this->css_button\"></span>\n";
			return $str;
		} else {
			if ($scope == "form")
				$str  = "<span id=\"botao\"><input type=\"button\" value=\"$this->lang_button_label\" onclick=\"ckForm(this.form)\" name=\"ok\" class=\"$this->css_button\"></span>\n";
			else if ($scope == "filter")
				$str  = "<span id=\"botao\"><input type=\"submit\" value=\"$this->lang_search_button_label\" name=\"ok\" class=\"$this->css_button\"></span>\n";
			if (($modo == "update" && $this->step == 1 && count($this->ref_filter) > 0) ||
				($modo == "delete" && $this->step == 1 && count($this->ref_filter) > 0) ||
				($modo == "updatedelete" && $this->step == 1 && count($this->ref_filter) > 0) ||
				($modo == "complete" && $this->step == 1 && count($this->ref_filter) > 0) ||
				($modo == "report" && $this->step == 1 && count($this->ref_filter) > 0))
				$str .= "<input type=\"hidden\" name=\"step\" id=\"step\" value=\"1\">\n";
			else
				$str .= "<input type=\"hidden\" name=\"step\" id=\"step\" value=\"" . ($this->step+1) . "\">\n";
			for ($i=0; $i<$this->unique_ix; $i++) {
				$str .= "<input type=\"hidden\" name=\"ck_unique$i\" id=\"ck_unique$i\" value=\"0\">\n";
			}
			if ($modo == "insert" && $this->step == 1 && $scope == "form")
				$str .= "<input type=\"hidden\" name=\"modo\" id=\"modo\" value=\"insert\">\n";
			else if ($modo == "update" && $this->step == 2 && $scope == "form")
				$str .= "<input type=\"hidden\" name=\"modo\" id=\"modo\" value=\"update\">\n";
			else
				$str .= "<input type=\"hidden\" name=\"modo\" id=\"modo\" value=\"$modo\">\n";
			if ($scope == "filter" || isset($this->vars["_ck_filter"]))
				$str .= "<input type=\"hidden\" name=\"_ck_filter\" id=\"_ck_filter\" value=\"1\">\n";
			if ($modo == "update" && 
				$this->step == 2 &&
				isset($this->res_upd)) {
				if (is_array($this->pk)) {
					$id = "";
					foreach ($this->pk as $key) {
						$val = $this->res_upd[$key];
						if ($val instanceof DateTime) $val = $val->format("Y-m-d");
						$id .= ($id!="" ? "|" : "") . $val;
					}
				} else {
					$id = $this->res_upd[$this->pk];
				}
				$str .= "<input type=\"hidden\" name=\"id\" id=\"id\" value=\"$id\">\n";
				for ($i=0; $i<count($this->tab_ref); $i++) {
					$table = $this->tab_ref[$i]["table"];
					if ($table != null) $str .= "<input type=\"hidden\" name=\"id_$table\" value=\"" . $this->res_upd["id_$table"] . "\">\n";
				}
			}
			$params = preg_replace("/(\&)*(modo|step|id|urlKey|_session_id|ck_return_pk)=[^\&]+/", "", $_SERVER["QUERY_STRING"]);
			if (substr($params,0,1) == "&") $params = substr($params,1);
			$str .= "<input type=\"hidden\" name=\"QUERY_STRING\" id=\"QUERY_STRING\" value=\"$params\">\n";
			return $str;
		}
	}
	private function get_form_back() {
		$str  = "<span id=\"botao\"><input type=button value=\"$this->lang_back_button_label\" onclick=\"javascript:window.open('" . $_SERVER["HTTP_REFERER"] . (strpos($_SERVER["HTTP_REFERER"], "?") === false ? "?" : "&" . "get_session_filter=1") . "','_self');\" name=ok class=\"$this->css_button\"></span>\n";
		$str .= "<input type=\"hidden\" name=\"pag\" id=\"pag\" value=\"" . $_GET["pag"] . "\">\n";
		return $str;
	}
	private function get_form_close() {
		$str  = "<span id=\"botao\"><input type=button value=\"$this->lang_close_button_label\" onclick=\"javascript:window.close();\" name=ok class=\"$this->css_button\"></span>\n";
		$str .= "<input type=\"hidden\" name=\"pag\" id=\"pag\" value=\"" . $_GET["pag"] . "\">\n";
		return $str;
	}
	private function get_form_print() {
		$str  = "<span id=\"botao\"><input type=button value=\"$this->lang_print_button_label\" onclick=javascript:self.print() name=ok class=\"$this->css_button\"></span>\n";
		$str .= "<input type=\"hidden\" name=\"pag\" id=\"pag\" value=\"" . $_GET["pag"] . "\">\n";
		return $str;
	}
	private function get_list_title($title, $colspan=1) {
		$str = "";
		if ($title != "") {
			$str .= "<tr><td colspan=$colspan class=\"$this->css_title_pg\">";
			$str .= $title;
			$ck_float = 0;
			if ($this->modo == "complete" || $this->ck_collapse == 1 || $this->ck_csv == 1 || $this->ck_xls == 1) {
				if ($this->modo == "complete" && 
					$this->setup_complete == "linked" && 
					$this->step == 1 &&
					count($this->ref_filter) >= 0) {
					$str .= "<span style=\"float:right\">" . $this->build_linked_insert_button() . "</span>";
					$ck_float = 1;
				}
				$str .= "<span style=\"" . ($ck_float == 0 ? "float:right;" : "") . "display:flex;gap:5px;\">\n";
				if ($this->ck_collapse == 1) {
					$str .= "<img id=\"uncollapse\" src=\"$this->img_dir/$this->img_uncollapse\" border=0 align=\"absmiddle\" style=\"cursor:pointer;\" onclick=\"chAllDisplay(true)\">";
					$str .= "<img id=\"collapse\" src=\"$this->img_dir/$this->img_collapse\" border=0 align=\"absmiddle\" style=\"cursor:pointer;display:none;\" onclick=\"chAllDisplay(false)\">";
				}
				if ($this->ck_xls == 1 && $this->step == 1 && ($this->modo == "update" || $this->modo == "report")) {
					$str .= "<img src=\"{$this->img_dir}/icon_excel.gif\" border=0 align=\"absmiddle\" style=\"cursor:pointer;\" title=\"Exportar para Excel\" onclick=\"gotoExcel();\">";
				}
				if ($this->ck_csv == 1) {
					$url = "?modo=$this->modo&ck_csv=1";
					foreach ($this->vars as $ix => $val) {
						if (!in_array($ix,["modo","step","id","pagina","QUERY_STRING","urlKey","_session_id"])) {
							// In case $_REQUEST[...] has been manipulated before cls_form->build()
							if (isset($_POST[$ix]) && $val != $_POST[$ix]) $val = $_POST[$ix]; 
							if (isset($_GET[$ix]) && $val != $_GET[$ix]) $val = $_GET[$ix];
							if (is_array($val)) {
								foreach ($val as $arrval) $url .= "&" . $ix . "[]=" . $arrval;
							} else {
								$url .= "&$ix=$val";
							}
						}
					}
					$str .= "<a target=_blank href=\"$url\" class=\"update\" style=\"float:right;\">\n";
					$str .= "<img style=\"display:inline-block;\" src=\"$this->img_dir/icon_excel.gif\" border=\"0\" title=\"Exportar em CSV\">\n";
					$str .= "</a>\n";
				}
				foreach ($this->title_opt as $opt) {
					$str .= " <img src=\"" . $opt["icon"] . "\" border=0 align=\"absmiddle\" style=\"cursor:pointer;\" title=\"" . $opt["label"] . "\" onclick=\"location.href='" . $opt["url"] . "'\">";
				}
				$str .= "</span>\n";
			}
			$str .= "</td></tr>\n";
		}
		return $str;
	}
	private function get_val($ref, $prefix=null, $suf=null) {
		if (gettype($ref) == "array" && !isset($ref["object"])) print_r($ref);
		if (gettype($ref) == "array") $ref = $ref["object"];
		$type = $ref->type;
		if ($ref->type == "hidden" && !empty($ref->db_type)) $type = $ref->db_type;
		if ($type == "date"  && $this->step == 1 && $suf === null && !empty($this->vars["_ck_filter"])) $type = "daterange";
		if ($type == "month" && $this->step == 1 && $suf === null && !empty($this->vars["_ck_filter"])) $type = "monthrange";
		$modo = ($this->modo == "complete" || $this->modo == "updatedelete") && isset($this->vars["modo"]) ? $this->vars["modo"] : $this->modo;
		$field = $prefix . $this->getFieldLbl($ref->field);
		if ($type == "dbfield") {
			return "'".$ref->value."'";
		} else if ($type == "checkbox" && isset($this->vars[$field.$suf])) {
			return 1;
		} else if ($type == "checkbox" && !isset($this->vars[$field.$suf])) {
			return 0;
		} else if ($type == "radio" && !isset($this->vars[$field.$suf])) {
			return "NULL";
		} else if ($type == "radio_checkbox" && $this->vars[$field.$suf] == "") {
			return "NULL";
		} else if ($type == "date" || $type == "datetime") {
			if (!empty($this->vars["dia_".$field.$suf]) && 
				!empty($this->vars["mes_".$field.$suf]) && 
				!empty($this->vars["ano_".$field.$suf])) {
				$val = $this->vars["ano_".$field.$suf]."-".$this->vars["mes_".$field.$suf]."-".$this->vars["dia_".$field.$suf];
				if ($type == "datetime") $val .= " ".$this->vars["hor_".$field.$suf].":".$this->vars["min_".$field.$suf].":00";
			} else if (!empty($this->vars[$field.$suf]) && preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/", $this->vars[$field.$suf])) {
				$val = $this->vars[$field.$suf];
			}
			if (!empty($val)) {
				return $this->format_sql_date($val);
			} else {
				return "NULL";
			}
		} else if ($type == "datetime-local") {
			if (!empty($this->vars[$field.$suf])) {
				return $this->format_sql_date(str_replace("T"," ",$this->vars[$field.$suf]));
			} else {
				return "NULL";
			}
		} else if ($type == "month" && 
			!empty($this->vars[$field.$suf])) {
			$tbl = !empty($ref->entity) ? $ref->entity->table : $ref->parent->table;
			$qry = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$tbl' AND COLUMN_NAME = '" . $ref->field . "'";
			$res = nc_query($qry);
			if ($res[0]["DATA_TYPE"] == "datetime" || $res[0]["DATA_TYPE"] == "date") {
				return $this->format_sql_date($this->vars[$field.$suf]."01");
			}
			return "'" . $this->vars[$field.$suf] . "'";
		} else if ($type == "month" && 
			!empty($this->vars["mes_".$field.$suf]) && 
			!empty($this->vars["ano_".$field.$suf])) {
			$tbl = !empty($ref->entity) ? $ref->entity->table : $ref->parent->table;
			$qry = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$tbl' AND COLUMN_NAME = '" . $ref->field . "'";
			$res = nc_query($qry);
			if ($res[0]["DATA_TYPE"] == "datetime" || $res[0]["DATA_TYPE"] == "date") {
				return $this->format_sql_date($this->vars["ano_".$field.$suf]."-".$this->vars["mes_".$field.$suf]."-01");
			}
			return "'" . $this->vars["ano_".$field.$suf].$this->vars["mes_".$field.$suf] . "'";
		} else if ($type == "hour" && 
			($this->vars["hor_".$field.$suf] != "" || $this->vars["min_".$field.$suf] != "")) {
			return ($this->vars["hor_".$field.$suf] != "" ? $this->vars["hor_".$field.$suf] * 60 : 0) + $this->vars["min_".$field.$suf];
		} else if ($type == "hour" && 
			($this->vars["hor_".$field.$suf] == "" || $this->vars["min_".$field.$suf] == "")) {
			return "NULL";
		} else if ($type == "time" && !empty($this->vars[$field.$suf])) {
			$parts = explode(":",$this->vars[$field.$suf]);
			return $parts[0] * 60 + $parts[1];
		} else if ($type == "daterange" && 
			$this->vars["dia_".$field."_ini".$suf] != "" && 
			$this->vars["mes_".$field."_ini".$suf] != "" && 
			$this->vars["ano_".$field."_ini".$suf] != "" && 
			$this->vars["dia_".$field."_fim".$suf] == "" && 
			$this->vars["mes_".$field."_fim".$suf] == "" && 
			$this->vars["ano_".$field."_fim".$suf] == "") {
			return array(
				"ini" => $this->format_sql_date($this->vars["ano_".$field."_ini".$suf]."-".$this->vars["mes_".$field."_ini".$suf]."-".$this->vars["dia_".$field."_ini".$suf] . " 00:00:00"),
				"fim" => $this->format_sql_date($this->vars["ano_".$field."_ini".$suf]."-".$this->vars["mes_".$field."_ini".$suf]."-".$this->vars["dia_".$field."_ini".$suf] . " 23:59:59")
			);
		} else if ($type == "daterange" && 
			$this->vars["dia_".$field."_ini".$suf] != "" && 
			$this->vars["mes_".$field."_ini".$suf] != "" && 
			$this->vars["ano_".$field."_ini".$suf] != "" && 
			$this->vars["dia_".$field."_fim".$suf] != "" && 
			$this->vars["mes_".$field."_fim".$suf] != "" && 
			$this->vars["ano_".$field."_fim".$suf] != "") {
			return array(
				"ini" => $this->format_sql_date($this->vars["ano_".$field."_ini".$suf]."-".$this->vars["mes_".$field."_ini".$suf]."-".$this->vars["dia_".$field."_ini".$suf] . " 00:00:00"),
				"fim" => $this->format_sql_date($this->vars["ano_".$field."_fim".$suf]."-".$this->vars["mes_".$field."_fim".$suf]."-".$this->vars["dia_".$field."_fim".$suf] . " 23:59:59")
			);
		} else if ($type == "daterange" && 
			($this->vars["dia_".$field."_ini".$suf] == "" || 
			 $this->vars["mes_".$field."_ini".$suf] == "" || 
			 $this->vars["ano_".$field."_ini".$suf] == "" || 
			 $this->vars["dia_".$field."_fim".$suf] == "" || 
			 $this->vars["mes_".$field."_fim".$suf] == "" || 
			 $this->vars["ano_".$field."_fim".$suf] == "")) {
			return "NULL"; // array("ini" => "NULL", "fim" => "NULL");
		} else if ($type == "monthrange" && 
			$this->vars["mes_".$field."_ini".$suf] != "" && 
			$this->vars["ano_".$field."_ini".$suf] != "" && 
			$this->vars["mes_".$field."_fim".$suf] != "" && 
			$this->vars["ano_".$field."_fim".$suf] != "") {
			if (!empty($ref->db_type)) {
				$db_type = $ref->db_type;
			} else {
				$qry = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $ref->parent->table . "' AND COLUMN_NAME = '" . $this->getFieldLbl($ref->field) . "'";
				$res = nc_query($qry);
				$db_type = $res[0]["DATA_TYPE"];
			}
			if (in_array($db_type, ["datetime", "date"])) {
				return array(
					"ini" => $this->format_sql_date($this->vars["ano_".$field."_ini".$suf]."-".$this->vars["mes_".$field."_ini".$suf]."-01"),
					"fim" => $this->format_sql_date($this->vars["ano_".$field."_fim".$suf]."-".$this->vars["mes_".$field."_fim".$suf]."-".date("t",mktime(12,0,0,$this->vars["mes_".$field."_fim".$suf],1,$this->vars["ano_".$field."_fim".$suf])))
				);
			} else {
				return array(
					"ini" => "'".$this->vars["ano_".$field."_ini".$suf].str_pad($this->vars["mes_".$field."_ini".$suf],2,'0',STR_PAD_LEFT)."'",
					"fim" => "'".$this->vars["ano_".$field."_fim".$suf].str_pad($this->vars["mes_".$field."_fim".$suf],2,'0',STR_PAD_LEFT)."'"
				);
			}
		} else if ($type == "text" && isset($ref->mask)) {
			if ($ref->unlock_mask == 0 || !isset($this->vars[$field.$suf])) {
				$str = "";
				$c_mask = 0;
				for ($i=0; $i<count($ref->mask); $i++) {
					if ($this->vars[$field.$c_mask.$suf] != "") {
						if (is_numeric($ref->mask[$i])) {
							$str .= str_pad($this->vars[$field.$c_mask.$suf], $ref->mask[$i], '0');
							$c_mask++;
						} else if ($ref->write_mask == 1)
							$str .= $ref->mask[$i];
					}
				}
			} else {
				$str = $this->vars[$field.$suf];
			}
			if ($str != "")
				return $this->format_sql_str($str);
			else
				return "NULL";
		} else if ($type == "textarea" && !empty($ref->prop["maxlength"])) { // html maxlength conta 1 caractere para \n, mas o BD conta 2 carecteres CR/LF
			$str = substr($this->vars[$field.$suf],0,$ref->prop["maxlength"]);
			if ($str != "")
				return $this->format_sql_str($str);
			else
				return "NULL";
		} else if ($type == "file") {
			$str = $this->add_file($ref);
			if ($str != "")
				return "'" . str_replace("'","''",$str) . "'";
			else
				return "NULL";
		} else if ($type == "dropdown" && 
			isset($ref->list) && 
			isset($ref->new_entry) &&
			$this->vars[$field.$suf] == "NEW") { // new entry
			return "'".$this->vars[$field."_entry".$suf] . "'";
		} else if ($type == "dropdown" && 
			isset($ref->qry) && 
			isset($ref->new_entry) &&
			$this->vars[$field.$suf] == "NEW" &&
			$ref->new_entry["table"] == null) { // new entry
			return "'".$this->vars[$field."_entry".$suf] . "'";
		} else if ($type == "dropdown" && 
			isset($ref->qry) && 
			isset($ref->new_entry) &&
			$this->vars[$field.$suf] == "NEW" &&
			$ref->new_entry["table"] != null) { // new entry
			if ($ref->new_entry["url"] != null) $this->ck_redirect = 0;
			$new_entry_table = $ref->new_entry["table"];
			$new_entry_field = $ref->new_entry["field"];
			$new_entry_url = $ref->new_entry["url"];
			$new_entry_pk = $ref->new_entry["pk"];
			$qry = "INSERT INTO $new_entry_table ($new_entry_field) VALUES ('" . $this->vars[$field."_entry".$suf] . "')";
			if ($this->db == "MSSQL") {
				$qry_str  = "$qry;\n";
				$qry_str .= "SELECT SCOPE_IDENTITY() AS id;\n";
				if ($this->debug == 1) $this->show_debug($qry_str,"?");
				$res_new_entry = nc_query($qry_str, "QUERY");
			} else if ($this->db == "MYSQL") {
				if ($this->debug == 1) $this->show_debug($qry,"?");
				nc_query($qry);
				$qry = "SELECT MAX($new_entry_pk) AS id FROM $new_entry_table";
				if ($this->debug == 1) $this->show_debug($qry,"?");
				$res_new_entry = nc_query($qry);
			}
			$this->ck_new_ref[count($this->ck_new_ref)] = array(
				"dir" => $new_entry_table,
				"id" => $res_new_entry[0]["id"],
				"nome" => $this->vars[$field."_entry".$suf],
				"url" => str_replace("[id]", $res_new_entry[0]["id"], $new_entry_url)
			); 
			return $res_new_entry[0]["id"];
		} else if ($type == "publisher") {
			if ($this->db == "MSSQL")
				return "'" . str_replace("'","''",str_replace(array("\'",'\"'),array("'",'"'),$this->vars["content".$field.$suf])) . "'";
			else if ($this->db == "MYSQL" && strpos($this->vars["content".$field.$suf], "\'") === false)
				return "'" . str_replace("'", "\'", $this->vars["content".$field.$suf]) . "'";
			else if ($this->db == "MYSQL" && strpos($this->vars["content".$field.$suf], "\'") !== false)
				return "'" . $this->vars["content".$field.$suf] . "'";
		} else if ($type == "dropdown" && array_key_exists("multiple",$ref->prop)) {
			if (isset($ref->rec_table))
				return !empty($this->vars[$field.$suf]) ? $this->vars[$field.$suf] : [];
			else
				return !empty($this->vars[$field.$suf]) ? "'" . implode(",",$this->vars[$field.$suf]) . "'" : "NULL";
		} else if ($type == "fieldlist") {
			return $this->vars[$field.$suf];
		} else if ($type == "password" && $this->password_encrypt_method != "") {
			$method = $this->password_encrypt_method;
			if (!empty($this->vars[$field.$suf])) {
				$passw = $this->vars[$field.$suf];
				if ($method == "md5") { // || $method == "md5" || // add other standard methods
					return "'" . $method($passw) . "'";
				} else {
					return "'" . $method($passw, $this->vars) . "'";
				}
			} else {
				return "NULL";
			}
		} else {
			if (is_array($ref->field))
				return $this->vars[$field.$suf];
			else if (!isset($this->vars[$field.$suf]) || $this->vars[$field.$suf] == "")
				return "NULL";
			else
				return $this->format_sql_str($this->vars[$field.$suf]);
		}
	}
	private function format_sql_date($str) {
		if ($this->db == "MSSQL")
			return "CONVERT(datetime, '$str', 120)";
		else if ($this->db == "MYSQL")
			return "'$str'";
	}
	private function get_ref_query($sql, $res, &$debug=false) {
		preg_match_all("[\[([a-zA-Z0-9_])+\]]", $sql, $match);
		foreach ($match[0] as $ix) {
			$sql = str_replace($ix, $res[substr($ix,1,-1)], $sql);
		}
		if ($this->debug == 1) $this->show_debug($sql,"?","relative",$debug);
		return nc_query($sql);
	}
	private function get_list_cell($ix, $ix_label, $ref, $val, $rowspan, $bgcolor, $params, $class, $id="tr") {
		$label_ref = $this->label[$ix_label];
		$str = "";
		// index
		$field = $this->getFieldLbl($ref["field"]);
		// adjust delete bug
		if ($ref["ck_update"] == 1) {
			$ck_link = 0;
		} else if (isset($this->list_url_condition) && $this->list_url_condition["field"] != null) {
			$_param = $this->list_url_condition["field"];
			if ($_param instanceof Closure) {
				$ck_link = $_param($this->res_list[$ix]);
			} else {
				$_op = $this->list_url_condition["op"];
				$_vals = $this->list_url_condition["val"];
				$ck_link = 0;
				if (!is_array($_vals)) $_vals = [$_vals];
				foreach ($_vals as $_val) {
					// if (!empty($_SESSION["verpro_debug"])) echo $this->res_list[$ix][$_param] . " $_op $_val<br>";
					if (($_op == "==" && $this->res_list[$ix][$_param] == $_val) || 
						($_op == "!=" && $this->res_list[$ix][$_param] != $_val) || 
						($_op == ">=" && $this->res_list[$ix][$_param] >= $_val) || 
						($_op == "<=" && $this->res_list[$ix][$_param] <= $_val) || 
						($_op == ">" && $this->res_list[$ix][$_param] > $_val) || 
						($_op == "<" && $this->res_list[$ix][$_param] < $_val)) {
						$ck_link = 1;
					}
					if (($_op == "!=" && $this->res_list[$ix][$_param] == $_val)) {
						$ck_link = 0;
						break;
					}
				}
			}
		} else if ($this->modo == "update" || $this->modo == "updatedelete" || $this->modo == "complete") {
			$ck_link = 1;
		} else if ($this->document_name_list != "") {
			$ck_link = 1;
		} else {
			$ck_link = 0;
		}
		$style = "text-align:" . $ref["alignment"] . ";";
		if ($ck_link == 1 || (isset($ref["len"]) && $ref["len"] > 0)) $style .= "cursor:pointer;";
		if ($bgcolor != "") $style .= "background-color:$bgcolor!important;";
		if ($this->ck_recursive != 0) {
			$style .= "padding-left:" . ($val["level"]*20) . "px;width:100%;";
		} else if ($ix_label == 0 && $this->modo == "update") {
			$style .= "text-indent:-15px!important;";
			if ($this->ck_recursive != 0) 
				$style .= "padding-left:" . ($val["level"]*20) . "px!important;";
			else 
				$style .= "padding-left:" . (5+15+(20*max(0,count($this->group_key)-1))) . "px!important;";
		}
		$ref["label_prop"]["style"] = (isset($ref["label_prop"]["style"]) ? $ref["label_prop"]["style"] . ";" : "") . $style;
		if (isset($ref["len"]) && $ref["len"] > 0) $ref["label_prop"]["title"] = strip_tags((string)$val[$field]);
		if (!empty($class)) {
			if (isset($ref["label_prop"]))
				$ref["label_prop"]["class"] .= " $class";
			else
				$ref["label_prop"]["class"] = $class;
		}
		$prop_str = "";
		foreach ($ref["label_prop"] as $_ix => $_val) {
			if (!empty($_val)) {
				if ($_val instanceof Closure) {
					$_val = $_val($val);
				} else {
					preg_match_all("/\[([a-zA-Z0-9_.])+\]/i", $_val, $match);
					if (!empty($match[0])) $_val = $val[$this->getFieldLbl(substr($match[0][0],1,-1))];
				}
				$prop_str .= ($prop_str != "" ? " " : "") . "$_ix=\"$_val\"";
			} else {
				$prop_str .= ($prop_str != "" ? " " : "") . $_ix;
			}
		}
		if (!empty($ref["nowrap"])) $prop_str .= " nowrap";
		if (!empty($ref["repeat"])) $rowspan = 1;
		if ($this->modo == "update" || $this->modo == "complete" || $this->modo == "updatedelete" || !empty($this->document_name_list)) {
			if (!empty($this->document_name_list)) {
				$url = $this->document_name_list;
				if ($url instanceof Closure) $url = $url($this->res_list[$ix]);
				preg_match_all("[\[([a-zA-Z0-9_.])+\]]", $url, $match);
				foreach ($match[0] as $m) {
					$temp = explode(",", substr($m,1,-1));
					$_val = $val[$this->getFieldLbl($temp[0])];
					if (count($temp) == 2) $_val = str_pad($_val,$temp[1],'0',STR_PAD_LEFT);
					$url = str_replace($m, rawurlencode((string)$_val), $url);
				}
				if (count($match[0]) == 0) {
					if (strpos($this->document_name_list,"?") === false)
						$url = "$this->document_name_list?$params";
					else
						$url = "$this->document_name_list&$params";
				}
			} else {
				$url = "?$params&modo=update&step=2" . (isset($this->vars["_ck_filter"])?"&_ck_filter=1":"");
			}
			$str .= "<td 
				" . (!empty($ref["tagId"]) ? "id=" . $ref["tagId"] . $ix : "") . " 
				" . ($rowspan > 1 ? "rowspan=$rowspan" : "") . " 
				" . ($ck_link == 1 && empty($ref["link"]) ? "onclick=\"window.open('$url',(event.ctrlKey?'_blank':'_self'))\" onmouseover=\"markTr(this.parentNode,'silver')\" onmouseout=\"markTr(this.parentNode)\"" : "") . "
				$prop_str>";
			if ($ix_label == 0) $str .= $this->list_bullet . " ";
		} else if ($this->modo == "delete") {
			$str .= "<td " . ($rowspan > 1 ? "rowspan=$rowspan" : "") . " $prop_str " . ($ix_label == 0 ? "nowrap" : "") . ">";
			if (is_array($this->pk)) 
				$str_pk = json_encode(array_intersect_key($val, array_flip($this->pk)));
			else
				$str_pk = $val[$this->pk];
			if ($ix_label == 0) $str .= "<label><input type=\"checkbox\" name=\"delete$ix\" value='$str_pk'>"; // single quotes to contain json string
		} else if ($this->modo == "report") {
			$str .= "<td 
				" . (!empty($ref["tagId"]) ? "id=" . $ref["tagId"] . $ix : "") . " 
				" . ($rowspan > 1 ? "rowspan=$rowspan" : "") . "
				$prop_str " . ($ix_label == 0 ? "nowrap" : "") . ">";
			if ($this->list_form == "custom" && $ix_label == 0)  {
				$fn = $this->list_form_condition ? $this->list_form_condition : function() { return true; };
				$str .= "<label><input type=\"checkbox\" name=\"id[]\" id=\"id$ix\" value=" . $val[$this->getFieldLbl($this->pk)] . " " . (!$fn($val) ? "DISABLED" : "") . ">";
			}
		}
		if ($ref["ck_update"] == 1) {
			$obj = $this->ref_list[$ref["object_id"]];
			//$str .= "<input type=checkbox name={$field}{$ix} value=1 " . ($val[$field] == 1 ? "CHECKED" : "") . ">\n";
			$parseComment = function($str,$val,$ix) {
				$str = str_replace("[ix]", $ix, $str);
				preg_match_all("/\[([a-zA-Z0-9_]+)\]/", $str, $match);
				foreach ($match[0] as $m) $str = str_replace($m, $val[substr($m,1,-1)], $str);
				return $str;
			};
			if (isset($obj->comment_before)) {
				$str .= $parseComment($obj->comment_before,$val,$ix) . "\n";
			}
			if ($label_ref["mode"] == "NUM") $val[$field] = formatnum($val[$field], $label_ref["depth"]."+", ".", "");
			$str .= $this->get_field(["scope" => "list", "ref" => $obj, "res" => $val, "sufix" => $ix, "class" => $this->css_formpeq]);
			if ($obj->type == "date" || $obj->type == "datetime") {
				$str .= "<input type=\"hidden\" name=\"dia_{$field}_bak{$ix}\" id=\"dia_{$field}_bak{$ix}\" value=\"" . str_pad($val["dia_$field"],2,'0',STR_PAD_LEFT) . "\">\n";
				$str .= "<input type=\"hidden\" name=\"mes_{$field}_bak{$ix}\" id=\"mes_{$field}_bak{$ix}\" value=\"" . str_pad($val["mes_$field"],2,'0',STR_PAD_LEFT) . "\">\n";
				$str .= "<input type=\"hidden\" name=\"ano_{$field}_bak{$ix}\" id=\"ano_{$field}_bak{$ix}\" value=\"" . $val["ano_$field"] . "\">\n";
				if ($obj->type == "datetime") {
					$str .= "<input type=\"hidden\" name=\"hor_{$field}_bak{$ix}\" id=\"hor_{$field}_bak{$ix}\" value=\"" . str_pad($val["hor_$field"],2,'0',STR_PAD_LEFT) . "\">\n";
					$str .= "<input type=\"hidden\" name=\"min_{$field}_bak{$ix}\" id=\"min_{$field}_bak{$ix}\" value=\"" . str_pad($val["min_$field"],2,'0',STR_PAD_LEFT) . "\">\n";
				}
			} else {
				$str .= "<input type=\"hidden\" name=\"{$field}_bak{$ix}\" id=\"{$field}_bak{$ix}\" value=\"" . $val[$field] . "\">\n";
			}
			if (isset($obj->comment)) {
				$str .= $parseComment($obj->comment,$val,$ix) . "\n";
			}
			foreach (is_array($ref["update_key"]) ? $ref["update_key"] : array($ref["update_key"]) as $key) {
				if (!isset($this->ck_upd_fields[$ix][$key])) {
					$str .= "<input type=\"hidden\" name=\"{$key}{$ix}\" id=\"{$key}{$ix}\" value=\"" . $val[$key] . "\">\n";
				}
				$this->ck_upd_fields[$ix][$key] = 1;
			}
			if (!isset($this->ck_upd_fields[$ix]["grp"])) {
				if ($this->ck_flag_mode == 1 && count($this->group_key) > 0) {
					$str .= "<input type=\"hidden\" name=\"group_key{$ix}\" id=\"group_key{$ix}\" value=\"" . $this->res_list[$ix]["grp".(count($this->group_key)-1)] . "\">\n";
				}
				$this->ck_upd_fields[$ix]["grp"] = 1;
			}
		} else {
			/* if (!empty($ref["repeat"]["total"])) {
				// calculo subtotal esta resolvido com parser definido no metodo set_label_repeat()
			} else */
			if ($ref["mode"] == "NUM") {
				if ($ref["field"] instanceof Closure) {
					$ret = $ref["field"]($val);
					$this->label[$ix_label]["total"] += $ret;
					$label = $ret != "" ? number_format($ret, $ref["depth"], ",", ".") : "";
				} else if (!empty($field) && is_numeric($val[$field])) {
					if (isset($ref["parser"]))
						$label = $ref["parser"]($val[$field], $val, $ix, $ix_label);
					else
						$label = /*$ref["un"] . " " .*/ number_format($val[$field], $ref["depth"], ",", "."); 
					$this->label[$ix_label]["total"] += $val[$field];
				} else {
					$label = "";
				}
			} else if ($ref["mode"] == "flag" && ($this->modo == "report" || $ref["ck_update"] == 0)) {
				$label = !empty($val[$field]) ? "X" : "";
			} else {
				if (isset($ref["len"]) && $ref["len"] > 0) {
					if ($ref["ck_remove_tags"] == 1) $val[$field] = strip_tags($val[$field]);
					$val[$field] = substr((string)$val[$field], 0, $ref["len"]) . (strlen((string)$val[$field]) > $ref["len"] ? "..." : "");
				}
				$fY = count($this->label) > 20 ? "y" : "Y";
				if ($ref["field"] instanceof Closure)
					$label = $ref["field"]($val);
				else if (!empty($ref["parser"]))
					$label = $ref["parser"]($val[$field], $val, $ix, $ix_label);
				else if ($val[$field] instanceof DateTime && strtotime($val[$field]->format("Y-m-d")) != strtotime($val[$field]->format("Y-m-d H:i")))
					$label = $val[$field]->format("d/m/$fY H:i");
				else if ($val[$field] instanceof DateTime)
					$label = $val[$field]->format("d/m/$fY");
				else
					$label = nl2br((string)$val[$field]);
			}
			if (isset($ref["holder"])) {
				$tag = $ref["holder"]["tag"];
				$prop_str = "";
				foreach ($ref["holder"]["prop"] as $_ix => $_val) $prop_str .= " $_ix='$_val'";
				$label = "<$tag $prop_str>$label</$tag>";
			}
			if (isset($ref["link"])) {
				$ck_display = 0;
				foreach ($ref["link"] as $link) {
					if (empty($link["param"]) && empty($link["condition"]))
						$go = true;
					else if (!empty($link["condition"]) && $link["condition"]($this->res_list[$ix]))
						$go = true;
					else if (!empty($link["param"]) && (
						($link["param"]["op"] == "==" && is_array($link["param"]["val"]) && in_array($val[$link["param"]["col"]], $link["param"]["val"])) ||
						($link["param"]["op"] == "!=" && is_array($link["param"]["val"]) && !in_array($val[$link["param"]["col"]], $link["param"]["val"])) ||
						($link["param"]["op"] == "==" && !is_array($link["param"]["val"]) && $val[$link["param"]["col"]] == $link["param"]["val"]) ||
						($link["param"]["op"] == "!=" && !is_array($link["param"]["val"]) && $val[$link["param"]["col"]] != $link["param"]["val"]) ||
						($link["param"]["op"] == ">=" && $val[$link["param"]["col"]] >= $link["param"]["val"]) ||
						($link["param"]["op"] == "<=" && $val[$link["param"]["col"]] <= $link["param"]["val"]) ||
						($link["param"]["op"] == ">" && $val[$link["param"]["col"]] > $link["param"]["val"]) ||
						($link["param"]["op"] == "<" && $val[$link["param"]["col"]] < $link["param"]["val"])
						))
						$go = true;
					else
						$go = false;
					if ($go) {
						$c = 0;
						foreach ($link["explode"] ? explode($link["explode"], (string)$val[$field]) : [$label] as $part) {
							if ($c > 0) $str .= $link["explode"];
							$url = $link["url"];
							if ($url instanceof Closure) {
								$url = $url($val);
							} else {
								preg_match_all("[\[([a-zA-Z0-9_.,\(\)+ ])+\]]", $url, $match);
								foreach ($match[0] as $m) {
									$temp = explode(",", substr($m,1,-1));
									$_ix = $this->getFieldLbl($temp[0]);
									if ($_ix == $field && $link["explode"]) // if using explode param, read $_val from $part
										$_val = $part;
									else
										$_val = $val[$_ix];
									if ($_val == null) $_val = "";
									if (count($temp) == 2) $_val = str_pad($_val, $temp[1], '0', STR_PAD_LEFT);
									if ($_val instanceof DateTime) $_val = $_val->format("d/m/Y");
									$url = str_replace($m, rawurlencode($_val), $url);
								}
							}
							if (!empty($part)) {
								if (!empty($link["onclick"])) {
									$click = $link["onclick"];
									preg_match_all("[\[([a-zA-Z0-9_.,\(\)+ ])+\]]", $click, $match);
									foreach ($match[0] as $m) {
										$_val = $this->res_list[$ix][substr($m,1,-1)];
										if ($_val instanceof DateTime) $_val = $_val->format("d/m/Y");
										$click = str_replace($m, $_val, $click);
									}
								}
								$str .= "<a 
									href=\"$url\"
									" . (!empty($link["target"]) ? "target=\"" . $link["target"] . "\"" : "") . "
									" . (!empty($link["onclick"]) ? "onclick=\"$click\"" : "") . "
									class=\"$this->css_link_general\">" . trim($part) . "</a>";
							}
							$ck_display = 1;
							$c++;
						}
					}
				}
				if ($ck_display == 0) $str .= $label;
			} else {
				$str .= $label;
			}
		}
		if ($ref["ck_hidden"] == 1) {
			$str .= "<input type=\"hidden\" name={$field}{$ix} value=\"" . $val[$field] . "\">\n";
		}
		if ($this->modo == "delete" && $ix_label == 0) {
			$str .= "</label>";
		}
		$str .= "</td>\n";
		if (($this->modo == "complete" || $this->modo == "updatedelete") && 
			$ix_label == count($this->label) - 1) {
			$ck_cb = !isset($this->delete_mode) || $this->delete_mode == "checkbox" ? 1 : 0;
			$str .= "<td " . ($rowspan > 1 ? "rowspan=$rowspan" : "") . " style=\"" . ($ck_cb == 0 ? "width:22px;" : "text-align:right;") . ($bgcolor != "" ? "background-color:$bgcolor!important;" : "") . "\" class=\"$class\" " . " nowrap>";
			if (is_array($this->pk)) {
				$pk_str = "";
				for ($k=0; $k<count($this->pk); $k++) {
					$pk_str .= ($k>0 ? "|" : "") . $val[$this->pk[$k]];
				}
			} else {
				$pk_str = $val[$this->pk];
			}
			if ($ck_cb == 1)
				$str .= "<input type=\"checkbox\" name=\"delete$ix\" id=\"delete$ix\" value=\"" . $pk_str . "\" " . ($val["_ck_delete"] == 0 ? "DISABLED" : "") . " onclick=\"markDelete(this)\">" . ucfirst(strtolower($this->lang_title_delete));
			else if ($val["_ck_delete"] == 1)
				$str .= "<a href=\"javascript:confirm('$this->lang_delete')?window.location='?modo=delete&step=2&count=1&id0=$pk_str':void(null)\"><img src=\"$this->delete_mode\" title=\"$this->lang_title_delete\" border=0></a>";
			$str .= "</td>\n";
		}
		return $str;
	}
	private function add_list_links($i) {
		foreach ($this->link_ref as $link) {
			if ($link["url"] instanceof Closure) {
				$url = $link["url"]($this->res_list[$i]);
			} else {
				//preg_match_all("/\[([a-zA-Z0-9_.,])*\]/", $link["url"], $match);
				preg_match_all("/\[.*?\]/", $link["url"], $match);
				$url = $link["url"];
				foreach ($match[0] as $found) {
					if ($found == "[]") {
						$url = str_replace($found, $i, $url);
					} else {
						$_field = substr($found,1,-1);
						preg_match("/^[\.a-zA-Z0-9_]+,[0-9]+$/i", $_field, $ck_pad); // verifica formato [field,(int)length]
						if (count($ck_pad) > 0) {
							$_len = substr($_field, strpos($_field,",")+1);
							$_field = substr($_field, 0, strpos($_field,","));
						}
						if ($_field == "row")
							$val = $i;
						else
							$val = $this->res_list[$i][$this->getFieldLbl($_field)];
						if (count($ck_pad) > 0) $val = str_pad($val, $_len, '0', STR_PAD_LEFT);
						$url = str_replace($found, rawurlencode((string)$val), $url);
					}
				}
			}
			$ck_link = 1;
			foreach ($link["conditions"] as $condition) {
				if ($condition instanceof Closure) {
					if (!$condition($this->res_list[$i])) $ck_link = 0;
				} else {
					$param = $condition["param"];
					$p = strpos($param, ".");
					if ($p > 0) $param = substr($param, $p+1);
					$op = $condition["op"];
					$val = $condition["val"];
					if (!(($op == "==" && is_array($val) && in_array($this->res_list[$i][$param], $val)) || 
						  ($op == "!=" && is_array($val) && !in_array($this->res_list[$i][$param], $val)) || 
						  ($op == "==" && $this->res_list[$i][$param] == $val) || 
						  ($op == "!=" && $this->res_list[$i][$param] != $val) || 
						  ($op == ">=" && $this->res_list[$i][$param] >= $val) || 
						  ($op == "<=" && $this->res_list[$i][$param] <= $val) || 
						  ($op == ">" && $this->res_list[$i][$param] > $val) || 
						  ($op == "<" && $this->res_list[$i][$param] < $val))) {
						$ck_link = 0;
					}
				}
			}
			$prop = "";
			if (!empty($link["onclick"])) {
				$str = $link["onclick"];
				preg_match_all("/\[([a-zA-Z0-9_.,])+\]/", $str, $match);
				foreach($match[0] as $found) {
					$_field = substr($found,1,-1);
					if ($_field == "row")
						$val = $i;
					else
						$val = $this->res_list[$i][$_field];
					$str = str_replace($found, $val, $str);
				}
				$prop .= "onclick=\"$str\"";
			}
			$holder = $link["holder"];
			$target = $link["target"];
			if ($holder != null) {
				echo "<td class=\"$this->css_print_option\" align=center " . ($this->ck_active == 1 && $this->res_list[$i]["grp_ativo"] == 0 ? "style=\"background-color:$this->inactive_color\"" : "") . ">";
				if ($ck_link == 1) {
					preg_match_all("/\[([a-zA-Z0-9_.,])+\]/", $holder, $match);
					foreach($match[0] as $marker) {
						$holder = str_replace($marker, $this->res_list[$i][substr($marker,1,-1)], $holder);
					}
					$style_str = "border:0;";
					if (!empty($link["maxsize"])) $style_str .= "max-width:".$link["maxsize"]."px;max-height:".$link["maxsize"]."px;";
					if (!empty($link["style"])) $style_str .= $link["style"];
					$prop_str = "src=\"$holder\" title=\"" . $link["name"] . "\" style=\"$style_str\"";
					if (!empty($link["id"])) $prop_str .= "id=\"" . $link["id"] . "$i\"";
					echo "<a target=\"$target\" href=\"$url\" $prop><img $prop_str></a>";
				}
				echo "</td>\n";
			} else {
				echo "<td width=" . (strlen($link["name"])*8) . " nowrap>";
				if ($ck_link == 1) echo "<a target=\"$target\" class=\"$this->css_link_general\" href=\"$url\" $prop>[" . $link["name"] . "]</a>";
				echo "</td>\n";
			}
		}
	}
	/*
	public function add_qry_default_value($field, $value) {
		$this->add_field($field, $label="", $type="dbfield");
		$c = count($this->ref)-1; // existing index
		$this->ref[$c]["value"] = $value; 
	}
	*/
	private function build_xml() {
		if (isset($this->vars["obj"]) && $this->vars["obj"] == "filter" && count($this->ref_filter) > 0) {
			for ($i=0; $i<count($this->ref_filter); $i++) {
				$ref[] = $this->ref_filter[$i]["object"];
			}
		} else if (isset($this->vars["obj"]) && $this->vars["obj"] == "entity_field") {
			for ($i=0; $i<count($this->ref); $i++) {
				if ($this->ref[$i]["object"]->type == "entity" &&
					$this->ref[$i]["object"]->prefix == $this->vars["prefix"]) {
						$ref = $this->ref[$i]["object"]->field;
						break;
				}
			}
		} else if (isset($this->vars["obj"]) && $this->vars["obj"] == "list") {
			$ref = $this->ref_list;
		} 
		if (!isset($ref)) { /* Solicitado por campo no corpo principal do formulario, ou por campo de entidade que deve consultar o corpo principal do formulario */
			for ($i=0; $i<count($this->ref); $i++) {
				$ref[] = $this->ref[$i]["object"];
			}
		}
		for ($i=0; $i<count($ref); $i++) {
			$obj = $ref[$i];
			if ($obj->type != "entity") {
				$field = $obj->field;
				if (is_array($field)) {
					$str = "";
					for ($j=0; $j<count($field); $j++) {
						$str .= ($j > 0 ? "_" : "") . $field[$j];
					}
					$field = $str;
				}
				if (strtolower($this->getFieldLbl($field)) == strtolower($this->vars["field"])) {
					break;
				}
			}
			if ($obj->type == "entity") {
				$ck = 0;
				for ($j=0; $j<count($obj->field); $j++) {
					if (strtolower($obj->field[$j]->field) == strtolower($this->vars["field"])) {
						$obj = $obj->field[$j];
						$ck = 1;
					}
					if ($ck == 1) break;
				}
				if ($ck == 1) break;
			}
			if ($obj->type == "radio" && isset($obj->list)) {
				$ck = 0;
				foreach ($obj->list as $item) {
					if (isset($item["field"]) && strtolower($item["field"]) == strtolower($this->vars["field"])) {
						$obj = $item["object"];
						$ck = 1;
					}
					if ($ck == 1) break;
				}
				if ($ck == 1) break;
			}
		}
		//echo "<pre>"; print_r($ref); echo "</pre>";
		if (isset($this->vars[$this->keyword])) {
			if (isset($obj->qry)) {
				$sql = $obj->qry["sql"];
				preg_match_all("[\[([a-zA-Z0-9_])+\]]", $sql, $match);
				for ($i=0; $i<count($match[0]); $i++) {
					$sql = str_replace($match[0][$i], $this->vars[substr($match[0][$i],1,-1)], $sql);
				}
				$obj->qry["ix"] = $obj->qry["ix"];
				$obj->qry["label"] = $obj->qry["label"];
			} else if (isset($obj->ajaxtable)) {
				$table = $obj->ajaxtable;
				$id = $obj->ajaxid;
				if (is_array($id)) {
					$str = "";
					for ($i=0; $i<count($id); $i++) {
						$str .= ($i > 0 ? "+';'+" : "") . "$table." . $id[$i];
					}
					$id = $str;
				} else {
					$id = "$table.$id";
				}
				$label = $obj->ajaxlabel;
				$filter = $obj->ajaxfilter;
				$xtra_condition = $obj->ajaxcondition;
				$condition = "";
				if (is_array($filter)) {
					$condition .= "(\n";
					for ($i=0; $i<count($filter); $i++) {
						if (is_array($filter[$i])) {
							$keys = array_keys($filter[$i]);
							$ix = $keys[0];
						} else {
							$ix = $filter[$i];
						}
						if ($this->vars["ck_$ix"] == 1)
						$condition .= "$ix LIKE '%" . $this->vars[$this->keyword] . "%' OR\n";
					}
					$condition .= "1=0) AND\n";
				} else {
					$condition .= "$filter LIKE '%" . $this->vars[$this->keyword] . "%' AND\n";
				}
				if (is_array($xtra_condition)) {
					for ($i=0; $i<count($xtra_condition); $i++) {
						$condition .= "\t\t" . $xtra_condition[$i] . " AND\n";
					}
				} else if ($xtra_condition != "") {
					$condition .= "\t\t" . $xtra_condition . " AND\n";
				}
				//if ($obj->condition != "") $condition .= $obj->condition . " AND\n";
				$sql = "SELECT DISTINCT
						$id AS id,
						$label AS label
					FROM $table
					WHERE 
						$condition
						1 = 1 
					ORDER BY 
						$label";
				$obj->qry["ix"] = "id";
				$obj->qry["label"] = "label";
			}
			$res = nc_query($sql);
			$output = "QRY";
		} else if (isset($obj->qry)) {
			if (isset($this->vars["id_ref"]) && $this->vars["id_ref"] != "") {
				$parts = explode(":", $this->vars["id_ref"]);
				if (count($parts) == 2) $this->vars[$parts[0]] = $parts[1];
			}
			$sql = $obj->qry["sql"];
			$sql = str_replace("[" . $this->vars["field"] . "]", 0, $sql);
			preg_match_all("[\[([a-zA-Z0-9_])+\]]", $sql, $match);
			for ($i=0; $i<count($match[0]); $i++) {
				$parent_field = substr($match[0][$i],1,-1);
				if (isset($this->vars[$parent_field])) $sql = str_replace("[$parent_field]", $this->vars[$parent_field], $sql);
			}
			$res = nc_query($sql);
			$output = "QRY";
		} else if (isset($obj->list)) {
			if ($obj->list["vals"] instanceof Closure) {
				$res = $obj->list["vals"]($_GET[$obj->ajax[0]["field"]]);
			} else {
				$res = $obj->list["vals"];
			}
			$output = "LIST";
		}
		header("Content-type: text/xml");
		echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
		echo "<xmlresponse>\n";
		if ($output == "QRY") {
			if ($this->debug == 1 && isset($sql)) echo "<sql><![CDATA[$sql]]></sql>\n"; 
			foreach ($res as $r) { 
				echo "<data>\n";
				echo "<id><![CDATA[" . $r[$obj->qry["ix"]] . "]]></id>\n";
				$str = $this->parse_label($r, $obj->qry["label"]);
				echo "<text><![CDATA[$str]]></text>\n";
				foreach ($r as $field => $val) {
					if ($val instanceof DateTime) $val = $val->format("d/m/Y");
					if (!is_numeric($field) && $field != $obj->qry["ix"] && $field != $obj->qry["label"]) echo "<$field><![CDATA[$val]]></$field>\n";
				}
				echo "</data>\n";
			}
		} else if ($output == "LIST") {
			foreach ($res as $ix => $val) { 
				echo "<data>\n";
				echo "<id><![CDATA[$ix]]></id>\n";
				echo "<text><![CDATA[$val]]></text>\n";
				echo "</data>\n";
			}
		}
		echo "</xmlresponse>\n";
	}
	private function build_xml_unique() {
		$str = "";
		foreach (explode(",", $this->vars["fields"]) as $f) {
			$str .= $f . " = '" . $this->vars[$f] . "' AND ";
		}
		if (isset($this->unique_condition[$_REQUEST["unique_ix"]])) {
			$sql = $this->unique_condition[$_REQUEST["unique_ix"]];
			preg_match_all("[\[([a-zA-Z0-9_])+\]]", $sql, $match);
			foreach ($match[0] as $found) {
				$sql = str_replace($found, "'".$this->vars[strtolower(substr($found,1,-1))]."'", $sql);
			}
			$str .= "$sql AND ";
		}
		$qry = "SELECT COUNT(*) AS ck FROM $this->table 
			WHERE
				" . (isset($_GET["pk"]) ? $_GET["pk"]  . " <> '" . $_GET["val"] . "' AND" : "") . "
				$str
				1 = 1";
		$res = nc_query($qry);
		header("Content-type: text/xml");
		echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
		echo "<xmlresponse>\n";
		if ($this->debug == 1) echo "<sql><![CDATA[$qry]]></sql>\n";
		echo "<data>\n";
		echo "<ck>" . $res[0]["ck"] . "</ck>\n";
		echo "</data>\n";
		echo "</xmlresponse>\n";
	}
	private function build_xml_insert() {
		$fields = $this->vars["field"];
		$vals = "'".$this->vars["new_entry"]."'";
		foreach ($this->vars as $ix => $val) {
			if (substr($ix,0,2) == "f:") {
				$fields .= ", " . substr($ix,2);
				$vals .= ", '$val'";
			}
		}
		$qry = "INSERT INTO ".$this->vars["tbl"]." ($fields) VALUES ($vals)";
		if ($this->db == "MSSQL") {
			$qry_str = "$qry;\n";
			$qry = "SELECT SCOPE_IDENTITY() AS id";
			$qry_str .= "$qry;\n";
			$res = nc_query($qry_str, "QUERY");
			$_id = $res[0]["id"];
		} else if ($this->db == "MYSQL") {
			$connect = new connect;
			$connect->query($qry);
			$_id = $connect->id;
			$connect->close();
		}
		header("Content-type: text/xml");
		echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
		echo "<xmlresponse>\n";
		if ($this->debug == 1) echo "<sql><![CDATA[$qry]]></sql>\n";
		echo "<data>\n";
		echo "<msg>OK</msg>\n";
		echo "<id>$_id</id>\n";
		echo "<label><![CDATA[".$this->vars["new_entry"]."]]></label>\n";
		echo "</data>\n";
		echo "</xmlresponse>\n";
	}
	public function build_xls() {
		header("Content-type: application/x-msexcel");
		header("Content-Disposition: attachment; filename=" . $this->vars["filename"]);
		echo "<html>\n";
		echo "<head>\n";
		if (isset($this->css_xls_path))
			$css = $this->css_xls_path;
		else
			$css = $this->css_path;
		if ($css != "") {
			$file = file($css);
			echo "<style type=\"text/css\">\n<!--\n";
			for ($i=0; $i<count($file); $i++) {
				echo $file[$i];
			}
			echo "-->\n</style>\n";
		}
		echo "</head>\n";
		echo "<body>\n";
		$content = $this->vars["html"];
		$decode = base64_decode($content,true);
		if (!empty($decode)) $content = utf8_decode($decode);
		echo $content . "\n"; // str_replace("\\\"","\"",$content) . "\n";
		echo "</body>\n";
		echo "</html>\n";
	}
	public function build_csv() {
		header("Content-type: text/csv;");
		header("Content-Disposition: attachment; filename=\"{$this->ent}.csv\"");
		$c = 0;
		foreach ($this->label as $label) {
			if ($label["ck_hidden"] == 0) {
				echo ($c>0 ? ";" : "") . $label["label"];
				$c++;
			}
		}
		echo "\r\n";
		$this->pageby = false;
		foreach ($this->get_list() as $r) {
			$c = 0;
			foreach ($this->label as $label) {
				if ($label["ck_hidden"] == 0) {
					if ($label["field"] instanceof Closure)
						$val = $label["field"]($r);
					else if (!empty($label["field"]))
						$val = $r[$this->getFieldLbl($label["field"])];
					else
						$val = null;
					if (isset($label["parser"])) {
						$val = $label["parser"]($val, $r);
					} else if ($val instanceof DateTime) {
						if (strtotime($val->format("Y-m-d H:i")) != strtotime($val->format("Y-m-d")))
							$val = $val->format("d/m/Y H:i");
						else
							$val = $val->format("d/m/Y");
					} else if ($label["mode"] == "NUM") {
						$val = formatnum($val, $label["depth"]."+", ",", ".");
					} else if ($label["mode"] == "flag") {
						$val = !empty($val) ? "X" : "";
					}
					if (!empty($val)) $val = str_replace('"', '""', $val);
					$val = strip_tags((string)$val);
					echo ($c>0 ? ";" : "") . "\"$val\"";
					$c++;
				}
			}
			echo "\r\n";
		}
	}
	public function use_multiple_insert($num) {
		if (is_numeric($num))
			$this->repeat_insert = $num;
	}
	private function insert($use_table=false, $ck_confirm=1) {
		$this->builder = "form";
		$fields = [];
		if (!empty($this->adm)) {
			$str_field = [ $this->table => "" ];
			$str_val =   [ $this->table => "" ];
			$ck_insert = [ $this->table => 1 ];
		}
		$qry_multiple = "";
		for ($i=0; $i<count($this->ref); $i++) {
			$obj = $this->ref[$i]["object"];
			if ($obj->type != "fieldlist" &&
				!empty($obj->rec_table) && 
				!empty($obj->rec_table["table"]) && 
				!array_key_exists("multiple",$obj->prop)) {
				$table = $obj->rec_table["table"];
			} else if (count($this->tab_ref) == 0 || empty($this->tab_ref[$obj->tab_id]["table"])) {
				if (!empty($this->adm))
					$table = $this->table;
				else
					$table = false;
			} else {
				$table = $this->tab_ref[$obj->tab_id]["table"];
			}
			if (!isset($ck_insert[$table])) $ck_insert[$table] = 0;
			if (!isset($str_field[$table])) {
				$str_field[$table] = "";
				$str_val[$table] = "";
			}
			if ($obj->type != "entity" &&
				$obj->type != "display" &&
				$obj->type != "file" &&
				$obj->ck_qry == 1) {
				$field = $obj->field;
				$fields[] = $field;
				if (!empty($obj->ck_log)) {
					if (!empty($obj->log_parser)) {
						$val = $obj->log_parser;
						if (($val instanceof Closure)) $val = $val($this->vars);
					} else {
						$val = $this->vars[$field];
					}
					if (!empty($val)) {
						$val = "[$obj->log_signature] " . trim($val);
						$val = str_replace("'","''",$val);
					}
				} else if ($table != $this->table) {
					$val = $this->get_val($obj, $table . "_");
				} else {
					$val = $this->get_val($obj);
				}
				if (!is_array($val) &&
					$val != null &&
					$val != "NULL" &&
					trim($val) != "''" &&
					trim($val) != "'&nbsp;'" &&
					$obj->type != "checkbox") 
					$ck_insert[$table] = 1;
				if ($obj->type == "daterange") {
					$str_field[$table] .= ($str_field[$table] != "" ? "," : "") . "{$field}_ini, {$field}_fim";
					$str_val[$table] .= ($str_val[$table] != "" ? "," : "") . $val["ini"] . "," . $val["fim"];
				} else if ($obj->type == "dropdown" && array_key_exists("multiple",$obj->prop) && isset($obj->rec_table)) {
					foreach ($val as $v) {
						if ($v != "") $qry_multiple .= "INSERT INTO " . $obj->rec_table["table"] . " (" . $obj->rec_table["field"] . ", $field) VALUES ('[pk]', '$v');\n";
					}
				} else if ($obj->type == "fieldlist") {
					$keys = array_keys($obj->rec_table["xtra_key"]);
					$str_xtra_field = "";
					$str_xtra_val = "";
					for ($j=0; $j<count($keys); $j++) {
						$str_xtra_field .= $keys[$j] . ",";
						$str_xtra_val .= "'" . $obj->rec_table["xtra_key"][$keys[$j]] . "',";
					}
					foreach (explode(";",$val) as $v) {
						$qry_multiple .= "INSERT INTO " . $obj->rec_table["table"] . " ($str_xtra_field " . $obj->rec_table["field"] . ", $field) VALUES ($str_xtra_val '[pk]', '$v');\n";
					}
				} else if (strlen((string)$val) > 0) {
					$str_field[$table] .= ($str_field[$table] != "" ? "," : "") . $field;
					if ($obj->ck_log == 1) {
						$str_val[$table] .= ($str_val[$table] != "" ? "," : "") . "'$val'";
					} else {
						$str_val[$table] .= ($str_val[$table] != "" ? "," : "") . $val;
					}
				}
				if (!empty($val)) $val = str_replace("'","",$val);
				if ($obj->type == "radio" && 
					isset($obj->list["vals"][$val])) {
					$item = $obj->list["vals"][$val];
					if (is_array($item) && 
						!empty($item["ck_xtra"]) && 
						$item["type"] != null) {
						$field = $item["field"];
						$val = $this->get_val($item);
						$str_field[$table] .= ($str_field[$table] != "" ? "," : "") . $field;
						$str_val[$table] .= ($str_val[$table] != "" ? "," : "") . $val;
					}
				}
			}
		}
		foreach ($this->insert_val as $ins) {
			if (strpos($ins["val"], "(") === false) $ins["val"] = "'" . $ins["val"] . "'";
			$str_field[$this->table] .= ($str_field[$this->table] != "" ? "," : "") . $ins["field"];
			$str_val[$this->table] .= ($str_val[$this->table] != "" ? "," : "") . $ins["val"];
		}
		foreach (array_keys($str_field) as $tbl) {
			if (!empty($tbl) && 
				(!$use_table || $use_table == $tbl) &&
				($tbl == $this->table || $ck_insert[$tbl] == 1)) {
				if ($tbl != $this->table) {
					$ck_tab = 0;
					for ($j=0; $j<count($this->tab_ref); $j++) {
						if ($this->tab_ref[$j]["table"] == $tbl) {
							$ck_tab = 1;
							break;
						}
					}
					if ($ck_tab == 1) {
						$str_field[$tbl] .= ", " . $this->tab_ref[$j]["key"];
						$str_val[$tbl] .= "," . $this->id;
					} else {
						$ck_ref = 0;
						for ($j=0; $j<count($this->ref); $j++) {
							if (isset($this->ref[$j]["entity"]["table"]) && $this->ref[$j]["entity"]["table"] == $tbl) {
								$ck_ref = 1;
								break;
							}
						}
						if ($ck_ref == 1) {
							$str_field[$tbl] .= ", " . $this->ref[$j]["entity"]["field"];
							$str_val[$tbl] .= "," . $this->id;
							$xtra_key = $this->ref[$j]["entity"]["xtra_key"];
							$xtra_keys = array_keys($xtra_key);
							for ($j=0; $j<count($xtra_keys); $j++) {
								if (substr($xtra_key[$xtra_keys[$j]],0,6) != "SELECT") {
									$str_field[$tbl] .= "," . $xtra_keys[$j];
									$str_val[$tbl] .= ",'" . $xtra_key[$xtra_keys[$j]].",";
								}
							}
						} 
					}
				}
				if (!empty($tbl)) {
					$qry = "INSERT INTO " . $tbl . " (" . $str_field[$tbl] . ") VALUES (" . $str_val[$tbl] . ")";
					if ($this->db == "MSSQL") {
						$qry_str = "$qry;\n";
						$qry = "SELECT SCOPE_IDENTITY() AS id";
						$qry_str .= "$qry;\n";
						if ($this->debug == 1) $this->show_debug($qry_str,"?");
						$connect = new connect;
						$res = $connect->query($qry_str, "QUERY", true);
						$connect->close();
						if (count($res) == 0) exit("<pre>ERRO NA GRAVAÇÃO DE " . $this->ent . ":\n$qry</pre>");
						$_id = $res[0]["id"];
					} else if ($this->db == "MYSQL") {
						if ($this->debug == 1) $this->show_debug($qry,"?");
						$connect = new connect;
						$connect->query($qry);
						$_id = $connect->id;
						$connect->close();
					}
					if (in_array($this->pk, $fields)) $_id = $this->vars[$this->pk];
					if ($tbl == $this->table) {
						$this->id = $_id;
						$this->return_pk .= ($this->return_pk != "" ? ", " : "") . $_id;
					} else {
						$this->id_table[$tbl] = $_id;
					}
				}
			} // if
		} // for
		if ($qry_multiple != "") {
			$qry_multiple = str_replace("[pk]", $this->id, $qry_multiple);
			if ($this->debug == 1) $this->show_debug($qry_multiple,"M");
			if ($this->db == "MSSQL") {
				nc_query($qry_multiple,"NONQUERY");
			} else if ($this->db == "MYSQL") {
				foreach (explode(";", $qry_multiple) as $qry) { 
					if (trim($temp[$i]) != "") nc_query($qry);
				}
			}
		}
		// files
		$upd_file = "";
		for ($i=0; $i<count($this->ref); $i++) {
			$obj = $this->ref[$i]["object"];
			if ($obj->type == "file") {
				$val = $this->get_val($obj);
				if ($obj->ck_qry == 1) {
					$upd_file .= ($upd_file != "" ? ", " : "") . $obj->field . " = $val";
				}
			}
		}
		if ($upd_file != "") {
			$qry = "UPDATE {$this->table} SET $upd_file WHERE $this->pk = '$this->id'";
			nc_query($qry);
			if ($this->debug == 1) $this->show_debug($qry,"F");
		}
		if (count($this->ent_1XN) > 0 || 
			count($this->ent_NXN) > 0) {
			$this->update_entity();
		}
		if ($ck_confirm == 1)
			$this->build_confirm();
		if (isset($this->exec_input)) { $ex = $this->exec_input; $ex(); }
	}
	private function update() {
		$this->builder = "form";
		if (isset($this->vars["id"])) $this->id = $this->return_pk = $this->vars["id"];
		if (!empty($this->adm)) {
			$upd_str = [ $this->table => "" ];
			$ins_str_field = [ $this->table => "" ];
			$ins_str_val = [ $this->table => "" ];
		}
		$qry_multiple = "";
		for ($i=0; $i<count($this->ref); $i++) {
			$obj = $this->ref[$i]["object"];
			if (isset($obj->rec_table) && !empty($obj->rec_table["table"])) {
				$table = $obj->rec_table["table"];
				$key = $obj->rec_table["field"];
				$xtra_key = $obj->rec_table["xtra_key"];
				$join_str[$table] = "INNER JOIN $this->table ON $table.$key = $this->table.$this->pk";
				$key_field[$table] = $key;
				if (is_array($xtra_key)) {
					$keys = array_keys($xtra_key);
					for ($j=0; $j<count($keys); $j++) {
						if (substr($xtra_key[$keys[$j]],0,6) == "SELECT")
							$join_str[$table] .= " AND $table." . $keys[$j] . " IN (" . $xtra_key[$keys[$j]] . ")";
						else
							$join_str[$table] .= " AND $table." . $keys[$j] . " = '" . $xtra_key[$keys[$j]] . "'";
					}
				}
			} else if ($obj->tab_id < 0 || empty($this->tab_ref[$obj->tab_id]["table"])) {
				if (!empty($this->adm))
					$table = $this->table;
				else
					$table = false;
			} else {
				$table = $this->tab_ref[$obj->tab_id]["table"];
				$key = $this->tab_ref[$obj->tab_id]["key"];
				$join_str[$table] = "INNER JOIN $this->table ON $table.$key = $this->table.$this->pk";
				$key_field[$table] = $key;
			}
			if (!isset($upd_str[$table])) {
				if (!isset($upd_str[$table]) && !empty($key_field[$table])) {
					$ins_str_field[$table] = $key_field[$table];
					$ins_str_val[$table] = $this->vars["id"];
				}
				$upd_str[$table] = "";
			}
			if ($obj->tab_id > 0 && $this->tab_ref[$obj->tab_id]["table"] != null)
				$prefix = $this->tab_ref[$obj->tab_id]["table"]."_";
			else
				$prefix = "";
			if ($obj->type != "entity" &&
				$obj->type != "display" &&
				$obj->ck_qry == 1 &&
				$obj->ck_readonly != 1 &&
				!empty($table)) {
				if ($obj->type != "entity" &&
					!$this->ck_disabled($this->ref, $i, $this->vars) &&
					($obj->type != "file" ||
					 !empty($this->vars["remove$obj->field"]) ||
					 (is_array($_FILES[$obj->field]["name"]) && $_FILES[$obj->field]["name"][0] != "") ||
					 (!is_array($_FILES[$obj->field]["name"]) && $_FILES[$obj->field]["name"] != ""))) {
					$field = $obj->field;
					if (!empty($obj->ck_log)) {
						if (!empty($obj->log_parser)) {
							$val = $obj->log_parser;
							if (($val instanceof Closure)) $val = $val($this->vars);
						} else {
							$val = $this->vars[$field];
						}
						if (!empty($val)) {
							$val = "[$obj->log_signature] " . trim($val);
							$val = str_replace("'","''",$val);
						}
					} else {
						$val = $this->get_val($obj, $prefix);
					}
					if ($obj->type == "daterange") {
						$upd_str[$table] .= ($upd_str[$table] != "" ? ",\n\t" : "") . "{$field}_ini = " . $val["ini"] . ", {$field}_fim = " . $val["fim"];
						$ins_str_field[$table] .= ($ins_str_field[$table] != "" ? ",\n" : "") . "{$field}_ini, {$field}_fim";
						$ins_str_val[$table] .= ($ins_str_val[$table] != "" ? ",\n" : "") . $val["ini"] . ", " . $val["fim"];
					} else if ($obj->type == "dropdown" && array_key_exists("multiple",$obj->prop) && isset($obj->rec_table)) {
						$qry_multiple .= "DELETE FROM " . $obj->rec_table["table"] . " WHERE " . $obj->rec_table["field"] . " = '" . $this->vars["id"] . "';\n";
						$xtra_key = "";
						$xtra_key_vals = "";
						$keys = array_keys($obj->rec_table["xtra_key"]);
						for ($j=0; $j<count($keys); $j++) {
							$xtra_key .= $keys[$j] . ",";
							$xtra_key_vals .= "'" . $obj->rec_table["xtra_key"][$keys[$j]] . "',";
						}
						for ($j=0; $j<count($val); $j++) {
							$qry_multiple .= "INSERT INTO " . $obj->rec_table["table"] . " ($xtra_key " . $obj->rec_table["field"] . ", $field) VALUES ($xtra_key_vals '" . $this->vars["id"] . "', '" . $val[$j] . "');\n";
						}
					} else if ($obj->type == "fieldlist") {
						$keys = array_keys($obj->rec_table["xtra_key"]);
						$str_xtra_keys = "";
						$str_xtra_field = "";
						$str_xtra_val = "";
						for ($j=0; $j<count($keys); $j++) {
							$str_xtra_keys .= $keys[$j] . " = '" . $obj->rec_table["xtra_key"][$keys[$j]] . "' AND\n";
							$str_xtra_field .= $keys[$j] . ",";
							$str_xtra_val .= "'" . $obj->rec_table["xtra_key"][$keys[$j]] . "',";
						}
						$qry_multiple .= "DELETE FROM " . $obj->rec_table["table"] . " WHERE $str_xtra_keys " . $obj->rec_table["field"] . " = '" . $this->vars["id"] . "';\n";
						$keys = explode(";",$val);
						for ($j=0; $j<count($keys); $j++) {
							$qry_multiple .= "INSERT INTO " . $obj->rec_table["table"] . " ($str_xtra_field " . $obj->rec_table["field"] . ", $field) VALUES ($str_xtra_val '" . $this->vars["id"] . "', '" . $keys[$j] . "');\n";
						}
					} else if ($obj->ck_log == 1) {
						if ($val != "NULL") {
							if ($this->db == "MSSQL")
								$upd_str[$table] .= ($upd_str[$table] != "" ? ",\n\t" : "") . "$table.$field = CASE WHEN $table.$field IS NOT NULL THEN $table.$field + CHAR(13) ELSE '' END + '$val'";
							else if ($this->db == "MYSQL")
								$upd_str[$table] .= ($upd_str[$table] != "" ? ",\n\t" : "") . "$table.$field = CONCAT(CASE WHEN $table.$field IS NOT NULL THEN CONCAT($table.$field, CHAR(13)) ELSE '' END, '$val')";
							$ins_str_field[$table] .= ($ins_str_field[$table] != "" ? ",\n" : "") . "$table.$field";
							$ins_str_val[$table] .= ($ins_str_val[$table] != "" ? ",\n" : "") . "'$val'";
						}
					} else if ($obj->type != "password" || $val != "NULL") {
						$upd_str[$table] .= ($upd_str[$table] != "" ? ",\n\t" : "") . "$table.$field = $val";
						$ins_str_field[$table] .= ($ins_str_field[$table] != "" ? ",\n" : "") . "$table.$field";
						$ins_str_val[$table] .= ($ins_str_val[$table] != "" ? ",\n" : "") . $val;
					}
					if (!is_array($val)) $val = str_replace("'","",(string)$val);
					if ($obj->type == "radio" && 
						isset($obj->list["vals"][$val])) {
						$item = $obj->list["vals"][$val];
						if (is_array($item) && 
							!empty($item["ck_xtra"]) && 
							$item["type"] != null) {
							$field = $item["field"];
							$val = $this->get_val($item);
							$upd_str[$table] .= ($upd_str[$table] != "" ? ",\n\t" : "") . "$table.$field = $val";
							$ins_str_field[$table] .= ($ins_str_field[$table] != "" ? ",\n" : "") . "$table.$field";
							$ins_str_val[$table] .= ($ins_str_val[$table] != "" ? ",\n" : "") . $val;
						}
					}
				}
			}
			if (!empty($obj->xtra_field)) {
				foreach ($obj->xtra_field as $x) {
					if ($x["condition"] === false || $x["condition"]($val)) {
						$val = $x["val"];
						if ($val != "NULL" && !is_numeric($val) && strpos($val,"(") === false) $val = "'$val'";
						$upd_str[$table] .= ($upd_str[$table] != "" ? ",\n\t" : "") . $table . "." . $x["field"] . " = " . $val;
						$ins_str_field[$table] .= ($ins_str_field[$table] != "" ? ",\n" : "") . $table . "." . $x["field"];
						$ins_str_val[$table] .= ($ins_str_val[$table] != "" ? ",\n" : "") . $val;
					}
				}
			}
		}
		foreach ($this->ref_tbl_val as $field => $val) {
			$temp = explode(".", $field);
			if (count($temp) == 2) {
				$table = $temp[0];
				$field = $temp[1];
			} else {
				$table = $this->table;
			}
			if ($val != "") $upd_str[$table] .= ($upd_str[$table] != "" ? ",\n\t" : "") . "$table.$field = $val";
		}
		// multiple tables
		$qry_str = "";
		foreach (array_keys($upd_str) as $tbl) {
			if (!empty($tbl) && $upd_str[$tbl] != "") {
				$where_str = "";
				if (is_array($this->pk)) {
					$pks = explode("|", $this->vars["id"]);
					for ($j=0; $j<count($this->pk); $j++) {
						$where_str .= ($j>0 ? " AND\n\t" : "") . "$this->table." . $this->pk[$j] . " = '" . $pks[$j] . "'";
					}
				} else {
					$where_str .= "$this->table.$this->pk = '" . $this->vars["id"] . "'";
				}
				if ($tbl == $this->table) {
					$qry = "UPDATE $tbl\n"
						. "SET\n\t" . $upd_str[$tbl] . "\n"
						. "WHERE\n\t"
						. $where_str;
					$qry_str .= "$qry;\n";
					if ($this->db == "MYSQL") nc_query($qry);
				} else {
					$qry = "SELECT COUNT(*) AS c FROM $tbl 
						" . $join_str[$tbl] . "
						WHERE $where_str";
					$res = nc_query($qry);
					if ($res[0]["c"] == 0) {
						$this->insert($tbl,0);
					} else {
						if ($this->db == "MSSQL") {
							$qry = "UPDATE $tbl
								SET " . $upd_str[$tbl] . "
								FROM $tbl
								" . $join_str[$tbl] . "
								WHERE $where_str\n";
							$qry_str .= "$qry;\n";
							$qry_str .= "IF @@ROWCOUNT = 0 \n";
							$qry_str .= "INSERT INTO $tbl (" . $ins_str_field[$tbl] . ")\nVALUES (" . $ins_str_val[$tbl] . ")\n";
						} else if ($this->db == "MYSQL") {
							$qry = "UPDATE $tbl
								" . $join_str[$tbl] . "
								SET " . $upd_str[$tbl] . "
								WHERE $where_str\n";
							$qry_str .= "$qry;\n";
						}
						if ($this->db == "MYSQL") nc_query($qry);
						// check updated rows
					}
				}
			}
		}
		if ($qry_multiple != "") {
			$qry_str .= "$qry_multiple";
			if ($this->db == "MYSQL") {
				$temp = explode(";", $qry_multiple);
				for ($j=0; $j<count($temp); $j++) { 
					if (trim($temp[$j]) != "") nc_query($temp[$j]);
				}
			}
		}
		if (!empty($qry_str)) {
			if ($this->debug == 1) $this->show_debug($qry_str,"?");
			if ($qry_str != "" && $this->db == "MSSQL") nc_query($qry_str,"NONQUERY");
		}
		if (count($this->ent_1XN) > 0 || 
			count($this->ent_NXN) > 0) {
			$this->update_entity();
		}
		$this->build_confirm();
		if (isset($this->exec_input)) { $ex = $this->exec_input; $ex(); }
	}
	private function delete() {
		$del = [];
		for ($i=0; $i<$this->vars["count"]; $i++) {
			if (isset($this->vars["delete$i"])) $del[] = $this->vars["delete$i"];
		}
		if (!empty($del)) {
			$qry_str = "";
			foreach ($this->sql_cmd as $cmd) {
				if (strpos($cmd["use"],"D") !== false) $qry_str .= $cmd["sql"] . "\n";
			}
			foreach ($this->delete_cascade as $child) {
				$qry = "DELETE FROM " . $child["table"] . " WHERE " . $child["ix"] . " IN ('" . implode("','",$del) . "')";
				$qry_str .= "$qry;\n";
				if ($this->db == "MYSQL") nc_query($qry);
			}
			foreach ($this->update_cascade as $child) {
				$qry = "UPDATE " . $child["table"] . " SET " . $child["ix"] . " = NULL WHERE " . $child["ix"] . " IN ('" . implode("','",$del) . "')";
				$qry_str .= "$qry;\n";
				if ($this->db == "MYSQL") nc_query($qry);
			}
			if (is_array($this->pk)) {
				$where_str = "";
				foreach ($del as $item) {
					$sel = "";
					foreach (json_decode($item) as $ix => $val) {
						if ($val instanceof stdClass && isset($val->date)) 
							$val = "CONVERT(datetime, '" . substr($val->date,0,23) . "', 120)";
						else
							$val = "'$val'";
						$sel .= ($sel != "" ? " AND " : "") . "$ix = $val";
					}
					$where_str .= ($where_str != "" ? " OR " : "") . "($sel)";
				}
			} else {
				$where_str = "$this->pk IN ('" . implode("','",$del) . "')";
			}
			$qry = "DELETE FROM $this->table WHERE $where_str";
			$qry_str .= "$qry;\n";			
			if ($this->db == "MYSQL") nc_query($qry);
			if ($this->debug == 1) $this->show_debug($qry_str,"?");
			if ($qry_str != "" && $this->db == "MSSQL") nc_query($qry_str,"NONQUERY");
		}
		$this->build_confirm();
	}
	private function get_file_dir($file, $create_dir=false, $prefix=false, $c=false) {
		$dir = $file["dir"]["D"];
		preg_match_all("/\[([a-zA-Z0-9_.,])+\]/", $dir, $match);
		if (isset($file["sql"])) {
			$res_dir = nc_query(str_replace("[id]", $this->id, $file["sql"]));
			foreach ($match[0] as $ix) {
				$dir = str_replace($ix, $res_dir[0][substr($ix,1,-1)], $dir);
			}
		} else {
			if (isset($this->id)) {
				foreach ($match[0] as $ix) {
					$temp = explode(",", substr($ix,1,-1));
					if ($temp[0] == $this->pk) {
						$val = $this->id;
					} else if (isset($this->vars[$temp[0]])) {
						$val = $this->vars[$temp[0]];
					} else if (isset($this->vars[$prefix.$temp[0].$c])) {
						$val = $this->vars[$prefix.$temp[0].$c];
					} else {
						$qry = "SELECT " . $temp[0] . " AS val FROM $this->table WHERE $this->pk = $this->id";
						$res = nc_query($qry);
						$val = $res[0]["val"];
					}
					if (count($temp) == 2) $val = str_pad($val,$temp[1],'0',STR_PAD_LEFT);
					$dir = str_replace($ix, $val, $dir);
				}
			}
		}
		if ($create_dir) {
			if (count($match[0]) > 0 && !is_dir($dir)) {
				$temp = explode("/", $dir);
				$path = "";
				for ($j=0; $j<count($temp); $j++) {
					$path .= ($j>0?"/":"") . $temp[$j];
					if (!is_dir($path)) {
						if ($this->debug == 1) echo "<div class=alert>Criando diretório $path</div>\n";
						mkdir($path);
						chmod($path, 0777);
					}
				}
			}
		}
		return $dir;
	}
	public function add_file($ref, $prefix="", $c="") {
		if ($this->use_sysdoc == 1) $sysdoc = new cls_sysdoc;
		if (gettype($ref) == "array") $ref = $ref["object"];
		$field = $prefix . $ref->field . $c;
		// files
		for ($i=0; $i<count($ref->file); $i++) {
			// file
			$files = [];
			if (is_array($_FILES[$field]["name"])) {
				for ($j=0; $j<count($_FILES[$field]["name"]); $j++) {
					$files[] = array(
						"name" => $_FILES[$field]["name"][$j],
						"tmp_name" => $_FILES[$field]["tmp_name"][$j]
					);
				}
			} else {
				$files[] = array(
					"name" => $_FILES[$field]["name"],
					"tmp_name" => $_FILES[$field]["tmp_name"]
				);				
			}
			if (array_key_exists("multiple", $ref->prop) && isset($this->vars[$field."_bak"])) {
				$return_str = $this->vars[$field."_bak"];
				if (!empty($this->vars["remove" . $field])) {
					foreach ($ref->file as $_file) {
						$_dir = $this->get_file_dir($_file,false,$prefix,$c);
						foreach ($this->vars["remove" . $field] as $_remove) {
							$_file_str = $_dir . "/" . $_remove;
							if (is_file($_file_str)) unlink($_file_str);
						}
					}
					$return_str = implode("|", array_diff(explode("|",$return_str), $this->vars["remove" . $field]));
				}
			} else {
				if (isset($this->vars[$field."_bak"]) && $this->vars[$field."_bak"] != "") {
					foreach ($ref->file as $_file) {
						$_dir = $this->get_file_dir($_file,false,$prefix,$c);
						$_file_str = $_dir . "/" . $this->vars[$field."_bak"];
						if (is_file($_file_str)) unlink($_file_str);
					}
				}
				$return_str = "";
			}
			foreach ($files as $file) {
				if ($file["name"] == "") break; 
				$f = strtr($file["name"], "áéíóúâêôàãõüçÁÉÍÓÚÂÊÔÀÃÕÜÇ°ª", "aeiouaeoaaoucAEIOUAEOAAOUCoa");
				$ext = substr($f,strrpos($f,".")+1);
				$allowed = ["pdf","txt","jpg","jpeg","png","gif","doc","docx","xls","xlsx","ppt","pptx","eml","msg","pfx"];
				if (!empty($this->debug)) array_push($allowed, "htm", "html");
				if (!in_array(strtolower($ext), $allowed)) {
					exit("<div class=alert>Tipo de arquivo invalido [$ext]</div>");
				}
				if (strtoupper($ref->file_name_mask) == "SELF") {
					$file_name = str_replace([";","/","?",":","&","=","+"], "_", $f);
				} else {
					$mask = str_replace("[SELF]", $f, $ref->file_name_mask);
					$file_name = $this->get_file_name($mask, $this->id, $prefix, $c);
					// use original extension
					$temp = explode(".", $file_name);
					if (count($temp) > 1) $file_name = substr($file_name, 0, -strlen($temp[count($temp)-1])-1);
					$temp = explode(".", $file["name"]);
					$ext = $temp[count($temp)-1];
					$file_name .= ".$ext";
				}
				$dir = $this->get_file_dir($ref->file[$i], true, $prefix, $c);
				if (!strcmp(substr(PHP_OS,0,3),"WIN"))
					$destino = "$dir\\$file_name";
				else
					$destino = "$dir/$file_name";
				if ($this->debug == 1) echo "<div class=alert>Copiando arquivo $destino</div>\n"; 
				if ($ref->file[$i]["maxsize"] == false) {
					copy($file["tmp_name"], $destino);
				} else {
					if ($this->use_sysdoc == 1 && $sysdoc->fn_ckpdf($destino)) {
						$sysdoc->fn_convertpdf2jpg($destino, $ref->file[$i]["dir"]["D"]."\\" . str_replace(".pdf","",$file_name), $ref->file[$i]["maxsize"], $ck_origem="abs", $ck_destino="abs"); 
					} else {
						$ck_resize = 1;
						if (!empty($ref->file[$i]["maxsize"])) {
							$s = getImageSize($file["tmp_name"]); 
							if (!empty($s)) {
								if (empty($ref->file[$i]["pos"]) && max($s[0],$s[1]) <= $ref->file[$i]["maxsize"]) 
									$ck_resize = 0;
								else if ($ref->file[$i]["pos"] == "H" && $s[0] <= $ref->file[$i]["maxsize"]) 
									$ck_resize = 0;
								else if ($ref->file[$i]["pos"] == "V" && $s[1] <= $ref->file[$i]["maxsize"]) 
									$ck_resize = 0;
							} else {
								$ck_resize = 0;
							}
						}
						if ($ck_resize == 1) {
							$dst_img = $this->image_resize($file["tmp_name"], $ref->file[$i]["maxsize"], $ref->file[$i]["pos"]);
							if ($dst_img == false) {
								echo "<span class=\"$this->css_text\" style=background-color:white;padding:0><br>$this->lang_file_format: <b>" . $file["name"] . "</b>.<br>$this->lang_file_format_types</span>";
								$this->debug = 1;
							} else {
								if (substr(strtolower($file_name), -4) == ".jpg" || substr(strtolower($file_name), -5) == ".jpeg") {
									ImageJpeg($dst_img, "$dir/$file_name", 100);
								} else if (substr(strtolower($file_name), -4) == ".gif") {
									ImageGif($dst_img, "$dir/$file_name");
								} else if (substr(strtolower($file_name), -4) == ".png") {
									ImagePng($dst_img, "$dir/$file_name");
								} else {
									$size = GetImageSize($file["tmp_name"]);
									if ($size[2] == 1) {
										$file_name .= ".gif";
										ImageGif($dst_img, "$dir/$file_name");
									} else if ($size[2] == 2) {
										$file_name .= ".jpg";
										ImageJpeg($dst_img, "$dir/$file_name", 100);
									} else if ($size[2] == 3) {
										$file_name .= ".png";
										ImagePng($dst_img, "$dir/$file_name");
									}
								}
								// thumb
								// $dst_img = $this->image_resize($file["tmp_name"], $ref->file[$i]["maxsize"]);
								// ImageJpeg($dst_img, "$dir/$file_name", 100);
							}
						} else {
							copy($file["tmp_name"], "$dir/$file_name");
						}
					}
				}
				$return_str .= ($return_str != "" ? "|" : "") . $file_name;
			}
		}
		return $return_str;
	}
	private function image_resize($imgname, $maxsize, $pos=null) {
		$size = GetImageSize($imgname);
		if ($size[2] == 1) {
			$src_img=ImageCreatefromGif($imgname); 
		} else if ($size[2] == 2) {
			$src_img=ImageCreatefromJpeg($imgname); 
		} else if ($size[2] == 3) {
			$src_img=ImageCreatefromPng($imgname); 
		} else {
			return false;
		}
		$w = $size[0];
		$h = $size[1];
		if ($pos == "H") {
			$new_w = $maxsize;
			$new_h = $new_w*($h/$w);
		} else if ($pos == "V") {
			$new_h = $maxsize;
			$new_w = $new_h*($w/$h);
		} else {
			$new_w = round(($w >  $h) ? $maxsize : ($w*$maxsize)/$h);
			$new_h = round(($h >= $w) ? $maxsize : ($h*$maxsize)/$w);
		}
		$dst_img=ImageCreatetruecolor($new_w, $new_h); 
		if ($size[2] == 3) {
			imagealphablending($dst_img, false);
			imagesavealpha($dst_img,true);
		}
		//imagecopyresized($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
		imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
		return $dst_img;
	}
	private function update_entity() {
		$this->builder_ref = "form";
		$qry_str = "";
		if ($this->db == "MSSQL") {
			$qry = "DECLARE @id_entity int";
			$qry_str .= "$qry;\n";
		}
		for ($i=0; $i<count($this->ent_1XN); $i++) {
			$p = $this->ent_1XN[$i];
			$e = $this->ref[$p]["object"];
			$ent_table = $e->table;
			$prefix = $e->prefix;
			$key_field = $e->key_field;
			$tab_id = $e->tab_id;
			$pk = $e->pk;
			if ($e->ck_readonly == 0) {
				if (is_array($key_field)) {
					$keys = "";
					$vals = "";
					foreach ($key_field as $ix => $k) {
						$ck = 0;
						foreach ($e->field as $f) if ($f->field == $k) $ck++;
						if ($ck == 0) {
							$keys .= ($keys != "" ? ",\n\t" : "") . $k;
							if (!is_numeric($ix)) {
								$val = $this->vars[$ix];
								if ($this->get_field_def($ix)->type == "date") $val = "CONVERT(datetime,'$val',120)";
							} else {
								$val = $this->vars[$k];
							}
							$vals .= ($vals != "" ? ",\n\t" : "") . $val;
						}
					}
					$key_field_val = $vals;
					$key_field = $keys;
				} else if (count($this->tab_ref) > 0 && !empty($this->tab_ref[$tab_id]["table"]) && $this->modo == "update") {
					$key_field_val = $this->vars["id_" . $this->tab_ref[$tab_id]["table"]];
				} else if (isset($this->id) && !empty($key_field)) {
					$key_field_val = $this->id;
				} else {
					$key_field_val = "";
				}
				$list_id = $this->vars["delete_list_".$prefix];
				if (!empty($list_id)) {
					$str_xtra_keys = "";
					if (!empty($e->xtra_key)) {
						foreach ($e->xtra_key as $key => $val) {
							$str_xtra_keys .= "$key = '$val' AND\n";
						}
					}
					foreach ($e->field as $ref) {
						if ($ref->type == "dropdown" && array_key_exists("multiple", $ref->prop)) {
							$qry = "DELETE FROM " . $ref->rec_table["table"] . "
									WHERE
										" . $ref->rec_table["field"] . " IN ($list_id)";
							$qry_str .= "$qry;\n";
							if ($this->db == "MYSQL") nc_query($qry);
						}
					}
					if (is_array($pk)) {
						$pk_str = "";
						foreach (explode(",",$list_id) as $val) {
							if ($val != "0") {
								$vals = explode(";",$val);
								$str = "";
								foreach ($pk as $_p => $_f) {
									$str .= ($str != "" ? " AND " : "") . $_f . " = " . $this->format_sql_str($vals[$_p]);
								}
								$pk_str .= ($pk_str != "" ? " OR " : "") . "($str)";
							}
						}
						$pk_str = "($pk_str)";
					} else {
						$pk_str = "$pk IN ($list_id)";
					}
					if ($e->ck_dependent == 1) {
						foreach ($e->field as $f) {
							if (!empty($f->table)) {
								$qry = "DELETE FROM $f->table WHERE $f->key_field IN ($list_id)";
								$qry_str .= "$qry;\n";
								if ($this->db == "MYSQL") nc_query($qry);
							}
						}
						$qry = "DELETE FROM $ent_table\n"
							. "WHERE\n\t"
							. (!empty($str_xtra_keys) ? "$str_xtra_keys\n\t" : "")
							. (!is_array($e->key_field) && !empty($key_field) ? "$key_field = $key_field_val AND\n\t" : "")
							. $pk_str;
						$qry_str .= "$qry;\n";
						if ($this->db == "MYSQL") nc_query($qry);
					} else {
						$qry = "UPDATE $ent_table\n"
							. "SET $key_field = NULL\n"
							. "WHERE\n\t"
							. $str_xtra_keys . "\n\t"
							. (!empty($key_field) && !is_array($pk) ? "$key_field = $key_field_val AND" : "") . "\n\t"
							. $pk_str;
						$qry_str .= "$qry;\n"; // 
						if ($this->db == "MYSQL") nc_query($qry);
					}
				}
				if ($this->ck_return_pk == 1 && $this->ck_return_entity == $ent_table) {
					if ($this->db == "MSSQL") {
						$qry = "DECLARE @str varchar(max)";
						$qry_str .= "$qry;\n";
					}
				}
				for ($j=0; $j<$this->vars["count_$prefix"]; $j++) {
					if (isset($e->update_qry)) {
						$qry_upd = $e->update_qry;
						$qry_upd = str_replace("[id]", $this->id, $qry_upd);
					}
					$qry_multiple = "";
					$str_field = [ $ent_table => $key_field ];
					$str_val = [ $ent_table => $key_field_val ];
					$str_upd = [ $ent_table => "" ];
					$str_upd_condition = [ $ent_table => "" ];
					$ck_upd = [];
					if (!is_array($e->key_field) && $key_field_val != "") {
						$str_upd_condition[$ent_table] = "$key_field = $key_field_val";
					}
					$id_entity = $this->vars["id_$prefix$j"];
					if (is_array($e->key_field)) {
						foreach ($e->key_field as $ix => $key) {
							$f = is_numeric($ix) ? $key : $ix;
							if ($obj = $this->get_field_def($f)) {
								if ($obj->type != "hidden") $str_upd[$ent_table] .= ($str_upd[$ent_table] != "" ? ",\n\t" : "") . "$key = " . $this->format_sql_str($this->vars[$f], $obj->type);
							}
						}
					}
					if (is_array($pk)) {
						if (!empty($id_entity)) {
							$vals = explode(";",$id_entity);
							foreach ($pk as $k => $f) {
								$val = $vals[$k];
								$parts = preg_split("/\:/",$val,2);
								if (count($parts) == 2) {
									$type = $parts[0];
									$val = $parts[1];
								} else if ($obj = $this->get_field_def($f, $e->field)) {
									$type = $obj->type;
								} else {
									$type = false;
								}
								$str_upd_condition[$ent_table] .= (!empty($str_upd_condition[$ent_table]) ? " AND\n\t" : "") . "$f = " . $this->format_sql_str($val, $type);
							}
						}
					} else {
						$str = "$pk = $id_entity";
						if (isset($e->entity_duplicate_fields)) $str = "($str OR $e->entity_duplicate_parent = $id_entity)\n\t";
						$str_upd_condition[$ent_table] .= (!empty($str_upd_condition[$ent_table]) ? " AND\n\t" : "") . $str;
					}
					for ($k=0; $k<count($e->field); $k++) {
						$ref = $e->field[$k];
						if (isset($ref->rec_table)) {
							$upd_table = $ref->rec_table["table"];
							if (!isset($str_field[$upd_table])) {
								$str_field[$upd_table] = "";
								$str_val[$upd_table] = "";
								$str_upd[$upd_table] = "";
								$str_upd_condition[$upd_table] = "";
								$key_field_table = $ref->rec_table["field"];
								if (!is_array($key_field_table)) $key_field_table = array($key_field_table);
								for ($l=0; $l<count($key_field_table); $l++) {
									$f = $key_field_table[$l];
									if ($f == $key_field)
										$val = $key_field_val;
									else if (isset($this->vars["{$prefix}_{$f}{$j}"])) // option still valid?
										$val = $this->vars["{$prefix}_{$f}{$j}"];
									else
										$val = $this->vars["id_{$prefix}{$j}"];
									$str_field[$upd_table] .= ($l>0?",":"") . "$f";
									$str_val[$upd_table] .= ($l>0?",":"") . $val;
									$str_upd_condition[$upd_table] .= (!empty($str_upd_condition[$upd_table]) ? " AND\n\t":"") . "$f = $val";
								}
							}
						} else {
							$upd_table = $ent_table;
						}
						if ($ref->ck_qry == 1) {
							$field = $ref->field;
							if ($ref->type == "file") {
								$ck_qry = 0;
								if ($_FILES[$prefix."_".$field.$j]["tmp_name"] != "") {
									$val = $this->add_file($ref, $prefix."_", $j);
									if ($this->db == "MSSQL") $val = str_replace("'","''",$val);
									if ($this->db == "MYSQL") $val = str_replace("'","\\'",$val);
									$val = "'$val'";
									$ck_qry = 1;
								} else if (isset($this->vars[$prefix."_remove".$field.$j])) {
									for ($l=0; $l<count($ref->file); $l++) {
										@unlink($ref->file[$l]["dir"]["O"] . "/" . $this->get_file_name($ref->file[$l]["file_name_mask"], $id_entity));
									}
									$val = "NULL";
									$ck_qry = 1;
								}
								if ($ck_qry == 1) {
									$str_field[$upd_table] .= ",\n\t$field";
									$str_val[$upd_table] .= ",\n\t$val";
									$str_upd[$upd_table] .= ($str_upd[$upd_table] != "" ? ",\n\t" : "") . "$field = $val";
								}
							} else {
								$ix = $j;
								if (isset($e->entity_duplicate_fields)) {
									$ck = 0;
									for ($l=0; $l<count($e->entity_duplicate_fields); $l++) {
										if ($ref->field == $e->entity_duplicate_fields[$l]) { $ck = 1; break; }
									}
									if ($ck == 0) {
										for ($l=$j; $l>=0; $l--) {
											if (!isset($this->vars[$prefix . "_ck_child" . $l])) break;
										}
										$ix = $l;
									}
								}
								if (isset($ref->default) && is_array($ref->default) && isset($ref->default["ref"]))
									$val = $this->get_val($ref->default["ref"]);
								else
									$val = $this->get_val($ref, "{$prefix}_", $ix);
								if ($e->ck_history == 1) {
									$val_bak = $this->get_val($ref, "{$prefix}_", "_bak{$ix}");
									if ($val != $val_bak) $ck_new_entry[$upd_table][$ix] = 1;
								}
								if ($ref->type == "daterange") {
									$str_field[$upd_table] .= ",\n\t{$field}_ini,\n{$field}_fim";
									$str_val[$upd_table] .= ",\n\t" . $val["ini"] . ",\n" . $val["fim"];
									$str_upd[$upd_table] .= ($str_upd[$upd_table] != "" ? ",\n\t" : "") . "{$field}_ini = " . $val["ini"] . " AND {$field}_fim = " . $val["fim"];
								} else if ($ref->type == "dropdown" && array_key_exists("multiple",$ref->prop)) {
									if ($this->db == "MSSQL" && $this->modo == "insert") {
										$qry_multiple .= "SELECT @id_entity = SCOPE_IDENTITY();\n";
									} else if ($this->modo == "update") {
										$qry_multiple .= "SELECT @id_entity = $id_entity;\n";
										$qry_multiple .= "DELETE FROM " . $ref->rec_table["table"] . " WHERE " . $ref->rec_table["field"] . " = @id_entity;\n";
									} 
									for ($l=0; $l<count($val); $l++) {
										$qry_multiple .= "INSERT INTO " . $ref->rec_table["table"] . " (" . $ref->rec_table["field"] . ", $field) VALUES (@id_entity, '" . $val[$l] . "');\n";
									}
								} else if ($ref->type == "fieldlist") {
									$_str_field = $ref->rec_table["field"];
									if (!empty($id_entity)) {
										$_str_val = "'$id_entity'";
										$_str_keys = $ref->rec_table["field"] . " = '$id_entity'";
										$ck_exists = 1;
									} else {
										$_str_val = "@id_entity";
										$_str_keys = $ref->rec_table["field"] . " = @id_entity";
										$ck_exists = 0;
									}
									if (isset($ref->rec_table["xtra_key"])) {
										foreach (array_keys($ref->rec_table["xtra_key"]) as $_ix => $_val) {
											$_str_keys .= "$_ix = '$_val' AND\n";
											$_str_field .= "$_ix,";
											$_str_val .= "'$_val',";
										}
									}
									if ($ck_exists == 1) $qry_multiple .= "DELETE FROM " . $ref->rec_table["table"] . " WHERE $_str_keys;\n";
									foreach (explode(";",$val) as $_ix => $_val) {
										if (trim($_val) != "") $qry_multiple .= "INSERT INTO " . $ref->rec_table["table"] . " ($_str_field, $field) VALUES ($_str_val, '$_val');\n";
									}
								} else if ($ref->type != "display") {
									if (is_array($field)) {
										$keys = array_keys($field);
										$vals = explode(";",$val);
										for ($l=0; $l<count($keys); $l++) {
											if ($this->db == "MSSQL") $vals[$l] = str_replace("'","''",$vals[$l]);
											if ($this->db == "MYSQL") $vals[$l] = str_replace("'","\\'",$vals[$l]);
											$str_field[$upd_table] .= ",\n\t" . $field[$keys[$l]];
											$str_val[$upd_table] .= ",\n\t'" . $vals[$l] . "'";
											$str_upd[$upd_table] .= ($str_upd[$upd_table] != "" ? ",\n\t" : "") . $field[$keys[$l]] . " = '" . $vals[$l] . "'";
										}
									} else {
										$str_field[$upd_table] .= ($str_field[$upd_table] != "" ? ",\n\t" : "") . $field;
										$str_val[$upd_table] .= ($str_val[$upd_table] != "" ? ",\n\t" : "") . $val;
										if (!$ref->ck_readonly || is_array($ref->ck_readonly)) {
											$str_upd[$upd_table] .= ($str_upd[$upd_table] != "" ? ",\n\t" : "") . "$field = $val";
											$ck_upd[$field] = trim($val,"'");
										}
									}
								}
							}
							if (isset($e->update_qry)) {
								//$qry_upd = str_replace("[id]", $this->id, $qry_upd);
								$qry_upd = str_replace("[$field]", $val, $qry_upd);
							}
						} // if ($ref["ck_qry"] == 1) {
					} // for
					if (isset($e->update_qry)) {
						if ($this->debug == 1) $this->show_debug($qry_upd,"?");
						nc_query($qry_upd);
					} else {
						if (isset($e->entity_duplicate_fields) &&
							isset($this->vars[$prefix . "_ck_child" . $j])) {
							for ($l=$j; $l>=0; $l--) {
								if (!isset($this->vars[$prefix . "_ck_child" . $l])) break;
							}
							$str_field[$upd_table] .= ",\n\t" . $e->entity_duplicate_parent;
							if ($this->vars["id_" . $prefix . $l] != "0")
								$str_val[$upd_table] .= ",\n\t" . $this->vars["id_" . $prefix . $l];
							else
								$str_val[$upd_table] .= ",\n\t@id_entity";
						}
						if (isset($e->xtra_key)) {
							foreach ($e->xtra_key as $_ix => $_val) {
								if ($this->db == "MSSQL") $_val = str_replace("'","''",$_val);
								if ($this->db == "MYSQL") $_val = str_replace("'","\\'",$_val);
								$str_field[$ent_table] .= ($str_field[$ent_table] != "" ? ",\n\t" : "") . $_ix;
								$str_val[$ent_table] .= ($str_val[$ent_table] != "" ? ",\n\t" : "") . "'$_val'";
								$str_upd[$ent_table] .= ($str_upd[$ent_table] != "" ? ",\n\t" : "") . "$_ix = '$_val'";
							}
						}
						if ($e->ck_history == 1) {
							$str_field[$ent_table] .= ",\n\t" . $e->history_dt_field;
							$str_val[$ent_table] .= ",\n\tGETDATE()";
						}
						if ($e->ck_user == 1) {
							$str_field[$ent_table] .= ",\n\t" . $e->user_field;
							$str_val[$ent_table] .= ",\n\t" . $e->user_val;
						}
						if ($this->modo == "update" && !empty($e->ck_upd) && !empty($this->vars["src_$prefix$j"])) {
							$src = array_map("utf8_decode", (array)json_decode(base64_decode($this->vars["src_$prefix$j"])));
							$ck = 0;
							foreach ($ck_upd as $ix => $val) {
								if ($val != $src[$ix]) $ck = 1;
							}
							if ($ck == 0) $str_upd = [];
						}
						foreach ($str_field as $tbl => $str) {
							if (!empty($str_upd[$tbl])) {
								if ($e->ck_dependent == 0) {
									$upd_str = "$key_field = $key_field_val";
									foreach ($e->field as $f) {
										if ($f->field == $e->pk) 
											$pk_val = $this->get_val($f, "{$prefix}_", $j);
										else if ($f->ck_qry == 1) 
											$upd_str .= ", $f->field = " . $this->get_val($f, "{$prefix}_", $j);
									}
									foreach ($e->upd_field as $f => $val) {
										$upd_str .= ", $f = $val";
									}
									$qry = "UPDATE $tbl\n"
										. "SET $upd_str\n"
										. "WHERE 
											$e->pk = $pk_val";
									$qry_str .= "$qry;\n";
								} else {
									$qry_ins = "INSERT INTO $tbl ($str)\n"
										. "VALUES (" . $str_val[$tbl] . ")";
									$qry_upd = "UPDATE $tbl\n"
										. "SET\n\t"
										. $str_upd[$tbl] . "\n"
										. "WHERE\n\t"
										. $str_upd_condition[$tbl];
									if ($tbl == $ent_table) {
										if (empty($id_entity) || (!empty($e->ck_history) && isset($ck_new_entry[$tbl][$ix]))) {
											$qry_str .= "$qry_ins;\n";
											if ($this->db == "MYSQL") nc_query($qry_ins);
										} else if (empty($e->ck_history)) {
											$qry_str .= "$qry_upd;\n";
											if ($this->db == "MYSQL") nc_query($qry_upd);
										}
									} else {
										if ($this->db == "MSSQL") {
											$qry_str .= "$qry_upd;\n";
											$qry_str .= "IF (@@ROWCOUNT = 0)\n";
											$qry_str .= "$qry_ins;\n";
										} else if ($this->db == "MYSQL") {
											$connect = new connect;
											$connect->query($qry_upd);
											$qry_str .= "$qry_upd;\n";
											if ($connect->affected_rows == 0) {
												$connect->query($qry_ins);
												$qry_str .= "$qry_ins;\n";
											}
											$connect->close();
										}
									}
								}
							}
						}
						if ($qry_multiple != "") {
							if ($this->db == "MSSQL") {
								$qry_str .= "$qry_multiple";
							} else if ($this->db == "MYSQL") {
								$qry = "SELECT MAX(id) AS id FROM $ent_table";
								$res_max = nc_query($qry);
								foreach (explode(";", $qry_multiple) as $qry) { 
									$qry = str_replace("@id_entity", $res_max[0]["id"], $qry);
									if (trim($qry) != "") nc_query($qry);
									$qry_str .= "$qry;\n";
								}
							}
						}
						if (isset($e->entity_duplicate_fields) &&
							!isset($this->vars[$prefix . "_ck_child" . $j])) {
							$qry_str .= "SELECT @id_entity = SCOPE_IDENTITY();\n";
						}
						if ($this->db == "MSSQL" && $this->ck_return_pk == 1 && $this->ck_return_entity == $ent_table) {
							$qry = "IF @str IS NULL
									SET @str = CONVERT(varchar, SCOPE_IDENTITY())
								ELSE
									SET @str = @str + ',' + CONVERT(varchar, SCOPE_IDENTITY())";
							$qry_str .= "$qry\n";
						}
						//echo "<pre>$qry</pre>";
						//nc_query($qry);
					}
				}
			}
		}
		for ($i=0; $i<count($this->ent_NXN); $i++) {
			// get ref position
			$p = $this->ent_NXN[$i];
			$obj = $this->ref[$p]["object"];
			// check tab entity
			$tab_id = $this->ref[$p]["tab_id"];
			if (count($this->tab_ref) > 0 && $this->tab_ref[$tab_id]["table"] != null && $this->modo == "update")
				$pk = $this->vars["id_" . $this->tab_ref[$tab_id]["table"]];
			else if (count($this->tab_ref) > 0 && $this->tab_ref[$tab_id]["table"] != null && isset($this->id_table[$this->tab_ref[$tab_id]["table"]]) && $this->modo == "insert")
				$pk = $this->id_table[$this->tab_ref[$tab_id]["table"]];
			else
				$pk = $this->id;
			// get table definition
			$ent_table = $obj->table;
			$prefix = $obj->prefix;
			$key_field = $obj->key_field;
			if (is_array($key_field)) {
				$key_field = $key_field[0];
				$key_field_val = $key_field[1];
			} else {
				$key_field_val = $pk;
			}
			$entity_field = $obj->field;
			$list_id = "";
			for ($j=0; $j<$this->vars["count_".$prefix]; $j++) {
				if (!isset($this->vars[$prefix.$j]) && $this->vars[$prefix."_bak".$j] == 1) {
					$_field = $entity_field[0]->field;
					if (!is_array($_field)) $_field = array($_field);
					$_label = "";
					foreach ($_field as $_ix) {
						$_label .= ($_label != "" ? "_" : "") . $_ix;
					}
					$list_id .= ($list_id != "" ? "," : "") . "'" . $this->vars["{$prefix}_{$_label}{$j}"] . "'";
				}
			}
			if ($list_id != "" && $key_field_val != "") {
				$_field = $entity_field[0]->field;
				if (!is_array($_field)) $_field = array($_field);
				$_label = "";
				foreach ($_field as $_ix) {
					if ($this->db == "MSSQL")
						$_label .= ($_label != "" ? "+','+" : "") . "CONVERT(varchar,$_ix)";
					else if ($this->db == "MYSQL")
						$_label .= ($_label != "" ? "+','+" : "") . "$_ix";
				}
				$str_field = "$key_field = $key_field_val AND";
				foreach ($obj->defaults as $_f => $_val) {
					$str_field .= " $_f = '$_val' AND";
				}
				if ($obj->ck_dependent == 1) {
					$qry = "DELETE FROM $ent_table
						WHERE
							$str_field
							$_label IN ($list_id)";
				} else {
					$qry = "UPDATE $ent_table
						SET $key_field = NULL
						WHERE
							$key_field = '$key_field_val' AND
							$_label IN ($list_id)";
				}
				$qry_str .= "$qry;\n";
				if ($this->db == "MYSQL") nc_query($qry);
			}
			for ($j=0; $j<$this->vars["count_".$prefix]; $j++) {
				$field_list = "";
				$var_list = "";
				if (isset($this->vars[$prefix.$j]) && 
					($this->vars[$prefix."_bak".$j] == 0 || count($entity_field) > 1)) {
					if ($obj->ck_dependent == 1 && $this->vars[$prefix."_bak".$j] == 0) {
						for ($k=0; $k<count($entity_field); $k++) {
							$_field = $entity_field[$k]->field;
							if (!is_array($_field)) $_field = array($_field);
							$_label = "";
							foreach ($_field as $_ix) {
								$_label .= ($_label != "" ? "_" : "") . $_ix;
								$field_list .= ($field_list != "" ? ", " : "") . $_ix;
							}
							foreach (explode(",", $this->vars["{$prefix}_{$_label}{$j}"]) as $_val) {
								$var_list .= ($var_list != "" ? ", " : "") . ($_val != "" ? "'$_val'" : "NULL");
							}
							foreach ($obj->defaults as $_f => $_val) {
								$field_list .= ", $_f";
								$var_list .= ", '$_val'";
							}
						}
						$qry = "INSERT INTO $ent_table ($key_field, $field_list) VALUES ($pk, $var_list)";
					} else if ($obj->ck_dependent == 1) {
						for ($k=1; $k<count($entity_field); $k++) {
							$_field = $entity_field[$k]->field;
							if ($this->vars[$prefix."_".$_field.$j] == "")
								$field_list .= ($field_list != "" ?", ":"") . $_field . " = NULL";
							else
								$field_list .= ($field_list != "" ?", ":"") . $_field . " = '" . $this->vars[$prefix."_".$_field.$j] . "'";
						}
						$qry = "UPDATE $ent_table
							SET $field_list
							WHERE
								" . $entity_field[0]->field . " = '" . $this->vars[$prefix."_".$entity_field[0]->field.$j] . "'" . " AND
								$key_field = $pk";
					} else if ($obj->ck_dependent == 0) {
						for ($k=0; $k<count($entity_field); $k++) {
							$_field = $entity_field[$k]->field;
							$field_list .= ($k>0?" AND ":"") . $_field . " = '" . $this->vars[$prefix."_".$_field.$j] . "'";
						}
						$qry = "UPDATE $ent_table
							SET $key_field = '$pk'
							WHERE
								$field_list";
					}
					$qry_str .= "$qry;\n";
					if ($this->db == "MYSQL") nc_query($qry);
				}
			}
		}
		if ($this->debug == 1) $this->show_debug($qry_str,"?");
		if ($qry_str != "") {
			if ($this->db == "MSSQL" && $this->ck_return_pk == 1 && $this->ck_return_entity != "default") {
				$qry = "SELECT @str AS val";
				$qry_str .= "$qry;\n";
				$res = nc_query($qry_str,"QUERY");
				$this->return_pk .= ($this->return_pk != "" ? "," : "") . $res[0]["val"];
			} else if ($this->db == "MSSQL") 
				nc_query($qry_str,"NONQUERY");
		}
	}
	public function add_notification($from_mail, $to_mail, $subject, $msg, $qry = null) {
		$this->ck_notification = 1;
		$this->notification_ref = array(
			"from_mail" => $from_mail,
			"to_mail" => $to_mail,
			"subject" => $subject,
			"msg" => $msg,
			"qry" => $qry
		);
	}
	private function build_confirm() {
		if ($this->ck_notification == 1) {
			$from_mail = $this->notification_ref["from_mail"];
			$to_mail = $this->notification_ref["to_mail"];
			$subject = str_replace("[id]", $this->id, $this->notification_ref["subject"]);
			$msg = str_replace("[id]", $this->id, $this->notification_ref["msg"]);
			if ($this->notification_ref["qry"] != null) {
				$qry = str_replace("[id]", $this->id, $this->notification_ref["qry"]);
				$res = nc_query($qry);
				$keys = array_keys($res[0]);
				for ($i=0; $i<count($keys); $i++) {
					$from_mail = str_replace("[".$keys[$i]."]", $res[0][$keys[$i]], $from_mail);
					$to_mail = str_replace("[".$keys[$i]."]", $res[0][$keys[$i]], $to_mail);
					$subject = str_replace("[".$keys[$i]."]", $res[0][$keys[$i]], $subject);
					$msg = str_replace("[".$keys[$i]."]", $res[0][$keys[$i]], $msg);
				}
			}
			//echo "<pre>$to_mail</pre><pre>$subject</pre><pre>$msg</pre>";
			$header = "From: $from_mail\r\n";
			mail($to_mail, $subject, $msg, $header);
		}
		if ($this->modo == "insert") $title = $this->lang_confirm_insert;
		if ($this->modo == "update" || $this->modo == "report") $title = $this->lang_confirm_update;
		if ($this->modo == "updatedelete" || $this->modo == "complete") $title = $this->lang_confirm_process;
		if ($this->modo == "delete") $title = $this->lang_confirm_delete;
		if ($title instanceof Closure) $title = $title($this);
		echo "<!DOCTYPE html>\n";
		echo "<HTML>\n";
		echo "<HEAD>\n";
		echo "<TITLE>" . strip_tags($title) . "</TITLE>\n";
		echo "<link rel='STYLESHEET' type='text/css' href='" . $this->css_path . "'>\n";
		for ($i=0; $i<count($this->css_ref); $i++) {
			echo "<link rel='STYLESHEET' type='text/css' href='" . $this->css_ref[$i] . "'>\n";
		}
		echo "</HEAD>\n";
		echo "<body bgcolor=White>\n";
		echo "<table border=0 id=\"tbl-cls_form\" width=\"$this->form_width\" cellspacing=\"$this->cellspacing\" cellpadding=\"$this->cellpadding\" class=\"$this->css_table\">\n";
		if ($this->ck_redirect == 0) $title .= "<a href=\"?modo=report&step=2&id=$this->id\" target=\"_blank\" style=\"float:right;\"><img src=\"$this->img_dir/$this->img_print\"></a>";
		if (!empty($this->confirm_msg)) {
			if ($this->confirm_msg instanceof Closure) $this->confirm_msg = ($this->confirm_msg)();
			$title .= "<br><br><div class=\"$this->css_text\" " . ($this->confirm_align ? "style=\"text-align:$this->confirm_align\"" : "") . ">$this->confirm_msg</div>\n";
		}
		echo "<tr><td class=\"$this->css_confirm\">$title</td></tr>\n";
		echo "<tr class=\"$this->css_list_separator\"><td></td></tr>\n";
		$ck_link_new_entry = 0;
		for ($i=0; $i<count($this->ck_new_ref); $i++) {
			if ($this->ck_new_ref[$i]["url"] != null) $ck_link_new_entry = 1;
		}
		if ($ck_link_new_entry == 1) {
			echo "<tr><td class={$this->css_text_entity}>$this->lang_new_entity_complete:</td></tr>\n";
			for ($i=0; $i<count($this->ck_new_ref); $i++) {
				echo "<tr><td class=\"$this->css_text\">$this->list_bullet <a class=\"$this->css_link_general\" target=\"_blank\" href=\"" . $this->ck_new_ref[$i]["url"] . "\">" . strtoupper($this->ck_new_ref[$i]["dir"]) . ": " . $this->ck_new_ref[$i]["nome"] . "</a></td></tr>\n";
			}
		}
		echo "</table>\n";
		echo "</BODY>\n";
		echo "</HTML>\n";
		$temp_str = "";
		$modo = ($this->modo == "complete" || $this->modo == "updatedelete") && isset($this->vars["modo"]) ? $this->vars["modo"] : $this->modo;
		if (!empty($this->redirect_str) && ($modo == "insert" || $modo == "update" || strpos($this->redirect_str, "[id]") === false)) {
			$url = $this->redirect_str;
			if (isset($this->id)) $url = str_replace("[id]", $this->id, $url);
			preg_match_all("[\[([a-zA-Z0-9_])+\]]", $url, $match);
			for ($k=0; $k<count($match[0]); $k++) {
				$url = str_replace($match[0][$k], $this->vars[substr($match[0][$k],1,-1)], $url);
			}
		} else {
			$url = "?modo=$this->modo";
			if (($this->modo == "report" || $this->modo == "update" || $this->modo == "delete") && count($this->ref_filter) > 0) $url .= "&step=0";
			if (!empty($_POST["QUERY_STRING"]) && !is_array($this->pk)) {
				$params = preg_replace("/($this->pk|modo|ck_return_pk)=([0-9A-Za-z]+)(\&)*/","",$_POST["QUERY_STRING"]);
				if (!empty($params)) $url .= "&$params";
			}
			foreach ($_GET as $ix => $val) {
				if (!is_array($val) && !in_array($ix, ["modo","step",$this->pk,"ck_return_pk","urlKey"])) $url .= "&$ix=$val";
			}
		}
		if ($this->ck_return_pk == 1 &&
			(($this->modo == "insert" && strpos($this->ck_return_use,"I") !== false) ||
			 ($this->modo == "update" && strpos($this->ck_return_use,"U") !== false)))
			$url .= "&ck_return_pk=" . urlencode($this->return_pk);
		if ($this->debug == 1) 
			echo("<a href=\"$url\">$url</a>");
		else if ($this->ck_redirect == 1) 
			echo "<META HTTP-EQUIV=\"Refresh\" content=\"1;url=$url\">\n";
	}
	// global methods
	public function get_field_label($field) { // public method used on cls_report
		return $this->getFieldLbl($field);
	}
	public function getFieldLbl($field) { // public method used on cls_report
		if (is_array($field)) {
			$str = "";
			foreach ($field as $f) {
				$str .= (strlen($str) != "" ? "_" : "") . $this->getFieldLbl($f);
			}
			return $str;
		} else if (is_null($field) || $field instanceof Closure) {
			return "";
		} else if (strrpos($field, "AS ") > 1) {
			return trim(substr($field, strrpos($field, "AS ")+3));
		} else if (strpos($field, ".") > 1 && 
			(substr($field, strpos($field, ".")-3, 3) != "dbo" || $this->db != "MSSQL")) {
			return trim(substr($field, strpos($field, ".")+1));
		} else {
			return trim($field);
		}
	}
	public function get_field_index($field) { // public method used on cls_report
		return $this->getFieldIx($field);
	}
	public function getFieldIx($field) { // public method used on cls_report
		if ($field === null || $field instanceof Closure)
			return false;
		else if (is_array($field))
			return $field;
		else if (strrpos($field, "AS ") > 1)
			return trim(substr($field, 0, strrpos($field, "AS ")));
		//else if (strpos($field, ".") > 1)
		//	return substr($field, strpos($field, ".")+1);
		else
			return trim($field);
	}
	private function parse_label($r,$ix_label) {
		if ($ix_label instanceof Closure) {
			return $ix_label($r);
		} else {
			preg_match_all("/\[([a-zA-Z0-9_])+\]/i", $ix_label, $match);
			if (count($match[0]) > 0) {
				$str = $ix_label;
				foreach ($match[0] as $ix) $str = str_replace($ix,$r[substr($ix,1,-1)],$str);
			} else {
				$str = $r[$ix_label];
			}
			return $str;
		}
	}
	private function format_sql_str($val, $type=false) {
		if (is_array($val)) {
			$ret = [];
			foreach ($val as $str) {
				$ret[] = $this->parse_sql_str($str, $type);
			}
			return $ret;
		} else {
			return $this->parse_sql_str($val, $type);
		}
	}
	private function parse_sql_str($str, $type=false) {
		if ($this->db == "MSSQL")
			$str = str_replace("'","''",str_replace("\'","'",$str));
		else if ($this->db == "MYSQL" && strpos($str, "\'") === false)
			$str = str_replace("'", "\'", $str);
		if (($type == "date" || $type == "datetime-local") && $this->db == "MSSQL")
			return "CONVERT(datetime, '$str', 120)";
		else
			return "'$str'";
	}
	private function ck_entity_field($ref, $field) {
		foreach ($ref as $ck) {
			if ($ck->field == $field) {
				return true;
				break;
			}
		}
		return false;
	}
	private function ck_hidden($ref, $i, $res=false) {
		$ck = isset($ref[$i]["object"]->ck_hidden) ? $ref[$i]["object"]->ck_hidden : 0;
		if (is_array($ck)) { // single rule
			return $this->ck_condition($ref, $ck, $res);
		} else {
			return $ck;
		}
	}
	private function ck_disabled($ref, $i, $res=false) {
		$ck = isset($ref[$i]["object"]->ck_disabled) ? $ref[$i]["object"]->ck_disabled : 0;
		if (is_array($ck)) { // single rule
			return $this->ck_condition($ref, $ck, $res);
		} else {
			return $ck;
		}
	}
	private function ck_condition($ref, $rules, $res = false) {
		if (!$res && $this->modo == "update" && $this->step == 2) $res = $this->res_upd;
		if (isset($rules["field"])) $rules = [$rules];
		// if (!empty($_SESSION["verpro_debug"])) echo "<pre>"; print_r($rules); echo "</pre>"; 
		// if (!empty($_SESSION["verpro_debug"])) echo "<pre>" . print_r($res,1) . "</pre>";
		foreach ($rules as $rule) {
			$op = isset($rule["op"]) ? $rule["op"] : $rule["condition"];
			$op_val = $rule["val"];
			$bool = $rule["bool"];
			foreach ($rule["src"] as $src_field) {
				// if (!empty($_SESSION["verpro_debug"])) echo "$src_field $op " . json_encode($op_val) . "<br>\n";
				// if (!empty($_SESSION["verpro_debug"])) echo "<pre>" . json_encode($rule) . "</pre>";
				if ($this->builder == "form") {
					foreach ($ref as $r) {
						$obj = gettype($r) == "array" ? $r["object"] : $r;
						// if (!empty($_SESSION["verpro_debug"])) echo "$obj->field ($obj->type)<br>\n";
						if ($obj->type != "entity" && $this->getFieldLbl($obj->field) == $src_field) {
							$field = $this->getFieldLbl($obj->field);
							if ($res && isset($obj->field_qry) && isset($res[$obj->field_qry]))
								$field_val = $res[$obj->field_qry];
							else if ($res && isset($res[$field]))
								$field_val = $res[$field];
							else if (!$res && isset($this->res_upd))
								$field_val = $this->res_upd[$this->getFieldLbl($obj->field)];
							else if (isset($obj->default) && $obj->type == "fieldfilter")
								$field_val = $obj->default[0];
							else if (isset($obj->default))
								$field_val = $obj->default;
							else if ($obj->type == "checkbox")
								$field_val = 0;
							else 
								$field_val = "";
							if (gettype($op) == "object") { // not implemented, javascript interface not resolved
								return $op();
							} else {
								if ($op_val instanceof Closure) {
									$_val = [];
									foreach (nc_query($obj->qry["sql"]) as $_r) {
										if ($op_val($_r)) $_val[] = $_r[$obj->qry["ix"]];
									}
									$op_val = $_val;
								}			
								if ($op == "==") {
									$ret = (int)in_array($field_val, is_array($op_val) ? $op_val : [$op_val]);
								} else if ($op == "!=") {
									$ret = (int)!in_array($field_val, is_array($op_val) ? $op_val : [$op_val]);
								} else if (($op == ">=" && $field_val >= $op_val) ||
									($op == "<=" && $field_val <= $op_val) ||
									($op == ">" && $field_val > $op_val) ||
									($op == "<" && $field_val < $op_val)) {
									$ret = 1;
								} else if ($bool == "&&") {
									$ret = 0;
								}
								// if (!empty($_SESSION["verpro_debug"])) echo "$obj->field [$field_val] $op " . (is_array($op_val) ? json_encode(array_map("utf8_encode",$op_val)) : "'$op_val'") . " $bool => $ret<br>\n";
								if ($bool == "||" && $ret == 1) return $ret;
								if ($bool == "&&" && $ret == 0) return $ret;
							}
						}
					}
				} else if ($this->builder == "list") {
					foreach ($res as $ix => $field_val) {
						if ($ix == $src_field) {
							if (gettype($op) == "object") { // not implemented, javascript interface not resolved
								return $op();
							} else {
								// if (!empty($_SESSION["verpro_debug"])) echo "$src_field: $field_val $op " . json_encode($op_val) . "<br>\n";
								if ($op == "==") {
									$ret = (int)in_array($field_val, is_array($op_val) ? $op_val : [$op_val]);
								} else if ($op == "!=") {
									$ret = (int)!in_array($field_val, is_array($op_val) ? $op_val : [$op_val]);
								} else if (($op == ">=" && $field_val >= $op_val) ||
									($op == "<=" && $field_val <= $op_val) ||
									($op == ">" && $field_val > $op_val) ||
									($op == "<" && $field_val < $op_val)) {
									$ret = 1;
								} else {
									$ret = 0;
								}
								if ($ret == 1 && $bool == "||") return $ret;
								if ($ret == 0 && $bool == "&&") return $ret;
							}
						}
					}
				}
			}
			// if ($op == "!=" && is_array($op_val) && count($op_val) > 1) return 1; // disabled 09/10/2023
		}
		if ($bool == "||") return 0;
		if ($bool == "&&") return 1;
	}
}

class cls_entity {
	public function __construct($parent, $label, $table, $key_field, $rel, $p) {
		$this->parent = $parent; 
		$this->label = $label; 
		$this->table = $table; 
		$this->key_field = $key_field; 
		$this->rel = $rel; 
		$this->pos = $p; 
		$this->type = "entity"; 
		$this->ck_req = 0;
		$this->ck_hidden = 0;
		$this->ck_readonly = 0;
		$this->ck_multiple_entry = 0;
		$this->ck_edicao_global = 0;
		$this->ck_collapse = 0;
		$this->ck_separator = 0;
		$this->ck_dependent = 1;
		$this->ck_history = 0;
		$this->ck_user = 0;
		$this->layout = "H";
		$this->key_condition = "";
		$this->path = null;
		$this->cols = 1;
		$this->tab_id = count($this->parent->tab_ref)-1; 
		$this->tab_ref = $this->parent->tab_ref;
		if ($this->tab_id >= 0 && $this->tab_ref[$this->tab_id]["label"] == false) $this->tab_id = -1;
		$this->prefix = ($this->tab_id >= 0 ? $this->tab_ref[$this->tab_id]["table"] . "_" : "") . $p . $table;
		$this->req_dependency = $this->tab_id >= 0 ? $this->tab_ref[$this->tab_id]["req_dependency"] : [];
		$this->col_group = false;
		$this->field_group = false;
		$this->field = []; 
		$this->defaults = [];
		$this->prop = []; 
		$this->related = []; 
		$this->upd_field = [];
		$this->dependency = [];
		$this->set_pk("id");
	}
	public function set_pk($pk, $label=false, $type=false) {
		if (is_array($pk)) {
			$this->pk = $pk;
		} else {
			$this->pk = $this->parent->getFieldIx($pk);
			$this->pk_label = $this->parent->getFieldLbl($pk);
		}
		if ($type) { 
			if ($this->rel == "NXN") exit("invalid parameters label, type for method set_pk() on NXN entity");
			$this->ck_dependent = 0; // Entidade independente, o registro é vinculado/desvinculado conforme selecionado no formulario
			return $this->add_field($pk, $label, $type, 1, 0);
		}
	}
	public function set_dependent($ck) {
		$this->ck_dependent = $ck;
	}
	public function set_prefix($str) {
		if (is_numeric(substr($str,-1))) exit("Prefixo $str inválido, última posição não deve ser numérica");
		$this->prefix = $str; 
	}
	public function set_required() {
		$params = $this->get_condition_params(func_get_args());
		if ($params["field"] == null)
			$this->ck_req = 1;
		else
			$this->set_options(["ck_req" => $params]);
		$this->search_next_args("set_required", func_get_args());
	}
	public function set_hidden() {
		$params = $this->get_condition_params(func_get_args());
		if ($params["field"] == null)
			$this->ck_hidden = 1;
		else
			$this->set_options(["ck_hidden" => $params]);
		$this->search_next_args("set_hidden", func_get_args());
	}
	private function get_condition_params($args) {
		if (!empty($args) && gettype($args[0]) == "array") {
			if (!empty($args[1])) $src = gettype($args[1]) == "array" ? $args[1] : [$args[1]];
			$args = $args[0];
		}
		$field = isset($args[0]) ? $args[0] : null;
		$op =    isset($args[1]) ? $args[1] : "==";
		$val =   isset($args[2]) ? $args[2] : "";
		$bool =  isset($args[3]) ? str_replace(["AND","OR"], ["&&","||"], $args[3]) : "&&";
		if (!isset($src)) $src = [ $this->parent->getFieldLbl($field) ];
		return [ "field" => $field, "op" => $op, "val" => $val, "bool" => $bool, "src" => $src ];
	}
	private function search_next_args($method, $args) {
		if (!empty($args) && gettype($args[0]) == "array") {
			if (!empty($args[1])) $src = gettype($args[1]) == "array" ? $args[1] : [$args[1]];
			$args = $args[0];
		} else {
			$src = false;
		}
		if (count($args) > 4) {
			$remove = array_splice($args,0,4); // remove first 4 elements
			array_splice($args,3,0,$remove[3]); // add arg(3) $bool to the end of the array
			// call_user_func_array([ $this, $method ], $args);
			$this->$method($args, $src);
		}
	}
	public function set_options($opt) {
		if (isset($opt["ck_req"])) {
			if (is_array($this->ck_req))
				$this->ck_req = [$this->ck_req, $opt["ck_req"]];
			else
				$this->ck_req = $opt["ck_req"];
		}
		if (isset($opt["ck_hidden"])) {
			if (is_array($this->ck_hidden))
				$this->ck_hidden = [$this->ck_hidden, $opt["ck_hidden"]];
			else
				$this->ck_hidden = $opt["ck_hidden"];
		}
	}
	public function set_readonly() {
		$this->ck_readonly = 1;
		foreach ($this->field as $f) $f->set_readonly();
	}
	public function add_dependency($table, $field) {
		$this->dependency[] = [
			"table" => $table,
			"field" => $field
		];
	}
	public function protect($cmd) {
		$this->protectCmd = $cmd;
	}
	public function hide_empty() {
		$this->ck_hide_empty = true;
	}
	/* Depracated 19/09/2023
	public function set_history($dt_field, $key_field) {
		$this->ck_history = 1;
		$this->history_dt_field = $dt_field;
		$this->history_key_field = $key_field;
	}
	*/
	public function set_user($field, $val) {
		$this->ck_user = 1;
		$this->user_field = $field;
		$this->user_val = $val;
	}
	public function set_multiple_entry() {
		$this->ck_multiple_entry = 1; 
		$this->parent->ck_multiple_entry = 1; 
	}
	public function set_group($group, $ck_split_selected=1) {
		$this->group = $group;
		$this->ck_split_selected = $ck_split_selected;
	}
	public function set_restriction($val) {
		$this->restriction = $val;
	}
	public function set_cols($cols) {
		$this->cols = $cols;
	}
	public function set_collapse($ck=1) {
		$this->ck_collapse = $ck;
	}
	public function set_layout($layout) {
		// V = Vertical, H = Horizontal
		$this->layout = $layout;
	}
	/* ck_update() 
	Cria campo src_{entity} com a referencia dos valores selecionados na query
	Executa UPDATE apenas se os valores informados no formulario sao diferentes do encontrado no campo src_{entity}
	*/
	public function ck_update() { 
		$this->ck_upd = 1;
	}
	public function add_condition($sql) {
		$this->key_condition .= ($this->key_condition != "" ? " AND\n\t\t" : "") . "($sql)"; 
	}
	public function add_related($sql) {
		$this->related[] = $sql;
	}
	public function set_html($path) {
		$this->path = $path; 
	}
	public function add_prop($prop, $value=null) {
		echo "<div class=alert>cls_entity-&gt;add_prop() depracated, use add_action()</div>"; // 30/04/2019
	}
	public function add_action($cmd) {
		$this->cmd[] = $cmd;
	}
	public function add_global_form($ref=[]) {
		if (!is_array($ref)) $ref = array($ref);
		$this->ck_edicao_global = 1; 
		$this->edicao_global_ref = $ref; 
		$this->parent->ck_global_form = 1; 
	}
	public function add_update_field($field, $val) {
		$this->upd_field[$field] = $val;
	}
	public function set_field($field) {
		if ($this->rel == "1XN") exit("invalid method set_field($field) => use add_field() method instead");
		if (is_array($field)) {
			$str_field = "";
			foreach ($field as $f) $str_field .= ($str_field != "" ? "_" : "") . $f;
		} else {
			$str_field = $field;
		}
		$c = count($this->field);
		return $this->field[$c] = new cls_field("entity_field", $this->parent, $field, null, null);
	}
	public function prevent_delete($field, $op, $val) {
		if (!isset($this->prevent_del)) $this->prevent_del = [];
		$this->prevent_del[] = [
			"field" => $field,
			"op" => $op,
			"val" => $val
		];
	}
	public function prevent_edit($field, $op, $val) {
		if (!isset($this->prevent_edit)) $this->prevent_edit = [];
		$this->prevent_edit[] = [
			"field" => $field,
			"op" => $op,
			"val" => $val
		];
	}
	public function add_field($field, $label=null, $type=null, $ck_req=0, $ck_qry=1) {
		if ($this->rel == "NXN") exit("invalid method add_field($field) => use set_field() method instead");
		if ($type == "filteredtext") $type = "fieldfilter";
		$f = new cls_field("entity_field", $this->parent, $field, $label, $type, $ck_req, $ck_qry, count($this->field), $this);
		if ($this->ck_readonly == 1) $f->set_readonly();
		if ($this->parent->use_uppercase && ($type == "text" || $type == "textarea")) {
			$f->add_prop("style","text-transform:uppercase;");
			$f->add_prop("onblur","this.value=this.value.toUpperCase();");
		}
		if ($type == "color") {
			$this->parent->add_js("fn_buildcolortable.js", true);
		} else if ($type == "fieldfilter") {
			$this->parent->add_js("ajax_lib.v2.js", true);
		} else if ($type == "fieldlist") {
			$this->parent->add_js("fieldlist.js", true);
			$this->parent->add_js("drag_lib.js", true);
		}
		return $this->field[] = $f;
	}
	public function start_field_group($label = "", $display = "table-row") {
		if (!isset($this->field_group_id)) $this->field_group_id = 0;
		if ($display === false) $display = "inline-block"; // $ck_line_break = false
		$this->field_group = [ "id" => $this->field_group_id, "label" => $label, "display" => $display ];
		$this->field_group_id++;
	}
	public function end_field_group() {
		$this->field_group = false; 
	}
	public function start_col_group($label = "", $color = "") {
		if (!isset($this->col_group_id)) $this->col_group_id = 0;
		$this->col_group = [ "id" => $this->col_group_id, "label" => $label, "color" => $color ];
		$this->col_group_id++;
	}
	public function end_col_group() {
		$this->col_group = false; 
	}
	public function add_default($field, $value) {
		$this->defaults[$field] = $value;
	}
	public function set_origem($qry, $label, $ck_dependent=1) {
		$this->qry = $qry;
		$this->src_label = $label;
		$this->ck_dependent = $ck_dependent;
		/* ck_dependent
		1 = Entidade vinculada ao formulario, o registro é criado/excluido conforme definido no formulario 
		0 = Entidade independente, o registro é vinculado/desvinculado conforme selecionado no formulario 
		*/
		$this->group = "";
		if (is_array($qry)) {
			$this->set_field($label); // Se recebe uma lista cria um campo padrao
			$this->src_field = $label;
			$this->src_label = "label";
		}
	}
	public function set_update($qry) {
		$this->update_qry = $qry;
	}
	public function set_order($sql) {
		$this->order = $sql;
	}
}
class cls_field {
	public function __construct($scope, $parent, $field, $label, $type, $ck_req=0, $ck_qry=1, $pos=false, $entity=false) {
		$this->scope = $scope; 
		$this->parent = $parent; 
		$this->entity = $entity; 
		$this->field = $field; 
		$this->label = $label; 
		$this->type = $type; 
		if ($this->type == "checkbox") $this->cb_value = 1;
		$this->pos = $pos; 
		$this->ck_req = $ck_req; 
		$this->ck_qry = $ck_qry; 
		$this->ck_bak = 0; 
		$this->ck_log = 0; 
		$this->ck_readonly = 0; 
		$this->ck_disabled = 0; 
		$this->ck_hidden = 0;
		$this->use_translator = 0;
		$this->use_uppercase = 0;
		$this->file_name_mask = "SELF";
		if ($entity) {
			$this->col_group = $this->entity->col_group;
			$this->field_group = $this->entity->field_group;
		} else {
			$this->field_group = $this->parent->field_group;
		}
		$this->tab_ref = $this->parent->tab_ref;
		$this->tab_id = count($this->parent->tab_ref)-1; 
		if ($this->tab_id >= 0 && $this->parent->tab_ref[$this->tab_id]["label"] == false) $this->tab_id = -1;
		$this->title = null;
		$this->comment = "";
		$this->comment_before = "";
		$this->prop = []; 
		$this->title_prop = []; 
		$this->holder_prop = []; 
		$this->file = []; 
		if ($type == "publisher") {
			$this->publisher_img_action = $this->parent->js_dir . "/publisher_lib.img.php";
			$this->publisher_img_dir = "imagens/";
		} else if (in_array($type, ["date","datetime","daterange","month"]) && $this->parent->pref_field_date == "text") {
			$this->add_prop("onfocus", "recvalue(this)");
			$this->add_prop("onkeyup", "gotofield(this)");
			$this->add_prop("onkeyup", "fnValida(this,'1234567890')");
		} else if ($type == "file") {
			$this->ck_file_link = true;
			$this->parent->ck_file = 1;
		}
		/*if ($type == "checkbox") {
			$this->set_comment($label);
		}*/
	}
	public function set_translator($translator) {
		$this->use_translator = 1;
		$this->translator = $translator;
		$this->label = $this->translator->get($this->label);
	}
	public function set_uppercase() {
		if ($this->type == "text" || $this->type == "textarea") {
			$this->use_uppercase = 1;
			$this->add_prop("style","text-transform:uppercase;");
			$this->add_prop("onblur","this.value=this.value.toUpperCase();");
		}
	}
	public function set_index($ix) {
		$this->field_qry = $this->parent->getFieldIx($ix) . " AS " . $this->parent->getFieldLbl($this->field);
	}
	public function set_qry_index($ix) {
		$this->qry_index = $ix;
	}
	public function set_cb_value($val) {
		if ($this->type == "checkbox") $this->cb_value = $val;
	}
	public function set_db_type($type) {
		$this->db_type = $type;
	}
	public function add_xtra_field($field, $val, $condition=false) {
		if ($this->scope == "field") {
			$this->xtra_field[] = [ "field" => $field, "val" => $val, "condition" => $condition ];
		} else if ($this->scope == "label") {
			foreach ($this->parent->label as $c => $label) {
				if ($this->field == $label["field"]) {
					$this->parent->label[$c]["xtra_field"][] = [ "field" => $field, "val" => $val, "condition" => $condition ];
					break;
				}
			}
		}
	}
	public function set_options($opt) {
		if (isset($opt["ajax"])) {
			$this->ajaxqry = $opt["ajax"]["qry"];
			$this->ajaxid = $opt["ajax"]["id"];
			$this->ajaxlabel = $opt["ajax"]["label"];
			$this->ajaxfilter = isset($opt["ajax"]["filter"]) ? $opt["ajax"]["filter"] : $opt["ajax"]["label"];
			$this->ajax_action = isset($opt["ajax"]["action"]) ? $opt["ajax"]["action"] : [];
			$this->ajax_xtrafield = isset($opt["ajax"]["xtrafield"]) ? $opt["ajax"]["xtrafield"] : [];
		}
		if (isset($opt["new_entry"])) {
			$this->new_entry = $opt["new_entry"];
			$this->add_prop("onchange", "document.getElementById(this.id.replace('$this->field','{$this->field}_entry')).style.display=this.value=='NEW'?'':'none'");
		}
		if (isset($opt["entity"])) {
			$this->rec_table = $opt["entity"];
		}
		if (isset($opt["ck_req"])) {
			if (is_array($this->ck_req))
				$this->ck_req = [$this->ck_req, $opt["ck_req"]];
			else
				$this->ck_req = $opt["ck_req"];
		}
		if (isset($opt["ck_readonly"])) {
			if (isset($this->ck_readonly) && is_array($this->ck_readonly))
				$this->ck_readonly = [$this->ck_readonly, $opt["ck_readonly"]];
			else
				$this->ck_readonly = $opt["ck_readonly"];
		}
		if (isset($opt["ck_disabled"])) {
			if (isset($this->ck_disabled) && is_array($this->ck_disabled))
				$this->ck_disabled = [$this->ck_disabled, $opt["ck_disabled"]];
			else
				$this->ck_disabled = $opt["ck_disabled"];
		}
		if (isset($opt["ck_hidden"])) {
			if (is_array($this->ck_hidden))
				$this->ck_hidden = [$this->ck_hidden, $opt["ck_hidden"]];
			else
				$this->ck_hidden = $opt["ck_hidden"];
		}
		if (isset($opt["ck_empty"])) {
			if (isset($this->ck_empty) && is_array($this->ck_empty))
				$this->ck_empty = [$this->ck_empty, $opt["ck_empty"]];
			else
				$this->ck_empty = $opt["ck_empty"];
		}
		if (isset($opt["ck_value"])) {
			if (isset($this->ck_value) && is_array($this->ck_value))
				$this->ck_value = [$this->ck_value, $opt["ck_value"]];
			else
				$this->ck_value = $opt["ck_value"];
		}
	}
	public function set_pattern($pattern) {
		$this->pattern = $pattern;
	}
	public function set_mask($value, $write_mask=0, $unlock_mask=0) {
		$this->mask = $value;
		$this->write_mask = $write_mask;
		$this->unlock_mask = $unlock_mask;
		$this->parent->ck_mask = max($this->parent->ck_mask, $unlock_mask);
		$this->add_prop("onfocus", "recvalue(this)");
		$this->add_prop("onkeyup", "gotofield(this)");
	}
	public function set_default($value) {
		$this->default = $value;
		if ($this->type == "daterange" && isset($value[0]) && preg_match("/([0-9]+)-([0-9]+)-([0-9]+)/", $value[0], $match)) {
			$this->default["ano_ini"] = $match[1];
			$this->default["mes_ini"] = $match[2];
			$this->default["dia_ini"] = $match[3];
		}
		if ($this->type == "daterange" && isset($value[1]) && preg_match("/([0-9]+)-([0-9]+)-([0-9]+)/", $value[1], $match)) {
			$this->default["ano_fim"] = $match[1];
			$this->default["mes_fim"] = $match[2];
			$this->default["dia_fim"] = $match[3];
		}
		if ($this->type == "daterange" && isset($value[2])) {
			$this->default["ck"] = $value[2];
		}
	}
	public function set_range_default($ano, $mes, $ck=true) {
		$monthsize[1] = 31;
		$monthsize[2] = $ano % 4 == 0 ? 29 : 28;
		$monthsize[3] = 31;
		$monthsize[4] = 30;
		$monthsize[5] = 31;
		$monthsize[6] = 30;
		$monthsize[7] = 31;
		$monthsize[8] = 31;
		$monthsize[9] = 30;
		$monthsize[10] = 31;
		$monthsize[11] = 30;
		$monthsize[12] = 31;
		$dt = mktime(0,0,0,$mes,1,$ano);
		$this->set_default([ date("Y-m-01",$dt), date("Y-m-t",$dt), $ck ]);
	}
	public function set_unique($param=false, $url=false) {
		$this->parent->add_form_rule_unique($this->field, $param, $url);
	}
	public function ck_email() {
		if ($this->type == "text") $this->parent->add_form_rule("form." . $this->field . ".value != \"\" && !mail_reg.test(form." . $this->field . ".value)", $this->parent->lang_js_field . " \\\"" . $this->label . "\\\" " . $this->parent->lang_js_format);
		$this->add_prop("onkeyup","this.value=this.value.toLowerCase()");
		$this->set_uppercase_disable();
		$this->parent->ck_verify_email = 1;
	}
	public function add_rule($cmd,$msg) {
		if ($this->scope == "field") 
			$this->parent->add_form_rule("function(val) { return $cmd; }(form." . $this->field . ".value)", $msg);
		else if ($this->scope == "entity_field") 
			$this->parent->add_form_rule("function(val,i) { return $cmd; }(form['" . $this->entity->prefix . "_" . $this->parent->getFieldLbl($this->field) . "'+i].value,i)", $msg, "entity");
		else if ($this->scope == "label")
			$this->parent->add_form_rule("function(val,i) { return $cmd; }(form['" . $this->parent->getFieldLbl($this->field) . "'+i].value,i)", $msg, "label");
	}
	public function set_uppercase_disable() {
		if (isset($this->prop["style"]) &&
			strpos($this->prop["style"], "text-transform:uppercase;") !== false) {
			$this->prop["style"] = str_replace("text-transform:uppercase;","",$this->prop["style"]);
		}
		if (isset($this->prop["onblur"]) &&
			strpos($this->prop["onblur"], "this.value=this.value.toUpperCase();") !== false) {
			$this->prop["onblur"] = str_replace("this.value=this.value.toUpperCase();","",$this->prop["onblur"]);
		}
	}
	public function add_bak() {
		$this->ck_bak = 1;
	}
	public function set_comment($value,$pos="after") {
		if ($this->use_translator == 1) $value = $this->translator->get($value);
		if ($pos == "after")
			$this->comment = $value;
		if ($pos == "before")
			$this->comment_before = $value;
	}
	public function set_comment_before($value) {
		$this->set_comment($value,"before");
	}
	public function ck_qry_input($ix) {
		$this->ck_qry = $ix;
	}
	/* set_hidden(), set_required(), set_readonly(), set_disabled() 
	methods accept multiple argument blocks as in: set_...($field_name, $op, $val, $bool, $field_name, $op, $val, etc)
	if [trigger field] is different from [value field] use: set_...(args, src) as in: set_...([ $field_name, $op, $val, $bool, $field_name, $op, $val, etc] , [ $field_name, $field_name ])
	*/
	// public function set_hidden($field = null, $op = "==", $val = "", $bool = "&&") {
	public function set_hidden() {
		$params = $this->get_condition_params(func_get_args());
		if ($params["field"] == null)
			$this->ck_hidden = 1;
		else
			$this->set_options(["ck_hidden" => $params]);
		$this->search_next_args("set_hidden", func_get_args());
		if ($this->type == "password" && $this->ck_qry == 1) {
			$this->parent->form_objs[$this->pos+1]->set_hidden(func_get_args());
		}
	}
	// public function set_required($field = null, $op = "==", $val = "", $bool = "&&") {
	public function set_required() {
		$params = $this->get_condition_params(func_get_args());
		if ($params["field"] == null)
			$this->ck_req = 1;
		else
			$this->set_options(["ck_req" => $params]);
		$this->search_next_args("set_required", func_get_args());
		if ($this->type == "password" && $this->ck_qry == 1) {
			$this->parent->form_objs[$this->pos+1]->set_required(func_get_args());
		}
	}
	// public function set_readonly($field = null, $op = "==", $val = "", $bool = "&&") {
	public function set_readonly() {
		$params = $this->get_condition_params(func_get_args());
		if ($params["field"] == null)
			$this->ck_readonly = 1;
		else
			$this->set_options(["ck_readonly" => $params]);
		$this->search_next_args("set_readonly", func_get_args());
		if ($this->type == "password" && $this->ck_qry == 1) {
			$this->parent->form_objs[$this->pos+1]->set_readonly(func_get_args());
		}
	}
	// public function set_disabled($field = null, $op = "==", $val = "", $bool = "&&") {
	public function set_disabled() {
		$params = $this->get_condition_params(func_get_args());
		if ($params["field"] == null)
			$this->ck_disabled = 1;
		else
			$this->set_options(["ck_disabled" => $params]);
		$this->search_next_args("set_disabled", func_get_args());
	}
	public function set_empty() {
		$params = $this->get_condition_params(func_get_args());
		$this->set_options(["ck_empty" => $params]);
		$this->search_next_args("set_empty", func_get_args());
	}
	public function set_value() {
		$args = func_get_args();
		$val = array_shift($args);
		$params = $this->get_condition_params($args);
		$this->set_options(["ck_value" => [ $val, $params ]]);
		// $this->search_next_args("set_value", $args);
	}
	private function get_condition_params($args) {
		if (!empty($args) && gettype($args[0]) == "array") {
			if (!empty($args[1])) $src = gettype($args[1]) == "array" ? $args[1] : [$args[1]];
			$args = $args[0];
		}
		$field = isset($args[0]) ? $args[0] : null;
		$op =    isset($args[1]) ? $args[1] : "==";
		$val =   isset($args[2]) ? $args[2] : "";
		$bool =  isset($args[3]) ? str_replace(["AND","OR"], ["&&","||"], $args[3]) : "&&";
		if (!isset($src)) $src = [ $this->parent->getFieldLbl($field) ];
		return [ "field" => $field, "op" => $op, "val" => $val, "bool" => $bool, "src" => $src ];
	}
	private function search_next_args($method, $args) {
		if (!empty($args) && gettype($args[0]) == "array") {
			if (!empty($args[1])) $src = gettype($args[1]) == "array" ? $args[1] : [$args[1]];
			$args = $args[0];
		} else {
			$src = false;
		}
		if (count($args) > 4) {
			$remove = array_splice($args,0,4); // remove first 4 elements
			array_splice($args,3,0,$remove[3]); // add arg(3) $bool to the end of the array
			// call_user_func_array([ $this, $method ], $args);
			$this->$method($args, $src);
		}
	}
	public function set_ajax_table($table, $id="id", $label="nome", $filter=null) {
		$this->set_ajaxtable($table, $id, $label, $filter);
	}
	public function set_ajaxtable($table, $id="id", $label="nome", $filter=null) {
		$this->ajaxtable = $table;
		$this->ajaxid = $id;
		$this->ajaxlabel = $label;
		$this->ajaxfilter = $filter == null ? $label : $filter;
		$this->ajaxcondition = "";
		if (!isset($this->ajax_action)) 
			$this->ajax_action = [];
		$this->ajax_xtrafield = [];
	}
	/* Depracated
	public function set_ajax_qry($qry, $id="id", $label="nome", $filter=null) {
		$this->set_ajaxqry($qry, $id, $label, $filter);
	}
	public function set_ajaxqry($qry, $id="id", $label="nome", $filter=null) {
		$this->ajaxqry = $qry;
		$this->ajaxid = $id;
		$this->ajaxlabel = $label;
		$this->ajaxfilter = $filter == null ? $label : $filter;
		$this->ajax_action = [];
		$this->ajax_xtrafield = [];
	}
	*/
	public function set_ajaxcondition($condition) {
		$this->set_ajax_condition($condition);
	}
	public function set_ajax_condition($condition) {
		$this->ajaxcondition = $condition;
	}
	public function set_ajaxpath($url, $tags=[], $ref=false) {
		$this->set_ajax_path($url, $tags, $ref);
	}
	public function set_ajax_path($url, $tags=[], $ref=false) {
		$this->ajax_path = $url;
		if (count($tags) == 2) $tags = [ "id" => array_values($tags)[0], "label" => array_values($tags)[1] ]; // Accept [id,label] and [id:id,label:label]
		$this->ajax_tags = $tags;
		$this->ajax_action = [];
		$this->ajax_xtrafield = [];
		if (!$ref) {
			if ($this->scope == "field") 
				$ref = $this->parent->ref;
			else if ($this->scope == "filter") 
				$ref = $this->parent->ref_filter;
			else if ($this->scope == "entity_field")
				$ref = $this->entity->field;
		}
		if ($this->type == "dropdown") {
			preg_match_all("[\[([a-zA-Z0-9_])+\]]", $url, $match);
			if (count($match[0]) > 0) {
				$this->parent->add_js("ajax_lib.v2.js", true);
				foreach ($match[0] as $f) {
					for ($i=0; $i<count($ref)-1; $i++) { // usa-se count($ref)-1 para ignorar o campo atual
						$obj = gettype($ref[$i]) == "array" ? $ref[$i]["object"] : $ref[$i];
						$field = $this->parent->getFieldLbl($obj->field);
						// echo strtolower(substr($f,1,-1)) . " == " . strtolower($field) . "<br>\n";
						if (strtolower(substr($f,1,-1)) == strtolower($field)) {
							if ($this->scope == "entity_field") {
								$action = "loader_" . $this->entity->prefix . "_" . $this->field . ".load(false, p)";
							} else {
								$action = "loader_" . $this->parent->getFieldLbl($this->field) . ".load()";
							}
							if (!empty($obj->ajax) || !empty($obj->ajax_path)) {
								$obj->add_ajax_action($action, "S");
							} else {
								$obj->add_prop("onchange", $action);
							}
							$this->ajax[] = [ "field" => $field, "label" => $obj->label, "obj" => "field" ];
						}
					}
				}
			}
		} else {
			$this->ajax = true;
		}
	}
	public function get_ajax_scope() {
		foreach ($this->ajax as $ajax) $src = $ajax["obj"];
		return $src;
	}
	public function set_ajax_filter($filter) {
		$this->ajaxfilter = $filter;
	}
	public function set_ajax_group($group) {
		$this->ajax_group = $group;
	}
	public function set_ajax_limit($val) {
		$this->ajax_limit = $val;
	}
	public function add_ajaxaction($action, $modo="S", $add_sufix=false) {
		$this->add_ajax_action($action, $modo, $add_sufix);
	}
	public function add_ajax_action($action, $modo="S", $add_sufix=false) {
		// $modo => S = SELECT, R = RESET
		if (!isset($this->ajax_action)) $this->ajax_action = [];
		$c = count($this->ajax_action);
		$this->ajax_action[$c] = array("action" => $action, "modo" => $modo, "add_sufix" => $add_sufix);
	}
	public function add_ajax_xtrafield($field, $xml_label=false) {
		$this->add_ajax_field($field, $xml_label);
	}
	public function add_ajax_field($field, $xml_label=false) {
		if (!$xml_label) $xml_label = $field;
		if (!isset($this->ajax_xtrafield)) $this->ajax_xtrafield = [];
		$c = count($this->ajax_xtrafield);
		$this->ajax_xtrafield[$c] = [ "field" => $field, "xml_label" => $xml_label ];
		/* if ($this->type == "fieldfilter") {
			if ($this->scope == "entity_field") {
				$dst = $this->parent->get_field_def($field, $this->entity->field);
				$this->add_ajax_action("document.formulario['" . $this->entity->prefix . "_$field'+p].value = ''", "R");
			} else {
				$dst = $this->parent->get_field_def($field);
				if (!empty($dst->mask)) {
					$c = 0;
					foreach ($dst->mask as $part) {
						if (is_numeric($part)) {
							$this->add_ajax_action("document.formulario.$field$c.value = ''", "R");
							$c++;
						}
					}
				} else {
					$this->add_ajax_action("document.formulario.$field.value = ''", "R");
				}
			}
		} */
	}
	public function set_currency($value) {
		$this->set_comment($value,"before");
	}
	public function add_prop($prop, $value="", $fn=false) {
		preg_match("[function ([a-zA-Z0-9_]+)\(([a-zA-Z0-9_]+)\)]", $value, $match);
		if (count($match) > 0 && ($match[2] == "" || $match[2] == "rs" || $match[2] == "res")) {
			$this->prop_condition[$prop] = $match[1];
			$value = "";
		}
		if (isset($this->prop[$prop])) {
			if (!in_array($value, explode(";",$this->prop[$prop]))) $this->prop[$prop] .= (substr($this->prop[$prop],-1) != ";" ? ";" : "") . $value;
		} else {
			$this->prop[$prop] = $value;
		}
		if ($fn && $fn instanceof Closure) $this->prop_condition[$prop] = $fn;
	}
	public function add_holder_prop($prop, $value="", $scope="") {
		if ($scope != "title") {
			if (isset($this->holder_prop[$prop])) {
				if (!in_array($value, explode(";",$this->holder_prop[$prop]))) $this->holder_prop[$prop] .= (substr($this->holder_prop[$prop],-1) != ";" ? ";" : "") . $value;
			} else {
				$this->holder_prop[$prop] = $value;
			}
		}
		if ($scope != "field") {
			if (isset($this->title_prop[$prop])) {
				if (!in_array($value, explode(";",$this->title_prop[$prop]))) $this->title_prop[$prop] .= (substr($this->title_prop[$prop],-1) != ";" ? ";" : "") . $value;
			} else {
				$this->title_prop[$prop] = $value;
			}
		}
	}
	public function set_list($list, $ck_break=false) {
		if ($this->use_translator == 1) {
			foreach ($list as $ix => $val) {
				$list[$ix] = $this->translator->get($val);
			}
		}
		$this->list = [
			"vals" => $list,
			"ck_break" => $ck_break,
			"ck_new_entry" => 0
		];
	}
	public function set_list_from($list, $src) {
		$this->set_list($list);
		if ($this->scope == "field")
			$ref = $this->parent->ref;
		else if ($this->scope == "filter")
			$ref = $this->parent->ref_filter;
		else if ($this->scope == "entity_field")
			$ref = $this->entity->field;
		else if ($this->scope == "label")
			$ref = $this->parent->ref_list;
		for ($i=0; $i<count($ref)-1; $i++) { // usa-se count($ref)-1 para ignorar o campo atual
			$obj = gettype($ref[$i]) == "array" ? $ref[$i]["object"] : $ref[$i];
			$field = $this->parent->getFieldLbl($obj->field);
			// echo $src . " == " . $field . "<br>\n";
			if ($src == $field) {
				if ($this->scope == "entity_field") {
					if (!empty($src->ajax)) {
						$action = "loader_" . $this->entity->prefix . "_" . $this->field . ".load(false, p)";
					} else {
						$action = "loader_" . $this->entity->prefix . "_" . $this->field . ".load(false, this.id.replace('" . $this->entity->prefix . "_" . $field . "',''))";
					}
				} else if ($this->scope == "label") {
					$action = "loader_" . $this->parent->getFieldLbl($this->field) . "[].load()";
				} else {
					$action = "loader_" . $this->parent->getFieldLbl($this->field) . ".load()";
				}
				if (!empty($src->ajax)) {
					$obj->add_ajax_action($action, "S");
				} else {
					$obj->add_prop("onchange", $action);
				}
				$this->ajax[] = [ "field" => $field, "label" => $obj->label, "obj" => $this->scope ];
				break;
			}
		}
	}
	public function set_qry($qry, $ix, $label, $group_label = null, $xtra_fields = null) { 
		$this->qry = array(
			"sql" => $qry, 
			"ix" => $ix, 
			"label" => $label, 
			"group_label" => $group_label,
			"xtra_fields" => $xtra_fields,
			"ck_break" => false			
		);
		$this->ck_new_entry = 0;
		$this->ajax = $this->parent->ck_ajax_qry($qry, $this->scope);
	}
	public function set_qry_pk($pk) {
		$this->qry["pk"] = $pk;
	}
	public function set_qry_break($ck=true) {
		$this->qry["ck_break"] = $ck;
	}
	public function enable_log($signature, $log_parser = false) {
		$this->ck_log = 1;
		$this->log_signature = $signature;
		$this->log_parser = $log_parser;
	}
	public function set_new_entry($table=null, $field=null, $params=[], $pk=false) {
		if (!is_array($params)) $params = strpos($params,"?") !== false ? array("url" => $params) : [];
		$params["table"] = $table;
		$params["field"] = $field;
		if (!isset($params["pk"])) $params["pk"] = $pk ? $pk : "SCOPE_IDENTITY()";
		if (!isset($params["label"])) $params["label"] = $params["field"];
		if (!isset($params["url"])) $params["url"] = "";
		$this->ck_new_entry = 1;
		$this->new_entry = $params;
		$this->add_prop("onchange", "document.getElementById(this.id+'_entry').style.display=this.value=='NEW'?'':'none'");
	}
	public function set_default_label($label) {
		if ($this->use_translator == 1) $ent = $this->translator->get($label);
		$this->default_label = $label;
	}
	public function set_dropdown_multiple($table, $key, $xtra_key=[]) {
		//$this->add_prop("size", $size);
		$this->add_prop("multiple");
		$this->set_entity($table, $key, $xtra_key);
	}
	public function set_entity($table, $key, $xtra_key=[]) {
		$this->rec_table = [ "table" => $table, "field" => $key, "xtra_key" => $xtra_key ];
		$this->table = $table;
		$this->key_field = $key;
		$this->xtra_key = $xtra_key;
		// form join
		$str = "LEFT JOIN $table ON $table.$key = {$this->parent->table}.{$this->parent->pk}";
		$keys = array_keys($xtra_key);
		for ($i=0; $i<count($keys); $i++) {
			if (substr($xtra_key[$keys[$i]],0,6) == "SELECT")
				$str .= " AND $table." . $keys[$i] . " IN (" . $xtra_key[$keys[$i]] . ")";
			else
				$str .= " AND $table." . $keys[$i] . " = '" . $xtra_key[$keys[$i]] . "'";
		}
		$ck = 1;
		for ($i=0; $i<count($this->parent->related); $i++) {
			if ($this->parent->related[$i]["use"] == "F" && $this->parent->related[$i]["sql"] == $str) return false;
		}
		if ($ck == 1) $this->parent->add_related($str, $use="F");
	}
	public function set_ref_query($sql, $label) {
		$this->ref_qry = $sql;
		$this->ref_qry_label = $label;
	}
	public function set_ref_table($table, $index, $label) {
		$sql = "SELECT $label AS $table
			FROM $table
			WHERE $index IN ('[" . $this->parent->getFieldIx($this->field) . "]')";
		$this->set_ref_query($sql, $table);
	}
	public function set_ref_label($field) {
		if ($this->type != "fieldfilter" && $this->type != "dropdown") exit("set_ref_label() only for fieldfilter or dropdown");
		$this->ref_label = $field;
	}
	public function set_file_name($mask) {
		$this->file_name_mask = $mask;
	}
	public function add_dir($dir, $maxsize=false, $ck_thumb=0, $pos=null) {
		$c = count($this->file); // new index
		if (is_array($dir)) {
			$this->file[$c]["dir"]["O"] = $dir[0]; // access dir
			$this->file[$c]["dir"]["D"] = $dir[1]; // write dir
		} else {
			$this->file[$c]["dir"]["O"] = $dir;
			$this->file[$c]["dir"]["D"] = $dir;
		}
		$this->file[$c]["maxsize"] = $maxsize;
		$this->file[$c]["ck_thumb"] = $ck_thumb;
		$this->file[$c]["pos"] = $pos;
		//$this->file[$c]["mask"] = $mask;
	}
	public function set_dir_qry($sql) {
		$c = count($this->file)-1; // existing index
		$this->file[$c]["sql"] = $sql;
	}
	public function hide_file() {
		$this->ck_file_link = false;
	}
	public function set_publisher_dir($dir) {
		$this->publisher_img_dir = $dir;
	}
	public function add_radio_option($val, $label, $field=null, $type=null) {
		$obj = new cls_field("field", $this->parent, $field, $label, $type);
		$this->list["vals"][$val] = array(
			"object" => $obj,
			"ck_xtra" => 1, 
			"label" => $label, 
			"field" => $field, 
			"type" => $type, 
			"ck_req" => 0, 
			"prop" => []
		);
		if ($this->scope == "form") {
			foreach ($this->parent["ref"] as $f) {
				if ($f["field"] == $this->field) {
					$tab_id = $f["tab_id"];
					break;
				}
			}
		}
		$field = $this->field;
		if ($this->scope != "filter" && count($this->parent->tab_ref) > 0 && $this->parent->tab_ref[$tab_id]["table"] != null)
			 $field = $this->tab_ref[$tab_id]["table"] . "_" . $field;
		if ($type == "text") {
			$this->list["vals"][$val]["prop"]["onfocus"] = "temp=this.value";
			$this->list["vals"][$val]["prop"]["onblur"] = "temp!=this.value?{$field}[".(count($this->list["vals"])-1)."].checked=true:void(null)";
		} else if ($type == "dropdown") {
			$this->list["vals"][$val]["prop"]["onchange"] = "{$field}[".(count($this->list["vals"])-1)."].checked=true";
		}
		return $obj;
	}
	public function add_radio_field_prop($prop, $value=true) { // habilitar apos debug do metodo add_radio_option
		$keys = array_keys($this->list["vals"]);
		$ix = $keys[count($keys)-1];
		$this->list["vals"][$ix]["object"]->add_prop($prop, $value);
	}
	public function add_radio_qry_new_entry($table=null, $field=null, $params=[]) {
		$params["table"] = $table;
		$params["field"] = $field;
		if (!isset($params["pk"])) $params["pk"] = $pk ? $pk : "SCOPE_IDENTITY()";
		if (!isset($params["label"])) $params["label"] = $params["field"];
		if (!isset($params["url"])) $params["url"] = "";
		$keys = array_keys($this->list);
		$ix = $keys[count($keys)-1];
		$this->list[$ix]["ck_new_entry"] = 1;
		$this->list[$ix]["new_entry"] = $params;
		$this->add_radio_field_prop("onchange", "document.getElementById(this.id+'_entry').style.display=this.value=='NEW'?'':'none'");
	}
	public function ck_min_value($val) {
		$this->min_value = $val;
	}
	public function ck_max_value($val) {
		$this->max_value = $val;
	}
	/* Deprecated 07/08/2023
	public function set_group_block($ck) {
		if (!$ck) $this->field_group["display"] = "inline-block";
	} */
	public function set_parser($fn) {
		$this->parser = $fn;
	}
	// cls_report compatibility
	public function set_parent($field) {
		$this->parent->cls_report->set_field_parent($field);
		$this->field_parent = $field;
	}
	public function set_order($val, $dir="ASC") {
		$this->parent->cls_report->set_field_order($val, $dir);
	}
	public function set_estatistico($ck) {
		$this->parent->cls_report->set_field_estatistico($ck);
		$this->ck_estatistico = $ck;
	}
}
class cls_tab {
	public function __construct($label, $table, $key) {
		$this->label = $label; 
		$this->table = $table; 
		$this->key = $key;
		$this->ck_readonly = false;
	}
	public function set_readonly() {
		$this->ck_readonly = true;
	}
}
// Compatibiliyy
if (is_file(dirname(__FILE__) . "/fn_formatnum.php")) {
	include_once dirname(__FILE__) . "/fn_formatnum.php";
} else {
	function formatnum($valor, $depth, $sep_dec, $sep_mil) {
		return number_format($valor, str_replace("+","",$depth), $sep_dec, $sep_mil);
	}
}

if (!function_exists("isnull")) {
	function isnull($var, $default) {
		return is_null($var) ? $default : $var;
	}
}
?>