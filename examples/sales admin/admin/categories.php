<?php 
include "../inc/globals.php";
include "../inc/connect.php";
include "../inc/cls_form.php";

if (!isset($step)) $step = 1;

$form = new cls_form();
// if ($_SESSION["debug"] == 1) $form->set_debug();
$form->set_entity("categories", "CATEGORIES");
$form->add_dependency("products","category_id","Products");

// key params
$form->set_key("category_id");
$form->add_label("categories.category_name","Name");

// fields
$f = $form->add_field("category_name","Name","text",1);
$f->add_prop("size",50);
// $f->add_prop("maxlength",50);

// build
$form->build($modo, $step);
?>