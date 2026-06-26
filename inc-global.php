<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();
@session_start();

$a = array(
    "12345678901234567890",
    "23456789012345678901",
    "34567890123456789012",
    "45678901234567890123",
    "56789012345678901234",
    "67890123456789012345",
    "78901234567890123456",
    "89012345678901234567",
    "90123456789012345678",
    "01234567890123456789"
);

date_default_timezone_set("Asia/Calcutta");
$currentimestamp = date('Y-m-d H:i:s');

$server = "local";

if ($server == "live") {
    $host = "localhost";
    $database = "amavi_project";
    $user = "amavi_user";
    $password = "password";
} else if ($server == "development") {
    $host = "localhost";
    $database = "amavi_project";
    $user = "amavi_user";
    $password = "password";
} else if ($server == "local") {
    $host = "127.0.0.1";
    $database = "amavi_project";
    $user = "root";
    $password = "root";
} else {
    echo "Global Configuration Error";
    exit();
}

$connection = new mysqli($host, $user, $password, $database, 8889) or die("Cannot Connect to Database. Please try again later.");

$query = "SELECT * FROM settings where status = 1 order by settings_group";
$res = $connection->query($query);

while ($row = mysqli_fetch_assoc($res)) {
    ${$row['settings_key']} = $row['settings_value'];
}

if ($site == "live") {
    $localpath = $_SERVER['DOCUMENT_ROOT'] . "/";
    $siteurl = $url;
} else {
    $localpath = $_SERVER['DOCUMENT_ROOT'] . $site;
    $siteurl = rtrim($url, '/') . $site . "/";
}

$mediaslug = "amavi-images";
$imageslug = "images";
$cssslug = "amavi-css";
$jsslug = "amavi-js";

$reg = isset($_GET["reg"]) ? $_GET["reg"] : "";
$mediaurl = $siteurl . $mediaslug . "/";

$title = $sitename;
$description = $sitename;
$keywords = $sitename;
$image = "seo";
$type = "website";
$canurl = $siteurl;

$imagesurl = $mediaurl . $imageslug . "/";
$cssurl = $mediaurl . $cssslug . "/";
$jsurl = $mediaurl . $jsslug . "/";
$thankyou = $siteurl . "thankyou/";
$emailimg = $imagesurl . "email/";
$emailbanner = $emailimg . "banner.jpg";

$utm = "";
$crmintegration = true;
$trackercodeintegration = true;

$localpath = rtrim($localpath, '/') . '/';

$smtp = $localpath . "include/inc-smtp.php";
$browserfunction = $localpath . "include/inc-browserfunction.php";
$leadinsert = $localpath . "include/inc-leads.php";
$leadsendmail = $localpath . "include/leads-sendmail.php";
$crmleadsintegrate = $localpath . "include/inc-crm.php";
$spamdetector = $localpath . "include/inc-spamdetector.php";

$captcha = false;
$math1 = rand(0, 9);
$math2 = rand(0, 9);
$captcharesult = (int)$math1 + (int)$math2;
$random_val = $a[array_rand($a)];

function generatelogs($filename, $message, $localpath)
{
    $filename = $localpath . "logs/" . $filename . ".txt";
    $fp = fopen($filename, "a");
    fwrite($fp, "\n\nType : " . $filename);
    fwrite($fp, "\nMessage : " . json_encode($message));
    fwrite($fp, "\nlogged at : " . date("Y-m-d H:i:s") . "\n");
    fwrite($fp, "=======================================================");
    fclose($fp);
}

function getIPAddress()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function reportSpamAttach($data)
{
    if ($data != "") {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://paypride.com/spam-detector/spam-detection.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'data' => $data,
                'site_url' => $url . ' & hit from :' . getIPAddress()
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
    }
}
