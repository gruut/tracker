<?php
require_once('MsgHandler.php');
require_once('../utils/dbQuery.php');

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

$merger_id = dbQuote($json_data['mID']);
$merger_list = dbSelectMerger($merger_id);
$se_list = dbSelectSE();

$check_query = "SELECT * FROM merger WHERE mID = '$merger_id'";

$check_result = dbQuery($check_query);

$block_height = dbQuote($json_data['hgt']);
$block_id = dbQuote($json_data['bID']);
$prev_block_id = dbQuote($json_data['prevbID']);
$prev_block_hash = dbQuote($json_data['prevHash']);
$time = dbQuote($json_data['time']);

if(mysqli_num_rows($check_result) > 0){
    $update_query = "UPDATE 'merger' SET 
                     'hgt' = '$block_height',
                     'bID' = '$block_id',
                     'prevbID' = '$prev_block_id',
                     'prevHash' = '$prev_block_hash',
                     
                     'time' = '$time'";

    dbQuery($update_query);
}
else{
    $ip = dbQuote($json_data['ip']);
    $port = dbQuote($json_data['port']);
    $mCert = dbQuote($json_data['mCert']);

    $insert_query = "INSERT INTO merger (mID, ip, port, mCert, time, bID, prevbID, prevHash )
                    VALUES ('{$merger_id}', '{$ip}', '{$port}', '{$mCert}', '{$time}', '{$block_height}', '{$block_id}', '{$prev_block_id}', '{$prev_block_hash}' )";
    
    dbQuery($insert_query);
}

jsonResponse($merger_list, $se_list);

