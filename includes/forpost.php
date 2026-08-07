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
    $format=$data['format']??"H264";
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
        CURLOPT_POSTFIELDS => "SessionID=$session_id&CameraID=$camera_id&TS=$ts&TZ=$tz&Format=$format",
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
        CURLOPT_POSTFIELDS => "SessionID=$session_id&CameraID=$camera_id&Format=H264",
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
        $data=array();
        return $data;
    }
    $data = json_decode($response, true);
    if(isset($data['Error'])){
        $data=array();
        return $data;
    }
    return $data; //в ответе URL это адрес трансляции
}
function fp_close_translation($data){ //закрываем трансляцию
    $url=$data['url'];
    $session_id=$data['session_id'];

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://cam.uhome.kz/api/StopTranslation',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => "SessionID=$session_id&URL=$url",
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    if(!$response){
        $data=array();
        return $data;
    }
    $data = json_decode($response, true);
    if(isset($data['Error'])){
        $data=array();
        return $data;
    }
    return $data; //в ответе URL это адрес трансляции

}


//получаем информацию, есть ли запись в этот день
function fp_get_records($data){
    $session_id = $_SESSION['current_camera']['session_id'];
    $camera_id =$_SESSION['current_camera']['camera_id'];
    $date = $data['date'];
    $date_from = $date." 00:00:00";
    $date_to = $date. "23:59:59";
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://cam.uhome.kz/api/GetRecords',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => "SessionID=$session_id&CameraID=$camera_id&From=$date_from&To=$date_to",
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    if(!$response){
        $result=array("result"=>"error",'records'=>array());
        return $result;
    }
    $response = json_decode($response, true);
    if(isset($data['Error'])){
        $result=array("result"=>"error",'records'=>array());
        return $result;
    }
    $result['result']='success';
    $result['records']=$response;
    return $result; //в ответе массив с датами записи
}