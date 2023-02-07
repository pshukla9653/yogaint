<?php
error_reporting(0);
$pathlocal=$_SERVER['HTTP_HOST'];

if ($pathlocal == "localhost") {
    define('LOCAL_MODE', true);
}
else {
 	define('LOCAL_MODE', false);
}

if ($pathlocal == "localhost") {
	define(DBSERVER,"localhost");
	define(DBNAME,"yogaint");
	define(DBUSER,"root");
	define(DBPASS,"");
	define(MYDOMAIN,"http://".$pathlocal."/projects/yoga/admin/");
	define(HDOMAIN,"http://".$pathlocal."/projects/yoga/");
	define(APIKEY,"test_89526458d285939d46984b9090a");
	define(AUTHKEY,"test_adb8f5fb3f7e110a4c062499d05");
	define(APIURL,"https://test.instamojo.com/api/1.1/");
} else {

	define(DBSERVER,"localhost");
	define(DBNAME,"yogaint");
	define(DBUSER,"yogaintuser");
	define(DBPASS,"h&#%tK&OTi#g");
	define(MYDOMAIN,"http://internationalyogfestival.com/admin/");
	define(HDOMAIN,"http://internationalyogfestival.com/");
	define(APIKEY,"8d19a7c7f7db58802631cb40ba56dcef");
	define(AUTHKEY,"b49facc33f0c052c6d38090916bc175a");
	define(APIURL,"https://www.instamojo.com/api/1.1/");
}



define('PAGENAME', basename($_SERVER['PHP_SELF']));
include_once("database.php");
$obj=new DB(DBNAME,DBSERVER,DBUSER,DBPASS);

include_once('phpmailer.php');
define('SMTP_HOST', 'localhost');
define('SMTP_PORT', '25');
define('SMTP_USER', 'echron@iiagroup.co.in');
define('SMTP_PASS', 'echron@2018');

define('FILENAME', basename($_SERVER['PHP_SELF'], '.php'));
define('COMPNAME', $obj->item_id('1','tbl_profile','name'));

$interest_ids =  array(
	1 => 'Krishnamacharya Yoga Mandiram',
	2 => 'Isha Foundation',
	3 => 'Ramamani Iyengar Memorial Yoga Institute',
	4 => 'Kaivalyadhama Yoga Institute',
	5 => 'The Art of Living',
  
  );
  
  $dates_ids = array(
	1 => '1 march 2023',
	2 => '2 march 2023',
	3 => '3 march 2023',
	4 => '4 march 2023',
	5 => '5 march 2023',
	6 => '6 march 2023',
	7 => '7 march 2023',
  
  );
  


function send_mail($message,$subject) {
	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->Host = SMTP_HOST;
	$mail->SMTPAuth = true;
	$mail->Username = SMTP_USER; 
	$mail->Password = SMTP_PASS;
	$mail->Port = SMTP_PORT;
	$mail->From = 'info@iiagroup.co.in';
	$mail->FromName = 'IIA Group Form';
	$mail->addAddress('echrontech@gmail.com');
	$mail->Subject = 'Echrontech Website Enquiry';
	$mail->Body    = $message;
	$mail->AltBody    = $message;
	$mail->isHTML(true);
	if(!$mail->Send()) { echo 'Your Request not sent due to technical problem please try few days later'; } else { echo ''; }
}
function send_mail_smtp($message, $to, $subject)
{
	$headers = "From: info@echrontech.com" . "\r\n";
	$headers .= "MIME-Version: 1.0" . "\r\n";
	$headers .= "X-Priority: 3\r\n";
	$headers .= "X-Mailer: smail-PHP " . phpversion() . "\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
	$success = mail($to, $subject, $message, $headers);

	return $success;
}
define('SITE_KEY',"6LfuyacUAAAAAPfatY4P761NBXrbRvc98Bq9P5k-"); 
define('SECRET_KEY',"6LfuyacUAAAAALHaaX5cveSuL8dKHwgGQz5sSDdq");

date_default_timezone_set("Asia/Kolkata");
$date_time = date('Y-m-d H:i:s');

 
$postoption = array(
    'Meta Title' => 'text,l8',
    'Meta Keyword' => 'textarea,l4',
    'Meta Description' => 'textarea,l9',
    'Meta Follow' => 'select,l3',
    'Home Name' => 'text,l6',
    'Home Description' => 'textarea,l6',
    'Order No' => 'text,l6'
);

