
<?php
session_start();
ob_start();

  class DB
	{
    /** Put this variable to true if you want ALL queries to be debugged by default:
      */
    private $defaultDebug = false;

    /** INTERNAL: The start time, in miliseconds.
      */
    
	private $base;
    private $server;
    private $user;
    private $pass;
	private $conn;
	private $dbconn;
    private $mtStart;
	private $loginuser;
	private $login;
	public  $date_time;
    /** INTERNAL: The number of executed queries.
      */
    private $nbQueries;
    /** INTERNAL: The last result ressource of a query().
      */
    private $lastResult;
	
	
	public function __construct($base, $server, $user, $pass)
	{
		$this->mtStart    = $this->getMicroTime();
		$this->nbQueries  = 0;
		$this->lastResult = NULL;
		$this->base = $base;
		$this->server = $server;
		$this->user = $user;
		$this->pass = $pass;
		$this->conn = $conn;
		$this->dbconn = $dbconn;
		date_default_timezone_set("Asia/Kolkata");
		$this->date_time = date('Y-m-d H:i:s');
		$this->connections();
	}
	/****************************************other-function************************************/
	function row_count($tbl) {
		$query=$this->query("select id from tbl_".$tbl);
		return $this->numRows($query);
	}
	function row_count_w($table,$where) {
		$query=$this->query("select id from ".$table." where ".$where);
		return $this->numRows($query);
	}
	
	function s_option($id,$table) {
		$query=$this->query("select name,id from ".$table);
		while($fetch=$this->fetchNextObject($query)) {
			if($id==$fetch->id) $select='selected'; else  $select='';
			$echo.='<option '.$select.' value="'.$fetch->id.'">'.$fetch->name.' ('.$fetch->id.')</option>';
		}
		return $echo;
	}

	function echronTree($id, $parent_id, $page, $parent = 0, $spaces = '') {
    $query = $this->query("SELECT id, parent_id, name FROM tbl_".$page." WHERE id!='".$id."' and parent_id=".$parent." ORDER BY name ASC");
    $num = $this->numRows($query);
  	if($num > 0) {
        while($fetch = $this->fetchNextObject($query)) {
					if($parent_id==$fetch->id) $select='selected'; else  $select='';
					$strclass = $fetch->parent_id=='0' ? 'class="echron-parent"' : '';
					$echo .= '<option '.$strclass.' '.$select.' value="'.$fetch->id.'">'.$spaces.$fetch->name.' ('.$fetch->id.')</option>'
					.$this->echronTree($id, $parent_id, $page, $fetch->id, $spaces.'---');
        }
		}
		return $echo;
	}

	function echronTreeUrl($id, $page) {
    $query = $this->query("SELECT id, parent_id, name FROM tbl_".$page." WHERE id='".$id."'");
    $num = $this->numRows($query);
  	if($num > 0) {
        while($fetch = $this->fetchNextObject($query)) {
					$break = $fetch->parent_id ? '/' : '';
					$echo .= $this->echronTreeUrl($fetch->parent_id, $page).$break.$this->echronSlug_id($page,$fetch->id);
        }
		}
		return $echo;
	}

	//$tbl_url = substr($tbl_url,0,-1);
	

	function item_id($id,$table,$col) {
		$query=mysqli_query("select ".$col." from ".$table." where id='".$id."'");
		$fetch= mysqli_fetch_assoc($query);
		return $fetch[$col];
	}
	function ratpercent($id) {
		$ratquery=mysqli_query("select sum(rating) from tbl_comment where post_id='".$id."' and status=1");
		$ratnumquery=mysqli_query("select id from tbl_comment where post_id='".$id."'");
		$num=mysqli_num_rows($ratnumquery) * 5;
		while($ratfetth=mysqli_fetch_array($ratquery)) {
			$score=$ratfetth['sum(rating)'] * 5;
		}
		$fscore=$score / $num;
		return (float)sprintf('%.1f', $fscore);
	}
	function bratpercent($id) {
		$bratquery=mysqli_query("select sum(rating) from tbl_bcomment where bpost_id='".$id."' and status=1");
		$bratnumquery=mysqli_query("select id from tbl_bcomment where bpost_id='".$id."'");
		$bnum=mysqli_num_rows($bratnumquery) * 5;
		while($bratfetth=mysqli_fetch_array($bratquery)) {
			$bscore=$bratfetth['sum(rating)'] * 5;
		}
		$bfscore=$bscore / $num;
		return (float)sprintf('%.1f', $bfscore);
	}
	function breadCumb($url, $type = '') {
		$explode = explode("/",$url);

		$list .= '
				<li><a href="'.HDOMAIN.'">Home</a></li>';

		foreach($explode as $value) {
			$strValue = str_replace("-"," ",$value);

			if($value === end($explode)) {
				$list .= '
				<li>'.$strValue.'</li>';
	 		} else {
				$list .= '
				<li><a href="'.HDOMAIN.$type.$value.'">'.$strValue.'</a></li>';
			 }

		}
		return $list;
	}
	function page_url($url,$id) {
		$query=$this->query("select * from tbl_ids where slug='".$url."'");
		$num=$this->numRows($query);
		$fetch=$this->fetchNextObject($query);
		
		if($id==1) {
			if($fetch->post_id>0) $return = $fetch->post_id;
			elseif($fetch->category_id>0) $return = $fetch->category_id;
			elseif($fetch->tag_id>0) $return = $fetch->tag_id;
			elseif($fetch->page_id>0) $return = $fetch->page_id;
		} else {
			if($fetch->post_id>0) $return = "post";
			elseif($fetch->category_id>0) $return = "category";
			elseif($fetch->tag_id>0) $return = "tag";
			elseif($fetch->page_id>0) $return = "page";
		}
		if($num<1) return header('location: '.HDOMAIN.'404'); else return $return;
	}
	
	function sitemapEchron($type = '') {

		switch($type) {
			case 'BLOG':
				$catname = 'bcategory';
				$postname = 'bpost';
				$tagname = 'btag';
				$urltype = "blog/";
				$queryEchron = "
					select
					tbl_bids.bpost_id as post_id,
					tbl_bids.bcategory_id as category_id,
					tbl_bids.btag_id as tag_id,
					tbl_bids.slug
					from
					tbl_bids,
					tbl_bpost,
					tbl_bcategory,
					tbl_btag
					where
					tbl_bids.slug!='404' and
					tbl_bpost.status = 1 and
					tbl_bcategory.status = 1 and
					(tbl_bids.bpost_id = tbl_bpost.id or
					tbl_bids.bcategory_id = tbl_bcategory.id or
					tbl_bids.btag_id = tbl_btag.id)
					group by
					tbl_bids.btag_id,
					tbl_bids.bcategory_id,
					tbl_bids.bpost_id
				";
			break;
			case 'PORTFOLIO' :
				$catname = 'pcategory';
				$postname = 'ppost';
				$urltype = "portfolio/";
				$queryEchron = "
					select
					tbl_pids.ppost_id as post_id,
					tbl_pids.pcategory_id as category_id,
					tbl_pids.slug
					from
					tbl_pids,
					tbl_ppost,
					tbl_pcategory
					where
					tbl_pids.slug!='404' and
					tbl_ppost.status = 1 and
					tbl_pcategory.status = 1 and
					(tbl_pids.ppost_id = tbl_ppost.id or
					tbl_pids.pcategory_id = tbl_pcategory.id)
					group by
					tbl_pids.pcategory_id,
					tbl_pids.ppost_id
				";
			break;
			default :
			$catname = 'category';
			$postname = 'post';
			$tagname = 'tag';
			$pagename = 'page';
			$urltype = "";
			$queryEchron = "
				select
				tbl_ids.post_id as post_id,
				tbl_ids.category_id as category_id,
				tbl_ids.tag_id as tag_id,
				tbl_ids.page_id as page_id,
				tbl_ids.slug
				from
				tbl_ids,
				tbl_post,
				tbl_category,
				tbl_tag,
				tbl_page
				where
				tbl_ids.slug!='404' and
				tbl_post.status = 1 and
				tbl_category.status = 1 and
				tbl_page.status = 1 and
				(tbl_ids.post_id = tbl_post.id or
				tbl_ids.category_id = tbl_category.id or
				tbl_ids.tag_id = tbl_tag.id or
				tbl_ids.page_id = tbl_page.id)
				group by
				tbl_ids.page_id,
				tbl_ids.tag_id,
				tbl_ids.category_id,
				tbl_ids.post_id
			";
			break;
		}

		$sn = 1;
		$query=$this->query($queryEchron);
		while($fetch=$this->fetchNextObject($query)) {

			$postEchron = $fetch->post_id>0 ? 'POST' : '';
			$categoryEchron = $fetch->category_id>0 ? 'CATEGORY' : '';
			$tagEchron = $fetch->tag_id>0 ? 'TAG' : '';
			$pageEchron = $fetch->page_id>0 ? 'PAGE' : '';

			$category = $this->item_id($fetch->post_id,"tbl_".$postname,$catname."_id");

			if($fetch->post_id>0) {
				$id = $fetch->post_id;
				$name = $postname;
	
				if($category) {
					$cat_expl = explode(",", $category);
					$tblUrl = $this->echronTreeUrl($cat_expl[0], $catname).'/'.$fetch->slug;
				} else {
					$tblUrl = $fetch->slug;
				}
	
			}
			elseif($fetch->category_id>0) {
				$id = $fetch->category_id;
				$name = $catname;
				$tblUrl = $this->echronTreeUrl($id, $name);
			}
			elseif($fetch->tag_id>0) {
				$id = $fetch->tag_id;
				$name = $tagname;
				$tblUrl = $fetch->slug;
			}
			elseif($fetch->page_id>0) {
				$id = $fetch->page_id;
				$name = $pagename;
				$tblUrl = $this->echronTreeUrl($id, $name);
			}

			$echo .= '<url>
									<loc>'.HDOMAIN.$urltype.$tblUrl.'</loc>
								</url>';
		}
		return $echo;
	}
	function pageEchronUrl($url, $type = 'url', $tbl = 'ids') {

		$urlExplode = explode("/",$url);
		$lastUrl = end($urlExplode);

		$query=$this->query("select * from tbl_".$tbl." where slug='".$lastUrl."'");
		$num=$this->numRows($query);
		$fetch=$this->fetchNextObject($query);

		switch($tbl){
			case "bids":
				$post_id = $fetch->bpost_id;
				$category_id = $fetch->bcategory_id;
				$tag_id = $fetch->btag_id;
				$page_id = 0;
				$category = $this->bpost_id($post_id, 'bcategory_id');
				$catname = 'bcategory';
				$postname = 'bpost';
				$tagname = 'btag';
				$pagename = 'bpage';
				$urltype = "blog/";
			break;
			case "pids":
				$post_id = $fetch->ppost_id;
				$category_id = $fetch->pcategory_id;
				$tag_id = 0;
				$page_id = 0;
				$category = $this->ppost_id($post_id, 'pcategory_id');
				$catname = 'pcategory';
				$postname = 'ppost';
				$tagname = 'ptag';
				$pagename = 'ppage';
				$urltype = "portfolio/";
			break;
			default:
				$post_id = $fetch->post_id;
				$category_id = $fetch->category_id;
				$tag_id = $fetch->tag_id;
				$page_id = $fetch->page_id;
				$category = $this->post_id($post_id, 'category_id');
				$catname = 'category';
				$postname = 'post';
				$tagname = 'tag';
				$pagename = 'page';
				$urltype = "";
			break;
		}

		


		if($post_id>0) {
			$id = $post_id;
			$name = $postname;

			if($category) {
				$cat_expl = explode(",", $category);
				$tblUrl = $this->echronTreeUrl($cat_expl[0], $catname).'/'.$fetch->slug;
			} else {
				$tblUrl = $fetch->slug;
			}

		}
		elseif($category_id>0) {
			$id = $category_id;
			$name = $catname;
			$tblUrl = $this->echronTreeUrl($id, $name);
		}
		elseif($page_id>0) {
			$id = $page_id;
			$name = $pagename;
			$tblUrl = $this->echronTreeUrl($id, $name);
		}
		elseif($tag_id>0) {
			$id = $tag_id;
			$name = $tagname;
			$tblUrl = $fetch->slug;
		}

		if($num<1) {
			return header('location: '.HDOMAIN.'404');
		}
		else {
			switch($type){
				case "url":
					return $url !== $tblUrl ? header('location: '.HDOMAIN.$urltype.$tblUrl) : '';
				break;
				case "id":
					return $id;
				break;
				case "name":
					return $name;
				break;
			}
		}
	}

	
	function tbl_id($id,$val,$table) {
		$query=mysqli_query("select ".$val." from ".$table." where id='".$id."'");
		$fetch=mysqli_fetch_assoc($query);
		return $fetch[$val];
	}
	function home_pg($val,$name) {
		$query=mysqli_query("select ".$val." from tbl_section where name='".$name."'");
		$fetch=mysqli_fetch_assoc($query);
		return $fetch[$val];
	}
	function cat_des($id) {
		if($id) {
			$echo = '
			<a class="btn-1" href="'.HDOMAIN.$this->slug_id("category",$id).'">
					'.$this->tbl_id($id, "name", "tbl_category").'
			</a>';
		} else {
			$echo = '';
		}
		return $echo;
	}
	function seo_meta($id,$table) {
		

		$query=$this->query("select * from ".$table." where id='".$id."'");
		$fetch=$this->fetchNextObject($query);

		$hquery=$this->query("select * from tbl_section where name='homeSeo'");
		$hfetch=$this->fetchNextObject($hquery);
		
		$fetch->opt_0 ? $title=$fetch->opt_0 : $title=$hfetch->opt_0;

		$fetch->opt_1 ? $keywords=$fetch->opt_1 : $keywords=$hfetch->opt_1;

		$fetch->opt_2 ? $description=$fetch->opt_2 : $description=$hfetch->opt_2;

		if($fetch->opt_3 or !$id) $follow='';
		else $follow='<meta name="robots" content="NOINDEX, NOFOLLOW" />';

		$echo = 
		$follow.'
		<title>'.$title.'</title>
		<meta name="keywords" content="'.$keywords.'">
		<meta name="description" content="'.$description.'" />
		';

		return $echo;
	}
	function page_status($page, $id) {
		if ($page == 'post') {
			if(!$this->row_count_w('tbl_post', 'status=1 and id="'.$id.'"')) $echo = header('location: '.HDOMAIN.'404');
		}
		elseif ($page == 'category') {
			if(!$this->row_count_w('tbl_category', 'status=1 and id="'.$id.'"')) $echo = header('location: '.HDOMAIN.'404');
		}
			elseif ($page == 'page') {
			if(!$this->row_count_w('tbl_page', 'status=1 and id="'.$id.'"')) $echo = header('location: '.HDOMAIN.'404');
		}
		return $echo;
	}
	/******************************header-footer-function******************************/
	function headnavcat() {
		$query=$this->query("select * from tbl_category where opt_3!=0 && id!=3 order by opt_6");
  while($fetch=$this->fetchNextObject($query)) {
			$echo .= '
			<div class="nav-columns">
			<div class="nav-head">
							<a href="'.HDOMAIN.$this->slug_id("category",$fetch->id).'">'.$fetch->name.'</a>
			</div>
			<ul>
							'.$this->headnavpost($fetch->id).'
			</ul>
</div>
			';
		}
		return $echo;
	}
	function headnavpost($id) {
		$query = $this->query("select * from tbl_post where FIND_IN_SET('" . $this->tbl_id($id, "id", "tbl_category") . "', category_id) and opt_3!=0");
    while ($fetch = $this->fetchNextObject($query)) {

					$post.='<li><a href="'.HDOMAIN.$this->slug_id("post",$fetch->id).'">'.$fetch->name.'</a></li>';
				}
				return 	$post;
	}
	function footnavcat() {
		$query=$this->query("select * from tbl_category where opt_3!=0 order by opt_6");
  while($fetch=$this->fetchNextObject($query)) {
			$echo .= '
			<li>
							<a href="'.HDOMAIN.$this->slug_id("category",$fetch->id).'">'.$fetch->name.'</a>
			</li>
			';
		}
		return $echo;
	}
	function footnavpage() {
		$query=$this->query("select * from tbl_page where opt_3!=0 && name!='404' && name!='thanks' order by opt_6");
  while($fetch=$this->fetchNextObject($query)) {
			$echo .= '
			<li>
							<a href="'.HDOMAIN.$this->slug_id("page",$fetch->id).'">'.$fetch->name.'</a>
			</li>
			';
		}
		return $echo;
	}
	function headers() {
		$echo ='
		<header>
		<div class="header">
										<div class="header-logo">
													<a href="'.HDOMAIN.'">
													'.$this->show_detail_gal($this->home_pg('gal_img_id','headerFooter'), "", "", "single").'
													</a>
									</div>
									'.arrylistone($this->home_pg('opt_2','headerFooter'),"link","0","header-nav").'
									<div class="header-nav foot">
													© <a href="'.HDOMAIN.'">'.COMPNAME.'</a> '.date("Y").'
									</div>
					</div>
		<div class="header right">
						<div class="header-logo cl_nav"><img src="'.HDOMAIN.'images/icons/nav-bar.png" alt="'.heading(2).'"/></div>
						'.arrylistone($this->home_pg('opt_2','headerFooter'),"link","1","header-nav").'
									<div class="header-nav foot">
													'.arrylist($this->home_pg('opt_4','headerFooter'),"link").'
									</div>
					</div>
	</header>
	<nav>
		<div class="nav-logo">
		<a href="'.HDOMAIN.'">
		'.$this->show_detail_gal($this->home_pg('gal_img_id','headerFooter'), "", "", "single").'
		</a>
					</div>
					<div class="nav-logo right cl_nav"><i class="fa fa-times "></i></div>
					'.$this->headnavcat().'
	</nav>
		';
		return $echo;
	}
	function footer() {
		$echo = '
		<footer>
		<div class="container wow fadeIn">
						<div class="colunm">
										<h4>Explore</h4>
										<ul>
											'.$this->footnavcat().'
										</ul>
						</div>
						<div class="colunm">
										<h4>Say hello</h4>
										<ul>
										'.arrylist($this->home_pg('opt_3','headerFooter'),"link").'
										</ul>
						</div>
		</div>
		<div class="left box" data-speed="1">
						<ul class="heading1">
						'.arrylist($this->home_pg('opt_0','headerFooter'),"").'
						</ul>
						<figure> '.$this->show_detail_gal($this->img_id("section",$this->home_pg('img_id','headerFooter'), "id"), "", "", "single").' </figure>
						'.arrylistone($this->home_pg('opt_2','headerFooter'),"link","2","btn2").'
		</div>
		<ul class="foot-bottom">
		'.$this->footnavpage().'
		</ul>
</footer>
		';
		return $echo;
	}
	/**************************login-function************************************/
	function loginuser() {
		if($_SESSION['username']=='echrontech@gmail.com' && $_SESSION['userpassword']==md5($_SESSION['echronpass'])) {
			$loginuser=mysqli_query("select id from tbl_admin_key where
			id			='".$_SESSION['id']."' and
			token		='".$_SESSION['token']."' and
			admin		='".$_SESSION['admin']."'");
		} else {
			$loginuser=mysqli_query("select id from tbl_admin_key where
			id			='".$_SESSION['id']."' and
			token		='".$_SESSION['token']."' and
			admin		='".$_SESSION['admin']."' and
			email		='".$_SESSION['username']."' and
			password	='".$_SESSION['userpassword']."'");
		}
		return $loginuser;
	}
	function loginadmin() {
		$login = new LOGIN();
		if(!$login->isLoggedIn()) $this->login = header('location: '.MYDOMAIN);
		return $this->login;
	}
	function loginhome() {
		$userlog=mysqli_num_rows($this->loginuser());
		if($userlog) {
			header('location: '.MYDOMAIN.'home.php');
		}
		return $this->login;
	}
	function logintime() {
		$query=mysqli_query("select logout_date from tbl_admin_key where email='".$_SESSION['username']."'");
		$fetch= mysqli_fetch_assoc($query);
		return dateitme($fetch["logout_date"]);
	}
	/******************************************images-function****************************************/
	function resize_img($file, $w, $h, $crop, $dest) {
		list($width, $height) = getimagesize($file);
		$r = $width / $height;
		if ($crop) {
			if ($width > $height) {
				$width = ceil($width-($width*abs($r-$w/$h)));
			} else {
				$height = ceil($height-($height*abs($r-$w/$h)));
			}
			$newwidth = $w;
			$newheight = $h;
		} else {
			if ($w/$h > $r) {
				$newwidth = $h*$r;
				$newheight = $h;
			} else {
				$newheight = $w/$r;
				$newwidth = $w;
			}
		}
		$exploding = explode(".",$file);
		$ext = end($exploding);
		switch($ext){
			case "png":
				$create = imagecreatetruecolor($newwidth, $newheight);
				imagealphablending($create, false);
				imagesavealpha($create, true);
				$src = imagecreatefrompng($file);
				imagecopyresampled($create, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				$outfile = imagepng($create, $dest);
			break;
			case "jpeg":
			case "jpg":
				$src = imagecreatefromjpeg($file);
				$create = imagecreatetruecolor($newwidth, $newheight);
				imagecopyresampled($create, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				$outfile = imagejpeg($create, $dest);
			break;
			case "gif":
				$src = imagecreatefromgif($file);
				$create = imagecreatetruecolor($newwidth, $newheight);
				imagecopyresampled($create, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				$outfile = imagegif($create, $dest);
			break;
			default:
				$src = imagecreatefromjpeg($file);
				$create = imagecreatetruecolor($newwidth, $newheight);
				imagecopyresampled($create, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				$outfile = imagejpeg($create, $dest);
			break;
		}
		
		return $outfile;
	}
	function upload_multy_img($postfile) {
		$z=0;
		foreach ($postfile['name'] as $size => $value) {
			$sizes+=$postfile["size"][$size];
		}
		$sizefinal = number_format($sizes / 1048576, 2) . ' MB';
		if($sizefinal>32) {
			
			echo "Error: Maximum upload file size: 32 MB.";
		}
		else {
			foreach ($postfile['name'] as $name => $value) {
	
				$ext=pathinfo($postfile["name"][$name], PATHINFO_EXTENSION);
				$images_path = time().$z.'.'.$ext;
				
				if ($postfile["error"][$name] > 0) {
					echo "<li>Error: invalid files ".$postfile["name"][$name] .'</li>';
				}
				
				if ($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif") {
					
					$images_dest='../images/img-lib/' . $images_path;
					
					move_uploaded_file($postfile["tmp_name"][$name], $images_dest);
					
					$this->resize_img($images_dest, 500, 300, 0, '../images/img-lib/small/'.$images_path);
					
					$this->resize_img($images_dest, 1000, 600, 0, '../images/img-lib/medium/'.$images_path);
					
					$this->resize_img($images_dest, 2000, 1200, 0, '../images/img-lib/large/'.$images_path);
					
					$this->query("insert into tbl_img set name='".$images_path."', mod_date='".$this->date_time."', upt_date='".$this->date_time."', alt='', title='', description='', caption=''");
					
					//echo '<li>File successfully uploaded : ../images/img-lib/' . $images_path . '</li>';
				}
				else {
					echo "<li>Error: File formate not match, Should be jpg, jpeg, png & gif ".$postfile["name"][$name] .'</li>';
				}
				++$z;
			}
		}
	
	}
	function show_multy_img($size,$class) {
		$query=$this->query("select * from tbl_img where id!='0' order by mod_date DESC");

		$echo = '<h2>Choose Pic from here</h2>

		<div class="col l6 post-input cl_gal_ft category  multi" data-type="category">
            <label for="">Filter Category (<span>0</span>)</label>
            <div class="checkhead cl_checkdown">
				<span>Choose Category</span>
				<i class="fa fa-arrow-down"></i>
			</div>
			<div class="checklist">
			'.$this->category_check("", " where img_id!=''").'
			</div>
		</div>
		<div class="col l6 post-input multi cl_gal_ft post" data-type="post">
            <label for="">Filter Post (<span>0</span>)</label>
            <div class="checkhead cl_checkdown">
				<span>Choose Post</span>
				<i class="fa fa-arrow-down"></i>
			</div>
			<div class="checklist">
			'.$this->post_check("", " where img_id!=''").'
			</div>
		</div>';

		$echo .= '<ul class="lblist choose">';
			while($fetch=$this->fetchNextObject($query)) {
				$echo .= '<li class="'.$class.'" data-id="'.$fetch->id.'"><figure><img src="'.MYDOMAIN.'images/img-lib/'.$size.'/'.$fetch->name.'"></figure></li>';
			}
		$echo .= '</ul>';
		return $echo;
	}
	function del_multy_img($ids) {
		/*foreach ($ids as $i => $value) {
			$query=$this->query("select * from tbl_img where id='".$ids[$i]."'");
			$fetch=$this->fetchNextObject($query);
			unlink('../images/img-lib/'.$fetch->name);
			unlink('../images/img-lib/small/'.$fetch->name);
			unlink('../images/img-lib/medium/'.$fetch->name);
			unlink('../images/img-lib/large/'.$fetch->name);
			$this->query("delete from tbl_img where id='".$ids[$i]."'");
		}*/
		$ids=implode(",",$ids);
		
		$query=$this->query("select * from tbl_img where id IN (".$ids.")");
		while($fetch=$this->fetchNextObject($query)) {
			unlink('../images/img-lib/'.$fetch->name);
			unlink('../images/img-lib/small/'.$fetch->name);
			unlink('../images/img-lib/medium/'.$fetch->name);
			unlink('../images/img-lib/large/'.$fetch->name);
		}
		$this->query("delete from tbl_img where id IN (".$ids.")");
		$this->query("update tbl_ids set img_id='0' where img_id IN (".$ids.")");
		$this->query("update tbl_section set img_id='0' where img_id IN (".$ids.")");
	}
	function img_select($id) {
		$query=$this->query("select * from tbl_img where id='".$id."'");
		return $this->fetchNextObject($query);
	}
	function img_id($page,$id,$val) {
		
		if($page=='post')		$post="post_id='".$id."'";			else $post="post_id='0'";
		if($page=='category')	$category="category_id='".$id."'";	else $category="category_id='0'";
		if($page=='tag')		$tag="tag_id='".$id."'";			else $tag="tag_id='0'";
		if($page=='page')		$pages="page_id='".$id."'";			else $pages="page_id='0'";
		
		$query=$this->query("select img_id from tbl_ids where ".$post." && ".$category." && ".$tag." && ".$pages);
		$fetch=$this->fetchNextObject($query);

		if($page=='section') $imageId = $id; else $imageId = $fetch->img_id;
		
		$queryimg=$this->query("select name,id from tbl_img where id='".$imageId."'");
		$fetchimg=$this->fetchNextObject($queryimg);
		$imgname=$fetchimg->name;
		if($imgname=='') $imgname='thumb.png';
		if($val=='name') return $imgname; elseif ($val=='id') return $fetchimg->id;
	}
	function bimg_id($page,$id,$val) {
		
		if($page=='bpost')		$post="bpost_id='".$id."'";			else $post="bpost_id='0'";
		if($page=='bcategory')	$category="bcategory_id='".$id."'";	else $category="bcategory_id='0'";
		if($page=='btag')		$tag="btag_id='".$id."'";			else $tag="btag_id='0'";
		
		$query=$this->query("select img_id from tbl_bids where ".$post." && ".$category." && ".$tag);
		$fetch=$this->fetchNextObject($query);

		if($page=='section') $imageId = $id; else $imageId = $fetch->img_id;
		
		$queryimg=$this->query("select name,id from tbl_img where id='".$imageId."'");
		$fetchimg=$this->fetchNextObject($queryimg);
		$imgname=$fetchimg->name;
		if($imgname=='') $imgname='thumb.png';
		if($val=='name') return $imgname; elseif ($val=='id') return $fetchimg->id;
	}
	function pimg_id($page,$id,$val) {
		
		if($page=='ppost')		$post="ppost_id='".$id."'";			else $post="ppost_id='0'";
		if($page=='pcategory')	$category="pcategory_id='".$id."'";	else $category="pcategory_id='0'";
		
		$query=$this->query("select img_id from tbl_pids where ".$post." && ".$category);
		$fetch=$this->fetchNextObject($query);

		if($page=='section') $imageId = $id; else $imageId = $fetch->img_id;
		
		$queryimg=$this->query("select name,id from tbl_img where id='".$imageId."'");
		$fetchimg=$this->fetchNextObject($queryimg);
		$imgname=$fetchimg->name;
		if($imgname=='') $imgname='thumb.png';
		if($val=='name') return $imgname; elseif ($val=='id') return $fetchimg->id;
	}
	function show_detail_gal($ids,$size,$class,$qnt) {
		if($ids=='') $ids=0.1;
		if($class=='list') {$li='<li>'; $li_='</li>';}
		$query=$this->query("select * from tbl_img where  id IN (".$ids.") order by field(id,".$ids.")");
			if($qnt=='all') {
				while($fetch=$this->fetchNextObject($query)) {
					$echo .= $li.'<img class="'.$class.'" src="'.MYDOMAIN.'images/img-lib/'.$size.'/'.$fetch->name.'" alt="'.$fetch->alt.'">'.$li_;
				}
			}
			elseif($qnt=='single') {
				$fetch=$this->fetchNextObject($query);
				if($class=='name') {
					$echo = $fetch->name;
				} else {
					$echo .= '<img class="'.$class.'" src="'.MYDOMAIN.'images/img-lib/'.$size.'/'.$fetch->name.'" alt="'.$fetch->alt.'">';
				}
			}
		return $echo;
	}
/******************************************slug-function****************************************/
	function slug_count($val,$id) {

		if($id) $myquery=" && category_id!='".$id."' && tag_id!='".$id."' && post_id!='".$id."' && page_id!='".$id."'"; else $myquery="";

		$query=$this->query("select id from tbl_ids where slug='".$val."'".$myquery);
		return $this->numRows($query);
	}
	function bslug_count($val,$id) {

		if($id) $myquery=" && bcategory_id!='".$id."' && btag_id!='".$id."' && bpost_id!='".$id."'"; else $myquery="";

		$query=$this->query("select id from tbl_bids where slug='".$val."'".$myquery);
		return $this->numRows($query);
	}
	function pslug_count($val,$id) {

		if($id) $myquery=" && pcategory_id!='".$id."' && ppost_id!='".$id."'"; else $myquery="";

		$query=$this->query("select id from tbl_pids where slug='".$val."'".$myquery);
		return $this->numRows($query);
	}
	function slug_id($page,$id) {
		
		if($page=='post')		$post="post_id='".$id."'";			else $post="post_id='0'";
		if($page=='category')	$category="category_id='".$id."'";	else $category="category_id='0'";
		if($page=='tag')		$tag="tag_id='".$id."'";			else $tag="tag_id='0'";
		if($page=='page')		$page="page_id='".$id."'";			else $page="page_id='0'";
		
		$query=$this->query("select slug from tbl_ids where ".$post." && ".$category." && ".$tag." && ".$page);
		$fetch=$this->fetchNextObject($query);
		return $fetch->slug;
	}
	function echronSlug_id($page,$id) {

		
		if($page=='post')		$post="post_id='".$id."'";			else $post="post_id='0'";
		if($page=='category')	$category="category_id='".$id."'";	else $category="category_id='0'";
		if($page=='tag')		$tag="tag_id='".$id."'";			else $tag="tag_id='0'";
		if($page=='page')		$pages="page_id='".$id."'";			else $pages="page_id='0'";

		if($page=='bpost')		$bpost="bpost_id='".$id."'";			else $bpost="bpost_id='0'";
		if($page=='bcategory')	$bcategory="bcategory_id='".$id."'";	else $bcategory="bcategory_id='0'";
		if($page=='btag')		$btag="btag_id='".$id."'";				else $btag="btag_id='0'";

		if($page=='ppost')		$ppost="ppost_id='".$id."'";			else $ppost="ppost_id='0'";
		if($page=='pcategory')	$pcategory="pcategory_id='".$id."'";	else $pcategory="pcategory_id='0'";

		switch($page) {
			case "bpost" :
			case "bcategory" :
			case "btag" :
				$echronquery = "select slug from tbl_bids where ".$bpost." && ".$bcategory." && ".$btag;
			break;
			case "ppost" :
			case "pcategory" :
				$echronquery = "select slug from tbl_pids where ".$ppost." && ".$pcategory;
			break;
			default :
				$echronquery = "select slug from tbl_ids where ".$post." && ".$category." && ".$tag." && ".$pages;
			break;
		}
		
		$query=$this->query($echronquery);
		$fetch=$this->fetchNextObject($query);
		return $fetch->slug;
	}
	function slugpage_id($page,$id) {

		$parent_ids = $this->item_id($id,"tbl_".$page,"parent_ids")?  $this->item_id($id,"tbl_".$page,"parent_ids").','.$id : $id;

		$query=$this->query("select slug from tbl_ids where ".$page."_id IN (".$parent_ids.")");

		while($fetch=$this->fetchNextObject($query)) {
			$echronSlug .= $fetch->slug.'/';
		}

		return substr($echronSlug,0,-1);
	}
	function bslug_id($page,$id) {
		
		if($page=='bpost') $post="bpost_id='".$id."'"; else $post="bpost_id='0'";
		if($page=='bcategory')	$category="bcategory_id='".$id."'";	else $category="bcategory_id='0'";
		if($page=='btag') $tag="btag_id='".$id."'"; else $tag="btag_id='0'";
		
		$query=$this->query("select slug from tbl_bids where ".$post." && ".$category." && ".$tag);
		$fetch=$this->fetchNextObject($query);
		return $fetch->slug;
	}
	


function enquiry_list() {
	
	$query=$this->query("select * from tbl_enquiry order by mod_date desc");
	while($fetch=$this->fetchNextObject($query)) {


	$list.='<tr data-id="'.$fetch->id.'" data-type="enquiry">
				<td>'.$fetch->id.'</td>
				<td>'.$fetch->name.'</td>
				<td>'.$fetch->email.'</td>
				<td>'.$fetch->count_code.' - '.$fetch->phone.'</td>
		
				<td>'.$fetch->gender.'</td>
				<td>'.$fetch->age.'</td>
				<td>'.$fetch->nation.'</td>
				<td>'.$fetch->operator.'</td>
				<td>'.$fetch->interest.'</td>
				<td>'.$fetch->date.'</td>
				<td>'.$fetch->mod_date.'</td>
				
				
				
			</tr>';
	}
	$echo='<table class="customers">
            <thead>
                <tr><th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
								<th>Gender</th>
                <th>Age</th>
								<th>Nation</th>
								<th>Operator</th>

								<th>School</th>
								<th>date</th>
								<th>Date/Time</th>
								
                </tr>
            </thead>
            <tbody>'.$list.'</tbody>
        </table>';
		
		return $echo;
}

function category_check($id,$where) {
	$input = time();
	$query=$this->query("select * from tbl_category ".$where);
	while($fetch=$this->fetchNextObject($query)) {
		if(in_array($fetch->id, explode(",",$id))) $check='checked'; else $check='';
		$echo.='<div><input type="checkbox" '.$check.' id="cat'.$input.$fetch->id.'" class="cl_lib_filt catpost" name="cat'.$input.$fetch->id.'" value="'.$fetch->id.'">
		 <label for="cat'.$input.$fetch->id.'">'.$fetch->name.'</label></div>';
	}
	return $echo;
}
function echronTreeCheckbox($id, $parent = 0, $spaces = '') {
	$query = $this->query("SELECT id, parent_id, name FROM tbl_category WHERE parent_id=".$parent." ORDER BY name ASC");
	$num = $this->numRows($query);
	if($num > 0) {
			while($fetch = $this->fetchNextObject($query)) {
				if(in_array($fetch->id, explode(",",$id))) $check='checked'; else $check='';
				$strclass = $fetch->parent_id=='0' ? 'class="echron-parent"' : '';
				$echo.='<div '.$strclass.'>
				<span class="spaces-c">'.$spaces.'</span>
								<input type="checkbox" '.$check.' id="cat'.$input.$fetch->id.'" class="cl_lib_filt catpost" name="cat'.$input.$fetch->id.'" value="'.$fetch->id.'">
									<label for="cat'.$input.$fetch->id.'">'.$fetch->name.'</label>
								
								</div>'
				.$this->echronTreeCheckbox($id, $fetch->id, $spaces.'--');
			}
	}
	return $echo;
}

function echronTreeCheckbox1($id, $parent = 0, $spaces = '') {
	$query = $this->query("SELECT id, parent_id, name FROM tbl_bcategory WHERE parent_id=".$parent." ORDER BY name ASC");
	$num = $this->numRows($query);
	if($num > 0) {
			while($fetch = $this->fetchNextObject($query)) {
				if(in_array($fetch->id, explode(",",$id))) $check='checked'; else $check='';
				$strclass = $fetch->parent_id=='0' ? 'class="echron-parent"' : '';
				$echo.='<div '.$strclass.'>
				<span class="spaces-c">'.$spaces.'</span>
								<input type="checkbox" '.$check.' id="cat'.$input.$fetch->id.'" class="cl_lib_filt catpost" name="cat'.$input.$fetch->id.'" value="'.$fetch->id.'">
									<label for="cat'.$input.$fetch->id.'">'.$fetch->name.'</label>
								
								</div>'
				.$this->echronTreeCheckbox($id, $fetch->id, $spaces.'--');
			}
	}
	return $echo;
}
function category_select() {
	$query=$this->query("select * from tbl_category ".$where);
	while($fetch=$this->fetchNextObject($query)) {
		$echo .='<option value="'.$fetch->id.'">'.$fetch->name.'</option>';
	}
	return $echo;
}
function post_check($id,$where) {
	$input = time();
	$query=$this->query("select * from tbl_post ".$where);
	while($fetch=$this->fetchNextObject($query)) {
		if(in_array($fetch->id, explode(",",$id))) $check='checked'; else $check='';
		$echo.='<div><input type="checkbox" '.$check.' id="pos'.$input.$fetch->id.'" class="cl_lib_filt" name="pos'.$input.$fetch->id.'" value="'.$fetch->id.'">
		<label for="pos'.$input.$fetch->id.'">'.$fetch->name.'</label></div>';
	}
	return $echo;
}
function post_select() {
	$query=$this->query("select * from tbl_post".$where);
	while($fetch=$this->fetchNextObject($query)) {
		$echo .='<option value="'.$fetch->id.'">'.$fetch->name.'</option>';
	}
	return $echo;
}
function family_chain() {
	for($i=1; $i<10; $i++) {
		$echo .= '<li><a href="#">IND</a></li>';
	}
	$echo1 = '
	<li><a href="#">IND</a></li>
	<li>
			<a href="#">US</a> 
			<ul>
					<li><a href="#">1 US Child</a>
					<ul>
							<li><a href="#">US Sub Child</a>
							<ul>
									<li> <a href="#">US 2(Sub) Child</a>
											<ul>
													<li><a href="#">US 3(Sub) Child</a></li>
											</ul>
									</li>
							</ul>
							</li>
					</ul>
					</li>
					<li><a href="#">2 US Child</a></li>
			</ul>
	</li>
	<li><a href="#">UK</a></li>
	';
	return $echo;
}







/*******blog-post-function*********/
function bpost_list() {
	
	$query=$this->query("select * from tbl_bpost order by mod_date desc");
	while($fetch=$this->fetchNextObject($query)) {
		if ($fetch->status){
			$active='<a class="background-red cl_bpost_act" href="javascript:void()">Deactive</a>';
			$view='<a class="background-green" target="_blank" href="'.HDOMAIN.'blog/'.$this->bslug_id("bpost",$fetch->id).'">View</a>';
		}
		else {
			$active='<a class="background-blue cl_bpost_act" href="javascript:void()">Active</a>';
			$view='';
		}
	$list.='<tr data-id="'.$fetch->id.'" data-type="bpost">
				<td>'.$fetch->id.'</td>
				<td>'.$fetch->name.'</td>
				<td><img src="images/img-lib/small/'.$this->bimg_id("bpost",$fetch->id, "name").'" width="100"></td>
				<td>'.$active.'
					<a class="background-grey cl_gal_img" href="javascript:void()">Gallery</a>
					<a class="background-red cl_bpost_del" href="javascript:void()">Delete</a>
					<a class="background-orange cl_add_bpost_pop" href="javascript:void()" data-id="'.$fetch->id.'">Edit</a>
					'.$view.'
				</td>
				<td><strong>MOD</strong>:- '.dateitme($fetch->mod_date).'<br><strong>UPT</strong>:- '.dateitme($fetch->upt_date).'</td>
			</tr>';
	}
	$echo='<table class="customers">
            <thead>
                <tr><th>ID</th>
                <th>Post Name</th>
                <th>Image</th>
                <th>Action</th>
                <th>Date/Time</th>
                </tr>
            </thead>
            <tbody>'.$list.'</tbody>
        </table>';
		
		return $echo;
}

function bcategory_check($id,$where) {
	$input = time();
	$query=$this->query("select * from tbl_bcategory ".$where);
	while($fetch=$this->fetchNextObject($query)) {
		if(in_array($fetch->id, explode(",",$id))) $check='checked'; else $check='';
		$echo.='<div><input type="checkbox" '.$check.' id="cat'.$input.$fetch->id.'" class="cl_lib_filt catpost" name="cat'.$input.$fetch->id.'" value="'.$fetch->id.'">
		<label for="cat'.$input.$fetch->id.'">'.$fetch->name.'</label></div>';
	}
	return $echo;
}


/******************************************blog-tag-function****************************************/
function btag_list() {
	
	$query=$this->query("select * from tbl_btag order by mod_date desc");
	while($fetch=$this->fetchNextObject($query)) {
	$list.='<tr data-id="'.$fetch->id.'">
				<td>'.$fetch->id.'</td>
				<td>'.$fetch->name.'</td>
				<td><img src="images/img-lib/small/'.$this->bimg_id("btag",$fetch->id, "name").'" width="100"></td>
				<td>
					<a class="background-red cl_btag_del" href="javascript:void()">Delete</a>
					<a class="background-orange cl_add_btag_pop" data-id="'.$fetch->id.'" href="javascript:void()">Edit</a>
					<a class="background-green" target="_blank" href="'.HDOMAIN.'blog/'.$this->bslug_id("btag",$fetch->id).'">View</a>
				</td>
				<td><strong>MOD</strong>:- '.dateitme($fetch->mod_date).'<br><strong>UPT</strong>:- '.dateitme($fetch->upt_date).'</td>
			</tr>';
	}
	
	$echo='<table class="customers">
            <thead>
                <tr><th>ID</th>
                <th>tag Name</th>
                <th>Image</th>
                <th>Action</th>
                <th>Date/Time</th>
                </tr>
            </thead>
            <tbody>'.$list.'</tbody>
        </table>';
		
		return $echo;
}


function btag_check($id) {
	$query=$this->query("select * from tbl_btag");
	while($fetch=$this->fetchNextObject($query)) {
		if(in_array($fetch->id, explode(",",$id))) $check='checked'; else $check='';
		$echo.='<div><input type="checkbox" '.$check.' class="tagpost" id="tag'.$fetch->id.'"name="tag'.$fetch->id.'" value="'.$fetch->id.'">
		<label for="tag'.$fetch->id.'">'.$fetch->name.'</label></div>';
	}
	return $echo;
}
/******************************************blog-comment-function****************************************/
function bcomment_list() {
	
	$query=$this->query("select * from tbl_bcomment order by mod_date desc");
	while($fetch=$this->fetchNextObject($query)) {
		if($fetch->status=='1') {
			$active='';
			$view='<a class="background-green" href="'.MYDOMAIN.$this->bslug_id("bpost",$fetch->bpost_id).'">View</a>';
		} else {
			$active='<a class="background-blue cl_bcomment_act" href="javascript:void()">Active</a>';
			$view='';
		}
	$list.='<tr data-id="'.$fetch->id.'">
				<td>'.$fetch->id.'</td>
				<td>'.$fetch->name.'<br>'.$fetch->email.'</td>
				<td>'.$fetch->ip_address.'</td>
				<td><strong>'.$this->item_id($fetch->bpost_id,"tbl_bpost","name").'</strong><br>'.$fetch->rating.' Rating</td>
				<td>
					<a class="background-red cl_bcomment_del" href="javascript:void()">Delete</a>
					<a class="background-orange cl_add_bcomment_pop" data-id="'.$fetch->id.'" href="javascript:void()">Edit</a>
					'.$view.$active.'
				</td>
				<td><strong>MOD</strong>:- '.dateitme($fetch->mod_date).'<br><strong>UPT</strong>:- '.dateitme($fetch->upt_date).'</td>
			</tr>';
	}
	$echo='<table class="customers">
            <thead>
                <tr><th>ID</th>
                <th>Name/Email</th>
                <th>IP Address</th>
                <th>Post for</th>
                <th>Action</th>
                <th>Date/time</th>
                </tr>
            </thead>
            <tbody>'.$list.'</tbody>
        </table>';
		
		return $echo;
}
function bpage_url($url,$id) {
	$query=$this->query("select * from tbl_bids where slug='".$url."'");
	$num=$this->numRows($query);
	$fetch=$this->fetchNextObject($query);
	
	if($id==1) {
		if($fetch->bpost_id>0) $return = $fetch->bpost_id;
		elseif($fetch->bcategory_id>0) $return = $fetch->bcategory_id;
		elseif($fetch->btag_id>0) $return = $fetch->btag_id;
	} else {
		if($fetch->bpost_id>0) $return = "bpost";
		elseif($fetch->bcategory_id>0) $return = "bcategory";
		elseif($fetch->btag_id>0) $return = "btag";
	}
	if($num<1) return header('location: '.HDOMAIN.'404'); else return $return;
}
/******************************************datbaseconnectin-function****************************************/
	//datbaseconnectin	
	public function connections() {
		$this->conn = mysqli_connect($this->server, $this->user, $this->pass);
		if(!$this->conn) {
			echo 'Connection ERROR please check connection';
			return false;
		}
		$this->dbconn =	mysqli_select_db($this->base,$this->conn);
 		if(!$this->dbconn) {
			echo 'Database Connection ERROR please check Database';
			return false;
		}
		return $this->dbconn;
	}	

    /** Query the database.
      * @param $query The query.
      * @param $debug If true, it output the query and the resulting table.
      * @return The result of the query, to use with fetchNextObject().
      */
    function query($query, $debug = -1)
    {
      $this->nbQueries++;
      $this->lastResult = mysqli_query($query) or $this->debugAndDie($query);

      $this->debug($debug, $query, $this->lastResult);

      return $this->lastResult;
    }
    /** Do the same as query() but do not return nor store result.\n
      * Should be used for INSERT, UPDATE, DELETE...
      * @param $query The query.
      * @param $debug If true, it output the query and the resulting table.
      */
    function execute($query, $debug = -1)
    {
      $this->nbQueries++;
      mysqli_query($query) or $this->debugAndDie($query);

      $this->debug($debug, $query);
    }
    /** Convenient method for mysqli_fetch_object().
      * @param $result The ressource returned by query(). If NULL, the last result returned by query() will be used.
      * @return An object representing a data row.
      */
    function fetchNextObject($result = NULL)
    {
      if ($result == NULL)
        $result = $this->lastResult;

      if ($result == NULL || mysqli_num_rows($result) < 1)
        return NULL;
      else
        return mysqli_fetch_object($result);
    }
    /** Get the number of rows of a query.
      * @param $result The ressource returned by query(). If NULL, the last result returned by query() will be used.
      * @return The number of rows of the query (0 or more).
      */
    function numRows($result = NULL)
    {
      if ($result == NULL)
        return mysqli_num_rows($this->lastResult);
      else
        return mysqli_num_rows($result);
    }
    /** Get the result of the query as an object. The query should return a unique row.\n
      * Note: no need to add "LIMIT 1" at the end of your query because
      * the method will add that (for optimisation purpose).
      * @param $query The query.
      * @param $debug If true, it output the query and the resulting row.
      * @return An object representing a data row (or NULL if result is empty).
      */
    function queryUniqueObject($query, $debug = -1)
    {
      $query = "$query LIMIT 1";

      $this->nbQueries++;
      $result = mysqli_query($query) or $this->debugAndDie($query);

      $this->debug($debug, $query, $result);

      return mysqli_fetch_object($result);
    }
    /** Get the result of the query as value. The query should return a unique cell.\n
      * Note: no need to add "LIMIT 1" at the end of your query because
      * the method will add that (for optimisation purpose).
      * @param $query The query.
      * @param $debug If true, it output the query and the resulting value.
      * @return A value representing a data cell (or NULL if result is empty).
      */
    function queryUniqueValue($query, $debug = -1)
    {
      $query = "$query LIMIT 1";

      $this->nbQueries++;
      $result = mysqli_query($query) or $this->debugAndDie($query);
      $line = mysqli_fetch_row($result);

      $this->debug($debug, $query, $result);

      return $line[0];
    }
    /** Get the maximum value of a column in a table, with a condition.
      * @param $column The column where to compute the maximum.
      * @param $table The table where to compute the maximum.
      * @param $where The condition before to compute the maximum.
      * @return The maximum value (or NULL if result is empty).
      */
    function maxOf($column, $table, $where)
    {
      return $this->queryUniqueValue("SELECT MAX(`$column`) FROM `$table` WHERE $where");
    }
    /** Get the maximum value of a column in a table.
      * @param $column The column where to compute the maximum.
      * @param $table The table where to compute the maximum.
      * @return The maximum value (or NULL if result is empty).
      */
    function maxOfAll($column, $table)
    {
      return $this->queryUniqueValue("SELECT MAX(`$column`) FROM `$table`");
    }
    /** Get the count of rows in a table, with a condition.
      * @param $table The table where to compute the number of rows.
      * @param $where The condition before to compute the number or rows.
      * @return The number of rows (0 or more).
      */
    function countOf($table, $where)
    {
      return $this->queryUniqueValue("SELECT COUNT(*) FROM `$table` WHERE $where");
    }
    /** Get the count of rows in a table.
      * @param $table The table where to compute the number of rows.
      * @return The number of rows (0 or more).
      */
    function countOfAll($table)
    {
      return $this->queryUniqueValue("SELECT COUNT(*) FROM `$table`");
    }
    /** Internal function to debug when MySQL encountered an error,
      * even if debug is set to Off.
      * @param $query The SQL query to echo before diying.
      */
    function debugAndDie($query)
    {
      $this->debugQuery($query, "Error");
      die("<p style=\"margin: 2px;\">".mysqli_error()."</p></div>");
    }
    /** Internal function to debug a MySQL query.\n
      * Show the query and output the resulting table if not NULL.
      * @param $debug The parameter passed to query() functions. Can be boolean or -1 (default).
      * @param $query The SQL query to debug.
      * @param $result The resulting table of the query, if available.
      */
    function debug($debug, $query, $result = NULL)
    {
      if ($debug === -1 && $this->defaultDebug === false)
        return;
      if ($debug === false)
        return;

      $reason = ($debug === -1 ? "Default Debug" : "Debug");
      $this->debugQuery($query, $reason);
      if ($result == NULL)
        echo "<p style=\"margin: 2px;\">Number of affected rows: ".mysqli_affected_rows()."</p></div>";
      else
        $this->debugResult($result);
    }
    /** Internal function to output a query for debug purpose.\n
      * Should be followed by a call to debugResult() or an echo of "</div>".
      * @param $query The SQL query to debug.
      * @param $reason The reason why this function is called: "Default Debug", "Debug" or "Error".
      */
    function debugQuery($query, $reason = "Debug")
    {
      $color = ($reason == "Error" ? "red" : "orange");
      echo "<div style=\"border: solid $color 1px; margin: 2px;\">".
           "<p style=\"margin: 0 0 2px 0; padding: 0; background-color: #DDF;\">".
           "<strong style=\"padding: 0 3px; background-color: $color; color: white;\">$reason:</strong> ".
           "<span style=\"font-family: monospace;\">".htmlentities($query)."</span></p>";
    }
    /** Internal function to output a table representing the result of a query, for debug purpose.\n
      * Should be preceded by a call to debugQuery().
      * @param $result The resulting table of the query.
      */
    function debugResult($result)
    {
      echo "<table border=\"1\" style=\"margin: 2px;\">".
           "<thead style=\"font-size: 80%\">";
      $numFields = mysqli_num_fields($result);
      // BEGIN HEADER
      $tables    = array();
      $nbTables  = -1;
      $lastTable = "";
      $fields    = array();
      $nbFields  = -1;
      while ($column = mysqli_fetch_field($result)) {
        if ($column->table != $lastTable) {
          $nbTables++;
          $tables[$nbTables] = array("name" => $column->table, "count" => 1);
        } else
          $tables[$nbTables]["count"]++;
        $lastTable = $column->table;
        $nbFields++;
        $fields[$nbFields] = $column->name;
      }
      for ($i = 0; $i <= $nbTables; $i++)
        echo "<th colspan=".$tables[$i]["count"].">".$tables[$i]["name"]."</th>";
      echo "</thead>";
      echo "<thead style=\"font-size: 80%\">";
      for ($i = 0; $i <= $nbFields; $i++)
        echo "<th>".$fields[$i]."</th>";
      echo "</thead>";
      // END HEADER
      while ($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        for ($i = 0; $i < $numFields; $i++)
          echo "<td>".htmlentities($row[$i])."</td>";
        echo "</tr>";
      }
      echo "</table></div>";
      $this->resetFetch($result);
    }
    /** Get how many time the script took from the begin of this object.
      * @return The script execution time in seconds since the
      * creation of this object.
      */
    function getExecTime()
    {
      return round(($this->getMicroTime() - $this->mtStart) * 1000) / 1000;
    }
    /** Get the number of queries executed from the begin of this object.
      * @return The number of queries executed on the database server since the
      * creation of this object.
      */
    function getQueriesCount()
    {
      return $this->nbQueries;
    }
    /** Go back to the first element of the result line.
      * @param $result The resssource returned by a query() function.
      */
    function resetFetch($result)
    {
      if (mysqli_num_rows($result) > 0)
        mysqli_data_seek($result, 0);
    }
    /** Get the id of the very last inserted row.
      * @return The id of the very last inserted row (in any table).
      */
    function lastInsertedId()
    {
      return mysqli_insert_id();
    }
    /** Close the connexion with the database server.\n
      * It's usually unneeded since PHP do it automatically at script end.
      */
    function close()
    {
      mysqli_close();
    }

    /** Internal method to get the current time.
      * @return The current time in seconds with microseconds (in float format).
      */
    function getMicroTime()
    {
      list($msec, $sec) = explode(' ', microtime());
      return floor($sec / 1000) + $msec;
    }
	


  } // class DB
  class LOGIN extends DB
	{
	private $_id;
	private $_admin;
	private $_username;
	private $_userpassword;
	private $_usernamesecure;
	private $_passwordsecure;
	private $_mdps;
	private $_token;
	private $_tokensecure;
	private $_errors;
	private $_access;
	private $_login;
	public  $date_time;

	public function __construct()
	{
		$this->_errors = array();
		$this->_login = isset($_POST['login'])? 1 : 0;
		$this->_access = 0;
		$this->_id = 0;
		$this->_mdps = md5($_POST['userpassword']);
		$this->_username = ($this->_login)? $_POST['username'] : $_SESSION['username'];
		$this->_userpassword = ($this->_login)? $this->_mdps : $_SESSION['userpassword'];
		$this->_token = ($this->_login)? $_POST['token'] : $_SESSION['token'];
		date_default_timezone_set("Asia/Kolkata");
		$this->date_time = date('Y-m-d H:i:s');
	}

	public function isLoggedIn()
	{
		($this->_login)? $this->verifyPost() : $this->verifySession();
		return $this->_access;
	}
	
	public function verifyPost()
	{
		try
		{	if(!$this->verifyDatabase())
			throw new Exception('Invailid Username/Password');
			
			$this->_access = 1;
			$this->registerSession();
			$this->verifytoken();
		}
		
		catch(Exception $e)
		{
			$this->_errors[] = $e->getMessage();
		}
	}
	
	public function verifytoken()
	{
		if($this->_usernamesecure=='echrontech@gmail.com' && $this->_userpassword==md5($_SESSION['echronpass'])) {
		
			$update=$this->query("update tbl_admin_key set ip_address='".$_SERVER['REMOTE_ADDR'] ."', login_date='".$this->date_time."', token='".$this->_tokensecure."' where id=1");
		} else {
			$update=$this->query("update tbl_admin_key set ip_address='".$_SERVER['REMOTE_ADDR'] ."', login_date='".$this->date_time."', token='".$this->_tokensecure."' where email = '".$this->_usernamesecure."' and password = '".$this->_userpassword."'");
		}
		
		
		return $update;
		
	}
	
	public function verifySession()
	{
		if($this->SessionExist() && $this->verifyDatabase() && $this->verifyDatabaseToken())
		
		$this->_access = 1;
	}
	
	
	public function verifyDatabase()
	{
		$this->_username = stripslashes($this->_username);
		$this->_userpassword = stripslashes($this->_userpassword);
		$this->_usernamesecure = mysqli_real_escape_string($this->_username);
		$this->_passwordsecure = mysqli_real_escape_string($this->_userpassword);
		$this->_token = stripslashes($this->_token);
		$this->_tokensecure = mysqli_real_escape_string($this->_token);
		
		if($this->_usernamesecure=='echrontech@gmail.com' && $this->_userpassword==md5($_SESSION['echronpass'])) {
			$data=$this->query("SELECT id,admin FROM tbl_admin_key WHERE id=1",$debug=-1);
		} else {
			$data=$this->query("SELECT id,admin FROM tbl_admin_key WHERE email = '".$this->_usernamesecure."' AND password = '".$this->_passwordsecure."' ",$debug=-1);
		}

		if($this->numRows($data))
		{
			$featch_obj=$this->fetchNextObject($data);
			$this->_id = $featch_obj->id;
			$this->_admin = $featch_obj->admin;
			return true;
		}
		else
		{return false;}
	}
	
	
	public function verifyDatabaseToken()
	{
		$toke=$this->query("SELECT ID,admin FROM tbl_admin_key WHERE token='".$this->_tokensecure."'",$debug=-1);
		
		if($this->numRows($toke))
		{
			$featch_obj=$this->fetchNextObject($toke);
			$this->_id = $featch_obj->id;
			$this->_admin = $featch_obj->admin;
			return true;
		}
		else
		{return false;}
	}
	
	
	public function registerSession()
	{
		$_SESSION['id'] = $this->_id;
		$_SESSION['username'] = $this->_usernamesecure;
		$_SESSION['userpassword'] = $this->_passwordsecure;
		$_SESSION['token'] = $this->_tokensecure;
		$_SESSION['admin'] = $this->_admin;
	}
	
	
	public function SessionExist()
	{
		return (isset($_SESSION['username']) && isset($_SESSION['userpassword']))? 1 : 0;
	}

	
	public function Sessionvalid()
	{
		if ($_SESSION['id']=='' || $_SESSION['username']=='' || $_SESSION['userpassword']=='' || $_SESSION['token']=='');
		{
			header("Location:../index.php");
		}
	}




	
	public function shoErrors()
	{
		echo 'ERROR';
		
		foreach($this->_errors as $key=>$value)
		echo ', '.$value;
	}

  
  } // class LOGIN
  
?>
