<?php

// Роли
$user_role_dict = [
    "0" => "Admin",
];

// Иконки для обозначения типа устройства
$cam_type_dict = [
    "camera" => "bi-camera-video",
    "gate" => "bi-door-open",
    "intercom" => "bi-phone-landscape"
];

// Шлагбаум. Тип номера. Используется
$lp_group = [
    1=>"temporary_lp",
    2=>"white_lp",
    3=>"black_lp"
];

$events_dictionary = [ //коды событий домофонии
    "maindoor"=>9999,
    "NOANSWER"=>9998,
    "ANSWERED"=>9997,
    "BUSY"=>9996,
    "SOS"=>9995,
    "CHANUNAVAIL"=>9994,
    "RFID"=>9993,
    "CODE"=>9992,
    "person"=>9991,
    "gate"=>9990,
    "tmp_lp"=>9989,
    "white_lp"=>9988

];

$events_translate = [
    126 => "eventtype_move",
    9999 => "eventtype_open_door",
    9998 => "no_answer",
    9997 => "answered",
    9996 => "busy",
    9993 => "rfid",
    9992 => "code",
    9991 => "person",
    9990 => "eventtype_open_gate",
    9989 => "eventtype_lp_tl",
    9988 => "eventtype_lp_wl"
];


