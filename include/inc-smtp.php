<?php
    require_once("phpmailer/class.phpmailer.php");
    require_once("phpmailer/PHPMailerAutoload.php");
    $mail = new PHPMailer();

    if($issmtp == "1"){
        $mail->isSMTP();
    }

    $mail->Host = $emailhostname;

    if($smtpdebug != ""){
        $mail->SMTPDebug  = $smtpdebug;
    } else {
        $mail->SMTPDebug  = 0;
    }

    $mail->SMTPAuth = true;
    $mail->Username = $emailusername;
    $mail->Password = $emailuserpassword;
    $mail->SMTPSecure = $emailsmtpsecure;
    $mail->Port = $emailport;
