<?php 
include "../inc/globals.php";
include "../inc/connect.php";
include "../inc/cls_form.php";

if (!isset($step)) $step = 1;

$form = new cls_form();
// if ($_SESSION["debug"] == 1) $form->set_debug();
$form->set_entity("customers", "CUSTOMERS");
$form->add_dependency("orders","customer_id","Order");

// key params
$form->set_key("customer_id");
$form->add_label("customers.first_name","First Name");
$form->add_label("customers.last_name","Last Name");
$form->add_label("customers.city","City");
$form->add_label("customers.state","State");
$form->add_label("customers.email","E-mail");
$form->add_label("customers.phone","Phone");
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

// filter
$f = $form->add_filter("first_name","First Name","text",1);
$f->add_prop("size",50);
$f = $form->add_filter("last_name","Last Name","text",1);
$f->add_prop("size",50);
$f = $form->add_filter("state","State","dropdown");
$f->set_list($states);

// build
$form->build($modo, $step);
?>