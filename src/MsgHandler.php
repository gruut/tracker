<?php

function saveInfo($data, $kinds){
    $data_path = '';
    if($kinds == "merger"){
        $data_path = "../merger_info/". $data['mID']. ".dat";
    }
    else{
        $data_path = "../se_info/". $data['seID']. ".dat";
    }
    file_put_contents($data_path, serialize($data));
}

function getAllMergerInfo(){
    $merger_list = array();
    $files = glob("../merger_info/*.dat");
    foreach($files as $merger) {
        $info = unserialize(file_get_contents($merger));
        array_push($merger_list, $info);
    }
    return $merger_list;
}

function getAllSeInfo(){
    $se_list = array();
    $files = glob("../se_info/*.dat");
    foreach($files as $se){
        $info = unserialize(file_get_contents($se));
        array_push($se_list, $info);
    }
    return $se_list;
}

function getSpecificInfo($id, $kinds){
    $file = '';
    if(kinds == "merger"){
        $file = "../merger_info";
    }
    else{
        $file = "../se_info";
    }
    $file .= ($id.".dat");
    $info = file_get_contents(unserialize($file));
    return $info;
}

function checkMergerJson($json_data){
    if($json_data === false){
        return false;
    }
    if(isset($json_data['mID']) && 
        isset($json_data['ip']) && 
        isset($json_data['port']) && 
        isset($json_data['mCert'])){
        
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
