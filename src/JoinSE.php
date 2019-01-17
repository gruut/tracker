<?php
require_once('MsgHandler.php');

if(empty($_POST)){
    return;
}

$post_str = file_get_contents("php://input");
$json_data = json_decode($post_str,true);

$merger_list = getAllMergerInfo();

jsonResponse($merger_list);
saveInfo($json_data, "se");