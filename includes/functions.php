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

// получаем права
function get_permitions($user_id)
{
    $sql = "select * from cctv_perm where user_id = ?";
    $params = array($user_id);
    $result = executeQuery($sql,$params);
    if($result ===false){
        return array();
    }
    return $result[0];
}

//проверяем версию сессии
function get_session_version($user_id){
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

//получаем читаемый юзер агент
function get_ua($ua){

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


//получение информации о пользователе
function cameras_info($house_id){
    $sql = "select i_id,ip,login,password,name,fp_login,fp_pass,camera_id,url, api,server,device_t,model from intercoms where house_id = ?
            and i_id not in(select camera_id from private_cameras)
            order by cam_number";
    $params =array($house_id);
    $result = executeQuery($sql, $params);
    if(empty($result)){
        return array();
    }
    $data = array();
    $intercoms =array();
    $cameras = array();
    $gates = array();
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
                'fp_pass'=>$row['fp_pass'],
                'camera_ip'=>$row['ip'],
                'camera_login'=>$row['login'],
                'camera_password'=>$row['password'],
                'camera_type'=>$row['device_t'],
                'camera_model'=>str_replace(" ","_",$row['model']),

            );

        }
        if($row['device_t']=='camera'){
            $cameras[$row['i_id']]=array('name' => $row['name'],
                'cameraID' => $row['camera_id'],
                'screen'=>str_replace("rtsp", "jh", $row['url']),
                'i_id'=>$row['i_id'],
                'fp_login'=>$row['fp_login'],
                'fp_pass'=>$row['fp_pass'],
                'camera_ip'=>$row['ip'],
                'camera_login'=>$row['login'],
                'camera_password'=>$row['password'],
                'camera_type'=>$row['device_t'],
                'camera_model'=>str_replace(" ","_",$row['model']),
            );
        }
        if($row['device_t']=='gate'){
            $gates[$row['i_id']]=array('name' => $row['name'],
                'cameraID' => $row['camera_id'],
                'screen'=>str_replace("rtsp", "jh", $row['url']),
                'i_id'=>$row['i_id'],
                'fp_login'=>$row['fp_login'],
                'fp_pass'=>$row['fp_pass'],
                'camera_ip'=>$row['ip'],
                'camera_login'=>$row['login'],
                'camera_password'=>$row['password'],
                'camera_type'=>$row['device_t'],
                'camera_model'=>str_replace(" ","_",$row['model']),
            );
        }
    }
    $data['intercom'] = $intercoms;
    $data['camera'] = $cameras;
    $data['gate'] = $gates;
    $data['camera_list']=$camera_list;
    //echo json_encode($data,JSON_UNESCAPED_UNICODE,JSON_PRETTY_PRINT);
    return $data;

}

//получение списка событий
function intercom_get_events($data){
    include "dict.php";
    $date = $data['date'];
    $i_id= $data['i_id'];
    $sql = "select action, action_date,id from calls_log WHERE  i_id = ? and CAST(action_date AS date) = CAST(?  AS date)  order by action_date DESC";
    $params = array($i_id,$date);
    $result = executeQuery($sql,$params);
    if(empty($result)){
        return array();
    }
    foreach ($result as $row) {
        $timestamp = strtotime($row['action_date']);
        $action = $events_dictionary[$row['action']];

        $json[] = array("ID"=>$row['id'],"Time"=>$timestamp,"Duration"=>30,"EventSubjectID"=>$action);

    }
    return $json;
}

