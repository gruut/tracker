<?php

require_once('connectDB.php');

function dbQuote($value){
    $connection = connectDB();
    return mysqli_real_escape_string($connection, $value);
}

function dbQuery($query){
    $connection = connectDB();

    $result = mysqli_query($connection, $query);

    return $result;
}

function dbSelectMerger($mID){
    $query = "SELECT * FROM merger WHERE NOT(mID = '$mID')";
    $result = dbQuery($query);

    if($result === false){
        return false;
    }

    $result_arr = array();
    while($row = mysqli_fetch_assoc($result)){
        $rows = array();
        $rows['mID'] = $row['mID'];
        $rows['ip'] = $row['ip'];
        $rows['mCert'] = $row['mCert'];
        $rows['time'] = $row['time'];
        $rows['hgt'] = $row['hgt'];
        $rows['bID'] = $row['bID'];
        $rows['prevbID'] = $row['prevbID'];
        $rows['prevHash'] = $row['prevHash'];

        array_push($result_arr, $rows);
    }
    
    return $result_arr;
}

function dbSelectSE(){
    $query = "SELECT * FROM se";
    $result = dbQuery($query);
    
    if($result === false){
        return false;
    }

    $result_arr = array();
    while($row = mysqli_fetch_assoc($result)){
        $rows = array();
        $rows['seID'] = $row['seID'];
        $rows['ip'] = $row['ip'];
        $rows['port'] = $row['port'];
        $rows['seCert'] = $row['seCert'];

        array_push($result_arr, $rows);
    }
    return $result_arr;
}