<?php
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://' . $camera_ip . '/cgi-bin/intercom_cgi?action=maindoor',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 5,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: text/plain',
        'Authorization: Basic ' . $auth
    ),
));

$response = curl_exec($curl);

curl_close($curl);
$response = trim($response);
if ($response == "OK") {
    echo '{"result":"success"}';
}