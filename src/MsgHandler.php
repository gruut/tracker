<?php

function saveInfo($data, $kinds){
    if($kinds == "merger"){
        $prefix = "../merger_info/";
        $info_path = $prefix. $data['mID']. ".dat";
        $block_hgt_path = $prefix. "blcok_hgt/hgt_".$data['mID']. ".dat";
        
        $info_data = array(
            'mID' => $data['mID'],
            'ip' => $data['ip'],
            'port'=> $data['port'],
            'mCert'=> $data['mCert'],
        );

        $block_hgt_data = array(
            'mID' => $data['mID'],
            'time' => $data['time'],
            'hgt' => $data['hgt']
        );
        file_put_contents($info_path, serialize($info_data));
        file_put_contents($block_hgt_path, serialize($block_hgt_data));
    }
    else{
        $info_path = "../se_info/". $data['seID']. ".dat";
        file_put_contents($info_path, serialize($data));
    }
}

function getAllMergerInfo(){
    $merger_list = array();
    $it = new DirectoryIterator("glob://../merger_info/*.dat");
    foreach($it as $merger) {
        $merger_info = unserialize(file_get_contents($merger));

        $block_hgt_path = "../merger_info/block_hgt/hgt_". $merger->getFileName();
        $hgt_info = unserialize(file_get_contents($block_hgt_path));
        
        $merger_info['time'] = $hgt_info['time'];
        $merger_info['hgt'] = $hgt_info['hgt'];

        array_push($merger_list, $merger_info);
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
