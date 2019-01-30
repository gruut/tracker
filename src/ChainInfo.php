<?php
require_once('../utils/mysql-lib.php');
require_once('../utils/MsgHandler.php');

if(empty($_POST)){
    return;
}

$json_data = json_decode($_POST['message'], true);

$merger_sig = $json_data['mSig'];
unset($json_data['mSig']);

if(!isset($json_data['msgID'])){
    return;
}

$msg_id = intval($json_data['msgID']);
if(!checkMsgID('CHAIN_INFO', $msg_id)){
    return;
}

unset($json_data['msgID']);

if(!checkChainInfo($json_data)){
    header("Content-type: application/json");
    http_response_code(500);
    echo $json_data;
    return;
}

$result_arr = mysql_open_($json_data);

$search_record = array(
    "mID" => $json_data['mID'],
    "cID" => $json_data['cID']
);

$check_exist = mysql_read_('merger', $search_record);
if(!$check_exist){
    header("Content-type: application/json");
    http_response_code(500);
    echo $json_data;
}
else{
    unset($result_arr['mID']);
    unset($result_arr['cID']);
    mysql_update_('merger', $result_arr, $search_record);
}
mysql_close_();
