<?php
//file로 각 Data들을 관리.
const SE_PREFIX = "../se_info/";
const MERGER_PREFIX = "../merger_info/";
const BLOCK_HGT_PREFIX =  "../block_hgt/";

function saveMergerInfo($data){
    if(!is_dir(MERGER_PREFIX)){
        mkdir(MERGER_PREFIX);
    }
    $info_path = MERGER_PREFIX. $data['mID']. ".dat";
    
    if(!is_dir(BLOCK_HGT_PREFIX)){
        mkdir(BLOCK_HGT_PREFIX);
    }
    $block_hgt_path = BLOCK_HGT_PREFIX. "hgt_".$data['mID']. ".dat";
    
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

function saveSeInfo($data){
    if(!is_dir(SE_PREFIX)){
        mkdir(SE_PREFIX);
    }
    $info_path = SE_PREFIX. $data['seID']. ".dat";
    file_put_contents($info_path, serialize($data));
}

function getAllMergerInfo(){
    $merger_list = array();
    $it = new DirectoryIterator("glob://".MERGER_PREFIX."*.dat");
    foreach($it as $merger) {
        $merger_info_path = MERGER_PREFIX.$merger->getFileName();
        $merger_info = unserialize(file_get_contents($merger_info_path));
        
        $block_hgt_path = BLOCK_HGT_PREFIX."hgt_". $merger->getFileName();
        $hgt_info = unserialize(file_get_contents($block_hgt_path));
        
        $merger_info['time'] = $hgt_info['time'];
        $merger_info['hgt'] = $hgt_info['hgt'];

        array_push($merger_list, $merger_info);
    }
    return $merger_list;
}

function getAllSeInfo(){
    $se_list = array();
    $files = glob(SE_PREFIX."*.dat");
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
