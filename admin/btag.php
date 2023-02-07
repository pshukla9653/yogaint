<?php 
	include('lib/dbconf.php');
	$obj->loginadmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php head_b(); ?>
</head>
<body class="tag">
<?php headers_b($obj->logintime()); leftbar_b(); ?>
<div class="right-sidebar">
    <h1 class="text-center"><i class="fa fa-tags "></i>Blog Tag</h1>
    <button data-id="0" class="btn-2 pull-right cl_add_btag_pop">Add New Blog Tag</button>
    <div class="tablelist-card">
    	<?php echo $obj->btag_list(); ?>
    </div>
    <div class="overlap">
        <div class="ajaxloader"><img src="<?php echo MYDOMAIN; ?>/images/icons/load.gif"></div>
        <div class="overlap_js"></div>
    </div>
</div>
<?php footer_b(); ?>   
</body>
</html>