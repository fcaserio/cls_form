<? 
foreach ($_GET  as $_ix =>$_val) $$_ix = $_val;
foreach ($_POST as $_ix =>$_val) $$_ix = $_val;
if (empty($ck_xml)) include "../admin/indice.php";
?>