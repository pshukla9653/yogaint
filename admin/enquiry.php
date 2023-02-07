<?php 
	include('lib/dbconf.php');
	$obj->loginadmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php head_b(); ?>
<style>
    ul{
        list-style: none;
    }
    li{
        list-style: none;
    }
</style>
</head>
<body class="category">
<?php headers_b($obj->logintime()); leftbar_b(); ?>
<div class="right-sidebar">
    <h1 class="text-center"><i class="fa fa-question-circle" aria-hidden="true"></i> Enquiry</h1>
    
    <div class="tablelist-card">
    	<?php echo $obj->enquiry_list(); ?>
    </div>
    <div class="overlap">
        <div class="ajaxloader"><img src="<?php echo MYDOMAIN; ?>/images/icons/load.gif"></div>
        <div class="overlap_js"></div>
    </div>
</div>
<?php footer_b(); ?>   
</body>
</html>