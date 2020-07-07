<?php
session_start();
if(!isset($_SESSION['shortFileName']))die();
if($_SESSION['shortFileName']=="")die();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'nominering@ungpirat.org';
    $mail->Password = 'hidden';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;        
	$mail->CharSet = 'UTF-8';

    $mail->setFrom('nominering@ungpirat.org', 'Ung pirat');
    $mail->addAddress("kansli@ungpirat.se");
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);
    $mail->Subject = $_SESSION['shortFileName'];
	$mail->Body    = '<a href="http://kvitton.ungpirat.se/download.php?f='.$_SESSION['shortFileName'].'">Klicka här för att ladda ner filen</a>';
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    header("Location: https://ungpirat.se");
} catch (Exception $e) {
	echo 'Det gick tyvärr inte att skicka meddelandet. Skicka filen som skapades i förra steget manuellt till kansli@ungpirat.se och skriv gärna i mejlet att detta inte fungerar så fixar vi det.';
	//echo 'Mailer Error: ' . $mail->ErrorInfo;
}
?>