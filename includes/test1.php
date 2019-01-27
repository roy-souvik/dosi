<?php
require("class.phpmailer.php");

class MyMailer extends PHPMailer {
    // Set default variables for all new objects
    var $From     = "debasis.das2006@email.com";
    var $FromName = "Mailer";
    var $Host     = "mail.hklifemap.com";
    var $Mailer   = "smtp";                         // Alternative to IsSMTP()
    var $WordWrap = 75;

    // Replace the default error_handler
    function error_handler($msg) {
        print("My Site Error");
        print("Description:");
        printf("%s", $msg);
        exit;
    }

    // Create an additional function
    function do_something($something) {
        // Place your new code here
    }
}



///----------------------------/////

$mail = new MyMailer;

// Now you only need to add the necessary stuff
$mail->AddAddress("hightechlabs@gmail.com", "Debasis Das");
$mail->Subject = "Here is the subject";

  $body  = "Hello <font size=\"4\">" . $row["full_name"] . "</font>, <p>";
    $body .= "<i>Your</i> personal photograph to this message.<p>";
    $body .= "Sincerely, <br>";
    $body .= "PHPMailer List manager";

    // Plain text body (for mail clients that cannot read HTML)
    $text_body  = "Hello " . $row["full_name"] . ", \n\n";
    $text_body .= "Your personal photograph to this message.\n\n";
    $text_body .= "Sincerely, \n";
    $text_body .= "PHPMailer List manager";

    $mail->Body    = $body;
    $mail->AltBody = $text_body;



//$mail->Body    = "This is the message body dddddddd";

//$mail->Body=$body;

//$mail->AddAttachment("c:/temp/11-10-00.zip", "new_name.zip");  // optional name

if(!$mail->Send())
{
   echo "There was an error sending the message";
   exit;
}

echo "Message was sent successfully";





















?>