$sectionoption = array(
    'Option 0' => 'textarea,l4',
    'Option 1' => 'textarea,l4',
    'Option 2' => 'textarea,l4',
    'Option 3' => 'textarea,l4',
    'Option 4' => 'textarea,l4',
    'Option 5' => 'textarea,l4'
);
function arrylist($text,$link) {
    $textloop=explode(",", $text);
    foreach($textloop as $output) {
        $intextloop=explode("|", $output);
        if($link=='link') $echo.='<li><a href="'.$intextloop[1].'">'.$intextloop[0].'</a></li>';
        else $echo.='<li>'.$intextloop[0].'</li>';
    }
    return $echo;
}
function arrylistone($text,$link,$value,$class) {
    $textloop=explode(",", $text);
        $intextloop=explode("|", $textloop[$value]);
        if($link=='link') $echo.='<a class="'.$class.'" href="'.$intextloop[1].'">'.$intextloop[0].'</a>';
        else $echo.=$intextloop[0];
    return $echo;
}

function send_otp_sms($phone, $message)
{
	$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://2factor.in/API/V1/162e0812-b6bc-11ea-9fa5-0200cd936042/SMS/".$phone."/".$message."/IIA Group Registration OTP",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "",
  CURLOPT_HTTPHEADER => array(
    "content-type: application/x-www-form-urlencoded"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
}

function dateitme($columns) {
$date = date_create($date=$columns);
return date_format($date, 'D, j/M/y, g:ia');
}
function dates($columns) {
$date = date_create($date=$columns);
return date_format($date, 'D, j/M/y');
}
function datesyear($columns) {
$date = date_create($date=$columns);
return date_format($date, 'Y');
}
function echronSpaceLoop($level) {
	for($sn=0; $sn<=$level; ++$sn) {
		$echronSpace .= '&nbsp;';
	}
	return $echronSpace;
}
function ehcronLpadLoop($echronSn, $type, $table, $child_id) {
	for($sns=0; $sns<=$echronSn; ++$sns) {
		$echronlpad .= "LPAD(echron".$sns.".id, 5, '0'), '.', ";

		$echsn = $sns -1;
		
		if($sns==0)
		$echronjoint .="";

		else
		
		$echronjoint .=" INNER JOIN ".$table." echron".$sns." ON (echron".$echsn.".id = echron".$sns.".".$child_id.")";
	}

	if($type=='lpad') $echo = substr($echronlpad, 0, -7);
	else if($type=='joint') $echo = $echronjoint;

	return $echo;
}
function echronForeach($array) {
	foreach($array as $key => $value) {
		
		$echronValue .= $key==0?  ltrim($value, '0') : ','.ltrim($value, '0');
	}
	return $echronValue;
}
function num_secunce($columns) {
	if($columns!=='') {
	$num_secunce=number_format($columns, 0, '.', ',');
	} else {
		$num_secunce='Not Mention';
	}
	
	return $num_secunce;
}
function file_size($bytes) {
	if ($bytes >= 1073741824)
	{
		$bytes = (int)number_format($bytes / 1073741824, 2) . ' GB';
	}
	elseif ($bytes >= 1048576)
	{
		$bytes = (int)number_format($bytes / 1048576, 2) . ' MB';
	}
	elseif ($bytes >= 1024)
	{
		$bytes = (int)number_format($bytes / 1024, 2) . ' KB';
	}
	elseif ($bytes > 1)
	{
		$bytes = (int)$bytes . ' bytes';
	}
	elseif ($bytes == 1)
	{
		$bytes = (int)$bytes . ' byte';
	}
	else
	{
		$bytes = '0 bytes';
	}
	return $bytes;
}
function chremove($string) {
	$string=strtolower($string);
	$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

	return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
function mailformate($fromphone,$frommail,$frommess) {
	$mess = "
	<html>
	<head>
	</head>
	<body style='font-family:Arial, sans-serif'>
	<div id='wapper' style='margin:auto; width:75%;'>
	<div class='logo' style='background:#f1f1f1; padding:10px; text-align:center;'>
	<img src='https://www.njob.in/images/logo.png' width='200'></div>
	<h2 style='font-size:18px;'>Welcome to njob.in</h2>
	<p>
	".$frommess."
	</p>
	
	
	<p>
	Need assistance<br>
	Ask team <strong>njob.in</strong><br>
	Email:- ".$frommail."<br>
	Phone:- ".$fromphone."
	</p>
	</div>
	</body>
	</html>
		";
	return $mess;
}
function send_sms($mobile, $msg){
	// Get cURL resource
	$curl = curl_init();
	$msg = urlencode($msg);
	$url = 'http://sms6.routesms.com/bulksms/bulksms?username=jrntran&password=jrntran1&type=0&dlr=1&destination='.$mobile.'&source=IIADEL&message='.$msg;
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $url,
		CURLOPT_USERAGENT => 'Sample cURL Request'
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	// Close request to clear up some resources
	curl_close($curl);
}
function generateFormToken($form) {
    
	// generate a token from an unique value, took from microtime, you can also use salt-values, other crypting methods...
	$token = md5(uniqid(microtime(), true));  
	
	// Write the generated token to the session variable to check it against the hidden field when the form is sent
	$_SESSION[$form.'_token'] = $token; 
	
	return $token;
}
$yrs=date("Y");

/************************************frontend************************************/
function heading($head) {
	if($head==1) $heading='Website Dovelopement';
	elseif($head==2) $heading='Website Designing';
	elseif($head==3) $heading='Digital Marketing';
	elseif($head==4) $heading='Graphics Designing';
	
	return $heading;
}
function head() { ?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="content-language" content="en" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="Echrontech designing Development"/>
<meta name="rating" content="General" />
<meta name="Distribution" content="Global" />
<meta name="subject" content="Best designer &amp; developer in Delhi NCR" />
<meta name="contactName" content="Saleemuddin Azam" />
<meta name="contactOrganization" content="Echrontech designing Development" />
<meta name="contactStreetAddress1" content="K-113, Krishna Park Extn, New Delhi, India." />
<meta name="contactZipcode" content="110018" />
<meta name="contactCity" content="New Delhi" />
<meta name="contactState" content="Delhi" />
<meta name="contactCountry" content="India" />
<meta name="contactPhoneNumber" content="+91 9560676728" />
<meta name="contactNetworkAddress" content="echrontech@gmail.com" />
<meta name="linkage" content="<?php echo HDOMAIN; ?>" />
<link rel="shortcut icon" type="image/x-icon" href="<?php echo HDOMAIN; ?>images/favicon.png" />
<link rel="stylesheet" type="text/css" href="<?php echo HDOMAIN; ?>css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo HDOMAIN; ?>css/style.css" media="screen">
<link rel="stylesheet" type="text/css" href="<?php echo HDOMAIN; ?>css/animate.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="<?php echo HDOMAIN; ?>js/html5shiv.js"></script>
        <script src="<?php echo HDOMAIN; ?>js/respond.min.js"></script>
	<![endif]-->
	
	<!-- WhatsHelp.io widget -->
<script type="text/javascript">
    (function () {
        var options = {
            whatsapp: "+919560676728", // WhatsApp number
            call_to_action: "", // Call to action
            position: "left", // Position may be 'right' or 'left'
        };
        var proto = document.location.protocol, host = "getbutton.io", url = proto + "//static." + host;
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
        s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
        var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
    })();
</script>
<!-- /WhatsHelp.io widget -->
    
<?php }
function foot() { ?>
<div id="top-link-block" class="hidden affix-top">
    <a href="#top" class="well well-sm" onclick="$('html,body').animate({scrollTop:0},'slow');return false;">↑</a>
</div><!-- /top-link-block -->
<div class="se-pre-con"><span>Please Wait...<br><img src="<?php echo HDOMAIN; ?>images/echrontech-final-logo.png" alt="Echrontech"/></span></div>
<script src="<?php echo HDOMAIN; ?>js/jquery.js"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-120718957-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-120718957-1');
</script>
<script type="application/ld+json">
{
"@context": "http://schema.org",
"@type": "ProfessionalService",
"name": "echrontech",
"address": {
"@type": "PostalAddress",
"streetAddress": "K-113, 2nd Floor, Krishna Park extn, Outer Ring Road, Near Pillar No. 4",
"addressLocality": "New Delhi",
"addressRegion": "Delhi",
"postalCode": "110018"
},
"image": "https://echrontech.com/images/echrontech-about.jpg",
"telePhone": "+91-9599970508",
"url": "https://www.echrontech.com/",

"logo": "https://echrontech.com/images/echrontech-final-logo.png",

"sameAs": [
"https://www.facebook.com/echrontech",
"https://twitter.com/echrontech",
"https://www.linkedin.com/company/echrontech/",
"https://in.pinterest.com/echrontech/" ],

"description": "Echrontech is a promising and trusted web designing, web development, graphic design and digital marketing company whose priority is complete customer satisfaction.",
"paymentAccepted": [ "cash", "check", "invoice", "paypal" ],
"openingHours": "Mo,Tu,We,Th,Fr,St 10:00-19:00",
"geo": {
"@type": "GeoCoordinates",
"latitude": "28.6744576",
"longitude": "77.2120576"
},
"priceRange":"$$$$"
},
}
</script>
<script src="<?php echo HDOMAIN; ?>js/wow.min.js"></script>
<script src="<?php echo HDOMAIN; ?>js/jquery-inertiaScroll.js"></script>
<script src="<?php echo HDOMAIN; ?>js/my_script.js"></script>

<?php }
function contactform() {
    $echo = '
    <div class="contact cform">
        <div class="container">
            <div class="form">
                <h3>Request A Call Back</h3>
                <input placeholder="* Name" name="name" type="text">
                <input placeholder="Company" name="company" type="text">
                <input placeholder="* Email" name="email" type="text">
                <input placeholder="* Phone Number" name="phone" type="text">
                <textarea rows="2" placeholder="Message" class="messages"></textarea>
                <div class="g-recaptcha" data-sitekey="'.SITE_KEY.'"></div>
                <button id="cform">Submit</button>
                <div class="loader"><img src="'.HDOMAIN.'images/icons/loader.gif" alt=""/></div>
                <div class="form-msg"></div>
            </div>
        </div>
    </div>
    ';

    return $echo;
}
/************************************backhend************************************/
function head_b() { ?>
<meta name="robots" content="NOINDEX, NOFOLLOW" />
<title><?php echo COMPNAME; ?></title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo MYDOMAIN; ?>css/style.css">
<link rel="shortcut icon" type="image/x-icon" href="<?php echo MYDOMAIN; ?>images/fav.png" />

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="<?php echo MYDOMAIN; ?>js/html5shiv.js"></script>
        <script src="<?php echo MYDOMAIN; ?>js/respond.min.js"></script>
    <![endif]-->
<?php }
function headers_b($logtime) { ?>
<header>
    <ul>
    	<li class="pull-left"><a href="index.php"><?php echo COMPNAME; ?></a></li>
		<li ><a href="javascript:void()" onclick="profileDropDown()" ><i class="fa fa-user" aria-hidden="true"></i> <i class="fa fa-angle-double-down" aria-hidden="true"></i></a>
		<ul id="toggle">
    	<li ><a href="index.php"> Signed in as <span> <?php echo COMPNAME; ?></span></a></li>
		<li><a href="profile.php"><i class="fa fa-user" aria-hidden="true"></i> Your profile </a></li>
		<li><a href="#"> <i class="fa fa-info-circle" aria-hidden="true"></i> Help </a></li>
        <li><a href="lib/logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Sign out</a></li>
    </ul></li>
		
        <li><a><i class="fa fa-clock-o "></i> Last Logout: <?php echo $logtime; ?></a></li>
    </ul>
</header>
<div class="header-mar"></div>
<?php }
function leftbar_b() { ?>
<div class="sidebar">
    <div class="user-info"><img src="images/admin-logo.png" alt="User"></div>
    <nav class="sidebar-nave">
        <h3>MAIN NAVIGATION</h3>
        <ul>
        <!-- <li <?php if(PAGENAME=='home.php') echo 'class="active"'; ?>>
            <div class="head"><i class="fa fa-tachometer"></i><span>Dashboad</span></div>
            <ul <?php if(PAGENAME=='home.php') echo 'class="active"'; ?>>
                <li <?php if(PAGENAME=='home.php') echo 'class="active"'; ?>><i class="fa fa-home"></i> <a href="home.php">Home</a></li>
            </ul>
        </li> -->
        <li <?php if(PAGENAME=='img-lib.php') echo 'class="active"'; ?>>
            <div class="head"><i class="fa fa-book"></i><span>Files</span></div>
            <ul <?php if(PAGENAME=='img-lib.php') echo 'class="active"'; ?>>
                <!-- <li <?php if(PAGENAME=='img-lib.php') echo 'class="active"'; ?>><i class="fa fa-file-image-o"></i> <a href="img-lib.php">Image Libary</a></li> -->
								<li <?php if(PAGENAME=='enquiry.php') echo 'class="active"'; ?>><i class="fa fa-question-circle" aria-hidden="true"></i> <a href="enquiry.php">Enquiry</a></li>
            </ul>
        </li>
        <!-- <li <?php if(PAGENAME=='post.php' || PAGENAME=='category.php' || PAGENAME=='tag.php' || PAGENAME=='page.php' || PAGENAME=='section.php' || PAGENAME=='comment.php') echo 'class="active"'; ?>>
            <div class="head"><i class="fa fa-th"></i><span>Portal</span></div>
            <ul <?php if(PAGENAME=='post.php' || PAGENAME=='category.php' || PAGENAME=='tag.php' || PAGENAME=='page.php' || PAGENAME=='section.php' || PAGENAME=='comment.php') echo 'class="active"'; ?>>
                <li <?php if(PAGENAME=='post.php') echo 'class="active"'; ?>><i class="fa fa-share-square"></i> <a href="post.php">Post</a></li>
                <li <?php if(PAGENAME=='category.php') echo 'class="active"'; ?>><i class="fa fa-columns "></i> <a href="category.php"> Category</a></li>
				<li <?php if(PAGENAME=='page.php') echo 'class="active"'; ?>><i class="fa fa-file-text-o "></i> <a href="page.php">Page</a></li>
				<li <?php if(PAGENAME=='tag.php') echo 'class="active"'; ?>><i class="fa fa-tags "></i> <a href="tag.php">Tag</a></li>
                <li <?php if(PAGENAME=='section.php') echo 'class="active"'; ?>><i class="fa fa-list "></i> <a href="section.php">Section</a></li>
                <li <?php if(PAGENAME=='comment.php') echo 'class="active"'; ?>><i class="fa fa-comment "></i> <a href="comment.php">Comment</a></li>
            </ul>
        </li>
		<li <?php if(PAGENAME=='ppost.php' || PAGENAME=='pcategory.php') echo 'class="active"'; ?>>
            <div class="head"><i class="fa fa-briefcase"></i><span>Portfolio</span></div>
            <ul <?php if(PAGENAME=='ppost.php' || PAGENAME=='pcategory.php') echo 'class="active"'; ?>>
                <li <?php if(PAGENAME=='ppost.php') echo 'class="active"'; ?>><i class="fa fa-share-square"></i> <a href="ppost.php">Post</a></li>
                <li <?php if(PAGENAME=='pcategory.php') echo 'class="active"'; ?>><i class="fa fa-columns "></i> <a href="pcategory.php">Category</a></li>
            </ul>
        </li>
		<li <?php if(PAGENAME=='bpost.php' || PAGENAME=='bcategory.php' || PAGENAME=='btag.php' || PAGENAME=='bcomment.php') echo 'class="active"'; ?>>
            <div class="head"><i class="fa fa-rocket"></i><span>Blogs</span></div>
            <ul <?php if(PAGENAME=='bpost.php' || PAGENAME=='bcategory.php' || PAGENAME=='btag.php' || PAGENAME=='bcomment.php') echo 'class="active"'; ?>>
                <li <?php if(PAGENAME=='bpost.php') echo 'class="active"'; ?>><i class="fa fa-share-square"></i> <a href="bpost.php">Post</a></li>
                <li <?php if(PAGENAME=='bcategory.php') echo 'class="active"'; ?>><i class="fa fa-columns "></i> <a href="bcategory.php">Category</a></li>
				<li <?php if(PAGENAME=='btag.php') echo 'class="active"'; ?>><i class="fa fa-tags "></i> <a href="btag.php"> Tag</a></li>
                <li <?php if(PAGENAME=='bcomment.php') echo 'class="active"'; ?>><i class="fa fa-comment "></i> <a href="bcomment.php">Comment</a></li>
            </ul>
        </li> -->
		
        </ul>
    </nav>
</div>
<?php }
function footer_b() { ?>
<!-- jQuery -->
<script src="<?php echo MYDOMAIN; ?>js/jquery.js"></script>
<script src="<?php echo MYDOMAIN; ?>js/my_script.js"></script>
<script>

<?php }
?>