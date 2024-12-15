# cls_form: PHP-Database-Administrator

Create PHP administrative interface to database
Supports SQL Server and MySQL
1. Initialize your table:
if (!isset($step)) $step = 1;
$form = new cls_form(); // cls_form("MSSQL") / cls_form("MySQL") / default MSSQL
$form->set_entity("mytablename", "My Table Name For Display");
2. Add fields to your form
$f = $form->add_field("product_name","Name","text",1);
$f->add_prop("size",50);
$f = $form->add_field("product_price","Price","text",1);
$f->add_prop("size",10);
$f->set_default(100);
// etc
3. Declare labels to build reports
$form->set_key("product_id");
$form->add_label("products.product_name","Name");
$form->add_label("products.product_price","Price");
$form->set_label_numeric(2);
4. Build:
$form->build($modo, $step); // supports $modo="insert", $modo="update", $modo="delete", $modo="report"
