<?php include('header.php'); ?>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<style>
.contact_form{
    float: right;
    width: 500px;
}
.info_holder{
line-height:25px;float:left;
width:280px;
text-align:justify;
}

.js_alert {
color:#F00;
font-size:11px;	
width: 170px;
}

.btn{
    background-color: #8E6434;
    border-radius: 5px 5px 5px 5px;
    border-style: none;
    color: #FFFFFF;
    float: right;
    font-size: 14px;
    height: 28px;
    margin-right: 11px;
    margin-top: 3px;
    width: 92px;
	cursor:pointer;
}	

::-webkit-input-placeholder { font-family:Arial;font-size:13px; color:#777777;}
::-moz-placeholder {font-family:Arial;font-size:13px; } /* firefox 19+ */
:-ms-input-placeholder {font-family:Arial;font-size:13px; } /* ie */
input:-moz-placeholder {font-family:Arial;font-size:13px; }
	
	
	
.focus {
    border: 2px solid #40280A;
    background-color: rgba(135, 93, 45,0.5);
	font-weight:bold!important;
	height:25px;
	font-size:14px;
}

textarea{
    font-family: Arial;
    font-size: 14px;
}

</style>
<script type="text/javascript">
  function formValidate(form,current_id)
		{	
			document.getElementById("name_required").innerHTML = '';
			document.getElementById("email_required").innerHTML = '';
			document.getElementById("phone_required").innerHTML = '';
			document.getElementById("message_required").innerHTML = '';

		
			var emailPattern =/^[a-zA-Z0-9._-]+@[a-zA-Z0-9]+([.-]?[a-zA-Z0-9]+)?([\.]{1}[a-zA-Z]{2,4}){1,4}$/;
			//var phonePattern = /^([\+]{1}[0-9]{2})?[1-9]{1}[0-9]{9}$/;

			var flag = 1;
			
			if(!form.name.value){
				document.getElementById("name_required").innerHTML = "Please Enter a Name";
				flag = 0;
			}
			

			if(!form.email.value || !emailPattern.test(form.email.value))
			{
					document.getElementById("email_required").innerHTML = "Please Enter a valid email";
					flag = 0;

			}
			
			if(!form.phone.value)
			{
				document.getElementById("phone_required").innerHTML = "Please Enter a valid mobile number";
				flag = 0;
			}
			
			if(!form.message.value){
				document.getElementById("message_required").innerHTML = "Please Enter a message";
				flag = 0;
			}
			

			if(flag == 0) {
				return false;
			} else {
				return true;
			}
		}
</script>	
<?php
if(isset($_POST['submit']) && $_POST['name']!="")   
{
		$name=trim($_POST["name"]);
		$email=trim($_POST["email"]);
		$phone=trim($_POST["phone"]);
		$comments=trim($_POST["message"]);

		$message="<fieldset><table>
					<tr><td>Name</td><td>:</td><td>".$name."</td></tr>
					<tr><td>Email</td><td>:</td><td>".$email."</td></tr>
					<tr><td>Phone</td><td>:</td><td>".$phone."</td></tr>
					<tr><td>Comments</td><td>:</td><td>".$comments."</td></tr>
				  </table></fieldset>";


	   $to = "dositextiles@gmail.com";
	   $subject = "Message: from ".$name;
	   $headers = 'From: '.$email. "\r\n";
	   $headers.= 'MIME-Version: 1.0' . "\r\n";
	   $headers.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	   
	   if(mail($to, $subject, $message, $headers))
	   {
			
			echo "<script> alert('Message Sent');</script>"; 
	   }
	   else 
	   {
			echo "<script> alert('Message Not Sent');</script>"; 
	   }

}	
?>
<body>
<div id="container">
    <div class="header">
    	<div class="logo"><a href="index.php"><img src="images/dosi_textiles.png" /></a></div>
        <div class="god-pic"><img src="images/god-picture.jpg" />
		<img src="images/shree-hanumate-namah.png" style="height: auto;margin-left: -6px;margin-top: 7px;width: 96px !important;"/></div>
        <div class="menu-bar">
        <ul>
			<li><a href="index.php" title="Home">Home</a></li>
			<li><a href="about_us.php" title="About us">About us</a></li>
			<li><a href="products.php" title="Products">Products</a></li>
			<li><a class="menu_active" href="contact_us.php" title="Contact us">Contact us</a></li>
        </ul>
        </div>
    </div>
    
    <div class="body_cont">
	<!--
    	<div class="banner">
        	<div class="banner_bar"></div>
            <div class="banner_frame"><img src="images/dosi_banner.jpg" /></div>
        </div>
    -->    
<div class="line"></div>

        <div class="body_content">
		
        	<h2>Contact Us</h2>
            <div class="info_holder">
				<h3>Visit us at: </h3>
				(Everyday 10:00 am to 6:00 pm)<br/>
				
				<table>
					<tr><td colspan="3"><h3>Dosi Textiles</h3></td></tr>
					<tr><td colspan="3">Near Clocktower, Sujangarh - 331507</td></tr>
					<tr><td>Dist.</td> <td>:</td><td> Churu, Rajasthan</td></tr>
					<tr><td>Ph.  </td> <td>:</td><td> 01568 224045</td></tr>
					<tr><td>Mob </td> <td>:</td><td>  +91 - 9414422049, 9828608096</td></tr>
					<tr><td>Skype</td> <td>:</td><td>  dosi.textiles</td></tr>
					<tr><td>Email</td> <td>:</td><td>  dositextiles@gmail.com</td></tr>
				</table>
			</div>
			
<script type="text/javascript" src="js/jquery-1.4.3.min.js"></script>  
<script type="text/javascript">
$(document).ready(function(){

  $("input").focus(function(){
    $(this).addClass("focus");
	
	var name=$(this).attr('name');
 	
	if(name=='name'){
	  var placeholder_text="Enter a name";
	}
	
	if(name=='email'){
	  var placeholder_text="Enter a valid email address";
	}
	
	if(name=='phone'){
	  var placeholder_text="Enter a valid phone number";
	}
	
  
  $(this).attr("placeholder",placeholder_text); 
  });

  $("input").blur(function(){
    $(this).removeClass("focus");
	$(this).attr("placeholder","");
  });

  
    $("textarea").focus(function(){
    $(this).addClass("focus");
	$(this).attr("placeholder","Enter a message");
	
  });

  $("textarea").blur(function(){
    $(this).removeClass("focus");
	$(this).attr("placeholder","");
  });
});
</script>
			
			<div class="contact_form">
			
			<h3>Get in Touch</h3> 
			<form action="" method="POST" onSubmit="return formValidate(this,'0');" name="contact_form">

			<table style="width:400px;">
			<tr><td>Name</td><td>:</td><td><input type="text" name="name" id="name"  style="width:300px;"/></td>
				<td width="100"><div id="name_required" class="js_alert"></div></td>
			</tr>
            
          
          
			<tr><td>Email</td><td>:</td><td><input type="text" name="email" style="width:300px;"/></td>
				<td><div id="email_required" class="js_alert"></div></td>
			</tr>
            

            <tr><td>Phone</td><td>:</td><td><input type="text" name="phone" style="width:300px;"/></td>
				<td><div id="phone_required" class="js_alert"></div></td>
			</tr>
                    
          
		  
            <tr><td>Message</td><td>:</td><td>
			
			<!-- <textarea rows="2" style="width:300px; height:150px;" id="content" name="message"></textarea> -->
			
			  <div id="content">Loading...</div>
			
			</td>
				<td><div id="message_required" class="js_alert"></div></td>
			</tr>
             
          	</table>
			
			<table><tr>
				<td style="padding-left: 32px;"><button style="margin-left:35px;" type="submit" value="SUBMIT" name="submit" class="btn" >SUBMIT</button></td>
				<td><button type="reset" value="RESET" name="reset" class="btn">RESET</button></td>
				</tr>
				
			</table>

       </form>
	   
<script src="https://www.google.com/jsapi" type="text/javascript"></script>
    <script type="text/javascript">
    google.load("elements", "1", {packages: "keyboard"});
   
    function onLoad() {
      var content = document.getElementById('content');
      // Create the HTML for out text area
      content.innerHTML = '<textarea id="textCode" name="textCode" style="width:300px; height:150px;" rows="2" name="message"></textarea>';
   
      var kbd = new google.elements.keyboard.Keyboard(
          [google.elements.keyboard.LayoutCode.HINDI],
          ['textCode']);
    }
   
    google.setOnLoadCallback(onLoad);
   
    </script>
	   
	   
	   
	   
  </div> <!-- End of contact_form -->
			
			<div style="clear:both;"></div>
			
        </div>
		
 <div class="line"></div>
    </div>
    
    
<?php include('footer.php'); ?>
