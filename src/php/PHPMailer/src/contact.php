<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

/*
*  CONFIGURATION
*/

// Recipients
$fromEmail = 'info@altcome.eu'; // Email address that will be in the from field of the message.
$fromName = 'altcome'; // Name that will be in the from field of the message.
$sendToEmail = 'info@altcome.eu'; // Email address that will receive the message with the output of the form
$sendToName = 'altcome'; // Name that will receive the message with the output of the form

// Subject
$subject = 'Message for ALTCOME';

// SMTP settings
$smtpUse = false; // Set to true to enable SMTP authentication
$smtpHost = ''; // Enter SMTP host ie. smtp.gmail.com
$smtpUsername = ''; // SMTP username ie. gmail address
$smtpPassword = ''; // SMTP password ie gmail password
$smtpSecure = 'tls'; // Enable TLS or SSL encryption
$smtpAutoTLS = false; // Enable Auto TLS
$smtpPort = 587; // TCP port to connect to

// Success and error alerts
$okMessage = 'We have received your inquiry. Stay tuned, we’ll get back to you very soon.';
$errorMessage = 'There was an error while submitting the form. Please try again later';

// Fields - Value of attribute name => Text to appear in the email
$fields = array(
'name' => 'Name:', 
'surname' => 'Surname:', 
'phone' => 'Phone:', 
'email' => 'Email:', 
'message' => 'Message:',  

'department' => 'Department:',
'company_name' => 'Сompany name:',
'industry' => 'Industry:',
'url_address' => 'Address url:',
'about_company' => 'About company:',
'positive_examples' => 'Positive examples:',
'negative_examples' => 'Negative examples:',

'type-1' => 'Site type:',
'type-2' => 'Site type:',
'type-3' => 'Site type:',
'type-4' => 'Site type:',
'type-5' => 'Site type:',
'type-6' => 'Site type:',
'type-7' => 'Site type:',
'type-8' => 'Site type:',
'type-9' => 'Site type:',
'type-10' => 'Site type:',
'type-11' => 'Site type:',
'type-12' => 'Site type:',
'type-13' => 'Site type:',
'type-14' => 'Site type:',
'type-15' => 'Site type:',

'design-1' => 'Design:',
'design-2' => 'Design:',
'design-3' => 'Design:',
'design-5' => 'Design:',
'design-6' => 'Design:',

'task-1' => 'Marketing service:',
'task-2' => 'Marketing service:',
'task-3' => 'Marketing service:',
'task-4' => 'Marketing service:',
'task-5' => 'Marketing service:',
'task-6' => 'Marketing service:',
'task-7' => 'Marketing service:',
'task-8' => 'Marketing service:',
'task-9' => 'Marketing service:',
'task-10' => 'Marketing service:',
);

/*
*  LET'S DO THE SENDING
*/

// if you are not debugging and don't need error reporting, turn this off by error_reporting(0);
error_reporting(E_ALL & ~E_NOTICE);
try {
  if(count($_POST) == 0) throw new \Exception('Form is empty');
  $emailTextHtml .= "<table>";
  foreach ($_POST as $key => $value) {
    // If the field exists in the $fields array, include it in the email
    if (isset($fields[$key])) {
      $emailTextHtml .= "<tr><th><b>$fields[$key]</b></th><td>$value</td></tr>";
    }
  }
  $emailTextHtml .= "</table>";
  $mail = new PHPMailer;
  $mail->setFrom($fromEmail, $fromName);
  $mail->addAddress($sendToEmail, $sendToName);
  $mail->addReplyTo($from);
  $mail->isHTML(true);
  $mail->CharSet = 'UTF-8';
  $mail->Subject = $subject;
  $mail->Body    = $emailTextHtml;
  $mail->msgHTML($emailTextHtml);
  if($smtpUse == true) {
    // Tell PHPMailer to use SMTP
    $mail->isSMTP();
    // Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->Debugoutput = function ($str, $level) use (&$mailerErrors) {
      $mailerErrors[] = [ 'str' => $str, 'level' => $level ];
    };
    $mail->SMTPDebug = 3;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = $smtpSecure;
    $mail->SMTPAutoTLS = $smtpAutoTLS;
    $mail->Host = $smtpHost;
    $mail->Port = $smtpPort;
    $mail->Username = $smtpUsername;
    $mail->Password = $smtpPassword;
  }
  if(!$mail->send()) {
    throw new \Exception('I could not send the email.' . $mail->ErrorInfo);
  }
  $responseArray = array('type' => 'success', 'message' => $okMessage);
}
catch (\Exception $e) {
  $responseArray = array('type' => 'danger', 'message' => $e->getMessage());
}
// if requested by AJAX request return JSON response
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  $encoded = json_encode($responseArray); 
  header('Content-Type: application/json');
  echo $encoded;
}
// else just display the message
else {
  echo $responseArray['message'];
}