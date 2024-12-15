<?php 
include "../inc/globals.php";
include "../inc/connect.php";
include "../inc/cls_form.php";

if (!isset($step)) $step = 1;

$form = new cls_form();
// if ($_SESSION["debug"] == 1) $form->set_debug();
$form->set_entity("stores", "STORES");
$form->add_dependency("staffs","store_id","Staff");

// key params
$form->set_key("store_id");
$form->add_label("stores.store_name","Name");
$form->add_label("stores.city","City");
$form->add_label("stores.state","State");
$form->add_label("stores.email","E-mail");
$form->add_label("stores.phone","Phone");
$form->set_order("store_name");

// fields
$f = $form->add_field("store_name","Name","text",1);
$f->add_prop("size",50);
// $f->add_prop("maxlength",50);
$f = $form->add_field("phone","Phone","text");
$f->add_prop("size",10);
$f = $form->add_field("email","E-mail","text");
$f->add_prop("size",50);
$f->ck_email();
$f = $form->add_field("street","Street","text");
$f->add_prop("size",50);
$f = $form->add_field("city","City","text");
$f->add_prop("size",50);
$f = $form->add_field("state","State","dropdown");
$states = [ 
	"AL" => "Alabama", "AK" => "Alaska", "AZ" => "Arizona", "AR" => "Arkansas", "CA" => "California", "CO" => "Colorado", "CT" => "Connecticut", "DE" => "Delaware", "FL" => "Florida", "GA" => "Georgia", "HI" => "Hawaii", "ID" => "Idaho", "IL" => "Illinois", "IN" => "Indiana", "IA" => "Iowa", "KS" => "Kansas", "KY" => "Kentucky[B]", "LA" => "Louisiana", "ME" => "Maine", "MD" => "Maryland", "MA" => "Massachusetts[B]", "MI" => "Michigan", "MN" => "Minnesota", "MS" => "Mississippi", "MO" => "Missouri", "MT" => "Montana", "NE" => "Nebraska", "NV" => "Nevada", "NH" => "New Hampshire", "NJ" => "New Jersey", "NM" => "New Mexico", "NY" => "New York", "NC" => "North Carolina", "ND" => "North Dakota", "OH" => "Ohio", "OK" => "Oklahoma", "OR" => "Oregon", "PA" => "Pennsylvania[B]", "RI" => "Rhode Island", "SC" => "South Carolina", "SD" => "South Dakota", "TN" => "Tennessee", "TX" => "Texas", "UT" => "Utah", "VT" => "Vermont", "VA" => "Virginia[B]", "WA" => "Washington", "WV" => "West Virginia", "WI" => "Wisconsin", "WY" => "Wyoming"
];
$f->set_list($states);
$f = $form->add_field("zip_code","Zip Code","text");
$f->add_prop("size",5);
$f->add_prop("maxlength",5);

$e = $form->add_entity("Stock","stocks","store_id","1XN");
$e->set_prefix("stock");
$e->set_pk(["store_id","product_id"]);
$f = $e->add_field("product_id","Product","fieldfilter",1);
$f->set_qry("SELECT product_id, product_name FROM products WHERE product_name LIKE '%[pchave]%' ORDER BY product_name","product_id","product_name");
$f = $e->add_field("quantity","Quantity","text",1);
$f->add_prop("size",4);
$f->add_prop("maxlength",4);
$f->add_prop("onkeyup","fnValida(this,'1234567890')");

// filter
$f = $form->add_filter("store_name","Name","text",1);
$f->add_prop("size",50);
$f = $form->add_filter("state","State","dropdown");
$f->set_list($states);

// build
$form->build($modo, $step);
?>