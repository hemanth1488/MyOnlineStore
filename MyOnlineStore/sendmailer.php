<?php
require("PHPMailer/class.phpmailer.php");
include("PHPMailer/class.smtp.php");
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPDebug = 1; 
$mail->SMTPSecure = 'ssl'; 
$mail->Mailer = "smtp";

$mail->Host = "smtp.gmail.com";
 $mail->Port = 465;

$mail->SMTPAuth = true;
$mail->Username = 'hemanths1488';
$mail->Password = 'sukumaran';
$mail->From     = "hemanths1488@gmail.com";
$mail->AddAddress("hemanth.s@hcl.com");
$mail->Subject = "Test 1";
$mail->Body = "Test 1 of PHPMailer.";

if(!$mail->Send())
{
   echo "Error sending: " . $mail->ErrorInfo;;
}
else
{
   echo "Letter is sent";
}?>
