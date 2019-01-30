<?php
require_once('../utils/mysql-lib.php');
require_once('../utils/MsgHandler.php');

if(empty($_POST)){
    return;
}

$json_data = json_decode($_POST['message'],true);

if(!isset($json_data['msgID'])){
    return;
}

$msg_id = intval($json_data['msgID']);
if(!checkMsgID('JOIN_MERGER', $msg_id)){
    return;
}

unset($json_data['msgID']);

if(!checkMergerInfo($json_data)){
    return;
}

$result_arr = mysql_open_($json_data);

$merger_list = mysql_read_merger_info_('cID', $json_data['cID']);
$se_list = mysql_read_all_('se');

if($merger_list === false)
    $merger_list = array();
if($se_list === false)
    $se_list = array();

jsonResponse($merger_list, $se_list);

$search_record = array(
    "mID" => $result_arr['mID'],
    "cID" => $result_arr['cID']
);

$check_exist = mysql_read_('merger', $search_record);
if(!$check_exist){
    mysql_insert_('merger', $result_arr);
}
else{
    mysql_update_('merger', $result_arr, $search_record);
}

mysql_close_();

