<?php 
	include('lib/dbconf.php');
	$obj->loginhome();
	$newToken = generateFormToken('form1');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
       <?php head_b(); ?>
    </head>
    <body>

        <div class="login-page">
            <div class="login-mainpanel">
                <div class="login-form">
					<input type="hidden" id="token" value="<?php echo $newToken; ?>">
                    <img width="200" src="<?php echo MYDOMAIN ; ?>/images/admin-logo.png" alt="login">
                    <h2><?php echo COMPNAME; ?></h2>
                    <div class="input-group">
                        <input class="input-panel" type="text" id="username" placeholder="Username">
                        <span class="focus-inputpanel"></span>
                    </div>
                    <div class="input-group">
                        <input class="input-panel" type="password" id="userpassword" placeholder="Password">
                        <span class="focus-inputpanel"></span>
                    </div>
                    <div class="ajaxloader"><img src="<?php echo MYDOMAIN ; ?>/images/icons/load.gif" alt=""></div>
                    <button class="login-btn" id="cl_login">
                        Login
                    </button>
                    <a class="bttn" href="javascript:void()">Forgot Password ?</a>
                </div>
            </div>
        </div>
        <div class="log-pop">
        <div class="frg_pop_login fgt-paswrd">
       <a href="index.php"> <i class="fa fa-times " aria-hidden="true"></i></a>
            <h3>Forgot password</h3>
            <span class="fgt-pas">please enter your email id</span>
            <div class="frg_input">
            <i class="fa fa-envelope" aria-hidden="true"></i> <input type="text" class="input-panel pop email"placeholder="email">
            </div>
            <div class="show-sorry-msg pass-msg"></div>
            <div class="ajaxloader"><img src="<?php echo MYDOMAIN ; ?>/images/icons/load.gif" alt=""></div>
            
            <button class="login-btn cl_fgt_passw" >Submit</button>
                    have an account?
                    <a class="bttn-f" href="index.php">Sign in </a>
        </div>
        <div class="frg_pop_login otp">
            
        <a href="index.php"> <i class="fa fa-times " aria-hidden="true"></i></a>
            <h3>OTP</h3>
            <span class="fgt-pas">please enter your OTP<br> OTP sent to your MAIL ID</span>
            <div class="frg_input">
            <i class="fa fa-key" aria-hidden="true"></i> <input type="text" class="input-panel otp_code pop" placeholder="enter OTP">
            </div>
            <div class="show-sorry-msg pass-msg"></div>
            <div class="ajaxloader"><img src="<?php echo MYDOMAIN ; ?>/images/icons/load.gif" alt=""></div>
           <button class="login-btn cl_otp_pass">Enter</button>
                    Go back
                    <a class="bttn-f" href="index.php">Sign in </a>
              
        </div>
        <div class="frg_pop_login crt_pass">
            
        <a href="index.php"> <i class="fa fa-times " aria-hidden="true"></i></a>
            <h3>Create new password</h3>
            <span class="crt-n-pass">New Password</span>
             <input type="password" class="input-panel newpass crt_p" placeholder="New Password">
             <span class="crt-n-pass">Confirm Password</span>
             <input type="password" class="input-panel conpass crt_p" placeholder="Confirm Password">
             <div class="show-sorry-msg "></div>
            <div class="ajaxloader"><img src="<?php echo MYDOMAIN ; ?>/images/icons/load.gif" alt=""></div>
           <button class="login-btn cl_crt_pass">
                        Enter
                    </button>
                 
        </div>
        </div>
     <?php footer_b(); ?>  
    </body>
</html>
