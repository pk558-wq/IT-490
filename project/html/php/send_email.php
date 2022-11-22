<?php
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
require_once 'vendor/autoload.php';
include_once "config.php";

function send_email($to, $to_email, $subject, $body, $isHTML) {

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';  //gmail SMTP server
$mail->SMTPAuth = true;
//to view proper logging details for success and error messages
// $mail->SMTPDebug = 1;
$mail->Host = 'smtp.gmail.com';  //gmail SMTP server
$mail->Username = GMAIL_ACCOUNT;   //email
$mail->Password = GMAIL_APP_PASSWORD;   //16 character obtained from app password created
$mail->Port = 465;                    //SMTP port
$mail->SMTPSecure = "ssl";
$from_email=GMAIL_ACCOUNT;
$from=GMAIL_USER_NAME;

//sender information
$mail->setFrom($from_email, $from);

//receiver email address and name
$mail->addAddress($to_email, $to); 

// Add cc or bcc   
// $mail->addCC('email@mail.com');  
// $mail->addBCC('user@mail.com');  
 
 
$mail->isHTML($isHTML);
 
$mail->Subject = $subject;
$mail->Body    = $body;

// Send mail   
if (!$mail->send()) {
    echo 'Email not sent an error was encountered: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent.';
}

$mail->smtpClose();
}

//this was a test
//send_email("Pallavi", "pmkanoor@gmail.com", "testing function", "simple test", false);

?>