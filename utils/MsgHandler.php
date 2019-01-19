<?php
function checkMergerJson($json_data){
    if($json_data === false){
        return false;
    }
    if(isset($json_data['mID']) && 
        isset($json_data['ip']) && 
        isset($json_data['port']) && 
        isset($json_data['mCert']) &&
        isset($json_data['hgt']) &&
        isset($json_data['time'])){
        
        return true;
    }
    return false;
}

function jsonResponse($merger_list, $se_list){
    $json_data = array(
        "merger" => $merger_list,
        "se" => $se_list
    );
    
    header("Content-type: application/json");
    echo json_encode($json_data);
}
