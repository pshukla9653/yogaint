<?php 
	include('lib/dbconf.php');
	$obj->loginadmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php head_b(); ?>
</head>
<body>
<?php headers_b($obj->logintime()); leftbar_b(); ?>
<div class="right-sidebar">
    <h1 class="text-center"><i class="fa fa-tachometer"></i> DASHBOARD</h1>
    <div class="portal-home-b">
    <h2 class="portal-home">PORTAL</h2>
    <!-- <div class="userinfo-mainepanel">
        <div class=" userinfo-panel bg-pink ">
            <div class="icon"><i class="fa fa-external-link"></i></div>
            <div class="userinfocontent-panel">
                <h3>Post</h3>
                <h2><?php echo $obj->row_count("post"); ?></h2>
            </div>
        </div>
        <div class="userinfo-panel bg-cyan ">
            <div class="icon"><i class="fa fa-list"></i></div>
            <div class="userinfocontent-panel">
                <h3>Catagory</h3>
                <h2><?php echo $obj->row_count("category"); ?></h2>
            </div>
        </div>
        <div class="userinfo-panel bg-light-green ">
            <div class="icon"><i class="fa fa-tags"></i></div>
            <div class="userinfocontent-panel">
                <h3>Tag</h3>
                <h2><?php echo $obj->row_count("tag"); ?></h2>
            </div>
        </div>
        
        <div class="userinfo-panel bg-blue ">
            <div class="icon"><i class="fa fa-file-text"></i></div>
            <div class="userinfocontent-panel">
                <h3>Page</h3>
                <h2><?php echo $obj->row_count("page"); ?></h2>
            </div>
        </div>
        <div class="userinfo-panel bg-chocolate">
            <div class="icon"><i class="fa fa-paragraph"></i></div>
            <div class="userinfocontent-panel">
                <h3>Comment</h3>
                <h2><?php echo $obj->row_count("comment"); ?></h2>
            </div>
        </div>
        </div>
    </div>
    <div class="portal-home-b">
    <h2 class="portal-home">PORTFOLIO</h2>
    <div class="userinfo-mainepanel">
        <div class=" userinfo-panel bg-l-yellow">
            <div class="icon"><i class="fa fa-external-link"></i></div>
            <div class="userinfocontent-panel">
                <h3>Post</h3>
                <h2><?php echo $obj->row_count("post"); ?></h2>
            </div>
        </div>
        <div class="userinfo-panel bg-cyan ">
            <div class="icon"><i class="fa fa-list"></i></div>
            <div class="userinfocontent-panel">
                <h3>Catagory</h3>
                <h2><?php echo $obj->row_count("category"); ?></h2>
            </div>
        </div>
        </div>
    </div>
        <div class="portal-home-b">
        <h2 class="portal-home">BLOGS</h2>
        <div class="userinfo-mainepanel">
        <div class=" userinfo-panel bg-red ">
            <div class="icon"><i class="fa fa-external-link"></i></div>
            <div class="userinfocontent-panel">
                <h3>Post</h3>
                <h2><?php echo $obj->row_count("bpost"); ?></h2>
            </div>
        </div>
        <div class="userinfo-panel bg-grey ">
            <div class="icon"><i class="fa fa-list"></i></div>
            <div class="userinfocontent-panel">
                <h3>Catagory</h3>
                <h2><?php echo $obj->row_count("bcategory"); ?></h2>
            </div>
        </div>
        <div class="userinfo-panel bg-green ">
            <div class="icon"><i class="fa fa-tags"></i></div>
            <div class="userinfocontent-panel">
                <h3>Tag</h3>
                <h2><?php echo $obj->row_count("btag"); ?></h2>
            </div>
        </div>
        <div class="userinfo-panel bg-l-blue">
            <div class="icon"><i class="fa fa-paragraph"></i></div>
            <div class="userinfocontent-panel">
                <h3>Comment</h3>
                <h2><?php echo $obj->row_count("bcomment"); ?></h2>
            </div>
        </div>   
    </div>
</div> -->
    <div class="portal-home-b">
    <h2 class="portal-home">FILES</h2>
        <div class="userinfo-panel bg-orange ">
            <div class="icon"><i class="fa fa-file-image-o"></i></div>
            <div class="userinfocontent-panel">
                <h3>Image Libary</h3>
                <h2><?php echo $obj->row_count_w("tbl_enquiry", 'id!=0'); ?></h2>
            </div>
        </div>
    
</div>
</div>
<?php footer_b(); ?>   
</body>
</html>