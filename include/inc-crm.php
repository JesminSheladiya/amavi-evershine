<?php
    $gclid = isset($_POST["gclid"]) ? $_POST["gclid"] : "";
    $keyword = isset($_POST["keyword"]) ? $_POST["keyword"] : "";
    $utm_term_val = isset($_POST["utm_term"]) ? $_POST["utm_term"] : "";
    $utm_placement = isset($_POST["utm_placement"]) ? $_POST["utm_placement"] : "";
    $utm_device = isset($_POST["utm_device"]) ? $_POST["utm_device"] : "";
    $utm_subsource = isset($_POST["utm_subsource"]) ? $_POST["utm_subsource"] : "";
    $utm_ad_group = isset($_POST["utm_ad_group"]) ? $_POST["utm_ad_group"] : "";
    $utm_ad = isset($_POST["utm_ad"]) ? $_POST["utm_ad"] : "";
    $utm_channel = isset($_POST["utm_channel"]) ? $_POST["utm_channel"] : "";

    $utm_campaign = isset($_POST["utm_campaign"]) && $_POST["utm_campaign"] != ""
    ? urldecode(str_replace("+", " ", $_POST["utm_campaign"]))
    : "Amavi Evershine";

    $timestamp = date('YmdHis');
    $date = date("d-m-Y");

    $post_fields = json_encode([
        "firstName" => $fname,
        "lastName" => "",
        "email" => $email,
        "mobilePhone" => "+91$phone",
        "leadDate" => $date,
        "comments" => "Config: $config, Timeline: $timeline",
        "originFrom" => "Social Media",
        "product" => 'Amavi Evershine',
        "campaign" => $utm_campaign,
        "isUpdatefromUIDate" => false,
        "isImported" => true,
        "DumpdataObjectId" => $timestamp,
        "tenantId" => 264,
        "utm_source" => isset($_POST["utm_source"]) ? $_POST["utm_source"] : "",
        "utm_medium" => isset($_POST["utm_medium"]) ? $_POST["utm_medium"] : "",
        "gclid" => $gclid,
        "keyword" => $keyword,
        "utm_term" => $utm_term_val,
        "utm_placement" => $utm_placement,
        "utm_device" => $utm_device,
        "utm_subsource" => $utm_subsource,
        "utm_ad_group" => $utm_ad_group,
        "utm_ad" => $utm_ad,
        "utm_channel" => $utm_channel
    ]);

    $log_directory = 'logs';
    if (!is_dir($log_directory)) {
        mkdir($log_directory, 0777, true);
    }

    $log_file = $log_directory . '/crm' . date('Y-m') . '.log';
    $log_entry = "[" . date("Y-m-d H:i:s") . "] " .
                 "Name: $fname | Phone: $phone | Email: $email | " .
                 "Product: Amavi Evershine | Campaign: $utm_campaign" . PHP_EOL;
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://farvisioncloud.com/sfasync/api/syncleads/website',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $post_fields,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json'
        ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    generatelogs("thirdpartyapi", $post_fields, $localpath);
    generatelogs("thirdpartyapi", $response, $localpath);
