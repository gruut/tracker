<?php
require_once('../utils/mysql-lib.php');
require_once('../utils/MsgHandler.php');

if(empty($_POST)){
    return;
}
$result_arr = mysql_open_();

$json_data = json_decode($_POST['message'], true);

if(!checkChainInfo($json_data)){
    header("Content-type: application/json");
    http_response_code(500);
    echo $json_data;
    return;
}

$check_exist = mysql_read_urecord_('merger', 'mID', $result_arr['mID']);
if(!$check_exist){
    header("Content-type: application/json");
    http_response_code(500);
    echo $json_data;
}
else{
    mysql_update_urecordm_('merger', $result_arr, 'mID', $result_arr['mID']);
}

mysql_close_();