//Блок alrp
function get_alrp($data){  //получаем список alrp
    $i_id = $data['i_id'];
    $sql = "select a.id, c.id as customer_id , a.alrp, a.description, cam_id, alrp_group, c.fullname, c.full_address, c.flat, a.date_from, a.date_to from alrp a
            inner join customer c on (c.id = a.customer_id)
            where cam_id = ? and a.active =1";
    $params = array($i_id);
    $result = executeQuery($sql,$params);
    if(empty($result)){
        return array();
    }
    return $result;

}
function add_alrp($data){ //добавляем номер в бд
    $i_id = $data['i_id'];
    $lp = $data['lp'];
    $customer_id = $data['customer_id'];
    $group = $data['group'];
    $date_to = $data['date_to'];
    if($group==2){
        $date_to='2037-12-31 23:59:59';
    }
    $date_from = $data['date_from'];
    $description = $data['description'];
    //данные камеры
    $camera_ip =$data['camera_ip'];
    $camera_login =$data['camera_login'];
    $camera_password =$data['camera_password'];
    $auth = base64_encode($camera_login.":".$camera_password);
    $xml_data =  [
        '_attributes' => [
            'version' => '2.1.0',
            'xmlns' => 'http://www.ipc.com/ver10'
        ],

        'licensePlates' => [
            '_attributes' => [
                'type' => 'list',
                'maxCount' => 100,
                'count' => 1
            ],

            'item' => [
                [
                    'index' => 30,
                    'licensePlateNumber' => $lp,
                    'groupId' => $group,
                    'beginTime' => $date_from,
                    'endTime' => $date_to,
                    'carOwner' => $customer_id
                ]
            ]
        ]
    ];
    $xml = arrayToXml($xml_data);



    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://$camera_ip/AddLicensePlates",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$xml,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/xml',
            'Authorization: Basic '.$auth,
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
        $xml_response = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
    // LIBXML_NOCDATA — обязательно, иначе CDATA-блоки (deviceName, ip)
    // превратятся в пустые объекты вместо строк

    $json = json_encode($xml_response,JSON_UNESCAPED_UNICODE);
    $result = json_decode($json,true)['licensePlatesReply']['item']['errorCode']??'99';
    if($result!=0){
        $res['result']='error';
        return $res;
    }

    //добавляем в БД
    $sql = "insert into alrp set alrp = ?, cam_id = ?, customer_id = ?, description = ?, alrp_group = ?, date_to=?, date_from = ?";
    $params = array($lp,$i_id,$customer_id,$description,$group,$date_to,$date_from);
    $result = executeQuery($sql,$params);
    if(empty($result)){
        $res['result']='error';
        return $res;
    }
    $res['result']='success';
    return $res;

}


//редактирование номера
function edit_alrp($data){
    $i_id = $data['i_id'];
    $lp = $data['lp'];
    $customer_id = $data['customer_id'];
    $group = $data['group'];
    $date_to = $data['date_to'];
    if($group==2){
        $date_to='2037-12-31 23:59:59';
    }
    $date_from = $data['date_from'];
    $description = $data['description'];
    //данные камеры
    $camera_ip =$data['camera_ip'];
    $camera_login =$data['camera_login'];
    $camera_password =$data['camera_password'];
    $auth = base64_encode($camera_login.":".$camera_password);
    $xml_data = [
        '_attributes' => [
            'version' => '2.1.0',
            'xmlns' => 'http://www.ipc.com/ver10'
        ],

        'licensePlate' => [
            'licensePlateNumber' => $lp,
            'carOwner' => $customer_id,
            'groupId' => $group,
            'beginTime' => $date_from,
            'endTime' => $date_to,
        ]
    ];
    $xml = arrayToXml($xml_data);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://$camera_ip/ModifyLicensePlate",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$xml,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/xml',
            'Authorization: Basic '.$auth,
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $xml_response = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
    // LIBXML_NOCDATA — обязательно, иначе CDATA-блоки (deviceName, ip)
    // превратятся в пустые объекты вместо строк
    $json = json_encode($xml_response,JSON_UNESCAPED_UNICODE);
//    echo $json;
    $result = json_decode($json,true)['@attributes']['errorCode']??'99';
    if($result!=0){
        $res['result']='error';
        return $res;
    }
    //добавляем в БД
    $sql = "update alrp set  cam_id = ?, customer_id = ?, description = ?, alrp_group = ?, date_to=?, date_from = ? where alrp = ?";
    $params = array($i_id,$customer_id,$description,$group,$date_to,$date_from,$lp);
    $result = executeQuery($sql,$params);
    if(empty($result)){
        $res['result']='error';
        return $res;
    }
    $res['result']='success';
    return $res;



}

