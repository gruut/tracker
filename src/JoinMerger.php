<?php
require_once('../utils/mysql-lib.php');
require_once('../utils/MsgHandler.php');

if(empty($_POST)){
    return;
}

$json_data = json_decode($_POST['message'],true);

if(!checkMergerJson($json_data)){
    header("Content-type: application/json");
    http_response_code(500);
    echo $json_data;
    return;
}

$result_arr = mysql_open_($json_data);

$merger_list = mysql_read_all('merger');
$se_list = mysql_read_all('se');

jsonResponse($merger_list, $se_list);

$check_exist = mysql_read_urecord_('merger', 'mID', $result_arr['mID']);
if(!$check_exist){
    mysql_insert_('merger', $result_arr);
}
else{
    mysql_update_urecordm_('merger', $result_arr, 'mID', $result_arr['mID']);
}

mysql_close();

