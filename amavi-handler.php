<?php
error_reporting(0);
ini_set('display_errors', 0);

/* Database - same evershine_bliss */
$host = "localhost";
$user = "evershine_bliss";
$password = "Inso12345!@#$%";
$database = "evershine_bliss";

$connection = new mysqli($host, $user, $password, $database);
if ($connection->connect_error) {
    header('Content-Type: application/json');
    die(json_encode(['status' => 'error', 'message' => 'DB connection failed']));
}

/* Anti-spam honeypot check */
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['contact']) || $_POST['contact'] !== '') {
    http_response_code(400);
    exit;
}

/* Read fields - Amavi format */
$fname    = isset($_POST['fullname']) ? $connection->real_escape_string(trim($_POST['fullname'])) : '';
$email    = isset($_POST['email']) ? $connection->real_escape_string(trim($_POST['email'])) : '';
$phone    = isset($_POST['phone']) ? $connection->real_escape_string(trim($_POST['phone'])) : '';
$config   = isset($_POST['config']) ? $connection->real_escape_string(trim($_POST['config'])) : '';
$timeline = isset($_POST['timeline']) ? $connection->real_escape_string(trim($_POST['timeline'])) : '';

/* Validation */
$errors = [];
if (empty($fname)) $errors[] = "Full Name is required";
elseif (!preg_match("/^[A-Za-z\-'., ]+$/", $fname)) $errors[] = "Enter a valid Name";
if (empty($phone)) $errors[] = "Mobile Number is required";
elseif (!ctype_digit($phone) || strlen($phone) != 10 || !preg_match("/^[6789]\d{9}$/", $phone)) $errors[] = "Enter valid 10-digit mobile number";
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Enter a valid Email Address";

$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$created_at = date('Y-m-d H:i:s');

if (empty($errors)) {
    $sql = "INSERT INTO leads (fname,lname,email,phone,config,timeline,formtype,ip_address,created_at)
            VALUES ('$fname','','$email','$phone','$config','$timeline','amavi-enquiry','$ip','$created_at')";

    if ($connection->query($sql)) {
        /* Send email notification */
        $to = 'fenmac2@gmail.com';
        $subject = "Amavi Enquiry - $fname";
        $body = "Name: $fname\nPhone: $phone\nEmail: $email\nConfiguration: $config\nTimeline: $timeline\nIP: $ip\nTime: $created_at";
        $headers = "From: noreply@evershinebuilders.com";
        @mail($to, $subject, $body, $headers);

        /* CRM integration - Farvision Cloud */
        $crm_data = json_encode([
            "firstName"    => $fname,
            "lastName"     => "",
            "email"        => $email,
            "mobilePhone"  => "+91$phone",
            "leadDate"     => date("d-m-Y"),
            "comments"     => "Config: $config, Timeline: $timeline",
            "originFrom"   => "Social Media",
            "product"      => "Amavi Evershine",
            "campaign"     => "Amavi Evershine",
            "tenantId"     => 264,
            "DumpdataObjectId" => date('YmdHis')
        ]);

        $ch = curl_init('https://farvisioncloud.com/sfasync/api/syncleads/website');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $crm_data,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT        => 10
        ]);
        curl_exec($ch);
        curl_close($ch);

        echo '<script>window.location.href="https://evershinebuilders.com/thankyou/"</script>';
    } else {
        echo '<script>window.location.href="https://evershinebuilders.com/thankyou/?reg=error"</script>';
    }
} else {
    echo '<script>window.location.href="https://evershinebuilders.com/thankyou/?reg=' . urlencode(implode(', ', $errors)) . '"</script>';
}
$connection->close();
