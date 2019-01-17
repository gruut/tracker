<?php
require_once('MsgHandler.php');

if(empty($_POST)){
    return;
}

$post_str = file_get_contents("php://input");
$json_data = json_decode($post_str,true);

if(!checkMergerJson($json_data)){
    header("Content-type: application/json");
    http_response_code(500);
    echo $json_data;
    return;
}

$merger_list = getAllMergerInfo();
$se_list = getAllSeInfo();

jsonResponse($merger_list, $se_list);
saveMergerInfo($json_data);
