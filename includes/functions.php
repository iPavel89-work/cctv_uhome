<?php
//авторизация
function auth($account, $password)
{
    include 'conf.php';
    $connection = connection($db_host, $db_user, $db_passwd, $uhome_db);
    if(!$connection){
        return false;
    }
    $query = "select id,login, password, role, houses,fullname,active, session from cctv_users where login = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "s", $account);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($res) == 0){
        mysqli_close($connection);
        return false;
    }
    $row = mysqli_fetch_assoc($res);
    if(password_verify($password, $row['password'])===false){
        mysqli_close($connection);
        return false;
    }
    unset($row['password']);
    $row['house_list']=explode(',', $row['houses']);
    return $row;
}


//запросы в БД
function executeQuery($query, $params = array()) { // выполнение sql запросов
    include 'conf.php';
    $connection = connection($db_host, $db_user, $db_passwd, $uhome_db);
    if (!$connection) {
        return false;
    }

    $stmt = mysqli_prepare($connection, $query);
    if ($stmt === false) {
        mysqli_close($connection);
        return false;
    }

    // если есть параметры
    if (!empty($params)) {
        // определяем типы параметров автоматически
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) $types .= 'i';
            elseif (is_double($param)) $types .= 'd';
            elseif (is_null($param)) $types .= 's';
            else $types .= 's';
        }
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    try {
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($connection);
            return false;
        }
    } catch (mysqli_sql_exception $e) {
        mysqli_stmt_close($stmt);
        mysqli_close($connection);
        if ($e->getCode() == 1062) {
            return false;
        }
        return false;
    }


    $res = @mysqli_stmt_get_result($stmt);

    if ($res) { // SELECT
        $result = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $result[] = $row;
        }
        mysqli_stmt_close($stmt);
        mysqli_close($connection);
        return $result;
    } else { // INSERT, UPDATE, DELETE
        $affected = mysqli_stmt_affected_rows($stmt);
        $insertId = mysqli_insert_id($connection);
        mysqli_stmt_close($stmt);
        mysqli_close($connection);
        return $insertId > 0 ? $insertId : $affected;
    }
}
function debugQuery($query, $params) {  // вывод запроса sql  для дебага
    foreach ($params as $param) {
        $param = is_numeric($param) ? $param : "'" . addslashes($param) . "'";
        $query = preg_replace('/\?/', $param, $query, 1);
    }
    return $query;
}


//подключение к БД
function connection($host, $user, $passwd, $db){  //подключение к БД
    $link = mysqli_connect($host, $user, $passwd, $db);
    if (!$link) {
        mysqli_close($link);
        return false;
    }
    mysqli_set_charset($link, "utf8mb4");
    return $link;
}

//функция очистки строк
function sanitize_text($data): string
{
    if(!is_string($data)){
        return '';
    }
    $data = preg_replace("/('|;|--)/", '', $data);  // минимальная защита от SQL-инъекций
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8'); // защита от XSS при выводе
}


function get_permitions($user_id)// получаем права
{
    $sql = "select * from cctv_perm where user_id = ?";
    $params = array($user_id);
    $result = executeQuery($sql,$params);
    if($result ===false){
        return array();
    }
    return $result[0];
}
function get_session_version($user_id){ //проверяем версию сессии
    $sql = "select session from cctv_users where id = ?";
    $params = array($user_id);
    $result = executeQuery($sql,$params);
    if($result ===false){
        return array();
    }
    return $result[0];
}

//Проверка отправленной каптчи
function check_captcha($cap_token){
    include "conf.php";
    $curl = curl_init();
    $data['secret']=$captcha_key;
    $data['response']=$cap_token;
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://captcha.uhome.kz/$captcha_site/siteverify",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    if(!$response){
        $result['error']='error';
        return $result;
    }
    return json_decode($response,true);
}


function get_ua($ua){ //получаем читаемый юзер агент

    $platform = 'Other';
    $browser  = 'Other';
    $ver      = '';

    if (strpos($ua, 'Windows NT') !== false) $platform = 'Windows';
    elseif (strpos($ua, 'Android') !== false) $platform = 'Android';
    elseif (strpos($ua, 'iPhone') !== false || strpos($ua, 'iPad') !== false) $platform = 'iOS';
    elseif (strpos($ua, 'Mac OS X') !== false) $platform = 'macOS';
    elseif (strpos($ua, 'Linux') !== false) $platform = 'Linux';

    if (preg_match('/Edg\/(\d+)/', $ua, $m)) {
        $browser = 'Edge';
        $ver = $m[1];
    } elseif (preg_match('/Chrome\/(\d+)/', $ua, $m)) {
        $browser = 'Chrome';
        $ver = $m[1];
    } elseif (preg_match('/Firefox\/(\d+)/', $ua, $m)) {
        $browser = 'Firefox';
        $ver = $m[1];
    } elseif (preg_match('/Version\/(\d+).+Safari/', $ua, $m)) {
        $browser = 'Safari';
        $ver = $m[1];
    }

    $ua_short = "$platform / $browser$ver";
    return $ua_short;
}

function cameras_info($house_id){ //получение информации о пользователе
    $sql = "select i_id,ip,login,password,name,fp_login,fp_pass,camera_id,url, api,server,device_t from intercoms where house_id = ?
            and i_id not in(select camera_id from private_cameras)
            order by cam_number";
    $params =array($house_id);
    $result = executeQuery($sql, $params);
    if(empty($result)){
        return false;
    }
    $data = array();
    $intercoms =array();
    $cameras = array();
    $gates = array();
    $forpost = array();
    $camera_list=array();
    foreach($result as $key=> $row){
        $camera_list[]=$row['i_id'];
        if($row['device_t']=='intercom'){
            //$intercoms[] = $row;
            $intercoms[$row['i_id']] = array('name' => $row['name'],
                'cameraID' => $row['camera_id'],
                'screen'=>str_replace("rtsp", "jh", $row['url']),
                'i_id'=>$row['i_id'],
                'fp_login'=>$row['fp_login'],
                'fp_pass'=>$row['fp_pass']
            );

        }
        if($row['device_t']=='camera'){
            $cameras[$row['i_id']]=array('name' => $row['name'],
                'cameraID' => $row['camera_id'],
                'screen'=>str_replace("rtsp", "jh", $row['url']),
                'i_id'=>$row['i_id'],
                'fp_login'=>$row['fp_login'],
                'fp_pass'=>$row['fp_pass']
            );
        }
        if($row['device_t']=='gate'){
            $gates[$row['i_id']]=array('name' => $row['name'],
                'cameraID' => $row['camera_id'],
                'screen'=>str_replace("rtsp", "jh", $row['url']),
                'i_id'=>$row['i_id'],
                'fp_login'=>$row['fp_login'],
                'fp_pass'=>$row['fp_pass']
            );
        }
    }
    $data['intercom'] = $intercoms;
    $data['camera'] = $cameras;
    $data['gate'] = $gates;
    $data['forpost']=$forpost;
    $data['camera_list']=$camera_list;
    //echo json_encode($data,JSON_UNESCAPED_UNICODE,JSON_PRETTY_PRINT);
    return $data;

}