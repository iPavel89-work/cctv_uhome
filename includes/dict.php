<?php
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
    "gate"=>9990

];

$events_translate = [
    126 => "move",
    9999 => "open_door",
    9990 => "open_gate",
    9998 => "no_answer",
    9997 => "answered",
    9996 => "busy",
    9993 => "rfid",
    9992 => "code",
    9991 => "person"
];


$lp_group = [
    1=>"temporary_lp",
    2=>"white_lp",
    3=>"black_lp"
];
