<?php 
include "../inc/globals.php";
include "../inc/connect.php";
include "../inc/cls_form.php";

if (!isset($step)) $step = 1;

$form = new cls_form();
// if ($_SESSION["debug"] == 1) 
	$form->set_debug();
$form->set_entity("staffs", "STAFF");
$form->add_dependency("orders","staff_id","Order");

// key params
$form->set_key("staff_id");
$form->add_label("stores.store_name","Store");
$form->add_label("staffs.first_name","First Name");
$form->add_label("staffs.last_name","Last Name");
$form->add_label("staffs.email","E-mail");
$form->add_label("staffs.phone","Phone");
$form->add_related("INNER JOIN stores ON stores.store_id = staffs.store_id");
$form->set_groupbyactive("active");
$form->set_order("store_name");
$form->set_order("last_name");
$form->set_order("first_name");

// fields
$f = $form->add_field("first_name","First Name","text",1);
$f->add_prop("size",50);
// $f->add_prop("maxlength",50);
$f = $form->add_field("last_name","Last Name","text",1);
$f->add_prop("size",50);
// $f->add_prop("maxlength",50);
$f = $form->add_field("phone","Phone","text");
$f->add_prop("size",10);
$f = $form->add_field("email","E-mail","text",1);
$f->add_prop("size",50);
$f->ck_email();
$f = $form->add_field("store_id","Store","dropdown",1);
$f->set_qry("SELECT store_id, store_name FROM stores ORDER BY store_name","store_id","store_name");
$f = $form->add_field("active","Active","checkbox");
$f->set_default(1);

// filter
$f = $form->add_filter("first_name","First Name","text");
$f->add_prop("size",50);
$f = $form->add_filter("last_name","Last Name","text");
$f->add_prop("size",50);
$f = $form->add_filter("store_id","Store","dropdown");
$f->set_qry("SELECT store_id, store_name FROM stores ORDER BY store_name","store_id","store_name");

// build
$form->build($modo, $step);
?>