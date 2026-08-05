<?php
function open($data){ //Открываем ворота
    $ip = $data['camera_ip'];
    $xml = buildOpen($data['session_id'], $data['token']);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://$ip/ControlBarrier_I/ipc/coils/open",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$xml,

    ));

    $response = curl_exec($curl);

    curl_close($curl);
    if(!$response){
        $result = array('result' => 'error'  );
        return $result;
    }
    $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
    $json = json_encode($xml,JSON_UNESCAPED_UNICODE);
    $result = json_decode($json, true);
    $result['result']='success';
    return $result;

}

function get_session($data){
    $ip = $data['camera_ip'];


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://$ip/ReqLogin_I",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    if(!$response){
        $result = array('result' => 'fail'  );
        return $result;
    }
    $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
    $json = json_encode($xml,JSON_UNESCAPED_UNICODE);
    $result = json_decode($json, true);
    $result['result']='success';
    return $result;

}

function create_token($data){
    $login = $data['camera_login'];
    $password = $data['camera_password'];
    $nonce = $data['nonce'];
    $sessionId = $data['session_id'];
    $md5 = strtoupper(md5($password));
    $ip = $data['camera_ip'];
    $token = hash('sha512', $md5 . $nonce);

    $xml = buildDoLogin($login,$token,$sessionId);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://$ip/DoLogin_I",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $xml,
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    if(!$response){
        $result = array('result' => 'error'  );
        return $result;
    }
    $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
    $json = json_encode($xml,JSON_UNESCAPED_UNICODE);
    $result = json_decode($json, true);
    $result['result']='success';
    return $result;


}


function buildDoLogin(string $user, string $tokenHash, string $sessionId): string //формируем xml для авторизации
{
    return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<config version="1.7" xmlns="http://www.ipc.com/ver10"><Authentication type="authenticationMode">Token</Authentication><username type="string"><![CDATA[$user]]></username><password type="string"><![CDATA[$tokenHash]]></password></config><sessionId type="string"><![CDATA[$sessionId]]></sessionId>
XML;
}

function buildOpen($session,$token){
    return <<<XML
<sessionId type="string"><![CDATA[$session]]></sessionId><token type="string"><![CDATA[$token]]></token>
XML;
}

