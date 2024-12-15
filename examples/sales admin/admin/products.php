<?php 
include "../inc/globals.php";
include "../inc/connect.php";
include "../inc/cls_form.php";

if (!isset($step)) $step = 1;

$form = new cls_form();
// if ($_SESSION["debug"] == 1) $form->set_debug();
$form->set_entity("products", "PRODUCTS");
$form->add_dependency("order_items","product_id","Orders");

// key params
$form->set_key("product_id");
$form->add_label("products.product_name","Name");
$form->add_label("brands.brand_name","Brand");
$form->add_label("categories.category_name","Category");
$form->add_label("products.model_year","Year");
$form->add_label("products.list_price","Price");
$form->set_label_numeric(2);
$form->add_related("INNER JOIN brands ON brands.brand_id = products.brand_id");
$form->add_related("INNER JOIN categories ON categories.category_id = products.category_id");
$form->set_order("product_name");

// fields
$f = $form->add_field("product_name","Name","text",1);
$f->add_prop("size",50);
// $f->add_prop("maxlength",50);
$f = $form->add_field("brand_id","Brand","dropdown",1);
$f->set_qry("SELECT brand_id, brand_name FROM brands ORDER BY brand_name","brand_id","brand_name");
$f = $form->add_field("category_id","Category","dropdown",1);
$f->set_qry("SELECT category_id, category_name FROM categories ORDER BY category_name","category_id","category_name");
$f = $form->add_field("model_year","Year","text",1);
$f->add_prop("size",4);
$f->add_prop("maxlength",4);
$f->add_prop("onkeyup","fnValida(this,'1234567890')");
$f = $form->add_field("list_price","Price","text",1);
$f->add_prop("size",8);
$f->add_prop("maxlength",9);
$f->add_prop("onkeyup","fnValida(this,'1234567890.',',','.')");

$e = $form->add_entity("Stock","stocks","product_id","1XN");
$e->set_prefix("stock");
$e->set_pk(["product_id","store_id"]);
$f = $e->add_field("store_id","Store","dropdown",1);
$f->set_qry("SELECT store_id, store_name FROM stores ORDER BY store_name","store_id","store_name");
$f = $e->add_field("quantity","Quantity","text",1);
$f->add_prop("size",4);
$f->add_prop("maxlength",4);
$f->add_prop("onkeyup","fnValida(this,'1234567890')");

// filter
$f = $form->add_filter("product_name","Name","text",1);
$f->add_prop("size",50);
// $f->add_prop("maxlength",50);
$f = $form->add_filter("brand_id","Brand","dropdown",1);
$f->set_qry("SELECT brand_id, brand_name FROM brands ORDER BY brand_name","brand_id","brand_name");
$f = $form->add_filter("category_id","Category","dropdown",1);
$f->set_qry("SELECT category_id, category_name FROM categories ORDER BY category_name","category_id","category_name");
$f = $form->add_filter("model_year","Year","text",1);
$f->add_prop("size",4);
$f->add_prop("maxlength",4);
$f->add_prop("onkeyup","fnValida(this,'1234567890')");


// build
$form->build($modo, $step);
?>