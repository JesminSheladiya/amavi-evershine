<?php
include_once("../inc-global.php");
require_once __DIR__ . '/logger.php';

if (isset($_POST) && !empty($_POST) && $_POST['contact'] == "" && $_POST['site_url'] == $url) {

    $fname = (isset($_POST['fullname'])) ? mysqli_real_escape_string($connection, $_POST['fullname']) : '';
    $lname = '';
    $email = (isset($_POST['email'])) ? mysqli_real_escape_string($connection, $_POST['email']) : '';
    $phone = (isset($_POST['phone'])) ? mysqli_real_escape_string($connection, $_POST['phone']) : '';
    $config = (isset($_POST['config'])) ? mysqli_real_escape_string($connection, $_POST['config']) : '';
    $timeline = (isset($_POST['timeline'])) ? mysqli_real_escape_string($connection, $_POST['timeline']) : '';
    $message = '';
    $formtype = 'enquire';
    $random_val = (isset($_POST['random_val'])) ? mysqli_real_escape_string($connection, $_POST['random_val']) : '';
    $utm_campaign = (isset($_POST['utm_campaign'])) ? mysqli_real_escape_string($connection, $_POST['utm_campaign']) : '';

    $name = $fname;

    $leadslogs = $_POST;

    $leadslogs['ip_address'] = getIPAddress();
    generatelogs("leads", $leadslogs, $localpath);

    $errorStatus = 0;
    $errmsg = '';

    if ($name == "") {
        $errorStatus = 1;
        $errmsg .= 'Full Name is required.';
    } else if (!preg_match("/^[A-Za-z\-'., ]+$/", $name)) {
        $errorStatus = 1;
        $errmsg .= 'Enter a valid Name. Only Alphabets accepted. ';
    }

    if ($phone == "") {
        $errorStatus = 1;
        $errmsg .= 'Mobile Number is required. ';
    } else if (!ctype_digit($phone)) {
        $errorStatus = 1;
        $errmsg .= 'Mobile Number should be numeric. ';
    } else if (strlen($phone) != 10) {
        $errorStatus = 1;
        $errmsg .= 'Mobile Number should contain 10 digits. ';
    } else if (!preg_match("/^[0]?[6789]\d{9}$/", $phone)) {
        $errorStatus = 1;
        $errmsg .= 'Provide proper mobile number. ';
    }

    if ($email != "" && !preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email)) {
        $errorStatus = 1;
        $errmsg .= 'Enter a valid Email Address.';
    }

    if ($errorStatus == 0) {

        if (in_array($random_val, $a)) {
            $sql = "INSERT INTO leads (fname,lname,email,phone,config,timeline,formtype,message,random_val,ip_address,created_at)
            values('$fname','$lname','$email','$phone','$config','$timeline','$formtype','$message','$random_val','" . getIPAddress() . "','$currentimestamp')";
        } else {
            $sql = "INSERT INTO junkleads (fname,lname,email,phone,config,timeline,formtype,message,random_val,ip_address,created_at)
            values('$fname','$lname','$email','$phone','$config','$timeline','$formtype','$message','$random_val','" . getIPAddress() . "','$currentimestamp')";
        }

        if ($connection->query($sql)) {
            generatelogs("dbtransaction", $_POST, $localpath);
            require $crmleadsintegrate;

            $sendEmail = true;
            if ($sendEmail == true) {
                if ($mode != 'test') {
                    echo '<script>window.location="' . $thankyou . '?reg=success&name=' . urlencode($name) . '&phone=' . $phone . '&email=' . urlencode($email) . '&formtype=' . $formtype . '&config=' . urlencode($config) . '&timeline=' . urlencode($timeline) . '"</script>';
                } else {
                    require $leadsendmail;
                    echo 'Success<br/><br/>';
                    echo $subject . '<br/><br/>';
                    echo $body;
                }
            } else {
                if ($mode != 'test') {
                    echo '<script>window.location="' . $siteurl . '?reg=email-error"</script>';
                } else {
                    require $leadsendmail;
                    echo 'Email Fails<br/><br/>';
                    echo $mail->ErrorInfo . '<br/><br/>';
                    echo $subject . '<br/><br/>';
                    echo $body;
                }
            }
        } else {
            if ($mode != 'test') {
                echo '<script>window.location="' . $siteurl . '?reg=data-insertion-error"</script>';
            } else {
                echo 'Data fails to insert into DB<br/><br/>';
                echo $connection->error . "<br/><br/>";
            }
            generatelogs("dbtransaction", $sql . "->" . $connection->error, $localpath);
        }
    } else {
        if ($mode != 'test') {
            echo '<script>window.location="' . $siteurl . '?reg=validation-error"</script>';
        } else {
            echo '<script>window.location="' . $siteurl . '?reg=' . $errmsg . '"</script>';
        }
    }
} else {
    generatelogs("leads", "Attack: " . json_encode($_POST), $localpath);
    reportSpamAttach(json_encode($_POST));
    if ($mode != 'test') {
        echo '<script>window.location="' . $siteurl . '?reg=fail"</script>';
    } else {
        echo 'Fails totally<br/><br/>';
    }
}
