<?php
function setPage($chat_id, $data){
    file_put_contents("users/".$chat_id."page.txt", $data);
}
function getPage($chat_id){
    $myVar = file_get_contents("users/" . $chat_id . "page.txt");
    return $myVar;
}
function setMass($chat_id, $data){
    file_put_contents("users/".$chat_id."mass.txt", $data);
}
function getMass($chat_id){
    $myVar = file_get_contents("users/" . $chat_id . "mass.txt");
    return $myVar;
}
function setPhone($chat_id, $data){
    file_put_contents("users/".$chat_id."phone.txt", $data);
}
function getPhone($chat_id){
    $myVar = file_get_contents("users/" . $chat_id . "phone.txt");
    return $myVar;
}
function setLatitude($chat_id, $data){
    file_put_contents("users/".$chat_id."latitude.txt", $data);
}
function getLatitude($chat_id){
    $myVar = file_get_contents("users/" . $chat_id . "latitude.txt");
    return $myVar;
}
function setLongitude($chat_id, $data){
    file_put_contents("users/".$chat_id."longitude.txt", $data);
}
function getLongitude($chat_id){
    $myVar = file_get_contents("users/" . $chat_id . "longitude.txt");
    return $myVar;
}