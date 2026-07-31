<?php
function fp_auth($login, $password){ //авторизация в форпосте

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://cam.uhome.kz/api/Login',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => "Login=$login&Password=$password",
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    if(!$response){
        $data['result']='error';
        return $data;
    }
    $data = json_decode($response, true);
    if(isset($data['LoginErrorCode'])){
        $data['result']='error';
        return $data;
    }
    $data['result']='success';
    return $data; //в ответе LoginSessionID это ид сессии
}

function fp_archive_url($data){ //получаем ссылку на архив
    $camera_id=$data['camera_id'];
    $session_id=$data['session_id'];
    $ts=$data['ts'];
    $tz='18000';
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://cam.uhome.kz/api/GetTranslationURL',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => "SessionID=$session_id&CameraID=$camera_id&ts=$ts&tz=$tz&Format=HLS",
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    if(!$response){
        $data['result']='error';
        return $data;
    }
    $data = json_decode($response, true);
    if(isset($data['Error'])){
        $data['result']='error';
        return $data;
    }
    $data['result']='success';
    return $data; //в ответе URL это адрес трансляции

}

function fp_online_url($data){ //получаем ссылку на онлайн
    $camera_id=$data['camera_id'];
    $session_id=$data['session_id'];
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://cam.uhome.kz/api/GetTranslationURL',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => "SessionID=$session_id&CameraID=$camera_id&Format=HLS",
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    if(!$response){
        $data['result']='error';
        return $data;
    }

    $data = json_decode($response, true);
    if(isset($data['Error'])){
        $data['result']='error';
        return $data;
    }
    $data['result']='success';
    return $data; //в ответе URL это адрес трансляции

}

function fp_download_url($data){ //Получение ссылки на скачку архива
    $camera_id=$data['camera_id'];
    $session_id=$data['session_id'];
    $ts=$data['ts'];
    $tz='18000';
    $duration = $data['duration']; //Продолжительность записи в минутах
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://cam.uhome.kz/api/GetDownloadURL',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => "SessionID=$session_id&CameraID=$camera_id&Duration=$duration&TS=$ts&TZ=$tz",
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    if(!$response){
        $data['result']='error';
        return $data;
    }
    $data = json_decode($response, true);
    if(isset($data['Error'])){
        $data['result']='error';
        return $data;
    }
    $data['result']='success';
    return $data; //в ответе URL это адрес трансляции
}



function fp_get_events($data){ //Получение ссылки на скачку архива
    $camera_id=$data['camera_id'];
    $session_id=$data['session_id'];
    $upper_date=$data['upper_date'];
    $lower_date=$data['lower_date'];
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://cam.uhome.kz/api/GetEvents',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => "SessionID=$session_id&CameraID=$camera_id&UpperDate=$upper_date&LowerDate=$lower_date",
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    if(!$response){
        $data['result']='error';
        return $data;
    }
    $data = json_decode($response, true);
    if(isset($data['Error'])){
        $data['result']='error';
        return $data;
    }
    return $data; //в ответе URL это адрес трансляции
}

