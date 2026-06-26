<?php
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
        'data' => json_encode($_POST),
        'site_url' => $url
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
