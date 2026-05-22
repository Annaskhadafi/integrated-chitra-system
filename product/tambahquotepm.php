<?php
error_reporting(E_ALL);
require 'PHPMailer/src/PHPMailer.php' ;
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$note = $_POST['note'];
include "koneksi.php";
    //kirim email
    $mail =  new PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP(); 
    $mail->IsHTML(true);
    $mail->SMTPAuth 	= true; 
    $mail->Host 		= "chitraparatama.co.id";
    $mail->Port 		= 465;
    $mail->SMTPSecure 	= "ssl";
    $mail->Username 	= "icswarranty@chitraparatama.co.id"; //username SMTP
    $mail->Password 	= "icswarranty_2803;";   //password SMTP
	$mail->From    		= "icswarranty@chitraparatama.co.id"; //sender email
	$mail->FromName 	= "icswarranty";      //sender name
	$mail->AddAddress("dindasaputri2803@gmail.com", "Dinda Saputri");//recipient: email and name
	$mail->Subject  	=  "Warranty";
	$mail->Body     	=  "Name : $name<br>
	                        Phone : $phone<br>
	                        E-mail : $email<br><br>
	                        $note <br><br>
	                        <b><u>do not reply this email</u></b>";
	if($mail->Send()){ 
    	echo "<script> alert ('Email sent successfully !'); history.go(-1); </script>";
	}
	else{ 	
    	echo "<script> alert ('Email failed to sent'); history.go(-1); </script>"; 
	}
?>