<?php
require_once('../utils/mysql-lib.php');
require_once('../utils/MsgHandler.php');

if(empty($_POST)){
    return;
}

$result_arr = mysql_open_();

header("Content-type: application/json");
$json_data = json_decode($_POST['message'],true);

$merger_info = mysql_read_urecord_('merger', 'mID', $result_arr['mID']);
if(!$merger_info){
    http_response_code(500);
    $response_arr["flag"] = false;
    echo json_encode($response_arr);
}
else {
    echo json_encode($merger_info);
}
mysql_close_();