function remove_alrp($data){
    $i_id = $data['i_id'];
    $lp = $data['lp'];
    //данные камеры
    $camera_ip =$data['camera_ip'];
    $camera_login =$data['camera_login'];
    $camera_password =$data['camera_password'];
    $auth = base64_encode($camera_login.":".$camera_password);

    $xml_data =  [
        '_attributes' => [
            'version' => '2.1.0',
            'xmlns' => 'http://www.ipc.com/ver10'
        ],

        'deleteAction' => [
            'licensePlateNumber' => $lp
        ]
    ];
    $xml = arrayToXml($xml_data);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://$camera_ip/DeleteLicensePlate",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$xml,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/xml',
            'Authorization: Basic '.$auth,
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $xml_response = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
    // LIBXML_NOCDATA — обязательно, иначе CDATA-блоки (deviceName, ip)
    // превратятся в пустые объекты вместо строк
    $json = json_encode($xml_response,JSON_UNESCAPED_UNICODE);
//    echo $json;
    $result = json_decode($json,true)['@attributes']['errorCode']??'99';
    if($result!=0){
        $res['result']='error';
        return $res;
    }
    //Обновляем  БД
    $sql = "update alrp set  active = 0 where alrp = ? and cam_id = ?";
    $params = array($lp,$i_id);
    $result = executeQuery($sql,$params);
    if(empty($result)){
        $res['result']='error';
        return $res;
    }
    $res['result']='success';
    return $res;


}

//Конец блока alrp


//Конвертация xml
function arrayToXml(array $data, string $root = 'config'): string
{
    $xml = new SimpleXMLElement(
        '<?xml version="1.0" encoding="UTF-8"?><' . $root . '/>'
    );

    buildXml($xml, $data);

    return $xml->asXML();
}


function buildXml(SimpleXMLElement $xml, array $data): void
{
    foreach ($data as $key => $value) {

        // Атрибуты текущего узла
        if ($key === '_attributes') {
            foreach ($value as $attr => $attrValue) {
                $xml->addAttribute($attr, (string)$attrValue);
            }
            continue;
        }

        // Массив
        if (is_array($value)) {

            // Список элементов
            if (isListArray($value)) {

                foreach ($value as $item) {
                    $child = $xml->addChild($key);

                    if (is_array($item)) {
                        buildXml($child, $item);
                    } else {
                        $child[0] = htmlspecialchars((string)$item);
                    }
                }

            } else {

                $child = $xml->addChild($key);
                buildXml($child, $value);
            }

        } else {
            // Обычный текстовый элемент
            $xml->addChild(
                $key,
                htmlspecialchars((string)$value)
            );
        }
    }
}

function isListArray(array $array): bool
{
    return array_keys($array) === range(0, count($array) - 1);
}
//конец конвертации xml

//Получаем полный адрес по дому
function get_full_address($data){
    $hid = $data['hid'];


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://172.20.1.123:8011/get_full_address?id_building=$hid",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    if(!$response){
        $data=array();
        return $data;
    }

    $data = json_decode($response,true);
    return $data[0]['full_address'];
}

//получаем список клиентов дома
function get_customers($hid){
    $sql = "select id,  flat from customer where house_id = ?";
    $params = array($hid);
    $result = executeQuery($sql,$params);
    if(empty($result)){
        return array();
    }
    return $result;
}

function open_door($data){
    $i_id = $data['i_id'];
    //данные камеры
    $camera_ip =$data['camera_ip'];
    $camera_login =$data['camera_login'];
    $camera_password =$data['camera_password'];
    $camera_model =$data['camera_model'];
    $auth = base64_encode($camera_login.":".$camera_password);
    include "models/".$camera_model.".php";
    return $response_data;

}
function open_gate($data){
    $i_id = $data['i_id'];
    //данные камеры
    $camera_ip =$data['camera_ip'];
    $camera_login =$data['camera_login'];
    $camera_password =$data['camera_password'];
    $camera_model =$data['camera_model'];
    $auth = base64_encode($camera_login.":".$camera_password);
    include "models/".$camera_model.".php";
    $result = get_session($data);
    if($result['result']=="error"){
        return $result;
    }
    $session = $result['sessionId'];
    $nonce = $result['nonce'];
    $data['nonce']=$nonce;
    $data['session_id']=$session;
    $token = create_token($data);
    if($token['result']=="error"){
        return $token;
    }
    $data['session_id']=$token['sessionId'];
    $data['token']=$token['token'];
    $open = open($data);
    return $open;


}



//Согируем события
function log_data($data){
    $i_id = $data['i_id'];
    $action = $data['action'];
    $sql = "insert into calls_log set  action = ?, action_date = CURRENT_TIMESTAMP , i_id = ?";
    $params = array($action,$i_id);
    $result = executeQuery($sql, $params);
    if($result===false){
        return false;
    }
    return true;
}
