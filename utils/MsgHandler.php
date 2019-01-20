<?php
function checkChainInfo($json_data){
    if($json_data === false){
        return false;
    }
    return  isset($json_data['mID'])&&
            isset($json_data['time'])&&
            isset($json_data['hgt'])&&
            isset($json_data['bID'])&&
            isset($json_data['prevbID'])&&
            isset($json_data['prevHash']);
}

function checkMergerInfo($json_data){
    if($json_data === false){
        return false;
    }
    $check = checkChainInfo($json_data);
    $check &= ( isset($json_data['ip']) &&
                isset($json_data['port'])&&
                isset($json_data['mCert']));
    return $check;
}

function jsonResponse($merger_list, $se_list){
    $json_data = array(
        "merger" => $merger_list,
        "se" => $se_list
    );
    
    header("Content-type: application/json");
    echo json_encode($json_data);
}
