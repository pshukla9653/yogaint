<?php include('admin/lib/dbconf.php'); ?>
<?php


if(isset($_POST['action'])=="send_enquiry") {
	
	
		$name =  mysqli_real_escape_string($_POST['name']);
    $email =  mysqli_real_escape_string($_POST['email']); 
    $count_code =  mysqli_real_escape_string($_POST['count_code']);
    $phone =  mysqli_real_escape_string($_POST['phone']);
    $age =  mysqli_real_escape_string($_POST['age']);
    $gender =  mysqli_real_escape_string($_POST['gender']);
    $nation =  mysqli_real_escape_string($_POST['nation']);
   
    $operator =  mysqli_real_escape_string($_POST['operator']);
    $interest =mysqli_real_escape_string($_POST['interest']);
    $date =  $_POST['date'];
    $interest_id = array_keys($interest_ids, $interest);

    
    foreach ($date as $arr){ 
      $dateVal .= "<li>".$arr."</li>";
    }

    
    foreach ($interest as $arr){ 
      $interestVal .= "<li>".$arr."</li>";
    }
		
		$mailsubject = 'IIA GROUP';
		
    
	
		$obj->query("insert into tbl_enquiry set name='".$name."', email='".$email."', count_code='".$count_code."', phone='".$phone."', age='".$age."', gender='".$gender."', operator='".$operator."',  nation='".$nation."',  interest='".$interest."', date='".$dateVal."'");



  // $mess= "<table width='96%' cellspacing='0' cellpadding='2' border='0'>
	// <tr><td>IIA Contact Form<br/><br/></td></tr>
	// <tr><td>You have received an enquiry. Details of which are as follows: <br /><br />";
  // $mess.= "Name : ".$_POST['name']."<br /><br />";
	// $mess.= "Email : ".$_POST['email']."<br /><br />";
  // $mess.= "Phone : ".$_POST['phone']."<br /><br />";
  // $mess.= "Alt Phone : ".$_POST['altphone']."<br /><br />";
  // $mess.= "City : ".$_POST['city']."<br /><br />";
  // $mess.= "Qualification : ".$_POST['qualification']."<br /><br />";
  // $mess.= "Course : ".$_POST['course']."<br /><br />";
	// $mess.= "Message : ".$_POST['message']."<br /><br />";
	// $mess.= "Thanks<br>Team<br>iiagroup.co.in</strong></td></tr>
	//  <tr><td>We have received your enquiry. We will contact you very soon.<br /><br />Thanks<br>Team<br>info@iiagroup.co.in
	 	
  // </table>";
  
  // send_mail($mess,$subject);


//   $_SESSION['thanks']='
//   <div class="popup">
//   <div class="thank-wid">
//   <div class="thankyou">
//     <div class="thankarea"><i class="fa fa-thumbs-up"></i></div>
//     <h1>Thank You!<br><small><strong></strong></small></h1>
//     <br>
//     Thanks for the registration. 
// We will get back to you soon.
//     <p>Email:- <strong> contactus@internationalyogfestival.com</strong></p>
//   </div>
// </div>
//   </div>
// 			';
}
 
?>



<!doctype html>
<html>



<link rel="stylesheet" href="css/style.css?<?php echo time()?>">
<link rel="stylesheet" href="css/font-awesome.min.css " />

<body>
  <?php include('includes/header.php'); ?>

  <div class="thank-wid">
    <div class="thankyou">
      <div class="thankarea"><i class="fa fa-thumbs-up"></i></div>
      <h1>Thank You!<br><small><strong></strong></small></h1>
      <br>
      Thank you for registering for the International Yog Festival 2023. Kindly bring your original passport (mandatory for foreign nationals) /government ID proof at the venue for issuance of entry badge and reach 30 min before scheduled time.
      <p>Email:- <strong> contactus@internationalyogfestival.com</strong></p>
    </div>
  </div>
  

  <?php include('includes/footer.php'); ?>



  <div id="top-link-block" class="hidden">

    <a href="#top" class="well well-sm" onClick="$('html,body').animate({scrollTop:0},'slow');return false;"><i
        class="fa fa-arrow-up"></i></a>

  </div>
  <script src="js/jquery.js"></script>

  <script src="js/slick.js" type="text/javascript" charset="utf-8"></script>




  <script src="js/wow.min.js"></script>

  <script src="js/my_script.js"></script>



  <script>
    
    window.setTimeout(function() {
    window.location.href = 'http://localhost/yogaint/';
}, 5000);
  </script>
</body>

</html>