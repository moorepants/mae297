<?php

if(!defined('STDOUT')) define('STDOUT', fopen('php://stdout', 'w'));

$timestamp = date( "Y-m-d H:i:s", mktime(0, 0, 0));

// Check for empty fields
if(empty($_POST['name']) ||
   empty($_POST['org']) ||
   empty($_POST['email']) ||
   empty($_POST['phone']) ||
   empty($_POST['title']) ||
   empty($_POST['message']) ||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
   echo "No arguments Provided!";
   return false;
   }

$name = $_POST['name'];
$org = $_POST['org'];
$email_address = $_POST['email'];
$phone = $_POST['phone'];
$title = $_POST['title'];
$message = $_POST['message'];

// http://www.html-form-guide.com/email-form/php-email-form-attachment.html

//Get the uploaded file information
$name_of_uploaded_file = basename($_FILES['file']['name']);

//get the file extension of the file
$type_of_uploaded_file =
    substr($name_of_uploaded_file,
    strrpos($name_of_uploaded_file, '.') + 1);

$size_of_uploaded_file =
    $_FILES["file"]["size"]/1024;//size in KBs

//Settings
$max_allowed_file_size = 5000; // size in KB
$allowed_extensions = array("txt", "rst", "md", "pdf", "odt", "doc", "docx", "jpg", "gif", "png");

$errors = "";

//Validations
if($size_of_uploaded_file > $max_allowed_file_size )
{
  $errors .= "\n Size of file should be less than $max_allowed_file_size";
}

//------ Validate the file extension -----
$allowed_ext = false;
for($i=0; $i<sizeof($allowed_extensions); $i++)
{
  if(strcasecmp($allowed_extensions[$i],$type_of_uploaded_file) == 0)
  {
    $allowed_ext = true;
  }
}

if(!$allowed_ext)
{
  $errors .= "\n The uploaded file is not supported file type. ".
  " Only the following file types are supported: ".implode(',',$allowed_extensions);
}

//copy the temp. uploaded file to uploads folder
$upload_folder = "/home/jasonkmoore/moorepants.info/eme185-uploads/";
if (!file_exists($upload_folder)) {
    mkdir($upload_folder, 0777, true);
}
$shortened_title = substr($title, 0, 10);
$shortened_org = substr($org, 0, 10);
$custom_file_name = mb_ereg_replace("([^\w\d\-_\[\]\(\)])", '_',
  $name."-".$shortened_org."-".$shortened_title);
$attachment_filename = $custom_file_name.'.'.$type_of_uploaded_file;
$path_of_uploaded_file = $upload_folder . $attachment_filename;
$tmp_path = $_FILES["file"]["tmp_name"];

if(is_uploaded_file($tmp_path))
{
  if(!copy($tmp_path,$path_of_uploaded_file))
  {
    $errors .= '\n error while copying the uploaded file';
  }
} else {
  $attachment_filename = "";
}

// build csv file

$csv_file = $upload_folder . 'proposals.csv';

if (file_exists($csv_file)) {
  $file = new SplFileObject($csv_file, 'a');
} else {
  $file = new SplFileObject($csv_file, 'w');
}
$file->fputcsv(array(
  $timestamp,
  $name,
  $org,
  $email_address,
  $phone,
  $title,
  preg_replace("/[\n\r]/", '\n', $message),
  $attachment_filename
));
$file = null;

// Create the email and send the message
$to = 'jkm@ucdavis.edu';
$to_name = "Jason K. Moore";
$from = 'noreply@ucdavis.edu';
$from_name = 'MECH-CAP Website';
$email_subject = "[MECH-CAP Proposal]:  $name, $org";
$email_body = <<<EOT
You have received a new message from your website contact form.\r\n\r\n
Here are the details:\r\n\r\n
Name: $name\r\n\r\n
Organization: $org\r\n\r\n
Email: $email_address\r\n\r\n
Phone: $phone\r\n\r\n
Title: $title\r\n\r\n
Attachment: $attachment_filename\r\n\r\n
Message:\n$message
EOT;

require 'PHPMailerAutoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;
// Set PHPMailer to use the sendmail transport
$mail->isSendmail();
$mail->ContentType = 'text/plain';
$mail->IsHTML(false);
//Set who the message is to be sent from
$mail->setFrom($from, $from_name);
//Set an alternative reply-to address
$mail->addReplyTo($email_address, $name);
//Set who the message is to be sent to
$mail->addAddress($to, $to_name);
//Set the subject line
$mail->Subject = $email_subject;
$mail->Body = $email_body;
//Attach an image file
$mail->addAttachment($path_of_uploaded_file);
//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}

// Send a confirmation email to submitter.

$conf_email_body = <<<EOT
Thank you for submitting the proposal:\r\n
$title\r\n
We will review the proposal and touch base with you if we have any questions.
Furthermore, you will receive an email confirming if your proposal is accepted
for selection by the students. Please feel free to contact us with any
questions.\r\n\r\n
Sincerely,\r\n
Jason K. Moore
Steven A. Velinsky
EOT;

//Create a new PHPMailer instance
$conf_mail = new PHPMailer;
// Set PHPMailer to use the sendmail transport
$conf_mail->isSendmail();
$conf_mail->ContentType = 'text/plain';
$conf_mail->IsHTML(false);
$conf_mail->setFrom($to, $to_name);
$conf_mail->addAddress($email_address, $name);
$conf_mail->Subject = "Your UCD MECH-CAP proposal has been received.";
$conf_mail->Body = $conf_email_body;
if (!$conf_mail->send()) {
    echo "Mailer Error: " . $conf_mail->ErrorInfo;
} else {
    echo "Message sent!";
}

return true;
?>
